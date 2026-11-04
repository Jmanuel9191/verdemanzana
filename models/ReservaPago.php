<?php

namespace app\models;

use app\helpers\FormatoHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Pago de una reserva (historial). Los pagos NO se borran: se anulan.
 *
 * El comprobante es obligatorio y se guarda en una carpeta PROTEGIDA
 * (@app/privado/comprobantes/<idreservas>/), que no se puede abrir desde
 * el navegador: se descarga solo desde el admin (o, más adelante, por el
 * cliente dueño de la reserva).
 *
 * @property int         $idpago
 * @property int         $idreservas
 * @property string      $fecha_pago
 * @property string      $monto
 * @property string      $forma_pago
 * @property string      $comprobante
 * @property string|null $comprobante_original
 * @property string|null $observacion
 * @property int         $estado
 * @property string|null $motivo_anulacion
 * @property int|null    $idadmin_anulo
 * @property string|null $fecha_anulacion
 * @property int|null    $idadmin_registro
 * @property string      $fecha_registro
 */
class ReservaPago extends ActiveRecord
{
    public const ESTADO_ANULADO = 0;
    public const ESTADO_VALIDO = 1;

    public const CARPETA = '@app/privado/comprobantes';
    public const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    public const TAMANO_MAX = 5 * 1024 * 1024; // 5 MB

    /** @var UploadedFile|null archivo del formulario */
    public $archivo;

    public static function tableName()
    {
        return 'reserva_pago';
    }

    public static function formasPago(): array
    {
        return [
            'efectivo'      => 'Efectivo',
            'transferencia' => 'Transferencia',
            'qr'            => 'QR',
            'deposito'      => 'Depósito',
            'tarjeta'       => 'Tarjeta',
        ];
    }

    public function rules()
    {
        return [
            [['forma_pago', 'observacion'], 'trim'],

            [['idreservas', 'fecha_pago', 'monto', 'forma_pago'], 'required'],
            [['idreservas'], 'integer'],

            [['fecha_pago'], 'date', 'format' => 'php:Y-m-d', 'message' => 'Fecha inválida.'],
            [['fecha_pago'], 'validarFecha'],

            [['monto'], 'number', 'min' => 0.01, 'tooSmall' => 'El monto tiene que ser mayor a 0.'],
            [['monto'], 'match', 'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'El monto debe ser un número con hasta 2 decimales (ej. 1500 o 1.500,50).'],
            [['monto'], 'validarSaldo'],

            [['forma_pago'], 'in', 'range' => array_keys(self::formasPago())],

            [['observacion'], 'string', 'max' => 2000],
            [['observacion'], 'default', 'value' => null],

            // Comprobante obligatorio al registrar
            [['archivo'], 'file',
                'skipOnEmpty' => false,
                'extensions' => self::EXTENSIONES,
                'checkExtensionByMimeType' => true,
                'maxSize' => self::TAMANO_MAX,
                'uploadRequired' => 'Subí el comprobante del pago (foto o PDF).',
                'tooBig' => 'El comprobante no puede pesar más de 5 MB.',
                'wrongExtension' => 'El comprobante tiene que ser JPG, PNG, WEBP o PDF.',
                'on' => 'registrar',
            ],
        ];
    }

    public function scenarios()
    {
        $escenarios = parent::scenarios();
        $escenarios['registrar'] = ['idreservas', 'fecha_pago', 'monto', 'forma_pago', 'observacion', 'archivo'];
        return $escenarios;
    }

    public function attributeLabels()
    {
        return [
            'idpago'           => 'N.º',
            'fecha_pago'       => 'Fecha de pago',
            'monto'            => 'Monto (Bs)',
            'forma_pago'       => 'Forma de pago',
            'archivo'          => 'Comprobante',
            'comprobante'      => 'Comprobante',
            'observacion'      => 'Observación',
            'estado'           => 'Estado',
            'motivo_anulacion' => 'Motivo de anulación',
            'fecha_registro'   => 'Registrado',
        ];
    }

    public function beforeValidate()
    {
        $this->monto = FormatoHelper::normalizarMonto($this->monto);
        return parent::beforeValidate();
    }

    /** No se aceptan pagos con fecha futura. */
    public function validarFecha($attribute)
    {
        if ($this->$attribute > date('Y-m-d')) {
            $this->addError($attribute, 'La fecha de pago no puede ser posterior a hoy.');
        }
    }

    /** No se permite pagar más que el saldo de la cotización. */
    public function validarSaldo($attribute)
    {
        if (!$this->isNewRecord) {
            return;
        }
        $cotizacion = ReservaCotizacion::deReserva((int)$this->idreservas);
        if ($cotizacion === null) {
            $this->addError($attribute, 'La reserva no tiene presupuesto: primero completá el Paso 1.');
            return;
        }
        $saldoC = ReservaCotizacionDetalle::aCentavos($cotizacion->getSaldo());
        $montoC = ReservaCotizacionDetalle::aCentavos($this->$attribute);

        if ($saldoC <= 0) {
            $this->addError($attribute, 'La reserva ya está pagada por completo.');
        } elseif ($montoC > $saldoC) {
            $this->addError($attribute, 'El monto supera el saldo pendiente (' . FormatoHelper::formatoBs(ReservaCotizacionDetalle::deCentavos($saldoC)) . ').');
        }
    }

    // ---------------------------------------------------------------- guardar / anular

    /**
     * Valida, guarda el comprobante en la carpeta protegida y registra el pago.
     * Si falla la base, borra el archivo para no dejar basura.
     */
    public function registrar(): bool
    {
        $this->scenario = 'registrar';
        $this->archivo = $this->archivo ?? UploadedFile::getInstance($this, 'archivo');

        if (!$this->validate()) {
            return false;
        }

        $carpeta = self::carpetaReserva((int)$this->idreservas);
        FileHelper::createDirectory($carpeta, 0750, true);

        $extension = strtolower($this->archivo->extension);
        $nombre = date('Ymd_His') . '_' . Yii::$app->security->generateRandomString(12) . '.' . $extension;
        $ruta = $carpeta . DIRECTORY_SEPARATOR . $nombre;

        if (!$this->archivo->saveAs($ruta)) {
            $this->addError('archivo', 'No se pudo guardar el comprobante. Intentá de nuevo.');
            return false;
        }

        $this->comprobante = $nombre;
        $this->comprobante_original = mb_substr($this->archivo->baseName . '.' . $extension, 0, 255);
        $this->estado = self::ESTADO_VALIDO;
        $this->idadmin_registro = self::idAdminActual();

        if (!$this->save(false)) {
            @unlink($ruta);
            $this->addError('monto', 'No se pudo registrar el pago.');
            return false;
        }

        $this->actualizarReserva();
        return true;
    }

    /**
     * Anula el pago (queda en el historial, tachado, con motivo y quién lo anuló).
     */
    public function anular(string $motivo): bool
    {
        $motivo = trim($motivo);
        if ((int)$this->estado === self::ESTADO_ANULADO) {
            $this->addError('estado', 'El pago ya estaba anulado.');
            return false;
        }
        if (mb_strlen($motivo) < 5) {
            $this->addError('motivo_anulacion', 'Escribí el motivo de la anulación (mínimo 5 caracteres).');
            return false;
        }

        $this->estado = self::ESTADO_ANULADO;
        $this->motivo_anulacion = mb_substr($motivo, 0, 500);
        $this->idadmin_anulo = self::idAdminActual();
        $this->fecha_anulacion = date('Y-m-d H:i:s');

        $ok = $this->save(false, ['estado', 'motivo_anulacion', 'idadmin_anulo', 'fecha_anulacion']);
        if ($ok) {
            $this->actualizarReserva();
        }
        return $ok;
    }

    /** Actualiza cancelado/saldo en las columnas de texto de reservas1. */
    private function actualizarReserva(): void
    {
        $cotizacion = ReservaCotizacion::deReserva((int)$this->idreservas);
        if ($cotizacion !== null) {
            $cotizacion->sincronizarReserva();
        }
    }

    // ---------------------------------------------------------------- consultas

    /** Suma de pagos válidos de una reserva, como "1500.00". */
    public static function totalPagado(int $idreservas): string
    {
        $suma = self::find()
            ->where(['idreservas' => $idreservas, 'estado' => self::ESTADO_VALIDO])
            ->sum('monto');
        return ReservaCotizacionDetalle::deCentavos(ReservaCotizacionDetalle::aCentavos($suma ?? 0));
    }

    public static function tienePagos(int $idreservas): bool
    {
        return self::find()->where(['idreservas' => $idreservas])->exists();
    }

    // ---------------------------------------------------------------- relaciones (quién registró / anuló)

    public function getAdminRegistro()
    {
        return $this->hasOne(Administrador::class, ['idadmin' => 'idadmin_registro']);
    }

    public function getAdminAnulo()
    {
        return $this->hasOne(Administrador::class, ['idadmin' => 'idadmin_anulo']);
    }

    /** Usuario del admin para mostrar (es único; el "nombre" a veces se repite). "—" si no se sabe. */
    public static function nombreAdmin(?Administrador $admin): string
    {
        if ($admin === null) {
            return '—';
        }
        return trim((string)$admin->usuario) !== '' ? (string)$admin->usuario : (string)$admin->nombre;
    }

    // ---------------------------------------------------------------- archivo

    public static function carpetaReserva(int $idreservas): string
    {
        return Yii::getAlias(self::CARPETA) . DIRECTORY_SEPARATOR . $idreservas;
    }

    /** Ruta completa del comprobante (null si no existe). */
    public function getRutaComprobante(): ?string
    {
        if (!$this->comprobante) {
            return null;
        }
        $ruta = self::carpetaReserva((int)$this->idreservas) . DIRECTORY_SEPARATOR . basename($this->comprobante);
        return is_file($ruta) ? $ruta : null;
    }

    // ---------------------------------------------------------------- textos

    public function getFormaPagoTexto(): string
    {
        return self::formasPago()[$this->forma_pago] ?? (string)$this->forma_pago;
    }

    public function getMontoTexto(): string
    {
        return FormatoHelper::formatoBs($this->monto);
    }

    public function esValido(): bool
    {
        return (int)$this->estado === self::ESTADO_VALIDO;
    }

    private static function idAdminActual(): ?int
    {
        $app = Yii::$app;
        if (!$app->has('user') || !($app->user instanceof \yii\web\User)) {
            return null;
        }
        $id = $app->user->id;
        return $id !== null ? (int)$id : null;
    }
}

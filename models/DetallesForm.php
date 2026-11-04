<?php

namespace app\models;

use app\helpers\FormatoHelper;
use yii\base\Model;

/**
 * Paso 3 de la reserva nueva: "Detalles".
 *
 * Solo usa columnas que YA EXISTEN en reservas1 (no cambia su estructura) y
 * guarda ÚNICAMENTE estas columnas con updateAttributes (el resto de la reserva
 * no se toca ni se valida).
 *
 * - La comisión se guarda como número limpio ("1500.00"), igual que los datos
 *   que ya había, porque el perfil de la organizadora la SUMA con SQL.
 * - Los textos con formato pasan por FormatoHelper::limpiarHtml().
 * - Cantidad de invitados y paquete NO van acá: los llena el Paso 1.
 * - El cronograma va en su propio paso; la lista de invitados está en su módulo.
 */
class DetallesForm extends Model
{
    /** Campos de texto corto (varchar NOT NULL en reservas1) */
    public const TEXTOS = [
        'color_sillas', 'color_servilleta', 'color_mantel', 'en_mesa',
        'nombre_decoradora',
        'nombre_cordinadora', 'nombre_subcordinadora', 'nombre_supervisora',
    ];

    /** Campos con editor (longtext, admiten null) */
    public const EDITORES = [
        'tipo_bebidas', 'vajillasyvasisenmesa', 'katering',
        'detalle_decoradora', 'detalle_decoracioniglesia', 'otros_detalles',
        'otrainfocoordinacion',
    ];

    // Organizadora
    public $idorganizadora;
    public $comision_organizadora;
    public $cant_garzones;

    // Equipamiento
    public $color_sillas;
    public $color_servilleta;
    public $color_mantel;
    public $en_mesa;
    public $tipo_bebidas;
    public $vajillasyvasisenmesa;
    public $katering;

    // Decoración
    public $nombre_decoradora;
    public $detalle_decoradora;
    public $detalle_decoracioniglesia;
    public $otros_detalles;

    // Coordinación
    public $nombre_cordinadora;
    public $nombre_subcordinadora;
    public $nombre_supervisora;
    public $otrainfocoordinacion;

    /** @var Reservas */
    public $reserva;

    /** @var string|null valor viejo de la comisión que no es un número (se muestra como aviso) */
    public $comisionAnteriorTexto;

    /** @var string|null valor viejo de garzones que no es un número */
    public $garzonesAnteriorTexto;

    public static function deReserva(Reservas $reserva): self
    {
        $f = new self();
        $f->reserva = $reserva;

        $f->idorganizadora = $reserva->idorganizadora ? (int)$reserva->idorganizadora : null;

        // Comisión: si lo guardado es un número, se muestra "1.500,00"; si es texto viejo, se avisa
        $com = trim((string)$reserva->comision_organizadora);
        if ($com !== '') {
            $num = FormatoHelper::normalizarMonto(str_ireplace(['bs.', 'bs'], '', $com));
            if (is_numeric(trim((string)$num))) {
                $f->comision_organizadora = number_format((float)$num, 2, ',', '.');
            } else {
                $f->comisionAnteriorTexto = $com;
            }
        }

        $garz = trim((string)$reserva->cant_garzones);
        if ($garz !== '') {
            if (ctype_digit($garz)) {
                $f->cant_garzones = $garz;
            } else {
                $f->garzonesAnteriorTexto = $garz;
            }
        }

        foreach (array_merge(self::TEXTOS, self::EDITORES) as $campo) {
            $f->$campo = $reserva->$campo;
        }
        return $f;
    }

    public function rules()
    {
        return [
            [array_merge(['comision_organizadora', 'cant_garzones'], self::TEXTOS), 'trim'],

            [['idorganizadora'], 'default', 'value' => null],
            [['idorganizadora'], 'integer'],
            [['idorganizadora'], 'exist', 'targetClass' => Organizadoras::class, 'targetAttribute' => 'idorganizadora',
                'message' => 'La organizadora no existe.'],

            [['comision_organizadora'], 'filter', 'filter' => function ($v) {
                return FormatoHelper::normalizarMonto(is_string($v) ? str_ireplace(['bs.', 'bs'], '', $v) : $v);
            }],
            [['comision_organizadora'], 'number', 'min' => 0, 'tooSmall' => 'La comisión no puede ser negativa.',
                'message' => 'Escribí la comisión como monto, ej. 1.500 o 1.500,50.'],
            [['comision_organizadora'], 'match', 'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'La comisión puede tener hasta 2 decimales.'],
            [['comision_organizadora'], 'required', 'when' => function () {
                return !empty($this->idorganizadora);
            }, 'whenClient' => 'function () { return false; }',
                'message' => 'Si hay organizadora, escribí su comisión (puede ser 0).'],

            [['cant_garzones'], 'integer', 'min' => 0, 'max' => 999,
                'message' => 'La cantidad de garzones tiene que ser un número entero.',
                'tooBig' => 'Revisá la cantidad de garzones (máx. 999).'],

            [self::TEXTOS, 'string', 'max' => 500],
            [self::EDITORES, 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idorganizadora'            => 'Organizadora',
            'comision_organizadora'     => 'Comisión de la organizadora',
            'cant_garzones'             => 'Cantidad de garzones',
            'color_sillas'              => 'Color de silla',
            'color_servilleta'          => 'Color de servilletas',
            'color_mantel'              => 'Color de mantel',
            'en_mesa'                   => 'En mesa',
            'tipo_bebidas'              => 'Tipo de bebidas',
            'vajillasyvasisenmesa'      => 'Vajillas y vasos en mesa',
            'katering'                  => 'Catering',
            'nombre_decoradora'         => 'Nombre de la decoradora',
            'detalle_decoradora'        => 'Detalle de la decoración del salón',
            'detalle_decoracioniglesia' => 'Detalle de la decoración de la iglesia',
            'otros_detalles'            => 'Otros detalles',
            'nombre_cordinadora'        => 'Nombre de la coordinadora',
            'nombre_subcordinadora'     => 'Nombre de la sub-coordinadora',
            'nombre_supervisora'        => 'Nombre de la supervisora',
            'otrainfocoordinacion'      => 'Otra información',
        ];
    }

    /**
     * Guarda solo las columnas del Paso 3 en reservas1.
     */
    public function guardar(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $datos = [
            'idorganizadora'        => $this->idorganizadora ? (int)$this->idorganizadora : null,
            // Número limpio, ej. "1500.00" (el perfil de la organizadora lo suma con SQL)
            'comision_organizadora' => ($this->comision_organizadora === null || $this->comision_organizadora === '')
                ? '' : number_format((float)$this->comision_organizadora, 2, '.', ''),
            'cant_garzones'         => ($this->cant_garzones === null || $this->cant_garzones === '')
                ? '' : (string)(int)$this->cant_garzones,
        ];
        foreach (self::TEXTOS as $campo) {
            $datos[$campo] = (string)$this->$campo;          // NOT NULL: nunca null
        }
        foreach (self::EDITORES as $campo) {
            $datos[$campo] = FormatoHelper::limpiarHtml($this->$campo);
        }

        $this->reserva->updateAttributes($datos);
        return true;
    }

    /** Organizadoras para el selector: activas + la que ya tenga la reserva aunque esté inactiva. */
    public function catalogoOrganizadoras(): array
    {
        $lista = [];
        $query = Organizadoras::find()->orderBy(['nombre' => SORT_ASC]);
        foreach ($query->all() as $o) {
            $activa = (string)$o->estado === '1';
            if (!$activa && (int)$o->idorganizadora !== (int)$this->idorganizadora) {
                continue;
            }
            $lista[(int)$o->idorganizadora] = $o->nombre . ($activa ? '' : ' (inactiva)');
        }
        return $lista;
    }
}

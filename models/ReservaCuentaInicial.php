<?php

namespace app\models;

use app\helpers\FormatoHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * "Saldo de arranque" de una reserva VIEJA (sin presupuesto por pasos), para
 * poder registrarle pagos con comprobante igual que a las nuevas.
 *
 *  - Total de la cuenta     = total_paquete + total_servicios (una sola cuenta)
 *  - Cancelado a la fecha   = cancelado_paquete + cancelado_servicios + pagos válidos nuevos
 *  - Saldo                  = total − cancelado
 *
 * Al registrar/anular un pago se actualizan las columnas de montos que YA EXISTEN
 * en reservas1 (los pagos nuevos cubren primero el paquete y lo que sobra los
 * servicios adicionales), así las pantallas viejas siguen cuadrando.
 *
 * @property int         $idreservas
 * @property string      $total_paquete
 * @property string      $cancelado_paquete
 * @property string      $total_servicios
 * @property string      $cancelado_servicios
 * @property string|null $observacion
 * @property int|null    $idadmin_registro
 * @property int|null    $idadmin_actualizo
 * @property string      $fecha_registro
 * @property string      $fecha_actualizacion
 *
 * @property Reservas $reserva
 */
class ReservaCuentaInicial extends ActiveRecord
{
    /** Columnas de reservas1 que pasan a manejarse desde Facturación. */
    public const COLUMNAS_RESERVA = [
        'presupuesto', 'monto_canceladop', 'saldo_pagarp',
        'servicios_adicionales', 'monto_cancelados', 'saldo_pagars',
    ];

    public static function tableName()
    {
        return 'reserva_cuenta_inicial';
    }

    public function rules()
    {
        return [
            [['total_paquete', 'cancelado_paquete', 'total_servicios', 'cancelado_servicios'], 'filter', 'filter' => function ($v) {
                $v = trim((string)$v);
                return $v === '' ? '0' : FormatoHelper::normalizarMonto($v);
            }],
            [['total_paquete', 'cancelado_paquete', 'total_servicios', 'cancelado_servicios'], 'number', 'min' => 0,
                'message' => 'Escribí un monto válido (ej. 1.500,50).'],
            [['total_paquete'], 'number', 'min' => 0.01, 'tooSmall' => 'El presupuesto del paquete debe ser mayor a 0.'],
            [['cancelado_paquete'], 'compare', 'compareAttribute' => 'total_paquete', 'operator' => '<=', 'type' => 'number',
                'message' => 'Lo cancelado del paquete no puede ser mayor que su presupuesto.'],
            [['cancelado_servicios'], 'compare', 'compareAttribute' => 'total_servicios', 'operator' => '<=', 'type' => 'number',
                'message' => 'Lo cancelado de servicios no puede ser mayor que su total.'],
            [['total_paquete'], 'validarContraPagos'],
            [['observacion'], 'string', 'max' => 500],
            [['observacion'], 'filter', 'filter' => 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'total_paquete'       => 'Presupuesto del paquete',
            'cancelado_paquete'   => 'Cancelado del paquete (antes del sistema)',
            'total_servicios'     => 'Servicios adicionales',
            'cancelado_servicios' => 'Cancelado de servicios (antes del sistema)',
            'observacion'         => 'Observación',
        ];
    }

    /** Si ya hay pagos nuevos, el saldo de arranque no puede quedar por debajo de ellos. */
    public function validarContraPagos($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }
        $saldoInicialC = $this->c($this->getTotal()) - $this->c($this->getCanceladoInicial());
        $pagosC = $this->c(ReservaPago::totalPagado((int)$this->idreservas));
        if ($pagosC > $saldoInicialC) {
            $this->addError($attribute, 'Con estos montos el saldo quedaría por debajo de los pagos ya registrados ('
                . FormatoHelper::formatoBs(ReservaPago::totalPagado((int)$this->idreservas)) . ').');
        }
    }

    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreservas']);
    }

    public static function deReserva(int $idreservas): ?self
    {
        return self::findOne(['idreservas' => $idreservas]);
    }

    /**
     * Cuenta nueva con los montos que hoy tiene reservas1 (para que el admin los confirme).
     */
    public static function desdeReserva(Reservas $reserva): self
    {
        $num = function ($v) {
            $n = FormatoHelper::normalizarMonto(trim((string)$v));
            return is_numeric($n) ? (string)$n : '0';
        };
        $c = new self();
        $c->idreservas = (int)$reserva->idreservas;
        $c->total_paquete = $num($reserva->presupuesto);
        $c->cancelado_paquete = $num($reserva->monto_canceladop);
        $c->total_servicios = $num($reserva->servicios_adicionales);
        $c->cancelado_servicios = $num($reserva->monto_cancelados);
        return $c;
    }

    // ---------------------------------------------------------------- montos

    public function getTotal(): string
    {
        return $this->d($this->c($this->total_paquete) + $this->c($this->total_servicios));
    }

    /** Lo cancelado antes del sistema (sin comprobante). */
    public function getCanceladoInicial(): string
    {
        return $this->d($this->c($this->cancelado_paquete) + $this->c($this->cancelado_servicios));
    }

    /** Cancelado a la fecha = inicial + pagos válidos nuevos. */
    public function getTotalPagado(): string
    {
        return $this->d($this->c($this->getCanceladoInicial()) + $this->c(ReservaPago::totalPagado((int)$this->idreservas)));
    }

    public function getSaldo(): string
    {
        return $this->d($this->c($this->getTotal()) - $this->c($this->getTotalPagado()));
    }

    /**
     * Escribe los montos en las columnas que ya existen en reservas1.
     * Los pagos nuevos cubren primero el saldo del paquete; lo que sobra, los servicios.
     */
    public function sincronizarReserva(): void
    {
        $reserva = $this->reserva;
        if ($reserva === null) {
            return;
        }
        $nuevosC = $this->c(ReservaPago::totalPagado((int)$this->idreservas));
        $saldoPaqueteC = max(0, $this->c($this->total_paquete) - $this->c($this->cancelado_paquete));
        $alPaqueteC = min($nuevosC, $saldoPaqueteC);
        $aServiciosC = $nuevosC - $alPaqueteC;

        $canceladoP = $this->c($this->cancelado_paquete) + $alPaqueteC;
        $canceladoS = $this->c($this->cancelado_servicios) + $aServiciosC;

        // Mismo formato que tienen los datos viejos: número simple ("28500" o "28500.50")
        $txt = function (int $centavos) {
            return $centavos % 100 === 0 ? (string)intdiv($centavos, 100) : number_format($centavos / 100, 2, '.', '');
        };

        $reserva->updateAttributes([
            'presupuesto'           => $txt($this->c($this->total_paquete)),
            'monto_canceladop'      => $txt($canceladoP),
            'saldo_pagarp'          => $txt($this->c($this->total_paquete) - $canceladoP),
            'servicios_adicionales' => $txt($this->c($this->total_servicios)),
            'monto_cancelados'      => $txt($canceladoS),
            'saldo_pagars'          => $txt($this->c($this->total_servicios) - $canceladoS),
        ]);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $app = Yii::$app;
        $idAdmin = ($app->has('user') && $app->user instanceof \yii\web\User && $app->user->id !== null) ? (int)$app->user->id : null;
        if ($insert) {
            $this->idadmin_registro = $idAdmin;
        }
        $this->idadmin_actualizo = $idAdmin;
        return true;
    }

    private function c($v): int
    {
        return ReservaCotizacionDetalle::aCentavos($v);
    }

    private function d(int $centavos): string
    {
        return ReservaCotizacionDetalle::deCentavos($centavos);
    }
}

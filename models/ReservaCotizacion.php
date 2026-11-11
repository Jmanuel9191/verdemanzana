<?php

namespace app\models;

use app\helpers\FormatoHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * Cotización (presupuesto) de una reserva. Una sola por reserva.
 *
 * No modifica la estructura de reservas1: se engancha por idreservas.
 * Una reserva "nueva" (formulario de 3 pasos) es la que tiene cotización.
 *
 * @property int         $idcotizacion
 * @property int         $idreservas
 * @property int|null    $idpaquete
 * @property string      $subtotal
 * @property string      $descuento_total
 * @property string      $total
 * @property string|null $observaciones
 * @property int|null    $idadmin_registro
 * @property int|null    $idadmin_actualizo
 * @property string      $fecha_registro
 * @property string      $fecha_actualizacion
 *
 * @property ReservaCotizacionDetalle[] $detalles
 * @property ReservaCotizacionDetalle|null $lineaPaquete
 * @property ReservaCotizacionDetalle[] $lineasExtras
 * @property Reservas    $reserva
 * @property ReservaPago[] $pagosValidos
 */
class ReservaCotizacion extends ActiveRecord
{
    public static function tableName()
    {
        return 'reserva_cotizacion';
    }

    public function rules()
    {
        return [
            [['idreservas'], 'required'],
            [['idreservas', 'idpaquete', 'idadmin_registro', 'idadmin_actualizo'], 'integer'],
            [['idpaquete', 'idadmin_registro', 'idadmin_actualizo'], 'default', 'value' => null],
            [['idreservas'], 'unique', 'message' => 'Esta reserva ya tiene una cotización.'],
            [['observaciones'], 'string'],
            [['observaciones'], 'trim'],
            [['observaciones'], 'default', 'value' => null],
            // subtotal / descuento_total / total los calcula recalcularTotales()
        ];
    }

    public function attributeLabels()
    {
        return [
            'idcotizacion'    => 'N.º',
            'idreservas'      => 'Reserva',
            'idpaquete'       => 'Paquete',
            'subtotal'        => 'Subtotal',
            'descuento_total' => 'Descuento total',
            'total'           => 'Total',
            'observaciones'   => 'Observaciones',
            'fecha_registro'  => 'Fecha',
        ];
    }

    // ---------------------------------------------------------------- relaciones

    public function getDetalles()
    {
        return $this->hasMany(ReservaCotizacionDetalle::class, ['idcotizacion' => 'idcotizacion'])
            ->orderBy(['orden' => SORT_ASC, 'iddetalle' => SORT_ASC]);
    }

    public function getLineaPaquete()
    {
        return $this->hasOne(ReservaCotizacionDetalle::class, ['idcotizacion' => 'idcotizacion'])
            ->andOnCondition(['seccion' => ReservaCotizacionDetalle::SECCION_PAQUETE]);
    }

    public function getLineasExtras()
    {
        return $this->hasMany(ReservaCotizacionDetalle::class, ['idcotizacion' => 'idcotizacion'])
            ->andOnCondition(['seccion' => [
                ReservaCotizacionDetalle::SECCION_ITEM,
                ReservaCotizacionDetalle::SECCION_PROVEEDOR,
                ReservaCotizacionDetalle::SECCION_EXPERIENCIA,
            ]])
            ->orderBy(['orden' => SORT_ASC, 'iddetalle' => SORT_ASC]);
    }

    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreservas']);
    }

    public function getPagosValidos()
    {
        return $this->hasMany(ReservaPago::class, ['idreservas' => 'idreservas'])
            ->andOnCondition(['estado' => ReservaPago::ESTADO_VALIDO])
            ->orderBy(['fecha_pago' => SORT_ASC, 'idpago' => SORT_ASC]);
    }

    // ---------------------------------------------------------------- cuentas

    /**
     * Suma las líneas (ya calculadas) y deja subtotal / descuento / total en la cabecera.
     *
     * @param ReservaCotizacionDetalle[]|null $lineas  si no se pasan, se leen de la base
     */
    public function recalcularTotales(?array $lineas = null): void
    {
        $lineas = $lineas ?? $this->detalles;

        $subtotalC = 0;
        $descuentoC = 0;
        foreach ($lineas as $linea) {
            $subtotalC += ReservaCotizacionDetalle::aCentavos($linea->subtotal);
            $descuentoC += ReservaCotizacionDetalle::aCentavos($linea->descuento_monto);
        }

        $this->subtotal = ReservaCotizacionDetalle::deCentavos($subtotalC);
        $this->descuento_total = ReservaCotizacionDetalle::deCentavos($descuentoC);
        $this->total = ReservaCotizacionDetalle::deCentavos($subtotalC - $descuentoC);
    }

    /** Total del presupuesto (mismo nombre que ReservaCuentaInicial::getTotal). */
    public function getTotal(): string
    {
        return (string)$this->total;
    }

    /** Cancelado a la fecha: suma de pagos válidos de la reserva. */
    public function getTotalPagado(): string
    {
        return ReservaPago::totalPagado((int)$this->idreservas);
    }

    /** Saldo = total − cancelado (puede ser 0, nunca se deja pagar de más). */
    public function getSaldo(): string
    {
        $saldoC = ReservaCotizacionDetalle::aCentavos($this->total)
            - ReservaCotizacionDetalle::aCentavos($this->getTotalPagado());
        return ReservaCotizacionDetalle::deCentavos($saldoC);
    }

    public static function deReserva(int $idreservas): ?self
    {
        return self::findOne(['idreservas' => $idreservas]);
    }

    /**
     * Copia los totales a las columnas de texto que YA EXISTEN en reservas1
     * (presupuesto / monto_canceladop / saldo_pagarp), para que las pantallas
     * actuales del cliente sigan mostrando los números. No cambia la estructura
     * de reservas1 y solo se usa en reservas creadas con el formulario nuevo.
     */
    public function sincronizarReserva(): void
    {
        $reserva = $this->reserva;
        if ($reserva === null) {
            return;
        }
        $paquete = $this->lineaPaquete;

        $datos = [
            'presupuesto'      => FormatoHelper::formatoBs($this->total),
            'monto_canceladop' => FormatoHelper::formatoBs($this->getTotalPagado()),
            'saldo_pagarp'     => FormatoHelper::formatoBs($this->getSaldo()),
        ];
        if ($paquete !== null) {
            $datos['paquete_reservar'] = mb_substr($paquete->nombre, 0, 25);
            $datos['limite_invitados'] = (string)(int)round((float)$paquete->cantidad);
        }

        // updateAttributes: escribe solo estas columnas, sin validar ni tocar el resto
        $reserva->updateAttributes($datos);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $idAdmin = $this->idAdminActual();
        if ($insert && $this->idadmin_registro === null) {
            $this->idadmin_registro = $idAdmin;
        }
        $this->idadmin_actualizo = $idAdmin;
        return true;
    }

    /** Id del admin logueado (null si no hay sesión de admin, ej. consola). */
    private function idAdminActual(): ?int
    {
        $app = Yii::$app;
        if (!$app->has('user') || !($app->user instanceof \yii\web\User)) {
            return null;
        }
        $id = $app->user->id;
        return $id !== null ? (int)$id : null;
    }
}

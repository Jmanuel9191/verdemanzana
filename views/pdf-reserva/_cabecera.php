<?php

use app\components\PdfReserva;
use app\models\PresupuestoForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $titulo string */

$paquete = $cotizacion->lineaPaquete;
$fecha = $reserva->fecha_reserva ? date('d/m/Y', strtotime($reserva->fecha_reserva)) : '—';
$v = function ($x) {
    $x = trim((string)$x);
    return $x !== '' ? Html::encode($x) : '—';
};
?>
<h1 class="doc-titulo"><?= Html::encode($titulo) ?></h1>
<div class="caja">
    <table class="datos">
        <tr>
            <td width="34%"><span class="etq">Cliente</span><br><b><?= $v($reserva->nombre) ?></b></td>
            <td width="33%"><span class="etq">Evento</span><br><b><?= $v(PdfReserva::nombreEvento($reserva)) ?></b></td>
            <td width="33%"><span class="etq">Tipo de evento</span><br><?= $v(PresupuestoForm::TIPOS_EVENTO[$reserva->tipoevento] ?? $reserva->tipoevento) ?></td>
        </tr>
        <tr>
            <td><span class="etq">Fecha y hora</span><br><b><?= $fecha ?></b><?= $reserva->hora_inicio ? ' · ' . Html::encode($reserva->hora_inicio) : '' ?></td>
            <td><span class="etq">Sucursal</span><br><?= $v($reserva->sucursal) ?></td>
            <td><span class="etq">Teléfono</span><br><?= $v($reserva->telefono) ?></td>
        </tr>
        <tr>
            <td><span class="etq">Paquete</span><br><?= $v($paquete ? $paquete->nombre : '') ?></td>
            <td><span class="etq">Invitados</span><br><?= $paquete ? (int)round((float)$paquete->cantidad) : '—' ?></td>
            <td><span class="etq">Reserva N.º</span><br><?= (int)$reserva->idreservas ?></td>
        </tr>
    </table>
</div>

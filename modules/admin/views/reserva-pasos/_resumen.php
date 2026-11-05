<?php

use app\helpers\FormatoHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $paquete string */
/* @var $invitados string */
/* @var $fecha string|null  Y-m-d */
/* @var $sucursal string|null */
/* @var $total string */
/* @var $pagado string */

$saldo = \app\models\ReservaCotizacionDetalle::deCentavos(
    \app\models\ReservaCotizacionDetalle::aCentavos($total) - \app\models\ReservaCotizacionDetalle::aCentavos($pagado)
);
?>
<div class="resumen-reserva" id="resumen-reserva" data-pagado="<?= Html::encode($pagado) ?>">
    <div class="resumen-bloque">
        <span id="res-paquete"><?= Html::encode($paquete ?: 'Sin paquete') ?></span>
        <small id="res-invitados"><?= $invitados !== '' ? Html::encode($invitados) . ' invitados' : '' ?></small>
    </div>
    <div class="resumen-bloque">
        <span id="res-fecha"><?= $fecha ? Html::encode(date('d/m/Y', strtotime($fecha))) : 'Sin fecha' ?></span>
        <small id="res-sucursal"><?= Html::encode($sucursal ?: '') ?></small>
    </div>
    <div class="resumen-bloque resumen-montos">
        <div><small>TOTAL</small><b id="res-total"><?= FormatoHelper::formatoBs($total) ?></b></div>
        <div><small>CANCELADO</small><b id="res-pagado"><?= FormatoHelper::formatoBs($pagado) ?></b></div>
        <div class="res-saldo"><small>SALDO</small><b id="res-saldo"><?= FormatoHelper::formatoBs($saldo) ?></b></div>
    </div>
</div>

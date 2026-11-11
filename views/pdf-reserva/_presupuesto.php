<?php

use app\helpers\FormatoHelper;
use app\models\ReservaCotizacionDetalle;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */

$c = $cotizacion;
$paquete = $c->lineaPaquete;
$lineas = $c->detalles;
$pagado = $c->getTotalPagado();
$saldo = $c->getSaldo();

$cant = function ($v) {
    $f = (float)$v;
    return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '.');
};
$pct = function ($v) {
    $f = round((float)$v, 2);
    return floor($f) == $f ? (string)(int)$f : rtrim(rtrim(number_format($f, 2, ',', ''), '0'), ',');
};
$detallePaquete = $paquete ? FormatoHelper::limpiarHtml($paquete->detalle) : null;
?>
<?= $this->render('_cabecera', ['reserva' => $reserva, 'cotizacion' => $cotizacion, 'titulo' => 'Presupuesto']) ?>

<?php
[$lineasCero, $lineasCosto] = ReservaCotizacionDetalle::agruparPresupuesto($lineas);
$grupos = ReservaCotizacionDetalle::gruposPresentes($lineas);
$nombresGrupos = ReservaCotizacionDetalle::nombresGrupos();

// Una fila del presupuesto. $bg = fondo verde claro (bloque de precio 0).
$fila = function (ReservaCotizacionDetalle $l, bool $bg) use ($cant, $pct) {
    $color = ReservaCotizacionDetalle::colorSeccion($l->seccion);
    $td = $bg ? ' style="background-color:#f7f9f1;"' : '';
    ob_start(); ?>
        <tr>
            <td<?= $td ?>>
                <b style="color:<?= $color ?>;"><?= Html::encode($l->nombre) ?></b>
                <?php if ($l->empresa): ?><span class="muted chico"> · <?= Html::encode($l->empresa) ?></span><?php endif; ?>
            </td>
            <td class="num"<?= $td ?>><?= $cant($l->cantidad) ?> <span class="muted chico"><?= Html::encode(mb_strtolower($l->getUnidadTexto())) ?></span></td>
            <td class="num"<?= $td ?>><?= FormatoHelper::formatoBs($l->precio_unitario) ?></td>
            <td class="num"<?= $td ?>><?= FormatoHelper::formatoBs($l->subtotal) ?></td>
            <td class="num"<?= $td ?>>
                <?php if (ReservaCotizacionDetalle::aCentavos($l->descuento_monto) > 0): ?>
                    − <?= FormatoHelper::formatoBs($l->descuento_monto) ?><br><span class="muted chico">(<?= $pct($l->descuento_porcentaje) ?>%)</span>
                <?php else: ?>
                    <span class="muted">—</span>
                <?php endif; ?>
            </td>
            <td class="num"<?= $td ?>><b><?= FormatoHelper::formatoBs($l->total) ?></b></td>
        </tr>
    <?php return ob_get_clean();
};
?>

<?php if ($grupos): ?>
    <table width="100%" style="margin-bottom:1.5mm;"><tr><td style="font-size:8.5pt;">
        <?php foreach ($grupos as $sec): ?>
            <span style="color:<?= ReservaCotizacionDetalle::colorSeccion($sec) ?>;">■</span>
            <span class="muted"><?= Html::encode($nombresGrupos[$sec] ?? '') ?></span>&nbsp;&nbsp;&nbsp;
        <?php endforeach; ?>
    </td></tr></table>
<?php endif; ?>

<table class="tabla">
    <thead>
    <tr>
        <th width="40%">Descripción</th>
        <th class="num">Cant.</th>
        <th class="num">Precio unit.</th>
        <th class="num">Subtotal</th>
        <th class="num">Descuento</th>
        <th class="num">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($lineasCero as $l): ?>
        <?= $fila($l, true) ?>
    <?php endforeach; ?>
    <?php if ($lineasCero && $lineasCosto): ?>
        <tr><td colspan="6" style="border-top:0.5mm solid #6ba622; padding:0; height:3mm;"></td></tr>
    <?php endif; ?>
    <?php foreach ($lineasCosto as $l): ?>
        <?= $fila($l, false) ?>
    <?php endforeach; ?>
    </tbody>
</table>

<table width="100%" style="margin-top:2mm;"><tr><td width="50%"></td><td width="50%"><table width="100%">
    <tr><td class="tot-etq">Subtotal</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($c->subtotal) ?></td></tr>
    <?php if (ReservaCotizacionDetalle::aCentavos($c->descuento_total) > 0): ?>
        <tr><td class="tot-etq">Descuento</td><td class="tot-val" width="40%">− <?= FormatoHelper::formatoBs($c->descuento_total) ?></td></tr>
    <?php endif; ?>
    <tr class="total"><td class="tot-etq">TOTAL</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($c->total) ?></td></tr>
    <?php if (ReservaCotizacionDetalle::aCentavos($pagado) > 0): ?>
        <tr><td class="tot-etq">Cancelado a la fecha</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($pagado) ?></td></tr>
        <tr class="saldo"><td class="tot-etq">Saldo</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($saldo) ?></td></tr>
    <?php endif; ?>
</table></td></tr></table>

<?php if ($detallePaquete): ?>
    <h3 class="doc-sub">El paquete <?= Html::encode($paquete->nombre) ?> incluye</h3>
    <div><?= $detallePaquete ?></div>
<?php endif; ?>

<?php if (trim((string)$c->observaciones) !== ''): ?>
    <h3 class="doc-sub">Observaciones</h3>
    <div class="caja"><?= nl2br(Html::encode($c->observaciones)) ?></div>
<?php endif; ?>

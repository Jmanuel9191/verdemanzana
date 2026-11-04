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
    <?php foreach ($lineas as $l): ?>
        <?php
        $sec = $l->seccion;
        $clase = $sec === ReservaCotizacionDetalle::SECCION_PAQUETE ? 'etq-paquete' : ($sec === 'proveedor' ? 'etq-proveedor' : 'etq-item');
        ?>
        <tr class="<?= $sec === ReservaCotizacionDetalle::SECCION_PAQUETE ? 'paquete' : '' ?>">
            <td>
                <span class="etiqueta <?= $clase ?>"><?= Html::encode($l->getSeccionTexto()) ?></span>
                <b><?= Html::encode($l->nombre) ?></b>
                <?php if ($l->empresa): ?><span class="muted chico"> · <?= Html::encode($l->empresa) ?></span><?php endif; ?>
            </td>
            <td class="num"><?= $cant($l->cantidad) ?> <span class="muted chico"><?= Html::encode(mb_strtolower($l->getUnidadTexto())) ?></span></td>
            <td class="num"><?= FormatoHelper::formatoBs($l->precio_unitario) ?></td>
            <td class="num"><?= FormatoHelper::formatoBs($l->subtotal) ?></td>
            <td class="num">
                <?php if (ReservaCotizacionDetalle::aCentavos($l->descuento_monto) > 0): ?>
                    − <?= FormatoHelper::formatoBs($l->descuento_monto) ?><br><span class="muted chico">(<?= $pct($l->descuento_porcentaje) ?>%)</span>
                <?php else: ?>
                    <span class="muted">—</span>
                <?php endif; ?>
            </td>
            <td class="num"><b><?= FormatoHelper::formatoBs($l->total) ?></b></td>
        </tr>
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

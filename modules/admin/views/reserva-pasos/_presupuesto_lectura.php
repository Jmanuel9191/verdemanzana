<?php

use app\helpers\FormatoHelper;
use app\models\ReservaCotizacionDetalle;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form app\models\PresupuestoForm */
/* @var $pagado string */

$c = $form->cotizacion;
$lineas = $form->todasLasLineas();
$saldo = ReservaCotizacionDetalle::deCentavos(
    ReservaCotizacionDetalle::aCentavos($c->total) - ReservaCotizacionDetalle::aCentavos($pagado)
);
$cant = function ($v) {
    $f = (float)$v;
    return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '.');
};
$pct = function ($v) {
    $f = round((float)$v, 2);
    return floor($f) == $f ? (string)(int)$f : rtrim(rtrim(number_format($f, 2, ',', ''), '0'), ',');
};
?>
<div class="box-body">
    <h3 class="titulo-seccion">
        Presupuesto
        <?= Html::a('<i class="fa fa-pencil"></i> Modificar presupuesto', ['modificar-presupuesto', 'id' => $form->reserva->idreservas],
            ['class' => 'btn btn-warning btn-sm pull-right']) ?>
        <?= Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $form->reserva->idreservas, 'tipo' => 'presupuesto'],
            ['class' => 'btn btn-danger btn-sm pull-right', 'style' => 'margin-right:6px', 'target' => '_blank', 'title' => 'Abrir PDF (se puede descargar o imprimir)']) ?>
        <?= Html::a('<i class="fa fa-picture-o"></i> Galería <span class="badge">' . count($form->reserva->galeriapresupuesto) . '</span>',
            ['reservas/galeriapresupuesto', 'id' => $form->reserva->idreservas],
            ['class' => 'btn btn-default btn-sm pull-right', 'target' => '_blank', 'style' => 'margin-right:6px',
                'title' => 'Ver o subir imágenes del presupuesto (se abre en otra pestaña)']) ?>
    </h3>

    <?php
    [$lineasCero, $lineasCosto] = ReservaCotizacionDetalle::agruparPresupuesto($lineas);
    $grupos = ReservaCotizacionDetalle::gruposPresentes($lineas);
    $nombresGrupos = ReservaCotizacionDetalle::nombresGrupos();

    $fila = function ($l, $bg) use ($cant, $pct) {
        $color = ReservaCotizacionDetalle::colorSeccion($l->seccion);
        $tr = $bg ? ' style="background-color:#f7f9f1;"' : '';
        ob_start(); ?>
            <tr<?= $tr ?>>
                <td>
                    <b style="color:<?= $color ?>;"><?= Html::encode($l->nombre) ?></b>
                    <?php if ($l->empresa): ?><small class="text-muted">· <?= Html::encode($l->empresa) ?></small><?php endif; ?>
                </td>
                <td class="text-right"><?= $cant($l->cantidad) ?> <small class="text-muted"><?= Html::encode(mb_strtolower($l->getUnidadTexto())) ?></small></td>
                <td class="text-right"><?= FormatoHelper::formatoBs($l->precio_unitario) ?></td>
                <td class="text-right"><?= FormatoHelper::formatoBs($l->subtotal) ?></td>
                <td class="text-right">
                    <?php if (ReservaCotizacionDetalle::aCentavos($l->descuento_monto) > 0): ?>
                        − <?= FormatoHelper::formatoBs($l->descuento_monto) ?> <small class="text-muted">(<?= $pct($l->descuento_porcentaje) ?>%)</small>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>
                <td class="text-right"><b><?= FormatoHelper::formatoBs($l->total) ?></b></td>
            </tr>
        <?php return ob_get_clean();
    };
    ?>

    <?php if ($grupos): ?>
        <p style="margin:0 0 8px; font-size:13px;">
            <?php foreach ($grupos as $sec): ?>
                <span style="color:<?= ReservaCotizacionDetalle::colorSeccion($sec) ?>;">■</span>
                <span class="text-muted" style="margin-right:16px;"><?= Html::encode($nombresGrupos[$sec] ?? '') ?></span>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-condensed tabla-lineas tabla-lectura">
            <thead>
            <tr>
                <th>Descripción</th>
                <th class="text-right">Cant.</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Desc.</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lineasCero as $l): ?>
                <?= $fila($l, true) ?>
            <?php endforeach; ?>
            <?php if ($lineasCero && $lineasCosto): ?>
                <tr><td colspan="6" style="border-top:2px solid #6ba622; padding:0; height:10px; background:#fff;"></td></tr>
            <?php endif; ?>
            <?php foreach ($lineasCosto as $l): ?>
                <?= $fila($l, false) ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php if (trim((string)$c->observaciones) !== ''): ?>
                <label class="control-label">Observaciones del presupuesto</label>
                <div class="observaciones-lectura"><?= nl2br(Html::encode($c->observaciones)) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <table class="table tabla-totales">
                <tr><th>Subtotal</th><td><?= FormatoHelper::formatoBs($c->subtotal) ?></td></tr>
                <tr><th>Descuento total</th><td>− <?= FormatoHelper::formatoBs($c->descuento_total) ?></td></tr>
                <tr class="fila-total"><th>TOTAL</th><td><?= FormatoHelper::formatoBs($c->total) ?></td></tr>
                <tr><th>Cancelado a la fecha</th><td><?= FormatoHelper::formatoBs($pagado) ?></td></tr>
                <tr><th>Saldo</th><td><?= FormatoHelper::formatoBs($saldo) ?></td></tr>
            </table>
        </div>
    </div>
</div>

<?php

use app\helpers\FormatoHelper;
use app\models\ReservaCotizacionHistorial;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $historial ReservaCotizacionHistorial[] del más nuevo al más viejo */
/* @var $cotizacion app\models\ReservaCotizacion */

$fechaHora = function ($v) {
    return $v ? date('d/m/Y H:i', strtotime($v)) : '';
};
$cantidad = count($historial);
$hayCreacion = false;
foreach ($historial as $h) {
    if ($h->accion === ReservaCotizacionHistorial::ACCION_CREADO) {
        $hayCreacion = true;
    }
}
?>
<div class="box-body historial-presupuesto">
    <h3 class="titulo-seccion">
        <a data-toggle="collapse" href="#historial-cambios" class="enlace-historial collapsed">
            <i class="fa fa-history"></i> Historial de cambios del presupuesto
            <span class="badge"><?= $cantidad ?></span>
            <i class="fa fa-chevron-down flecha"></i>
        </a>
    </h3>

    <div id="historial-cambios" class="collapse">
        <?php if ($cantidad === 0): ?>
            <p class="text-muted">Todavía no hubo cambios en el presupuesto.</p>
        <?php endif; ?>

        <ul class="lista-historial">
            <?php foreach ($historial as $h): ?>
                <?php $esCreacion = $h->accion === ReservaCotizacionHistorial::ACCION_CREADO; ?>
                <li class="<?= $esCreacion ? 'evento-creado' : 'evento-modificado' ?>">
                    <div class="cabecera-evento">
                        <b><?= Html::encode($fechaHora($h->fecha_registro)) ?></b>
                        · <i class="fa fa-user"></i> <?= Html::encode($h->getNombreAdmin()) ?>
                        · <?= $esCreacion ? 'Creó el presupuesto' : 'Modificó el presupuesto' ?>
                        <span class="pull-right total-evento">
                            <?php if ($h->total_anterior !== null): ?>
                                <?= FormatoHelper::formatoBs($h->total_anterior) ?> →
                            <?php endif; ?>
                            <b><?= FormatoHelper::formatoBs($h->total_nuevo) ?></b>
                            <?php $dif = $h->getDiferenciaTexto(); ?>
                            <?php if ($dif !== ''): ?>
                                <small class="<?= strpos($dif, '+') === 0 ? 'text-success' : (strpos($dif, '−') === 0 ? 'text-danger' : 'text-muted') ?>">(<?= Html::encode($dif) ?>)</small>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if ($h->motivo): ?>
                        <div class="motivo-evento"><i class="fa fa-comment"></i> Motivo: <?= Html::encode($h->motivo) ?></div>
                    <?php endif; ?>
                    <ul class="cambios-evento">
                        <?php foreach ($h->getCambiosLista() as $cambio): ?>
                            <li><?= ReservaCotizacionHistorial::describir($cambio) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>

            <?php if (!$hayCreacion && $cotizacion->fecha_registro): ?>
                <li class="evento-creado">
                    <div class="cabecera-evento">
                        <b><?= Html::encode($fechaHora($cotizacion->fecha_registro)) ?></b>
                        · Presupuesto creado <small class="text-muted">(antes de que existiera este historial)</small>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

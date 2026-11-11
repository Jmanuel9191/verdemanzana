<?php

use app\components\GuiaReserva;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $guia string|null  nombre del archivo subido (o null) */

$this->title = 'Reserva: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['presupuesto', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = 'Guía';

$paquete = $cotizacion->lineaPaquete;
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content reserva-pasos">

    <?php foreach (['success' => 'success', 'error' => 'danger'] as $tipoFlash => $clase): ?>
        <?php if (Yii::$app->session->hasFlash($tipoFlash)): ?>
            <div class="alert alert-<?= $clase ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= Html::encode(Yii::$app->session->getFlash($tipoFlash)) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?= $this->render('_resumen', [
        'paquete'   => $paquete ? $paquete->nombre : '',
        'invitados' => $paquete ? (string)(int)round((float)$paquete->cantidad) : '',
        'fecha'     => $reserva->fecha_reserva,
        'sucursal'  => $reserva->sucursal,
        'total'     => $cotizacion->total,
        'pagado'    => $cotizacion->getTotalPagado(),
    ]) ?>

    <?= $this->render('_pasos', ['actual' => 'guia', 'idreservas' => $reserva->idreservas]) ?>

    <div class="box box-success caja-paso">
        <div class="box-body">
            <h3 class="titulo-seccion"> Archivo de guía</h3>
            <p class="text-muted">Subí <strong>un</strong> archivo para esta reserva: PDF, Word, Excel o imagen (máx. 15 MB). Si subís otro, se reemplaza. El cliente y la organizadora pueden descargarlo desde la web.</p>

            <?php if ($guia): ?>
                <p>
                    <strong>Archivo actual:</strong>
                    <?= Html::a('<i class="fa fa-download"></i> ' . Html::encode($guia),
                        GuiaReserva::url($guia),
                        ['target' => '_blank', 'rel' => 'noopener',
                         'download' => GuiaReserva::nombreDescarga($reserva, $guia)]) ?>
                </p>
            <?php else: ?>
                <p class="text-muted"><em>Todavía no se cargó ningún archivo.</em></p>
            <?php endif; ?>

            <?= Html::beginForm(['guia', 'id' => $reserva->idreservas], 'post', ['enctype' => 'multipart/form-data']) ?>
                <div class="form-group">
                    <input type="file" name="guia" class="form-control"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" required>
                </div>
                <?= Html::submitButton('<i class="fa fa-upload"></i> Subir archivo', ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>

</section>

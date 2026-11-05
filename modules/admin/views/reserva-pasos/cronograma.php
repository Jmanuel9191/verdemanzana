<?php

use app\models\ReservaCronograma;
use app\models\ReservaPago;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $cronograma ReservaCronograma */

$this->title = 'Reserva: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['presupuesto', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = 'Cronograma';

$paquete = $cotizacion->lineaPaquete;
$titulo = ReservaCronograma::titulo($reserva);
$guardado = !$cronograma->isNewRecord;
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

    <?= $this->render('_pasos', ['actual' => 'cronograma', 'idreservas' => $reserva->idreservas]) ?>

    <?php $af = ActiveForm::begin(['id' => 'form-cronograma']); ?>

    <div class="box box-success caja-paso">
        <div class="box-body">
            <h3 class="titulo-seccion"> <?= Html::encode($titulo) ?></h3>

            <?= $af->field($cronograma, 'contenido')->widget(CKEditor::class, ReservaCronograma::opcionesEditor())->label(false) ?>

            <?php if ($guardado): ?>
                <p class="text-muted small">
                    Última actualización: <?= Html::encode(date('d/m/Y H:i', strtotime($cronograma->fecha_actualizacion))) ?>
                    por <?= Html::encode(ReservaPago::nombreAdmin($cronograma->adminActualizo)) ?>.
                </p>
            <?php endif; ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(' Guardar cronograma', ['class' => 'btn btn-success btn-lg']) ?>
            <?= Html::a('<i class="fa fa-arrow-left"></i> Detalles', ['detalles', 'id' => $reserva->idreservas], ['class' => 'btn btn-default btn-lg']) ?>
            <?= Html::a('Ir al listado de reservas', ['reservas/index'], ['class' => 'btn btn-default btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <!-- ===================== VISTA PREVIA (lo guardado) ===================== -->
    <div class="box box-default" id="vista-previa">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i> Vista previa — así se ve guardado (y así lo verá el cliente)</h3>
            <?php if ($guardado && $cronograma->tieneContenido()): ?>
                <div class="box-tools">
                    <?= Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $reserva->idreservas, 'tipo' => 'cronograma'],
                        ['class' => 'btn btn-danger btn-sm', 'target' => '_blank']) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="box-body">
            <?php if ($guardado && $cronograma->tieneContenido()): ?>
                <div class="cronograma-vista">
                    <h2 class="titulo-cronograma"><?= Html::encode($titulo) ?></h2>
                    <?= $cronograma->contenido /* ya limpio con HTMLPurifier al guardar */ ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Todavía no hay cronograma guardado. Pegalo arriba y tocá <b>Guardar cronograma</b>.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


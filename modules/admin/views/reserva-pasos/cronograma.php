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
            <?= Html::submitButton('<i class="fa fa-save"></i> Guardar cronograma', ['class' => 'btn btn-success btn-lg']) ?>
            <?= Html::a('<i class="fa fa-arrow-left"></i> Detalles', ['detalles', 'id' => $reserva->idreservas], ['class' => 'btn btn-default btn-lg']) ?>
            <?= Html::a('Ir al listado de reservas', ['reservas/index'], ['class' => 'btn btn-default btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <!-- ===================== VISTA PREVIA (lo guardado) ===================== -->
    <div class="box box-default" id="vista-previa">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i> Vista previa  </h3>
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

<?php
$this->registerCss(<<<CSS
.reserva-pasos .titulo-seccion { color:#527f1a; font-size:20px; font-weight:600; margin:5px 0 15px; border-bottom:1px solid #e3e3e3; padding-bottom:8px; }
.pasos-reserva { margin-bottom:0; }
.pasos-reserva > li > a { font-weight:600; }
.pasos-reserva .paso-numero { display:inline-block; width:22px; height:22px; line-height:22px; border-radius:50%; background:#527f1a; color:#fff; text-align:center; font-size:12px; margin-right:4px; }
.pasos-reserva > li.disabled .paso-numero { background:#bbb; }
.caja-paso { border-top-left-radius:0; }
.resumen-reserva { position:sticky; top:0; z-index:20; display:flex; flex-wrap:wrap; gap:10px 30px; align-items:center; background:#2f4a10; color:#fff; padding:10px 15px; margin-bottom:15px; border-radius:3px; box-shadow:0 2px 6px rgba(0,0,0,.2); }
.resumen-reserva .resumen-bloque { display:flex; gap:8px; align-items:center; }
.resumen-reserva small { opacity:.8; }
.resumen-reserva .resumen-montos { margin-left:auto; gap:20px; }
.resumen-reserva .resumen-montos div { display:flex; flex-direction:column; text-align:right; }
.resumen-reserva .resumen-montos b { font-size:16px; }
.resumen-reserva .res-saldo b { color:#ffd54f; }
.ayuda-cronograma { background:#fafcf7; border:1px dashed #c5d9a8; padding:10px 15px; border-radius:3px; margin-bottom:15px; }
.ayuda-cronograma kbd { background:#527f1a; }
.cronograma-vista .titulo-cronograma { color:#527f1a; font-size:22px; font-weight:600; margin:0 0 15px; text-align:center; }
CSS);
$this->registerCss(ReservaCronograma::cssVista());
?>

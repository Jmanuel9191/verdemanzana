<?php

use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\TimePicker;
use app\models\MomentosCronograma;

/* @var $this yii\web\View */
/* @var $model app\models\Reservas */
/* @var $form yii\widgets\ActiveForm */
/* @var $detalles app\models\DetalleCronograma[] */

$format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text;
    return state.text;
}
SCRIPT;
$escape = new \yii\web\JsExpression("function(m) { return m; }");
$this->registerJs($format, \yii\web\View::POS_HEAD);

$detalles = isset($detalles) ? $detalles : [];
$index = 0;

?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?php
    $secciones = [
        'ANTES DEL EVENTO',
        'BODA RELIGIOSA',
        'ANTES DE LA RECEPCIÓN SOCIAL',
        'CEREMONIA CIVIL',
        'INGRESO RECEPCION SOCIAL',
        'BAILES Y BRINDIS',
        'SERVICIOS Y EXPERIENCIAS',
        'CENA Y TORTA',
        'INICIO DE FIESTA',
        'FIN DEL EVENTO'
    ];

    foreach ($secciones as $seccion):
        $momentos = MomentosCronograma::find()
            ->where(['seccion' => $seccion, 'evento_tipo' => 'boda'])
            ->orderBy(['orden' => SORT_ASC])
            ->all();
        ?>
        <div class="col-xs-12 labelso">
            <div class="col-xs-12">
                <h3 class="coloh3"><?= Html::encode($seccion) ?></h3>
            </div>

            <?php foreach ($momentos as $momentocron):
                $detalle = isset($detalles[$momentocron->idmomento]) ? $detalles[$momentocron->idmomento] : null;
                ?>
                <div class="col-xs-12" style="margin-bottom: 25px;">
                    <label><strong><?= Html::encode($momentocron->momento) ?></strong></label>
                    <div class="row">
                        <div class="col-md-2 col-xs-6">
                            <?= Html::label('Hora inicio') ?>
                            <?= TimePicker::widget([
                                'name' => "hora_inicio[$index]",
                                'value' => $detalle ? $detalle->hora_inicio : '00:00',
                                'pluginOptions' => [
                                    'showSeconds' => false,
                                    'showMeridian' => false,
                                ],
                                'options' => ['class' => 'form-control']
                            ]) ?>

                        </div>
                        <div class="col-md-2 col-xs-6">
                            <?= Html::label('Hora fin') ?>
                            <?= TimePicker::widget([
                                'name' => "hora_fin[$index]",
                                'value' => $detalle ? $detalle->hora_fin : '00:00',
                                'pluginOptions' => [
                                    'showSeconds' => false,
                                    'showMeridian' => false,
                                ],
                                'options' => ['class' => 'form-control']
                            ]) ?>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <?= Html::label('Proveedor') ?>
                            <?= Html::textInput("proveedor[$index]", $detalle ? $detalle->proveedor : '', ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <?= Html::label('Teléfono') ?>
                            <?= Html::textInput("telefono[$index]", $detalle ? $detalle->telefono : '', ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <?= Html::label('Descripción') ?>
                            <?= Html::textarea("descripcion[$index]", $detalle ? $detalle->descripcion : '', ['class' => 'form-control', 'rows' => 1]) ?>
                        </div>
                        <?= Html::hiddenInput("idmomento[$index]", $momentocron->idmomento) ?>
                    </div>
                </div>
                <?php $index++; endforeach; ?>
        </div>
        <hr>
    <?php endforeach; ?>

    <div class="form-group col-xs-12">
        <div class="col-xs-12 btnactial" style="margin-bottom: 20px">
            <?= Html::submitButton('Guardar Cronograma', ['class' => 'btn btn-success']) ?>
            <br>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    h3.coloh3 {
        margin-bottom: 10px;
    }
    label strong {
        color: GREEN;
        font-weight: 600;
        font-size: 14px;
    }
</style>
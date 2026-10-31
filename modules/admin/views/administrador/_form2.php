<?php

use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$format = <<< SCRIPT
function format(state) {
if (!state.id) return state.text; // optgroup
return state.text;
}
SCRIPT;
$escape = new \yii\web\JsExpression("function(m) { return m; }");
$this->registerJs($format, \yii\web\View::POS_HEAD);
?>

<div class="box-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL
        ] // important
    ]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tipo_admin')->dropDownList(
        [    '1' => 'Todo',
            '2' => 'Reservas',


        ]
    ) ?>
    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>


    <div class="form-group" align="center">




        <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

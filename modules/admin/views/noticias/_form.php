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
$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>


<div class="box-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL
        ] // important
    ]); ?>



    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcategoria')->widget(Select2::classname(), [

        'data' => \app\models\Categoria::getSelectMenu('noticias'),
        'language' => 'es',
        'options' => [
            'placeholder' => 'Categorias',
            //'multiple' => true,
        ],
        'pluginOptions' => [
            'templateResult' => new \yii\web\JsExpression('format'),
            'templateSelection' => new \yii\web\JsExpression('format'),
            'escapeMarkup' => $escape,
            'allowClear' => true
        ],
    ]); ?>



    <!--  <?//= $form->field($model, 'resumen')->textarea(['rows' => 6]) ?> -->

    <?= $form->field($model, 'descripcion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
            'allowedContent'=> true,
            'extraAllowedContent' => 'div',
        ]
    ]) ?>



   <!-- <?//= $form->field($model, 'otradescripcion')->hiddenInput(['value'=>'otradescripcion'])->label(false); ?> -->


    <?= $form->field($model, 'foto_portada')->widget(\sadovojav\cutter\Cutter::className(), [
        'cropperOptions' => [
            'aspectRatio' => 302 / 302,
        ]
    ]); ?>
    <!-- <?/*= $form->field($model, 'foto_contenido')->widget(\sadovojav\cutter\Cutter::className(), [
        'cropperOptions' => [
            /*'aspectRatio' => 750 / 418,
        ]
    ]); */?>-->

    <!--<?//= $form->field($model, 'fuente')->textInput(['maxlength' => true]) ?>
    <?//= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>-->
    <?= $form->field($model, 'fecha_noticia')->widget(
        DatePicker::className(), [
        // modify template for custom rendering
        'language' => 'es',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayBtn' => "linked",
            'keyboardNavigation' => false,
            'forceParse' => false
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>
<?php
$initial = [];
array_push($initial, Html::img('@web/imagen/proyectos/' . $model->foto_portada, ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:160px']));
?>
<?php
$initial2 = [];
array_push($initial2, Html::img('@web/imagen/proyectos/' . $model->logo, ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:160px']));
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
        'data' => \app\models\Categoria::getSelectMenu('proyectos'),
        'language' => 'es',
        'options' => [
            'placeholder' => 'Categorias',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'templateResult' => new \yii\web\JsExpression('format'),
            'templateSelection' => new \yii\web\JsExpression('format'),
            'escapeMarkup' => $escape,
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'descripcion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
    <?= $form->field($model, 'otrascaracteristicas')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'file')->widget(\kartik\widgets\FileInput::classname(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'initialPreviewFileType' => 'image',
            //'maxFileCount' => 10,
            'allowedFileExtensions' => ['jpg', 'png', 'gif'],
            'initialPreview' => $initial,
            "language" => "es"
        ]
    ]); ?>
    <?= $form->field($model, 'file2')->widget(\kartik\widgets\FileInput::classname(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'initialPreviewFileType' => 'image',
            //'maxFileCount' => 10,
            'allowedFileExtensions' => ['jpg', 'png', 'gif'],
            'initialPreview' => $initial2,
            "language" => "es"
        ]
    ]); ?>

    <?= $form->field($model, 'tipo')->dropDownList(['A' => 'TERMINADOS','B'=>'EN CONSTRUCCION','C'=>'HISTORICO']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

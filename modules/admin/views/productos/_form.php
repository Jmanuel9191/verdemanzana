<?php

use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Productos */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>
<?php
$initial = [];
array_push($initial, \yii\helpers\Url::to(['imagen/productos/' . $model->archivo]));
?>
<div class="box-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL
        ] // important
    ]); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?php
    $categorias = $model->categorias;
    if(!empty($categorias) && empty($model->idcategorias)){
        $temp = array();
        foreach ($categorias as $key => $item)
        {
            $temp[$key] = $item['idcategoria'];
        }
        $model->idcategorias = $temp;
    }
    ?>
    <?= $form->field($model, 'idcategorias')->widget(Select2::classname(), [
        'data' => \app\models\Categoria::getSelectMenu('productos'),
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

    <!--<?//= $form->field($model, 'resumen')->textarea(['rows' => 6]) ?>-->

    <?= $form->field($model, 'descripcion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
            'allowedContent'=> true,
            'extraAllowedContent' => 'div',
        ]
    ]) ?>

    <?= $form->field($model, 'precio')->textInput(['maxlength' => true]) ?>

   <!-- <?/*= $form->field($model, 'otradescripcion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) */?>-->


    <!-- <?//= $form->field($model, 'preciooferta')->textInput(['maxlength' => true]) ?>
    <?//= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'foto')->widget(\sadovojav\cutter\Cutter::className(), [
        'cropperOptions' => [
            'aspectRatio' => 278 / 185,
        ]
    ]); ?>
    <!-- <?/*= $form->field($model, 'file')->widget(\kartik\widgets\FileInput::classname(), [
        'options' => [
            'multiple' => true,
            'accept' => 'application/pdf',
        ],
        'pluginOptions' => [
            'initialPreviewFileType' => 'application/pdf',
            //'maxFileCount' => 10,
            'allowedFileExtensions' => ['pdf'],
            'initialPreview' => $initial,
            "language" => "es"
        ]
    ]); */?>-->
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

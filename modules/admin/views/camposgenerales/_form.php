<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\FormTextosEstaticos */
/* @var $form yii\widgets\ActiveForm */

$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>

    <div class="box-body">
<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL],
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'grupo')->widget(Select2::classname(), [
                'data' => [
                    'Recomendacion General Boda' => 'Recomendacion General Boda',
                    'Recomendacion General Quinceanos' => 'Recomendacion General Quinceaños',

                ],
                'language' => 'es',
                'options' => ['placeholder' => 'Seleccionar grupo...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'templateResult' => new \yii\web\JsExpression('format'),
                    'templateSelection' => new \yii\web\JsExpression('format'),
                    'escapeMarkup' => $escape,
                ],
            ]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'etiqueta_label')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
<?= $form->field($model, 'contenido_html')->widget(CKEditor::className(), [
    'options' => ['rows' => 6],
    'preset' => 'full',
    'clientOptions' => [
        'allowedContent' => true,
        'extraAllowedContent' => 'div(*)[*];span(*)[*];p(*)[*];ul;li;img[*];a[*]',
    ]
]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'orden')->input('number') ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'visible')->dropDownList([1 => 'Sí', 0 => 'No']) ?>
        </div>
    </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
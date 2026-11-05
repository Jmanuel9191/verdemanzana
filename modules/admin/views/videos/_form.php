<?php



use kartik\form\ActiveForm;
use kartik\select2\Select2;
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

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php /*= $form->field($model, 'tumb')->textInput(['maxlength' => true]) */ ?>




    <?= $form->field($model, 'idcategoria')->widget(Select2::classname(), [

        'data' => \app\models\Categoria::getSelectMenu('videos'),
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



    <?= $form->field($model, 'foto_portada')->widget(\sadovojav\cutter\Cutter::className(), [
        'cropperOptions' => [
            'aspectRatio' => 278 / 185,
        ]
    ]); ?>



    <?php /*= $form->field($model, 'tags')->textarea(['rows' => 6]) */ ?>

    <?php /*= $form->field($model, 'posicion')->textInput() */ ?>

    <?php /*= $form->field($model, 'fecha_video')->textInput() */ ?>

    <?php /*= $form->field($model, 'fecha_registro')->textInput() */ ?>

    <?php /*= $form->field($model, 'estado')->textInput(['maxlength' => true]) */ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

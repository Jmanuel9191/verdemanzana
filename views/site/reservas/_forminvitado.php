<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invitados */

?>
<?php $form = ActiveForm::begin([
    'id' => 'form-invitado', // ← agrega esta línea
    'options' => ['enctype' => 'multipart/form-data'],
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL],
]); ?>


<?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'nro_mesa')->input('number', ['min' => 1]) ?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Actualizar', ['class' => 'btn btn-success']) ?>
        <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

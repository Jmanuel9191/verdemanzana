<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invitados */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invitados-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL],
    ]); ?>

    <!-- Campo oculto para mantener la relación con la reserva -->
    <?= $form->field($model, 'idreservas')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nro_mesa')->input('number', ['min' => 1]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Agregar invitado' : 'Actualizar', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', ['invitados', 'idreservas' => $model->idreservas], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <br><br>
</div>

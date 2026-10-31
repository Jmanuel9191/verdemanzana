<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarritoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carrito-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcarrito') ?>

    <?= $form->field($model, 'idusuario') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'fecha_registro') ?>

    <?= $form->field($model, 'nit') ?>

    <?php // echo $form->field($model, 'razon_social') ?>

    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'monto_total') ?>

    <?php // echo $form->field($model, 'fecha_pago') ?>

    <?php // echo $form->field($model, 'tipo_pago') ?>

    <?php // echo $form->field($model, 'tipo_carro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

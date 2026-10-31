<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CategoriaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categoria-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcategoria') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'imagen') ?>

    <?= $form->field($model, 'nombre_cat_hijos') ?>

    <?php // echo $form->field($model, 'link1') ?>

    <?php // echo $form->field($model, 'link2') ?>

    <?php // echo $form->field($model, 'imagen3') ?>

    <?php // echo $form->field($model, 'idpadre') ?>

    <?php // echo $form->field($model, 'posicion') ?>

    <?php // echo $form->field($model, 'modulo') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

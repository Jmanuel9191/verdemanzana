<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BannerSeach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idbanner') ?>

    <?= $form->field($model, 'idcategoria') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'foto') ?>

    <?= $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'destino') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'posicion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VideosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="videos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idvideo') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'video') ?>

    <?= $form->field($model, 'tumb') ?>

    <?php // echo $form->field($model, 'idcategoria') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'posicion') ?>

    <?php // echo $form->field($model, 'fecha_video') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias_Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idnoticia') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'idcategoria') ?>

    <?= $form->field($model, 'resumen') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'fuente') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'fecha_noticia') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'tumb') ?>

    <?php // echo $form->field($model, 'desc_foto') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'disponible') ?>

    <?php // echo $form->field($model, 'cantidad') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'link1') ?>

    <?php // echo $form->field($model, 'posicion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

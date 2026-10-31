<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProyectosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proyectos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idproyecto') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'idcategoria') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'otrascaracteristicas') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'foto_portada') ?>

    <?php // echo $form->field($model, 'precio') ?>

    <?php // echo $form->field($model, 'tag') ?>

    <?php // echo $form->field($model, 'destacado') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

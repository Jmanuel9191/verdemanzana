<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Productos_search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idproducto') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'idcategoria') ?>

    <?= $form->field($model, 'resumen') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'precio') ?>

    <?php // echo $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'codigo') ?>

    <?php // echo $form->field($model, 'link1') ?>

    <?php // echo $form->field($model, 'link2') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'fotop') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'nuevo') ?>

    <?php // echo $form->field($model, 'promocion') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'posicion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

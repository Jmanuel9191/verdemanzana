<?php

use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categoria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <!--<?//= $form->field($model, 'imagen')->textInput(['maxlength' => true]) ?>-->



    <?php // Top most parent
    echo $form->field($model, 'modulo')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Modulos::findAll(['estado'=>'1']), 'idmodulo', 'nombre'),
        'language' => 'es',
        'options' => ['placeholder' => 'Modulos'],
    ]); ?>

        <?= $form->field($model, 'idpadre')->widget(DepDrop::classname(), [
        // Al editar: cargar las categorías del módulo actual para mostrar el nombre, no el número.
        // Se excluye la categoría que se está editando (no puede ser padre de sí misma).
        'data' => !empty($model->modulo)
            ? \yii\helpers\ArrayHelper::map(
                \app\models\Categoria::find()
                    ->where(['modulo' => $model->modulo])
                    ->andFilterWhere(['<>', 'idcategoria', $model->idcategoria])
                    ->all(),
                'idcategoria',
                'nombre'
            )
            : [],
        'options' => ['placeholder' => 'Categorias'],
        'type' => DepDrop::TYPE_SELECT2,
        'pluginOptions' => [
            'depends' => ['categoria-modulo'],
            'placeholder' => 'Categorias',
            'url' => \yii\helpers\Url::to(['subcat'])
        ]
    ]) ?>

    <?php /*= $form->field($model, 'posicion')->textInput() */ ?>

    <?php /*= $form->field($model, 'modulo')->textInput()*/ ?>

    <?php /*= $form->field($model, 'estado')->textInput(['maxlength' => true]) */ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

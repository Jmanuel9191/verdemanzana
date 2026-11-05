<?php

use app\helpers\FormatoHelper;
use app\models\Items;
use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use yii\helpers\Html;


?>
<div class="box-body">

    <?php $form = ActiveForm::begin([
        'id' => 'form-items',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL],
    ]); ?>

    <?= $form->field($model, 'tipo')->dropDownList(Items::tipos(), ['prompt' => 'Elegí...'])
        ?>

    <?= $form->field($model, 'nombre')->textInput([
        'maxlength' => true,
        'placeholder' => 'Ej. Silla Tiffany adicional / DJ show 4 horas',
    ]) ?>

    <div id="campos-proveedor" data-campo="<?= Html::getInputId($model, 'tipo') ?>">
        <?= $form->field($model, 'empresa')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ej. Titos Bar',
        ]) ?>

        <?= $form->field($model, 'telefono')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ej. 70012345',
            'inputmode' => 'tel',
        ]) ?>
    </div>

    <?= $form->field($model, 'unidad')->dropDownList(Items::unidades())
        ->hint('Cómo se cobra. En la reserva se carga la cantidad (ej. 20 unidades, 3 horas).') ?>

    <?= $form->field($model, 'precio_unitario', [
        'addon' => ['prepend' => ['content' => 'Bs']],
    ])->textInput([
        'inputmode' => 'decimal',
        'placeholder' => 'Ej. 15 o 15,50',
    ]) ?>

    <?= $form->field($model, 'tipo_evento')->dropDownList(Items::tiposEvento())
         ?>

    <?= $form->field($model, 'detalle')->widget(CKEditor::class, FormatoHelper::opcionesEditor(200)) ?>



    <?= $form->field($model, 'estado')->dropDownList(Items::estados())
        ->hint('Inactivo = no se ofrece en reservas nuevas.') ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton(' ' . ($model->isNewRecord ? 'Crear' : 'Guardar cambios'), [
                'class' => 'btn btn-success',
            ]) ?>
            <?= Html::a('Cancelar', ['index', 'ItemsSearch[tipo]' => $model->tipo ?: Items::TIPO_ITEM], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

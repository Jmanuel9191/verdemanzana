<?php

use app\helpers\FormatoHelper;
use app\models\Paquetes;
use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Paquetes */
?>
<div class="box-body">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL],
    ]); ?>

    <?= $form->field($model, 'nombre')->textInput([
        'maxlength' => true,
        'placeholder' => 'Ej. Bodas Premium',
        'autofocus' => $model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'tipo_evento')->dropDownList(Paquetes::tiposEvento())
        ->hint('"Todos" = el paquete se ofrece para cualquier tipo de evento.') ?>

    <?= $form->field($model, 'precio', [
        'addon' => ['prepend' => ['content' => 'Bs']],
    ])->textInput([
        'inputmode' => 'decimal',
        'placeholder' => 'Ej. 280 o 280,50',
    ])->hint('Precio por invitado') ?>

    <?= $form->field($model, 'invitados_incluidos')->input('number', [
        'min' => 0,
        'step' => 1,
        'placeholder' => 'Opcional, ej. 100',
    ])->hint('Cantidad de invitados con la que arranca la cotización.') ?>

    <?= $form->field($model, 'horas_incluidas')->input('number', [
        'min' => 0,
        'step' => 1,
        'placeholder' => 'Opcional',
    ]) ?>

    <?= $form->field($model, 'detalle')->widget(CKEditor::class, FormatoHelper::opcionesEditor())->hint('Al pegar desde Word se conservan negritas, listas, tamaños, colores y alineación; el resto se limpia.') ?>

 

    <?= $form->field($model, 'estado')->dropDownList(Paquetes::estados())
        ->hint('Un paquete inactivo no se ofrece en reservas nuevas.') ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton(
                $model->isNewRecord ? ' Crear paquete' : ' Guardar cambios',
                ['class' => 'btn btn-success']
            ) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

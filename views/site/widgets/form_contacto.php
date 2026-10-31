<?php
use yii\bootstrap\ActiveForm;
$model = new  \app\models\ContactForm();
?>
<div class="col-md-6 contacto">
    <h4>Formulario de Contacto</h4>
    <p>Ante cualquier consulta o sugerencia no dude en ponerse en contacto con nosotros.</p>

        <?php $form = ActiveForm::begin(['id' => 'frmContact','options'=>['class'=>'frm-contacto'],'action'=>['cuenta/contacto'],'method'=>'post']); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'nombre')->textInput(['autofocus' => true, 'placeholder' => 'Nombre completo','class'=>'form-control'])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email','class'=>'form-control'])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'pais')->textInput(['autofocus' => true, 'placeholder' => 'Pais','class'=>'form-control'])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ciudad')->textInput(['autofocus' => true, 'placeholder' => 'Ciudad','class'=>'form-control'])->label(false) ?>
            </div>


            <div class="col-md-12">
                <?= $form->field($model, 'mensaje')->textarea(['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Consulta...']) ?>
            </div>
            <div class="col-md-12" align="center">
                <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn">Enviar</button>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>

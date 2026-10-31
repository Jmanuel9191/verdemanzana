<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form ActiveForm */
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message): ?>
    <?= \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? \yii\helpers\Html::encode($message['message']) : 'Message Not Set!',
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]); ?>
<?php endforeach; ?>
<div id="mainbody">
    <div id="producto-titulo">
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-padding text-blue">
                    <h2 class="text-ralewaybold42">CARRITO</h2>
                    <p class="text-SourceSansPro16" id="breadcrum">Inicio <span class="arrow">&nbsp;</span>CARRITO</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-spacing4 bottom-spacing3">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-4 control-label'],
            ],
            //'options'=>['class'=>'col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2']
        ]); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'nombre') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'apellidos') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'direccion') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'telefono') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'pais') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ciudad') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'contrasena')->passwordInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_nacimiento')->widget(
                    \kartik\widgets\DatePicker::className(), [
                    // modify template for custom rendering
                    'language' => 'es',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayBtn' => "linked",
                        'keyboardNavigation' => false,
                        'forceParse' => false
                    ]
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'celular') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'sexo')->dropDownList(['Hombre', 'Mujer']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div><!-- site-register -->
</div><!-- site-register -->

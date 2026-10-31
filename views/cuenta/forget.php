<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('@web/web/js/vendor/jquery-1.11.1.min.js', ['position' => \yii\web\View::POS_END]);

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
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
        <h1>Recuperar Contrase&ntilde;a</h1>
        <p> Ingrese su correo electronico para realizar la recuperacion de contrase&ntilde;a, recuerde que la
            informacion
            proporcionada sera via correo electronico.</p>
        <br><br>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-4 control-label'],
            ],
            'options' => ['class' => 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3']
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Ingrese su correo electronico']) ?>


        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Ingresar', ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

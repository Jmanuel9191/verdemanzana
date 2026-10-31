<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="mainbody">
    <div id="producto-titulo">
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-padding text-blue">
                    <h2 class="text-ralewaybold42">Login </h2>
                    <p class="text-SourceSansPro16" id="breadcrum">Inicio <span class="arrow">&nbsp;</span>Login</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-spacing4 bottom-spacing3 ">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <h1><?= Html::encode($this->title) ?></h1>
 
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?php /*= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) */ ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
    </div>
</div>

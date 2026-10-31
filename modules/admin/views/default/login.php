<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= Yii::$app->homeUrl ?>"><b>Admin</b>M&M</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Ingreso a sistema de administracion</p>
        <?php $form = ActiveForm::begin(['class' => 'form-signin']); ?>

        <?= $form->field($model, 'username', [
            'inputOptions' => ['placeholder' => 'Usuario'],
            'inputTemplate' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>',
        ])->label(false) ?>
        <?= $form->field($model, 'password', [
            'inputOptions' => ['placeholder' => 'Contraseña'],
            'inputTemplate' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>',
        ])->label(false)->passwordInput() ?>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" value="remember-me"> Recordarme
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <br>
    <p class="text-center">Si necesitas comunicarte o saber mas de nosotros <br>contactanos o siguenos en:</p>
    <p class="text-center">
        <a href="mailto:soporte@marcaymercado.com"><i class="fa fa-envelope fa-2x" style="color: #9b6da9"></i>&nbsp;</a>
        <a href=""><i class="fa fa-facebook-square fa-2x" style="color: #3b579d"></i>&nbsp;</a>
        <a href=""><i class="fa fa-twitter-square fa-2x" style="color: #1da1f2"></i>&nbsp;</a>
        <a href=""><i class="fa fa-youtube-square fa-2x" style="color: #c12025"></i>&nbsp;</a>
    </p>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

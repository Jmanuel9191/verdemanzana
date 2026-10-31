<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Ingresar';
$this->params['breadcrumbs'][] = $this->title;
$script = <<<CSS
.help-block.help-block-error 
{
display: block!important;
}

@media (max-width: 767px){
.form-horizontal .control-label {
    font-family: 'Open Sans', sans-serif!important;
    text-align: left!important;
    display: block;
    margin-bottom: 8px!important;
    font-size: 15px!important;
}
}



.well {
    min-height: 20px;
    padding: 19px;
    margin-bottom: 20px;
    background-color: #f2f2f3;
    border: 1px solid #527f1a;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    margin-top: 70px;
    margin-bottom: 60px;
}

.well h1 {
    margin-top: 10px;
    /* font-family: 'Segoe UI Semi Bold'!important; */
    font-family: 'Open Sans', sans-serif!important;
font-style: italic;
    color: #527f1a;
    font-weight: 600;
    font-size: 30px;
    margin-bottom: 30px;
}

.form-horizontal .control-label {
        font-family: 'Open Sans', sans-serif!important;
       color: #15364f;
}

.registrarse{
    padding: 10px 20px;
    background: #527f1a;
    color: white;
    font-weight: 600;font-style: italic;
    margin-bottom: 20px;
    font-family: 'Open Sans', sans-serif!important;
}
.plomo-1t{
    color: #ef6890; font-style: italic;
       font-family: 'Open Sans', sans-serif!important;
}

CSS;
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
?>



<?= $this->render('../banners/fijo',
    ['titulo' => 'Login de Organizadora',])

?>



<div class="col-xs-12" style="padding: 0">




    <div class="container">

        <div class="row top-spacing4 bottom-spacing3 text-center">
            <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
                <div class="well">
                    <h1>Iniciar sesion</h1>

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
                        <div class="col-lg-offset-1 col-lg-10">
                            <?= Html::submitButton('Aceptar', ['class' => 'btn registrarse', 'name' => 'login-button']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>



                    <div class="row">


                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

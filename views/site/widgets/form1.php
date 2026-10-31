<?php

use yii\helpers\Html;

$model = new  \app\models\LoginWeb();
?>


<div class="col-xs-12">
    <p class="latofort"> Si usted es cliente por favor ingrese los siguientes datos para acceder al sistema</p>

</div>


<?= Html::beginForm('http://sistemadegestion.agenciamym.com/mym/cookieClientes.asp', 'post', ['class'=>'contact-form']) ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">

                <?= Html::textInput('username','',[ 'class'=>'cprocontb4','placeholder'=>'Usuario', 'required' => true])?>

            </div>
        </div>





        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::passwordInput('contrasena','',['class'=>'cprocontb4','placeholder'=>'Contraseña' ])?>

            </div>
        </div>



        <div class="col-xs-12 margin-top-20 text-center">

            <?= Html::submitButton('INGRESAR', ['class' => 'btn btn-lg', 'name' => 'login-button', 'style'=> 'width: 100%']) ?>
        </div>

    </div>
<?= Html::endForm() ?>



<?php
/// solucion geoposicion https://developers.google.com/maps/documentation/geolocation/intro
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */


use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use linslin\yii2\curl;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;



$configuracion = \app\models\Configuracion::find()->one();
$this->render('widgets/metatags', ['model' => $configuracion]);
/*

$dir = explode(',', $configuracion['coordgoogle']);
$coord = new LatLng(['lat' => $dir[0], 'lng' => $dir[1]]);
$map = new Map([
    'center' => $coord,
    'zoom' => 16,
    'width' => '100%',
    'height' => '550',
]);

// Lets add a marker now
$marker = new \dosamigos\google\maps\overlays\Marker([
    'position' => $coord,
    'title' => $configuracion['nombre_empresa'],
    'icon'  => Url::to('@web/assets_b/images/globo.png')
]);
$marker->attachInfoWindow(
    new \dosamigos\google\maps\overlays\InfoWindow([
        'content' => "<p>{$configuracion['nombre_empresa']}</p>"
    ])
);
$map->addOverlay($marker);*/



$dir = explode(',', $configuracion['coordgoogle']);
$coord = new LatLng(['lat' => $dir[0], 'lng' => $dir[1]]);
$map = new Map([
    'center' => $coord,
    'zoom' => 13,
    'width' => '100%',
    'height' => '550',
]);
//$img = Url::to('@web/web/images/globo.png');
// Lets add a marker now
$marker = new \dosamigos\google\maps\overlays\Marker([
    'position' => new LatLng(['lat' => '-17.819022', 'lng' => '-63.216821']),
    'title' => $configuracion['titulo_pagina'],

    //'icon' => $img
]);
$marker->attachInfoWindow(
    new \dosamigos\google\maps\overlays\InfoWindow([
        'content' => "<p>{$configuracion['titulo_pagina']}</p><div class=\"altobox\">SUCURSAL 1 <br> </div>"
    ])
);
$map->addOverlay($marker);
// Lets add a marker now
$marker = new \dosamigos\google\maps\overlays\Marker([
    'position' => new LatLng(['lat' => '-17.802409, ', 'lng' => '-63.217653']),


    'title' => $configuracion['titulo_pagina'],
   // 'icon' => $img
]);
$marker->attachInfoWindow(
    new \dosamigos\google\maps\overlays\InfoWindow([
        'content' => "<p>{$configuracion['titulo_pagina']}</p><div class=\"altobox\">SUCURSAL 2 <br> Av. Hilanderia entre 4° y 5° anillo</div>"
    ])
);
$map->addOverlay($marker);
// Lets add a marker now
$marker = new \dosamigos\google\maps\overlays\Marker([
    'position' => new LatLng(['lat' => '-17.792389', 'lng' => '-63.172810']),


    'title' => $configuracion['titulo_pagina'],
   // 'icon' => $img
]);
$marker->attachInfoWindow(
    new \dosamigos\google\maps\overlays\InfoWindow([
        'content' => "<p>{$configuracion['titulo_pagina']}</p><div class=\"altobox\">SUCURSAL 3 <br> Av. Capitan Arrien entre 1er y 2do anillo / La sucursal se llama Parque Urbano</div>"
    ])
);

$map->addOverlay($marker);





$script = <<<CSS
.help-block-error{
display: none;
}
.contacto{
   
    background-size: cover;
    position: relative;
    
   
}
.padding-top-90 {
    padding-top: 5.529rem;
}

.form-control {
    display: block;
    width: 70%;}

.field-contactform-verifycode{
width: 70%;
}
CSS;
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);
$this->registerjsFile('@web/assets_b/js/plugins.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]);


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);

?>






<?= $this->render('banners/fijo',
    ['titulo' => 'Contacto',])

?>




<div class="bgnovedades1 colplomo">
    <div class="bgnovedades2"></div>
    <div class="container">


        <br>
        <h2 class="txt-h2 text-center">Nuestras Sucursales</h2>
        <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
        <br>

        <div class="lineanove"></div>
        <div class="row">
            <div class="col-md-4">
                <h2 class="acc_trigger ">
                    <a href="#"><span
                                class="tit_acor" style="color: #527f1a;">Doble via a la guardia</span></a></h2>


             <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salongg.jpg') ?>" class="img-responsive" alt="">








                <br>


                <div class="acc_container">
                    <div class="row">
                        <div class="col-xs-12  mov6 tam14">
                            <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Doble via a la Guardia entre 5° y 6° anillo #5195<br>
                            <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 77094165<br>
                            <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                            <div class="tam14 colrojo">
                                <strong> Horario de atención</strong><br>
                                Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                Sab:    9:00 a 13:00  <br>
                                <strong>Hacer cita previa</strong>
                            </div><br>


                        </div>

                        <br><br>





                    </div>
                </div>
            </div>



            <div class="col-md-4">
                <h2 class="acc_trigger "><a ><span
                                class="tit_acor" style="color: #527f1a;">Parque Urbano</span></a></h2>

                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salonparqueurbano1.jpg') ?>" class="img-responsive" alt="">



                <br>


                <div class="acc_container">
                    <div class="row">
                        <div class="col-xs-12  mov6 tam14">
                            <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Parque Urbano / Av. Capitan Arrien entre 1er y 2do anillo <br>
                            <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 77094165<br>
                            <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                            <div class="tam14 colrojo">
                                <strong> Horario de atención</strong><br>
                                Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                Sab:    9:00 a 13:00  <br>
                                <strong>Hacer cita previa</strong>
                            </div><br>



                        </div>

                        <br><br>




                    </div>
                </div>
            </div>



            <div class="col-md-4">
                <h2 class="acc_trigger "><a href="#" ><span
                                class="tit_acor" style="color: #527f1a;" >Garden</span></a></h2>

                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salongarden1.jpg') ?>" class="img-responsive" alt="">




                <br>


                <div class="acc_container">
                    <div class="row">
                        <div class="col-xs-12  mov6 tam14">
                            <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Av. Esmeralda esq. Onix zona Doble Vía La Guardia<br>
                            <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 77094165<br>
                            <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                            <div class="tam14 colrojo">
                                <strong> Horario de atención</strong><br>
                                Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                Sab:    9:00 a 13:00  <br>
                                <strong>Hacer cita previa</strong>
                            </div><br>



                        </div>

                        <br><br>





                    </div>
                </div>
            </div>


        </div>


        <div class="clear"></div>
        <br>
        <div class="lineanove"></div>
        <div class="row">

        </div>
    </div>
    <div class="bgnovedades3"></div>
</div>




<div class="contactos">
<div class="container">
    <div class="row">




            <h2 class="txt-h2 text-center">Formulario de contacto</h2>
            <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
            <br>
            <hr/>
            <!--<div class="col-xs-12 col-sm-6 col-md-6">
        <h3>Información de contacto</h3>
        <p><span style="font-weight: bold;">Direcci&oacute;n: </span><?//php echo $direccion_empresa; ?></p>
        <p><span style="font-weight: bold;">Tel&eacute;fonos: </span><?//php echo $telefono_empresa; ?></p>
        <p><span style="font-weight: bold;">Email.: </span><?//php echo $email; ?></p>
        <p>Santa Cruz - Bolivia</p>
        <br/>
    </div>-->


        <?php $form = ActiveForm::begin([

            'options'=>['class'=>'contact-form'],
            'action'=>['site/contacto2'],'method'=>'post',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"\">{input}</div>\n<div class=\"\">{error}</div>",
            ],
            //'options'=>['class'=>'col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2']
        ]); ?>


            <div class="form-group" align="center">
                <?= $form->field($model, 'nombre')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Nombre Completo' , 'required' => true  ]) ?>
            </div>


        <div class="form-group" align="center">


            <?= $form->field($model, 'email')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Email' , 'required' => true  ]) ?>

        </div>


        <div class="form-group" align="center">
                <?= $form->field($model, 'telefono')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Teléfono' , 'required' => true  ]) ?>


            </div>


        <div class="form-group" align="center">

                <?= $form->field($model, 'mensaje')->label(false)->textarea(['rows' => 12, 'cols'=> 20, 'class'=>'form-control ','placeholder'=>'Dime tu consulta:']) ?>

                <br/>


                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-md-4  col-xs-12">{image}</div><div class="col-md-8 col-xs-12">{input}</div></div>',
                    'options'=>['class'=>'form-control input-sm','placeholder'=>'Insertar Captcha'],
                ])->label(false) ?>

            </div>


            <br>
        <div class="form-group" align="center" >
            <input type="submit" name="enviar" id="submit_contact" class="btn btn-success" align="center" value="Enviar" style="margin-bottom: 33px;width: 200px;">

        </div>
            <?php ActiveForm::end(); ?>


        <hr>
            <h3>Mapa de Ubicación</h3>
        </div>


    </div>
    <br>

    <div class="mapa">

        <div class="" ><br>
            <?php echo $map->display();?>
        </div>
    </div>
</div>









<?php
$script = <<<JS

 $(".image-popup").magnificPopup({
type: "image",
closeOnContentClick: false,
removalDelay: 600,
mainClass: "my-mfp-slide-bottom",
image: {
verticalFit: false
}
});



$(".popup-gallery").magnificPopup({
delegate: "a",
type: "image",
fixedContentPos: true,
fixedBgPos: true,
tLoading: "Loading image #%curr%...",
removalDelay: 600,
closeBtnInside: true,
zoom: {
enabled: true,
duration: 700
},
gallery: {
enabled: true,
navigateByImgClick: true,
preload: [ 0, 1 ]
},
image: {
tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
}
});


JS;
$this->registerJs($script, \yii\web\View::POS_END);
echo $this->render('widgets/geolocation');
?>





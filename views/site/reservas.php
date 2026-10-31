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



$dir = explode(',', $configuracion['coordgoogle']);
$coord = new LatLng(['lat' => $dir[0], 'lng' => $dir[1]]);
$map = new Map([
    'center' => $coord,
    'zoom' => 16,
    'width' => '100%',
    'height' => '450',
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
CSS;

$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);

?>



<?= $this->render('banners/fijo',
    ['titulo' => 'Reservas',])

?>

<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Formulario de Reservas</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>
    <br>
    <div class="col-md-10 col-md-offset-2">
        <ul class="nav nav-tabs text-center responsive" role="tablist">
            <li role="presentation" class="active col-md-3 col-sm-6">
                <a href="#infantiles" aria-controls="infantiles" role="tab" data-toggle="tab">
                    <strong class="servicio-txt1">Salón 1</strong><br />
                    Doble via la guardia entre 5° y 6° anillo #5195
                </a>
            </li>
            <li role="presentation" class="col-md-3 col-sm-6">
                <a href="#baby" aria-controls="baby" role="tab" data-toggle="tab">
                    <strong class="servicio-txt1">Salón 2</strong><br />
                    Av. Hilanderia entre 4° y 5° anillo
                </a>
            </li>

            <li role="presentation" class="col-md-3 col-sm-6">
                <a href="#panel3" aria-controls="baby" role="tab" data-toggle="tab">
                    <strong class="servicio-txt1">Salón 3</strong><br />
                    Parque Urbano / Av. Capitan Arrien entre 1er y 2do anillo
                </a>
            </li>

        </ul>
        <br>
        <div class="tab-content responsive">
            <div role="tabpanel" class="tab-pane fade in active" id="infantiles">

                <?= $this->render('widgets/formreserva')


                ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="baby">
                <?= $this->render('widgets/formreserva2')

                ?>
            </div>


            <div role="tabpanel" class="tab-pane fade" id="panel3">
                <?= $this->render('widgets/formreserva3')

                ?>
            </div>


        </div>
    </div>
</div>
<hr>
</div>

<br>
<div class="modal fade" id="modalDialog" tabindex="-1" role="dialog" aria-labelledby="modalDialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="contentDialog">
            </div>
        </div>
    </div>
</div>


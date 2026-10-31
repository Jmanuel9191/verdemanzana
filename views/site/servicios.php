<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
  $model = new \app\models\ContactForm();
$configuracion = \app\models\Configuracion::find()->one();
$this->render('widgets/metatags', ['model' => $configuracion]);


$script = <<<CSS

.nav-tabs>li {
    float: left;
    margin-bottom: 0px;
}
CSS;
$this->registerCss($script);

$tab="";


@$tab = $_GET['tab'];
?>


<?= $this->render('banners/fijo',
    ['titulo' => 'Servicios',])

?>



<div class="container ">
    <div class="row">
    <br>
    <h2 class="txt-h2 text-center">Nuestros Servicios</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <div class="">
        <br>
        <ul class="nav nav-tabs text-center responsive" role="tablist">


            <li class="col-md-1 col-sm-12"></li>
            <li role="presentation" class="<?php echo ((empty($tab) OR ($tab==1))?"active":"")?> col-md-2 col-sm-6">
                <a href="#infantiles" aria-controls="infantiles" role="tab" data-toggle="tab">

                    <div class="servico-icono4 center-block hidden-sm hidden-xs"></div>
                    <br>
                    <strong class="servicio-txt1">BODA</strong>
                </a>
            </li>
            <li role="presentation" class="col-md-2 col-sm-6 <?php echo (($tab==2)?"active":"")?>">
                <a href="#baby" aria-controls="baby" role="tab" data-toggle="tab">
                    <div class="servico-icono3 center-block hidden-sm hidden-xs"></div>

                    <br>
                    <strong class="servicio-txt1">15 AÑOS</strong>
                </a>
            </li>
            <li role="presentation" class="col-md-2 col-sm-6 <?php echo (($tab==3)?"active":"")?>">
                <a href="#quince" aria-controls="quince" role="tab" data-toggle="tab">
                    <div class="servico-icono2 center-block hidden-sm hidden-xs"></div>
                    <br>
                    <strong class="servicio-txt1">INFANTILES</strong>
                </a>
            </li>
            <li role="presentation" class="col-md-2 col-sm-6 <?php echo (($tab==4)?"active":"")?>">
                <a href="#matrimonios" aria-controls="matrimonios" role="tab" data-toggle="tab">
                    <div class="servico-icono1 center-block hidden-sm hidden-xs"></div>
                    <br>
                    <strong class="servicio-txt1">EVENTOS VARIOS</strong>
                </a>
            </li>

            <li role="presentation" class="col-md-2 col-sm-6 <?php echo (($tab==5)?"active":"")?>">
                <a href="#conferencias" aria-controls="conferencias" role="tab" data-toggle="tab">
                    <div class="servico-icono5 center-block hidden-sm hidden-xs"></div>
                    <br>
                    <!--  <strong class="servicio-txt1">Matrimonios</strong> -->

                    <strong class="servicio-txt1">CONFERENCIAS, SEMINARIOS y EVENTOS EMPRESARIALES </strong>
                </a>
            </li>
            <li class="col-md-1 col-sm-12"></li>
        </ul>
        <div class="tab-content responsive">
            <div role="tabpanel" class="tab-pane fade <?php echo ((empty($tab) OR ($tab==1))?"in active":"")?>" id="infantiles">
                <br>
                <p class="text-justify">
                   <!-- Verde Manzana Kids te ofrece un cumple inolvidable con un paquete en el que te incluye el salón, organización, equipamiento, decoración  y la atención de nuestro personal el día de tu evento.
                    <br>
                    Además te ofrecemos servicios que puedes adicionar a nuestro paquete como ser animación, catering, decoración adicional, fotografía, filmación y mucho más.
-->
                    Uno de los eventos que nos encanta celebrar son las bodas, nuestros años de experiencia nos ha permitido crear un servicio verdaderamente inolvidable. Una organización y coordinación en línea, servicios exclusivos de Verde Manzana hacen que vivas unas experiencia única. Solicita tu presupuesto y vive una experiencia diferente





                </p>
                <p><strong>
                        Si quieres aún más detalles de nuestro servicio puedes cotizar </strong></p>
                <a class="btn btn-verde" href="<?= Url::to(['cotizaciones']) ?>">COTIZAR</a>
                <hr>
                <div class="row">



                    <?php for ($i=0;$i<6;$i++):?>
                        <div class="col-md-3 col-xs-6">
                            <a href=" <?= Url::to(['assets_b/img/servicios/4/'.($i+1).'.jpg']) ?>" class="fancybox" rel="matrimonios">
                                <img src="<?= Url::to(['assets_b/img/servicios/4/'.($i+1).'.jpg']) ?>" class="img-thumbnail">
                            </a>
                            <br>
                        </div>
                    <?php endfor;?>


                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade <?php echo (($tab==2)?"in active":"")?>" id="baby">
                <br>
                <p class="text-justify">
                    Y  de pronto pasan los años y llega este gran día, una fiesta de 15 años en Verde Manzana tiene las siguientes características: Risas, bailes, selfies y mucha diversión. Solicita tu presupuesto y vive una experiencia diferente  </p>
                <p><strong>
                        Si quieres aún más detalles de nuestro servicio puedes cotizar </strong></p>
                <a class="btn btn-verde" href="<?= Url::to(['cotizaciones']) ?>">COTIZAR</a>
                <div class="row">
                    <?php for ($i=0;$i<5;$i++):?>


                        <div class="col-md-3 col-xs-6">
                            <a href="<?= Url::to(['assets_b/img/servicios/3/'.($i+1).'.jpg']) ?>" class="fancybox" rel="quince">
                                <img src=" <?= Url::to(['assets_b/img/servicios/3/'.($i+1).'.jpg']) ?>" class="img-thumbnail">
                            </a>
                            <br>
                        </div>
                    <?php endfor;?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade <?php echo (($tab==3)?"in active":"")?>" id="quince">
                <br>
                <p class="text-justify">Por siempre serás mi princesita hemos denominado a nuestra fiesta de 15 años en el te incluye el salón, organización, equipamiento, decoración  y la atención de nuestro personal el día de tu evento.
                    El evento que más le gusta celebrar a Neco la manzana son los eventos infantiles. Es por ello que hemos creado paquetes acorde a este día tan especial para los más pequeños de la casa. Solicita tu presupuesto y vive una experiencia diferente.

                </p>
                <p><strong>
                        Si quieres aún más detalles de nuestro servicio puedes cotizar </strong></p>
                <a class="btn btn-verde" href="<?= Url::to(['cotizaciones']) ?>">COTIZAR</a>
                <div class="row">
                    <?php for ($i=0;$i<6;$i++):?>
                        <div class="col-md-3 col-xs-6">
                            <a href=" <?= Url::to(['assets_b/img/servicios/2/'.($i+1).'.jpg']) ?>" class="fancybox" rel="baby">
                                <img src="<?= Url::to(['assets_b/img/servicios/2/'.($i+1).'.jpg']) ?>" class="img-thumbnail">
                            </a>
                            <br>
                        </div>

                    <?php endfor;?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade <?php echo (($tab==4)?"in active":"")?>" id="matrimonios">
                <br>
                <p class="text-justify">
                    Espacios únicos en diversas ubicaciones de la ciudad es lo que ofrece Verde Manzana para celebrar cualquier tipo de acontecimiento. Solicita tu presupuesto y vive una experiencia diferente. </p>
                <p><strong>
                        Si quieres aún más detalles de nuestro servicio puedes cotizar </strong></p>
                <a class="btn btn-verde" href="<?= Url::to(['cotizaciones']) ?>">COTIZAR</a>
                <div class="row">

                    <?php for ($i=0;$i<7;$i++):?>
                        <div class="col-md-3 col-xs-6">
                            <a href="<?= Url::to(['assets_b/img/servicios/1/'.($i+1).'.jpg']) ?> " class="fancybox" rel="infantiles">
                                <img src="<?= Url::to(['assets_b/img/servicios/1/'.($i+1).'.jpg']) ?>" class="img-thumbnail">
                            </a>
                            <br>
                        </div>
                    <?php endfor;?>

                </div>
            </div>





            <div role="tabpanel" class="tab-pane fade <?php echo (($tab==5)?"in active":"")?>" id="conferencias">
                <br>
                <p class="text-justify">
                    Un triunfador es aquel que se levanta y busca las circunstancias que desea y si no las encuentra las fabrica. Verde Manzana tiene espacios únicos he inspiradores para cualquier tipo de evento: Charlas, seminarios, conferencias, lanzamientos, eventos empresariales. Solicita tu presupuesto y vive una experiencia diferente.
                </p>
                <p><strong>
                        Si quieres aún más detalles de nuestro servicio puedes cotizar </strong></p>
                <a class="btn btn-verde" href="<?= Url::to(['cotizaciones']) ?>">COTIZAR</a> <br>
                <!--<div class="row">
                    <?php for ($i=0;$i<6;$i++):?>
                        <div class="col-md-3 col-xs-6">
                            <a href=" <?= Url::to(['assets_b/img/servicios/4/'.($i+1).'.jpg']) ?>" class="fancybox" rel="matrimonios">
                                <img src="<?= Url::to(['assets_b/img/servicios/4/'.($i+1).'.jpg']) ?>" class="img-thumbnail">
                            </a>
                            <br>
                        </div>
                    <?php endfor;?>
                </div> -->
            </div>




        </div>
        <br>
    </div>
</div>

</div>
<br>





<?php
$script = <<<JS
  $(".fancybox").fancybox({
        openEffect	: 'none',
        closeEffect	: 'none'
    });
    (function($) {
        fakewaffle.responsiveTabs(['xs', 'sm']);
    })(jQuery);
 	 
JS;
$this->registerJs($script, \yii\web\View::POS_READY);

?>
<?php
use yii\helpers\Url;

$configuracion = \app\models\Configuracion::find()->one();
$this->render('widgets/metatags', ['model' => $configuracion]);

$script = <<<CSS

CSS;
$this->registerCss($script);


?>




<?= $this->render('banners/fijo',
    ['titulo' => 'Quienes Somos',])

?>



<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Nuestra Historia</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 text-justify">
            <p>En Septiembre del año 2014 nace Verde Manzana, con un solo objetivo que a la fecha no lo hemos cambiado y creemos firmemente que es nuestra razón de vivir.</p>
            <p>Crear  Recuerdos Inolvidables, la vida es muy bella y mejor aun si tenemos grabados en el corazón momentos felices. La sonrisa, el agradecimiento, el amor, la confianza,  son nuestro alimento que nos impulsa para mejorar día a día.</p>
            <p>Con la bendición de Dios y mucho esfuerzo queremos llenar de Verdes Manzanas sus vidas, brindándoles Recuerdos Inolvidables.</p>
        </div>
        <div class="col-md-4">
            <div id="quienes">
                <?php for ($i=1;$i<4;$i++):?>
                    <div>
                        <img src="<?= Url::to(['assets_b/img/quienes/'.$i.'.jpg']) ?> " class="img-thumbnail">
                    </div>
                <?php endfor;?>
            </div>

        </div>
    </div>
    <hr>
</div>


<?= $this->render('widgets/servicios'); ?>

<br>
<?= $this->render('widgets/banner_mid'); ?>


<?php
$script = <<<JS
 $('#quienes').bxSlider({
                    slideWidth: 450,
                    minSlides: 1,
                    moveSlides: 1,
                    maxSlides: 1,
                    slideMargin: 10,
                    pager:false,
                    controls:false,
                    auto:true,
                });

 	 
JS;
$this->registerJs($script, \yii\web\View::POS_READY);

?>


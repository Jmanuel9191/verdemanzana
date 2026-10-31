

<?php

use yii\helpers\Url;

$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
$dia = date("d", strtotime($model['fecha_noticia']));
$mes = date("m", strtotime($model['fecha_noticia']));
$año = date("Y", strtotime($model['fecha_noticia']));
//$horass=date("H:i",strtotime($model['fecha_noticia']));



$script = <<<CSS


 
CSS;


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
//$this->registerCssFile('@web/web/plugin/galeria/thumbrigth/css/examples.css', ['depends'=>\app\assets\AppAsset::class, 'media' => 'screen','']);
$configuracion = \app\models\Configuracion::find()->one();
$configuracion['titulo_pagina']= $model['titulo'];
$configuracion['meta_descripcion']=  strip_tags($model['descripcion']);
$this->render('../widgets/metatags', ['model'=> $configuracion, 'foto' => Url::to($model['foto_contenido'])]);

?>

<div class="container">

    <div class="row">



        <div class="row">
            <div class="col-md-6 text-center">
                <div class="row">
                    <div class="col-xs-9">
                        <ul class="bxslider" style="width: 468px; height: 468px;">


                            <?php foreach($model->galeria as $key => $item):?>
                                <li><img src="<?= Url::to("@web/imagen/noticias/{$item['archivo']}") ?>" class="img-thumbnail" style="max-height: 468px;"/></li>
                            <?php endforeach; ?>


                        </ul>
                    </div>
                    <div class="col-xs-3">
                        <div id="bx-pager">



                            <?php foreach($model->galeria as $key => $item):?>
                                <a data-slide-index="<?php echo $key ?>" href=""><img src="<?= Url::to("@web/imagen/noticias/{$item['archivo']}") ?>" class="img-thumbnail" style="width: 100px; height: 80px;"/></a>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <h2><strong><?= $model->titulo; ?><small class="pull-right"><?php echo date('d/m/Y',strtotime( $model->fecha_noticia)) ?></small></strong></h2>
                <hr>

                <?= $model->descripcion; ?>

                <hr>

                <br>

                <div class="col-xs-12 col-sm-12">
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53e2c6583afe0ece"></script>
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <div class="sociales top-spacing3 bottom-spacing3">
                        <!-- Go to www.addthis.com/dashboard to customize your tools -->
                        <script type="text/javascript"
                                src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54f678625eb57483"
                                async="async"></script>
                        <!-- Go to www.addthis.com/dashboard to customize your tools -->
                        <div class="addthis_native_toolbox"></div>
                    </div>


                </div>
            </div>
        </div>



    </div>

</div>


<br>


<?= $this->render('../widgets/banner_mid'); ?>


<?php
$script = <<<JS


 $('.bxslider').bxSlider({
                        pagerCustom: '#bx-pager',
                        controls: false,
                        auto: true,
                    });


JS;
$this->registerJs($script, \yii\web\View::POS_READY);

?>
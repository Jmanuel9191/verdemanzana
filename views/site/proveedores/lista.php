<?php

use yii\helpers\Url;

$cat = "";
@$nombre = $_GET['cat'];
$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);


$categorias2 = \app\models\Categoria::findOne(['alias' => 'proveedores']);
$cats = $categorias2->categorias;


$script = <<<CSS


.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
    z-index: 3;
    color: #fff;
    cursor: default;
    background-color: #6ba723;
    border-color: #6ba723;
}
.pagination  a {
   
    cursor: pointer!important;
}
div.inline-gallery.popup-gallery.ejs, ol, li {
    list-style: none;
   
    margin: 0;
    padding: 0;
}


.pagination {
    position: relative;
    z-index: 20;
    left: 0;
    top: 50%;
}
.buscadoreste button {
    position: absolute;
    background: transparent;
    border: none!important;
    right: 25px;
    top: 7px;
    color: #68a221;
}

CSS;


$this->registerCssFile('@web/assets_b/css/plugins2.css', ['media' => 'screen', '']);
$this->registerjsFile('@web/assets_b/js/plugins2.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]);
$this->registerCss($script);
?>

<?php $this->registerjsFile('@web/assets_b/js/imagesloaded.pkgd.min.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]); ?>
<?php $this->registerjsFile('@web/assets_b/js/isotope.pkgd.min.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]); ?>
<?php $this->registerjsFile('@web/assets_b/js/jquery.appear.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]); ?>
<?php $this->registerjsFile('@web/assets_b/js/jquery.parallax-1.1.3.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]); ?>


    <div class="container" style="overflow-x: hidden">
        <!-- portfolio section -->
        <div class="blog_post_content">

            <div id="noticias">
                <div class="container">
                <div class="row">


                    <section id="portfolio" class="masonry work-3col gutter-wide wow fadeIn" style="
    padding-bottom: 100px;">

                                <div class="col-xs-12 buscadoreste col-md-3 text-center margin-twenty no-margin-lr no-margin-top" style="
    margin-top: 20px;
">


                                    <form role="search" method="get" action="<?= Url::to(['proveedores/'])?>" class="search-form" style="margin-bottom: 30px;">
                                        <input style="border: 1px solid #62991f;" class="form-control inpundde"  placeholder="Buscar proveedor:" value="" name="s"  title="Buscar:" type="search">
                                        <button type="submit" class="search-submit" title="Search">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </form>

                                    <?= $this->render('sidebar'); ?>


                        </div>


                                <div class="col-xs-12 col-md-9  overflow-hidden">
                                    <div class="tab-content">


<div class="col-xs-12" style="padding: 0">
                                        <div class="respspnsadnk">

                                        <?php foreach ($model as $item): ?>


                                                <div class="col-md-4 col-xs-12 ">


                                                    <figure class="overflow-hidden">
                                                        <div class="gallery-img bg-fast-blue-green-gradiant">

                                                            <div class="contenderopro">

                                                                <div class="provtitle">
                                                                    <h3><?= $item['titulo'] ?></h3>
                                                                </div>

                                                                <div class="imgprove">
                                                                    <img src="<?= Url::to($item['foto']); ?>"
                                                                         class="img-responsive"
                                                                         style="margin: 0 auto; width: 100%"/>
                                                                </div>

                                                                <div class="descripcin">
                                                                    <p class="text-center"
                                                                       style="   font-weight: 600;  font-size: 18px;">
                                                                        Descripcion de la Empresa</p>

                                                                    <?php if ($item['descripcion']) : ?>
                                                                        <div class="lineadf">
                                                                            <?= $item['descripcion'] ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                    <?php endif; ?>



                                                                    <?php if ($item['otrascaracteristicas']) : ?>
                                                                        <div class="lineadf">
                                                                            <p class="text-center"
                                                                               style="   font-weight: 600;  font-size: 18px;">
                                                                                Datos Adicionales</p>
                                                                            <?= $item['otrascaracteristicas'] ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                    <?php endif; ?>


                                                                    <div class="redessociales">

                                                                        <div class="direcciones">

                                                                            <?php if ($item['facebook']) : ?>
                                                                                <a href="<?= Url::to($item['facebook']) ?>"
                                                                                   target="_blank" title="Facebook"><i
                                                                                            class="fa fa-facebook"></i></a>
                                                                            <?php else: ?>
                                                                            <?php endif; ?>

                                                                            <?php if ($item['twitter']) : ?>
                                                                                <a href="<?= Url::to($item['twitter']) ?>"
                                                                                   target="_blank" title="twitter"><i
                                                                                            class="fa fa-twitter"></i></a>

                                                                            <?php else: ?>
                                                                            <?php endif; ?>

                                                                            <?php if ($item['youtube']) : ?>
                                                                                <a href="<?= Url::to($item['youtube']) ?>"
                                                                                   target="_blank" title="youtube"><i
                                                                                            class="fa fa-youtube"></i></a>
                                                                            <?php else: ?>
                                                                            <?php endif; ?>


                                                                            <?php if ($item['linkedin']) : ?>
                                                                                <a href="<?= Url::to($item['linkedin']) ?>"
                                                                                   target="_blank" title="youtube"><i
                                                                                            class="fa fa-linkedin"></i></a>


                                                                            <?php else: ?>
                                                                            <?php endif; ?>


                                                                            <?php if ($item['pinterest']) : ?>
                                                                                <a href="<?= Url::to($item['pinterest']) ?>"
                                                                                   target="_blank" title="youtube"><i
                                                                                            class="fa fa-pinterest-p"></i></a>

                                                                            <?php else: ?>
                                                                            <?php endif; ?>


                                                                        </div>
                                                                    </div>


                                                                        <div class="tam12 posi_fecha" align="center">

                                                                            <div class="services-box">
                                                                                <div class="services-image box-item">

                                                                                    <div class="inline-gallery popup-gallery ejs"
                                                                                        style="    list-style-type: none;
">

                                                                                        <div style="display: block!important; top: 0!important">
                                                                                            <div class="box-item">



                                                                                                    <div class="hover-box produw1">

                                                                                                        <?php if ($item->galeria) : ?>

                                                                                                        <?php foreach ($item->galeria as $value): ?>
                                                                                                            <a class="produw zoom"  href="<?= Url::to("@web/imagen/proveedores/{$value['archivo']}") ?>">

                                                                                                                <img alt="" src="<?= Url::to(['assets_b/images/galeria.png']) ?>">

                                                                                                            </a>

                                                                                                        <?php endforeach; ?>


                                                                                                        <?php else : ?>

                                                                                                            <a class="produw zoom"  href="<?= Url::to($item['foto']); ?>">

                                                                                                                <img alt="" src="<?= Url::to(['assets_b/images/galeria.png']) ?>">

                                                                                                            </a>


                                                                                                        <?php endif; ?>



                                                                                                    </div>




                                                                                            </div>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                            </div>
                                                                        </div>








                                                                </div>

                                                            </div>


                                                        </div>


                                                    </figure>


                                                </div>
                                                <!-- end portfolio item -->


                                            <?php endforeach; ?>

                                        </div>
</div>




                                        <div class="col-xs-12">
                                            <hr>
                                            <div class="text-center">
                                                <?= \yii\widgets\LinkPager::widget([
                                                    'pagination' => $data->pagination,
                                                ]); ?>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                    </section>


                </div>
                </div>

            </div>


        </div>
    </div>





<?php
$script = <<<JS



 
    jQuery('.grid').imagesLoaded(function () {
        jQuery('.grid').isotope({
            itemSelector: 'li',
            layoutMode: 'masonry'
        });
    });


    

    jQuery('.portfolio-filter > li > a').on('click', function () {
        jQuery('.portfolio-filter > li > a').parent().removeClass('active');
        jQuery(this).parent().addClass('active');
        var selector = $(this).attr('data-filter');
        jQuery('.grid').isotope({filter: selector});
        return false;
    });

   jQuery(window).resize(function () {
        setTimeout(function () {
            jQuery('.grid').isotope('layout');
        }, 500);
    });
    
       
       
JS;
$this->registerJs($script, \yii\web\View::POS_READY);
?>

<?php
$script = <<<JS

 $('.zoom').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			}
		});


JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>

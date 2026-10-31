<?php

use app\assets_b\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

$data = new \app\models\Categoria();
/*
$this->registerCssFile('@web/assets_b/css/main.css', ['depends' => AppAsset::class, 'media' => 'screen', '']);
$this->registerCssFile('@web/assets_b/images/rs-plugin/css/style.css', ['depends' => AppAsset::class, 'media' => 'screen', '']);
$this->registerCssFile('@web/assets_b/css/settings.min.css', ['depends' => AppAsset::class, 'media' => 'screen', '']);
//$this->registerjsFile('@web/assets_b/web/images/rs-plugin/js/jquery.themepunch.plugins.min.js', ['depends' => AppAsset::class,'position' => \yii\web\View::POS_END]);
//$this->registerjsFile('@web/assets_b/web/images/rs-plugin/js/jquery.themepunch.revolution.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);

/*
$this->registerjsFile('@web/assets_b/js/banner/jquery.themepunch.tools.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
$this->registerjsFile('@web/assets_b/js/banner/jquery.themepunch.revolution.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
$this->registerjsFile('@web/assets_b/js/banner/jquery.revolution.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
/*
$this->registerjsFile('@web/assets_b/js/banner/extensions/revolution.extension.navigation.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
$this->registerjsFile('@web/assets_b/js/banner/extensions/revolution.extension.layeranimation.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
$this->registerjsFile('@web/assets_b/js/banner/extensions/revolution.extension.slideanims.min.js', ['depends' => AppAsset::class, 'position' => \yii\web\View::POS_END]);
*/

$script = <<<CSS

CSS;
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);


?>



<div class="banner">

    <!-- START REVOLUTION SLIDER 3.1 rev5 fullwidth mode -->
    <div class="fullwidthbanner-container roundedcorners text-center" style="z-index: 0">
        <div class="fullwidthbanner" >
            <ul>	<!-- SLIDE  -->
                <li data-transition="fade" data-slotamount="10" data-masterspeed="1500" >
                    <!-- MAIN IMAGE -->
                    <img src="<?= Url::to(['imagen/banners/b1.1.jpg']) ?>"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <!-- LAYERS -->

                    <!-- LAYER NR. 9 -->


                </li>
                <!-- SLIDE  -->
                <li data-transition="slidedown" data-slotamount="7" data-masterspeed="1000" >
                    <!-- MAIN IMAGE -->
                    <img src="<?= Url::to(['imagen/banners/b2.2.jpg']) ?>" style='background-color:#b8e8f6' alt=""  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <!-- LAYERS -->
                    <!-- LAYER NR. 9 -->


                </li>

                <!-- SLIDE  -->
                <li data-transition="zoomout" data-slotamount="7" data-masterspeed="1000" >
                    <!-- MAIN IMAGE -->
                    <img src="<?= Url::to(['imagen/banners/b3.3.jpg']) ?>"  alt="darkblurbg"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <!-- LAYERS -->
                    <!-- LAYER NR. 9 -->

                </li>

                <!-- SLIDE  -->
                <li data-transition="slidedown" data-slotamount="7" data-masterspeed="1000" >
                    <!-- MAIN IMAGE -->
                    <img src=" <?= Url::to(['imagen/banners/b4.4.jpg']) ?>" style='background-color:#b8e8f6' alt=""  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <!-- LAYERS -->
                    <!-- LAYER NR. 9 -->


                </li>

                <!-- SLIDE  -->
                <li data-transition="zoomout" data-slotamount="7" data-masterspeed="1000" >
                    <!-- MAIN IMAGE -->
                    <img src=" <?= Url::to(['imagen/banners/b5.5.jpg']) ?>"  alt="darkblurbg"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <!-- LAYERS -->
                    <!-- LAYER NR. 9 -->



                </li>
            </ul>
            <div class="tp-bannertimer"></div>
        </div>
    </div>
    <script type="text/javascript">

    </script>
</div>

<div class="container">
    <div class="row hidden-xs hidden-sm">
        <div class="col-md-12">
            <div class="atencion-cliente">
                <div class="row">
                    <br>
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="col-xs-2">
                            <img src="<?= Url::to(['assets_b/img/antecion-icono1.png']) ?>">
                        </div>
                        <div class="col-xs-5">
                            <span class="atencion-txt1">Atención al Cliente</span>
                            <br>
                            <span class="atencion-numero">(+591 77094165)</span>
                            <br>
                            <span class="atencion-txt2"> En Verde Manzana estamos para atenderte y asesorarte <br> de una manera diferente pero efectiva.</span>
                        </div>
                        <div class="col-xs-2" style="margin-top: 35px">
                            <img src="<?= Url::to(['assets_b/img/antecion-icono2.png']) ?>">
                        </div>
                        <div class="col-xs-3" style="margin-top: 35px">
                            <a href="<?= Url::to(['contacto']) ?>" class="btn btn-blanco">Escribanos aquí</a>
                        </div>
                    </div>
                    <br>
                </div>

            </div>
        </div>
    </div>
</div>





<?php
$script = <<<JS

     
        var revapi;

        jQuery(document).ready(function() {

            revapi = jQuery('.fullwidthbanner').revolution(
                {
                    delay:9000,
                    startwidth:1170,
                    startheight:650,
                    hideThumbs:10,

                    thumbWidth:100,
                    thumbHeight:50,
                    thumbAmount:5,

                    navigationType:"both",
                    navigationArrows:"solo",
                    navigationStyle:"round",

                    touchenabled:"on",
                    onHoverStop:"on",

                    navigationHAlign:"center",
                    navigationVAlign:"bottom",
                    navigationHOffset:0,
                    navigationVOffset:0,

                    soloArrowLeftHalign:"left",
                    soloArrowLeftValign:"center",
                    soloArrowLeftHOffset:20,
                    soloArrowLeftVOffset:0,

                    soloArrowRightHalign:"right",
                    soloArrowRightValign:"center",
                    soloArrowRightHOffset:20,
                    soloArrowRightVOffset:0,

                    shadow:0,
                    fullWidth:"on",
                    fullScreen:"off",

                    stopLoop:"off",
                    stopAfterLoops:-1,
                    stopAtSlide:-1,


                    shuffle:"off",

                    autoHeight:"off",
                    forceFullWidth:"off",

                    hideThumbsOnMobile:"off",
                    hideBulletsOnMobile:"on",
                    hideArrowsOnMobile:"on",
                    hideThumbsUnderResolution:0,

                    hideSliderAtLimit:0,
                    hideCaptionAtLimit:768,
                    hideAllCaptionAtLilmit:0,
                    startWithSlide:0,
                    videoJsPath:"plugins/revslider/rs-plugin/videojs/",
                    fullScreenOffsetContainer: ""
                });

        });	//ready

JS;
$this->registerJs($script, \yii\web\View::POS_END);?>


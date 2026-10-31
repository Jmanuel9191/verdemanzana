
<?php

use yii\helpers\Url;



//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1', 'idcategoria' => ['1', '2']]);
//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1']);




$videos = \app\models\Videos::find()->where(['estado' => '1'])->orderBy('idvideo')->all();


//$horass=date("H:i",strtotime($model['fecha_noticia']));

$script = <<<CSS

.view.ve3 {
   
    width: 100%;
}
CSS;
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);






?>
<?php if ($videos){ ?>




<h2 class="txt-h2">Nuestros <small>Videos</small><span class="pull-right"><span id="prevn" class="noti-prev"></span><span id="nextn" class="noti-next"></span></span></h2>
<div class="separador-titulo"></div>
<br>
<div id="properties-carousel3" class="properties-carousel">
    <?php foreach ($videos as $noticia):  ?>


            <div class="" style="   ">


                    <div class="view ve3 view-item" style="margin-bottom: 10px;">
                        <img src="<?= Url::to($noticia['foto_portada']) ?>" class=""/>

                        <div class="mask">



                            <div class="services-box">
                                <div class="services-image">
                                    <a href="<?=$noticia['url']?>" class="popup-youtube" style="WIDTH: 100%; height: 100%;">
                                        <span class="overlay"></span>


                                         <div class="center-block centrovideo">
                                             <i class="fa fa-play" aria-hidden="true"></i>
                                             <p>Reproducir</p>
                                         </div>
                                    </a>
                                </div>

                            </div>

                        </div>


                    </div>

                <p class="videotitu"> <?= \yii\helpers\StringHelper::truncate($noticia['titulo'], 50) ?></p>

                </div>



    <?php endforeach;?>
</div>


<?php } else { ?>

<?php }  ?>
<br><br>
    <!-- Blog section -->




<?php
$script = <<<JS
$('#properties-carousel3').owlCarousel({
items:3,
loop:true,
navigation:true,
dots:false,
margin:20,
autoplay:true,
autoplayTimeout:5000,
responsive:{
170:{
items:1
},
500:{
items:1
},
678:{
items:2
},
768:{
items:2
},
991:{
items:2
},
992:{
items:3
}
}
});

 	 
JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>



<?php $this->registerjsFile('@web/assets_b/js/plugins.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]); ?>



<?php
$script = <<<JS

   $(".popup-youtube, .popup-vimeo , .show-map").magnificPopup({
        disableOn: 700,
        type: "iframe",
        removalDelay: 600,
        mainClass: "my-mfp-slide-bottom"
    });
    
 	 
JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>

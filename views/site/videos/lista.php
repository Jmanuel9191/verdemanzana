<?php
use yii\helpers\Url;

$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);

$script = <<<CSS
.view {
    width: 100%}
CSS;
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);


?>

<div class="container">
                    <!-- portfolio section -->
                    <div class="row">

                        <div id="noticias">
                            <div class="col-xs-12">




 <?php


if (isset($model)) :
        foreach ($model as $item):

            ?>


            <div class="col-md-4 col-sm-6 col-xs-12" style="       margin-bottom: 30px;">




                <div class="view ve3 view-item" style="margin-bottom: 10px;">
                    <img src="<?= Url::to($item['foto_portada']) ?>" class="" style="    width: 100%;"/>

                    <div class="mask">



                        <div class="services-box">
                            <div class="services-image">
                                <a href="<?=$item['url']?>" class="popup-youtube" style="WIDTH: 100%; height: 100%;">
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

                <p class="videotitu"> <?= \yii\helpers\StringHelper::truncate($item['titulo'], 100) ?></p>

            </div>





        <?php endforeach; ?>

        <div class="col-xs-12">
            <hr>
            <div class="text-center">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $data->pagination,
                ]); ?>
            </div>
        </div>
<?php else: ?>
    <p>Sin registros</p>

<?php endif; ?>




                            </div>

                        </div>


                    </div>
</div>



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
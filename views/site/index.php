<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
$configuracion = \app\models\Configuracion::find()->one();
$this->render('widgets/metatags', ['model' => $configuracion]);

$script = <<<CSS
CSS;
//$this->registerCss($script, ['depends' => \app\assets\AppAsset::class]);

?>

<?= $this->render('banners/slider') ?>


<?= $this->render('widgets/brindamos'); ?>

<!--
    <div class="servicios container-fluid">
        <?//= $this->render('widgets/servicios'); ?>
    </div>






<div class="container">
    <div class="row">

<?//= $this->render('promociones/destacados'); ?>



    </div>
</div> -->

<?= $this->render('widgets/banner_mid'); ?>

<!--
<div class="container">
    <div class="row">
<div class="col-xs-12">
        <?//= $this->render('videos/destacados'); ?>

        <div class="text-center" align="center">

            <a href="<?= Url::to('videos') ?>" class="btn-verde"> Ver todos</a>
        </div>

</div>
        <?//= $this->render('widgets/calendarios'); ?>

-->




<!--

<div class="col-xs-12">
    <?//= $this->render('noticias/destacados'); ?>
</div>




    </div>
</div> -->


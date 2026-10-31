<?php

use yii\helpers\Html;


use kartik\widgets\SideNav;
use yii\helpers\Url;

$categoria = \app\models\Categoria::findAll('estado = 1' );


$noticias = \app\models\Noticias::findAll([ 'estado' => '1', 'idcategoria' => ['1', '2']]);


$script = <<<CSS

CSS;
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);


?>



<div class="single_sidebar margin-top-40">
    <div class="sidebar_head text-center textwhite">
        <h6>NOTICIAS DESTACADAS</h6>
        <div class="main-nav-border"></div>
    </div>


    <?php  foreach ($noticias as $item):

    $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    $dia = date("d", strtotime($item['fecha_noticia']));
    $mes = date("m", strtotime($item['fecha_noticia']));
    $año = date("Y", strtotime($item['fecha_noticia']));


    ?>
    <div class="sidebare_recent_post margin-top-20">
        <div class="row">
            <div class="col-xs-3 col-sm-3 ">
                <div class="sidebare_recent_img">
                    <img src="<?= Url::to($item['foto_portada']) ?>" alt="">
                </div>
            </div>
            <div class="col-xs-9 col-sm-9">
                <div class="sidebare_recent_content">
                    <p style="    FONT-SIZE: 14px;"><?= \yii\helpers\StringHelper::truncate($item['titulo'], 60) ?></p>
                    <a href=""><?= $meses[$mes * 1] ?>  <?= $dia ?> - <?= $año ?></a> <a href="" class="admin"><?=$item['fuente']?></a>
                </div>
            </div>
        </div>
    </div>

    <?php endforeach; ?>
</div>

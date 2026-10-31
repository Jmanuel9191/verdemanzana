
<?php

use yii\helpers\Url;



//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1', 'idcategoria' => ['1', '2']]);
//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1']);



$noticias33 = \app\models\Promociones::find()->where(['estado' => '1', 'destacado' => 1]) ->limit(9)->orderBy('idpromocion')->all();


//$horass=date("H:i",strtotime($model['fecha_noticia']));

$script = <<<CSS
.view {
   
    width: 90%;
}
CSS;
$this->registerCssFile('@web/assets_b/css/owlcarousel/owl.carousel.css', ['media' => 'screen','']);
$this->registerCssFile('@web/assets_b/css/owlcarousel/owl.theme.css', ['media' => 'screen','']);
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);


$meses=['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
?>



<div class="container-fluid">
    <div class=" ">
        <div class="row">


        <h2 class="txt-h2">Paquetes <small>en promoción</small><span class="pull-right"><span id="prevp" class="noti-prev"></span><span id="nextp" class="noti-next"></span></span></h2>
        <div class="separador-titulo"></div>
        </div>
    </div>
    <br>
    <div id="promociones">




<div id="properties-carousel" class="properties-carousel">
    <?php foreach ($noticias33 as $item): ?>
        <?php
        $dia = date("d", strtotime($item['fecha_promocion']));
        $mes = date("m", strtotime($item['fecha_promocion']));
        $año = date("Y", strtotime($item['fecha_promocion']));
        $href = Url::to(['promocion/', 'id' => $item['idpromocion'], ]);
        ?>



        <div class="col-xs-12">
            <div class="view view-item">
                <img src="<?= Url::to($item['foto']) ?>"  />

                <div class="mask">
                    <h2><?= \yii\helpers\StringHelper::truncate($item['titulo'], 60) ?></h2>

                    <p><?= \yii\helpers\StringHelper::truncate($item['resumen'], 100) ?></p>
                    <a href="<?= $href ?>" class="info">Ver Promoción</a>
                </div>

            </div>
            <div class="paquetes-item-fecha1 text-center" style="width: 111px; height: 166px;">
                <br>
                <div style="height: 160px;width: 100px;margin: 20px 15px">
                    <span class="dia"><?php echo $dia?></span>
                    <br>
                    <span class="mes"><?php echo $meses[$mes-1]?></span>
                    <br>
                    <span class="anio"><?php echo $año?></span>
                </div>
            </div>
        </div>



    <?php endforeach; ?>
</div>


    </div>

</div>
<br>
<br>




<?php
$script = <<<JS
$('#properties-carousel').owlCarousel({
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




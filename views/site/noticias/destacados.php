
<?php

use yii\helpers\Url;



//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1', 'idcategoria' => ['1', '2']]);
//$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1']);




$noticias = \app\models\Noticias::find()->where(['estado' => '1', 'destacado' => 1])->orderBy('idnoticia')->all();


//$horass=date("H:i",strtotime($model['fecha_noticia']));

$script = <<<CSS

.view.ve3 {
   
    width: 100%;
}
CSS;

$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);



?>


<h2 class="txt-h2">Últimas <small>Noticias</small><span class="pull-right"><span id="prevn" class="noti-prev"></span><span id="nextn" class="noti-next"></span></span></h2>
<div class="separador-titulo"></div>
<br>
<div id="properties-carousel2" class="properties-carousel">
    <?php foreach ($noticias as $noticia):
        $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        $dia = date("d", strtotime($noticia['fecha_noticia']));
        $mes = date("m", strtotime($noticia['fecha_noticia']));
        $año = date("Y", strtotime($noticia['fecha_noticia'])); ?>


            <div class="row">
                <div class="col-sm-4 col-xs-12">

                    <div class="view ve3 view-item">
                        <img src="<?= Url::to($noticia['foto_portada']) ?>" class=""/>

                        <div class="mask">

                            <a href="<?= Url::to(['noticia/' , 'id' => $noticia['idnoticia']]) ?>" class="info">
                                <i class="glyphicon glyphicon-plus"></i>
                            </a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <h4 class="txt-h4" style="margin-bottom: 0"><?= \yii\helpers\StringHelper::truncate($noticia['titulo'], 25) ?></h4>
                    <span class="fecha-not" ><i class="glyphicon glyphicon-calendar" style="color: #feac00"></i><?php echo date("d/m/Y",strtotime($noticia['fecha_noticia']));?></span>
                    <hr style="margin-bottom: 10px;margin-top: 10px;">
                    <!--<p><?//= \yii\helpers\StringHelper::truncate($item['resumen'], 100) ?></p>-->
                    <a class="btn btn-verde text-center" href="<?= Url::to(['noticia/' , 'id' => $noticia['idnoticia']]) ?>">Ver Noticia</a>
                </div>
            </div>

    <?php endforeach;?>
</div>

<br><br>
    <!-- Blog section -->



<?php
$script = <<<JS
$('#properties-carousel2').owlCarousel({
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
}
}
});

 	 
JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>


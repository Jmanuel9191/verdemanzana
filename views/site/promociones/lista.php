<?php

use yii\helpers\Url;


$script = <<<CSS


.home1_mix_content .port_overfolio {
    background: rgba(0, 63, 117, 0.65);
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    -webkit-transition: .5s;
    transition: .5s;
    opacity: 0;
    color: #fff;
       
}

.home1_mix_content .single_mixi_portfolio {
    
    border: 3px solid #003f75;
    border-radius: 5px;
   
}



.home1_mix_content .single_mixi_portfolio .caption {
    padding-bottom: 0px;
    display: block;
}

.view h2 {
    color: #ffffff;
    font: 700 21px OpenSans;
    margin: 40px 0 0 0;
}




@media (min-width: 1200px) {
.view {
    float: left;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    background: #fff;
    width: 80%;
    margin-bottom: 40px;
}
.view img {
    display: block;
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.view h2 {
    color: #ffffff;
    font: 700 21px OpenSans;
    margin: 50px 0 0 0;
}

}



@media (min-width: 992px) and (max-width: 1199px) {
.view {
    float: left;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    background: #fff;
    width: 80%;
    margin-bottom: 40px;
}
.view img {
    display: block;
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.view h2 {
    color: #ffffff;
    font: 700 21px OpenSans;
    margin: 50px 0 0 0;
}

}

@media (min-width: 768px) and (max-width: 991px) {
.view {
    float: left;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    background: #fff; 
    margin-bottom: 40px;
}
.view img {
    display: block;
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.view h2 {
    color: #ffffff;
    font: 700 21px OpenSans;
    margin: 50px 0 0 0;
}

}


@media (max-width: 767px)  and (min-width: 481px)  {
.view {
    float: left;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    background: #fff; 
     width: 80%;
    margin-bottom: 40px;
}
.view img {
    display: block;
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.view h2 {
    color: #ffffff;
    font: 700 21px OpenSans;
    margin: 50px 0 0 0;
}

}

@media (max-width: 480px)   {
.view {
    float: left;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    background: #fff; 
     width: 80%;
    margin-bottom: 40px;
}
.view img {
    display: block;
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.view h2 {
    color: #ffffff;
    font: 700 15px OpenSans;
    margin: 50px 0 0 0;
}

.view p {

font-size: 10px;
}
}

CSS;
$this->registerCss($script);

?>





    <div id="promociones">
    <div class="container">
        <div class="row">




<?php
$meses=['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
if (!empty($model)) :

    foreach ($model as $item):
        $dia = date("d", strtotime($item['fecha_promocion']));
        $mes = date("m", strtotime($item['fecha_promocion']));
        $año = date("Y", strtotime($item['fecha_promocion']));
        $href = Url::to(['promocion/', 'id' => $item['idpromocion'], 'cat' => $categoria['idcategoria']]); ?>



        <div class="col-sm-6 col-xs-12">


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




        <?php
    endforeach; ?>


    </div>



    <div class="col-xs-12">
        <hr>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $data->pagination,
            ]); ?>
        </div>
    </div>


<?php else: ?>


    <div class="col-xs-12">
        <p>Sin registros</p>
    </div>
<?php endif; ?>

    </div>
    </div>

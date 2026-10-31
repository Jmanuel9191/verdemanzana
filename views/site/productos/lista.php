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
    margin: 0px 0 0 0;
}




   



CSS;
$this->registerCss($script);

?>



    <div id="paquetes">
    <div class="container">
    <div class="row" >


<?php

if (!empty($model)) :

    foreach ($model as $item):
        $href = Url::to(['producto/', 'id' => $item['idproducto'], 'cat' => $categoria['idcategoria']]); ?>


                    <div class="col-md-4" align="center">
                        <div class="row">
                            <div class="col-xs-12" >

                                <div class="view view-item">
                                    <img src="<?= Url::to($item['foto']); ?>" class="img-thumbnail" width="430px" height="430px"/>

                                    <div class="mask">
                                        <br>
                                        <br>
                                        <h2><?= \yii\helpers\StringHelper::truncate($item['titulo'], 90) ?></h2>
                                        <br>
                                        <p><?= \yii\helpers\StringHelper::truncate($item['resumen'], 100) ?></p>
                                        <br>
                                        <a href="<?= $href ?>" class="info">Ver Paquete</a>
                                    </div>

                                </div>


                            </div>
                        </div>
                        <br>
                        <br>
                    </div>



        <?php
    endforeach; ?>
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


<?php else: ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <p>Sin registros</p>
        </div>
    </div>
</div>


<?php endif; ?>

    </div>

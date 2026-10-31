<?php

use yii\helpers\Html;


use kartik\widgets\SideNav;
use yii\helpers\Url;

//$categoria = \app\models\Categoria::findAll('estado = 1' );





$script = <<<CSS
/*
.panel {
    margin-bottom: 20px;
    background-color: #fff;
    border: 0px solid transparent;
    border-radius: 0px;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
}
.nav-pills > li > a {
    border-radius: 0px;
}

.kv-sidenav li a {
    border-radius: 0;
    border-bottom: 0px solid #ddd;
}


ul.nav-stacked > li > a{
    background: black;
    color: white;
     border-top: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.05) inset;
        text-align: left;
            border-bottom: 1px solid;

}


ul.nav-stacked > li.active > a{
   
  background: TRANSPARENT;
    color: red;
}

ul.nav-stacked > li:hover > a{
      background: rgb(228, 52, 46);
    color: white;
}

ul.nav-stacked > li:focus > a{
   background: rgb(228, 52, 46);
    color: white;
}


ul.nav-stacked > li.active:hover > a{
    background: rgb(228, 52, 46);
    color: white;
}

ul.nav-stacked > li.active:focus > a{
  background: rgb(228, 52, 46);
    color: white;
}




.kv-sidenav > li > a.kv-toggle  {
      background: black;
    color: white;
    border-top: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.05) inset;
        text-align: left;
            font-weight: 600;
    
}

.kv-sidenav .indicator {
    color: white;
}





.nav-stacked > li + li {
    margin-top: 0px;
    margin-left: 0;
}

.kv-sidenav > li.active > a.kv-toggle  {
      background: rgb(242, 145, 44);
    color: white;
}

.kv-sidenav > li:hover > a.kv-toggle  {
           background: rgb(242, 145, 44);
    color: white;
}
.kv-sidenav > li:focus > a.kv-toggle  {
           background: rgb(242, 145, 44);
    color: white;
}

.kv-sidenav > li:active > a.kv-toggle  {
        background: rgb(242, 145, 44);
    color: white;
}

.kv-sidenav > li.active:hover > a.kv-toggle  {
          background: rgb(242, 145, 44);
    color: white;
}
  .kv-sidenav > li.active:focus > a.kv-toggle  {
      background: rgb(242, 145, 44);
    color: white;
}
    
    .nav > li > a {
    position: relative;
    display: block;
    padding: 14px 15px;
}
    */
.nav-pills > li > a {
       padding:0px 10px 0px;
}


div.col-md-3 > div > .panel.panel-default {

    border-color: transparent;
}



.kv-sidenav li a {
    border-radius: 0;
    border-bottom: 0px solid #000;
    color: #222222;
    font-family: 'Montserrat', sans-serif;
    font-style: normal;
    font-weight: 100;
}




.kv-sidenav .indicator {
    color: #337ab7;
    font-weight: 300;
}
.panel-default {
    border-color: rgba(221, 221, 221, 0);
}
.accordion .panel {
    box-shadow: none;
     border-bottom: 0px solid #000000; 
    border-radius: 0px;
}


.kv-sidenav > li.active > a.kv-toggle  {
   
    background-color: #ffffff;
    color: #ca7361;
}

.kv-sidenav > li:hover > a.kv-toggle  {
       background-color: #ffffff;
    color: #ca7361;
}
.kv-sidenav > li:focus > a.kv-toggle  {
      background-color: #ffffff;
    color: #ca7361;;
}

.kv-sidenav > li:active > a.kv-toggle  {
      background-color: #ffffff;
    color: #ca7361;;
}

.kv-sidenav > li.active:hover > a.kv-toggle  {
    background-color: #ffffff;
    color: #ca7361;
}
  .kv-sidenav > li.active:focus > a.kv-toggle  {
 background-color: #ffffff;
    color: #ca7361;;
}


.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
    color: #fff;
    background-color: rgba(3, 146, 206, 0.23);
    color: rgb(48, 101, 181);
}




ul.kv-sidenav > li.active > a{

     background-color: #ffffff;
    color: #ca7361;;
}



ul.kv-sidenav > li.active:hover > a{

      background-color: #ffffff;
    color: #ca7361;;
}



ul.kv-sidenav > li.active:focus > a{

    background-color: #ffffff;
    color: #ca7361;;
}


ul.kv-sidenav > li.active:active > a{

     background-color: #ad1e1d;
    color: white;
}



.nav>li>a:hover, .nav>li>a:focus {
    /*text-decoration: none;
     background-color: #ffffff;
    color: #ca7361;;*/
  
}


.nav-stacked>li+li {
    margin-top: 0px;
    margin-left: 0;
}

ul.nav.nav-pills{

    display: -webkit-inline-box;
}

.panel {
   
    background-color: transparent;
    padding: 5px;
        margin-bottom: 0px;
   }

CSS;
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);


?>





<div class="submenipro" style=" display: -webkit-box;  margin-right: 0px;     float: right;">

    <div class="">

        <?php
        //$ip = new \app\models\Categoria();
        //$cats = $ip->getMenu3('noticias');
        $cat = \app\models\Categoria::findOne(['alias' => 'videos']);
        $cats = $cat->categorias;

        ?>



        <?php   $items = [];
        foreach ($cats as $item) {



            $sub = $item->categorias;
            if (count($sub) > 0) {
                $values = [];
                foreach ($sub as $value) {
                    $sub2 = $value->categorias;


                    if (count($sub2) > 0) {
                        $tmp = [];
                        foreach ($sub2 as $value2) {
                            array_push($tmp, ['label' => $value2['nombre'], 'icon' => '', 'url' => Url::to([$categoria['alias'] . '?cat='  . $value2['idcategoria']]), 'active' => ($value2['idcategoria'] == Yii::$app->getRequest()->getQueryParam('cat'))]);
                        }
                        array_push($values, ['label' => $value['nombre'], 'icon' => '', 'items' => $tmp]);
                    } else {
                        array_push($values, ['label' => $value['nombre'], 'icon' => '', 'url' => Url::to([$categoria['alias']. '?cat=' .  $value['idcategoria']]), 'active' => ($value['idcategoria'] == Yii::$app->getRequest()->getQueryParam('cat'))]);
                    }
                }
                array_push($items, ['label' => $item['nombre'], 'icon' => '', 'items' => $values]);
            } else {
                array_push($items, ['label' => $item['nombre'], 'icon' => '',  'url' => Url::to(['videos'.  '?cat=' .   $item['idcategoria']]), 'active' => ($item['idcategoria'] == Yii::$app->getRequest()->getQueryParam('cat')),]);

            }



        }?>



        <?php
        echo "<div class=''>" . SideNav::widget([
                'encodeLabels' => false,
                'heading' => false,
                'items' => $items,
                'containerOptions' => ['class' => ''],

            ]) . "</div>";?>



    </div>

</div>










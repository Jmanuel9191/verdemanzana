<?php

$script = <<<CSS

.titles > h1 {
    border-bottom: 4px solid #14284b;
    margin: -10px 0;
    padding-bottom: 0px;
    font-size: 30px;
    line-height: 40px;
    display: inline;
    font-weight: 700;
    font-style: normal;
    color: black;
}


CSS;
$this->registerCss($script);
$configuracion = \app\models\Configuracion::find()->one();


$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre])



?>



<!--home Section -->

<?= $this->render('banners/fijo',
    ['titulo' => 'Paquetes',])

?>


<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Nuestros Paquetes</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>
    <br>

    <?= $this->render('productos/sidebar', ['categoria' => $categoria]) ?>
    <br><br><br>
    <hr>

</div>



    <?php
    if (isset($all)) {
        echo $this->render('productos/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
        echo $this->render('widgets/metatags', ['model' => $configuracion]);
    } else {
        if (!empty($model)) {
            echo $this->render('productos/item', ['model' => $model, 'categoria' => $categoria]);
        }
    }


    ?>





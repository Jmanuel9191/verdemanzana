<?php
use yii\helpers\Url;

$script = <<<CSS


.clientes{
   
    background-size: cover;
    position: relative;
     
   
}

.noticias{
   
    background-size: cover;
    position: relative;
       
   
}


.main_about_benner .single_about_benner {
    width: 53%;
    margin: -10px auto;
    margin-top: 2%;
}


CSS;



$this->registerCss($script);
$configuracion = \app\models\Configuracion::find()->one();


$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);

?>



<?= $this->render('banners/fijo',
    ['titulo' => 'Noticias',])

?>


<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Noticias</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>
    <br>

    <?= $this->render('noticias/sidebar', ['categoria' => $categoria]) ?>
    <br><br>
    <hr>

</div>
    <?php
    if (isset($all)) {
        echo $this->render('noticias/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
        echo $this->render('widgets/metatags', ['model' => $configuracion]);
    } else {
      if (!empty($model)) {
            echo $this->render('noticias/item', ['model' => $model, 'categoria' => $categoria]);
        }
    }
    ?>


<br>


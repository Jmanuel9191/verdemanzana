<?php
use yii\helpers\Url;

$script = <<<CSS



CSS;



$this->registerCss($script);
$configuracion = \app\models\Configuracion::find()->one();


$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);

?>



<?= $this->render('banners/fijo',
    ['titulo' => 'Videos',])

?>


<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Videos</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>
    <br>

    <?= $this->render('videos/sidebar', ['categoria' => $categoria]) ?>
    <br><br>
    <hr>

</div>
    <?php
    if (isset($all)) {
        echo $this->render('videos/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
        echo $this->render('widgets/metatags', ['model' => $configuracion]);
    } else {
     if (!empty($model)) {
            echo $this->render('videos/item', ['model' => $model, 'categoria' => $categoria]);
        }
    }
    ?>


<br>


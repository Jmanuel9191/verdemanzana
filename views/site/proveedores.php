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
    ['titulo' => 'Proveedores',])

?>


<div class="container ">
<BR>
<BR>
    <h2 class="txt-h2 text-center">Nuestros Proveedores</h2> <br>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>

    <br>
    <br>
    <?php
    if (isset($all)) {
        echo $this->render('proveedores/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
        echo $this->render('widgets/metatags', ['model' => $configuracion]);
    } else {
       if (!empty($model)) {
            echo $this->render('proveedores/item', ['model' => $model, 'categoria' => $categoria]);
        }
    }
    ?>


<br>

</div>
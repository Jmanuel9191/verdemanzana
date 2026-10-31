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
    ['titulo' => 'Ficha del evento',])

?>

<br>
<br>

    <?php
    if (isset($all)) {
        echo $this->render('reservas/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
        echo $this->render('widgets/metatags', ['model' => $configuracion]);
    } else {
            if (!empty($model)) {
            echo $this->render('reservas/item', ['model' => $model]);
        }
    }
    ?>


<br>


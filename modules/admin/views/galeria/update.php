<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Galeria */

$this->title = 'Update Galeria: ' . $model->idgaleria;
$this->params['breadcrumbs'][] = ['label' => 'Galerias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idgaleria, 'url' => ['view', 'id' => $model->idgaleria]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="galeria-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

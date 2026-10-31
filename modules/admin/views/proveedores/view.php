<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */

$this->title = $model->idproovedor;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticias-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idproovedor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idproovedor], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idnoticia',
            'titulo',
            'idcategoria',
            'direccion:ntext',
            'descripcion:ntext',
            'email',
            'fecha_registro',
            'telefono',
            'movil',
            'foto',
            'facebook',
            'twitter',
            'estado',
            'disponible',
            'cantidad',
            'tags:ntext',
            'link1',
            'posicion',
        ],
    ]) ?>

</div>

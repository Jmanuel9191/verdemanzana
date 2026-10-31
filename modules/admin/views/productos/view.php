<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Productos */

$this->title = $model->idproducto;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idproducto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idproducto], [
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
            'idproducto',
            'titulo',
            'idcategoria',
            'resumen:ntext',
            'descripcion:ntext',
            'precio',
            'video',
            'codigo',
            'link1',
            'link2',
            'fecha_registro',
            'foto',
            'fotop',
            'estado',
            'nuevo',
            'promocion',
            'tags:ntext',
            'posicion',
        ],
    ]) ?>

</div>

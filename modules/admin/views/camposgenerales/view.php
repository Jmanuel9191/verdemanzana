<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FormTextosEstaticos */

$this->title = $model->etiqueta_label;
$this->params['breadcrumbs'][] = ['label' => 'Textos Estáticos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-textos-estaticos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->idformtextos], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->idformtextos], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de eliminar este texto?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idformtextos',
            'grupo',
            'etiqueta_label',
            [
                'attribute' => 'contenido_html',
                'format' => 'raw',
            ],
            'orden',
            [
                'attribute' => 'visible',
                'value' => $model->visible ? 'Sí' : 'No',
            ],
        ],
    ]) ?>

</div>

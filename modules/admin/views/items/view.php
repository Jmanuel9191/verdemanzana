<?php

use app\models\Items;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ítems / Proveedores', 'url' => ['index', 'ItemsSearch[tipo]' => $model->tipo]];
$this->params['breadcrumbs'][] = $this->title;

$activo = (int)$model->estado === Items::ESTADO_ACTIVO;
$esProveedor = $model->tipo === Items::TIPO_PROVEEDOR;
$fecha = function ($valor) {
    return $valor ? date('d/m/Y H:i', strtotime($valor)) : '';
};
?>
<section class="content-header">
    <h1>
        <?= Html::encode($this->title) ?>
        <small><?= Html::encode($model->getTipoTexto()) ?></small>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <?php foreach (['success' => 'success', 'error' => 'danger'] as $tipoFlash => $clase): ?>
                <?php if (Yii::$app->session->hasFlash($tipoFlash)): ?>
                    <div class="alert alert-<?= $clase ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= Html::encode(Yii::$app->session->getFlash($tipoFlash)) ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="box">
                <div class="box-header">
                    <?= Html::a('<i class="fa fa-arrow-left"></i> Volver', ['index', 'ItemsSearch[tipo]' => $model->tipo], ['class' => 'btn btn-default']) ?>
                    <?= Html::a('<i class="fa fa-pencil"></i> Editar', ['update', 'id' => $model->iditem], ['class' => 'btn btn-info']) ?>
                    
                    <?= Html::a('<i class="fa fa-trash"></i> Eliminar', ['delete', 'id' => $model->iditem], [
                        'class' => 'btn btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => '¿Eliminar "' . $model->nombre . '"? Las reservas ya guardadas no se modifican.',
                    ]) ?>
                </div>

                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => array_values(array_filter([
                            ['attribute' => 'tipo', 'value' => $model->getTipoTexto()],
                            'nombre',
                            $esProveedor ? ['attribute' => 'empresa', 'value' => $model->empresa ?: '—'] : null,
                            $esProveedor ? ['attribute' => 'telefono', 'value' => $model->telefono ?: '—'] : null,
                            ['attribute' => 'unidad', 'value' => $model->getUnidadTexto()],
                            ['attribute' => 'precio_unitario', 'value' => $model->getPrecioTexto()],
                            ['attribute' => 'tipo_evento', 'value' => $model->getTipoEventoTexto()],
                            [
                                'attribute' => 'estado',
                                'format' => 'raw',
                                'value' => $activo
                                    ? '<span class="label label-success">Activo</span>'
                                    : '<span class="label label-default">Inactivo</span>',
                            ],
                            'posicion',
                            ['attribute' => 'detalle', 'format' => 'html'],
                            ['attribute' => 'fecha_registro', 'value' => $fecha($model->fecha_registro)],
                            ['attribute' => 'fecha_actualizacion', 'value' => $fecha($model->fecha_actualizacion)],
                        ])),
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>

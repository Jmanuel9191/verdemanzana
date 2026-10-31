<?php

use app\models\Paquetes;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Paquetes */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Paquetes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$activo = (int)$model->estado === Paquetes::ESTADO_ACTIVO;
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <?php foreach (['success' => 'success', 'error' => 'danger'] as $tipo => $clase): ?>
                <?php if (Yii::$app->session->hasFlash($tipo)): ?>
                    <div class="alert alert-<?= $clase ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= Html::encode(Yii::$app->session->getFlash($tipo)) ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="box">
                <div class="box-header">
                    <?= Html::a('<i class="fa fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-default']) ?>
                    <?= Html::a('<i class="fa fa-pencil"></i> Editar', ['update', 'id' => $model->idpaquete], ['class' => 'btn btn-info']) ?>
                    
                    <?= Html::a('<i class="fa fa-trash"></i> Eliminar', ['delete', 'id' => $model->idpaquete], [
                        'class' => 'btn btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => '¿Eliminar el paquete "' . $model->nombre . '"? Las reservas ya guardadas no se modifican.',
                    ]) ?>
                </div>

                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'nombre',
                            [
                                'attribute' => 'tipo_evento',
                                'value' => $model->getTipoEventoTexto(),
                            ],
                            [
                                'attribute' => 'precio',
                                'value' => $model->getPrecioTexto(),
                            ],
                            [
                                'attribute' => 'invitados_incluidos',
                                'value' => $model->invitados_incluidos ?? '—',
                            ],
                            [
                                'attribute' => 'horas_incluidas',
                                'value' => $model->horas_incluidas ?? '—',
                            ],
                            [
                                'attribute' => 'estado',
                                'format' => 'raw',
                                'value' => $activo
                                    ? '<span class="label label-success">Activo</span>'
                                    : '<span class="label label-default">Inactivo</span>',
                            ],
                            'posicion',
                            [
                                'attribute' => 'detalle',
                                'format' => 'html', // pasa por HTMLPurifier: muestra el formato sin permitir scripts
                            ],
                            [
                                'attribute' => 'fecha_registro',
                                'value' => $model->fecha_registro ? date('d/m/Y H:i', strtotime($model->fecha_registro)) : '',
                            ],
                            [
                                'attribute' => 'fecha_actualizacion',
                                'value' => $model->fecha_actualizacion ? date('d/m/Y H:i', strtotime($model->fecha_actualizacion)) : '',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>

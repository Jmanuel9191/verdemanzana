<?php

use app\models\Paquetes;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaquetesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Paquetes';
$this->params['breadcrumbs'][] = $this->title;
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
                    <?= Html::a('<i class="fa fa-plus"></i> Nuevo paquete', ['create'], ['class' => 'btn btn-success']) ?>
                </div>

                <div class="box-body">
                    <?php Pjax::begin(['id' => 'grilla-paquetes']); ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'emptyText' => 'No se encontraron paquetes.',
                            'summary' => 'Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b> paquetes.',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'nombre',
                                    'format' => 'raw',
                                    'value' => function (Paquetes $model) {
                                        return Html::a(Html::encode($model->nombre), ['view', 'id' => $model->idpaquete], ['data-pjax' => '0']);
                                    },
                                    'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Buscar nombre...'],
                                ],
                                [
                                    'attribute' => 'tipo_evento',
                                    'value' => function (Paquetes $model) {
                                        return $model->getTipoEventoTexto();
                                    },
                                    'filter' => Paquetes::tiposEvento(),
                                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => '— Cualquiera —'],
                                    'contentOptions' => ['style' => 'white-space:nowrap'],
                                ],
                                [
                                    'attribute' => 'precio',
                                    'value' => function (Paquetes $model) {
                                        return $model->getPrecioTexto();
                                    },
                                    'filter' => false,
                                    'contentOptions' => ['class' => 'text-right', 'style' => 'white-space:nowrap'],
                                    'headerOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'invitados_incluidos',
                                    'label' => 'Invitados',
                                    'value' => function (Paquetes $model) {
                                        return $model->invitados_incluidos !== null ? $model->invitados_incluidos : '—';
                                    },
                                    'filter' => false,
                                    'contentOptions' => ['class' => 'text-center'],
                                    'headerOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'attribute' => 'horas_incluidas',
                                    'label' => 'Horas',
                                    'value' => function (Paquetes $model) {
                                        return $model->horas_incluidas !== null ? $model->horas_incluidas : '—';
                                    },
                                    'filter' => false,
                                    'contentOptions' => ['class' => 'text-center'],
                                    'headerOptions' => ['class' => 'text-center'],
                                ],
                                
                                [
                                    'attribute' => 'estado',
                                    'format' => 'raw',
                                    'value' => function (Paquetes $model) {
                                        $activo = (int)$model->estado === Paquetes::ESTADO_ACTIVO;
                                        return Html::button(
                                            '<i class="fa ' . ($activo ? 'fa-eye' : 'fa-eye-slash') . '"></i>',
                                            [
                                                'class' => 'btn btn-sm btn-paquete-estado btn-' . ($activo ? 'success' : 'default'),
                                                'title' => $activo ? 'Activo (clic para desactivar)' : 'Inactivo (clic para activar)',
                                                'data-url' => Url::to(['estado', 'id' => $model->idpaquete]),
                                            ]
                                        );
                                    },
                                    'filter' => Paquetes::estados(),
                                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => '— Todos —'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'headerOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Operaciones',
                                    'template' => '<div class="btn-group" role="group">{view}{update}{delete}</div>',
                                    'buttons' => [
                                        'view' => function ($url) {
                                            return Html::a('<i class="fa fa-search"></i>', $url, ['class' => 'btn btn-sm btn-default', 'title' => 'Ver', 'data-pjax' => '0']);
                                        },
                                        'update' => function ($url) {
                                            return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-sm btn-info', 'title' => 'Editar', 'data-pjax' => '0']);
                                        },
                                        'delete' => function ($url, Paquetes $model) {
                                            return Html::a('<i class="fa fa-trash"></i>', $url, [
                                                'class' => 'btn btn-sm btn-danger',
                                                'title' => 'Eliminar',
                                                'data-pjax' => '0',
                                                'data-method' => 'post',
                                                'data-confirm' => '¿Eliminar el paquete "' . $model->nombre . '"? Las reservas ya guardadas no se modifican.',
                                            ]);
                                        },
                                    ],
                                    'contentOptions' => ['style' => 'white-space:nowrap'],
                                ],
                            ],
                        ]); ?>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// Ojo de estado: POST por AJAX (con token CSRF) y recarga solo la grilla.
$js = <<<JS
$(document).on('click', '.btn-paquete-estado', function (e) {
    e.preventDefault();
    var btn = $(this);
    btn.prop('disabled', true);
    $.post(btn.data('url'), {_csrf: yii.getCsrfToken()})
        .done(function () {
            $.pjax.reload({container: '#grilla-paquetes', timeout: 5000});
        })
        .fail(function () {
            alert('No se pudo cambiar el estado. Recargá la página e intentá de nuevo.');
            btn.prop('disabled', false);
        });
});
JS;
$this->registerJs($js);

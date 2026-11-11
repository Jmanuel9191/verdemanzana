<?php

use app\models\Items;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totales array ['item' => n, 'proveedor' => n] */

$tipoActual = (string)$searchModel->tipo;   // 'item' | 'proveedor' | '' (todos)

$this->title = 'Ítems / Proveedores / Experiencias';
$this->params['breadcrumbs'][] = $this->title;

$pestanas = [
    Items::TIPO_ITEM        => ['Ítems', '', $totales[Items::TIPO_ITEM] ?? 0],
    Items::TIPO_PROVEEDOR   => ['Proveedores', '', $totales[Items::TIPO_PROVEEDOR] ?? 0],
    Items::TIPO_EXPERIENCIA => ['Experiencias', '', $totales[Items::TIPO_EXPERIENCIA] ?? 0],
    ''                      => ['Todos', '', array_sum($totales)],
];

// Columnas
$columnas = [
    ['class' => 'yii\grid\SerialColumn'],
];

if ($tipoActual === '') {
    $columnas[] = [
        'attribute' => 'tipo',
        'value' => function (Items $model) {
            return $model->getTipoTexto();
        },
        'filter' => false,
    ];
}

$columnas[] = [
    'attribute' => 'nombre',
    'format' => 'raw',
    'value' => function (Items $model) {
        $html = Html::a(Html::encode($model->nombre), ['view', 'id' => $model->iditem], ['data-pjax' => '0']);
        if ($model->empresa) {
            $html .= '<br><small class="text-muted"><i class="fa fa-building-o"></i> ' . Html::encode($model->empresa) . '</small>';
        }
        return $html;
    },
    'filterInputOptions' => [
        'class' => 'form-control',
        'placeholder' => $tipoActual === Items::TIPO_PROVEEDOR ? 'Nombre o empresa...' : 'Buscar nombre...',
    ],
];
$columnas[] = [
    'attribute' => 'unidad',
    'value' => function (Items $model) {
        return $model->getUnidadTexto();
    },
    'filter' => Items::unidades(),
    'filterInputOptions' => ['class' => 'form-control', 'prompt' => '— Todas —'],
];
$columnas[] = [
    'attribute' => 'precio_unitario',
    'label' => 'Precio',
    'value' => function (Items $model) {
        return $model->getPrecioTexto();
    },
    'filter' => false,
    'contentOptions' => ['class' => 'text-right', 'style' => 'white-space:nowrap'],
    'headerOptions' => ['class' => 'text-right'],
];
$columnas[] = [
    'attribute' => 'tipo_evento',
    'value' => function (Items $model) {
        return $model->getTipoEventoTexto();
    },
    'filter' => Items::tiposEvento(),
    'filterInputOptions' => ['class' => 'form-control', 'prompt' => '— Cualquiera —'],
    'contentOptions' => ['style' => 'white-space:nowrap'],
];
$columnas[] = [
    'attribute' => 'estado',
    'format' => 'raw',
    'value' => function (Items $model) {
        $activo = (int)$model->estado === Items::ESTADO_ACTIVO;
        return Html::button(
            '<i class="fa ' . ($activo ? 'fa-eye' : 'fa-eye-slash') . '"></i>',
            [
                'class' => 'btn btn-sm btn-item-estado btn-' . ($activo ? 'success' : 'default'),
                'title' => $activo ? 'Activo (clic para desactivar)' : 'Inactivo (clic para activar)',
                'data-url' => Url::to(['estado', 'id' => $model->iditem]),
            ]
        );
    },
    'filter' => Items::estados(),
    'filterInputOptions' => ['class' => 'form-control', 'prompt' => '— Todos —'],
    'contentOptions' => ['class' => 'text-center'],
    'headerOptions' => ['class' => 'text-center'],
];
$columnas[] = [
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
        'delete' => function ($url, Items $model) {
            return Html::a('<i class="fa fa-trash"></i>', $url, [
                'class' => 'btn btn-sm btn-danger',
                'title' => 'Eliminar',
                'data-pjax' => '0',
                'data-method' => 'post',
                'data-confirm' => '¿Eliminar "' . $model->nombre . '"? Las reservas ya guardadas no se modifican.',
            ]);
        },
    ],
    'contentOptions' => ['style' => 'white-space:nowrap'],
];
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
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

            <div style="margin-bottom:15px">
                <?= Html::a('<i class="fa fa-plus"></i> Nuevo ítem', ['create', 'tipo' => Items::TIPO_ITEM], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fa fa-plus"></i> Nuevo proveedor', ['create', 'tipo' => Items::TIPO_PROVEEDOR], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fa fa-plus"></i> Nueva experiencia', ['create', 'tipo' => Items::TIPO_EXPERIENCIA], ['class' => 'btn btn-success']) ?>
            </div>

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php foreach ($pestanas as $clave => [$texto, $icono, $cantidad]): ?>
                        <li class="<?= $tipoActual === (string)$clave ? 'active' : '' ?>">
                            <a href="<?= Url::to(['index', 'ItemsSearch[tipo]' => $clave]) ?>">
                                <i class="fa <?= $icono ?>"></i> <?= $texto ?>
                                <span class="badge"><?= (int)$cantidad ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content">
                    <?php Pjax::begin(['id' => 'grilla-items']); ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'emptyText' => 'No se encontraron registros.',
                            'summary' => 'Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b>.',
                            'columns' => $columnas,
                        ]); ?>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php

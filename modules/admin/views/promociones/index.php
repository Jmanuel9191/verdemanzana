<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promociones';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>
    <section class="content-header">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header">
                        <?= Html::a('Nueva Promocion', ['create'], ['class' => 'btn btn-success']) ?>
                    </div>

                    <div class="box-body ">
                        <?php Pjax::begin(['id'=>'table']); ?>
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    //'idnoticia',
                                    [
                                        'header' => 'categoria',
                                        'value' => function ($model) {
                                            if (!empty($model->idcategoria))
                                                return $model->categoria['nombre'];
                                            return '';
                                        },
                                        'filter' => \kartik\widgets\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'idcategoria',
                                            'data' => \app\models\Categoria::getSelectMenu('promociones'),
                                            'language' => 'es',
                                            'options' => [
                                                'placeholder' => 'Categorias',
                                                //'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'templateResult' => new \yii\web\JsExpression('format'),
                                                'templateSelection' => new \yii\web\JsExpression('format'),
                                                'escapeMarkup' => $escape,
                                                'allowClear' => true
                                            ],
                                        ])
                                    ],
                                    'titulo',
                                    //'resumen:ntext',
                                    // 'fuente',
                                    // 'url:url',
                                    // 'fecha_registro',
                                    // 'fecha_noticia',
                                    [
                                        'header' => 'Foto',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $img = \yii\helpers\Url::to($model->foto);
                                            return "<img src='{$img}' class='img-thumbnail'>";
                                        },
                                        'contentOptions' => ['class' => 'col-md-3']
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Posicion',
                                        'template' => '<div class="btn-group btn-group-justified" role="group">{up}{down}</div>',
                                        'buttons' => [
                                            'up' => function ($url, $model, $key) {
                                                if ($model->idpromocion > 1) {
                                                    return Html::a('<i class="fa fa-chevron-up toggle"></i>', "#", ['class' => 'btn btn-info','onclick'=>"action('{$url}'); return false;", "title"=>"Arriba"]);
                                                }
                                                return Html::a('<i class="fa fa-chevron-up"></i>', '#', ['class' => 'btn btn-info disable']);
                                            },
                                            'down' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-chevron-down toggle"></i>', "#", ['class' => 'btn btn-info','onclick'=>"action('{$url}'); return false;","title"=>"Abajo"]);
                                            },
                                        ],
                                        'contentOptions' => ['class' => 'col-sm-1']
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Estado',
                                        'template' => '<div class="btn-group btn-group-justified" role="group">{estado}{destacado}</div>',
                                        'buttons' => [
                                            'estado' => function ($url, $model, $key) {
                                                return Html::a('<i class="toggle fa fa-eye"></i>', "#", ['class' => 'btn btn-'.(($model->estado) ? 'success' : 'default'),'onclick'=>"action('{$url}'); return false;", "title"=>"Visible"]);
                                            },
                                            'destacado' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-star"></i>', "#", ['class' => 'btn btn-'.(($model->destacado) ? 'success' : 'default'), 'onclick'=>"action('{$url}'); return false;", "title"=>"Destacado"]);
                                            },
                                        ],
                                        'contentOptions' => ['class' => 'col-sm-1']
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Operaciones',
                                        'template' => '<div class="btn-group btn-group-justified" role="group">{galeria}{update}{delete}</div>',
                                        'buttons' => [
                                            'galeria' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-picture-o"></i>', $url, ['class' => 'btn btn-primary',"title"=>"Galeria"]);
                                            },
                                            'update' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-info',"title"=>"Editar"]);
                                            },
                                            'delete' => function ($url, $model, $key) {

                                                return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data' => [
                                                    'confirm' => 'Esta seguro?.',
                                                    'method' => 'post',
                                                    'pjax' => '0',
                                                ], "title"=>"Eliminar"]);
                                            },
                                        ],
                                        'contentOptions' => ['class' => 'col-sm-2']
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

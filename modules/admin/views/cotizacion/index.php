<?php

use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cotizaciones';
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
                        <?//= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success']) ?>

                    
                        <?php /* Html::a('Nuevo Banner', ['create'], ['class' => 'btn btn-success'])*/
                        echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'nombre',
                                'telefono',
                                'tipo_evento',
                                'fecha_evento',
                                'email:email',
                                'cantidad_invitados',
                                'sucursal',
                                'comentario',
                                'fecha_registro',

                            ],
                            'target' => ExportMenu::TARGET_BLANK,
                            'fontAwesome' => true,
                            'stream' => false,
                            'filename' => 'newsletter'
                        ]);
                        ?>
                        

                    </div>

                    <div class="box-body">

                        <?php Pjax::begin(['id' => 'table']); ?>

                        <div class="table-responsive veremosqpasa">   <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                   /* [
                                        'label' => 'Numero',
                                        'value' => function ($model) {

                                            return $model->idclub;
                                        },
                                    ],*/
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'nombre',

                                    ],

                                    'telefono',

                                    //'movil',
                                    // 'pais',
                                    // 'ciudad',

                                    // 'contrasena',
                                    'sucursal',
                                    'fecha_evento',
                                    // 'registrador',
                                   /* [
                                        'header' => 'Tickeado Movil',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getEstado();
                                        },
                                        'filter' => \kartik\widgets\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'estado',
                                            'data' => (new \app\models\Club())->getEstado(true),
                                            'language' => 'es',
                                            'options' => [
                                                'placeholder' => 'Estado',
                                                //'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'templateResult' => new \yii\web\JsExpression('format'),
                                                'templateSelection' => new \yii\web\JsExpression('format'),
                                                'escapeMarkup' => $escape,
                                                'allowClear' => true
                                            ],
                                        ])
                                    ],*/

                                    //'fecha_tickeo',

                                  /*  [
                                        'header' => 'Tickeado Supervisor',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getEstado2();
                                        },
                                        'filter' => \kartik\widgets\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'estado2',
                                            'data' => (new \app\models\Club())->getEstado2(true),
                                            'language' => 'es',
                                            'options' => [
                                                'placeholder' => 'Estado',
                                                //'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'templateResult' => new \yii\web\JsExpression('format'),
                                                'templateSelection' => new \yii\web\JsExpression('format'),
                                                'escapeMarkup' => $escape,
                                                'allowClear' => true
                                            ],
                                        ])
                                    ],*/
                                    //'fecha_tickeo2',
                                    // 'fecha_registro',

                                    // 'sexo',
                                    // 'activo',

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Operaciones',
                                        'template' => '<div class="btn-group btn-group-justified " role="group">{estado}{view}{delete}</div>',
                                        'buttons' => [
                                            'estado' => function ($url, $model, $key) {
                                                return Html::a('<i class="toggle fa fa-check"></i>', "#", ['class' => 'btn btn-'.(($model->estado) ? 'success2' : 'default'),'onclick'=>"action('{$url}'); return false;", "title"=>"Visible"]);
                                            },
                                            'estado2' => function ($url, $model, $key) {
                                                return Html::a('<i class="toggle fa fa-check-circle"></i>', "#", ['class' => 'btn btn-'.(($model->estado2) ? 'success3' : 'default'),'onclick'=>"action('{$url}'); return false;", "title"=>"Visible"]);
                                            },
                                            'view' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-success', "title" => "Ver"]);
                                            },
                                            'estade' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-check-square-o"></i>', $url, ['class' => 'btn btn-'.((($model->verificado = 0)||($model->carnet_adelante)||($model->carnet_atras)||($model->foto_selfie)) ? 'warning' : 'default'), "title" => "Editar"]);
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data' => [
                                                    'confirm' => 'Esta seguro?.',
                                                    'method' => 'post',
                                                    'pjax' => '0',
                                                ], "title" => "Eliminar"]);
                                            },

                                            'verificado' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-address-card-o"></i>', "#", ['class' => 'btn btn-'.(($model->verificado) ? 'success' : 'default'), 'onclick'=>"action('{$url}'); return false;", "title"=>"Verificado"]);
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


<?php

use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organizadoras';
$this->params['breadcrumbs'][] = $this->title;
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
                        <?= Html::a('Nueva organizadora', ['create'], ['class' => 'btn btn-success']) ?>
                        <?php /* Html::a('Nuevo Banner', ['create'], ['class' => 'btn btn-success'])*/
                        echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'email',
                                'nombre',
                                'telefono',
                                'direccion',
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

                                   

                                    ['class' => 'yii\grid\SerialColumn'],

                                    //'idusuario',
                                    'nombre',
                                    'telefono',                                  
                                    //'fecha_fin',
                                    //'movil',
                                    // 'pais',
                                    // 'ciudad',
                                    'email:email',
                                    // 'contrasena',
                                    // 'fecha_nacimiento',
                                    // 'movil',
                                    // 'fecha_registro',
                                    /*[
                                        'header' => 'Estado',
                                        'value' => function ($model) {
                                            return \app\models\Usuarios::getEstado($model->estado);
                                        },
                                        'filter' => \kartik\widgets\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'estado',
                                            'data' => \app\models\Usuarios::getEstado(),
                                            'language' => 'es',
                                            'options' => [
                                                'placeholder' => 'Estado',
                                                //'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])
                                    ],*/
                                    /*[
                                        'header' => 'Tipo',
                                        'value' => function ($model) {
                                            return \app\models\Usuarios::getTipo($model->tipo);
                                        },
                                        'filter' => \kartik\widgets\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'tipo',
                                            'data' => \app\models\Usuarios::getTipo(),
                                            'language' => 'es',
                                            'options' => [
                                                'placeholder' => 'Tipo',
                                                //'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])
                                    ],*/
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
                                            'view' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-success', "title" => "Editar"]);
                                            },
                                            'estade' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-check-square-o"></i>', $url, ['class' => 'btn btn-' . ((($model->verificado = 0) || ($model->carnet_adelante) || ($model->carnet_atras) || ($model->foto_selfie)) ? 'warning' : 'default'), "title" => "Editar"]);
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data' => [
                                                    'confirm' => 'Esta seguro?.',
                                                    'method' => 'post',
                                                    'pjax' => '0',
                                                ], "title" => "Eliminar"]);
                                            },

                                            'verificado' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-address-card-o"></i>', "#", ['class' => 'btn btn-' . (($model->verificado) ? 'success' : 'default'), 'onclick' => "action('{$url}'); return false;", "title" => "Verificado"]);
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


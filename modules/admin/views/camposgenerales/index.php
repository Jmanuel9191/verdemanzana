<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormTextosEstaticosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Textos del Formulario';
$this->params['breadcrumbs'][] = $this->title;


$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>

    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header">
                        <!--<?//= Html::a('Nuevo Texto Estático', ['create'], ['class' => 'btn btn-success']) ?>-->
                    </div>

                    <div class="box-body">
                        <?php Pjax::begin(['id' => 'table']); ?>
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    [
                                        'attribute' => 'grupo',
                                        'filter' => Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'grupo',
                                            'data' => \app\models\FormTextosEstaticos::find()
                                                ->select('grupo')
                                                ->distinct()
                                                ->indexBy('grupo')
                                                ->column(),
                                            'options' => ['placeholder' => 'Grupo...'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'templateResult' => new \yii\web\JsExpression('format'),
                                                'templateSelection' => new \yii\web\JsExpression('format'),
                                                'escapeMarkup' => $escape
                                            ],
                                        ]),
                                    ],

                                    'etiqueta_label',
                                    'orden',

                                    [
                                        'attribute' => 'visible',
                                        'format' => 'boolean',
                                        'filter' => [
                                            1 => 'Sí',
                                            0 => 'No'
                                        ],
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<div class="btn-group btn-group-justified" role="group">{update}{delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-info']);
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="fa fa-trash"></i>', $url, [
                                                    'class' => 'btn btn-danger',
                                                    'data' => [
                                                        'confirm' => '¿Está seguro de eliminar este texto?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            },
                                        ],
                                        'contentOptions' => ['class' => 'col-sm-1'],
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

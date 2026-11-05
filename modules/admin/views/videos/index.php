<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VideosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>

                </div>

                <div class="box-body ">
                    <?php Pjax::begin(); ?>
                    <div class="table-responsive">
                        <?php Pjax::begin(); ?>    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                // 'idvideo',
                                'titulo',
                              //  'descripcion:ntext',
                                'url',
                                //'tumb',
                                // 'idcategoria',
                                [
                                    'header' => 'categoria',
                                    'value' => function ($model) {
                                        if (!empty($model->idcategoria))
                                            return \app\models\Categoria::find()->where(['=', 'idcategoria', $model->idcategoria])->one()['nombre'];
                                        return '';
                                    },
                                ],
                                // 'tags:ntext',
                                // 'posicion',
                                // 'fecha_video',
                                // 'fecha_registro',
                                // 'estado',
                               /* [
                                    'class' => \dosamigos\grid\ToggleColumn::className(),
                                    'attribute' => 'estado',
                                    'onValue' => '1',
                                    'onLabel' => 'Activo',
                                    'onIcon' => 'fa fa-check',
                                    'offIcon' => 'fa fa-close',
                                    'offLabel' => 'No Activo',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],*/
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Operaciones',
                                    'template' => '<div class="btn-group btn-group-justified" role="group">{update}{delete}</div>',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-info']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'title' => 'Eliminar', 'data' => [
                        'confirm' => 'Esta seguro?.',
                        'method' => 'post',
                        'pjax' => '0',
                    ]]);
                                        },
                                    ],
                                    'contentOptions' => ['class' => 'col-sm-1']
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

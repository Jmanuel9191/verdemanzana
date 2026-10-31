<?php

use app\models\Modulos;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categorias';
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
                    <?= Html::a('Create Categoria', ['create'], ['class' => 'btn btn-success']) ?>

                </div>

                <div class="box-body ">
                    <?php Pjax::begin(); ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'header' => 'Modulo',
                                    'filter'=>\kartik\widgets\Select2::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'modulo',
                                        'data' => \yii\helpers\ArrayHelper::map(Modulos::findAll(['estado'=>'1']),'idmodulo','nombre'),
                                        'options' => ['placeholder' => ''],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]),
                                    'value' => function ($model) {
                                        if (!empty($model->modulo))
                                            return Modulos::find()->where(['=', 'idmodulo', $model->modulo])->one()?->nombre ?? '';
                                        return '';
                                    },
                                ],
                                //'idcategoria',
                                'nombre',
                                //'descripcion:ntext',
                                //'imagen',
           
                                // 'link1',
                                
                                // 'link2',
                                // 'imagen3',
                                // 'idpadre',
                                [
                                    'header' => 'Padre',
                                    'value' => function ($model) {
                                        if (!empty($model->idpadre))
                                            return \app\models\Categoria::find()->where(['=', 'idcategoria', $model->idpadre])->one()?->nombre ?? '';
                                        return '';
                                    },
                                ],
                                // 'modulo',

                                // 'estado',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Operaciones',
                                    'template' => '<div class="btn-group btn-group-justified" role="group">{update}{delete}</div>',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-info']);
                                        },
                                        'delete' => function ($url, $model, $key) {

                                            return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger','data' => [
                                                'confirm' => 'Esta seguro?.'
                                            ],]);
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
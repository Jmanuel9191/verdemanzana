<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Carrito */

$this->title = $model->idcarrito;
$this->params['breadcrumbs'][] = ['label' => 'Carritos', 'url' => ['index']];
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
                    <a class="btn btn-default" href="<?= \yii\helpers\Url::to(['index'])?>">Atras</a>
                </div>

                <div class="box-body ">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idcarrito',
                            [
                                'attribute' => 'idusuario',
                                //'format'=>'raw',
                                'value' => $model->usuarios['nombre'].' '.$model->usuarios['apellido'],
                            ],
                            'fecha_registro',
                            'monto_total',
                        ],
                    ]) ?>


                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3>Detalle</h3>
                </div>

                <div class="box-body ">
                    <?php
                    $items = \app\models\Detallecarrito::find()->where(['idcarrito'=>$model->idcarrito]);
                    $dataProvider = new \yii\data\ActiveDataProvider([
                        'query' => $items,
                        'pagination'=>false,
                    ]);

                    ?>
                    <?= \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            //'idcarrito',
                            [
                                'header' => 'producto',
                                'value' => function ($model) {

                                    return  $model->producto['titulo'];
                                },
                            ],
                            'cantidad',
                            'precio',
                            'subtotal',
                        ],
                    ]) ?>


                </div>
            </div>
        </div>
</section>


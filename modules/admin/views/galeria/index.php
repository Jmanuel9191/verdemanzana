<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Galeria_search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Galerias';
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
                    <?= Html::a('Create Galeria', ['create'], ['class' => 'btn btn-success']) ?>
                </div>

                <div class="box-body ">
                    <?php Pjax::begin(); ?>
                    <div class="table-responsive">    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'idgaleria',
                                'nombre',
                                'descripcion:ntext',
                                'foto',
                                'tumb',
                                // 'idcategoria',
                                // 'tags:ntext',
                                // 'posicion',
                                // 'fecha_registro',
                                // 'estado',

                                ['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>
                    </div>
                    <?php Pjax::end(); ?></div>
            </div>
        </div>
    </div>
</section>



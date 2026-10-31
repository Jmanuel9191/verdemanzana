<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarritoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Carritos';
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
                    <?php // = Html::a('Nuevo Producto', ['create'], ['class' => 'btn btn-success']) ?>
                </div>

                <div class="box-body ">

                    <?php Pjax::begin(['id'=>'table']); ?>
                    <div class="table-responsive">   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idcarrito',
            'idusuario',
            'estado',
            'fecha_registro',
            'nit',
            // 'razon_social',
            // 'session',
            // 'monto_total',
            // 'fecha_pago',
            // 'tipo_pago',
            // 'tipo_carro',

            ['class' => 'yii\grid\ActionColumn'],
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
$script = <<<JS
    function action(url)
    {
    $.get(url, function(data) {
      $.pjax.reload({container:"#table"});
    });
    }

JS;
$this->registerJs($script, \yii\web\View::POS_HEAD);


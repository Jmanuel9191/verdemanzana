<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reservas';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$format = <<< SCRIPT
function format(state) {
if (!state.id) return state.text; // optgroup
return state.text;
}
SCRIPT;
$escape = new \yii\web\JsExpression("function(m) { return m; }");
$this->registerJs($format, \yii\web\View::POS_HEAD);
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
                    <?= Html::a('Nueva Reserva', ['create'], ['class' => 'btn btn-success']) ?>
                </div>

                <div class="box-body ">

                    <?php Pjax::begin(['id'=>'table']); ?>
                    <div class="table-responsive">   <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

            //'idusuario',
            'nombre',
            'telefono',
            'sucursal',
            'tipoevento',
                                'limite_invitados',



            // 'pais',
            // 'ciudad',
            'email:email',
            [
                'header' => 'organizadora',
                'value' => function ($model) {
                    if (!empty($model->idorganizadora))
                        return $model->organizadora['nombre'];
                    return '';
                },
                'filter' => \kartik\widgets\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'idorganizadora',
                    'data' => \app\models\Organizadoras::getSelectorganizadoras(),
                    'language' => 'es',
                    'options' => [
                        'placeholder' => 'Organizadoras',
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
            // 'contrasena',
            // 'fecha_nacimiento',
            // 'movil',
            // 'fecha_registro',
            // 'estado',
            // 'sexo',
            // 'activo',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Operaciones',
                'template' => '<div class="btn-group btn-group-justified" role="group">{estado}{view}{viewcronograma}{delete}</div>',
                'buttons' => [
                    'estado' => function ($url, $model, $key) {
                        return Html::a('<i class="toggle fa fa-check"></i>', "#", ['class' => 'btn btn-'.(($model->estado) ? 'success2' : 'default'),'onclick'=>"action('{$url}'); return false;", "title"=>"Visible"]);
                    },

                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-info', "title" => "Ver"]);
                    },
                    'viewcronograma' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-info guiased', "title" => "Ver GUIA"]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-success',"title"=>"Editar"]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data' => [
                            'confirm' => 'Esta seguro?.'
                        ], "title" => "Eliminar"]);
                    },
                ],
                'contentOptions' => ['class' => 'col-sm-2']
            ],


                                /*[
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Operaciones',
                                    'template' => '<div class="btn-group btn-group-justified" role="group">{galeriadecoracion}{galeriacronograma}</div>',
                                    'buttons' => [

                                        'galeriadecoracion' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-picture-o"></i>', $url, ['class' => 'btn btn-primary',"title"=>"Galeria Decoracion"]);
                                        },

                                        'galeriacronograma' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-picture-o"></i>', $url, ['class' => 'btn btn-primary',"title"=>"Galeria Cronograma"]);
                                        },



                                    ],
                                    'contentOptions' => ['class' => 'col-sm-2']
                                ],*/
        ],
    ]); ?>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>


    <style>
        .btn-success2 > i {
            color: white!important;

        }
        .btn-success2 {
            background-color: #d24318;
            border-color: #d24318;
        }

        .btn-success3 > i {
            color: white!important;

        }
        .btn-success3 {
            background-color: #395398;
            border-color: #395398;
        }

        .btn.btn-info.guiased
        {
            background: #00a65a;
            border-color: #00a65a;
        }
        }
    </style>




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

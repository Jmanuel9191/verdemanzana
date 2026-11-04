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
    <?= Html::a('Nueva reserva', ['reserva-pasos/create'], ['class' => 'btn btn-success']) ?>
</div>

<?php foreach (['success' => 'success', 'error' => 'danger'] as $tipoFlash => $clase): ?>
    <?php if (Yii::$app->session->hasFlash($tipoFlash)): ?>
        <div class="alert alert-<?= $clase ?> alert-dismissible" style="margin:0 10px 10px">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?= Html::encode(Yii::$app->session->getFlash($tipoFlash)) ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>


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
            [
                'attribute' => 'sucursal',
                'filter' => \app\models\PresupuestoForm::SUCURSALES,
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Todas'],
            ],
            [
                'attribute' => 'tipoevento',
                'label' => 'Tipo de evento',
                'filter' => \app\models\PresupuestoForm::TIPOS_EVENTO,
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Todos'],
                'value' => function ($model) {
                    return \app\models\PresupuestoForm::TIPOS_EVENTO[$model->tipoevento] ?? $model->tipoevento;
                },
            ],
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
                'template' => '<div class="btn-group btn-group-justified" role="group">{presupuesto}{pagos}{estado}{view}{delete}</div>','buttons' => [
                    'estado' => function ($url, $model, $key) {
                        $activa = (int)$model->estado === 1;
                        return Html::a('<i class="toggle fa fa-check"></i>', "#", [
                            'class' => 'btn btn-' . ($activa ? 'success2' : 'default'),
                            'onclick' => "cambiarEstado('{$url}'); return false;",
                            'title' => $activa ? 'Activa (clic para desactivar)' : 'Inactiva (clic para activar)',
                        ]);
                    },

                    'view' => function ($url, $model, $key) {
                        // Reservas nuevas (con presupuesto por pasos) → ficha nueva; las viejas → vista de siempre
                        if (\app\models\ReservaCotizacion::find()->where(['idreservas' => $model->idreservas])->exists()) {
                            return Html::a('<i class="fa fa-eye"></i>', ['reserva-pasos/ver', 'id' => $model->idreservas],
                                ['class' => 'btn btn-info', 'title' => 'Ver reserva', 'data-pjax' => '0']);
                        }
                        return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-info', "title" => "Ver"]);
                    },
                    'viewcronograma' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-info guiased', "title" => "Ver GUIA"]);
                    },

                    'presupuesto' => function ($url, $model, $key) {
                        if (!\app\models\ReservaCotizacion::find()->where(['idreservas' => $model->idreservas])->exists()) {
                            return '';
                        }
                        return Html::a('<i class="fa fa-calculator"></i>', ['reserva-pasos/presupuesto', 'id' => $model->idreservas],
                            ['class' => 'btn btn-warning', 'title' => 'Presupuesto', 'data-pjax' => '0']);
                    },
                    'pagos' => function ($url, $model, $key) {
                        if (!\app\models\ReservaCotizacion::find()->where(['idreservas' => $model->idreservas])->exists()) {
                            return '';
                        }
                        return Html::a('<i class="fa fa-money"></i>', ['reserva-pasos/pagos', 'id' => $model->idreservas],
                            ['class' => 'btn btn-primary', 'title' => 'Pagos', 'data-pjax' => '0']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-success',"title"=>"Editar"]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash"></i>', $url, [
                            'class' => 'btn btn-danger',
                            'title' => 'Eliminar',
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => '¿Seguro que querés eliminar esta reserva? No se puede deshacer.',
                                'method' => 'post',
                            ],
                        ]);
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
    </style>




<?php
// Activar / desactivar: POST con token de seguridad (antes era GET)
$script = <<<JS
    function cambiarEstado(url)
    {
        $.post(url, {_csrf: yii.getCsrfToken()})
            .done(function () { $.pjax.reload({container: "#table", timeout: 5000}); })
            .fail(function (xhr) {
                alert(xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'No se pudo cambiar el estado.');
            });
    }
JS;
$this->registerJs($script, \yii\web\View::POS_HEAD);

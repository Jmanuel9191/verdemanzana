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
    <?= Html::a('<i class="fa fa-file-excel-o"></i> Descargar Lista', ['reservas-excel/index'], [
        'id' => 'btn-excel-reservas',
        'class' => 'btn btn-default',
        'style' => 'margin-left:6px',
        'data-pjax' => '0',
        'title' => 'Descarga la lista con los filtros y el orden que tengas puestos',
    ]) ?>
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
            [
                'attribute' => 'fecha_reserva',
                'label' => 'Fecha del evento',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->fecha_reserva ? date('d/m/Y', strtotime($model->fecha_reserva)) : '';
                },
                'filter' => '<div class="filtro-fechas">'
                    . '<label>Desde</label>'
                    . Html::activeInput('date', $searchModel, 'fecha_desde', ['class' => 'form-control input-sm', 'title' => 'Eventos desde esta fecha'])
                    . '<label>Hasta</label>'
                    . Html::activeInput('date', $searchModel, 'fecha_hasta', ['class' => 'form-control input-sm', 'title' => 'Eventos hasta esta fecha'])
                    . '</div>',
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            'nombre',
            'telefono',
            [
                'attribute' => 'sucursal',
                'filter' => \app\models\PresupuestoForm::SUCURSALES,
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Todas'],
                'headerOptions' => ['style' => 'min-width:130px'],
            ],
            [
                'attribute' => 'tipoevento',
                'label' => 'Tipo de evento',
                'filter' => \app\models\PresupuestoForm::TIPOS_EVENTO,
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Todos'],
                'headerOptions' => ['style' => 'min-width:130px'],
                'value' => function ($model) {
                    return \app\models\PresupuestoForm::TIPOS_EVENTO[$model->tipoevento] ?? $model->tipoevento;
                },
            ],
                                'limite_invitados',



            // 'pais',
            // 'ciudad',
           //'email:email',
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
                'template' => '<div class="operaciones-reserva">{estado}{opciones}</div>',
                'buttons' => [
                    // Estado: siempre a la vista (activar / desactivar)
                    'estado' => function ($url, $model, $key) {
                        $activa = (int)$model->estado === 1;
                        return Html::a('<i class="toggle fa fa-check"></i>', "#", [
                            'class' => 'btn btn-sm btn-' . ($activa ? 'success2' : 'default'),
                            'onclick' => "cambiarEstado('{$url}'); return false;",
                            'title' => $activa ? 'Activa (clic para desactivar)' : 'Inactiva (clic para activar)',
                        ]);
                    },

                    // El resto, en un menú desplegable "Opciones"
                    'opciones' => function ($url, $model, $key) {
                        $id = (int)$model->idreservas;
                        $esNueva = \app\models\ReservaCotizacion::find()->where(['idreservas' => $id])->exists();
                        $item = function ($icono, $texto, $ruta, $extra = []) {
                            return '<li>' . Html::a('<i class="fa ' . $icono . ' fa-fw"></i> ' . $texto, $ruta, array_merge(['data-pjax' => '0'], $extra)) . '</li>';
                        };

                        $items = [];
                        if ($esNueva) {
                            $items[] = $item('fa-eye', 'Ver reserva', ['reserva-pasos/ver', 'id' => $id]);
                            $items[] = '<li class="divider"></li>';
                            $items[] = $item('fa-calculator', 'Presupuesto', ['reserva-pasos/presupuesto', 'id' => $id]);
                            $items[] = $item('fa-money', 'Pagos', ['reserva-pasos/pagos', 'id' => $id]);
                            $items[] = $item('fa-list-alt', 'Detalles', ['reserva-pasos/detalles', 'id' => $id]);
                            $items[] = $item('fa-clock-o', 'Cronograma', ['reserva-pasos/cronograma', 'id' => $id]);
                            $items[] = $item('fa-users', 'Lista de invitados', ['reservas/invitados', 'idreservas' => $id]);
                            $items[] = $item('fa-key', 'Cambiar contraseña', ['reserva-pasos/contrasena', 'id' => $id]);
                            $items[] = $item('fa-file-pdf-o', 'PDF completo', ['reserva-pasos/pdf', 'id' => $id, 'tipo' => 'completo'], ['target' => '_blank']);
                        } else {
                            // Reservas viejas (formulario anterior)
                            $items[] = $item('fa-eye', 'Ver', ['reservas/view', 'id' => $id]);
                            $items[] = $item('fa-users', 'Lista de invitados', ['reservas/invitados', 'idreservas' => $id]);
                            $items[] = $item('fa-key', 'Cambiar contraseña', ['reservas/update2', 'id' => $id]);
                        }
                        $items[] = '<li class="divider"></li>';
                        $items[] = '<li>' . Html::a('<i class="fa fa-trash fa-fw"></i> Eliminar', ['reservas/delete', 'id' => $id], [
                            'class' => 'text-danger',
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => '¿Seguro que querés eliminar esta reserva? No se puede deshacer.',
                                'method' => 'post',
                            ],
                        ]) . '</li>';

                        return '<div class="btn-group">'
                            . '<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                            . 'Opciones <span class="caret"></span></button>'
                            . '<ul class="dropdown-menu dropdown-menu-right">' . implode('', $items) . '</ul>'
                            . '</div>';
                    },
                ],
                'contentOptions' => ['class' => 'col-operaciones', 'style' => 'white-space:nowrap']
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
// El listado filtra sin recargar la página (Pjax): al hacer clic se toman los filtros que haya en ese momento
$this->registerJs("$('#btn-excel-reservas').on('click', function () { this.href = this.href.split('?')[0] + window.location.search; });");
// Menú "Opciones": que no lo corte la tabla con scroll
$this->registerCss('.operaciones-reserva { display:flex; gap:4px; align-items:center; } .operaciones-reserva .dropdown-menu > li > a { padding:5px 15px; } @media (min-width:768px) { #table .table-responsive { overflow:visible; } }');
$this->registerCss('.filtro-fechas label { display:block; font-size:10px; font-weight:normal; color:#888; margin:0; text-transform:uppercase; } .filtro-fechas input { margin-bottom:3px; padding:2px 4px; font-size:12px; height:26px; }');

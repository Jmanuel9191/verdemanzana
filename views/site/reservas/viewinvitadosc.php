<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Reservas;
use app\models\Invitados;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvitadosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $idreservas int */

$this->title = 'MI EVENTO';
$this->params['breadcrumbs'][] = $this->title;

// Obtener la reserva actual
$reserva = Reservas::findOne($idreservas);

// Calcular fecha límite
$fechaLimite = date('Y-m-d', strtotime($reserva->fecha_reserva . ' -1 day'));
$hoy = date('Y-m-d');

// Verificar si aún se puede modificar
$puedeModificar = $hoy <= $fechaLimite;

// Verificar si se puede agregar (fecha válida y no se alcanzó el límite)
$cantidadInvitados = Invitados::find()->where(['idreservas' => $idreservas])->count();
$limiteInvitados = (int) $reserva->limite_invitados;
$puedeAgregar = $puedeModificar && ($cantidadInvitados < $limiteInvitados);
?>

<?php if (Yii::$app->session->hasFlash('invitadoCreado')): ?>
    <div id="flash-invitado-creado"></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('invitadoEditado')): ?>
    <div id="flash-invitado-editado"></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('invitadoEliminado')): ?>
    <div id="flash-invitado-eliminado"></div>
<?php endif; ?>


    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="margin-top: 30px; margin-bottom: 40px">

                <div class="box-header">

                    <?php if ($puedeAgregar): ?>
                        <?= Html::button('Agregar Invitado', [
                            'class' => 'btn btn-success abrir-modal-invitado',
                            'data-url' => \yii\helpers\Url::to(['createinvitadoc', 'idreservas' => $idreservas])
                        ]) ?>



                    <?php else: ?>
                        <div class="alert alert-warning" style="margin-top:10px;">
                            <?= $puedeModificar ? 'Ya alcanzaste el límite de invitados.' : 'Ya no es posible modificar la lista de invitados. Se cerró el plazo.' ?>
                        </div>
                    <?php endif; ?>
                    <p><strong>Invitados registrados:</strong> <?= $cantidadInvitados ?> / <?= $limiteInvitados ?></p>

                </div>

                <div class="box-body">
                    <?php Pjax::begin(['id' => 'tabla-invitados']); ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'apellido',
                                'nombre',
                                'telefono',
                                'nro_mesa',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Operaciones',
                                    'template' => '{updateinv} {delete}',
                                    'buttons' => [
                                        'updateinv' => function ($url, $model) use ($puedeModificar) {
                                            if (!$puedeModificar) return '';
                                            return Html::a('<i class="fa fa-pencil"></i>', ['updateinv', 'id' => $model->idinvitado], [
                                                'class' => 'btn btn-success editar-modal-invitado',
                                                'title' => 'Editar',
                                            ]);
                                        },


                                        'delete' => function ($url, $model) use ($puedeModificar) {
                                            if (!$puedeModificar) return '';
                                            return Html::a('<i class="fa fa-trash"></i>', ['deleteinvitado', 'id' => $model->idinvitado], [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => '¿Está seguro de eliminar este invitado?',
                                                    'method' => 'post',
                                                ],
                                                'title' => 'Eliminar',
                                            ]);
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

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modal-invitado',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
]);
echo '<div id="modal-contenido-invitado"></div>';
\yii\bootstrap\Modal::end();


?>

<?php
$js = <<<JS
$(document).ready(function () {
    if (window.location.hash === '#invitados') {
        $('.nav-tabs a[href="#invitados"]').tab('show');
    }

    $('.abrir-modal-invitado').click(function () {
    let url = $(this).data('url');
    $('#modal-invitado').modal('show')
        .find('#modal-contenido-invitado')
        .load(url);
});
    
    $('.editar-modal-invitado').click(function (e) {
    e.preventDefault();
    let url = $(this).attr('href');
    $('#modal-invitado').modal('show')
        .find('#modal-contenido-invitado')
        .load(url);
});

    if ($('#flash-invitado-editado').length) {
    Swal.fire({
        title: 'Invitado editado correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#28a745'
    });
}
if ($('#flash-invitado-eliminado').length) {
    Swal.fire({
        title: 'Invitado eliminado correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#d33' // rojo o el color que prefieras
    });
}

    // Solo si hay flash, mostrar el SweetAlert
    if ($('#flash-invitado-creado').length) {
        Swal.fire({
            title: 'Invitado añadido correctamente',
            icon: 'success',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#28a745'
        });
    }
});


JS;

$this->registerJs($js);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['depends' => [\yii\web\JqueryAsset::class]]);
?>


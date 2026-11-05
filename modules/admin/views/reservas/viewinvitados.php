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

$this->title = 'Lista de invitados';
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

<section class="content-header">
    <h1><?= Html::encode($this->title) ?> - <?= Html::encode($reserva->nombre) ?> </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <p><strong>Invitados registrados:</strong> <?= $cantidadInvitados ?> / <?= $limiteInvitados ?></p>

                    <?php if ($puedeAgregar): ?>
                        <?= Html::a('Agregar Invitado', ['createinvitado', 'idreservas' => $idreservas], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <div class="alert alert-warning" style="margin-top:10px;">
                            <?= $puedeModificar ? 'Ya alcanzaste el límite de invitados.' : 'Ya no es posible modificar la lista de invitados. Se cerró el plazo.' ?>
                        </div>
                    <?php endif; ?>

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
                                    'template' => '<div class="btn-group btn-group-justified" role="group">{updateinv} {delete}</div>',
                                    'buttons' => [
                                        'updateinv' => function ($url, $model) use ($puedeModificar) {
                                            if (!$puedeModificar) return '';
                                            return Html::a('<i class="fa fa-pencil"></i>', ['updateinv', 'id' => $model->idinvitado], ['class' => 'btn btn-success', 'title' => 'Editar']);
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
</section>

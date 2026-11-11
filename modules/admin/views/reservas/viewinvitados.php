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
/* @var $distribucion string|null  imagen de distribución (nombre de archivo) */

$distribucion = $distribucion ?? \app\components\DistribucionInvitados::buscar((int)$idreservas);
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
  <?= Html::a('<i class="fa fa-arrow-left"></i> Volver a ver reserva', ['view', 'id' => $idreservas], ['class' => 'btn btn-default']) ?>
                   
                    <?php if ($puedeAgregar): ?>
                        <?= Html::a('Agregar Invitado', ['createinvitado', 'idreservas' => $idreservas], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?= Html::a('<i class="fa fa-file-pdf-o"></i> Descargar PDF (Apellido)', ['invitados-pdf', 'idreservas' => $idreservas, 'orden' => 'apellido'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
                    <?= Html::a('<i class="fa fa-file-pdf-o"></i> Descargar PDF (# mesa)', ['invitados-pdf', 'idreservas' => $idreservas, 'orden' => 'mesa'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>

                    <?php if (!$puedeAgregar): ?>
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

                    <!-- ===== Distribución de la lista de invitados (imagen) ===== -->
                    <hr>
                    <h4 style="color:#527f1a"><strong>Distribución de la lista de invitados</strong></h4>

                    <?php if ($distribucion): ?>
                        <p>
                            <a href="<?= Html::encode(\app\components\DistribucionInvitados::url($distribucion)) ?>" target="_blank">
                                <img src="<?= Html::encode(\app\components\DistribucionInvitados::url($distribucion)) ?>"
                                     alt="Distribución" style="max-width:320px; max-height:240px; border:1px solid #ddd; border-radius:4px;">
                            </a>
                        </p>
                    <?php else: ?>
                        <p class="text-muted">Todavía no se cargó la distribución.</p>
                    <?php endif; ?>

                    <?= Html::beginForm(['subir-distribucion', 'idreservas' => $idreservas], 'post', ['enctype' => 'multipart/form-data', 'class' => 'form-inline']) ?>
                        <div class="form-group">
                            <input type="file" name="distribucion" accept="image/jpeg,image/png,image/webp" required>
                        </div>
                        <?= Html::submitButton('<i class="fa fa-upload"></i> ' . ($distribucion ? 'Cambiar distribución' : 'Subir distribución'), ['class' => 'btn btn-primary']) ?>
                        <p class="help-block">Una imagen (JPG, PNG o WEBP), hasta 15 MB. Aparece también en el PDF.</p>
                    <?= Html::endForm() ?>
                </div>

            </div>
        </div>
    </div>
</section>

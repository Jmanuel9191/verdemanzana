<?php

use yii\helpers\Html;
use app\models\Reservas;

/* @var $this yii\web\View */
/* @var $model app\models\Invitados */

$reserva = Reservas::findOne($model->idreservas);

$this->title = 'Actualizar invitado – ' . ($reserva->nombre);
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = [
    'label' => 'Lista de invitados',
    'url' => ['invitados', 'idreservas' => $model->idreservas]
];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= $this->render('_forminvitado', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</section>

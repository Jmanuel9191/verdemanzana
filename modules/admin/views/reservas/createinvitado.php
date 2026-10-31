<?php
use app\models\Reservas;

use yii\helpers\Html;
$reserva = \app\models\Reservas::findOne($model->idreservas);

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Añadir invitado';
$this->params['breadcrumbs'][] = ['label' => 'Invitados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>




<section class="content-header">
    <h1><?= Html::encode($this->title) ?> - <?= Html::encode($reserva->nombre) ?> </h1>
</section>

<!-- Main content -->
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

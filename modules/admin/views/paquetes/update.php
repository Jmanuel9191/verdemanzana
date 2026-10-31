<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Paquetes */

$this->title = 'Editar paquete: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Paquetes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->idpaquete]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="callout callout-info" style="margin-bottom:15px">
                Los cambios se aplican a las reservas <b>nuevas</b>. Las reservas ya guardadas mantienen el precio y detalle con que se cargaron.
            </div>
            <div class="box">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</section>

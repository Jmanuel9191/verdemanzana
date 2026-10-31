<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Actualizar Cronograma: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->idreservas]];
$this->params['breadcrumbs'][] = 'Update';
?>




<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo
    Html::a('<i class="fa fa-arrow-left"></i> Volver al crronograma', ['reservas/viewcronograma', 'id' => $model->idreservas], ['class' => 'btn btn-default']) ?>
    <br>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">


                <?php
                if ($model->tipoevento == 'matrimonio') {
                    echo $this->render('_form_boda', ['model' => $model, 'detalles' => $detalles]);
                } else {
                    echo $this->render('_form_quince', ['model' => $model, 'detalles' => $detalles]);
                }
                ?>
            </div>
        </div>
    </div>
</section>

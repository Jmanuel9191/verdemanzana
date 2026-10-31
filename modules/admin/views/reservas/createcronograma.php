<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Crear Cronograma de:' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Cronograma', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                $tipo = strtolower(trim($model->tipoevento));
                if ($tipo === 'matrimonio') {
                    echo $this->render('_form_boda', ['model' => $model]);
                } else {
                    echo $this->render('_form_quince', ['model' => $model]);
                }
                ?>
            </div>
        </div>
    </div>
</section>

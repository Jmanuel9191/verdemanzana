<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Productos */

$this->title = 'Usuario: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Accesos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idadmin, 'url' => ['view', 'id' => $model->idadmin]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= $this->render('_form2', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</section>

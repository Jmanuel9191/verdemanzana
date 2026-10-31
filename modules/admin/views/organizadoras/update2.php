<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Productos */

$this->title = 'Oganizadora: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Oganizadoras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idorganizadora, 'url' => ['view', 'id' => $model->idorganizadora]];
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
                <?= $this->render('_form3', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</section>

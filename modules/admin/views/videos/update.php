<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Videos */

$this->title = 'Update Videos: ' . $model->idvideo;
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idvideo, 'url' => ['view', 'id' => $model->idvideo]];
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

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</section>
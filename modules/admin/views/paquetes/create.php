<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Paquetes */

$this->title = 'Nuevo paquete';
$this->params['breadcrumbs'][] = ['label' => 'Paquetes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</section>

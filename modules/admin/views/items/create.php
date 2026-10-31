<?php

use app\models\Items;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $tipo string|null */

$this->title = $tipo === Items::TIPO_PROVEEDOR ? 'Nuevo proveedor' : 'Nuevo ítem';
$this->params['breadcrumbs'][] = ['label' => 'Ítems / Proveedores', 'url' => ['index', 'ItemsSearch[tipo]' => $tipo ?: Items::TIPO_ITEM]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= $this->render('_form', ['model' => $model, 'tipo' => $tipo]) ?>
            </div>
        </div>
    </div>
</section>

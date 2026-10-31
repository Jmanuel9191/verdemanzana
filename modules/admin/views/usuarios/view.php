<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->idusuario;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <?= Html::a('Borrar', ['delete', 'id' => $model->idusuario], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'esta reguro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

                <div class="box-body ">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'nombre',
                            'apellido',
                            'direccion',
                            'telefono',
                            'pais',
                            'ciudad',
                            'email:email',
                            'contrasena',
                            'fecha_nacimiento',
                            'movil',
                            'fecha_registro',
                            //'estado',
                            'sexo',
                            //'activo',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
</section>

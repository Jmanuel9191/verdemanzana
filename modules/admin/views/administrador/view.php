<?php
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
$dias = date("Y").'-'.date("m").'-'.date("d");
$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Administrador accesos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .btn-secondary {
        color: #fff!important;
        background-color: #6c757d;
        border-color: #6c757d; padding: 10px;
    }

    .btn-primary {
        color: #fff!important;
        background-color: #007bff;
        border-color: #007bff; padding: 10px;
    }
    .btn-info {
        color: #fff!important;
        background-color: #17a2b8;
        border-color: #17a2b8;  padding: 10px;
    }
    .btn-danger {
        background-color: #dd4b39;
        border-color: #d73925; padding: 10px;
    }
    .btn-dark {
        color: #fff!important;
        background-color: #343a40;
        border-color: #343a40; padding: 10px;
    }
</style>






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
                    <?= Html::a('Cambiar Contraseña', ['update2' , 'id' => $model->idadmin], ['class' => 'btn btn-primary']) ?>

                    <?= Html::a('Editar', ['update', 'id' => $model->idadmin], ['class' => 'btn btn-secondary']) ?>
                   

                    <?= Html::a('Borrar', ['delete', 'id' => $model->idadmin], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'esta reguro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

                <div class="box-body ">


                    
                    
                    <h3>Datos Generales </h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'nombre',
                            'mail',
                            'usuario',

                        ],
                    ]) ?>


            

          







                        



                </div>
            </div>
        </div>
</section>

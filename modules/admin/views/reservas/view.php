<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$dia = date("d", strtotime($model['fecha_reserva']));
$mes = date("m", strtotime($model['fecha_reserva']));
$ano = date("Y", strtotime($model['fecha_reserva']));

$cronograma = \app\models\CronogramaBoda::findOne(['idreserva' => $model->idreservas]);

$url = $cronograma ? ['updatecronograma', 'id' => $model->idreservas] : ['createcronograma', 'id' => $model->idreservas];
$texto = $cronograma ? 'Editar Cronograma' : 'Crear Cronograma';

/**
 * Enlace de descarga solo si el archivo existe realmente en el servidor.
 * Si el campo está vacío o el archivo no está en /imagen/usuarios, muestra un aviso.
 */
$linkArchivo = function ($archivo, $texto) {
    $rutaFisica = Yii::getAlias('@webroot') . '/imagen/usuarios/' . $archivo;
    if (!empty($archivo) && is_file($rutaFisica)) {
        return Html::a(
            $texto,
            Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($archivo),
            ['download' => true, 'target' => '_blank']
        );
    }
    return '<span style="color: red;">No se cargó ningún archivo</span>';
};

$urlmans = ($model->tipoevento == 'matrimonio')
    ? ['form-campos-extra', 'idreserva' => $model->idreservas]
    : ['form-campos-extra-quince', 'idreserva' => $model->idreservas];
?>
<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <style>
        .btn-secondary {
            color: #fff!important;
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 10px;
        }
        .btn-primary {
            color: #fff!important;
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px;
        }
        .btn-danger {
            background-color: #dd4b39;
            border-color: #d73925;
            padding: 10px;
        }
    </style>

</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <?= Html::a('Cambiar Contraseña', ['update2' , 'id' => $model->idreservas], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Editar datos generales', ['update', 'id' => $model->idreservas], ['class' => 'btn btn-secondary']) ?>

                    <?= Html::a($texto, $url, ['class' => 'btn btn-secondary']) ?>


                    <?= Html::a('Lista de invitados', ['invitados', 'idreservas' => $model->idreservas], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('VER GUIA(anterior)', ['viewguia', 'id' => $model->idreservas], ['class' => 'btn btn-primary']) ?>


                    <?= Html::a('➕ Añadir u Ordenar Campos', $urlmans, [
                        'class' => 'btn btn-secondary'
                    ]) ?>

                    <?= Html::a('Borrar', ['delete', 'id' => $model->idreservas], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'esta reguro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

                <div class="box-body btnslinks">

                    <h3>Presupuesto</h3>

                    <?=  Html::a('Ver o Subir imagenes <i class="fa fa-upload" aria-hidden="true"></i>',
                    Url::to(['galeriapresupuesto', 'id' => $model->idreservas]),
                    ['target' => '_blank']
                    );  ?>


                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'presupuesto',
                            'monto_canceladop',
                            'saldo_pagarp',

                            [
                                'label' => 'Descargar Presupuesto',
                                'format' => 'raw',
                                'value' => $linkArchivo($model->presupuestofile, 'Descargar Presupuesto'),
                            ],

                        ],
                    ]) ?>

                    <h3>Servicios adicionales</h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'servicios_adicionales',
                            'monto_cancelados',
                            'saldo_pagars',


                        ],
                    ]) ?>


                    <h3>Datos generales del evento</h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => 'Organizadora',
                                'value' => $model->organizadora?->nombre ?? 'Sin organizadora asignada',
                            ],

                            [  'label' => 'Comision de la organizadora',
                                'value' =>  $model->comision_organizadora.' Bs '
                            ],

                            //'idusuario',
                           [  'label' => 'Fecha de Evento',
                               'value' =>   $dia. ' de '.  $meses2[$mes * 1]. ' , '.$ano ],

                            'sucursal',
                            'tipoevento',
                            'tipo_evento',
                            'hora_inicio',
                            'nombre',
                            'telefono',
                            'email',
                            'paquete_reservar',
                            'cant_garzones',
                            'limite_invitados',


                        ],
                    ]) ?>


                    <h3>EQUIPAMIENTO </h3>
                    <?=  Html::a('Ver o Subir imagenes <i class="fa fa-upload" aria-hidden="true"></i>',
                        Url::to(['galeriaequipamiento', 'id' => $model->idreservas]),
                        ['target' => '_blank']
                    );  ?>


                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [


                            'color_sillas',
                            'color_mantel',
                            'color_servilleta',
                            'en_mesa',

                            [
                                'attribute' => 'tipo_bebidas',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->tipo_bebidas);
                                }
                            ],
                            [
                                'attribute' => 'vajillasyvasisenmesa',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->vajillasyvasisenmesa);
                                }
                            ],
                            [
                                'attribute' => 'katering',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->katering);
                                }
                            ],


                        ],
                    ]) ?>











                    <h3>DECORACIÓN</h3>

                    <?=  Html::a('Ver o Subir imagenes <i class="fa fa-upload" aria-hidden="true"></i>',
                        Url::to(['galeriadecoracionus', 'id' => $model->idreservas]),
                        ['target' => '_blank']
                    );  ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'nombre_decoradora',

                            [
                                'attribute' => 'detalle_decoradora',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->detalle_decoradora);
                                }
                            ],
                            [
                                'attribute' => 'detalle_decoracioniglesia',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->detalle_decoracioniglesia);
                                }
                            ],
                            [
                                'attribute' => 'otros_detalles',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->otros_detalles);
                                }
                            ],






                        ],
                    ]) ?>



                    <h3>COORDINACIÓN</h3>

                    <?=  Html::a('Ver o Subir imagenes <i class="fa fa-upload" aria-hidden="true"></i>',
                        Url::to(['galeriacoordinacion', 'id' => $model->idreservas]),
                        ['target' => '_blank']
                    );  ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'nombre_cordinadora',
                            'nombre_subcordinadora',
                            'nombre_supervisora',

                            [
                                'attribute' => 'otrainfocoordinacion',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->otrainfocoordinacion);
                                }
                            ],

                            // ✅ Enlace para descargar el archivo de cronograma
                            [
                                'label' => 'Descargar Cronograma',
                                'format' => 'raw',
                                'value' => $linkArchivo($model->cronogramafile, 'Descargar Cronograma'),
                            ],

                            // ✅ Enlace para descargar el archivo de lista de invitados
                            [
                                'label' => 'Descargar Lista de Invitados',
                                'format' => 'raw',
                                'value' => $linkArchivo($model->listainvitadosfile, 'Descargar Lista de Invitados'),
                            ],
                        ],
                    ]) ?>



                    <h3>COMENTARIOS DEL CLIENTE</h3>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',


                            [
                                'attribute' => 'comentario_decoracion',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->comentario_decoracion);
                                }
                            ],
                            [
                                'attribute' => 'comentario_cronograma',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return strip_tags($model->comentario_cronograma);
                                }
                            ],

                        ],
                    ]) ?>


                    <div class="row">
                                      <div class="col-xs-12">
                        <h3>Galeria de Decoracion Cliente </h3>
                        <?php foreach ($model->galeriadecoracion as $imagen): ?>
                            <div class="col-md-3">

                                <a class="image-popup" href="<?= Yii::getAlias('@web') . '/imagen/reservasdecoracion/' . $imagen->archivo ?>" style="cursor:pointer">
                                    <img src="<?= Yii::getAlias('@web') . '/imagen/reservasdecoracion/' . $imagen->archivo ?>" class="img-responsive">
                                </a>


                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-xs-12">
                        <h3>Galeria de cronograma Cliente </h3>
                        <?php foreach ($model->galeriacronograma as $imagen2): ?>
                            <div class="col-md-3">

                                <a class="image-popup" href="<?= Yii::getAlias('@web') . '/imagen/reservascronograma/' . $imagen2->archivo ?>" style="cursor:pointer">
                                    <img src="<?= Yii::getAlias('@web') . '/imagen/reservascronograma/' . $imagen2->archivo ?>" class="img-responsive">
                                </a>


                            </div>
                        <?php endforeach; ?>
                    </div>
</div>


                </div>
            </div>
        </div>
</section>
<style>
    .btnslinks a {
        background: #69a322;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-bottom: 10px;
        font-weight: 600;
    }
</style>


<?php
$script = <<<JS

$(".image-popup").magnificPopup({
    type: "image",
    closeOnContentClick: false,
    removalDelay: 600,
    mainClass: "my-mfp-slide-bottom",
    image: {
        verticalFit: true
    },
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0, 1],
        tPrev: 'Anterior',
        tNext: 'Siguiente',
        tCounter: '%curr% de %total%',
        arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>' // Define las flechas
    }
});





JS;
$this->registerJs($script, \yii\web\View::POS_END);
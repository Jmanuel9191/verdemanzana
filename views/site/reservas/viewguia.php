<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$dia = date("d", strtotime($model['fecha_boda']));
$mes = date("m", strtotime($model['fecha_boda']));
$ano = date("Y", strtotime($model['fecha_boda']));

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
                    <?= Html::a('Datos guia', ['updateguia', 'id' => $model->idreservas], ['class' => 'btn btn-secondary']) ?>
                    <?= Html::a('Borrar', ['delete', 'id' => $model->idreservas], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'esta reguro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

                <div class="box-body btnslinks">

                    <h3>Datos generales</h3>


                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                                'tipoevento',
                            //'idusuario',

                            [  'label' => 'Fecha de Boda',
                                'value' =>   $dia. ' de '.  $meses2[$mes * 1]. ' , '.$ano ],
                            'nombrenovios',
                            'salondeeventos',
                            'paqueteeventos',
                            'horainvitacionevento',
                            'iglesia',
                            'direccioniglesia',
                            'horaiglesia',
                            'musica',
                            'horasmusicas',
                            'maestradeceremonia',
                            'recomendacionsalon',
                            'recomendacioniglesia',
                            'recomendaciondj',
                            'coordinadorar',
                            'aproxcoordinadora',
                            'degustacionbarra',
                            'aproxbarra',
                            'desgustaciontorta',
                            'aproxtorta',
                            'degustacioncomida',
                            'aproxcomida',
                            'decoradorar',
                            'aproxdecoradora',
                            'celebranter',
                            'aproxcelebrante',

                        ],
                    ]) ?>

                    <h3>Ceremonia Religiosa</h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'horaceremonia',
                            'ccordinacions',
                            'cdecoracion',
                            'censamblajemusical',
                            'cbouquet',
                            'ctransporte',
                            'caccesorios',
                            'csalidasesposos',
                            'cessionfotos',
                            'crecmcoodinacion',
                            'crecmdecoracion',
                            'crecmensamblaje',
                            'csugsaludasesposo'


                        ],
                    ]) ?>


                    <h3>Ceremonia Civil</h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'horacivil',
                            'oficialregistrocivil',
                            'tipodeceremoniaciv',
                            'testigoscv',
                            'cvensabmlemusical',
                            'cvefectosespec',
                            'ccprecoctel',
                            'recmregistrocv',
                            'recmensambcivil',
                            'recmefectos',
                            'detallesimpcv',


                        ],
                    ]) ?>


                    <h3>Bebidas </h3>



                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'cerveza',
                            'sodas',
                            'agua',
                            'otrosbebidas',
                            'barracocteles',
                            'horadeapertura',
                            'recmbebidas',
                            'recmbarradecocteles',



                        ],
                    ]) ?>



                    <h3>Campos faltantes de Bebidas </h3>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'dulcebienvenida',
                            'mesadulces',
                            'mesabocaditos',
                            'maquetatortafalsa',
                            'detallesimportantesbebidas',



                        ],
                    ]) ?>








                    <h3>Ingreso de novios, vals y brindis (Ingreso Quinceañera)</h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'coreografo',
                            'maestoceremoniadet',
                            'bailedeingreso',
                            'baileprincip',
                            'brindis',
                            'baileespecial',
                            'efectosespeciales',
                            'recmcoreografo',
                            'recmmaestrocemonia',
                            'recmefectosd',
                            'horaingresonovios',
                            'detallesimpingresonovis'




                        ],
                    ]) ?>
                    <h3> Campos faltantes de ingreso para 15 años</h3>



                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'ingresoin',
                            'experienciasin',




                        ],
                    ]) ?>




                    <h3>Cena, fiesta y torta</h3>



                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'horacena',
                            'cenac',
                            'tortac',
                            'bocaditosc',
                            'dulcesc',
                            'horalocac',
                            'recmcena',
                            'recmtorta',
                            'recmbocaditos',
                            'recmdul',
                            'recmhoraloca',
                            'mariachis',
                            'otrosdetallesfiestas',





                        ],
                    ]) ?>



                    <h3>Otros proveedores o (Antes de la fiesta) </h3>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'invitaciones',
                            'fotografia',
                            'video',
                            'vestidodenovia',
                            'salondebelleza',
                            'trajenovio',
                            'glitterbar',
                            'espejomagico',
                            'video360',
                            'memoriphone',
                            'recmfotoyvideo',
                            'recmvestidonovia',
                            'recmsalonbelleza',
                            'recmtrajenovio',
                            'recmgiiterbar',
                            'recmemori',
                            'detallesimpoprov',




                        ],
                    ]) ?>


                    <h3> Campos faltantes para Antes de la fiesta de quinceaños</h3>



                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'limosna',
                            'horantesfiesta',




                        ],
                    ]) ?>




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


<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
//$horass=date("H:i",strtotime($model['fecha_noticia']));

$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$dia = date("d", strtotime($model['fecha_reserva']));
$mes = date("m", strtotime($model['fecha_reserva']));
$ano = date("Y", strtotime($model['fecha_reserva']));
$id="";
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);
$this->registerjsFile('@web/assets_b/js/plugins.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]);


@$nombre = $_GET['id'];

$user2 = Yii::$app->user2->identity;

$categorias5 = \app\models\Organizadoras::findOne(['idorganizadora' => $model['idorganizadora']]);

$script = <<<CSS


 .galers{
     background: #69a322;
    color: white;
    padding: 6px 11px;
 }

.btn-primary {
    color: #fff;
    background-color: #69a322;
    border-color: #69a322;
}
CSS;


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
//$this->registerCssFile('@web/web/plugin/galeria/thumbrigth/css/examples.css', ['depends'=>\app\assets\AppAsset::class, 'media' => 'screen','']);
$configuracion = \app\models\Configuracion::find()->one();
$configuracion['titulo_pagina']= $model['nombre'];



?>

<?= $this->render('../banners/fijo',
    ['titulo' => 'Ficha del evento / '.Yii::$app->user2->identity->nombre]
)

?>


<?php if (Yii::$app->session->hasFlash('invitadoCreado')): ?>
    <?php
    $this->registerJs("
            $(document).ready(function() {
                $('.nav-tabs a[href=\"#invitados\"]').tab('show');
                Swal.fire({
                    title: 'Invitado añadido correctamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#28a745'
                });
            });
        ");
    ?>
<?php endif; ?>



<div class="container">

    <div class="">
        <div class="">
            <div class="col-xs-12">
                <h2 class="comi"><a href="<?= Url::to(['logout2']) ?>" style="color: #6ba622;
    text-decoration: underline;
    font-size: 18px;
    font-weight: 600;"> CERRAR SESSION </a> </h2> <br>
            </div>


            <div class="panel">
                <div class="tabsalones">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active">
                            <a href="#ficha" aria-controls="one" role="tab" data-toggle="tab">Ficha del evento<span class="moremsa">Ver más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span></a>
                        </li>
                        <li role="presentation">
                            <a href="#guia" aria-controls="two" role="tab" data-toggle="tab">Guia del evento <span class="moremsa">Ver más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span></a>
                        </li>
                        <li role="presentation">
                            <a href="#invitados" aria-controls="two" role="tab" data-toggle="tab">Lista de invitados<span class="moremsa">Ver más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span></a>
                        </li>
                    </ul>
                </div>


                <div class="panel-body" style="padding: 5px">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active repnpes" id="ficha">

                            <div class="col-xs-12 ficjhatec">
                            <h2 class="txt-h2 text-left">Ficha del evento</h2>
                                <div class="separador-titulo left-block"></div>
                            </div>

                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-xs-12">  <h3> <strong> PRESUPUESTO: </strong> </h3>        </div>

                            <div class="col-md-7 col-xs-12">

                                <p><strong>  PRESUPUESTO PAQUETE: </strong> <?= $model['presupuesto'] ?></p>
                                <p><strong> MONTO CANCELADO: </strong> <?= $model['monto_canceladop'] ?></p>
                                <p><strong> SALDO A PAGAR:  </strong> <?= $model['saldo_pagarp'] ?></p>

                                <p class="despreus">
                                    <?php  if ($model->presupuestofile) {  ?>
                                        <?=  Html::a('Descargar Presupuesto <i class="fa-solid fa-download"></i>',
                                            Yii::getAlias('@web') . '/imagen/usuarios/' . $model->presupuestofile,
                                            ['download' => true, 'target' => '_blank']);  ?>
                                    <?php     } else {   ?>

                                        <span style="color: red;"></span>
                                    <?php      }   ?>

                                </p>


                                <h3 style="margin-top: 5px"> <strong>  SERVICIOS ADICIONALES: </strong> </h3>
                                <p><strong>  </strong> <?= $model['servicios_adicionales'] ?></p>
                                <p><strong> MONTO CANCELADO:  </strong> <?= $model['monto_cancelados'] ?></p>
                                <p><strong> SALDO POR PAGAR: </strong> <?= $model['saldo_pagars'] ?></p>

                            </div>
                                <div class="col-md-5 col-xs-12">

                                    <div class="  gallery galese ficha" >

                                        <?php if (!empty($model->galeriapresupuesto)): ?>
                                            <?php foreach($model->galeriapresupuesto as $key => $item): ?>
                                                <a  class="image-popup"  href="<?= Url::to("@web/imagen/galeriapresupuesto/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                                    <img src="<?= Url::to("@web/imagen/galeriapresupuesto/{$item['archivo']}") ?>" class="img-responsive">
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>





                                    </div>
                                </div>


                            </div>


                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>


                            <div class="col-xs-12" style="padding: 0">

                                <div class="col-xs-12">  <h3> <strong>  DATOS GENERALES DEL EVENTO: </strong> </h3>        </div>
                                <div class="col-md-7 col-xs-12">

                                    <p><strong> Organizadora Asociada: </strong>   <?= \yii\helpers\Html::encode($categorias5['nombre'] ?? 'Sin asignar') ?></p>
                                    <p><strong>FECHA DE EVENTO: </strong> <?= $dia ?> de  <?= $meses2[$mes * 1] ?>,  <?= $ano ?></p>
                                    <p><strong>SUCURSAL: </strong> <?= $model['sucursal'] ?></p>
                                    <p><strong>TIPO DE EVENTO: </strong> <?= $model['tipo_evento'] ?></p>
                                    <p><strong>HORA DE INICIO: </strong> <?= $model['hora_inicio'] ?></p>
                                </div>

                                <div class="col-md-5 col-xs-12">

                                    <p><strong>NOMBRES: </strong> <?= $model['nombre'] ?></p>
                                    <p><strong>TELEFONO: </strong> <?= $model['telefono'] ?></p>
                                    <p><strong>EMAIL:  </strong> <?= $model['email'] ?></p>
                                    <p><strong>TIPO DE PAQUETE:  </strong> <?= $model['paquete_reservar'] ?></p>
                                    <p><strong>CANTIDAD DE GARZONES:  </strong> <?= $model['cant_garzones'] ?></p>

                                </div>
                            </div>


                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>


                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-xs-12">  <h3> <strong>  EQUIPAMIENTO: </strong> </h3>        </div>


                                <div class="col-md-7 col-xs-12">
                                    <p><strong> COLOR DE SILLAS: </strong> <?= $model['color_sillas'] ?></p>
                                    <p><strong> COLOR DE MANTEL: </strong> <?= $model['color_mantel'] ?></p>
                                    <p><strong>COLOR DE SERVILLETA: </strong> <?= $model['color_servilleta'] ?></p>
                                    <p><strong> EN MESA: </strong> <?= $model['en_mesa'] ?></p>
                                    <p><strong> TIPO DE BEBIDAS: </strong> <?= $model['tipo_bebidas'] ?></p>
                                    <p><strong> VAJILLAS Y VASOS EN MESA: </strong> <?= $model['vajillasyvasisenmesa'] ?></p>
                                    <p><strong>KATERING:  </strong> <?= $model['katering'] ?></p>
                                </div>

                                <div class="col-md-5 col-xs-12">
                                    <div class="  gallery galese ficha" >

<?php if (!empty($model->galeriaequipamiento)): ?>
    <?php foreach($model->galeriaequipamiento as $key => $item): ?>

                                        <a  class="image-popup"  href="<?= Url::to("@web/imagen/galeriaequipamiento/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                            <img src="<?= Url::to("@web/imagen/galeriaequipamiento/{$item['archivo']}") ?>" class="img-responsive">
                                        </a>
                                    <?php endforeach; ?>
<?php endif; ?>


                                    </div>
                                </div>
                            </div>






                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>


                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-xs-12">  <h3> <strong>  DECORACIÓN: </strong> </h3>        </div>

                                <div class="col-md-7 col-xs-12">
                                <p><strong>Nombre decoradora:  </strong> <?= $model['nombre_decoradora'] ?></p>
                                <p><strong> Detalle de la decoración salón: </strong> <?= $model['detalle_decoradora'] ?> </p>
                                <p><strong> Detalle de la decoración iglesia: </strong> <?= $model['detalle_decoracioniglesia'] ?> </p>
                                <p><strong> Otros detalles: </strong> <?= $model['otros_detalles'] ?> </p>

                              </div>

                                <div class="col-md-5 col-xs-12">
                                    <div class="  gallery galese ficha" >
<?php if (!empty($model->galeriadecoracionus)): ?>
    <?php foreach($model->galeriadecoracionus as $key => $item): ?>
                                            <a  class="image-popup"  href="<?= Url::to("@web/imagen/galeriadecoracionus/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                                <img src="<?= Url::to("@web/imagen/galeriadecoracionus/{$item['archivo']}") ?>" class="img-responsive">
                                            </a>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>


                            </div>





                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>


                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-xs-12">  <h3> <strong>  COORDINACIÓN: </strong> </h3>        </div>
                                <div class="col-md-7 col-xs-12">

                                <p><strong> Nombre coordinadora: </strong> <?= $model['nombre_cordinadora'] ?></p>
                                <p><strong> Nombre sub-coordinadora: </strong> <?= $model['nombre_subcordinadora'] ?></p>
                                <p><strong> Nombre supervisora: </strong> <?= $model['nombre_supervisora'] ?></p>
                                <p><strong> Otra información: </strong> <?= $model['otrainfocoordinacion'] ?></p>

                                <p class="despreus">
                                    <?php  if ($model->cronogramafile) {  ?>
                                        <?=  Html::a('Descargar Cronograma <i class="fa-solid fa-download"></i>',
                                            Yii::getAlias('@web') . '/imagen/usuarios/' . $model->cronogramafile,
                                            ['download' => true, 'target' => '_blank']);  ?>
                                    <?php     } else {   ?>

                                        <span style="color: red;"></span>
                                    <?php      }   ?>

                                </p>

                                <p class="despreus">
                                    <?php  if ($model->listainvitadosfile) {  ?>
                                        <?=  Html::a('Descargar Lista de invitados <i class="fa-solid fa-download"></i>',
                                            Yii::getAlias('@web') . '/imagen/usuarios/' . $model->listainvitadosfile,
                                            ['download' => true, 'target' => '_blank']);  ?>
                                    <?php     } else {   ?>

                                        <span style="color: red;"></span>
                                    <?php      }   ?>

                                </p>
                                </div>


                                <div class="col-md-5 col-xs-12">
                                    <div class="  gallery galese ficha" >
<?php if (!empty($model->galeriacoordinacion)): ?>
    <?php foreach($model->galeriacoordinacion as $key => $item): ?>
                                            <a  class="image-popup"  href="<?= Url::to("@web/imagen/galeriacoordinacion/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                                <img src="<?= Url::to("@web/imagen/galeriacoordinacion/{$item['archivo']}") ?>" class="img-responsive">
                                            </a>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>


                            </div>

                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>

                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-xs-12">  <h3> <strong>  OBSERVACIÓN CLIENTE: </strong> </h3>        </div>

                                <div class="col-xs-12">
                                    <p><strong>OBSERVACIONES DEL CLIENTE SOBRE DECORACION: </strong> <?= nl2br(\yii\helpers\Html::encode($model['comentario_decoracion'])) ?></p>
                                    <p><strong> OBSERVACIONES DEL CLIENTE SOBRE CRONOGRAMA:  </strong> <?= nl2br(\yii\helpers\Html::encode($model['comentario_cronograma'])) ?> </p>

                                </div>



                            </div>

                            <div class="col-xs-12"> <hr style="   MARGIN-BOTTOM: 5PX;">
                            </div>


                            <div class="col-xs-12" style="padding: 0">
                                <div class="col-md-6 col-xs-12">
                                    <h3> <strong>  IMAGENES CLIENTES DECORACIÓN: </strong> </h3>

                                    <div class="  gallery galese ficha" >
<?php if (!empty($model->galeriadecoracion)): ?>
    <?php foreach($model->galeriadecoracion as $key => $item): ?>
                                            <a  class="image-popup"  href="<?= Url::to("@web/imagen/reservasdecoracion/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                                <img src="<?= Url::to("@web/imagen/reservasdecoracion/{$item['archivo']}") ?>" class="img-responsive">
                                            </a>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>

                                    <div class="col-xs-12" style="padding: 0">
                                        <br>
                                        <a href="<?= Url::to(['galeriadecoracion?id='.$model['idreservas']]) ?>" class="galers">Agregar imagenes </a>
                                        <br>
                                        <br>
                                    </div>
                                </div>



                                <div class="col-md-6 col-xs-12">
                                    <h3> <strong>  IMAGENES CLIENTES CRONOGRAMA: </strong> </h3>
                                    <div class="  gallery galese ficha" >
<?php if (!empty($model->galeriacronograma)): ?>
    <?php foreach($model->galeriacronograma as $key => $item): ?>
                                            <a  class="image-popup"  href="<?= Url::to("@web/imagen/reservascronograma/{$item['archivo']}") ?>" style="cursor:pointer; border: 1px solid #69a322;">
                                                <img src="<?= Url::to("@web/imagen/reservascronograma/{$item['archivo']}") ?>" class="img-responsive">
                                            </a>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>

                                    <div class="col-xs-12" style="padding: 0">
                                        <br>
                                        <a href="<?= Url::to(['galeriacronograma?id='.$model['idreservas']]) ?>" class="galers">Agregar imagenes </a>
                                        <br>
                                        <br>
                                    </div>

                                </div>
                            </div>




                            <div class="col-xs-12"><br>
                                <?php $form = ActiveForm::begin([
                                    'action' => ['site/comentarios'],
                                    'id' => 'login-form',
                                    /*'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-8\">{error}</div>",
                                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                                    ],*/
                                    'options' => ['enctype' => 'multipart/form-data']
                                ]); ?>

                                <div class="menuform">
                                    <div class="row">

                                        <div class="col-md-6 col-xs-12">
                                            <label for="">Agrega observacion de decoracion<span>*</span></label>
                                            <?= $form->field($model, 'comentario_decoracion')->label(false)->textarea(['rows' => 12, 'cols'=> 20, 'class'=>'form-control ','placeholder'=>'Dime tu observacion:']) ?>

                                        </div>


                                        <div class="col-md-6 col-xs-12">
                                            <label for="">Agrega observacion de cronograma<span>*</span></label>
                                            <?= $form->field($model, 'comentario_cronograma')->label(false)->textarea(['rows' => 12, 'cols'=> 20, 'class'=>'form-control ','placeholder'=>'Dime tu observacion:']) ?>

                                        </div>
                                    </div>
                                </div>




                                <div class="col-xs-12">
                                    <?= Html::submitButton('Enviar observaciones', ['class' => 'btnregister btn btn-primary center-block']) ?>
                                    <br><br>
                                </div>



                                <?php ActiveForm::end(); ?>

                            </div>


                        </div>







                        <!-- FICHA DEL EVENTO... -->



                       <div role="tabpanel" class="tab-pane fade in" id="guia">


                            <?php echo $this->render('guiadecoracion', ['model' => $model]);  ?>

                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="invitados">


                            <?= $this->render('viewinvitados', [
                                'searchModel' => new \app\models\InvitadosSearch(['idreservas' => $model->idreservas]),
                                'dataProvider' => (new \app\models\InvitadosSearch(['idreservas' => $model->idreservas]))->search(Yii::$app->request->queryParams),
                                'idreservas' => $model->idreservas,
                            ]) ?>



                        </div>


                    </div>

                </div>

            </div>





        </div>



    </div>

</div>




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

 
$(".popup-gallery").magnificPopup({
delegate: "a",
type: "image",
fixedContentPos: true,
fixedBgPos: true,
tLoading: "Loading image #%curr%...",
removalDelay: 600,
closeBtnInside: true,
zoom: {
enabled: true,
duration: 700
},
gallery: {
enabled: true,
navigateByImgClick: true,
preload: [ 0, 1 ]
},
image: {
}
});

JS;
$this->registerJs($script, \yii\web\View::POS_READY);

?>

<style>
    ul.nav.navbar-nav > li > a:hover,
    ul.nav.navbar-nav > li > a:focus {
        background-color: transparent !important;
    }
</style>

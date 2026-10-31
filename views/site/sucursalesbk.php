

<?php

/* @var $this yii\web\View */

use yii\helpers\Url;


$script = <<<CSS

CSS;
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);
$this->registerjsFile('@web/assets_b/js/plugins.js', ['depends' => \app\assets_b\AppAsset::class, 'position' => \yii\web\View::POS_END]);


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
$timepo =   date("Y") . "-" . date("m") . "-" . date("d");
$eventos1 = \app\models\Reservas::find()->where(['sucursal' => 'Doble via a la guardia', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos2 = \app\models\Reservas::find()->where(['sucursal' => 'Hilanderia', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos3 = \app\models\Reservas::find()->where(['sucursal' => 'Parque Urbano', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos4 = \app\models\Reservas::find()->where(['sucursal' => 'Campestre', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();

?>












<?= $this->render('banners/fijo',
    ['titulo' => 'Salones',])

?>

<div class="wrapper ">

    <div class="clear"></div>

    <!--QUIENES SOMOS-->
    <div class="bgnovedades1 colplomo">
        <div class="bgnovedades2"></div>
        <div class="container">


            <br>
            <h2 class="txt-h2 text-center">Nuestros salones</h2>
            <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
            <br>
            <br>
            <div class="row">

                <div class="container">

                    <div class="panel">
                        <div class="tabsalones">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active">
                                    <a href="#one" aria-controls="one" role="tab" data-toggle="tab">Doble via a la Guardia <span class="moremsa">Leer más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#two" aria-controls="two" role="tab" data-toggle="tab">Parque Urbano <span class="moremsa">Leer más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span></a>
                                </li>
                                <li role="presentation">
                                    <a href="#three" aria-controls="three" role="tab" data-toggle="tab">Garden <span class="moremsa">Leer más <i class="fa fa-plus fa-lg txt-rosa" aria-hidden="true"></i> </span> </a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="one">

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="two">

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="three">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-md-4">
                    <h2 class="acc_trigger ">
                        <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',1)"><span
                                    class="tit_acor" style="color: #527f1a;">Doble via a la guardia</span></a></h2>

                                            <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/doblevia.jpg') ?>" class="img-responsive" alt="">
                                          
                                                <button class="btn btn-block  btn-info" data-toggle="modal" data-target="#salon1"  type="button">Recorrido Virtual</button>


                        <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',1)" style="    border: 1px solid #527f1a;
    padding: 5px 12px;
    color: white;
    width: 100% !important;
    display: block;
    background: #527f1a;
    text-align: center;
    border-radius: 5px;">Mostrar Ubicacíon</a>

                                           
                                            

                    <br>
                    
                    <div class="acc_container">
                        <div class="row">
                            <div class="col-xs-12  mov6 tam14">
                                <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 120 - 350 personas<br>
                                <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Doble via a la Guardia entre 5° y 6° anillo #5195<br>
                                <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Todo tipo de evento<br>
                                <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 75594713  - 3263303<br>
                                <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                <div class="tam14 colrojo"><br>
                                    <strong> Horario de atención</strong><br>
                                    09:00 – 12:30<br>
                                    15:00 – 19:00
                                </div><br>



                            </div>

                            <div class="col-xs-12" style="    margin-bottom: 40px;">

                                <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                <?php if ($eventos1 ) : ?>
                                <?php  foreach ($eventos1 as $item): ?>



                                    <div class="col-sm-8" style="padding-left: 0">
                                        <?=$item['nombre']?>
                                    </div>

                                    <div class="col-sm-4" style="padding-right: 0">
                                        <?=$item['fecha_reserva']?>
                                    </div>



                                    <?php
                                endforeach; ?>

                                <?php else: ?>

                                    <p style="color: #9E9E9E">Sin eventos programados para hoy</p>
                                <?php endIf?>


                            </div>
                            <br><br>

                            
                             
                        <div class="col-xs-12">
                            <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',1)" style="
                              border: 1px solid #527f1a;  padding: 5px 12px;  color: #527f1a;width:100% !important;">Mostrar Ubicacíon</a>
                        </div>
                       

                        <div id="salon1" class="modal fade" role="dialog">

                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">

                                    <div class="modal-header btn-info ">
                                        <h4 class="modal-title">Salón Doble Vía La Guardia</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="box-body" style="height:80vh;">
                                            <iframe style="border:0;height:100%;width:100%" src="https://www.paneek.net/#/tour/view/6964" frameborder="0" allowfullscreen></iframe>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>

                                    </div>


                                </div>

                            </div>

                        </div>
                        
                       

                         


                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h2 class="acc_trigger "><a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',2)"><span
                                    class="tit_acor" style="color: #527f1a;" >Hilanderia</span></a></h2>


                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/hilanderia.jpg') ?>" class="img-responsive" alt="">
                     <button class="btn btn-block btn-info" data-toggle="modal" data-target="#salon2"  type="button">Recorrido Virtual</button>

                    <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',2)" style="    border: 1px solid #527f1a;
    padding: 5px 12px;
    color: white;
    width: 100% !important;
    display: block;
    background: #527f1a;
    text-align: center;
    border-radius: 5px;">Mostrar Ubicacíon</a>



                    <br>
                    <div class="acc_container">
                        <div class="row">
                            <div class="col-xs-12  mov6 tam14">
                                <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 50 - 150 personas<br>
                                <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Av. Hilanderia entre 4° y 5° anillo<br>
                                <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Todo tipo de evento<br>
                                <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 7801948 - 3271417<br>
                                <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                <div class="tam14 colrojo"><br>
                                    <strong> Horario de atención</strong><br>
                                    09:00 – 19:00
                                </div><br>



                            </div>

                            <div class="col-xs-12" style="    margin-bottom: 40px;">

                                <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                <?php if ($eventos2) : ?>

                                <?php  foreach ($eventos2 as $item2): ?>



                                        <div class="col-sm-8" style="padding-left: 0">
                                    <?=$item2['nombre']?>
                                    </div>

                                    <div class="col-sm-4" style="padding-right: 0">
                                        <?=$item2['fecha_reserva']?>
                                    </div>


                                    <?php
                                endforeach; ?>


                                <?php else: ?>

                                    <p style="color: #9E9E9E">Sin eventos programados para hoy</p>
                                <?php endIf?>


                            </div>
                            <br><br>


                            <div class="col-xs-12">
                                <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',2)" style="  border: 1px solid #527f1a;  padding: 5px 12px;  color: #527f1a;">Mostrar Ubicacíon</a>
                            </div>
                            
                             <div id="salon2" class="modal fade" role="dialog">

                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">

                                    <div class="modal-header btn-info ">
                                        <h4 class="modal-title">Salón Hilandería</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="box-body" style="height:80vh;">
                                            <iframe style="border:0;height:100%;width:100%" src="https://www.paneek.net/#/tour/view/6965" frameborder="0" allowfullscreen></iframe>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>

                                    </div>


                                </div>

                            </div>

                        </div>


                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <h2 class="acc_trigger "><a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',3)"><span
                                    class="tit_acor" style="color: #527f1a;">Parque Urbano</span></a></h2>


                   <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/parqueurbano.jpg') ?>" class="img-responsive" alt="">
                   <button class="btn btn-block btn-info" data-toggle="modal" data-target="#salon3"  type="button">Recorrido Virtual</button>

                    <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',3)" style="    border: 1px solid #527f1a;
    padding: 5px 12px;
    color: white;
    width: 100% !important;
    display: block;
    background: #527f1a;
    text-align: center;
    border-radius: 5px;">Mostrar Ubicacíon</a>


                    <br>
                    <div class="acc_container">
                        <div class="row">
                            <div class="col-xs-12  mov6 tam14">
                                <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 80 - 250 personas<br>
                                <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Parque Urbano / Av. Capitan Arrien entre 1er y 2do anillo <br>
                                <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Infantil - Baby shower<br>
                                <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 75594713 <br>
                                <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                <div class="tam14 colrojo"><br>
                                    <strong> Horario de atención</strong><br>
                                    09:00 – 12:30<br>
                                    15:00 – 19:00
                                </div><br>



                            </div>

                            <div class="col-xs-12" style="    margin-bottom: 40px;">

                                <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                <?php if ($eventos3) : ?>

                                <?php  foreach ($eventos3 as $item3): ?>


                                        <div class="col-sm-8" style="padding-left: 0">
                                    <?=$item3['nombre']?>
                                    </div>

                                    <div class="col-sm-4" style="padding-right: 0">
                                        <?=$item3['fecha_reserva']?>
                                    </div>


                                    <?php
                                endforeach; ?>

                                <?php else: ?>

                                    <p style="color: #9E9E9E">Sin eventos programados para hoy</p>
                                <?php endIf?>


                            </div>
                            <br><br>


                            <div class="col-xs-12">
                                <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',3)" style="  border: 1px solid #527f1a;  padding: 5px 12px;  color: #527f1a;">Mostrar Ubicacíon</a>
                            </div>

                             <div id="salon3" class="modal fade" role="dialog">

                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">

                                    <div class="modal-header btn-info ">
                                        <h4 class="modal-title">Salón Hilandería</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="box-body" style="height:80vh;">
                                            <iframe style="border:0;height:100%;width:100%" src="https://www.paneek.net/#/tour/view/6966" frameborder="0" allowfullscreen></iframe>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>

                                    </div>


                                </div>

                            </div>

                        </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-md-offset-4" style="margin-top:20px">
                    <h2 class="acc_trigger "><a  ><span
                                    class="tit_acor" style="color: #527f1a;">Campestre</span></a></h2>


                   <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/verdemanzana.jpg') ?>" class="img-responsive" alt="">
                    <button class="btn btn-block btn-info" data-toggle="modal" data-target="#salon4"  type="button">Recorrido Virtual</button>



                    <br>
                    <div class="acc_container">
                        <div class="row">
                            <div class="col-xs-12  mov6 tam14">
                                <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 120 - 700 personas<br>
                                <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Av. paraí entre 5° y 6° anillo<br>
                                <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Todo tipo de evento<br>
                                <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 75594713 - 3263303 <br>
                                <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                <div class="tam14 colrojo"><br>
                                    <strong> Horario de atención</strong><br>
                                    Cita previa.<br>
                                    
                                </div><br>



                            </div>

                            <div class="col-xs-12" style="    margin-bottom: 40px;">

                                <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                <?php if ($eventos4) : ?>

                                <?php  foreach ($eventos4 as $item4): ?>


                                        <div class="col-sm-8" style="padding-left: 0">
                                    <?=$item4['nombre']?>
                                    </div>

                                    <div class="col-sm-4" style="padding-right: 0">
                                        <?=$item4['fecha_reserva']?>
                                    </div>


                                    <?php
                                endforeach; ?>

                                <?php else: ?>

                                    <p style="color: #9E9E9E">Sin eventos programados para hoy</p>
                                <?php endIf?>


                            </div>
                            <br><br>


                           <!-- <div class="col-xs-12">
                                <a href="#" onclick="showLocation('<?= Url::to(['site/mapa']) ?>',3)" style="  border: 1px solid #527f1a;  padding: 5px 12px;  color: #527f1a;">Mostrar Ubicacíon</a>
                            </div>-->
                            <div id="salon4" class="modal fade" role="dialog">

                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">

                                    <div class="modal-header btn-info ">
                                        <h4 class="modal-title">Salón Campestre</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="box-body" style="height:80vh;">
                                            <iframe style="border:0;height:100%;width:100%" src="https://www.paneek.net/#/tour/view/6976" frameborder="0" allowfullscreen></iframe>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>

                                    </div>


                                </div>

                            </div>

                        </div>


                        </div>
                    </div>
                </div>


            </div>
            
            
            <div class="container">
        <div class="row">
        <div class="gallery col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1 class="gallery-title">Galería</h1>
        </div>




            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via1.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via1.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila1.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila1.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque1.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque1.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via2.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via2.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila2.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila2.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque2.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque2.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via3.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via3.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila3.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila3.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque3.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque3.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            
             <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via4.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via4.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila4.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila4.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque4.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque4.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via5.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via5.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila5.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila5.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque5.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque5.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter campe">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/campe1.jpeg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/campe1.jpeg') ?>" class="img-responsive">
                </a>
            </div>
            
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via6.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via6.JPG') ?>" class="img-responsive">
                </a>
            </div>

           <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila6.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila6.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque6.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque6.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter campe">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/campe2.jpeg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/campe2.jpeg') ?>" class="img-responsive">
                </a>
            </div>
            
           
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter campe">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/campe4.jpeg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/campe4.jpeg') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter campe">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/campe5.jpeg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/campe5.jpeg') ?>" class="img-responsive">
                </a>
            </div>
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter campe">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/campe6.jpeg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/campe6.jpeg') ?>" class="img-responsive">
                </a>
            </div>
            
            
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via7.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via7.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via8.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via8.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via9.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via9.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via10.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via10.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via11.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via11.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via12.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via12.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via13.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via13.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via14.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via14.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via15.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via15.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via16.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via16.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter via">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/via17.jpg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/via17.jpg') ?>" class="img-responsive">
                </a>
            </div>
            
            
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila7.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila7.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila8.jpg') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila8.jpg') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila9.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila9.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila10.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila10.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila11.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila11.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila12.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila13.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hila">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/hila13.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/hila13.JPG') ?>" class="img-responsive">
                </a>
            </div>
            
            
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque7.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque7.JPG') ?>" class="img-responsive">
                </a>
            </div>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter parque">
                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/images/parque8.JPG') ?>" style="cursor:pointer">
                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/images/parque8.JPG') ?>" class="img-responsive">
                </a>
            </div>

 
        </div>
    </div>
            
            


            <div class="clear"></div>
            <br>
            <div class="lineanove"></div>
            <div class="row">

            </div>
        </div>
        <div class="bgnovedades3"></div>
    </div>
    <!--FIN QUIENES SOMOS-->

    <!--REGISTRAR-->

    <!--FIN REGISTRAR-->

</div>
<div class="clear"></div>
<style>
   .gallery-title
{
    font-size: 36px;
    color: #42B32F;
    text-align: center;
    font-weight: 500;
    margin-bottom: 70px;
}
.gallery-title:after {
    content: "";
    position: absolute;
    width: 7.5%;
    left: 46.5%;
    height: 45px;
    border-bottom: 1px solid #5e5e5e;
}
.filter-button
{
    font-size: 18px;
    border: 1px solid #42B32F;
    border-radius: 5px;
    text-align: center;
    color: #42B32F;
    margin-bottom: 30px;

}
.filter-button:hover
{
    font-size: 18px;
    border: 1px solid #42B32F;
    border-radius: 5px;
    text-align: center;
    color: #ffffff;
    background-color: #42B32F;

}
.btn-default:active .filter-button:active
{
    background-color: #42B32F;
    color: white;
}

.port-image
{
    width: 100%;
}

.gallery_product
{
    margin-bottom: 30px;
} 
    
</style>

<?php
$script = <<<JS

$(document).ready(function(){

    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "all")
        {
            //$('.filter').removeClass('hidden');
            $('.filter').show('1000');
        }
        else
        {
//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });
    
    if ($(".filter-button").removeClass("active")) {
$(this).removeClass("active");
}
$(this).addClass("active");

});




 $(".image-popup").magnificPopup({
type: "image",
closeOnContentClick: false,
removalDelay: 600,
mainClass: "my-mfp-slide-bottom",
image: {
verticalFit: false
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
tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
}
});


JS;
$this->registerJs($script, \yii\web\View::POS_END);
echo $this->render('widgets/geolocation');
?>


sty
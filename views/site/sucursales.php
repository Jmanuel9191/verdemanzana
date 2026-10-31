

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
$eventos2 = \app\models\Reservas::find()->where(['sucursal' => 'Garden', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos3 = \app\models\Reservas::find()->where(['sucursal' => 'Parque Urbano', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
//$eventos4 = \app\models\Reservas::find()->where(['sucursal' => 'Campestre', 'estado' => '1'])->andWhere(['=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();

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

                                    <div class="">
                                        <h2 class="acc_trigger salntitlo ">
                                            Doble via a la guardia
                                        </h2>

                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salondoblevia22.jpg') ?>" class="img-responsive" style="margin: 0 auto"  alt="">
                                        <br>

                                        <div class="acc_container">
                                            <div class="row">
                                                <div class="col-xs-12  mov6 tam14 " style="padding: 0;">
                                                    <div class="col-sm-6 linsni25">
                                                        <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 100 - 350 personas<br>
                                                        <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Doble via a la Guardia entre 5° y 6° anillo #5195<br>
                                                        <!-- <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Todo tipo de evento<br> -->
                                                        <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 77094165<br>
                                                        <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                                        <div class="tam14 colrojo">
                                                            <strong> Horario de atención</strong><br>
                                                            Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                                            Sab:    9:00 a 13:00  <br>
                                                            <strong>Hacer cita previa</strong>
                                                        </div><br>

                                                    </div>
                                                    <div class="col-sm-6 linsni25">
                                                    <span class="colrojo"> <strong>Características:&nbsp;</strong></span>
                                                    <ul style="padding-left: 20px">
                                                        <li>    Salón con aire acondicionado </li>
                                                        <li>  Parque Privado </li>
                                                        <li>   Vestidor con aire acondicionado</li>
                                                        <li>    Baños equipados </li>
                                                        <li> Wi Fi, cámaras de seguridad </li>
                                                        <li>  Mesón para bar</li>

                                                        <li>  Tarima para música</li>
                                                        <li> Jardines con árboles luminosos</li>
                                                        <li>Techo decorado con lluvia de luces</li>
                                                        <li>Pilares con estructuras decoradas</li>
                                                        <li>Fondo con cielo estrellado</li>
                                                    </ul>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12" style="    margin-bottom: 20px;">

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



                                                <div class="col-xs-12" style=" padding: 0 ">
                                                    <div class="col-xs-12 col-md-6 linsni25">
                                                        <strong> <h4> <strong>Ubicación </strong></h4>  </strong>
                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5371.799866307942!2d-63.22001017050174!3d-17.81817946952749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1c28894c23075%3A0x8907e1b672f10fb4!2sSalon%20Verde%20Manzana!5e0!3m2!1ses-419!2sbo!4v1738971970032!5m2!1ses-419!2sbo" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>  </div>

                                                    <div class="col-xs-12 col-md-6 linsni25 ">
                                                        <strong> <h4> <strong>Galeria </strong></h4>  </strong>
                                                        <div class="  gallery galese" >
                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db1.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db1.jpg') ?>" class="img-responsive">
                                                            </a>
                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db2.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db2.jpg') ?>" class="img-responsive">
                                                            </a>



                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db3.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db3.jpg') ?>" class="img-responsive">
                                                            </a>



                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db4.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db4.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db5.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db5.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db6.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db6.jpg') ?>" class="img-responsive">
                                                            </a>



                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db7.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db7.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db8.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db8.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db9.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db9.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db10.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db10.jpg') ?>" class="img-responsive">
                                                            </a>



                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/db11.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/db11.jpg') ?>" class="img-responsive">
                                                            </a>

                                                        </div>



                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="two">

                                    <div class="">
                                        <h2 class="acc_trigger salntitlo ">Parque Urbano</h2>

                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salonparqueurbano.jpg') ?>" class="img-responsive" alt="">


                                        <br>
                                        <div class="acc_container">
                                            <div class="row">
                                                <div class="col-xs-12  mov6 tam14 " style="padding: 0;">

                                                    <div class="col-sm-6 linsni25">
                                                        <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 80 - 200 personas<br>
                                                        <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Parque Urbano / Av. Capitan Arrien entre 1er y 2do anillo

                                                        <br>
                                                        <!-- <span class="colrojo"> <strong>Eventos:&nbsp;</strong></span> Infantil - Baby shower<br> -->
                                                        <span class="colrojo"> <strong>Teléfono:&nbsp;</strong></span> 77094165 <br>
                                                        <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                                        <div class="tam14 colrojo">
                                                            <strong> Horario de atención</strong><br>
                                                            Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                                            Sab:    9:00 a 13:00  <br>
                                                            <strong>Hacer cita previa</strong>
                                                        </div><br>
                                                    </div>



                                                        <div class="col-sm-6 linsni25">
                                                            <span class="colrojo"> <strong>Características:&nbsp;</strong></span>
                                                            <ul style="padding-left: 20px">
                                                                <li>    Salón con aire acondicionado </li>
                                                                <li>   Vestidor con aire acondicionado</li>
                                                                <li>    Baños equipados </li>
                                                                <li> Wi Fi, cámaras de seguridad </li>

                                                                <li> Jardines exterior con focos bombilla</li>
                                                                <li>Techo decorado con lluvia de luces</li>
                                                                <li>Fondo con cielo estrellado</li>
                                                            </ul>


                                                        </div>

                                                </div>

                                                <div class="col-xs-12" style="    margin-bottom: 20px;">

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

                                                <div class="col-xs-12" style=" padding: 0 ">
                                                    <div class="col-xs-12 col-md-6 linsni25">
                                                        <strong> <h4> <strong>Ubicación </strong></h4>  </strong>
                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6389.0846165897865!2d-63.175679872194614!3d-17.79299222677066!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1e86977ab76b7%3A0x3a241271550d3e3a!2sVerde%20Manzana%20Parque%20Urbano!5e0!3m2!1ses-419!2sbo!4v1738971902084!5m2!1ses-419!2sbo" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                    </div>

                                                    <div class="col-xs-12 col-md-6 linsni25 ">
                                                        <strong> <h4> <strong>Galeria </strong></h4>  </strong>
                                                        <div class="  gallery galese" >
                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq1.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq1.jpg') ?>" class="img-responsive">
                                                            </a>
                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq2.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq2.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq3.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq3.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq4.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq4.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq5.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq5.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq6.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq6.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq7.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq7.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq8.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq8.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq9.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq9.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq10.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq10.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq11.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq11.jpg') ?>" class="img-responsive">
                                                            </a>

                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq12.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq12.jpg') ?>" class="img-responsive">
                                                            </a>


                                                            <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/pq13.jpg') ?>" style="cursor:pointer">
                                                                <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/pq13.jpg') ?>" class="img-responsive">
                                                            </a>
                                                        </div>



                                                    </div>

                                                </div>



                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="three">


                                    <div class="">
                                        <h2 class="acc_trigger salntitlo ">Garden</h2>


                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salongarden.jpg') ?>" class="img-responsive" alt="">

                                        <br>
                                        <div class="acc_container">
                                            <div class="row">
                                                <div class="col-xs-12  mov6 tam14 " style="padding: 0;">
                                                    <div class="col-sm-6 linsni25">
                                                    <span class="colrojo"> <strong>Capacidad:&nbsp;</strong></span> 50 - 130 personas<br>
                                                    <span class="colrojo"> <strong>Dirección:&nbsp;</strong></span> Av. Esmeralda esq. Onix zona Doble Vía La Guardia<br>
                                                    <span class="colrojo"> <strong>Teléfonos:&nbsp;</strong></span> 77094165<br>
                                                    <span class="colrojo"> <strong>Email:&nbsp;</strong></span> reservas@verdemanzana.com.bo
                                                        <div class="tam14 colrojo">
                                                            <strong> Horario de atención</strong><br>
                                                            Lun a Vier:   09:00 a 12:30 - 15:00 a 19:00 <br>
                                                            Sab:    9:00 a 13:00  <br>
                                                            <strong>Hacer cita previa</strong>
                                                        </div><br>
                                                    </div>

                                                    <div class="col-sm-6 linsni25">
                                                        <span class="colrojo"> <strong>Características:&nbsp;</strong></span>
                                                        <ul style="padding-left: 20px">
                                                            <li>    Salón al aire libre estilo colonial</li>
                                                            <li>   Vestidor con aire acondicionado</li>
                                                            <li>    Baños equipados </li>
                                                            <li> Wi Fi, cámaras de seguridad </li>

                                                            <li> Amplios jardines</li>
                                                            <li>Decoración con luces bombillas</li>
                                                            <li>Fondo con cielo estrellado</li>
                                                        </ul>

                                                    </div>

                                                </div>

                                                <div class="col-xs-12" style="    margin-bottom: 20px;">

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

                                                <div class="col-xs-12" style=" padding: 0 ">
                                                    <div class="col-sm-6 linsni25">
                                                        <strong> <h4> <strong>Ubicación </strong></h4>  </strong>
                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7596.96260986434!2d-63.22061692469236!3d-17.81605846725068!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1c3001d09bbbd%3A0x3c7f51f21d40e8fb!2sSal%C3%B3n%20Verde%20Manzana%20Garden!5e0!3m2!1ses-419!2sbo!4v1738971379915!5m2!1ses-419!2sbo" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                                                    </div>



                                                        <div class="col-xs-12 col-md-6 linsni25 ">
                                                            <strong> <h4> <strong>Galeria </strong></h4>  </strong>
                                                            <div class="  gallery galese" >
                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g1.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g1.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g2.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g2.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g3.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g3.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g4.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g4.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g5.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g5.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g6.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g6.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g7.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g7.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g8.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g8.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g9.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g9.jpg') ?>" class="img-responsive">
                                                                </a>

                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g10.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g10.jpg') ?>" class="img-responsive">
                                                                </a>


                                                                <a class="image-popup" href="<?= \yii\helpers\Url::to('@web/assets_b/img/g11.jpg') ?>" style="cursor:pointer">
                                                                    <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/g11.jpg') ?>" class="img-responsive">
                                                                </a>

                                                            </div>



                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
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
$this->registerJs($script, \yii\web\View::POS_END);
echo $this->render('widgets/geolocation');
?>



<?php
use yii\helpers\Url;
use app\models\FormTextosEstaticos;

$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$dia = date("d", strtotime($model['fecha_reserva']));
$mes = date("m", strtotime($model['fecha_reserva']));
$ano = date("Y", strtotime($model['fecha_reserva']));
$reco1 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '1'])
    ->andWhere(['visible' => 1])
    ->one();
$reco2 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '2'])
    ->andWhere(['visible' => 1])
    ->one();
$reco3 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '3'])
    ->andWhere(['visible' => 1])
    ->one();
$reco4 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '4'])
    ->andWhere(['visible' => 1])
    ->one();
$reco5 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '1'])
    ->andWhere(['visible' => 1])
    ->one();
$reco6 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '2'])
    ->andWhere(['visible' => 1])
    ->one();
$reco7 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '3'])
    ->andWhere(['visible' => 1])
    ->one();
$reco8 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '4'])
    ->andWhere(['visible' => 1])
    ->one();

?>

<div class="detalledecoracion">
    <div class="">
        <div class="row">



            <div class="col-xs-12" style="padding: 0">


                <img src="<?= Url::to([$model['tipoevento'] == 'quinceanos' ? 'assets_b/img/florquince.png' : 'assets_b/img/flores.png']) ?>" class="flower-left">


                <div class="col-md-4 col-xs-12"></div>
                <div class="col-md-4 col-xs-12">
                    <h2>GUIA</h2>
                    <h1> Experiencia de  <?=$model['tipoevento'] == 'quinceanos' ? '15 <span class="anosnoeo">años</span> ' : 'Boda' ?>  <br> <span> Verde Manzana </span> </h1>
                    <p class="subriiles" <?=$model['tipoevento'] == 'quinceanos' ? 'style="padding-left: 5px"' : '' ?>>Fecha de  <?=$model['tipoevento'] == 'quinceanos' ? 'tus 15 años ' : 'tu boda' ?> : <?= $dia. ' de '.  $meses2[$mes * 1]. ', '.$ano  ?>
                        <br>
                        Nombre <?=$model['tipoevento'] == 'quinceanos' ? 'quinceañera' : 'novios' ?> :  <?= $model['nombrenovios'] ?>

                    </p>
                </div>

                <div class="col-md-4 col-xs-12"></div>

                <img src="<?= Url::to([$model['tipoevento'] == 'quinceanos' ? 'assets_b/img/florquince.png' : 'assets_b/img/flores.png']) ?>" class="flower-right">


            </div>





            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-4 col-xs-12"></div>
            <div class="col-md-4 col-xs-12"> <div class="dashed-line"></div> </div>
                <div class="col-md-4 col-xs-12"></div>
            </div>





            <div class="col-xs-12" style="padding: 0">
            <div class="col-md-2 col-xs-12"></div>
            <div class="col-xs-12 col-md-8" >

                <div class="col-xs-12 genearlesp" style="padding: 0">


                <?php if (($model['tipoevento'] == 'matrimonio')): ?>
                    <p class="recoemandacikl" style="margin-top: 10px; font-size: 17px; padding-left: 0">
                        <?= $reco1->contenido_html ?>
                    </p>
                <?php endif; ?>

                    <?php if (($model['tipoevento'] == 'quinceanos')): ?>
                        <p class="recoemandacikl" style="margin-top: 10px; font-size: 17px; padding-left: 0">
                            <?= $reco5->contenido_html ?>
                        </p>
                    <?php endif; ?>

                </div>


            </div>

            <div class="col-md-2 col-xs-12"></div>

            </div>



            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-2 col-xs-12"></div>

            <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-xs-6 col-xs-12 psubi">
                        <p><strong>Salón de eventos: </strong> <?= $model['salondeeventos'] ?></p>
                        <p><strong>Paquete: </strong> <?= $model['paqueteeventos'] ?></p>
                        <p><strong>Hora invitación: </strong> <?= $model['horainvitacionevento'] ?></p>
                    </div>
                    <div class="col-xs-6 precome col-xs-12">
                        <p><strong>Recomendación: </strong>
                            <br>
                            <span> <?= $model['recomendacionsalon'] ?> </span>
                        </p>

                        <img src="<?= Url::to(['assets_b/img/salon.png'])?>" class=" imgsce"  alt="">

                    </div>


            </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>



            <?php if ($model['tipoevento'] == 'quinceanos'): ?>
            <?php else: ?>
            <div class="col-xs-12" style="padding: 0; margin-top: 10px">
                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-xs-6 col-xs-12 psubi">
                        <p><strong>Iglesia: </strong> <?= $model['iglesia'] ?></p>
                        <p><strong>Dirección: </strong> <?= $model['direccioniglesia'] ?></p>
                        <p><strong>Hora: </strong> <?= $model['horaiglesia'] ?></p>
                    </div>
                    <div class="col-xs-6 precome col-xs-12">
                        <p><strong>Recomendación: </strong>
                            <br>
                            <span> <?= $model['recomendacioniglesia'] ?> </span>
                        </p>

                        <img src="<?= Url::to(['assets_b/img/iglesia.png'])?>" class=" imgsce"  alt="">

                    </div>


                </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>



            <?php endif; ?>













            <div class="col-xs-12" style="padding: 0; margin-top: 10px">
                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-xs-6 col-xs-12 psubi">
                        <p><strong>Música: </strong> <?= $model['musica'] ?></p>
                        <p><strong>Horas: </strong> <?= $model['horasmusicas'] ?></p>
                        <p><strong><?=$model['tipoevento'] == 'quinceanos' ? 'Animador' : 'Maestro de ceremonia' ?>: </strong> <?= $model['maestradeceremonia'] ?></p>
                    </div>
                    <div class="col-xs-6 precome col-xs-12">
                        <p><strong>Recomendación: </strong>
                            <br>
                            <span> <?= $model['recomendaciondj'] ?> </span>
                        </p>

                        <img src="<?= Url::to(['assets_b/img/dj.png'])?>" class=" imgsce"  alt="">

                    </div>


                </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>



            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-4 col-xs-12"></div>
                <div class="col-md-4 col-xs-12"> <div class="dashed-line"></div> </div>
                <div class="col-md-4 col-xs-12"></div>
            </div>




            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12 genearlesp2" style="padding: 0">
                        <?php if (($model['tipoevento'] == 'matrimonio')): ?>
                            <p class="recoemandacikl" style="margin-top: 10px; font-size: 14px!important; padding-left: 0">
                                <?= $reco2->contenido_html ?>
                            </p>
                        <?php endif; ?>

                        <?php if (($model['tipoevento'] == 'quinceanos')): ?>
                            <p class="recoemandacikl" style="margin-top: 10px; font-size: 14px!important; padding-left: 0">
                                <?= $reco6->contenido_html ?>
                            </p>
                        <?php endif; ?>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>






            <div class="col-xs-12" style="padding: 0; margin-top: 10px">
                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8 aproxxlmas" style="text-align: right">


                    <div class="col-xs-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Reunión con celebrante: <?= $model['celebranter'] ?> </strong> </p>
                    </div>

                    <div class="col-sm-5 psubiaprox col-xs-5">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxcelebrante'] ?> </strong> </p>

                    </div>



                    <div class="col-xs-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Reunión decoradora: <?= $model['decoradorar'] ?> </strong> </p>
                    </div>

                    <div class="col-sm-5 psubiaprox col-xs-5">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxdecoradora'] ?> </strong> </p>

                    </div>




                    <div class="col-xs-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Degustación comida: <?= $model['degustacioncomida'] ?> </strong> </p>
                    </div>

                    <div class="col-sm-5 psubiaprox col-xs-5">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxcomida'] ?> </strong> </p>

                    </div>

                    <div class="col-xs-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Degustación torta: <?= $model['desgustaciontorta'] ?> </strong> </p>
                    </div>
                    <div class="col-sm-5 psubiaprox col-xs-5">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxtorta'] ?> </strong> </p>

                    </div>




                    <div class="col-sm-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Degustación barra: <?= $model['degustacionbarra'] ?> </strong> </p>
                    </div>
                    <div class="col-sm-5 psubiaprox col-xs-5">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxbarra'] ?> </strong> </p>

                    </div>



                    <div class="col-xs-7 col-xs-7 psubiaprox">
                        <p><img src="<?= Url::to(['assets_b/img/check.png'])?>" class=" imgsce list"  alt=""> <strong>Reunión coordinadora: <?= $model['coordinadorar'] ?> </strong> </p>
                    </div>
                    <div class="col-sm-5 col-xs-5 psubiaprox">
                        <p class="apxomsom"> <strong>Aprox  <?= $model['aproxcoordinadora'] ?> </strong> </p>

                    </div>




                </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>








            <div class="col-xs-12" style="padding: 0; ">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12 genearlesp2" style="padding: 0">

                        <?php if (($model['tipoevento'] == 'matrimonio')): ?>

                            <p class="recoemandacikl" style="margin-top: 10px; line-height: 20px; font-size: 14px; padding-left: 0">
                                <?= $reco3->contenido_html ?>
                            </p>
                        <?php endif; ?>
                        <?php if (($model['tipoevento'] == 'quinceanos')): ?>

                            <p class="recoemandacikl" style="margin-top: 10px; line-height: 20px; font-size: 14px; padding-left: 0">
                                <?= $reco7->contenido_html ?>
                            </p>
                        <?php endif; ?>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>




            <div class="col-xs-12" style="padding: 0;">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12 recoemandacikl genearlesp2" style="padding: 0">



                        <?php if (($model['tipoevento'] == 'matrimonio')): ?>
                            <p class="recoemandacikl" style=" font-size: 14px; line-height: 20px; padding-left: 0">
                                <?= $reco4->contenido_html ?>
                            </p>
                        <?php endif; ?>


                        <?php if (($model['tipoevento'] == 'quinceanos')): ?>
                            <p class="recoemandacikl" style=" font-size: 14px; line-height: 20px; padding-left: 0">
                                <?= $reco8->contenido_html ?>
                            </p>
                        <?php endif; ?>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>


            <div class="col-xs-12" style="padding: 0;">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong> <?=$model['tipoevento'] == 'quinceanos' ? 'Antes de la fiesta' : 'Ceremonia religiosa' ?>
                                <?php if ($model['tipoevento'] == 'quinceanos'): ?>
                                <?php else: ?>
                                <br>
                                <span style="font-size: 16px">Hora:  <?= $model['horaceremonia'] ?> </span>
                                <?php endif; ?>
                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>









            <div class="col-xs-12" style="padding: 0; margin-top: 10px">
                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <?php if ($model['tipoevento'] == 'quinceanos'): ?>


                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">
                        <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Invitaciones: </strong> <?= $model['invitaciones'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Fotografia: </strong> <?= $model['fotografia'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><strong>Video: </strong> <?= $model['video'] ?>  </p>
                        <p> <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Vestido de quinceañera: </strong>  <?= $model['vestidodenovia'] ?> </p>
                        <p>   <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><strong>Salón de belleza: </strong> <?= $model['salondebelleza'] ?>  </p>
                        <p style="padding-left: 50px">  <strong>Limosna: </strong> <?= $model['limosna'] ?>  </p>
                        <p style="padding-left: 50px">   <strong>Hora: </strong> <?= $model['horantesfiesta'] ?>  </p>
                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: right">
                        <p > &nbsp;&nbsp;&nbsp; <strong>Recomendación foto y video:</strong> <br> <?= $model['recmfotoyvideo'] ?>  </p>
                        <p > &nbsp;&nbsp;&nbsp; <strong>Recomendación salón de belleza:</strong> <br> <?= $model['recmsalonbelleza'] ?>  </p>


                    </div>



                    <?php else: ?>

                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Coordinación: </strong> <?= $model['ccordinacions'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Decoración: </strong> <?= $model['cdecoracion'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Ensamble musical: </strong> <?= $model['censamblajemusical'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Bouquet y botonier: </strong>  <?= $model['cbouquet'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <strong>Transporte: </strong> <?= $model['ctransporte'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Accesorios (aras, alianzas): </strong> <?= $model['caccesorios'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Salida de esposos: </strong> <?= $model['csalidasesposos'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <strong>Sesión de fotos exterior:</strong> <?= $model['cessionfotos'] ?>  </p>
                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: center">
                        <p>  <strong>Recomendación coordinación:</strong> <br> <?= $model['crecmcoodinacion'] ?>  </p>
                        <p>  <strong>Recomendación decoración:</strong> <br> <?= $model['crecmdecoracion'] ?>  </p>
                        <p>  <strong>Recomendación ensamble musical:</strong> <br>  <?= $model['crecmensamblaje'] ?>  </p>
                        <br class="hidden-xs">
                        <p>  <strong>Recomendación salida de esposos:</strong> <br> <?= $model['csugsaludasesposo'] ?>  </p>
                    </div>


                    <?php endif; ?>





                </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>






        </div>
    </div>
</div>


<br>



















<div class="detalledecoracion separte">
    <div class="">
        <div class="row">

            <?php if ($model['tipoevento'] != 'quinceanos'): ?>

            <div class="col-xs-12" style="padding: 0;">
                <img src="<?= Url::to(['assets_b/img/flores.png'])?>" class="flower-left">

                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong>Ceremonia civil
                                <br>
                                <span style="font-size: 16px">Hora:  <?= $model['horacivil'] ?> </span>

                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>
                <img src="<?= Url::to(['assets_b/img/flores.png'])?>" class="flower-right">

            </div>




            <div class="col-xs-12" style="padding: 0; margin-top: 10px">

                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Oficial de registro civil: </strong> <?= $model['oficialregistrocivil'] ?>  </p>
                        <p>   <strong>Tipo de ceremonia: </strong> <?= $model['tipodeceremoniaciv'] ?>  </p>
                        <p>  <strong>Testigos: </strong> <?= $model['testigoscv'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Ensamble musical: </strong>  <?= $model['cvensabmlemusical'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Efectos especiales y pirotecnias: </strong> <?= $model['cvefectosespec'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <strong>PreCoctel: </strong> <?= $model['ccprecoctel'] ?> </p>
                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: center">
                        <p>  <strong>Recomendación registro civil:</strong> <br> <?= $model['recmregistrocv'] ?>  </p>
                        <br class="hidden-xs">
                        <p>  <strong>Recomendación ensamble musical:</strong> <br> <?= $model['recmensambcivil'] ?>  </p>

                        <p>  <strong>Recomendación efectos:</strong> <br>  <?= $model['recmefectos'] ?>  </p>

                    </div>

                    <div class="col-xs-12">
                        <div class="wavy-background">
                            <div class="row">
                                <p class="dtlles">Detalles importantes:</p>
                                <p class="moremors" style="font-size: 16px">

                                    <?= $model['detallesimpcv'] ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-2 col-xs-12"></div>



            </div>


            <?php else: ?>
            <?php endif; ?>


            <div class="col-xs-12" style="padding: 0; margin-top: 20px">

                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong>Bebidas

                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>
            <div class="col-xs-12" style="padding: 0; margin-top: 10px">



                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">
                        <p>   <strong>Cerveza: </strong> <?= $model['cerveza'] ?>  </p>
                        <p>   <strong>Soda: </strong> <?= $model['sodas'] ?>  </p>
                        <p>  <strong>Agua: </strong> <?= $model['agua'] ?>  </p>
                        <p>  <strong>Otros: </strong>  <?= $model['otrosbebidas'] ?> </p>
                        <p>   <strong>Barra de cócteles: </strong> <?= $model['barracocteles'] ?>  </p>
                        <p style="font-size: 13px">   <strong>Hora de apertura: </strong> <?= $model['horadeapertura'] ?> </p>


                        <?php if ($model['tipoevento'] == 'quinceanos'): ?> <br>
                            <p>   <strong>Dulce de bienvenida: </strong> <?= $model['dulcebienvenida'] ?>  </p>
                            <p>   <strong>Mesa de dulces: </strong> <?= $model['mesadulces'] ?>  </p>
                            <p>   <strong>Mesa de bocaditos: </strong> <?= $model['mesabocaditos'] ?>  </p>
                            <p>   <strong>Maqueta torta falsa: </strong> <?= $model['maquetatortafalsa'] ?>  </p>

'                        <?php else: ?>
                        <?php endif; ?>

                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: center">
                        <p>  <strong>Recomendación bebidas:</strong> <br> <?= $model['recmbebidas'] ?>  </p>
                        <br class="hidden-xs">
                        <br class="hidden-xs">
                        <br class="hidden-xs">
                        <p>  <strong>Recomendación barra de cócteles:</strong> <br> <?= $model['recmbarradecocteles'] ?>  </p>


                    </div>


                    <?php if ($model['tipoevento'] == 'quinceanos'): ?>
                    <div class="col-xs-12">
                        <div class="wavy-background">
                            <div class="row">
                                <p class="dtlles">Detalles importantes:</p>
                                <p class="moremors" style="font-size: 16px">

                                    <?= $model['detallesimportantesbebidas'] ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php else: ?>
                    <?php endif; ?>


                </div>
                <div class="col-md-2 col-xs-12"></div>



            </div>









            <div class="col-xs-12" style="padding: 0; margin-top: 20px">

                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong> <?=$model['tipoevento'] == 'quinceanos' ? 'Ingreso de quinceañera' : 'Ingreso de novios, vals y brindis' ?>
                                <br>
                                <span style="font-size: 16px">Hora:  <?= $model['horaingresonovios'] ?> </span>

                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>




            <div class="col-xs-12" style="padding: 0; margin-top: 10px">



                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">

                        <?php if ($model['tipoevento'] == 'quinceanos'): ?>

                            <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>  <strong>Ingreso: </strong> <?= $model['ingresoin'] ?>  </p>
                            <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>  <strong>Música de ingreso: </strong> <?= $model['bailedeingreso'] ?>  </p>

                        <?php else: ?>


                        <?php endif; ?>


                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Coreógrafo: </strong> <?= $model['coreografo'] ?>  </p>



                        <?php if ($model['tipoevento'] == 'quinceanos'): ?>

                            <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>  <strong>Experiencias: </strong> <?= $model['experienciasin'] ?>  </p>

                        <?php else: ?>

                        <?php endif; ?>

                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>  <strong>Maestro de ceremonia: </strong> <?= $model['maestoceremoniadet'] ?>  </p>



                        <?php if ($model['tipoevento'] == 'quinceanos'): ?>
                        <?php else: ?>
                            <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Baile ingreso con canción: </strong> <?= $model['bailedeingreso'] ?>  </p>
                        <?php endif; ?>


                        <p>   <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong><?=$model['tipoevento'] == 'quinceanos' ? ' Baile vals' : 'Baile principal o vals' ?>: </strong>  <?= $model['baileprincip'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Brindis: </strong> <?= $model['brindis'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Baile especial o inicio de fiesta: </strong> <?= $model['baileespecial'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Efectos especiales o pirotecnia: </strong> <?= $model['efectosespeciales'] ?> </p>




                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: center">
                        <p>  <strong>Recomendación coreógrafo:</strong> <br> <?= $model['recmcoreografo'] ?>  </p>

                        <p>  <strong>Recomendación maestro de ceremonia:</strong> <br> <?= $model['recmmaestrocemonia'] ?>  </p>
                        <br class="hidden-xs">
                        <br class="hidden-xs">
                        <br class="hidden-xs">
                        <p>  <strong>Recomendación efectos:</strong> <br>  <?= $model['recmefectosd'] ?>  </p>

                    </div>

                    <div class="col-xs-12">
                        <div class="wavy-background">
                            <div class="row">
                                <p class="dtlles">Detalles importantes:</p>
                                <p class="moremors" style="font-size: 16px">

                                    <?= $model['detallesimpingresonovis'] ?>
                                </p>
                            </div>
                        </div>


                    </div>



                </div>
                <div class="col-md-2 col-xs-12"></div>





            </div>

            <div class="col-xs-12" style="
    margin-top: 10px;
">


            <img src="<?= Url::to(['assets_b/img/loggomanza.png'])?>" class="logomazn">
            <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downl">
            <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downr">
            </div>















        </div>
    </div>
</div>








































<br>








<div class="detalledecoracion">
    <div class="">
        <div class="row">




            <div class="col-xs-12" style="padding: 0;">

                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong>Cena, fiesta y torta
                                <br>
                                <span style="font-size: 16px">Hora:  <?= $model['horacena'] ?> </span>

                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>




            <div class="col-xs-12" style="padding: 0; margin-top: 10px">

                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: right">


                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Cena: </strong> <?= $model['cenac'] ?>  </p>
                        <p>   <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Torta: </strong> <?= $model['tortac'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Bocaditos: </strong> <?= $model['bocaditosc'] ?>  </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Dulces: </strong>  <?= $model['dulcesc'] ?> </p>
                        <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <strong>Hora loca: </strong> <?= $model['horalocac'] ?>  </p>

                        <?php if ($model['tipoevento'] == 'quinceanos'): ?>
                            <p>  <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i><strong>Mariachis: </strong> <?= $model['mariachis'] ?>  </p>

                        <?php else: ?>

                        <?php endif; ?>

                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: center">
                        <p>  <strong>Recomendación cena:</strong> <br> <?= $model['recmcena'] ?>  </p>
                        <p>  <strong>Recomendación torta:</strong> <br> <?= $model['recmtorta'] ?>  </p>

                        <p>  <strong>Recomendación bocadillos:</strong> <br>  <?= $model['recmbocaditos'] ?>  </p>
                        <p>  <strong>Recomendación dulces:</strong> <br>  <?= $model['recmdul'] ?>  </p>
                        <p>  <strong>Recomendación:</strong> <br>  <?= $model['recmhoraloca'] ?>  </p>

                    </div>


                    <?php if ($model['tipoevento'] == 'quinceanos'): ?>
                        <div class="col-xs-12">
                            <div class="wavy-background">
                                <div class="row">
                                    <p class="dtlles">Detalles importantes:</p>
                                    <p class="moremors" style="font-size: 16px">

                                        <?= $model['otrosdetallesfiestas'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                    <?php endif; ?>



                </div>
                <div class="col-md-2 col-xs-12"></div>



            </div>


            <div class="col-xs-12" style="padding: 0; margin-top: 20px">

                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12" style="padding: 0">

                        <p class="subiselm">
                            <strong>Otros proveedores

                            </strong>

                        </p>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>
            <div class="col-xs-12" style="padding: 0; margin-top: 10px">



                <div class="col-md-2 col-xs-12"></div>

                <div class="col-xs-12 col-md-8" style="text-align: center">




                    <div class="col-sm-9 col-xs-12 psubiaprox crelifiosa">

                        <?php if ($model['tipoevento'] != 'quinceanos'): ?>
                            <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Invitaciones: </strong> <?= $model['invitaciones'] ?>  </p>
                            <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Fotografia: </strong> <?= $model['fotografia'] ?>  </p>
                            <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><strong>Video: </strong> <?= $model['video'] ?>  </p>
                            <p> <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Vestido de novia: </strong>  <?= $model['vestidodenovia'] ?> </p>
                            <p>   <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><strong>Salón de belleza: </strong> <?= $model['salondebelleza'] ?>  </p>
                            <p>  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> <strong>Traje del novio: </strong> <?= $model['trajenovio'] ?>  </p>
                            <p>  <i class="fa-solid fa-star"></i><strong>Glitterbar: </strong> <?= $model['glitterbar'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Espejo mágico: </strong> <?= $model['espejomagico'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Video 360° o Tunel : </strong> <?= $model['video360'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Memori phone: </strong> <?= $model['memoriphone'] ?>  </p>

                        <?php else: ?>
                            <p>  <i class="fa-solid fa-star"></i><strong>Glitterbar: </strong> <?= $model['glitterbar'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Espejo mágico: </strong> <?= $model['espejomagico'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Video 360° o Tunel : </strong> <?= $model['video360'] ?>  </p>
                            <p>   <i class="fa-solid fa-star"></i><strong>Memori phone: </strong> <?= $model['memoriphone'] ?>  </p>

                        <?php endif; ?>



                    </div>

                    <div class="col-md-3 col-sm-3 psubiaprox crelifiosarec col-xs-12" style="text-align: right">
                        <?php if ($model['tipoevento'] != 'quinceanos'): ?>

                            <p>  <strong>Recomendación foto y video:</strong> <br> <?= $model['recmfotoyvideo'] ?>  </p>
                            <p>  <strong>Recomendación vestido de novia:</strong> <br> <?= $model['recmvestidonovia'] ?>  </p>
                            <p>  <strong>Recomendación salón de belleza:</strong> <br> <?= $model['recmsalonbelleza'] ?>  </p>
                            <p>  <strong>Recomendación :</strong> <br> <?= $model['recmtrajenovio'] ?>  </p>
                            <p>  <strong>Recomendación :</strong> <br> <?= $model['recmgiiterbar'] ?>  </p>
                            <p>  <strong>Recomendación :</strong> <br> <?= $model['recmemori'] ?>  </p>
                        <?php else: ?>
                            <p>  <strong>Recomendación :</strong> <br> <?= $model['recmgiiterbar'] ?>  </p>
                            <p>  <strong>Recomendación :</strong> <br> <?= $model['recmemori'] ?>  </p>
                        <?php endif; ?>





                    </div>
                    <div class="col-xs-12">
                        <div class="wavy-background">
                            <div class="row">
                                <p class="dtlles">Detalles importantes:</p>
                                <p style="font-size: 16px">

                                    <?= $model['detallesimpoprov'] ?>
                                </p>
                            </div>
                        </div>


                    </div>

                </div>
                <div class="col-md-2 col-xs-12"></div>



                <div class="col-xs-12" style="
    margin-top: 10px;
">


                    <img src="<?= Url::to(['assets_b/img/loggomanza.png'])?>" class="logomazn">
                    <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downl">
                    <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downr">
                </div>

            </div>


















        </div>
    </div>
</div>








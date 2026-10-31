<?php

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


@$nombre = $_GET['id'];

$user2 = Yii::$app->session->get('user2');

$categorias5 = \app\models\Organizadoras::findOne(['idorganizadora' => $model['idorganizadora']]);

$script = <<<CSS


 
CSS;


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
//$this->registerCssFile('@web/web/plugin/galeria/thumbrigth/css/examples.css', ['depends'=>\app\assets\AppAsset::class, 'media' => 'screen','']);
$configuracion = \app\models\Configuracion::find()->one();
$configuracion['titulo_pagina']= $model['nombre'];



?>

<?= $this->render('banners/fijo',
    ['titulo' => 'Ficha del evento',])

?>


    <div class="container ">
        <br>
        <h2 class="txt-h2 text-center">Ficha del evento</h2>
        <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
        <br>

        <hr>

    </div>


<div class="container">

    <div class="">

        <div class="row">
            <div class="col-xs-12">
                <h2 class="comi"><a href="<?= Url::to(['logout2']) ?>" style="
                                                            color: #6ba622;
                                                            text-decoration: underline;
                                            "> CERRAR SESSION </a> </h2> <br>
            </div>


            <div class="col-xs-12 ficjhatec">

                <div class="col-xs-12">


                    <h3> PRESUPUESTO: </h3>

                    <p><strong>  PRESUPUESTO PAQUETE: </strong> <?= $user['nombre'] ?></p>
                    <p><strong> MONTO CANCELADO: </strong> <?= $model['monto_canceladop'] ?></p>
                    <p><strong> SALDO A PAGAR:  </strong> <?= $model['saldo_pagarp'] ?></p>

                    <p><strong> SERVICIOS ADICIONALES:  </strong> <?= $model['servicios_adicionales'] ?></p>
                    <p><strong> MONTO CANCELADO:  </strong> <?= $model['monto_cancelados'] ?></p>
                    <p><strong> SALDO POR PAGAR: </strong> <?= $model['saldo_pagars'] ?></p>


                    <h3> DATOS GENERALES DEL EVENTO :</h3>

                    <p><strong> Organizadora Asociada: </strong>   <?= $categorias5['nombre'] ?></p>

                    <p><strong>FECHA DE EVENTO: </strong> <?= $dia ?> de  <?= $meses2[$mes * 1] ?>,  <?= $ano ?></p>

                    <p><strong>SUCURSAL: </strong> <?= $model['sucursal'] ?></p>
                    <p><strong>TIPO DE EVENTO: </strong> <?= $model['tipo_evento'] ?></p>
                    <p><strong>HORA DE INICIO: </strong> <?= $model['hora_inicio'] ?></p>
                    <p><strong>NOMBRES: </strong> <?= $model['nombre'] ?></p>
                    <p><strong>TELEFONO: </strong> <?= $model['telefono'] ?></p>
                    <p><strong>EMAIL:  </strong> <?= $model['email'] ?></p>
                    <p><strong>TIPO DE PAQUETE:  </strong> <?= $model['paquete_reservar'] ?></p>
                    <p><strong>CANTIDAD DE GARZONES:  </strong> <?= $model['cant_garzones'] ?></p>
                    <p><strong> COLOR DE SILLAS: </strong> <?= $model['color_sillas'] ?></p>
                    <p><strong> COLOR DE MANTEL: </strong> <?= $model['color_mantel'] ?></p>
                    <p><strong>COLOR DE SERVILLETA: </strong> <?= $model['color_servilleta'] ?></p>
                    <p><strong> EN MESA: </strong> <?= $model['en_mesa'] ?></p>
                    <p><strong> TIPO DE BEBIDAS: </strong> <?= $model['tipo_bebidas'] ?></p>
                    <p><strong> VAJILLAS Y VASOS EN MESA: </strong> <?= $model['vajillasyvasisenmesa'] ?></p>
                    <p><strong>CATERING:  </strong> <?= $model['katering'] ?></p>

                    <h3> DECORACIÓN:</h3>



                    <p><strong>Nombre decoradora:  </strong> <?= $model['nombre_decoradora'] ?></p>
                    <p><strong> DETALLES: </strong> <?= $model['detalle_decoradora'] ?> </p>


                    <p><strong>OBSERVACIONES DEL CLIENTE SOBRE DECORACION: </strong> <?= $model['comentario_decoracion'] ?></p>


                    <h3> CRONOGRAMA:</h3>



                    <p><strong> Nombre coordinadora: </strong> <?= $model['nombre_cordinadora'] ?></p>
                    <p><strong> Nombre supervisora: </strong> <?= $model['nombre_supervisora'] ?></p>
                    <p><strong> OBSERVACIONES DEL CLIENTE SOBRE CRONOGRAMA:  </strong> <?= $model['comentario_cronograma'] ?> </p>

                </div>




            </div>







            <div class="col-xs-12 text-center">

                <br>
                <br>

                <div class="row">


                    <div class="col-md-6 col-xs-12">

                        <H3 style="
    font-weight: 600;
">Galeria decoracion</H3>

                    <div class="col-xs-9">
                        <ul class="bxslider" style="width: 468px; height: 468px;">


                            <?php foreach($model->galeriadecoracion as $key => $item):?>
                                <li><img src="<?= Url::to("@web/imagen/reservasdecoracion/{$item['archivo']}") ?>" class="img-thumbnail" style="max-height: 468px;"/></li>
                            <?php endforeach; ?>


                        </ul>
                    </div>
                    <div class="col-xs-3">
                        <div id="bx-pager">



                            <?php foreach($model->galeriadecoracion as $key => $item):?>
                                <a data-slide-index="<?php echo $key ?>" href=""><img src="<?= Url::to("@web/imagen/reservasdecoracion/{$item['archivo']}") ?>" class="img-thumbnail" style="width: 100px; height: 80px;"/></a>
                            <?php endforeach; ?>

                        </div>
                    </div>


                    </div>
                    <div class="col-md-6 col-xs-12">
                        <H3 style="
    font-weight: 600;
">Galeria Cronograma</H3>
                        <div class="col-xs-9">
                            <ul class="bxslider2" style="width: 468px; height: 468px;">


                                <?php foreach($model->galeriacronograma as $key => $item):?>
                                    <li><img src="<?= Url::to("@web/imagen/reservascronograma/{$item['archivo']}") ?>" class="img-thumbnail" style="max-height: 468px;"/></li>
                                <?php endforeach; ?>


                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <div id="bx-pager2">



                                <?php foreach($model->galeriacronograma as $key => $item):?>
                                    <a data-slide-index="<?php echo $key ?>" href=""><img src="<?= Url::to("@web/imagen/reservascronograma/{$item['archivo']}") ?>" class="img-thumbnail" style="width: 100px; height: 80px;"/></a>
                                <?php endforeach; ?>

                            </div>
                        </div>

                    </div>





                </div>

            </div>








        </div>



    </div>

</div>


<br>



    <br>




<?php
$script = <<<JS


 $('.bxslider').bxSlider({
                        pagerCustom: '#bx-pager',
                        controls: false,
                        auto: true,
                    });


 $('.bxslider2').bxSlider({
                        pagerCustom: '#bx-pager2',
                        controls: false,
                        auto: true,
                    });
 
 

JS;
$this->registerJs($script, \yii\web\View::POS_READY);

?>
<?php
use yii\helpers\Url;

$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);

$script = <<<CSS
.view {
    width: 100%}

.tit_acor{
    color: #527f1a;
    font-size: 17px;
    font-weight: 600;
    text-align: center;
    display:block;
}


.datepicker {
    padding: 20px 15px 40px 15px;
    border-radius: 4px;
    direction: ltr
}

.datepicker-inline {
    width: 100%;
    margin-bottom: 40px;
}

.calendar-inicio-head{
    background-color: #f37d89;
    color: #ffffff;
    font: 700 25px / 26px OpenSans;
    padding: 45px 30px;
    width: 350px;
    margin: 0 auto;
    height: 240px;
}




hr {
  
    border-top: 1px solid #d2d2d2;
}

.datepicker table tr td, .datepicker table tr th {
    text-align: center;
    width: 40px;
    height: 30px;
    border-radius: 4px;
    border: none;
}

@media  (min-width: 992px) and (max-width: 1199px) {



 .calendar-inicio-head{

    width: 300px!important;

    }

    .datepicker-inline {
    width: 100%!important;
    }


    .datepicker {
    padding: 20px 15px 40px 15px!important;
        border-radius: 4px;
        direction: ltr
    }
}
@media  (max-width: 767px) {


    .calendar-inicio-head{

    width: 280px!important;

    }

    .datepicker-inline {
    width: 100%!important;
    }


    .datepicker {
    padding: 4px 15px!important;
        border-radius: 4px;
        direction: ltr
    }
  }


.datepicker table tr td.active.active {background-color: #6ba622;
    border-color: #6ba622;

}

.dededered p,
.dededered a
{
    font-size: 14px;
}

.comi{
    color: #527f1a;
    font-size: 17px;
    font-weight: 600;
    text-align: left;
    display: block;
    font-size: 30px;
}

.caja p{font-size: 16px; margin-bottom: 5px; }
.caja .col-sm-6.col-xs-12{ border-bottom:  1px solid #d2d2d2; margin-bottom: 25px; }
CSS;
$this->registerCssFile('@web/assets_b/css/plugins.css', ['media' => 'screen', '']);
$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);
$user = Yii::$app->session->get('user');


$timepo =   date("Y") . "-" . date("m") . "-" . date("d");
$eventos1 = \app\models\Reservas::find()->where(['sucursal' => 'Doble via a la guardia', 'estado' => '1'])->andWhere(['>=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos2 = \app\models\Reservas::find()->where(['sucursal' => 'Garden', 'estado' => '1'])->andWhere(['>=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos3 = \app\models\Reservas::find()->where(['sucursal' => 'Parque Urbano', 'estado' => '1'])->andWhere(['>=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();
$eventos4 = \app\models\Reservas::find()->where(['sucursal' => 'Campestre', 'estado' => '1'])->andWhere(['>=', 'fecha_reserva', $timepo])->orderBy('fecha_reserva')->all();

$reservas1 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
$reservas2 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
$reservas3 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
$reservas4 = \app\models\Reservas::find()->where(['estado' => '1'])->all();

$comisiones = \app\models\Reservas::find()->where(['idorganizadora' => $user['idorganizadora']])->andWhere(['>=', 'fecha_reserva', $timepo])->all();


?>


<?= $this->render('../banners/fijo',
    ['titulo' => 'Organizadora / '. $user['nombre'],])

?>






    <div class="container dededered" style="padding-top: 60px;">




                    <!-- portfolio section -->

                                <div class="row">

                                    <div class="col-xs-12">
                                        <h2 class="comi"><a href="<?= Url::to(['logout']) ?>" style="
                                                            color: #6ba622;
                                                            text-decoration: underline;
                                            "> CERRAR SESSION </a> </h2> <br>
                                    </div>

                                    <div class="col-xs-12">
                                    <h2 class="comi"> COMISIONES </h2> <br> <br>
                                    </div>

                                    <?php foreach ($comisiones as $reservadede) :


                                        $href = Url::to(['reserva' , 'id'=> $reservadede['idreservas']]);
                                        ?>
                                    <div class="col-xs-12">



                                        <div class="caja">
                                            <div class="col-sm-6 col-xs-12">
                                                <p><strong> Evento de: </strong> <a href="<?= $href; ?>" style="
    color: #669f20;

" > <?=$reservadede['nombre']?> </a> </p>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <p><strong> Comision: </strong><?=$reservadede['comision_organizadora']?> Bs. </p>
                                            </div>
                                        </div>

                                    </div>



                                         <?php endforeach;?>




                                    <?php
                                    $query = (new \yii\db\Query())->from('reservas1')->where(['idorganizadora' => $user['idorganizadora']])->andWhere(['>=', 'fecha_reserva', $timepo]);
                                    $sum = $query->sum('comision_organizadora');
                                    ?>

                                    <div class="col-xs-12">
                                    <p style="float: right; font-size: 18px;">COMISION TOTAL: <strong>  <?php  echo $sum; ?> Bs. </strong> </p>

                                   </div>


                                    <div class="col-xs-12"> <BR>
                                        <h2 class="comi"> EVENTOS </h2> <br> <br>
                                    </div>

                                    <div class="col-xs-12">


                                        <div class="col-md-4 col-sm-6 ">
                                        <h2 class="acc_trigger ">
                                            <a href="#"><span
                                                        class="tit_acor" style="color: #527f1a;">Doble via a la guardia</span></a></h2>

                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salondoblevia1.jpg') ?>" class="img-responsive" alt="">


                                            <br>
                                        <div class="acc_container">
                                            <div class="row">

                                                <div class="col-xs-12" style="    margin-bottom: 40px;">

                                                    <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                                    <div id="salon1" class="text-center">
                                                    <div id="calendar" class="center-block "></div>

                                                    </div>
                                                    <script type="application/javascript">
                                                        $(document).ready(function() {

                                                            $('#salon1 #calendar').datepicker({
                                                                startDate: "now",
                                                                language: "es",
                                                                todayHighlight: true,
                                                                beforeShowDay: function (date) {

                                                                    <?php foreach ($reservas1 as $reserva) : ?>
                                                                    <?php if ($reserva['sucursal'] == "Doble via a la guardia") :?>
                                                                    if (date.getFullYear() == <?php echo (date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                                                        if (date.getMonth() ==<?php echo (date('m', strtotime($reserva['fecha_reserva']))-1)?>) {
                                                                            switch
                                                                                (date.getDate()) {
                                                                                case <?php echo date('d', strtotime($reserva['fecha_reserva']))?>:
                                                                                    return {
                                                                                        tooltip: 'Reservado',
                                                                                        classes: 'active',
                                                                                    };
                                                                            }
                                                                        }
                                                                    }
                                                                    <?php endif;?>
                                                                    <?php endforeach;?>
                                                                },
                                                            });
                                                        });
                                                    </script>

                                                    <?php if ($eventos1 ) : ?>
                                                        <?php  foreach ($eventos1 as $item):

                                                            $href = Url::to(['reserva' , 'id'=> $item['idreservas']]);


                                                            $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                                            $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                                                            $dia = date("d", strtotime($item['fecha_reserva']));
                                                            $mes = date("m", strtotime($item['fecha_reserva']));
                                                            $ano = date("Y", strtotime($item['fecha_reserva']));


                                                            ?>



                                                              <p>

                                                                  <strong> Nombre: </strong>     <?=$item['nombre']?>

                                                              </p>

                                                    <p>
                                                 <strong> Fecha de evento: </strong>   <?= $dia?> de <?= $meses2[$mes * 1] ?>, <?= $ano ?>
                                                    </p>


                                                            <a href="<?= $href; ?>" style="
    color: #669f20;
    text-decoration: underline;
" >Ir a ficha de evento</a>
                                                            <hr>


                                                            <?php
                                                        endforeach; ?>

                                                    <?php else: ?>

                                                        <p style="color: #9E9E9E">Sin eventos</p>
                                                    <?php endIf?>


                                                </div>
                                                <br><br>



                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4 col-sm-6 ">
                                        <h2 class="acc_trigger "><a ><span
                                                        class="tit_acor" style="color: #527f1a;" >Garden</span></a></h2>


                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salongarden1.jpg') ?>" class="img-responsive" alt="">


                                        <br>
                                        <div class="acc_container">
                                            <div class="row">


                                                <div class="col-xs-12" style="    margin-bottom: 40px;">

                                                    <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                                    <div id="salon2" class="text-center">
                                                    <div id="calendar2" class="center-block"></div>
                                                    </div>
                                                    <script type="application/javascript">

                                                        $(document).ready(function() {

                                                            $('#salon2 #calendar2').datepicker({
                                                                startDate: "now",
                                                                language: "es",
                                                                todayHighlight: true,
                                                                beforeShowDay: function (date){
                                                                    <?php foreach ($reservas2 as $reserva) : ?>
                                                                    <?php if ($reserva['sucursal'] == "Garden") :?>
                                                                    if (date.getFullYear() == <?php echo (date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                                                        if (date.getMonth() ==<?php echo (date('m', strtotime($reserva['fecha_reserva']))-1)?>) {

                                                                            switch
                                                                                (date.getDate()) {
                                                                                case <?php echo date('d', strtotime($reserva['fecha_reserva']))?>:
                                                                                    return {
                                                                                        tooltip: 'Reservado',
                                                                                        classes: 'active',
                                                                                    };
                                                                            }

                                                                        }
                                                                    }
                                                                    <?php endif;?>
                                                                    <?php endforeach;?>
                                                                },
                                                            });
                                                        });
                                                    </script>


                                                    <?php if ($eventos2) : ?>

                                                        <?php  foreach ($eventos2 as $item2):
                                                            $href = Url::to(['reserva' , 'id'=> $item2['idreservas']]);
                                                            $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                                            $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                                                            $dia = date("d", strtotime($item2['fecha_reserva']));
                                                            $mes = date("m", strtotime($item2['fecha_reserva']));
                                                            $ano = date("Y", strtotime($item2['fecha_reserva']));
                                                            ?>



                                                            <p>

                                                                <strong> Nombre: </strong>        <?=$item2['nombre']?>

                                                            </p>

                                                            <p>
                                                                <strong> Fecha de evento: </strong>   <?= $dia?> de <?= $meses2[$mes * 1] ?>, <?= $ano ?>
                                                            </p>


                                                            <a href="<?= $href; ?>" style="
    color: #669f20;
    text-decoration: underline;
" >Ir a ficha de evento</a>
                                                            <hr>


                                                            <?php
                                                        endforeach; ?>


                                                    <?php else: ?>

                                                        <p style="color: #9E9E9E">Sin eventos</p>
                                                    <?php endIf?>


                                                </div>
                                                <br><br>






                                            </div>
                                        </div>
                                    </div>


                                        <div class="col-md-4 col-sm-6 ">
                                        <h2 class="acc_trigger "><a href="#"><span
                                                        class="tit_acor" style="color: #527f1a;">Parque Urbano</span></a></h2>


                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/salonparqueurbano1.jpg') ?>" class="img-responsive" alt="">


                                        <br>
                                        <div class="acc_container">
                                            <div class="row">


                                                <div class="col-xs-12" style="    margin-bottom: 40px;">

                                                    <strong> <h4> <strong>Eventos </strong></h4>  </strong>

                                                    <div id="salon3" class="text-center">
                                                    <div id="calendar3" class="center-block" style="margin: 0 auto"></div>
                                                    </div>

                                                    <script type="application/javascript">

                                                        $(document).ready(function() {

                                                            $('#salon3 #calendar3').datepicker({
                                                                startDate: "now",
                                                                language: "es",
                                                                todayHighlight: true,
                                                                beforeShowDay: function (date){
                                                                    <?php foreach ($reservas3 as $reserva) : ?>
                                                                    <?php if ($reserva['sucursal'] == "Parque Urbano") :?>
                                                                    if (date.getFullYear() == <?php echo (date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                                                        if (date.getMonth() ==<?php echo (date('m', strtotime($reserva['fecha_reserva']))-1)?>) {

                                                                            switch
                                                                                (date.getDate()) {
                                                                                case <?php echo date('d', strtotime($reserva['fecha_reserva']))?>:
                                                                                    return {
                                                                                        tooltip: 'Reservado',
                                                                                        classes: 'active',
                                                                                    };
                                                                            }

                                                                        }
                                                                    }
                                                                    <?php endif;?>
                                                                    <?php endforeach;?>
                                                                },
                                                            });
                                                        });
                                                    </script>

                                                    <?php if ($eventos3) : ?>

                                                        <?php  foreach ($eventos3 as $item3):
                                                            $href = Url::to(['reserva' , 'id'=> $item3['idreservas']]);


                                                            $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                                            $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                                                            $dia = date("d", strtotime($item3['fecha_reserva']));
                                                            $mes = date("m", strtotime($item3['fecha_reserva']));
                                                            $ano = date("Y", strtotime($item3['fecha_reserva']));
                                                            ?>



                                                            <p>

                                                                <strong> Nombre: </strong> <?=$item3['nombre']?>

                                                            </p>

                                                            <p>
                                                                <strong> Fecha de evento: </strong>   <?= $dia?> de <?= $meses2[$mes * 1] ?>, <?= $ano ?>
                                                            </p>


                                                            <a href="<?= $href; ?>" style="
    color: #669f20;
    text-decoration: underline;
" >Ir a ficha de evento</a>
                                                            <hr>

                                                            <?php
                                                        endforeach; ?>

                                                    <?php else: ?>

                                                        <p style="color: #9E9E9E">Sin eventos</p>
                                                    <?php endIf?>


                                                </div>
                                                <br><br>




                                            </div>
                                        </div>
                                    </div>

                                        <!-- <div class="col-md-3 col-sm-6 ">
                                        <h2 class="acc_trigger "><a  ><span
                                                        class="tit_acor" style="color: #527f1a;">Campestre</span></a></h2>


                                        <img src="<?= \yii\helpers\Url::to('@web/assets_b/img/verdemanzana.jpg') ?>" class="img-responsive" alt="">



                                        <div class="acc_container">
                                            <div class="row">


                                                <div class="col-xs-12" style="    margin-bottom: 40px;">

                                                    <strong>
                                                        <h4>
                                                            <strong>Eventos </strong>
                                                        </h4>
                                                    </strong>



                                                    <div id="salon4" class="text-center">

                                                    <div id="calendar4" class="center-block" style="margin: 0 auto"></div>

                                                    </div>

                                                    <script type="application/javascript">

                                                        $(document).ready(function() {

                                                            $('#salon4 #calendar4').datepicker({
                                                                startDate: "now",
                                                                language: "es",
                                                                todayHighlight: true,
                                                                beforeShowDay: function (date){
                                                                    <?php foreach ($reservas4 as $reserva) : ?>
                                                                    <?php if ($reserva['sucursal'] == "Campestre") :?>
                                                                    if (date.getFullYear() == <?php echo (date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                                                        if (date.getMonth() ==<?php echo (date('m', strtotime($reserva['fecha_reserva']))-1)?>) {

                                                                            switch
                                                                                (date.getDate()) {
                                                                                case <?php echo date('d', strtotime($reserva['fecha_reserva']))?>:
                                                                                    return {
                                                                                        tooltip: 'Reservado',
                                                                                        classes: 'active',
                                                                                    };
                                                                            }

                                                                        }
                                                                    }
                                                                    <?php endif;?>
                                                                    <?php endforeach;?>
                                                                },
                                                            });
                                                        });
                                                    </script>
                                                    <?php if ($eventos4) : ?>

                                                        <?php  foreach ($eventos4 as $item4):
                                                            $href = Url::to(['reserva' , 'id'=> $item4['idreservas']]);

                                                            $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                                            $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                                                            $dia = date("d", strtotime($item4['fecha_reserva']));
                                                            $mes = date("m", strtotime($item4['fecha_reserva']));
                                                            $ano = date("Y", strtotime($item4['fecha_reserva']));
                                                            ?>



                                                            <p>

                                                                <strong> Nombre: </strong> <?=$item4['nombre']?>

                                                            </p>

                                                            <p>
                                                                <strong> Fecha de evento: </strong>    <?= $dia?> de <?= $meses2[$mes * 1] ?>, <?= $ano ?>
                                                            </p>


                                                            <a href="<?= $href; ?>" style="
    color: #669f20;
    text-decoration: underline;
" >Ir a ficha de evento</a>
                                                            <hr>


                                                            <?php
                                                        endforeach; ?>

                                                    <?php else: ?>

                                                        <p style="color: #9E9E9E">Sin eventos</p>
                                                    <?php endIf?>


                                                </div>
                                                <br><br>




                                            </div>
                                        </div>
                                    </div>  -->


                                    </div>




                            </div>

                        </div>









<?php
use kartik\widgets\DatePicker;




$script = <<<CSS


.datepicker {
    padding: 20px 20px 40px 40px;
    border-radius: 4px;
    direction: ltr
}

.datepicker-inline {
    width: 350px
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
    width: 300px!important;
    }


    .datepicker {
    padding: 4px 40px 40px 40px!important;
        border-radius: 4px;
        direction: ltr
    }
}
@media  (max-width: 767px) {


    .calendar-inicio-head{

    width: 280px!important;

    }

    .datepicker-inline {
    width: 280px!important;
    }


    .datepicker {
    padding: 4px 30px!important;
        border-radius: 4px;
        direction: ltr
    }
  }
CSS;


$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);



$reservas = \app\models\Reservas::find()->where(['estado' => '1'])->all();

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="txt-h2 pull-center">Disponibilidad <small>de salones</small></h2>


            <div class="separador-titulo"></div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <br>
            <div id="salon1" class="text-center">
                <div class="calendar-inicio-head">Doble via a la guardia <br /><div class="baner-txt2">Doble via a la Guardia entre 5° y 6° anillo #5195</div></div>

                <div id="calendar" class="center-block "></div>
            </div>

            <script type="application/javascript">
                $(document).ready(function() {

                    $('#salon1 #calendar').datepicker({
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date) {

                            <?php foreach ($reservas as $reserva) : ?>
                            <?php if ($reserva['salon'] == "salon1") :?>
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


        </div>
        <div class="col-sm-6 col-md-4">
            <br>
            <div id="salon2" class="text-center">
                <div class="calendar-inicio-head">Hilanderia<br /><div class="baner-txt2">Av. Hilanderia entre 4° y 5° anillo</div></div>
                <div id="calendar2" class="center-block"></div>
            </div>
            <script type="application/javascript">

                $(document).ready(function() {

                $('#salon2 #calendar2').datepicker({
                    startDate: "now",
                    language: "es",
                    todayHighlight: true,
                    beforeShowDay: function (date){
                        <?php foreach ($reservas as $reserva) : ?>
                        <?php if ($reserva['salon'] == "salon2") :?>
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
        </div>


        <div class="col-xs-12 col-md-4">
            <br>
            <div id="salon3" class="text-center">
                <div class="calendar-inicio-head" style="margin: 0 auto">Parque Urbano<br /><div class="baner-txt2">Av. Capitan Arrien entre 1er y 2do anillo</div></div>
                <div id="calendar3" class="center-block" style="margin: 0 auto"></div>
            </div>
            <script type="application/javascript">

                $(document).ready(function() {

                    $('#salon3 #calendar3').datepicker({
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date){
                            <?php foreach ($reservas as $reserva) : ?>
                            <?php if ($reserva['salon'] == "salon3") :?>
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
        </div>


    </div>


</div>
















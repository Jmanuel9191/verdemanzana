<?php

use yii\helpers\Html;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use linslin\yii2\curl;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;


$model = new  \app\models\Reservasform();


$paquetes = \app\models\Productos::find()->where(['estado' => '1'])->all();
$reservas1 = \app\models\Reservas::find()->where(['estado' => '1'])->all();








$script = <<<CSS
.form-control {
    display: block;
    width: 90%;
    height: 40px;
    padding: 5px 10px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    margin: 0 15px;
}

.form-group {
    margin-bottom: 0px;
}

CSS;

$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);





?>





<?php $form = ActiveForm::begin([
    'id' => 'salon1',
    'options'=>['class'=>'form-horizontal'],
    'action'=>['site/reservas2'],'method'=>'post',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"\">{input}</div>\n<div class=\"\">{error}</div>",
    ],
    //'options'=>['class'=>'col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2']
]); ?>



    <?= $form->field($model, 'salon')->hiddenInput(['value'=> 'salon1'])->label(false);?>

    <div class="col-md-6">

        <?= $form->field($model, 'nombre')->label(false)->textInput(['class'=>'form-control','placeholder'=>'Nombre Completo' , 'required' => true  ]) ?>

        <br>
    </div>
    <div class="col-md-6">

        <?= $form->field($model, 'apellidos')->label(false)->textInput(['class'=>'form-control','placeholder'=>'Apellidos' , 'required' => true  ]) ?>

        <br>
    </div>
    <div class="col-md-6">

        <?= $form->field($model, 'email')->label(false)->input('email')->textInput(['class'=>'form-control','placeholder'=>'Email', 'required' => true]) ?>

        <br>
    </div>
    <div class="col-md-6">

        <?= $form->field($model, 'telefono')->label(false)->textInput(['class'=>'form-control','placeholder'=>'Telefono' , 'required' => true  ]) ?>

        <br>
    </div>
    <div class="col-md-6">


        <?= $form->field($model, 'pais')->label(false)->dropDownList(
            [


                'BO ' => 'Bolivia',
                'AF' => 'Afganistán',
                'AL' => ' Albania',
                'DE ' => 'Alemania',
                'AD' => 'Andorra',
                ' AO' => 'Angola',
                '   AI' => 'Anguilla',
                '   AQ' => 'Antártida',
                '   AG' => 'Antigua y Barbuda',
                '   AN' => 'Antillas Holandesas',
                '   SA' => 'Arabia Saudí',
                '   DZ' => 'Argelia',
                '   AR' => 'Argentina',
                '   AM' => 'Armenia',
                '   AW' => 'Aruba',
                '   AU' => 'Australia',
                '    AT' => 'Austria',
                '   AZ' => 'Azerbaiyán',
                '   BS' => 'Bahamas',
                '   BH' => 'Bahrein',
                '   BD' => 'Bangladesh',
                '   BB' => 'Barbados',
                '   BE' => 'Bélgica',

                '   BZ' => 'Belice',

                '   BJ' => 'Benin',

                '   BM' => 'Bermudas',

                '   BY' => 'Bielorrusia',

                '   MM ' => 'Birmania',


                '   BA' => 'Bosnia y Herzegovina',

                '   BW' => 'Botswana',

                '   BR' => 'Brasil',

                '   BN' => 'Brunei',

                '   BG' => 'Bulgaria',

                '   BF' => 'Burkina Faso',

                '   BI' => 'Burundi',

                '   BT' => 'Bután',

                '   CV' => 'Cabo Verde',

                '   KH' => 'Camboya',

                '   CM' => 'Camerún',

                '   CA' => 'Canadá',

                '   TD' => 'Chad',

                '   CL' => 'Chile',

                '   CN' => 'China',

                '   CY' => 'Chipre',

                '   VA' => 'Ciudad del Vaticano (Santa Sede)',

                '   CO' => 'Colombia',

                '   KM' => 'Comores',

                '   CG' => 'Congo',

                '   CD' => 'Congo, República Democrática del',

                '   KR' => 'Corea',

                '   KP' => 'Corea del Norte',

                '   CI' => 'Costa de Marfíl',

                '   CR' => 'Costa Rica',

                '   HR' => 'Croacia (Hrvatska)',

                '   CU' => 'Cuba',

                '   DK' => 'Dinamarca',

                '   DJ' => 'Djibouti',

                '   DM' => 'Dominica',

                '   EC' => 'Ecuador',

                '   EG' => 'Egipto',

                '   SV' => 'El Salvador',

                '   AE' => 'Emiratos Árabes Unidos',

                '   ER' => 'Eritrea',

                '   SI' => 'Eslovenia',

                '   ES' => 'España',

                '   US' => 'Estados Unidos',

                '   EE' => 'Estonia',

                '   ET' => 'Etiopía',

                '    FJ' => 'Fiji',

                '   PH' => 'Filipinas',

                '   FI' => 'Finlandia',

                '   FR' => 'Francia',

                '   GA' => 'Gabón',

                '   GM' => 'Gambia',

                '   GE' => 'Georgia',

                '   GH' => 'Ghana',

                '   GI' => 'Gibraltar',

                '   GD' => 'Granada',

                '   GR' => 'Grecia',

                '   GL' => 'Groenlandia',

                '   GP' => 'Guadalupe',

                '   GU' => 'Guam',

                '   GT' => 'Guatemala',

                '   GY' => 'Guayana',

                '   GF' => 'Guayana Francesa',

                '   GN' => 'Guinea',

                '   GQ' => 'Guinea Ecuatorial',

                '   GW' => 'Guinea-Bissau',

                '   HT' => 'Haití',

                '   HN' => 'Honduras',

                '   HU' => 'Hungría',

                '   IN' => 'India',

                '   ID' => 'Indonesia',

                '   IQ' => 'Irak',

                '   IR' => 'Irán',

                '   IE' => 'Irlanda',

                '   BV' => 'Isla Bouvet',

                '   CX' => 'Isla de Christmas',

                '   IS' => 'Islandia',

                '   KY' => 'Islas Caimán',

                '   CK' => 'Islas Cook',

                '   CC' => 'Islas de Cocos o Keeling',

                '   FO' => 'Islas Faroe',

                '   HM' => 'Islas Heard y McDonald',

                '   FK' => 'Islas Malvinas',

                '   MP' => 'Islas Marianas del Norte',

                '   MH' => 'Islas Marshall',

                '   UM' => 'Islas menores de Estados Unidos',

                '   PW' => 'Islas Palau',

                '   SB' => 'Islas Salomón',

                '  SJ' => 'Islas Svalbard y Jan Mayen',

                '    TK' => 'Islas Tokelau',

                '    TC' => 'Islas Turks y Caicos',

                '   VI' => 'Islas Vírgenes (EEUU)',

                '   VG' => 'Islas Vírgenes (Reino Unido)',

                '   WF' => 'Islas Wallis y Futuna',

                '   IL' => 'Israel',

                '   IT' => 'Italia',

                '   JM' => 'Jamaica',

                '   JP' => 'Japón',

                '   JO' => 'Jordania',

                '   KZ' => 'Kazajistán',

                '   KE' => 'Kenia',

                '    KG' => 'Kirguizistán',

                '    KI' => 'Kiribati',

                '   KW' => 'Kuwait',

                '   LA' => 'Laos',

                '   LS' => 'Lesotho',

                '   LV' => 'Letonia',

                '    LB' => 'Líbano',

                '    LR' => 'Liberia',

                '   LY' => 'Libia',

                '   LI' => 'Liechtenstein',

                '   LT' => 'Lituania',

                '   LU' => 'Luxemburgo',

                '   MK' => 'Macedonia, Ex-República Yugoslava de',

                '   MG' => 'Madagascar',

                '   MY' => 'Malasia',

                '   MW' => 'Malawi',

                '   MV' => 'Maldivas',

                '   ML' => 'Malí',

                '   MT' => 'Malta',

                '   MA' => 'Marruecos',

                '   MQ' => 'Martinica',

                '   MU' => 'Mauricio',

                '   MR' => 'Mauritania',

                '   YT' => 'Mayotte',

                '   MX' => 'México',

                '   FM' => 'Micronesia',

                '   MD' => 'Moldavia',

                '   MC' => 'Mónaco',

                '   MN' => 'Mongolia',

                '   MS' => 'Montserrat',

                '   MZ' => 'Mozambique',

                '   NA' => 'Namibia',

                '   NR' => 'Nauru',

                '   NP' => 'Nepal',

                '   NI' => 'Nicaragua',

                '   NE' => 'Níger',

                '   NG' => 'Nigeria',

                '   NU' => 'Niue',
                '   NF' => 'Norfolk',

                '  NO' => 'Noruega',

                '   NC' => 'Nueva Caledonia',

                '   NZ' => 'Nueva Zelanda',

                '   OM' => 'Omán',

                '   NL' => 'Países Bajos',

                '   PA' => 'Panamá',

                '   PG' => 'Papúa Nueva Guinea',

                '   PK' => 'Paquistán',

                '   PY' => 'Paraguay',

                '   PE' => 'Perú',

                '   PN' => 'Pitcairn',

                '   PF' => 'Polinesia Francesa',

                '   PL' => 'Polonia',

                '   PT' => 'Portugal',

                '   PR' => 'Puerto Rico',

                '   QA' => 'Qatar',

                '   UK' => 'Reino Unido',

                '   CF' => 'República Centroafricana',

                '   CZ' => 'República Checa',

                '   ZA' => 'República de Sudáfrica',

                '   DO' => 'República Dominicana',

                '   SK' => 'República Eslovaca',

                '   RE' => 'Reunión',

                '   RW' => 'Ruanda',

                '   RO' => 'Rumania',

                '   RU' => 'Rusia',

                '   EH' => 'Sahara Occidental',

                '   KN' => 'Saint Kitts y Nevis',

                '   WS' => 'Samoa',

                '   AS' => 'Samoa Americana',

                '   SM' => 'San Marino',

                '   VC' => 'San Vicente y Granadinas',

                '   SH' => 'Santa Helena',

                '   LC' => 'Santa Lucía',

                '   ST' => 'Santo Tomé y Príncipe',

                '   SN' => 'Senegal',

                '   SC' => 'Seychelles',

                '   SL' => 'Sierra Leona',

                '   SG' => 'Singapur',

                '   SY' => 'Siria',

                '   SO' => 'Somalia',

                '   LK' => 'Sri Lanka',

                '   PM' => 'St Pierre y Miquelon',

                '   SZ' => 'Suazilandia',

                'SD' => 'Sudán',

                'SE' => 'Suecia',

                'CH' => 'Suiza',

                'SR' => 'Surinam',

                'TH' => 'Tailandia',

                'TW' => 'Taiwán',

                'TZ' => 'Tanzania',

                '   TJ' => 'Tayikistán',

                '   TF' => 'Territorios franceses del Sur',

                '   TP' => 'Timor Oriental',

                '   TG' => 'Togo',

                '   TO' => 'Tonga',

                '   TT' => 'Trinidad y Tobago',

                '   TN' => 'Túnez',

                '   TM' => 'Turkmenistán',

                '   TR' => 'Turquía',

                '   TV' => 'Tuvalu',

                '   UA' => 'Ucrania',

                '   UG' => 'Uganda',

                '   UY' => 'Uruguay',

                '   UZ' => 'Uzbekistán',

                '   VU' => 'Vanuatu',

                '   VE' => 'Venezuela',

                '   VN' => 'Vietnam',

                '    YE' => 'Yemen',

                '   YU' => 'Yugoslavia',

                '   ZM' => 'Zambia',

                '   ZW' => 'Zimbabue'


            ]
        ) ?>



        <br>
    </div>
    <div class="col-md-6">



        <?= $form->field($model, 'ciudad')->label(false)->textInput(['class'=>'form-control','placeholder'=>'Ciudad' ]) ?>


        <br>
    </div>
    <div class="col-md-6">


        <?= $form->field($model, 'paquete')->label(false)->dropdownList(
            \app\models\Productos::getSelectMenu(),
            ['data' => \app\models\Productos::getSelectMenu(),
                'prompt' => 'Elije el paquete']) ?>



        <br>
    </div>
    <div class="col-md-6" id="fecha_reserva">
        <div class="input-group date">

            <?= $form->field($model, 'fecha_reserva')->label(false)->textInput(['class'=>'form-control','placeholder'=>'fecha_reserva', 'id'=>'salon1' ,  'style' => 'width: 92.9%;']) ?><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>

        </div>
        <br>

        <script type="application/javascript">
            $(document).ready(function() {
                $('#salon1 #fecha_reserva .input-group.date').datepicker({
                    format: "dd-mm-yyyy",
                    startDate: "now",
                    language: "es",
                    todayHighlight: true,
                    beforeShowDay: function (date){
                        <?php foreach ($reservas1 as $reserva) : ?>
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
                }); });
        </script>
    </div>
    <div class="col-md-12">

        <?= $form->field($model, 'mensaje')->label(false)->textarea(['rows' => 12, 'cols'=> 15, 'class'=>'form-control ','placeholder'=>'Mensaje....', 'style' => 'width: 96%;']) ?>






        <br>
    </div>
    <div class="col-xs-12">


        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class=""><div class="col-md-4  col-xs-12">{image}</div><div class="col-md-8 col-xs-12">{input}</div></div>',
            'options'=>['class'=>'form-control input-sm','placeholder'=>'Insertar Captcha'],
        ])->label(false) ?>



        <br>
    </div>
    <div class="col-xs-12">
        <div class="text-center">

            <input type="submit" name="enviar" id="submit_contact" class="btn btn-success2" value="Enviar" >

            <input type="reset" value="Borrar" class="btn btn-danger">
        </div>
    </div>





    <?php ActiveForm::end(); ?>

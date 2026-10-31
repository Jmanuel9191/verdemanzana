<?php
/// solucion geoposicion https://developers.google.com/maps/documentation/geolocation/intro
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use kartik\widgets\DatePicker;

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use linslin\yii2\curl;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;
use yii\helpers\Html;
$model = new \app\models\Cotizaciones();

$configuracion = \app\models\Configuracion::find()->one();
$this->render('widgets/metatags', ['model' => $configuracion]);



$script = <<<CSS
CSS;

$this->registerCss($script, ['depends' => \app\assets_b\AppAsset::class]);

?>






<?= $this->render('banners/fijo',
    ['titulo' => 'Cotizaciones',])

?>








<div class="contactos">
<div class="container">
    <div class="row">


        <br>
        <br>

            <h2 class="txt-h2 text-center">Formulario de cotizacion</h2>
            <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>

        <p class="text-center">Rellena los campos del formulario y nosotros nos contactaremos con usted.</p>
            <br>
            <hr/>

        <?php
        $format = <<< SCRIPT
function format(state) {
if (!state.id) return state.text; // optgroup
return state.text;
}
SCRIPT;
        $escape = new \yii\web\JsExpression("function(m) { return m; }");
        $this->registerJs($format, \yii\web\View::POS_HEAD);
        ?>



        <div class="col-sm-2 col-xs-12"></div>


        <div class="col-sm-8 col-xs-12">
        <?php $form = ActiveForm::begin([

            'class' => 'contact-form',
                'id' => 'login-form',
            /*'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-4 control-label'],
            ],*/
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

            <div class="form-group" align="center">
                <?= $form->field($model, 'nombre')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Nombre Completo' , 'required' => true  ]) ?>
            </div>

            <div class="form-group" align="center">
                <?= $form->field($model, 'telefono')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Teléfono' , 'required' => true  ]) ?>


            </div>


        <div class="form-group" align="center">


            <?= $form->field($model, 'email')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca su Email' , 'required' => true  ]) ?>

        </div>


            <div class="form-group" align="center">
                <?= $form->field($model, 'tipo_evento')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca el tipo de evento' , 'required' => true  ]) ?>



            </div>


            <div class="form-group" align="center">
                <?= $form->field($model, 'fecha_evento')->label(false)->widget(
                    DatePicker::className(), [
                    // modify template for custom rendering
                    'options' => ['placeholder' => 'Fecha del evento'],
                    'language' => 'es',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',

                        'todayBtn' => "linked",
                        'keyboardNavigation' => false,
                        'forceParse' => false
                    ]
                ]); ?>


            </div>


            <div class="form-group" align="center">
                <?= $form->field($model, 'cantidad_invitados')->label(false)->textInput(['class'=>'form-control color','placeholder'=>'Introduzca la cantidad de invitados' , 'required' => true  ]) ?>



            </div>

            <div class="form-group" align="center">

            <?= $form->field($model, 'sucursal')->label(false)->dropDownList(
                [   'Doble via a la guardia' => 'Doble via a la guardia',
                    'Hilanderia' => 'Hilanderia',
                    'Parque Urbano' => 'Parque Urbano',
                    'Campestre' => 'Campestre',



                ],
                [
                    'prompt' => 'Introduzca la sucursal',
                    [
                        'disabled' => true,
                    ]
                ]


            ) ?>
            </div>
            <div class="form-group" align="center">
            <?= $form->field($model, 'comentario')->label(false)->textarea(['rows' => 6, 'cols'=> 10, 'class'=>'form-control  minpuut','placeholder'=>'Algun comentario que desa hacer']) ?>

            </div>

            <br>
        <div class="form-group" align="center" >

            <?= Html::submitButton('Enviar cotizacion', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>

        </div>
            <?php ActiveForm::end(); ?>
        </div>

        <hr>
        <div class="col-sm-2 col-xs-12"></div>
        </div>


    </div>
    <br>


</div>









<?php
$script = <<<JS


JS;
$this->registerJs($script, \yii\web\View::POS_END);
echo $this->render('widgets/geolocation');
?>





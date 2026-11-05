<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$escape = new \yii\web\JsExpression("function(m) { return m; }");
?>

<script src="../../assets_b/js/bootstrap-datepicker.min.js"></script>
<script src="../../assets_b/js/bootstrap-datepicker.es.min.js"></script>


<div class="container-fluid">

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'type' => ActiveForm::TYPE_HORIZONTAL,

            'formConfig' => [
                'deviceSize' => ActiveForm::SIZE_SMALL,
            ]
        ]); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-lg-12 ol-xs-12 labelso">
                <br>

                <?= $form->field($model, 'tipoevento')->dropDownList(
                    ['matrimonio' => 'Matrimonio',
                     'quinceanos' => 'Quince años',

                    ]
                ) ?>

            </div>
            <div class="col-lg-6 col-xs-12 labelso pinks">
                <h3 class="coloh3">Datos generales</h3>

                <?= $form->field($model, 'fecha_boda')->widget(
                    DatePicker::className(), [
                    // modify template for custom rendering
                    'language' => 'es',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayBtn' => "linked",
                        'keyboardNavigation' => false,
                        'forceParse' => false
                    ]
                ]); ?>
                <?= $form->field($model, 'nombrenovios')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>

                <?= $form->field($model, 'salondeeventos')->dropDownList(
                    ['Doble via a la guardia' => 'Doble via a la guardia',
                        'Garden' => 'Garden',
                        'Parque Urbano' => 'Parque Urbano',

                    ]
                ) ?>

                <?= $form->field($model, 'paqueteeventos')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'horainvitacionevento')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'iglesia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'direccioniglesia')->textInput(['maxlength' => true]) ?>


            </div>
            <div class="col-lg-6 col-xs-12 labelso">
                <h3 class="coloh3"> &nbsp; </h3>

                <?= $form->field($model, 'horaiglesia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'musica')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'horasmusicas')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'maestradeceremonia')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'recomendacionsalon')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'recomendacioniglesia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recomendaciondj')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>

            </div>


            <hr>
        </div>




        <div class="col-xs-12">
            <div class="col-lg-6 col-xs-12 labelso">

                <?= $form->field($model, 'celebranter')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'decoradorar')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'degustacioncomida')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'desgustaciontorta')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'degustacionbarra')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'coordinadorar')->textInput(['maxlength' => true]) ?>


            </div>
            <div class="col-lg-6 col-xs-12 labelso">

                <?= $form->field($model, 'aproxcelebrante')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'aproxdecoradora')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'aproxcomida')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'aproxtorta')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'aproxbarra')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>
                <?= $form->field($model, 'aproxcoordinadora')->textInput(['maxlength' => true, 'class'=>'pinks']) ?>

            </div>


            <hr>
        </div>




        <div class="col-xs-12">
            <div class="col-xs-12">
            <h3 class="coloh3">Ceremonia Religiosa</h3>
            </div>

            <div class="col-lg-6 col-xs-12 labelso">

                <?= $form->field($model, 'horaceremonia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'ccordinacions')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cdecoracion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'censamblajemusical')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cbouquet')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'ctransporte')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'caccesorios')->textInput(['maxlength' => true]) ?>




            </div>
            <div class="col-lg-6 col-xs-12 labelso">
                <?= $form->field($model, 'csalidasesposos')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cessionfotos')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'crecmcoodinacion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'crecmdecoracion')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'crecmensamblaje')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'csugsaludasesposo')->textInput(['maxlength' => true]) ?>
            </div>







        </div>








<div class="col-xs-12 labelso">
    <div class="col-xs-12 ">
    <h3 class="coloh3">Ceremonia Civil</h3> </div>


    <div class="col-md-6 col-xs-12">
        <?= $form->field($model, 'horacivil')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'oficialregistrocivil')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'tipodeceremoniaciv')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'testigoscv')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'cvensabmlemusical')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'cvefectosespec')->textInput(['maxlength' => true]) ?>




    </div>
    <div class="col-md-6 col-xs-12">
        <?= $form->field($model, 'ccprecoctel')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'recmregistrocv')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'recmensambcivil')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'recmefectos')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'detallesimpcv')->textarea(['rows' => 5, 'cols'=> 10]) ?>
    </div>






</div>


        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Bebidas</h3> </div>
            <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'cerveza')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sodas')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'agua')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'otrosbebidas')->textInput(['maxlength' => true]) ?>

                </div>


            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'barracocteles')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'horadeapertura')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmbebidas')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmbarradecocteles')->textInput(['maxlength' => true]) ?>

            </div>


        </div>



        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Campos Faltantes de bebidas para 15 años </h3> </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'dulcebienvenida')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'mesadulces')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'mesabocaditos')->textInput(['maxlength' => true]) ?>


            </div>


            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'maquetatortafalsa')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'detallesimportantesbebidas')->textarea(['rows' => 5, 'cols'=> 10]) ?>

            </div>


        </div>






        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Ingreso de novios, vals y brindis (Ingreso Quinceañera)</h3> </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'horaingresonovios')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'coreografo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'maestoceremoniadet')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bailedeingreso')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'baileprincip')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'brindis')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'baileespecial')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'efectosespeciales')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmcoreografo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmmaestrocemonia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmefectosd')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'detallesimpingresonovis')->textarea(['rows' => 5, 'cols'=> 10]) ?>
            </div>

        </div>

        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Campos faltantes de ingreso para 15 años </h3> </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'ingresoin')->textInput(['maxlength' => true]) ?>

            </div>


            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'experienciasin')->textInput(['maxlength' => true]) ?>

            </div>


        </div>



        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Cena, fiesta y torta</h3> </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'horacena')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cenac')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tortac')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'bocaditosc')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'dulcesc')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'horalocac')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'otrosdetallesfiestas')->textarea(['rows' => 5, 'cols'=> 10]) ?>


            </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'recmcena')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmtorta')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmbocaditos')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmdul')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmhoraloca')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'mariachis')->textInput(['maxlength' => true]) ?>

            </div>

        </div>


        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Otros proveedores o (Antes de la fiesta)</h3> </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'invitaciones')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'fotografia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'vestidodenovia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'salondebelleza')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'trajenovio')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'glitterbar')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'espejomagico')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'video360')->textInput(['maxlength' => true]) ?>


            </div>
            <div class="col-xs-12 col-md-6">

                <?= $form->field($model, 'memoriphone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmfotoyvideo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmvestidonovia')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmsalonbelleza')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmtrajenovio')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmgiiterbar')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recmemori')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'detallesimpoprov')->textarea(['rows' => 5, 'cols'=> 10]) ?>
            </div>

        </div>

        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Campos faltantes para Antes de la fiesta de quinceaños</h3> </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'limosna')->textInput(['maxlength' => true]) ?>


            </div>
            <div class="col-xs-12 col-md-6">

                <?= $form->field($model, 'horantesfiesta')->textInput(['maxlength' => true]) ?>
            </div>

        </div>






        <div class="form-group col-xs-12">
                <div class="col-xs-12 btnactial">


                    <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
    </div>

            <?php ActiveForm::end(); ?>

    </div>



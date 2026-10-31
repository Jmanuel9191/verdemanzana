<?php

use dosamigos\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */
/* @var $form yii\widgets\ActiveForm */
?>
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
<?php
$initial = [];
array_push($initial, Html::img('@web/imagen/usuarios/' . $model->cronogramafile, ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:250px']));
?>
<?php
$initial2 = [];
array_push($initial2, Html::img('@web/imagen/usuarios/' . $model->listainvitadosfile, ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:250px']));
?>
<?php
$initial3 = [];
array_push($initial3, Html::img('@web/imagen/usuarios/' . $model->presupuestofile, ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:250px']));
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
            <div class="col-lg-6 col-xs-12 labelso">
                <h3 class="coloh3">Presupuesto</h3>

                <?= $form->field($model, 'presupuesto')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'monto_canceladop')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'saldo_pagarp')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-lg-6 col-xs-12 labelso">
                <h3 class="coloh3">Servicios adicionales</h3>

                <?= $form->field($model, 'servicios_adicionales')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'monto_cancelados')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'saldo_pagars')->textInput(['maxlength' => true]) ?>

            </div>


            <div class="col-xs-12 labelso">

            <?= $form->field($model, 'file3')->widget(\kartik\widgets\FileInput::classname(), [
                'options' => [
                    'multiple' => false,
                    'accept' => '.pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx',
                ],
                'pluginOptions' => [
                    'initialPreviewFileType' => 'any', // Permite previsualizar cualquier tipo de archivo
                    'allowedFileExtensions' => [
                        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', // Archivos de oficina
                        // 'jpg', 'png', 'gif', 'webp', 'jpeg'                 // Imágenes
                    ],
                    //'maxFileCount' => 10, // Puedes activarlo si deseas limitar el número de archivos
                    'initialPreview' => $initial3,
                    'showRemove' => true,
                    'showUpload' => false,

                    'deleteUrl' => Yii::$app->urlManager->createAbsoluteUrl([
                        'reservas/delete-file',
                        'id' => $model->idreservas,
                        'fileType' => 'presupuests'
                    ]),
                    'language' => 'es'
                ]
            ]); ?>
            </div>


            <hr>
        </div>

        <div class="col-xs-12">
            <div class="col-xs-12">
            <h3 class="coloh3">Datos generales del evento</h3>
            </div>

            <div class="col-lg-6 col-xs-12 labelso">

                <?= $form->field($model, 'idorganizadora')->widget(Select2::classname(), [

                    'data' => \app\models\Organizadoras::getSelectorganizadoras(),
                    'language' => 'es',
                    'options' => [
                        'placeholder' => 'Organizadora',
                        //'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'templateResult' => new \yii\web\JsExpression('format'),
                        'templateSelection' => new \yii\web\JsExpression('format'),
                        'escapeMarkup' => $escape,
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-lg-6 col-xs-12 labelso">
                <?= $form->field($model, 'comision_organizadora')->textInput(['maxlength' => true]) ?>
            </div>


            <?php $reservas1 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
            $reservas2 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
            $reservas3 = \app\models\Reservas::find()->where(['estado' => '1'])->all();
            $reservas4 = \app\models\Reservas::find()->where(['estado' => '1'])->all();

            ?>


            <style>

                .btnactial button{
                    padding: 10px 19px;
                    margin-bottom: 20px;
                    margin-left: 15px;
                    font-size: 20px;
                    font-weight: 600;
                }



                .control-label.col-sm-2{
                    display: block;
                    font-size: 14px;
                    width: 100%;
                    padding: 10px 15px
                }

                .form-group .col-sm-10{

                width: 100%; }
                .row{
                align-items: flex-start; /* Asegura la alineación superior */
                }
                .lelelrel h3 {


                    text-align: center;
                    font-size: 18px;
                    font-weight: 600;
                    color: #527f1a;
                }

                .lelelrel .center-block {

                    border: 1px solid #527f1a;
                    padding: 5px;
                }

                .datepicker table tr td.active:active, .datepicker table tr td.active.highlighted:active, .datepicker table tr td.active.active, .datepicker table tr td.active.highlighted.active {
                    color: #fff !important;
                    background-color: #527f1a;
                    border-color: #527f1a;
                }


            </style>

            <DIV CLASS="lelelrel">
                <div class="col-xs-12" style="margin-top: 20px; margin-bottom: 30px;">

                    <div class="col-md-4 col-sm-6 col-xs-12">

                        <h3>Doble via a la guardia </h3>

                        <div id="salon1" class="text-center">
                            <div class="calendar-inicio-head">

                            </div>

                            <div id="calendar" class="center-block "></div>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6 col-xs-12">

                        <h3>Garden </h3>
                        <div id="salon2" class="text-center">
                            <div class="calendar-inicio-head">

                            </div>

                            <div id="calendar2" class="center-block "></div>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <h3>Parque Urbano </h3>
                        <div id="salon3" class="text-center">
                            <div class="calendar-inicio-head">

                            </div>

                            <div id="calendar3" class="center-block "></div>
                        </div>
                    </div>


                    <!-- <div class="col-md-3 col-sm-6 col-xs-12">
                <h3>Campestre  </h3>
                <div id="salon4" class="text-center">
                    <div class="calendar-inicio-head">

                    </div>

                    <div id="calendar4" class="center-block "></div>
                </div>
            </div>-->
                </DIV>
            </div>
            <script type="application/javascript">
                $(document).ready(function () {
                    $('#salon1 #calendar').datepicker({
                        format: "dd-mm-yyyy",
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date) {
                            <?php foreach ($reservas1 as $reserva) : ?>
                            <?php if ($reserva['sucursal'] == "Doble via a la guardia") :?>
                            if (date.getFullYear() == <?php echo(date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                if (date.getMonth() ==<?php echo(date('m', strtotime($reserva['fecha_reserva'])) - 1)?>) {

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


            <script type="application/javascript">
                $(document).ready(function () {
                    $('#salon2 #calendar2').datepicker({
                        format: "dd-mm-yyyy",
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date) {
                            <?php foreach ($reservas2 as $reserva) : ?>
                            <?php if ($reserva['sucursal'] == "Garden") :?>
                            if (date.getFullYear() == <?php echo(date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                if (date.getMonth() ==<?php echo(date('m', strtotime($reserva['fecha_reserva'])) - 1)?>) {

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


            <script type="application/javascript">
                $(document).ready(function () {
                    $('#salon3 #calendar3').datepicker({
                        format: "dd-mm-yyyy",
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date) {
                            <?php foreach ($reservas3 as $reserva) : ?>
                            <?php if ($reserva['sucursal'] == "Parque Urbano") :?>
                            if (date.getFullYear() == <?php echo(date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                if (date.getMonth() ==<?php echo(date('m', strtotime($reserva['fecha_reserva'])) - 1)?>) {

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


            <script type="application/javascript">
                $(document).ready(function () {
                    $('#salon4 #calendar4').datepicker({
                        format: "dd-mm-yyyy",
                        startDate: "now",
                        language: "es",
                        todayHighlight: true,
                        beforeShowDay: function (date) {
                            <?php foreach ($reservas4 as $reserva) : ?>
                            <?php if ($reserva['sucursal'] == "Campestre") :?>
                            if (date.getFullYear() == <?php echo(date('Y', strtotime($reserva['fecha_reserva']))) ?>) {
                                if (date.getMonth() ==<?php echo(date('m', strtotime($reserva['fecha_reserva'])) - 1)?>) {

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



            <div class="col-lg-6 col-xs-12 labelso">
                <?= $form->field($model, 'fecha_reserva')->widget(
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


                <?= $form->field($model, 'sucursal')->dropDownList(
                    ['Doble via a la guardia' => 'Doble via a la guardia',
                        'Garden' => 'Garden',
                        'Parque Urbano' => 'Parque Urbano',
                        //'Campestre' => 'Campestre',

                    ]
                ) ?>

                <?= $form->field($model, 'tipoevento')->dropDownList(
                    ['matrimonio' => 'Matrimonio',
                        'quinceanos' => 'Quince años',
                        'otros' => 'Otros',

                    ]
                ) ?>

                <?= $form->field($model, 'tipo_evento')->textInput(['maxlength' => true]) ?>



                <?= $form->field($model, 'hora_inicio')->widget(
                    \kartik\widgets\TimePicker::className(), [


                    // modify template for custom rendering
                ]); ?>
                <?= $form->field($model, 'paquete_reservar')->textInput(['maxlength' => true]) ?>


            </div>



            <div class="col-lg-6 col-xs-12 labelso">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'cant_garzones')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'limite_invitados')->input('number') ?>


            </div>
        </div>








<div class="col-xs-12 labelso">
    <div class="col-xs-12 ">
    <h3 class="coloh3">Equipamiento</h3> </div>


    <div class="col-md-6 col-xs-12">
        <?= $form->field($model, 'color_sillas')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'color_mantel')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6 col-xs-12">
    <?= $form->field($model, 'color_servilleta')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'en_mesa')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4 col-xs-12" style="
    margin-top: 10px;
">
    <?= $form->field($model, 'tipo_bebidas')->widget(CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'full',
        'clientOptions' => [
            'allowedContent' => true,
            'extraAllowedContent' => 'div',
        ]
    ]) ?>
    </div>

        <div class="col-md-4 col-xs-12" style="
    margin-top: 10px;
">
    <?= $form->field($model, 'vajillasyvasisenmesa')->widget(CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'full',
        'clientOptions' => [
            'allowedContent' => true,
            'extraAllowedContent' => 'div',
        ]
    ]) ?>
        </div>

            <div class="col-md-4 col-xs-12 " style="
    margin-top: 10px;
">
    <?= $form->field($model, 'katering')->widget(CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'full',
        'clientOptions' => [
            'allowedContent' => true,
            'extraAllowedContent' => 'div',
        ]
    ]) ?>
            </div>

</div>


        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Decoración</h3> </div>
                <div class="col-xs-12 ">
            <?= $form->field($model, 'nombre_decoradora')->textInput(['maxlength' => true]) ?>

                </div>
                <div class="col-md-4 col-xs-12 " style="
    margin-top: 10px;
">

            <?= $form->field($model, 'detalle_decoradora')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'full',
                'clientOptions' => [
                    'allowedContent' => true,
                    'extraAllowedContent' => 'div',
                ]
            ]) ?>

                </div>
                <div class="col-md-4 col-xs-12 " style="
    margin-top: 10px;
">
            <?= $form->field($model, 'detalle_decoracioniglesia')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'full',
                'clientOptions' => [
                    'allowedContent' => true,
                    'extraAllowedContent' => 'div',
                ]
            ]) ?>
                </div>
                    <div class="col-md-4 col-xs-12 " style="
    margin-top: 10px;
">

                <?= $form->field($model, 'otros_detalles')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full',
                    'clientOptions' => [
                        'allowedContent' => true,
                        'extraAllowedContent' => 'div',
                    ]
                ]) ?>
                    </div>


        </div>







        <div class="col-xs-12 labelso ">
            <div class="col-xs-12">
                <h3 class="coloh3">Coordinación</h3> </div>
            <div class="col-xs-12 col-md-6">   <?= $form->field($model, 'nombre_cordinadora')->textInput(['maxlength' => true]) ?>  </div>
            <div class="col-xs-12 col-md-6">          <?= $form->field($model, 'nombre_subcordinadora')->textInput(['maxlength' => true]) ?> </div>
            <div class="col-xs-12">              <?= $form->field($model, 'nombre_supervisora')->textInput(['maxlength' => true]) ?> </div>


            <div class="col-md-6 col-xs-12 " style="
    margin-top: 10px;
">


                <?= $form->field($model, 'file')->widget(\kartik\widgets\FileInput::classname(), [
                    'options' => [
                        'multiple' => false,
                        'accept' => '.pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx',
                    ],
                    'pluginOptions' => [
                        'initialPreviewFileType' => 'any', // Permite previsualizar cualquier tipo de archivo
                        'allowedFileExtensions' => [
                            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', // Archivos de oficina
                            //'jpg', 'png', 'gif', 'webp', 'jpeg'                 // Imágenes
                        ],
                        //'maxFileCount' => 10, // Puedes activarlo si deseas limitar el número de archivos
                        'initialPreview' => $initial,
                        'showRemove' => true,
                        'showUpload' => false,

                        'deleteUrl' => Yii::$app->urlManager->createUrl(['reservas/delete-file', 'id' => $model->idreservas, 'fileType' => 'cronograma']),
                        'language' => 'es'
                    ]
                ]); ?>




            </div>
            <div class="col-md-6 col-xs-12 " style="
    margin-top: 10px;
">
                <?= $form->field($model, 'file2')->widget(\kartik\widgets\FileInput::classname(), [
                    'options' => [
                        'multiple' => false,
                        'accept' => '.pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx',
                    ],
                    'pluginOptions' => [
                        'initialPreviewFileType' => 'any', // Permite previsualizar cualquier tipo de archivo
                        'allowedFileExtensions' => [
                            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', // Archivos de oficina
                           // 'jpg', 'png', 'gif', 'webp', 'jpeg'                 // Imágenes
                        ],
                        //'maxFileCount' => 10, // Puedes activarlo si deseas limitar el número de archivos
                        'initialPreview' => $initial2,
                        'showRemove' => true,
                        'showUpload' => false,

                        'deleteUrl' => Yii::$app->urlManager->createAbsoluteUrl([
                            'reservas/delete-file',
                            'id' => $model->idreservas,
                            'fileType' => 'listainvitados'
                        ]),
                        'language' => 'es'
                    ]
                ]); ?>

            </div>

            <div class=" col-xs-12 " style="
    margin-top: 10px;
">

                <?= $form->field($model, 'otrainfocoordinacion')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full',
                    'clientOptions' => [
                        'allowedContent' => true,
                        'extraAllowedContent' => 'div',
                    ]
                ]) ?>
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



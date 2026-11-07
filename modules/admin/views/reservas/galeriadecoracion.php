<?php

use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Productos */

$this->title = 'Galeria Decoracion';
$this->params['breadcrumbs'][] = ['label' => 'Reserva', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body ">
                    <p class="text-muted" style="margin-bottom:12px">
                        <i class="fa fa-info-circle"></i> Podés subir imágenes de hasta <b>15 MB</b> cada una.
                        Cuanto más pesada, más tarda en subir: <b>esperá a que aparezca "Hecho"</b> antes de salir.
                    </p>
                    <?php
                    $initial = [];
                    $config = [];
                    foreach ($galeriadecoracion as $item) {
                        array_push($initial, Html::img(\yii\helpers\Url::to('@web/imagen/reservasdecoracion/' . $item->archivo), ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:160px']));
                        array_push($config, ["previewAsData" => false, "caption" => $item->archivo, "url" => Url::to(['erasedecoracion']), "key" => $item->idgaleria]);
                    }
                    $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']]); // important
                    echo $form->field($model, 'file')->widget(FileInput::classname(), [
                        'options' => [
                            'multiple' => true,
                            'accept' => 'image/*',

                        ],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['reservas/uploaddecoracion']),
                            'uploadExtraData' => [
                                'id' => $id,
                            ],
                            'initialPreviewFileType' => 'image',
                            //'maxFileCount' => 10,
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                            'maxFileSize' => 15360, // 15 MB
                            'initialPreview' => $initial,
                            'initialPreviewConfig' => $config,
                            "language" => "es"
                        ],
                        // Al terminar de subir, recarga la pagina para mostrar las
                        // imagenes ya guardadas (con su boton de borrar).
                        'pluginEvents' => [
                            'filebatchuploadcomplete' => 'function() { window.location.reload(); }',
                        ],
                    ]);
                    ActiveForm::end();
                    ?>


                </div>
            </div>
        </div>

    </div>
</section>


<?php

use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Productos */

$this->title = 'Galeria Presupuesto';
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
                    <?php
                    $initial = [];
                    $config = [];
                    foreach ($galeriapresupuesto as $item) {
                        array_push($initial, Html::img(\yii\helpers\Url::to('@web/imagen/galeriapresupuesto/' . $item->archivo), ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:160px']));
                        array_push($config, ["previewAsData" => false, "caption" => $item->archivo, "url" => Url::to(['erasepresupuesto']), "key" => $item->idgaleria]);
                    }
                    $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']]); // important
                    echo $form->field($model, 'file')->widget(FileInput::classname(), [
                        'options' => [
                            'multiple' => true,
                            'accept' => 'image/*',

                        ],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['reservas/uploadpresupuesto']),
                            'uploadExtraData' => [
                                'id' => $id,
                            ],
                            'initialPreviewFileType' => 'image',
                            //'maxFileCount' => 10,
                            'allowedFileExtensions' => ['jpg', 'png', 'gif', 'webp', 'jpeg'],
                            'initialPreview' => $initial,
                            'initialPreviewConfig' => $config,
                            "language" => "es"
                        ]
                    ]);
                    ActiveForm::end();
                    print_r(Url::to(['reservas/uploadpresupuesto']));
                    ?>


                </div>
            </div>
        </div>

    </div>
</section>


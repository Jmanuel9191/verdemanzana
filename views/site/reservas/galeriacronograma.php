<?php

use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$user2 = Yii::$app->session->get('user2');
?>

<style>
    .btn-primary {
        color: #fff;
        background-color: #527f1a;
        border-color: #527f1a;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #527f1a;
        border-color: #527f1a;
    }


</style>


<?= $this->render('../banners/fijo',
    ['titulo' => 'Ficha del evento / '.$user2['nombre'],])

?>




<div class="container ">
    <br>
    <h2 class="txt-h2 text-center">Galeria Cronograma</h2>
    <div class="col-xs-12"><div class="separador-titulo center-block"></div></div>
    <br>

    <hr>

</div>


<div class="container">

    <div class="">

        <div class="row">

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!-- Main content -->
<div class="col-xs-12 col-lg-10 col-lg-offset-1">
    <?php
    $initial = [];
    $config = [];
    if (isset($galeria)) {
        foreach ($galeria as $item) {
            array_push($initial, Html::img(\yii\helpers\Url::to('@web/imagen/reservascronograma/' . $item->archivo), ['class' => 'kv-preview-data krajee-init-preview file-preview-image', 'style' => 'max-height:160px']));
            array_push($config, ["previewAsData" => false, "caption" => $item->archivo, "url" => Url::to(['borrar']), "key" => $item->idgaleria]);
        }
    }

    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']]); // important
    echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Sube tus imagenes (*.jpg)',

            'uploadUrl' => Url::to(['upload3']),
            'uploadExtraData' => [
                'id' => $id,
            ],
            'initialPreviewFileType' => 'image',
            'initialPreview' => $initial,
            'initialPreviewConfig' => $config,
              'maxFileCount' => 5,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'language' => 'es-ES'
        ],
        // Cuando terminan de subirse todas las fotos, recarga la página
        // para mostrarlas junto con las anteriores.
        'pluginEvents' => [
            'filebatchuploadcomplete' => 'function() { window.location.reload(); }',
        ],
    ])->label(false);
    ActiveForm::end();
    ?>
    <br><br>


    <div style="" class="col-xs-12" align="center">

       
        <br><br><br>

    </div>
</div>
        </div>
    </div>
</div>

<br><br>

<?php
$script = <<<JS
function galeria() {
  $.pjax.reload({container:'#galeria'}); //refresh the grid
}
JS;
//$this->registerJs($script, \yii\web\View::POS_HEAD)
?>

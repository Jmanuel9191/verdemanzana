<?php


$script = <<<CSS
@media (min-width:768px) and (max-width:776px )   {

    .container.cntw {
        width: 740px!important;}
}


@media (min-width:992px) and (max-width:997px )   {

    .container.cntw {
        width: 960px!important;}
}
CSS;

$this->registerCss($script, ['depends' => \app\assets\AppAsset::class]);
$data = new \app\models\Categoria();
?>


    <div class="page-header"><!-- page-header -->
        <div class="container">
            <div class="row page-section"><!-- page section -->
                <div class="col-md-12 page-description"><!-- page description -->
                    <h1 class="page-title"><?= $titulo ?>
                        <span class=" tam15 colortemp">
                        </span></h1>
                        <span class="subtit"> <?= $subtitulo ?></span>
                </div>
                <!-- page description -->
            </div>
            <!-- page section -->


        </div>




    </div>

<div class="subbaer" >
    <div class="container cntw">
    <span class="subtit text-right" style="float: right">Inicio &nbsp;/&nbsp; <span class="colortemp"><?= $titulo2 ?></span></span>
    </div>

</div>
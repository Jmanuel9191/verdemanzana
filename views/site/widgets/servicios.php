<?php

use app\assets_b\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;?>

<div class="container">
    <br>
    <div class="row text-center">
        <div class="col-md-1 col-sm-12">
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="servico-icono4 center-block"></div>
            <strong class="servicio-txt1">BODA</strong>
            <br>
            <a class="servicio-ver btn btn-link" href="<?= Url::to(['servicios?tab=1']) ?>">Ver Información <i class="glyphicon glyphicon-plus" style="color: #feac00"></i></a>
            <br>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="servico-icono3 center-block"></div>
            <strong class="servicio-txt1">15 AÑOS</strong>
            <br>
            <a class="servicio-ver btn btn-link" href="<?= Url::to(['servicios?tab=2']) ?>">Ver Información <i class="glyphicon glyphicon-plus" style="color: #feac00"></i></a>
            <br>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="servico-icono2 center-block"></div>
            <strong class="servicio-txt1">INFANTILES</strong>
            <br>
            <a class="servicio-ver btn btn-link" href="<?= Url::to(['servicios?tab=3']) ?>">Ver Información <i class="glyphicon glyphicon-plus" style="color: #feac00"></i></a>
            <br>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="servico-icono1 center-block"></div>
            <strong class="servicio-txt1">EVENTOS VARIOS</strong>
            <br>
            <a class="servicio-ver btn btn-link" href="<?= Url::to(['servicios?tab=4']) ?>">Ver Información <i class="glyphicon glyphicon-plus" style="color: #feac00"></i></a>
            <br>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="servico-icono5 center-block"></div>
            <strong class="servicio-txt1">CONFERENCIAS, SEMINARIOS y EVENTOS EMPRESARIALES </strong>

            <br>
            <a class="servicio-ver btn btn-link" href="<?= Url::to(['servicios?tab=5']) ?>">Ver Información <i class="glyphicon glyphicon-plus" style="color: #feac00"></i></a>
            <br>
        </div>   <div class="col-md-1 col-sm-12">
        </div>

    </div>
    <br>
</div>
<?php
/*
 * conpartir facebook
 */
$this->registerMetaTag(['name' => 'og:title', 'content' => $model['titulo_pagina']], 'face_titulo');
$this->registerMetaTag(['name' => 'og:description', 'content' => $model['meta_descripcion']], 'face_resumen');
$this->registerMetaTag(['name' => 'og:site_name', 'content' => $model['nombre_empresa']], 'face_empresa');
$this->registerMetaTag(['name' => 'og:url', 'content' => \yii\helpers\Url::home()], 'face_url');
/*
 * conpartir facebook
 */
$this->registerMetaTag(['name' => 'twitter:card', 'content' => "summary"], 'twi_card');
$this->registerMetaTag(['name' => 'twitter:title', 'content' => $model['titulo_pagina']], 'twi_titulo');
$this->registerMetaTag(['name' => 'twitter:description', 'content' => $model['meta_descripcion']], 'twi_resumen');
/*
 * detalle de pagina
 */
$this->registerMetaTag(['name' => 'description', 'content' => $model['meta_descripcion']], 'description');
//$this->registerMetaTag(['name' => 'robots', 'content' => $resumen], 'description');
$this->title = $model['titulo_pagina'];

if(isset($foto)) {
    $this->registerMetaTag(['name' => 'og:image', 'content' => $foto], 'face_foto');
    $this->registerMetaTag(['name' => 'twitter:image', 'content' => $foto], 'twi_foto');
}
else{
    $this->registerMetaTag(['name' => 'og:image', 'content' => \yii\helpers\Url::to('@web/assets_b/img/logo2.png')], 'face_foto');
    $this->registerMetaTag(['name' => 'twitter:image', 'content' => \yii\helpers\Url::to('@web/assets_b/img/logo2.png')], 'twi_foto');
}
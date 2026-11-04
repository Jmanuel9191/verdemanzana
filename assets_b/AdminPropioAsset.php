<?php

namespace app\assets_b;

use yii\web\AssetBundle;

/**
 * CSS y JS propios del panel admin, guardados en la carpeta assets_b del proyecto:
 *   assets_b/css/estiloadmin.css
 *   assets_b/js/adminjs.js
 *
 * Se cargan DESPUÉS de AdminLTE (depends), así tus estilos pueden pisar los de la plantilla.
 * Para agregar más archivos, sumalos a $css o $js.
 */


class AdminPropioAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot/assets_b';
    public $baseUrl  = '@web/assets_b';
    public $css = ['css/estiloadmin.css'];
    public $js  = ['js/adminjs.js'];
    public $depends = ['app\assets_b\AssetAdmin'];   // primero AdminLTE, después lo tuyo
}

<?php

namespace app\assets_b;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * CSS y JS propios del panel admin (carpeta assets_b del proyecto).
 *
 *  - css/adminstyle.css  : estilos generales del admin (antes se cargaba desde la URL de producción)
 *  - css/estiloadmin.css : estilos que antes estaban escritos dentro de las vistas
 *  - js/adminjs.js       : JavaScript que antes estaba escrito dentro de las vistas
 *
 * Se cargan DESPUÉS de AdminLTE (depends), así pueden pisar los estilos de la plantilla.
 * Se usa en modules/admin/views/layouts/main.php.
 */
class AdminPropioAsset extends AssetBundle
{
    // Carpeta real y su dirección web (se lee directo, no se copia a /assets)
    public $basePath = '@webroot/assets_b';
    public $baseUrl = '@web/assets_b';

    public $css = [
        'css/adminstyle.css',
        'css/estiloadmin.css',
    ];

    // En el <head>: los buscadores Select2 de Kartik usan la función format() de este
    // archivo apenas carga la página (jQuery ya está en el <head>, ver config/web.php)
    public $js = [
        ['js/adminjs.js', 'position' => View::POS_HEAD],
    ];

    public $depends = [
        'app\assets_b\AssetAdmin',
    ];
}

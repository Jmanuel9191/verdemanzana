<?php

namespace app\assets_b;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Login del admin: AdminLTE + adminjs.js (que activa iCheck en el formulario de login).
 * No carga los estilos propios, para que el login se vea igual que siempre.
 * Se usa en modules/admin/views/layouts/login.php.
 */
class AdminLoginAsset extends AssetBundle
{
    public $basePath = '@webroot/assets_b';
    public $baseUrl = '@web/assets_b';

    // En el <head>: los buscadores Select2 de Kartik usan la función format() de este
    // archivo apenas carga la página (jQuery ya está en el <head>, ver config/web.php)
    public $js = [
        ['js/adminjs.js', 'position' => View::POS_HEAD],
    ];

    public $depends = [
        'app\assets_b\AssetAdmin',
    ];
}

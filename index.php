<?php

/**
 * Entorno automático:
 *  - Windows (tu Laragon)      -> desarrollo: errores visibles + barra de debug
 *  - Linux (servidor cPanel)   -> producción: errores ocultos al visitante
 * Así no hay que acordarse de cambiar nada al subir los archivos.
 */
$esLocal = (PHP_OS_FAMILY === 'Windows');

// PHP 8.4 marca como "deprecated" sintaxis de librerías externas (Guzzle, Google).
// No son errores: el código funciona igual. Se ocultan como en producción.
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', $esLocal ? '1' : '0');

defined('YII_DEBUG') or define('YII_DEBUG', $esLocal);
defined('YII_ENV') or define('YII_ENV', $esLocal ? 'dev' : 'prod');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/config/web.php');

(new yii\web\Application($config))->run();
<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets_b;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/assets_b';
    public $css = [
        'css/bootstrap.min.css',
       // 'css/font-awesome.min.css',
        'fancybox/jquery.fancybox.css',

        'css/style.css',
        'css/fonts.css',
        'css/fonts.css',
        'css/styleHover.css',
        'css/bootstrap-datepicker3.min.css',
        'css/slide_revolution/style.css',
        'css/slide_revolution/preview.css',
        'css/slide_revolution/settings.css',
        'css/style.css',
        'fontawesome/css/fontawesome.css',
'fontawesome/css/solid.css',
'fontawesome/css/brands.css',



    ];
    public $js = [


        //'js/vendor/modernizr-2.8.3-respond-1.4.2.min.js',
        'js/bootstrap.min.js',
        'js/responsive-tabs.js',
        'fancybox/jquery.fancybox.pack.js',
        'js/slide_revolution/jquery.themepunch.plugins.min.js',
        'js/slide_revolution/jquery.themepunch.revolution.min.js',
        'js/jquery.bxslider.min.js',
        'js/owlcarousel/owl.carousel.js',
        'js/bootstrap-datepicker.min.js',
        'locales/bootstrap-datepicker.es.min.js',




    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        //'Zelenin\yii\SemanticUI\assets\SemanticUICSSAsset',
    ];
}
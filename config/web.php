<?php
$params = require(__DIR__ . '/params.php');
$config = [
    'id' => 'mymcms',
    'basePath' => dirname(__DIR__),
    'language' => 'es',
     'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => ['log'],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => [ 'position' => \yii\web\View::POS_HEAD ],
                ],
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'AIzaSyCZs9s2QxeXnQVsXmkR_KW1tDGb1XHEHO4',
                        'language' => 'es',
                        'version' => '3.1.18'
                    ]
                ]
            ],
        ],
                  'session' => [
            'timeout' => 72000,
            'cookieParams' => [
                'httponly' => true,
                'samesite' => 'Lax',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '19b21f5c06acb79ba2b1f73832fa91b4e44eaa70c7e139c012434e870c4d04ad',
            'enableCsrfValidation' => true, //  AGREGA ESTA LÍNEA
            //'baseUrl'=>'',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'class' => 'yii\caching\MemCache',
            //'useMemcached' => true,
        ],

            'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Organizadoras',
            'enableAutoLogin' => false,
            'loginUrl' => ['site/login'],
            'idParam' => '__id_organizadora',
            'authKeyParam' => '__authKey_organizadora',
            'returnUrlParam' => '__returnUrl_organizadora',
            'authTimeout' => 36000,                          // organizadora: 60 min sin actividad
            'authTimeoutParam' => '__expire_organizadora',
        ],

        'user2' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Reservas',
            'enableAutoLogin' => false,
            'loginUrl' => ['site/logueo'],
            'idParam' => '__id_cliente',
            'authKeyParam' => '__authKey_cliente',
            'returnUrlParam' => '__returnUrl_cliente',
            'authTimeout' => 36000,                          // cliente: 60 min sin actividad
            'authTimeoutParam' => '__expire_cliente',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
              'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // En local (Laragon) NO envía correos reales: los guarda como archivo
            // en runtime/mail/ para que puedas revisarlos sin molestar a nadie.
            'useFileTransport' => YII_ENV_DEV,
            // En el servidor envía con la función de correo del hosting,
            // igual que hacía la configuración anterior (sin usuario ni contraseña).
            'transport' => [
                'dsn' => 'native://default',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            //'scriptUrl'=>'/admin/index.php',
            //'class'=>'yii\web\UrlManager',
            //'baseUrl'=>'admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<action>'=>'site/<action>',
                '<action>/<id:\d+>'=>'site/<action>',


                // Regla para eliminar archivos
                'reservas/delete-file/<id:\d+>/<fileType:\w+>' => 'reservas/delete-file',
            ],
        ],


    



    ],

    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
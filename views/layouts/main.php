<?php

/* @var $this \yii\web\View */

/* @var $content string */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

$configuracion = \app\models\Configuracion::find()->one();
$script = <<<CSS
   
    
CSS;
//$this->registerCss($script, ['depends' => \app\assets\AppAsset::class]);
\app\assets_b\AppAsset::register($this);   // estilos y JS del sitio público


?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="es-ES">


<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <!--charset ?>">-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="<?= \yii\helpers\Url::to('@web/assets_b/img/icon.png') ?>"/>
    <?php $this->head() ?>
    
  <script type = 'text / javascript'> window.smartlook || (function (d) {var o = smartlook = function () {o.api.push (argumentos)}, h = d.getElementsByTagName ('head') [0]; var c = d.createElement ('script'); o.api = new Array (); c.async = true; c.type = 'text / javascript'; c.charset = 'utf-8' ; c.src = 'https: //rec.smartlook.com/recorder.js'; h.appendChild (c);}) (documento); smartlook ('init', '8d2836cb0061dd1a50dbbe35646aeec955a2efb3'); </script>
  
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-173215211-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-173215211-1');
</script>





<!-- Código de instalación Cliengo para eduardomontenegroribera@hotmail.com --> <script type="text/javascript">(function () { var ldk = document.createElement('script'); ldk.type = 'text/javascript'; ldk.async = true; ldk.src = 'https://s.cliengo.com/weboptimizer/5f1842ab59ff16002a1d2f78/5f1842ac59ff16002a1d2f7b.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ldk, s); })();</script>

</head>


<body>


<?php $this->beginBody() ?>



<div class=" head">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <ul class="list-unstyled list-inline" style="margin-top: 10px; margin-bottom: 10px;">
                    <li class="dsplblo hidden-xs">
                        <i class="fa-solid fa-location-dot fa-lg txt-rosa"></i>
                        Dirección: <?= $configuracion['direccion_empresa']; ?>
                    </li>
                </ul>
            </div>
            <div class="col-sm-6 col-xs-12">
                <ul class="list-unstyled list-inline pull-right pllle cosilale footerns" style="margin-top: 10px; margin-bottom: 10px;">
                    <li class="dsplblo hidden-xs">
                        <i class="fa-solid fa-envelope a-lg txt-rosa"></i>
                        <?= $configuracion['email']; ?>
                    </li>
                    <li class="mwe face ">
                        <a href="https://www.facebook.com/VerdeManzana.com.bo" target="_blank">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="mwe insta ">
                        <a href="https://www.instagram.com/verdemanzanabo" target="_blank">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </li>
                    <li class="mwe tiktok">
                        <a href="https://www.tiktok.com/@verdemanzanabo" target="_blank">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </li>
                </ul>



            </div>

            <div class="col-xs-12">


            <a class="visible-sm visible-xs hidden-md hidden-lg" href="<?= Url::home() ?>"><img src="<?= Url::to(['assets_b/img/logo.png'])?>" class="logo" style="margin: 0 auto; display: block;
    max-width: 100%;" alt=""></a>
            </div>

        </div>
    </div>
</div>





<div class="container-fluid menu">



    <nav class="navbar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    MENU
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <?php

                $categoria= \app\models\Categoria::findOne(['alias' => 'proveedores']);

                //$cat = \app\models\Categoria::findOne(['alias' => 'noticias']);
                $cats = $categoria->categorias;
                $items_p = [];

                foreach ($cats as $item) {

                    // array_push($items_p, ['label' => $item['nombre'], 'url' => Url::to([$categoria['alias'] .  '?cat=' .   $item['idcategoria']]), 'active' => ($item['idcategoria'] == Yii::$app->getRequest()->getQueryParam('cat')),]);
                    array_push($items_p, ['label' => $item['nombre'], 'url' => Url::to([$categoria['alias'],'cat'=>  $item['idcategoria']]), 'active' => ($item['idcategoria'] == Yii::$app->getRequest()->getQueryParam('id')),]);


                    // array_push($items_p, ['label' => $item['nombre'], 'url' => ['/site/noticias', 'alias' => $item['alias'] , 'cat' => $item['idcategoria']],  'options' => ['class' => ''],]);
                }


                // ---- Menús que cambian si la organizadora o el cliente iniciaron sesión ----
                $plantillaDesplegable = '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>';
                $plantillaSalir = '<a href="{url}" data-method="post">{label}</a>';

                $organizadoraLogueada = !Yii::$app->user->isGuest && !empty(Yii::$app->session->get('user'));
                $menuTeam = $organizadoraLogueada
                    ? [
                        'label' => 'Team Verde Manzana <span class="caret"></span>',
                        'url' => '#',
                        'options' => ['class' => 'dropdown'],
                        'template' => $plantillaDesplegable,
                        'items' => [
                            ['label' => 'Mi perfil', 'url' => ['/site/perfilorganizadora']],
                            ['label' => 'Cerrar sesión', 'url' => ['/site/logout'], 'template' => $plantillaSalir],
                        ],
                    ]
                    : ['label' => 'Team Verde Manzana', 'url' => ['/site/perfilorganizadora'], 'options' => ['class' => '']];

                $clienteLogueado = !Yii::$app->user2->isGuest;
                $menuMiEvento = $clienteLogueado
                    ? [
                        'label' => 'Mi evento <span class="caret"></span>',
                        'url' => '#',
                        'options' => ['class' => 'dropdown'],
                        'template' => $plantillaDesplegable,
                        'items' => [
                            ['label' => 'Ver mi evento', 'url' => ['/site/perfilreserva']],
                            ['label' => 'Cerrar sesión', 'url' => ['/site/logout2'], 'template' => $plantillaSalir],
                        ],
                    ]
                    : ['label' => 'Mi evento', 'url' => ['/site/perfilreserva'], 'options' => ['class' => '']];

                echo Menu::widget([
                    'options' => ['class' => 'nav navbar-nav', 'id' => 'menu',],
                    'activeCssClass' => 'nav-selected',
                    'encodeLabels' => false,
                    'lastItemCssClass' => '',
                    'submenuTemplate' => "\n<ul class=\" dropdown-menu \" >\n{items}\n</ul>\n",

                    'items' => [
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => ' hidden-xs'],],


                      ['label' => 'Inicio', 'url' => ['/site/index'], 'options' => ['class' => ''],],
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],

                        ['label' => 'Salones', 'url' => ['/site/salones'], 'options' => ['class' => ''],],
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],

                        //['label' => 'Cotizaciones', 'url' => ['/site/cotizaciones'], 'options' => ['class' => ''],],
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],
                        //['label' => 'Servicios', 'url' => ['/site/servicios'], 'options' => ['class' => ''],],

                        ['label' => '<img src="assets_b/img/logo.png" class="logover">  ', 'url' => ['/site/index'], 'options' => ['class' => 'hidden-xs hidden-sm logovera'],],
                        //['label' => 'Proveedores', 'url' => ['/site/proveedores'], 'options' => ['class' => ''],],
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],


                        //['label' => 'Promociones', 'url' => ['/site/promociones'], 'options' => ['class' => ''],],

                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],

                        $menuMiEvento,
                        ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],

                        $menuTeam,
                        //['label' => 'Noticias', 'url' => ['/site/noticias'], 'options' => ['class' => ''],],
                       // ['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],
                       // ['label' => 'Reservas', 'url' => ['/site/reservas'], 'options' => ['class' => ''],],
                        //['label' => '', 'url' => ['/site/index'], 'options' => ['class' => 'menu-separador hidden-xs'],],
                        //['label' => 'Contacto', 'url' => ['/site/contacto'], 'options' => ['class' => ''],],

                    ],
                ]);
                ?>



            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

</div>



    <!-- MENU -->





    <?= $content ?>


<div class="container-fluid footer">
    <div class="container">
        <div class="text-center">
            <br>
            2025 ©  Copyright VERDE MANZANA<br>
            Diseño y Desarrollo <a href="http://marcaymercado.com" target="_blank">Marca y Mercado</a>
            <div>

                <ul class="list-unstyled list-inline pllle cosilale footerns" style="margin-top: 12px; margin-bottom: 0px;">

                    <li class="mwe face ">
                        <a href="https://www.facebook.com/VerdeManzana.com.bo" target="_blank">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="mwe insta ">
                        <a href="https://www.instagram.com/verdemanzanabo" target="_blank">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </li>
                    <li class="mwe tiktok">
                        <a href="https://www.tiktok.com/@verdemanzanabo" target="_blank">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </li>
                </ul>


            </div>
            <br>
        </div>
    </div>
</div>



<div class="whatsapp-web">
        <a href="https://api.whatsapp.com/send?phone=59177094165&amp;text=Hola,%20por%20favor%20mas%20informacion" target="_blank">
            <i class="fa-brands fa-whatsapp"></i>
            <p>Escríbenos</p>
        </a>


</div>


<?php

$script = <<<JS

	
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

<!--

<?php
//$mensaje = Yii::$app->session->getFlash('mensaje');
//if (!empty($mensaje)): ?>
<?//= \kartik\widgets\Growl::widget([
//'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
//'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
//'body' => (!empty($message['message'])) ? \yii\helpers\Html::encode($message['message']) : 'Message Not Set!',
//'delay' => 1, //This delay is how long before the message shows
//'pluginOptions' => [
//'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
//'placement' => [
///    'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
//      'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'left',
//    ]
//  ]
//]); ?>
<?php //endif; ?>
-->



<?php foreach (Yii::$app->session->getAllFlashes() as $message): ?>
    <?php if (!empty($message['message'])): ?>
        <?= \yii2mod\alert\Alert::widget([
            'useSessionFlash' => false,
            'options' => [
                'type' => isset($message['type']) ? $message['type'] : 'error',
                'title' => Html::encode($message['message']),
                'animation' => 'slide-from-top',
            ],
        ]) ?>
    <?php endif; ?>
<?php endforeach; ?>



<?php $this->endBody() ?>


</body>

</html>
<?php $this->endPage() ?>

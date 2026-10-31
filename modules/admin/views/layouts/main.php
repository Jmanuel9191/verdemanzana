<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\app\assets_b\AssetAdmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://www.verdemanzana.com.bo/assets_b/css/adminstyle.css" rel="stylesheet">
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>M</b>&amp;<b>M</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>M</b>arca&amp;<b>M</b>ercado</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="http://placehold.it/160x160" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= Yii::$app->user->identity->usuario ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="http://placehold.it/160x160" class="img-circle" alt="User Image">
                                <p>
                                    <?= Yii::$app->user->identity->usuario ?>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="<?= \yii\helpers\Url::to(['default/logout']) ?>"
                                       class="btn btn-default btn-flat">Salir</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) --
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="http://placehold.it/45x45" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Marca&amp;Mercado</p>
                </div>
            </div>

            <!-- Sidebar Menu -->

            <?php
            $items = [
                ['label' => 'MENU', 'options' => ['class' => 'header']],
                ['label' => 'Home', "url" => ['default/index'], "icon" => "home"],
            ];
            $modulos = \app\models\Modulos::findAll(['estado'=>'1']);
            foreach ($modulos as $modulo)
            {
                array_push($items,["label" => $modulo->nombre, "url" => [$modulo->alias.'/index'], "icon" => $modulo->icono]);
            }
            ?>



            <?php  if (Yii::$app->user->identity->tipo_admin == '1'):  ?>

                <?=
                \dmstr\widgets\Menu::widget([
                    "items" => $items,/*[
                    //["label" => "Home", "url" => \yii\helpers\Url::home(), "icon" => "home"],
                    //["label" => "Productos", "url" => ['productos/index'], "icon" => "list"],
                    //["label" => "Categorias", "url" => ['categoria/index'], "icon" => "list"],
                    //["label" => "Banner", "url" => ['banner/index'], "icon" => "picture-o"],
                    //["label" => "Promociones", "url" => ['promociones/index'], "icon" => "user-plus"],
                    //["label" => "Tiendas", "url" => ['tiendas/index'], "icon" => "shopping-cart "],
                    //["label" => "Noticias", "url" => ['noticias/index'], "icon" => "newspaper-o"],
                    //["label" => "Contenido", "url" => ['contenido/index'], "icon" => "file-text-o"],
                    //["label" => "Clientes", "url" => ['clientes/index'], "icon" => "users"],
                    //["label" => "Archivos", "url" => ['archivos/index'], "icon" => "folder-open"],
                    //["label" => "Galerias", "url" => ['galeria/index'], "icon" => "picture-o"],
                    //["label" => "Videos", "url" => ['videos/index'], "icon" => "video-camera"],
                    //["label" => "Usuarios", "url" => ['usuarios/index'], "icon" => "user"],
                ],*/
                    'options' => ['class' => 'sidebar-menu']
                ])
                ?>

            <?php  elseif (Yii::$app->user->identity->tipo_admin == '2'):  ?>


                <style>
                    .sidebar-nav li {

                    }
                    .sidebar-nav li {
                        list-style: none;
                        line-height: 40px;
                    }
                </style>
                <?=
                \dmstr\widgets\Menu::widget([
                    "items" => [
                        ["label" => "Home", "url" => \yii\helpers\Url::home(), "icon" => "circle-o"],
                        ["label" => "Reservas", "url" => ['reservas/index'], "icon" => "circle-o"],
                        ["label" => "Cotizaciones", "url" => ['cotizacion/index'], "icon" => "circle-o"],


                        //["label" => "Categorias", "url" => ['categoria/index'], "icon" => "list"],
                        //["label" => "Banner", "url" => ['banner/index'], "icon" => "picture-o"],
                        //["label" => "Promociones", "url" => ['promociones/index'], "icon" => "user-plus"],
                        //["label" => "Tiendas", "url" => ['tiendas/index'], "icon" => "shopping-cart "],
                        //["label" => "Noticias", "url" => ['noticias/index'], "icon" => "newspaper-o"],
                        //["label" => "Contenido", "url" => ['contenido/index'], "icon" => "file-text-o"],
                        //["label" => "Clientes", "url" => ['clientes/index'], "icon" => "users"],
                        //["label" => "Archivos", "url" => ['archivos/index'], "icon" => "folder-open"],
                        //["label" => "Galerias", "url" => ['galeria/index'], "icon" => "picture-o"],
                        //["label" => "Videos", "url" => ['videos/index'], "icon" => "video-camera"],
                        //["label" => "Usuarios", "url" => ['usuarios/index'], "icon" => "user"],
                    ],
                    'options' => ['class' => 'sidebar-nav']
                ])
                ?>
            <?php endif; ?>










        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Main content -->
    <div class="content-wrapper">
        <?= $content ?>
    </div><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 5.0.0
    </div>
    <strong>Copyright &copy; <?= date("Y") ?> <a href="http://marcaymercado.com">Marca&amp;Mercado</a>.</strong> All rights
    reserved.
</footer>

</div><!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

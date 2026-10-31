<?php

$script = <<<CSS
CSS;
//$this->registerCss($script);
$configuracion = \app\models\Configuracion::find()->one();


?>

<?= $this->render('banners/fijo',


    ['about' => 'noticias',
        'titulo' => 'NOTICIAS',
        'descripcion' => 'Para nosotros es un placer poder, desde este medio, darle a conocer las últimas noticias, artículos y novedades del acontecer mundial relacionados al comercio exterior',
        'pagina' => 'noticias',])

?>









<section id="blog_post" class="blog_post">
    <div class="container">
        <div class="row">
            <div class="fleetdetails_area home1_main_blog blog_post_area blog_post_02_area sections">
                <div class="col-sm-8">
                    <!-- portfolio section -->
                    <div class="blog_post_content">

                        <?php
                        if (isset($all)) {
                            echo $this->render('noticias/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
                            echo $this->render('widgets/metatags', ['model' => $configuracion]);
                        } else {
                         if (!empty($model)) {
                                echo $this->render('noticias/item', ['model' => $model, 'categoria' => $categoria]);
                            }
                        }
                        ?>




                    </div><!-- End off section -->
                </div>

                <div class="col-sm-4">

                    <?= $this->render('noticias/sidebar') ?>




                    <div class="single_sidebar margin-top-40">
                        <div class="sidebar_head text-center textwhite">
                            <h6>Recent Posts</h6>
                            <div class="main-nav-border"></div>
                        </div>
                        <div class="sidebare_recent_post margin-top-20">
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 ">
                                    <div class="sidebare_recent_img">
                                        <img src="assets/images/recentpostimg1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-xs-9 col-sm-9">
                                    <div class="sidebare_recent_content">
                                        <p>The movie star the professor and ann here</p>
                                        <a href="">Mar 12 - 2016</a> <a href="" class="admin">Admin</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebare_recent_post margin-top-20">
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 ">
                                    <div class="sidebare_recent_img">
                                        <img src="assets/images/recentpostimg2.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-xs-9 col-sm-9">
                                    <div class="sidebare_recent_content">
                                        <p>The movie star the professor and ann here</p>
                                        <a href="">Mar 12 - 2016</a><a href="" class="admin">Admin</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebare_recent_post margin-top-20">
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 ">
                                    <div class="sidebare_recent_img">
                                        <img src="assets/images/recentpostimg3.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-xs-9 col-sm-9">
                                    <div class="sidebare_recent_content">
                                        <p>The movie star the professor and ann here</p>
                                        <a href="">Mar 12 - 2016</a><a href="" class="admin">Admin</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single_sidebar margin-top-40">
                        <div class="sidebar_head text-center textwhite">
                            <h6>Post Archives</h6>
                            <div class="main-nav-border"></div>
                        </div>
                        <div class="sidebare_post_archrive margin-top-20">

                            <ul>
                                <li><span class="text-left">April 2016</span> <span class="pull-right">22</span></li>
                                <li><span class="text-left">March 2016</span> <span class="pull-right">18</span></li>
                                <li><span class="text-left">February 2016</span> <span class="pull-right">16</span></li>
                                <li><span class="text-left">January 2016</span> <span class="pull-right">24</span></li>
                                <li><span class="text-left">December 2015</span> <span class="pull-right">26</span></li>
                            </ul>
                        </div>

                    </div>
                    <div class="single_sidebar margin-top-40">
                        <div class="sidebar_head text-center textwhite">
                            <h6>Tag Clouds</h6>
                            <div class="main-nav-border"></div>
                        </div>
                        <div class="sidebare_post_tag">
                            <a href="">Logistics</a>
                            <a href="">Road ways</a>
                            <a href="">Seaways Cargo</a>
                            <a href="">Air Cargo</a>
                            <a href="">Rail Transport</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>





























<!--


<div class="container" style="margin-bottom: 25px">

    <div class="row">
        <!-- Menu (licitaciones)


        <!-- Servicios
        <div class="col-md-9">
            <h3>
            </h3>

            <div class="post-list" id="postList" style="display: flex; flex-wrap: wrap">
                <?php
                if (isset($all)) {
                    echo $this->render('noticias/lista', ['model' => $model->getModels(), 'data' => $model, 'categoria' => $categoria]);
                    echo $this->render('widgets/metatags', ['model' => $configuracion]);
                } else {
                    if (count($model) > 0) {
                        echo $this->render('noticias/item', ['model' => $model, 'categoria' => $categoria]);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>








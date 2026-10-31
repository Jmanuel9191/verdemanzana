
<?php

use yii\helpers\Url;



$noticias = \app\models\Noticias::findAll(['destacado' => '1', 'estado' => '1', 'idcategoria' => ['3']]);

//$horass=date("H:i",strtotime($model['fecha_noticia']));

$script = <<<CSS

CSS;
//$this->registerCss($script, ['depends' => \app\assets\AppAsset::class]);



?>


<!-- Blog section -->
<section id="blog" class="blog blog_home3">
    <div class="container">
        <div class="row">
            <div class="main_blog main_blog_home3 sections">
                <div class="col-sm-12">
                    <div class="head_title text-center">
                        <h2>NUESTROS CLIENTES</h2>

                        <p>Aqui algunos de los clientes que confiaron en nuestra experiencia y profesionalismo.</p>
                        <div class="separator"></div>

                    </div>
                </div>



                <?php  foreach ($noticias as $item):

                $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                $dia = date("d", strtotime($item['fecha_noticia']));
                $mes = date("m", strtotime($item['fecha_noticia']));
                $año = date("Y", strtotime($item['fecha_noticia']));

                ?>

                <div class="col-sm-6 col-lg-3">
                    <div class="single_blog">
                        <div class="single_blog_img heimg" style=" BORDER: 1px solid #e6e6e6; padding: 1px;">

                            <a href="<?= Url::to(['cliente/' , 'id' => $item['idnoticia']]) ?>">
                                <img src="<?= Url::to($item['foto_portada']) ?>" alt=""  />

                            </a>
                        </div>
                        <div class="single_blog_text">
                            <div class="">

                                <div class="col-xs-12 no-padding" style="   background: #e6e6e6;   padding: 1px 5px!important; margin-bottom: 15px!important;">
                                    <div class="single_blog_text_left ">
                                        <p> <?= \yii\helpers\StringHelper::truncate($item['titulo'], 60) ?></p>
                                        <a href=""><i class="fa fa-list" aria-hidden="true" style="color: #157416;"></i> <?=$item->categoria['nombre']?></a>
                                        <a href=""><i class="fa fa-comments-o" aria-hidden="true" style="color: #157416;"></i>  Por:  <?=$item['fuente']?></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
                <center>   <a href="<?= Url::to('clientes') ?>" class="btn btn-default btn-default-black margin-top-20">VER TODAS </a></center>




            </div>

        </div>

    </div>
</section><!-- End off Blog section -->







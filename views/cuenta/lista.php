<?php
use yii\helpers\Url;

$cat="";


@$nombre = $_GET['cat'];


$categorias5 = \app\models\Categoria::findOne(['idcategoria' => $nombre]);


?>

<div class="container">
                    <!-- portfolio section -->
                    <div class="blog_post_content">

                        <div id="noticias">
                            <div class="row">




 <?php


if (isset($model)) :
        foreach ($model as $item):

            $meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
            $dia = date("d", strtotime($item['fecha_noticia']));
            $mes = date("m", strtotime($item['fecha_noticia']));
            $año = date("Y", strtotime($item['fecha_noticia']));

            $href = Url::to(['noticia' , 'id'=> $item['idnoticia'],'cat'=>$categoria['idcategoria']]); ?>

                                <div class="col-md-6">

                <div class="col-sm-4 col-xs-12">
                    <div class="view view-item">
                        <img src="<?= Url::to($item['foto_portada']); ?>" class="img-thumbnail"/>

                        <div class="mask">
                            <br>
                            <br>

                            <a href="<?= $href; ?>" class="info"><i class="glyphicon glyphicon-plus"></i></a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <h4 class="txt-h4" style="margin-bottom: 0"><?= \yii\helpers\StringHelper::truncate($item['titulo'], 25) ?></h4>
                    <span class="fecha-not" ><i class="glyphicon glyphicon-calendar" style="color: #f37d89"></i><?php echo date("d/m/Y",strtotime($item['fecha_noticia']));?></span>
                    <hr style="margin-bottom: 10px;margin-top: 10px;">
                    <!--<p><?//= \yii\helpers\StringHelper::truncate($item['resumen'], 100) ?></p>-->
                    <a class="btn btn-verde text-center" href="<?= $href; ?>">Ver Noticia</a>
                </div>

            <br>
                            <!-- End off single blog -->


                                </div>   
        <?php endforeach; ?>

        <div class="col-xs-12">
            <hr>
            <div class="text-center">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $data->pagination,
                ]); ?>
            </div>
        </div>
<?php else: ?>
    <p>Sin registros</p>

<?php endif; ?>




                            </div>

                        </div>


                    </div>
</div>
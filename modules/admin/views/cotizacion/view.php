<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
    $this->params['breadcrumbs'][] = ['label' => 'Cotizaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;





$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
$dia = date("d", strtotime($model->fecha_evento));
$mes = date("m", strtotime($model->fecha_evento));
$abo = date("Y", strtotime($model->fecha_evento));


?>





<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>




</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">


                          <div class="box-body ">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            //'idusuario',
                            'nombre',
                    
                            'telefono',
                            'email:email',
                            'tipo_evento',

                            //'fecha_evento',
                            'cantidad_invitados',
                            'sucursal',
                            'comentario',
                            'fecha_registro',

                           // 'contrasena',

                            //'movil',


                            //'estado',
                            //'sexo',
                            //'activo',
                        ],
                    ]) ?>

                              <table id="w0" class="table table-striped table-bordered detail-view"><tbody>
                                  <tr><th> Fecha del evento</th><td><?php echo  $dia ?> de <?php echo  $meses2[$mes * 1] ?>, <?php echo  $abo ?></td></tr>
                             </tbody></table>






                          </div>
            </div>
        </div>
</section>

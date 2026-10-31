<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\FormTextosEstaticos;
use app\models\DetalleCronograma;
use app\models\DetalleCronogramaExtra;
use app\models\FormLayoutOrden;

$meses2 = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$dia = date("d", strtotime($model['fecha_reserva']));
$mes = date("m", strtotime($model['fecha_reserva']));
$ano = date("Y", strtotime($model['fecha_reserva']));
$reco1 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '1'])
    ->andWhere(['visible' => 1])
    ->one();
$reco2 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '2'])
    ->andWhere(['visible' => 1])
    ->one();
$reco3 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '3'])
    ->andWhere(['visible' => 1])
    ->one();
$reco4 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Boda', 'orden' => '4'])
    ->andWhere(['visible' => 1])
    ->one();
$reco5 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '1'])
    ->andWhere(['visible' => 1])
    ->one();
$reco6 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '2'])
    ->andWhere(['visible' => 1])
    ->one();
$reco7 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '3'])
    ->andWhere(['visible' => 1])
    ->one();
$reco8 = FormTextosEstaticos::find()
    ->where(['grupo' => 'Recomendacion General Quinceanos', 'orden' => '4'])
    ->andWhere(['visible' => 1])
    ->one();

$nombreEvento = $model->tipoevento;

if ($nombreEvento == 'matrimonio') {
    $nombreEvento = 'Boda';
} elseif ($nombreEvento == 'quinceanos') {
    $nombreEvento = '15 <span class="anosnoeo">años</span>';
} elseif ($nombreEvento == 'otros') {
    $nombreEvento = $model->tipo_evento;
}


$tipoEvento = $model->tipoevento;
if ($tipoEvento == 'matrimonio') {
    $tipoEvento = 'boda';
} elseif ($tipoEvento != 'quinceanos') {
    $tipoEvento = 'quinceanos'; // los "otros" se tratan igual que quince
}


$cronograma = \app\models\CronogramaBoda::findOne(['idreserva' => $model->idreservas]);

$detallesFijos = array();
if ($cronograma) {
    $detallesFijos = DetalleCronograma::find()
        ->alias('d')
        ->joinWith(['momento m'])
        ->where(['d.idcronograma' => $cronograma->idcronograma])
        ->andWhere(['m.evento_tipo' => $tipoEvento])
        ->indexBy('idmomento')
        ->all();
}

$extras = DetalleCronogramaExtra::find()
    ->where(['idreserva' => $model->idreservas])
    ->indexBy('idcampo_extra')
    ->all();

$orden = FormLayoutOrden::find()
    ->where(['idreserva' => $model->idreservas, 'tipo_evento' => $tipoEvento])
    ->orderBy(['grupo' => SORT_ASC, 'orden' => SORT_ASC])
    ->all();

$agrupado = array();
$grupoOrden = array();

foreach ($orden as $item) {
    $fila = array(
        'tipo' => $item->tipo,
        'etiqueta' => $item->etiqueta,
        'hora_inicio' => null,
        'hora_fin' => null,
        'proveedor' => null,
        'telefono' => null,
        'descripcion' => null,
        'idmomento' => 99999
    );

    if ($item->tipo == 'fijo' && isset($detallesFijos[$item->campo])) {
        $d = $detallesFijos[$item->campo];
        $fila['hora_inicio'] = $d->hora_inicio;
        $fila['hora_fin'] = $d->hora_fin;
        $fila['proveedor'] = $d->proveedor;
        $fila['telefono'] = $d->telefono;
        $fila['descripcion'] = $d->descripcion;
        if ($d->momento) {
            $fila['etiqueta'] = $d->momento->momento;
            $fila['idmomento'] = $d->idmomento;
        }
    } elseif ($item->tipo == 'extra' && isset($extras[$item->campo])) {
        $e = $extras[$item->campo];
        $fila['etiqueta'] = $e->etiqueta;
        $fila['hora_inicio'] = $e->hora_inicio;
        $fila['hora_fin'] = $e->hora_fin;
        $fila['proveedor'] = $e->proveedor;
        $fila['telefono'] = $e->telefono;
        $fila['descripcion'] = $e->descripcion;
    }

    if (!isset($agrupado[$item->grupo])) {
        $agrupado[$item->grupo] = array();
        $grupoOrden[$item->grupo] = $fila['idmomento'];
    }

    if ($fila['idmomento'] < $grupoOrden[$item->grupo]) {
        $grupoOrden[$item->grupo] = $fila['idmomento'];
    }

    $agrupado[$item->grupo][] = $fila;
}

uksort($agrupado, function($a, $b) use ($grupoOrden) {
    return $grupoOrden[$a] - $grupoOrden[$b];
});

?>

<div class="detalledecoracion">
    <div class="">
        <div class="row">

            <div class="col-xs-12" style="padding: 0">


                <img src="<?= Url::to([$model['tipoevento'] == 'matrimonio' ? 'assets_b/img/flores.png' : 'assets_b/img/florquince.png']) ?>" class="flower-left">


                <div class="col-md-4 col-xs-12"></div>
                <div class="col-md-4 col-xs-12">
                    <h2>GUIA</h2>
                    <h1> Experiencia de <?= $nombreEvento ?>  <br> <span> Verde Manzana </span> </h1>
                    <p class="subriiles"
                        <?=$model['tipoevento'] == 'matrimonio' ? '' : 'style="padding-left: 5px"' ?>>Fecha de Evento : <?= $dia. ' de '.  $meses2[$mes * 1]. ', '.$ano  ?>
                        <br>
                        Nombre(s): <?= $model['nombre'] ?>
                        <br>
                        Sucursal: <?= $model['sucursal'] ?>
                        <br>
                        Tipo de paquete: <?= $model['paquete_reservar'] ?>
                        <br>
                        Cantidad de invitados: <?= $model['limite_invitados'] ?>
                        <br>

                    </p>
                </div>

                <div class="col-md-4 col-xs-12"></div>

                <img src="<?= Url::to([$model['tipoevento'] == 'matrimonio' ? 'assets_b/img/flores.png' : 'assets_b/img/florquince.png']) ?>" class="flower-right">


            </div>

            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-4 col-xs-12"></div>
            <div class="col-md-4 col-xs-12"> <div class="dashed-line"></div> </div>
                <div class="col-md-4 col-xs-12"></div>
            </div>


            <div class="col-xs-12" style="padding: 0">
            <div class="col-md-2 col-xs-12"></div>
            <div class="col-xs-12 col-md-8" >

                <div class="col-xs-12 genearlesp" style="padding: 0">


                <?php if (($model['tipoevento'] == 'matrimonio')): ?>
                    <p class="recoemandacikl" style="margin-top: 10px; font-size: 17px; padding-left: 0">
                        <?= $reco1->contenido_html ?>
                         <?= $reco2->contenido_html ?>
                    </p>
                <?php endif; ?>

                    <?php if (($model['tipoevento'] == 'quinceanos')): ?>
                        <p class="recoemandacikl" style="margin-top: 10px; font-size: 17px; padding-left: 0">
                            <?= $reco5->contenido_html ?>
                            <?= $reco6->contenido_html ?>
                        </p>
                    <?php endif; ?>

                </div>


            </div>

            <div class="col-md-2 col-xs-12"></div>

            </div>



            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-2 col-xs-12"></div>

                    <div class="col-xs-12 col-md-8" style="text-align: right">

                        <?php foreach ($agrupado as $grupo => $momentos): ?>
                            <p class="subiselm"><strong><?= Html::encode($grupo) ?></strong></p>

                            <?php foreach ($momentos as $m): ?>
                                <div class="row" style="margin-bottom: 8px;<?= $m['tipo'] == 'extra' ? '' : '' ?>">
                                    <div class="col-xs-12 text-left"><strong><?= ($m['tipo'] == 'extra' ? '' : '') . Html::encode($m['etiqueta']) ?></strong></div>

                                    <div class="col-xs12" style="padding: 0">
                                    <div class="col-sm-3 col-xs-12">
                                     <p style="padding-left: 0" class="fonoee"> <span style="font-weight: 600">Hora inicio: </span>  <?= Html::encode(substr($m['hora_inicio'], 0, 5)) ?>

                                         <br> <span style="font-weight: 600">Hora fin: </span> <?= Html::encode(substr($m['hora_fin'], 0, 5)) ?>

                                     </p>
                                    </div>

                                        <div class="col-sm-3 col-xs-12">
                                            <p style="padding-left: 0" class="fonoee"> <span style="font-weight: 600">Proveedor: </span>  <?= Html::encode($m['proveedor']) ?>
                                            </p>
                                        </div>


                                        <div class="col-sm-3 col-xs-12">
                                            <p style="padding-left: 0" class="fonoee"> <span style="font-weight: 600">Teléfono: </span><?= Html::encode($m['telefono']) ?>
                                            </p>
                                        </div>

                                        <div class="col-sm-3 col-xs-12">
                                            <p style="padding-left: 0" class="fonoee"> <span style="font-weight: 600">Descripción: </span><?= Html::encode($m['descripcion']) ?>
                                            </p>
                                        </div>

                                    </div>
                                    </div>


                            <?php endforeach ?>
                            <hr class="llem">
                        <?php endforeach ?>



            </div>
                <div class="col-md-2 col-xs-12"></div>
            </div>




            <div class="col-xs-12" style="padding: 0">
                <div class="col-md-4 col-xs-12"></div>
                <div class="col-md-4 col-xs-12"> <div class="dashed-line"></div> </div>
                <div class="col-md-4 col-xs-12"></div>
            </div>





            <div class="col-xs-12" style="padding: 0; ">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-xs-12 col-md-8" >

                    <div class="col-xs-12 genearlesp2 recoemandacikl" style="padding: 0">

                        <?php if (($model['tipoevento'] == 'matrimonio')): ?>

                            <p class="recoemandacikl" style="margin-top: 10px; line-height: 20px; font-size: 14px; padding-left: 0">
                                <?= $reco4->contenido_html ?>
                                <?= $reco3->contenido_html ?>
                            </p>
                        <?php endif; ?>
                        <?php if (($model['tipoevento'] == 'quinceanos')): ?>

                            <p class="recoemandacikl" style="margin-top: 10px; line-height: 20px; font-size: 14px; padding-left: 0">
                                <?= $reco8->contenido_html ?>

                                <?= $reco7->contenido_html ?>
                            </p>
                        <?php endif; ?>

                    </div>


                </div>

                <div class="col-md-2 col-xs-12"></div>

            </div>






            <div class="col-xs-12" style="
    margin-top: 10px;
">


                <img src="<?= Url::to(['assets_b/img/loggomanza.png'])?>" class="logomazn">
                <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downl">
                <img src="<?= Url::to(['assets_b/img/florabajos.png'])?>" class="flower-downr">
            </div>









        </div>
    </div>
</div>


<br>


<style>
    hr.llem {

        border-top: 1px solid #d2d2d2;
</style>







































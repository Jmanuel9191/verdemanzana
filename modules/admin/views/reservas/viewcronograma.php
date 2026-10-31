<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CronogramaBoda;
use app\models\DetalleCronograma;
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
use app\models\DetalleCronogramaExtra;
use app\models\FormLayoutOrden;
use app\models\MomentosCronograma;

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Cronograma', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$tipoEvento = $model->tipoevento;
if ($tipoEvento == 'matrimonio') {
    $tipoEvento = 'boda'; // unificamos nombre interno
}
$cronograma = CronogramaBoda::findOne(['idreserva' => $model->idreservas]);

$urlmans = ($model->tipoevento == 'matrimonio')
    ? ['form-campos-extra', 'idreserva' => $model->idreservas]
    : ['form-campos-extra-quince', 'idreserva' => $model->idreservas];
$url = $cronograma ? ['updatecronograma', 'id' => $model->idreservas] : ['createcronograma', 'id' => $model->idreservas];
$texto = $cronograma ? 'Editar Cronograma' : 'Crear Cronograma';
?>
<section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <style>
        .btn-secondary {
            color: #fff!important;
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 10px;
        }
        .btn-primary {
            color: #fff!important;
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px;
        }
        .btn-danger {
            background-color: #dd4b39;
            border-color: #d73925;
            padding: 10px;
        }
    </style>

</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <?= Html::a('Cambiar Contraseña', ['update2' , 'id' => $model->idreservas], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Editar datos generales', ['update', 'id' => $model->idreservas], ['class' => 'btn btn-secondary']) ?>
                    <?= Html::a($texto, $url, ['class' => 'btn btn-secondary']) ?>
                   <?= Html::a('Lista de invitados', ['invitados', 'idreservas' => $model->idreservas], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('VER GUIA(anterior)', ['viewguia', 'id' => $model->idreservas], ['class' => 'btn btn-primary']) ?>

                    <?= Html::a('➕ Añadir u Ordenar Campos', $urlmans, [
                        'class' => 'btn btn-secondary'
                    ]) ?>


                </div>

                <div class="box-body btnslinks">

                    <?php


                    if ($cronograma) {

                        $detallesFijos = \app\models\DetalleCronograma::find()
                            ->alias('d')
                            ->joinWith(['momento m'])
                            ->where(['d.idcronograma' => $cronograma->idcronograma])
                            ->andWhere(['m.evento_tipo' => $tipoEvento])
                            ->all();

                        $detallesFijosIndexados = array();
                        foreach ($detallesFijos as $detalle) {
                            $detallesFijosIndexados[$detalle->idmomento] = $detalle;
                        }

                        $extras = \app\models\DetalleCronogramaExtra::find()
                            ->where(['idreserva' => $model->idreservas])
                            ->indexBy('idcampo_extra')
                            ->all();

                        $orden = \app\models\FormLayoutOrden::find()
                            ->where(array(
                                'idreserva' => $model->idreservas,
                                'tipo_evento' => $tipoEvento,
                            ))
                            ->orderBy(['grupo' => SORT_ASC, 'orden' => SORT_ASC])
                            ->all();

                        $agrupadoPorGrupo = array();

                        foreach ($orden as $item) {
                            $fila = array(
                                'tipo' => $item->tipo,
                                'etiqueta' => $item->etiqueta,
                                'hora_inicio' => null,
                                'hora_fin' => null,
                                'proveedor' => null,
                                'telefono' => null,
                                'descripcion' => null,
                                'idmomento' => 99999, // por defecto alto para los extras
                            );

                            if ($item->tipo === 'fijo' && isset($detallesFijosIndexados[$item->campo])) {
                                $d = $detallesFijosIndexados[$item->campo];
                                $fila['hora_inicio'] = $d->hora_inicio;
                                $fila['hora_fin'] = $d->hora_fin;
                                $fila['proveedor'] = $d->proveedor;
                                $fila['telefono'] = $d->telefono;
                                $fila['descripcion'] = $d->descripcion;
                                if ($d->momento) {
                                    $fila['etiqueta'] = $d->momento->momento;
                                    $fila['idmomento'] = $d->idmomento;
                                }
                            } elseif ($item->tipo === 'extra' && isset($extras[$item->campo])) {
                                $e = $extras[$item->campo];
                                $fila['hora_inicio'] = $e->hora_inicio;
                                $fila['hora_fin'] = $e->hora_fin;
                                $fila['proveedor'] = $e->proveedor;
                                $fila['telefono'] = $e->telefono;
                                $fila['descripcion'] = $e->descripcion;
                            }

                            if (!isset($agrupadoPorGrupo[$item->grupo])) {
                                $agrupadoPorGrupo[$item->grupo] = array();
                            }

                            $agrupadoPorGrupo[$item->grupo][] = $fila;
                        }

                        // Ordenar las secciones según el menor idmomento dentro del grupo
                        $ordenSecciones = array();
                        foreach ($agrupadoPorGrupo as $grupo => $momentos) {
                            $minId = 99999;
                            foreach ($momentos as $m) {
                                if (isset($m['idmomento']) && $m['idmomento'] < $minId) {
                                    $minId = $m['idmomento'];
                                }
                            }
                            $ordenSecciones[$grupo] = $minId;
                        }

                        uksort($agrupadoPorGrupo, function($a, $b) use ($ordenSecciones) {
                            return $ordenSecciones[$a] - $ordenSecciones[$b];
                        });

                        // Mostrar la tabla
                        echo '<hr><h2>Detalles del Cronograma</h2>';

                        foreach ($agrupadoPorGrupo as $grupo => $momentos) {
                            echo "<h3>" . Html::encode($grupo) . "</h3>";
                            echo "<div class='table-responsive'><table class='table table-bordered table-striped'>";
                            echo "<thead><tr><th>Momento</th><th>Hora Inicio</th><th>Hora Fin</th><th>Proveedor</th><th>Teléfono</th><th>Descripción</th></tr></thead><tbody>";

                            foreach ($momentos as $m) {
                                $fondoExtra = ($m['tipo'] === 'extra') ? "style='background:#f9f9f9;color:#555;'" : "";
                                $icono = ($m['tipo'] === 'extra') ? "🆕 " : "";

                                echo "<tr $fondoExtra>";
                                echo "<td>" . $icono . Html::encode($m['etiqueta']) . "</td>";
                                echo "<td>" . Html::encode(isset($m['hora_inicio']) ? $m['hora_inicio'] : '—') . "</td>";
                                echo "<td>" . Html::encode(isset($m['hora_fin']) ? $m['hora_fin'] : '—') . "</td>";
                                echo "<td>" . Html::encode(isset($m['proveedor']) ? $m['proveedor'] : '—') . "</td>";
                                echo "<td>" . Html::encode(isset($m['telefono']) ? $m['telefono'] : '—') . "</td>";
                                echo "<td>" . Html::encode(isset($m['descripcion']) ? $m['descripcion'] : '—') . "</td>";
                                echo "</tr>";
                            }

                            echo "</tbody></table></div>";
                        }

                    } else {
                        echo "<p><strong>No se ha generado un cronograma para esta reserva.</strong></p>";
                    }
                    ?>



                </div>
            </div>
        </div>
</section>
<style>
    .btnslinks a {
        background: #69a322;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-bottom: 10px;
        font-weight: 600;
    }
</style>



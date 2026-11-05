<?php

use yii\helpers\Html;
use app\models\FormLayoutOrden;

// Obtener cronograma asociado a la reserva
$cronograma = \app\models\CronogramaBoda::findOne(['idreserva' => $reserva->idreservas]);

// Traer detalle de cronograma (momentos fijos)
$detalle = \app\models\DetalleCronograma::find()
    ->where(['idcronograma' => $cronograma ? $cronograma->idcronograma : 0])
    ->indexBy('idmomento')
    ->all();

// Agrupar momentos por sección con su primer idmomento
$seccionesOrdenadas = [];
foreach ($momentos as $m) {
    $seccion = $m->seccion;
    $seccionesOrdenadas[$seccion]['momentos'][] = $m;
    if (!isset($seccionesOrdenadas[$seccion]['primer_id'])) {
        $seccionesOrdenadas[$seccion]['primer_id'] = $m->idmomento;
    }
}
uksort($seccionesOrdenadas, function ($a, $b) use ($seccionesOrdenadas) {
    return $seccionesOrdenadas[$a]['primer_id'] - $seccionesOrdenadas[$b]['primer_id'];
});

// Agrupar campos extra por grupo
$camposExtras = \app\models\DetalleCronogramaExtra::find()
    ->where(['idreserva' => $reserva->idreservas])
    ->all();
$camposExtrasPorGrupo = [];
foreach ($camposExtras as $extra) {
    $camposExtrasPorGrupo[$extra->grupo][] = $extra;
}

// Cargar órdenes ya guardados
$ordenes = FormLayoutOrden::find()
    ->where(['idreserva' => $reserva->idreservas])
    ->indexBy(function ($row) {
        return $row->tipo === 'extra' ? 'extra_' . $row->campo : $row->campo;
    })
    ->all();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 labelso">
                <h3>Definir orden para campos fijos y dinámicos de la reserva (Evento: Quince años y otros)</h3>
                <?= Html::a('<i class="fa fa-arrow-left"></i> Volver al cronograma', ['reservas/viewcronograma', 'id' => $reserva->idreservas], ['class' => 'btn btn-default']) ?>
                <br>

                <?= Html::beginForm(['guardar-orden-campos-quince'], 'post') ?>
                <?= Html::hiddenInput('idreserva', $reserva->idreservas) ?>

                <?php foreach ($seccionesOrdenadas as $grupo => $datos): ?>
                    <?php
                    $momentosGrupo = [];

                    foreach ($datos['momentos'] as $m) {
                        $key = (string)$m->idmomento;
                        $orden = isset($ordenes[$key]) ? $ordenes[$key]->orden : $m->orden;
                        $momentosGrupo[] = [
                            'tipo' => 'fijo',
                            'orden' => $orden,
                            'momento' => $m,
                            'detalle' => isset($detalle[$m->idmomento]) ? $detalle[$m->idmomento] : null,
                        ];
                    }

                    if (!empty($camposExtrasPorGrupo[$grupo])) {
                        foreach ($camposExtrasPorGrupo[$grupo] as $e) {
                            $key = 'extra_' . $e->idcampo_extra;
                            $orden = isset($ordenes[$key]) ? $ordenes[$key]->orden : $e->orden;
                            $momentosGrupo[] = [
                                'tipo' => 'extra',
                                'orden' => $orden,
                                'momento' => $e,
                            ];
                        }
                    }

// Ordenar todo junto por orden
                    usort($momentosGrupo, function ($a, $b) {
                        return $a['orden'] - $b['orden'];
                    });



                    ?>

                    <div class="grupo-bloque">
                        <h4><strong><?= Html::encode($grupo) ?></strong></h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Momento</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                                <th>Proveedor</th>
                                <th>Teléfono</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($momentosGrupo as $item): ?>
                                <?php
                                $m = $item['momento'];
                                $tipo = $item['tipo'];
                                $key = ($tipo === 'extra') ? 'extra_' . $m->idcampo_extra : (string)$m->idmomento;
                                $orden = $item['orden'];

                                // Para fijos, el detalle puede no estar definido
                                $detalle = isset($item['detalle']) ? $item['detalle'] : null;

                                $horaInicio = ($tipo === 'fijo') ? ($detalle && isset($detalle->hora_inicio) ? $detalle->hora_inicio : '—') : (isset($m->hora_inicio) ? $m->hora_inicio : '—');
                                $horaFin    = ($tipo === 'fijo') ? ($detalle && isset($detalle->hora_fin) ? $detalle->hora_fin : '—') : (isset($m->hora_fin) ? $m->hora_fin : '—');
                                $proveedor  = ($tipo === 'fijo') ? ($detalle && isset($detalle->proveedor) ? $detalle->proveedor : '—') : (isset($m->proveedor) ? $m->proveedor : '—');
                                $telefono   = ($tipo === 'fijo') ? ($detalle && isset($detalle->telefono) ? $detalle->telefono : '—') : (isset($m->telefono) ? $m->telefono : '—');
                                $descripcion = ($tipo === 'fijo') ? ($detalle && isset($detalle->descripcion) ? $detalle->descripcion : '—') : (isset($m->descripcion) ? $m->descripcion : '—');
                                ?>
                                <tr>
                                    <td style="width: 50px;">
                                        <?= Html::textInput("orden[$key]", $orden, ['class' => 'form-control']) ?>
                                        <?= Html::hiddenInput("grupo[$key]", $grupo) ?>
                                        <?= Html::hiddenInput("tipo_evento[$key]", 'quinceanos') ?>
                                    </td>
                                    <td><?= Html::encode($tipo === 'fijo' ? $m->momento : $m->etiqueta) ?></td>
                                    <td><?= Html::encode($horaInicio) ?></td>
                                    <td><?= Html::encode($horaFin) ?></td>
                                    <td><?= Html::encode($proveedor) ?></td>
                                    <td><?= Html::encode($telefono) ?></td>
                                    <td><?= Html::encode($descripcion) ?></td>
                                    <td class="text-nowrap">
                                        <div style="display: flex; align-items: center;">
                                            <?= Html::textInput("tipo[$key]", $tipo, [
                                                'readonly' => true,
                                                'class' => 'form-control',
                                                'style' => 'width: 100px; margin-right: 10px;'
                                            ]) ?>
                                            <?php if ($tipo === 'extra'): ?>
                                                <?= Html::a('<i class="fa fa-trash"></i>', ['eliminar-campo-extra-quince', 'id' => $m->idcampo_extra, 'idreserva' => $reserva->idreservas], [
                                                    'style' => 'padding: 6px 10px; background-color: #dc3545; border: none; color: white; border-radius: 4px;',
                                                    'data' => [
                                                        'confirm' => '¿Eliminar este campo dinámico?',
                                                        'method' => 'post',
                                                    ],
                                                ]) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <?= Html::submitButton('💾 Guardar todos los órdenes', ['class' => 'btn btn-primary']) ?>
                </div>

                <?= Html::endForm() ?>

                <hr>

                <h4>➕ Añadir nuevo campo dinámico a una sección</h4>

                <?= Html::beginForm(['crear-campo-extra-quince'], 'post', ['class' => 'form-horizontal']) ?>
                <?= Html::hiddenInput('idreserva', $reserva->idreservas) ?>

                <div class="row" style="margin-bottom:15px">
                    <div class="col-sm-3">
                        <?php
                        $secciones = \app\models\MomentosCronograma::find()
                            ->select('seccion')
                            ->where(['evento_tipo' => 'quinceanos']) // solo si usás este campo
                            ->groupBy('seccion')
                            ->orderBy(['MIN(idmomento)' => SORT_ASC]) // orden cronológico
                            ->column();

                        ?>
                        <?= Html::dropDownList('grupo', null, array_combine($secciones, $secciones), [
                            'class' => 'form-control',
                            'prompt' => 'Seleccionar sección (grupo)...'
                        ]) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= Html::textInput('etiqueta', '', ['class' => 'form-control', 'placeholder' => 'Etiqueta del campo']) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= \kartik\widgets\TimePicker::widget([
                            'name' => 'hora_inicio',
                            'value' => '00:00', // Valor por defecto
                            'pluginOptions' => [
                                'showMeridian' => false
                            ],
                            'options' => [
                                'placeholder' => 'Hora inicio',
                                'class' => 'form-control'
                            ]
                        ]) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= \kartik\widgets\TimePicker::widget([
                            'name' => 'hora_fin',
                            'value' => '00:00', // Valor por defecto
                            'pluginOptions' => [
                                'showMeridian' => false
                            ],
                            'options' => [
                                'placeholder' => 'Hora Fin',
                                'class' => 'form-control'
                            ]
                        ]) ?>
                    </div>
                </div>

                <div class="row" style="margin-bottom:15px">
                    <div class="col-sm-3">
                        <?= Html::textInput('proveedor', '', ['class' => 'form-control', 'placeholder' => 'Proveedor']) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= Html::textInput('telefono', '', ['class' => 'form-control', 'placeholder' => 'Teléfono']) ?>
                    </div>

                    <div class="col-sm-4">
                        <?= Html::textarea('descripcion', '', ['class' => 'form-control', 'placeholder' => 'Descripción', 'rows' => 1]) ?>
                    </div>

                    <div class="col-sm-1">
                        <?= Html::textInput('orden', '', ['class' => 'form-control', 'placeholder' => 'Orden']) ?>
                    </div>

                    <div class="col-sm-1">
                        <?= Html::submitButton('Agregar', ['class' => 'btn btn-success btn-block']) ?>
                    </div>
                </div>

                <?= Html::endForm() ?>




            </div>
        </div>
    </div>
</div>


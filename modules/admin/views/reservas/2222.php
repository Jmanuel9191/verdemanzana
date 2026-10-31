<?php

use yii\helpers\Html;
use app\models\FormLayoutOrden;
use app\models\FormCamposExtra;
use app\components\CamposReservaHelper;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 labelso">
                <h3>Definir orden para campos fijos y dinámicos de la reserva</h3>

                <?php
                // Estructura base fija
                $camposPorGrupo = CamposReservaHelper::getCamposPorGrupo();

                // Campos dinámicos existentes
                $camposExtras = FormCamposExtra::find()->where(['idreserva' => $reserva->idreservas])->all();
                $camposExtrasPorGrupo = [];
                foreach ($camposExtras as $extra) {
                    $camposExtrasPorGrupo[$extra->grupo][] = $extra;
                }

                // Órdenes guardados
                $ordenesGuardados = FormLayoutOrden::find()
                    ->where(['activo' => 1])
                    ->indexBy(function ($item) {
                        return $item->tipo === 'fijo' ? $item->campo : 'extra_' . $item->campo;
                    })
                    ->all();
                ?>

                <?= Html::beginForm(['guardar-orden-campos'], 'post') ?>
                <?= Html::hiddenInput('idreserva', $reserva->idreservas) ?>

                <?php foreach ($camposPorGrupo as $grupo => $campos): ?>
                    <div class="grupo-bloque">
                        <h4><strong><?= Html::encode($grupo) ?></strong></h4>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Campo</th>
                                <th>Grupo</th>
                                <th>Valor actual</th> <!-- NUEVA COLUMNA -->
                                <th>Tipo</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($campos as $item):
                                $campo = $item['campo'];
                                $orden = isset($ordenesGuardados[$campo]) ? $ordenesGuardados[$campo]->orden : $item['orden'];
                                $valorActual = Html::encode(isset($reserva->$campo) ? $reserva->$campo : ''); // Campo fijo

                                ?>
                                <tr>
                                    <td style="width: 80px;"><?= Html::textInput("orden[$campo]", $orden, ['class' => 'form-control']) ?></td>

                                    <td><?= Html::encode($item['label']) ?></td>
                                    <td><?= Html::hiddenInput("grupo[$campo]", $grupo) ?><?= Html::encode($grupo) ?></td>
                                    <td><?= $valorActual ?></td> <!-- Muestra valor desde la reserva -->
                                    <td><?= Html::textInput("tipo[$campo]", 'fijo', ['readonly' => true, 'class' => 'form-control']) ?></td>

                               </tr>
                            <?php endforeach; ?>

                            <?php if (!empty($camposExtrasPorGrupo[$grupo])): ?>
                                <?php foreach ($camposExtrasPorGrupo[$grupo] as $extra):
                                    $key = 'extra_' . $extra->idcampo_extra;
                                    $orden = isset($ordenesGuardados[$key]) ? $ordenesGuardados[$key]->orden : $extra->orden;
                                    ?>
                                    <tr>
                                        <td style="width: 80px;"><?= Html::textInput("orden[$key]", $orden, ['class' => 'form-control']) ?></td>

                                        <td><?= Html::encode($extra->etiqueta.'('. $extra->cantidadestrellas.')') ?></td>
                                        <td><?= Html::hiddenInput("grupo[$key]", $grupo) ?><?= Html::encode($grupo) ?></td>
                                        <td><?= Html::encode($extra->valor) ?></td> <!-- Muestra valor de campo extra -->
                                        <td class="text-nowrap">
                                            <?= Html::textInput("tipo[$key]", 'extra', ['readonly' => true, 'class' => 'form-control', 'style' => 'display:inline-block; width:auto']) ?>

                                            <?= Html::a('<i class="fa fa-trash"></i>', ['eliminar-campo-extra', 'id' => $extra->idcampo_extra, 'idreserva' => $reserva->idreservas], [
                                                'class' => 'btn btn-danger btn-sm',
                                                'title' => 'Eliminar',
                                                'style' => 'margin-left:5px',
                                                'data' => [
                                                    'confirm' => '¿Estás seguro de eliminar este campo?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <?= Html::submitButton('💾 Guardar todos los órdenes', ['class' => 'btn btn-primary btn-']) ?>
                </div>

                <?= Html::endForm() ?> <!-- FIN FORM PRINCIPAL -->

                <hr>

                <!-- FORMULARIOS PARA AGREGAR CAMPOS DINÁMICOS -->
                <h4>➕ Añadir nuevo campo dinámico</h4>

                <?= Html::beginForm(['crear-campo-extra'], 'post', ['class' => 'form-inline']) ?>
                <?= Html::hiddenInput('idreserva', $reserva->idreservas) ?>

                <div class="row" style="margin-bottom:20px">

                    <div class="col-sm-3">
                        <?= Html::dropDownList('grupo', null, array_combine(array_keys($camposPorGrupo), array_keys($camposPorGrupo)), [
                            'class' => 'form-control',
                            'prompt' => 'Seleccionar grupo...'
                        ]) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= Html::textInput('etiqueta', '', ['class' => 'form-control', 'placeholder' => 'Nombre del campo']) ?>
                    </div>

                    <div class="col-sm-2">
                        <?= Html::textInput('valor', '', ['class' => 'form-control', 'placeholder' => 'Valor por defecto']) ?>
                    </div>

                    <div class="col-sm-2">
                        <?= Html::textInput('cantidadestrellas', '', ['class' => 'form-control', 'placeholder' => 'Estrellas']) ?>
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

<style>
    .grupo-bloque {
        margin-bottom: 40px;
        padding: 20px;
        background: #fdfdfd;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    .form-inline .form-control {
        width: 100%;
        margin-bottom: 10px;
    }

    .form-control[readonly]{
        width: auto!important;
    }
</style>

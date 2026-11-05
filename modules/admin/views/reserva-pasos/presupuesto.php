<?php

use app\models\PresupuestoForm;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\time\TimePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form PresupuestoForm */
/* @var $historial app\models\ReservaCotizacionHistorial[] */

$reserva = $form->reserva;
$esNueva = $reserva->isNewRecord;
$paquete = $form->paquete;
$historial = $historial ?? [];

// Qué se muestra según la pantalla:
//  crear       → cliente + presupuesto editable
//  datos       → cliente editable + presupuesto solo para ver (con botón "Modificar presupuesto")
//  presupuesto → solo el presupuesto editable (pantalla "Modificar presupuesto")
$modo = $form->modo;
$verCliente = $modo !== PresupuestoForm::MODO_PRESUPUESTO;
$editarLineas = $modo !== PresupuestoForm::MODO_DATOS;
$esModificar = $modo === PresupuestoForm::MODO_PRESUPUESTO;

if ($esNueva) {
    $this->title = 'Nueva reserva';
} elseif ($esModificar) {
    $this->title = 'Modificar presupuesto: ' . $reserva->nombre;
} else {
    $this->title = 'Reserva: ' . $reserva->nombre;
}
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
if ($esModificar) {
    $this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['presupuesto', 'id' => $reserva->idreservas]];
    $this->params['breadcrumbs'][] = 'Modificar presupuesto';
} else {
    $this->params['breadcrumbs'][] = $this->title;
}

$catalogoPaquetes = PresupuestoForm::catalogoPaquetes();
$catalogoItems = PresupuestoForm::catalogoItems();

// Si el paquete guardado ya no está activo en el catálogo, se agrega igual para poder mostrarlo
if ($paquete !== null && !isset($catalogoPaquetes[(int)$paquete->idorigen])) {
    $catalogoPaquetes[(int)$paquete->idorigen] = [
        'nombre' => $paquete->nombre . ' (inactivo)', 'detalle' => (string)$paquete->detalle,
        'precio' => (string)$paquete->precio_unitario, 'invitados' => null, 'horas' => null, 'tipoEvento' => 'todos',
    ];
}

$num = function ($v) {
    return number_format((float)$v, 2, ',', '.');
};
$pct = function ($v) {
    $f = round((float)$v, 2);
    return floor($f) == $f ? (string)(int)$f : rtrim(rtrim(number_format($f, 2, ',', ''), '0'), ',');
};
$cant = function ($v) {
    $f = (float)$v;
    return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '');
};

// Totales iniciales para el resumen
$totalInicial = '0';
$tmp = new \app\models\ReservaCotizacion();
$lineas = $form->todasLasLineas();
foreach ($lineas as $l) {
    $l->calcular();
}
$tmp->recalcularTotales($lineas);
$pagado = $esNueva ? '0' : $form->cotizacion->getTotalPagado();

$errores = array_merge($form->getErrors('paquete'), $form->getErrors('extras'), $form->getErrors('archivo'),
    $form->getErrors('reserva'), $form->getErrors('motivo'));
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content reserva-pasos">

    <?php foreach (['success' => 'success', 'error' => 'danger'] as $tipoFlash => $clase): ?>
        <?php if (Yii::$app->session->hasFlash($tipoFlash)): ?>
            <div class="alert alert-<?= $clase ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= Html::encode(Yii::$app->session->getFlash($tipoFlash)) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($errores): ?>
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:18px">
                <?php foreach ($errores as $e): ?>
                    <li><?= Html::encode($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->render('_resumen', [
        'paquete'   => $paquete ? $paquete->nombre : '',
        'invitados' => $paquete ? $cant($paquete->cantidad) : '',
        'fecha'     => $reserva->fecha_reserva,
        'sucursal'  => $reserva->sucursal,
        'total'     => $tmp->total,
        'pagado'    => $pagado,
    ]) ?>

    <?= $this->render('_pasos', ['actual' => 'presupuesto', 'idreservas' => $reserva->idreservas]) ?>

    <?php $af = ActiveForm::begin([
        'id' => 'form-presupuesto',
        'options' => [
            'enctype' => 'multipart/form-data',
            // Datos para assets_b/js/adminjs.js (catálogos, fechas ocupadas, lo pagado)
            'data' => ['config' => [
                'paquetes'        => $catalogoPaquetes,
                'items'           => $catalogoItems,
                'ocupadas'        => $form->fechasOcupadas(),
                'pagado'          => (float)$pagado,
                'siguienteIndice' => count($form->extras) + 1000,
                'tipoEvento'      => (string)$reserva->tipoevento,
            ]],
        ],
    ]); ?>

    <div class="box box-<?= $esModificar ? 'warning' : 'success' ?> caja-paso">

        <?php if ($esModificar): ?>
            <div class="box-body">
                <div class="alert alert-warning aviso-modificar">
                    <i class="fa fa-info-circle"></i>
                    Estás modificando el presupuesto de una reserva ya creada. Al guardar, el cambio queda registrado en el historial
                    (qué se agregó, quitó o cambió, el total anterior y el nuevo, quién y cuándo).
                </div>
            </div>
        <?php endif; ?>

        <?php if ($verCliente): ?>
        <!-- ===================== DATOS DEL CLIENTE Y DEL EVENTO ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"> Cliente y evento</h3>

            <div class="row calendarios">
                <?php $k = 0;
                foreach (PresupuestoForm::SUCURSALES as $suc): ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <h4><?= Html::encode($suc) ?></h4>
                        <div class="calendario-sucursal" data-sucursal="<?= Html::encode($suc) ?>" id="cal-<?= $k++ ?>"></div>
                    </div>
                <?php endforeach; ?>
                <div class="col-xs-12">
                    <small class="text-muted">
                        <span class="muestra-ocupado"></span> Fecha ocupada.
                        Hacé clic en un día de un calendario para elegir fecha y sucursal.
                    </small>
                    <div id="aviso-fecha" class="alert alert-warning" style="display:none;margin-top:10px"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $af->field($reserva, 'fecha_reserva')->widget(DatePicker::class, [
                        'language' => 'es',
                        'options' => ['placeholder' => 'Elegí la fecha', 'autocomplete' => 'off'],
                        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd', 'todayHighlight' => true],
                    ])->label('Fecha de reserva') ?>

                    <?= $af->field($reserva, 'sucursal')->dropDownList(PresupuestoForm::SUCURSALES)->label('Sucursal') ?>

                    <?= $af->field($reserva, 'hora_inicio')->widget(TimePicker::class, [
                        'pluginOptions' => ['showMeridian' => true, 'minuteStep' => 15, 'defaultTime' => '06:00 PM'],
                    ])->label('Hora de inicio') ?>

                    <?= $af->field($reserva, 'tipoevento')->dropDownList(PresupuestoForm::TIPOS_EVENTO)
                        ->label('Tipo de evento') ?>

                    <?= $af->field($reserva, 'tipo_evento')->textInput(['maxlength' => true, 'placeholder' => 'Ej. Boda de Ana y Luis'])
                        ->label('Nombre del evento') ?>
                </div>
                <div class="col-md-6">
                    <?= $af->field($reserva, 'nombre')->textInput(['maxlength' => true])->label('Nombre(s) del cliente') ?>
                    <?= $af->field($reserva, 'telefono')->textInput(['maxlength' => 20, 'inputmode' => 'numeric', 'placeholder' => 'Solo números, ej. 70012345'])->label('Teléfono') ?>
                    <?= $af->field($reserva, 'email')->textInput(['maxlength' => 250, 'autocomplete' => 'off', 'placeholder' => 'Ej. ana.luis@gmail.com o analuis2027'])
                        ->label('Email o usuario para iniciar sesión')->hint('Es el usuario del cliente: puede ser un email o un nombre sin @. Sin espacios. No se puede repetir.') ?>

                    <?php if ($esNueva): ?>
                        <?= $this->render('_campo_contrasena', [
                            'nombre'   => 'PresupuestoForm[contrasena]',
                            'etiqueta' => 'Contraseña del cliente',
                            'error'    => $form->getFirstError('contrasena'),
                            'valor'    => $form->hasErrors() ? $form->contrasena : '',
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php endif; /* $verCliente */ ?>

        <?php if (!$editarLineas): ?>
            <?= $this->render('_presupuesto_lectura', ['form' => $form, 'pagado' => $pagado]) ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->render('_archivo_presupuesto', ['reserva' => $reserva]) ?>
                    </div>
                </div>
            </div>
        <?php else: ?>

        <!-- ===================== PAQUETE ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"> Paquete</h3>

            <div class="table-responsive">
                <table class="table table-bordered tabla-lineas" id="tabla-paquete">
                    <thead>
                    <tr>
                        <th>Paquete</th>
                        <th class="col-num">Cant.</th>
                        <th class="col-num">Precio</th>
                        <th class="col-num">Subtotal</th>
                        <th class="col-pct">Desc. %</th>
                        <th class="col-num">Desc. monto</th>
                        <th class="col-num">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="fila-linea fila-paquete">
                        <td class="col-desc">
                            <select name="Paquete[idorigen]" id="sel-paquete" class="form-control input-sm">
                                <option value="">— Elegí un paquete —</option>
                                <?php foreach ($catalogoPaquetes as $id => $p): ?>
                                    <option value="<?= (int)$id ?>" data-tipo-evento="<?= Html::encode($p['tipoEvento']) ?>"
                                        <?= $paquete && (int)$paquete->idorigen === (int)$id ? 'selected' : '' ?>>
                                        <?= Html::encode($p['nombre']) ?> — Bs <?= $num($p['precio']) ?> / persona
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="col-num">
                            <input type="text" name="Paquete[cantidad]" class="form-control input-sm num campo-cantidad" inputmode="decimal"
                                   value="<?= $paquete ? Html::encode($cant($paquete->cantidad)) : '' ?>">
                            <small class="text-muted unidad-linea">invitados</small>
                        </td>
                        <td class="col-num">
                            <input type="text" name="Paquete[precio_unitario]" class="form-control input-sm num campo-precio" inputmode="decimal"
                                   value="<?= $paquete ? Html::encode($num($paquete->precio_unitario)) : '' ?>">
                            <small class="text-muted">por persona</small>
                        </td>
                        <td class="col-num col-subtotal">
                            <span class="campo-subtotal"><?= $paquete ? $num($paquete->subtotal) : '0,00' ?></span>
                        </td>
                        <td class="col-pct">
                            <div class="input-group input-group-sm">
                                <input type="text" name="Paquete[descuento_porcentaje]" class="form-control num campo-pct" inputmode="decimal"
                                       value="<?= $paquete ? Html::encode($pct($paquete->descuento_porcentaje)) : '0' ?>">
                                <span class="input-group-addon">%</span>
                            </div>
                        </td>
                        <td class="col-num">
                            <input type="text" name="Paquete[descuento_monto]" class="form-control input-sm num campo-desc" inputmode="decimal"
                                   value="<?= $paquete ? Html::encode($num($paquete->descuento_monto)) : '0,00' ?>">
                        </td>
                        <td class="col-num">
                            <input type="text" name="Paquete[total]" class="form-control input-sm num campo-total" inputmode="decimal"
                                   value="<?= $paquete ? Html::encode($num($paquete->total)) : '0,00' ?>">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div id="detalle-paquete" class="detalle-paquete" style="<?= $paquete ? '' : 'display:none' ?>">
                <div id="detalle-paquete-info" class="text-muted"></div>
                <div id="detalle-paquete-html"><?= $paquete ? \yii\helpers\HtmlPurifier::process((string)$paquete->detalle) : '' ?></div>
            </div>
        </div>

        <!-- ===================== PROVEEDORES / ÍTEMS ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion">
                 Proveedores / Ítems
                <button type="button" class="btn btn-success btn-sm pull-right" id="btn-agregar-linea">
                    <i class="fa fa-plus"></i> Agregar
                </button>
            </h3>

            <div class="table-responsive">
                <table class="table table-bordered tabla-lineas" id="tabla-extras">
                    <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="col-num">Cant.</th>
                        <th class="col-num">Precio</th>
                        <th class="col-num">Subtotal</th>
                        <th class="col-pct">Desc. %</th>
                        <th class="col-num">Desc. monto</th>
                        <th class="col-num">Total</th>
                        <th class="col-accion"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($form->extras as $i => $linea): ?>
                        <?= $this->render('_linea', ['linea' => $linea, 'indice' => $i, 'catalogoItems' => $catalogoItems]) ?>
                    <?php endforeach; ?>
                    <tr class="fila-vacia" style="<?= $form->extras ? 'display:none' : '' ?>">
                        <td colspan="8" class="text-center text-muted">Sin proveedores ni ítems. Usá el botón <b>Agregar</b>.</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <template id="plantilla-linea">
                <?= $this->render('_linea', ['linea' => null, 'indice' => '__i__', 'catalogoItems' => $catalogoItems]) ?>
            </template>
        </div>

        <!-- ===================== TOTALES ===================== -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $af->field($form, 'observaciones')->textarea(['rows' => 4, 'placeholder' => 'Condiciones, aclaraciones, validez del presupuesto...']) ?>

                    <?php if ($esModificar): ?>
                        <?= $af->field($form, 'motivo')->textarea(['rows' => 2, 'maxlength' => 500,
                            'placeholder' => 'Opcional. Ej. El cliente pidió 10 invitados más.'])
                            ->hint('Opcional. Queda guardado en el historial junto con los cambios.') ?>
                    <?php else: ?>
                        <?= $this->render('_archivo_presupuesto', ['reserva' => $reserva]) ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <table class="table tabla-totales">
                        <tr><th>Subtotal</th><td id="tot-subtotal">Bs <?= $num($tmp->subtotal) ?></td></tr>
                        <tr><th>Descuento total</th><td id="tot-descuento">− Bs <?= $num($tmp->descuento_total) ?></td></tr>
                        <tr class="fila-total"><th>TOTAL</th><td id="tot-total">Bs <?= $num($tmp->total) ?></td></tr>
                        <?php if (!$esNueva): ?>
                            <tr><th>Cancelado a la fecha</th><td>Bs <?= $num($pagado) ?></td></tr>
                            <tr><th>Saldo</th><td id="tot-saldo">Bs <?= $num((float)$tmp->total - (float)$pagado) ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <?php endif; /* $editarLineas */ ?>

        <?php if (!$esNueva): ?>
            <?= $this->render('_historial', ['historial' => $historial, 'cotizacion' => $form->cotizacion]) ?>
        <?php endif; ?>

        <div class="box-footer">
            <?php
            if ($esNueva) {
                $textoBoton = 'Guardar presupuesto y crear reserva';
            } elseif ($esModificar) {
                $textoBoton = 'Guardar cambios del presupuesto';
            } else {
                $textoBoton = 'Guardar datos de la reserva';
            }
            ?>
            <?= Html::submitButton(' ' . $textoBoton, [
                'class' => 'btn btn-' . ($esModificar ? 'warning' : 'success') . ' btn-lg',
            ]) ?>
            <?= Html::a('Cancelar', $esModificar ? ['presupuesto', 'id' => $reserva->idreservas] : ['reservas/index'],
                ['class' => 'btn btn-default btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</section>

<?php
// La lógica de la pantalla está en assets_b/js/adminjs.js (VM.iniciarPresupuesto).
// Se arranca acá para que corra DESPUÉS de que Kartik inicia sus calendarios.
$this->registerJs('VM.iniciarPresupuesto();');

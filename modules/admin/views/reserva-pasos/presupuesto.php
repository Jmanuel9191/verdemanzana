<?php

use app\models\PresupuestoForm;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\time\TimePicker;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

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
        'options' => ['enctype' => 'multipart/form-data'],
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
            <h3 class="titulo-seccion"><i class="fa fa-user"></i> Cliente y evento</h3>

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
                        ->label('Email o usuario para iniciar sesión')->hint('Es el usuario del cliente: puede ser un email o un nombre. Sin espacios. No se puede repetir.') ?>

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
            <?= Html::submitButton('<i class="fa fa-save"></i> ' . $textoBoton, [
                'class' => 'btn btn-' . ($esModificar ? 'warning' : 'success') . ' btn-lg',
            ]) ?>
            <?= Html::a('Cancelar', $esModificar ? ['presupuesto', 'id' => $reserva->idreservas] : ['reservas/index'],
                ['class' => 'btn btn-default btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</section>

<?php
// ---------------------------------------------------------------- estilos
$this->registerCss(<<<CSS
.reserva-pasos .titulo-seccion { color:#527f1a; font-size:20px; font-weight:600; margin:5px 0 15px; border-bottom:1px solid #e3e3e3; padding-bottom:8px; }
.pasos-reserva { margin-bottom:0; }
.pasos-reserva > li > a { font-weight:600; }
.pasos-reserva .paso-numero { display:inline-block; width:22px; height:22px; line-height:22px; border-radius:50%; background:#527f1a; color:#fff; text-align:center; font-size:12px; margin-right:4px; }
.pasos-reserva > li.disabled .paso-numero { background:#bbb; }
.caja-paso { border-top-left-radius:0; }
.resumen-reserva { position:sticky; top:0; z-index:20; display:flex; flex-wrap:wrap; gap:10px 30px; align-items:center; background:#2f4a10; color:#fff; padding:10px 15px; margin-bottom:15px; border-radius:3px; box-shadow:0 2px 6px rgba(0,0,0,.2); }
.resumen-reserva .resumen-bloque { display:flex; gap:8px; align-items:center; }
.resumen-reserva small { opacity:.8; }
.resumen-reserva .resumen-montos { margin-left:auto; gap:20px; }
.resumen-reserva .resumen-montos div { display:flex; flex-direction:column; text-align:right; }
.resumen-reserva .resumen-montos b { font-size:16px; }
.resumen-reserva .res-saldo b { color:#ffd54f; }
.tabla-lineas th { background:#f4f8ee; white-space:nowrap; }
.tabla-lineas .col-num { width:120px; }
.tabla-lineas .col-pct { width:95px; }
.tabla-lineas .col-accion { width:45px; text-align:center; }
.tabla-lineas .col-subtotal { text-align:right; vertical-align:middle; color:#555; }
.tabla-lineas input.num { text-align:right; }
.tabla-lineas .detalle-linea { display:block; margin-top:3px; }
.detalle-paquete { background:#fafcf7; border:1px dashed #c5d9a8; padding:10px 15px; border-radius:3px; }
.tabla-totales th { text-align:right; }
.tabla-totales td { text-align:right; width:45%; font-size:15px; }
.tabla-totales .fila-total th, .tabla-totales .fila-total td { font-size:20px; color:#527f1a; border-top:2px solid #527f1a; }
.calendarios h4 { text-align:center; color:#527f1a; font-weight:600; }
.calendario-sucursal .datepicker-inline { margin:0 auto; border:1px solid #527f1a; padding:5px; }
.calendario-sucursal td.ocupado, .muestra-ocupado { background:#527f1a !important; color:#fff !important; border-radius:3px; }
.muestra-ocupado { display:inline-block; width:14px; height:14px; vertical-align:middle; }
.calendario-sucursal td.active { background:#f0ad4e !important; color:#fff !important; }
.aviso-modificar { margin-bottom:0; }
.tabla-lectura td { vertical-align:middle !important; }
.tabla-lectura .fila-paquete-lectura td { background:#fafcf7; }
.observaciones-lectura { background:#fafafa; border:1px solid #eee; padding:8px 12px; border-radius:3px; }
.historial-presupuesto .enlace-historial { color:#527f1a; text-decoration:none; display:block; }
.historial-presupuesto .enlace-historial .badge { background:#527f1a; vertical-align:middle; }
.historial-presupuesto .flecha { float:right; font-size:14px; margin-top:6px; transition:transform .2s; }
.historial-presupuesto .enlace-historial:not(.collapsed) .flecha { transform:rotate(180deg); }
.lista-historial { list-style:none; padding:0; margin:0; }
.lista-historial > li { border-left:3px solid #f0ad4e; background:#fffdf7; padding:8px 12px; margin-bottom:10px; border-radius:0 3px 3px 0; }
.lista-historial > li.evento-creado { border-left-color:#527f1a; background:#fafcf7; }
.lista-historial .cabecera-evento { font-size:13px; }
.lista-historial .motivo-evento { font-size:12px; color:#8a6d3b; margin-top:3px; }
.lista-historial .cambios-evento { margin:6px 0 0; padding-left:18px; font-size:13px; }
.lista-historial .cambios-evento li { margin-bottom:2px; }
CSS);

// ---------------------------------------------------------------- datos para el JavaScript
$datosJs = Json::htmlEncode([
    'paquetes' => $catalogoPaquetes,
    'items'    => $catalogoItems,
    'ocupadas' => $form->fechasOcupadas(),
    'pagado'   => (float)$pagado,
    'siguienteIndice' => count($form->extras) + 1000,
    'tipoEvento' => (string)$reserva->tipoevento,
]);
$idFecha = Html::getInputId($reserva, 'fecha_reserva');
$idSucursal = Html::getInputId($reserva, 'sucursal');
$idTipo = Html::getInputId($reserva, 'tipoevento');

$this->registerJs(<<<JS
(function () {
    var D = $datosJs;
    var fmt2 = new Intl.NumberFormat('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    // ---- números: acepta "25.200,50", "25200,5", "25,200.50", "25200.5"
    function parse(txt) {
        if (txt === null || txt === undefined) return 0;
        var s = String(txt).replace(/\\s/g, '');
        if (s === '') return 0;
        if (/^\\d{1,3}(\\.\\d{3})+(,\\d{1,2})?$/.test(s)) { s = s.replace(/\\./g, '').replace(',', '.'); }
        else if (/^\\d{1,3}(,\\d{3})+(\\.\\d{1,2})?$/.test(s)) { s = s.replace(/,/g, ''); }
        else { s = s.replace(',', '.'); }
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }
    function bs(c) { return fmt2.format(c / 100); }                      // centavos -> "25.200,00"
    function pctTxt(p) { return (Math.round(p * 100) / 100).toString().replace('.', ','); }
    function cantTxt(n) { return (Math.round(n * 100) / 100).toString().replace('.', ','); }

    // ---- cálculo de una fila. origen: 'pct' | 'desc' | 'total'
    function calcularFila(fila, origen) {
        var cant = parse(fila.find('.campo-cantidad').val());
        var precio = parse(fila.find('.campo-precio').val());
        var subC = Math.round(Math.round(cant * 100) * Math.round(precio * 100) / 100);
        var descC;
        if (origen === 'desc') {
            descC = Math.round(parse(fila.find('.campo-desc').val()) * 100);
        } else if (origen === 'total') {
            descC = subC - Math.round(parse(fila.find('.campo-total').val()) * 100);
        } else {
            var p = Math.min(100, Math.max(0, parse(fila.find('.campo-pct').val())));
            descC = Math.round(subC * p / 100);
        }
        descC = Math.max(0, Math.min(descC, subC));
        var totC = subC - descC;
        var pct = subC > 0 ? descC * 100 / subC : 0;

        fila.data('sub', subC).data('desc', descC).data('tot', totC);
        fila.find('.campo-subtotal').text(bs(subC));
        if (origen !== 'pct') fila.find('.campo-pct').val(pctTxt(pct));
        if (origen !== 'desc') fila.find('.campo-desc').val(bs(descC));
        if (origen !== 'total') fila.find('.campo-total').val(bs(totC));
        calcularTotales();
    }

    function calcularTotales() {
        if (!$('#tabla-paquete').length) return;   // presupuesto solo para ver
        var sub = 0, desc = 0, tot = 0;
        $('.fila-linea').each(function () {
            var f = $(this);
            if (f.closest('template').length) return;
            if (f.hasClass('fila-paquete') && !$('#sel-paquete').val()) return;
            if (f.hasClass('fila-extra') && f.find('.sel-item').length && !f.find('.sel-item').val()) return;
            sub += f.data('sub') || 0; desc += f.data('desc') || 0; tot += f.data('tot') || 0;
        });
        var pagadoC = Math.round(D.pagado * 100);
        $('#tot-subtotal').text('Bs ' + bs(sub));
        $('#tot-descuento').text('− Bs ' + bs(desc));
        $('#tot-total').text('Bs ' + bs(tot));
        $('#tot-saldo').text('Bs ' + bs(tot - pagadoC));
        $('#res-total').text('Bs ' + bs(tot));
        $('#res-saldo').text('Bs ' + bs(tot - pagadoC));
    }

    // ---- eventos de edición (delegados: sirven también para filas nuevas)
    var tabla = $('#tabla-paquete, #tabla-extras');
    tabla.on('input', '.campo-cantidad, .campo-precio, .campo-pct', function () { calcularFila($(this).closest('tr'), 'pct'); });
    tabla.on('input', '.campo-desc', function () { calcularFila($(this).closest('tr'), 'desc'); });
    tabla.on('input', '.campo-total', function () { calcularFila($(this).closest('tr'), 'total'); });
    // Al salir del campo, se reescribe con formato prolijo
    tabla.on('blur', '.campo-precio, .campo-desc, .campo-total', function () {
        var c = Math.round(parse($(this).val()) * 100);
        $(this).val(bs(c));
    });
    tabla.on('blur', '.campo-cantidad', function () { $(this).val(cantTxt(parse($(this).val()))); });

    // ---- paquete
    function mostrarPaquete(id, cargarValores) {
        var p = D.paquetes[id];
        var fila = $('.fila-paquete');
        if (!p) {
            $('#detalle-paquete').hide();
            $('#res-paquete').text('Sin paquete'); $('#res-invitados').text('');
            fila.data('sub', 0).data('desc', 0).data('tot', 0);
            calcularTotales();
            return;
        }
        if (cargarValores) {
            fila.find('.campo-cantidad').val(p.invitados ? p.invitados : 1);
            fila.find('.campo-precio').val(bs(Math.round(parse(p.precio) * 100)));
            fila.find('.campo-pct').val('0');
        }
        var info = [];
        if (p.invitados) info.push('Base: ' + p.invitados + ' invitados');
        if (p.horas) info.push(p.horas + ' horas');
        $('#detalle-paquete-info').text(info.join(' · '));
        if (cargarValores) $('#detalle-paquete-html').html(p.detalle);
        $('#detalle-paquete').toggle(!!(info.length || p.detalle));
        $('#res-paquete').text(p.nombre.replace(' (inactivo)', ''));
        $('#res-invitados').text(cantTxt(parse(fila.find('.campo-cantidad').val())) + ' invitados');
        // Al elegir otro paquete se parte del %; al abrir la página se respeta el monto guardado
        calcularFila(fila, cargarValores ? 'pct' : 'desc');
    }
    $('#sel-paquete').on('change', function () { mostrarPaquete($(this).val(), true); });
    $('.fila-paquete').on('input', '.campo-cantidad', function () {
        var n = parse($(this).val());
        $('#res-invitados').text(n ? cantTxt(n) + ' invitados' : '');
    });

    // ---- ítems / proveedores
    function filaVacia() { $('#tabla-extras .fila-vacia').toggle($('#tabla-extras .fila-extra').length === 0); }

    $('#btn-agregar-linea').on('click', function () {
        var html = $('#plantilla-linea').html().replace(/__i__/g, D.siguienteIndice++);
        var fila = $(html);
        $('#tabla-extras tbody .fila-vacia').before(fila);
        filtrarPorTipo(fila);
        filaVacia();
        fila.find('.sel-item').focus();
    });

    $('#tabla-extras').on('change', '.sel-item', function () {
        var fila = $(this).closest('tr');
        var it = D.items[$(this).val()];
        if (!it) { fila.data('sub', 0).data('desc', 0).data('tot', 0); calcularTotales(); return; }
        fila.attr('data-seccion', it.tipo);
        fila.find('.campo-cantidad').val('1');
        fila.find('.unidad-linea').text(it.unidad.toLowerCase());
        fila.find('.campo-precio').val(bs(Math.round(parse(it.precio) * 100)));
        fila.find('.campo-pct').val('0');
        fila.find('.detalle-linea').text(it.detalle.length > 160 ? it.detalle.substring(0, 160) + '…' : it.detalle);
        calcularFila(fila, 'pct');
    });

    $('#tabla-extras').on('click', '.btn-quitar-linea', function () {
        var fila = $(this).closest('tr');
        var nombre = fila.find('b').first().text() || fila.find('.sel-item option:selected').text().trim();
        if (nombre && fila.find('.sel-item').length === 0 && !confirm('¿Quitar "' + nombre + '" del presupuesto?')) return;
        fila.remove();
        filaVacia();
        calcularTotales();
    });

    // ---- filtrar paquetes e ítems por tipo de evento
    function filtrarPorTipo(contexto) {
        // En "Modificar presupuesto" no está el selector: se usa el tipo de la reserva
        var tipo = $('#$idTipo').length ? $('#$idTipo').val() : D.tipoEvento;
        (contexto || $(document)).find('#sel-paquete option, .sel-item option').each(function () {
            var t = $(this).data('tipo-evento');
            if (!t) return;
            var visible = (t === 'todos' || t === tipo || this.selected);
            $(this).prop('hidden', !visible).prop('disabled', !visible);
        });
    }
    $('#$idTipo').on('change', function () { filtrarPorTipo(); });

    // ---- calendarios de sucursales
    function claveFecha(d) {
        var m = ('0' + (d.getMonth() + 1)).slice(-2), dia = ('0' + d.getDate()).slice(-2);
        return d.getFullYear() + '-' + m + '-' + dia;
    }
    function revisarFecha() {
        if (!$('#$idFecha').length) return;   // pantalla sin datos del cliente
        var f = $('#$idFecha').val(), s = $('#$idSucursal').val();
        var ocupada = f && D.ocupadas[s] && D.ocupadas[s].indexOf(f) !== -1;
        $('#aviso-fecha').toggle(!!ocupada)
            .html('<i class="fa fa-warning"></i> <b>Atención:</b> ' + s + ' ya tiene una reserva el ' + f.split('-').reverse().join('/') + '.');
        $('#res-fecha').text(f ? f.split('-').reverse().join('/') : 'Sin fecha');
        $('#res-sucursal').text(s || '');
    }
    $('.calendario-sucursal').each(function () {
        var cal = $(this), suc = cal.data('sucursal');
        var plugin = $.fn.kvDatepicker ? 'kvDatepicker' : 'datepicker';
        cal[plugin]({
            language: 'es', format: 'yyyy-mm-dd', startDate: new Date(), todayHighlight: true,
            beforeShowDay: function (d) {
                if ((D.ocupadas[suc] || []).indexOf(claveFecha(d)) !== -1) {
                    return {classes: 'ocupado', tooltip: 'Reservado'};
                }
            }
        }).on('changeDate', function (e) {
            if (!e.date) return;
            $('#$idFecha').val(claveFecha(e.date)).trigger('change');
            $('#$idSucursal').val(suc).trigger('change');
        });
    });
    $('#$idFecha, #$idSucursal').on('change', revisarFecha);

    // ---- al cargar la página
    filtrarPorTipo();
    $('.fila-linea').each(function () {
        var f = $(this);
        if (f.closest('template').length) return;
        calcularFila(f, 'desc');
    });
    if ($('#sel-paquete').val()) { mostrarPaquete($('#sel-paquete').val(), false); }
    revisarFecha();
    filaVacia();

    // Evitar enviar con Enter en los campos numéricos (envíos accidentales)
    tabla.on('keydown', 'input', function (e) { if (e.key === 'Enter') { e.preventDefault(); } });
})();
JS);

<?php

use app\helpers\FormatoHelper;
use app\models\ReservaCotizacion;
use app\models\ReservaCotizacionDetalle;
use app\models\ReservaPago;
use app\models\Reservas;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $reserva Reservas */
/* @var $cotizacion ReservaCotizacion */
/* @var $pagos ReservaPago[] */
/* @var $pago ReservaPago  formulario de pago nuevo */

$this->title = 'Reserva: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['presupuesto', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = 'Pagos';

$total = $cotizacion->total;
$pagado = $cotizacion->getTotalPagado();
$saldo = $cotizacion->getSaldo();
$saldoC = ReservaCotizacionDetalle::aCentavos($saldo);
$paquete = $cotizacion->lineaPaquete;

$cantidadValidos = 0;
foreach ($pagos as $p) {
    if ($p->esValido()) {
        $cantidadValidos++;
    }
}

$fechaHora = function ($v) {
    return $v ? date('d/m/Y H:i', strtotime($v)) : '';
};
$abrirFormulario = $pago->hasErrors();
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

    <?= $this->render('_resumen', [
        'paquete'   => $paquete ? $paquete->nombre : '',
        'invitados' => $paquete ? (string)(int)round((float)$paquete->cantidad) : '',
        'fecha'     => $reserva->fecha_reserva,
        'sucursal'  => $reserva->sucursal,
        'total'     => $total,
        'pagado'    => $pagado,
    ]) ?>

    <?= $this->render('_pasos', ['actual' => 'pagos', 'idreservas' => $reserva->idreservas]) ?>

    <div class="box box-success caja-paso">
        <div class="box-body">

            <div class="encabezado-pagos">
                <h3 class="titulo-seccion"> Pagos</h3>
                <div>
                <?= Html::a(' Estado de cuenta', ['pdf', 'id' => $reserva->idreservas, 'tipo' => 'pagos'],
                    ['class' => 'btn btn-danger', 'target' => '_blank', 'title' => 'Abrir PDF (solo pagos válidos)']) ?>
                <?php if ($saldoC > 0): ?>
                    <button type="button" class="btn btn-success" id="btn-nuevo-pago">
                        <i class="fa fa-plus"></i> Registrar pago
                    </button>
                <?php else: ?>
                    <span class="label label-success etiqueta-pagado"><i class="fa fa-check"></i> Pagado por completo</span>
                <?php endif; ?>
                </div>
            </div>

            <!-- ===================== FORMULARIO DE PAGO NUEVO ===================== -->
            <?php if ($saldoC > 0): ?>
                <div id="panel-nuevo-pago" class="panel-nuevo-pago" style="<?= $abrirFormulario ? '' : 'display:none' ?>">
                    <?php $af = ActiveForm::begin([
                        'id' => 'form-pago',
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <?= $af->field($pago, 'fecha_pago')->widget(DatePicker::class, [
                                'language' => 'es',
                                'options' => ['autocomplete' => 'off'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,
                                    'endDate' => '0d',
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?= $af->field($pago, 'monto', [
                                // el monto se normaliza en el servidor ("1.500,50" → 1500.50); en el navegador no se valida
                                'enableClientValidation' => false,
                                'template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">Bs</span>{input}"
                                    . "<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-default\" id=\"btn-saldo\" data-saldo=\"" . number_format((float)$saldo, 2, ',', '.') . "\" title=\"Poner el saldo completo\">Saldo</button></span></div>\n{hint}\n{error}",
                            ])->textInput([
                                'inputmode' => 'decimal',
                                'autocomplete' => 'off',
                                'placeholder' => 'Ej. 1.500,50',
                                'class' => 'form-control text-right',
                            ])->hint('Saldo pendiente: <b>' . FormatoHelper::formatoBs($saldo) . '</b>') ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?= $af->field($pago, 'forma_pago')->dropDownList(ReservaPago::formasPago(), ['prompt' => 'Elegí…']) ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?= $af->field($pago, 'archivo')->fileInput([
                                'accept' => '.jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf',
                            ])->hint('Obligatorio. Foto o PDF, máx. 5 MB.') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <?= $af->field($pago, 'observacion')->textarea(['rows' => 2, 'maxlength' => 2000,
                                'placeholder' => 'Opcional. Ej. N.º de transacción, quién entregó el dinero…']) ?>
                        </div>
                        <div class="col-md-3 botones-pago">
                            <?= Html::submitButton('<i class="fa fa-save"></i> Guardar pago', ['class' => 'btn btn-success btn-block', 'id' => 'btn-guardar-pago']) ?>
                            <button type="button" class="btn btn-default btn-block" id="btn-cancelar-pago">Cancelar</button>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            <?php endif; ?>

            <!-- ===================== HISTORIAL ===================== -->
            <?php if (empty($pagos)): ?>
                <p class="text-muted sin-pagos"><i class="fa fa-info-circle"></i> Todavía no hay pagos registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed tabla-pagos">
                        <thead>
                        <tr>
                            <th>N.º</th>
                            <th>Fecha de pago</th>
                            <th class="text-right">Monto</th>
                            <th>Forma</th>
                            <th>Comprobante</th>
                            <th>Observación</th>
                            <th>Registrado</th>
                            <th class="text-center">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pagos as $p): ?>
                            <?php $valido = $p->esValido(); ?>
                            <tr class="<?= $valido ? '' : 'pago-anulado' ?>">
                                <td><?= (int)$p->idpago ?></td>
                                <td class="tachable"><?= Html::encode(date('d/m/Y', strtotime($p->fecha_pago))) ?></td>
                                <td class="text-right tachable"><b><?= Html::encode($p->getMontoTexto()) ?></b></td>
                                <td class="tachable"><?= Html::encode($p->getFormaPagoTexto()) ?></td>
                                <td>
                                    <?php if ($p->getRutaComprobante() !== null): ?>
                                        <?php $esPdf = strtolower(pathinfo($p->comprobante, PATHINFO_EXTENSION)) === 'pdf'; ?>
                                        <?= Html::a('<i class="fa ' . ($esPdf ? 'fa-file-pdf-o' : 'fa-file-image-o') . '"></i> Ver',
                                            ['comprobante', 'idpago' => $p->idpago],
                                            ['target' => '_blank', 'rel' => 'noopener', 'class' => 'btn btn-xs btn-default',
                                                'title' => $p->comprobante_original ?: 'Comprobante']) ?>
                                    <?php else: ?>
                                        <span class="text-danger" title="El archivo no está en el servidor"><i class="fa fa-exclamation-triangle"></i> Falta</span>
                                    <?php endif; ?>
                                </td>
                                <td class="col-observacion"><?= nl2br(Html::encode((string)$p->observacion)) ?></td>
                                <td class="col-registro">
                                    <?= Html::encode($fechaHora($p->fecha_registro)) ?><br>
                                    <small class="text-muted"><?= Html::encode(ReservaPago::nombreAdmin($p->adminRegistro)) ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if ($valido): ?>
                                        <span class="label label-success">Válido</span><br>
                                        <button type="button" class="btn btn-xs btn-danger btn-anular" style="margin-top:4px"
                                                data-idpago="<?= (int)$p->idpago ?>"
                                                data-detalle="<?= Html::encode('N.º ' . $p->idpago . ' · ' . date('d/m/Y', strtotime($p->fecha_pago)) . ' · ' . $p->getMontoTexto() . ' · ' . $p->getFormaPagoTexto()) ?>">
                                            <i class="fa fa-ban"></i> Anular
                                        </button>
                                    <?php else: ?>
                                        <span class="label label-default">Anulado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if (!$valido): ?>
                                <tr class="fila-motivo">
                                    <td></td>
                                    <td colspan="7">
                                        <i class="fa fa-ban"></i>
                                        Anulado el <b><?= Html::encode($fechaHora($p->fecha_anulacion)) ?></b>
                                        por <b><?= Html::encode(ReservaPago::nombreAdmin($p->adminAnulo)) ?></b>.
                                        Motivo: <?= Html::encode((string)$p->motivo_anulacion) ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <!-- ===================== TOTALES ===================== -->
            <div class="row">
                <div class="col-md-5 col-md-offset-7 col-sm-8 col-sm-offset-4">
                    <table class="table tabla-totales">
                        <tr>
                            <th>Total del presupuesto</th>
                            <td><?= FormatoHelper::formatoBs($total) ?></td>
                        </tr>
                        <tr>
                            <th>Cancelado a la fecha <small class="text-muted">(<?= $cantidadValidos ?> pago<?= $cantidadValidos === 1 ? '' : 's' ?> válido<?= $cantidadValidos === 1 ? '' : 's' ?>)</small></th>
                            <td><?= FormatoHelper::formatoBs($pagado) ?></td>
                        </tr>
                        <tr class="fila-total">
                            <th>Saldo</th>
                            <td><?= FormatoHelper::formatoBs($saldo) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
        <div class="box-footer">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Volver al presupuesto', ['presupuesto', 'id' => $reserva->idreservas], ['class' => 'btn btn-default']) ?>
            <?= Html::a('<i class="fa fa-list"></i> Ir al listado de reservas', ['reservas/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</section>

<!-- ===================== MODAL ANULAR ===================== -->
<div class="modal fade" id="modal-anular" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= Html::beginForm(['anular-pago', 'id' => $reserva->idreservas], 'post', ['id' => 'form-anular']) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-ban"></i> Anular pago</h4>
            </div>
            <div class="modal-body">
                <p id="anular-detalle" class="text-danger" style="font-weight:600"></p>
                <p class="text-muted">El pago no se borra: queda en el historial tachado, con el motivo, quién lo anuló y cuándo. El saldo se recalcula.</p>
                <?= Html::hiddenInput('idpago', '', ['id' => 'anular-idpago']) ?>
                <div class="form-group">
                    <label for="anular-motivo">Motivo de la anulación</label>
                    <?= Html::textarea('motivo', '', ['id' => 'anular-motivo', 'class' => 'form-control', 'rows' => 3,
                        'maxlength' => 500, 'required' => true, 'minlength' => 5,
                        'placeholder' => 'Ej. Monto mal cargado, se registra de nuevo.']) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger" id="btn-confirmar-anular"><i class="fa fa-ban"></i> Anular pago</button>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>


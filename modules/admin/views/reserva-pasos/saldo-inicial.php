<?php

use app\helpers\FormatoHelper;
use app\models\ReservaCotizacionDetalle;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cuenta app\models\ReservaCuentaInicial */
/* @var $esNueva bool    true = todavía no tenía saldo de arranque */
/* @var $pagado string   pagos nuevos (con comprobante) ya registrados */
/* @var $paraPresupuesto bool  viene de "Modificar presupuesto" (paso previo a pasar al sistema nuevo) */
$paraPresupuesto = $paraPresupuesto ?? false;

$this->title = 'Saldo de arranque: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['reservas/view', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = 'Saldo de arranque';

$hayPagos = ReservaCotizacionDetalle::aCentavos($pagado) > 0;
// Para mostrar en el formulario: "28500.00" → "28.500,00"
$enCampo = function ($v) {
    return is_numeric($v) ? number_format((float)$v, 2, ',', '.') : (string)$v;
};
foreach (['total_paquete', 'cancelado_paquete', 'total_servicios', 'cancelado_servicios'] as $campo) {
    if (!$cuenta->hasErrors($campo)) {
        $cuenta->$campo = $enCampo($cuenta->$campo);
    }
}
$montoCampo = [
    'enableClientValidation' => false,
    'template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">Bs</span>{input}</div>\n{hint}\n{error}",
];
$opcionesMonto = ['class' => 'form-control text-right monto-saldo', 'inputmode' => 'decimal', 'autocomplete' => 'off'];
// "En la reserva figura": solo la primera vez (después reservas1 ya tiene los montos sincronizados)
$figura = function ($valor, $extra = '') use ($esNueva) {
    return $esNueva ? 'En la reserva figura: <b>' . Html::encode($valor ?: '—') . '</b>' . $extra : ltrim($extra, '. ');
};
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $esNueva ? 'Confirmá el saldo de arranque' : 'Editar saldo de arranque' ?></h3>
        </div>

        <?php $af = ActiveForm::begin(['id' => 'form-saldo-inicial']); ?>
        <div class="box-body">
            <p class="text-muted">
                Esta reserva se creó con el formulario anterior. Para cobrarle pagos con comprobante, confirmá
                <b>cuánto cuesta</b> y <b>cuánto ya se había cancelado antes</b>. Los montos vienen cargados desde la reserva:
                porfavor revisar y corregír lo que haga falta. Paquete y servicios adicionales quedan en <b>una sola cuenta</b>.
            </p>
            <?php if ($paraPresupuesto): ?>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <b>Paso previo:</b> antes de armar el presupuesto nuevo, confirmá cuánto costaba
                    y cuánto ya se había cancelado. Después elegís el paquete y se muestra esto como referencia.
                </div>
            <?php endif; ?>
            <?php if ($hayPagos): ?>
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle"></i>
                    Ya hay pagos nuevos registrados por <b><?= FormatoHelper::formatoBs($pagado) ?></b>.
                    El saldo de arranque no puede quedar por debajo de ese monto.
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-success">Paquete</h4>
                    <?= $af->field($cuenta, 'total_paquete', $montoCampo)->textInput($opcionesMonto)
                        ->hint($figura($reserva->presupuesto)) ?>
                    <?= $af->field($cuenta, 'cancelado_paquete', $montoCampo)->textInput($opcionesMonto)
                        ->hint($figura($reserva->monto_canceladop)) ?>
                </div>
                <div class="col-md-6">
                    <h4 class="text-success">Servicios adicionales</h4>
                    <?= $af->field($cuenta, 'total_servicios', $montoCampo)->textInput($opcionesMonto)
                        ->hint($figura($reserva->servicios_adicionales, '. Dejá 0 si no tiene.')) ?>
                    <?= $af->field($cuenta, 'cancelado_servicios', $montoCampo)->textInput($opcionesMonto)
                        ->hint($figura($reserva->monto_cancelados)) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $af->field($cuenta, 'observacion')->textarea(['rows' => 2, 'maxlength' => 500,
                        'placeholder' => 'Opcional. Ej. Se corrigió el saldo según el contrato firmado.']) ?>
                </div>
                <div class="col-md-6">
                    <table class="table tabla-saldo-inicial" style="margin-top:20px">
                        <tr><th>Total de la cuenta</th><td class="text-right" id="si-total">—</td></tr>
                        <tr><th>Cancelado antes del sistema</th><td class="text-right" id="si-cancelado">—</td></tr>
                        <?php if ($hayPagos): ?>
                            <tr><th>Pagos nuevos registrados</th><td class="text-right"><?= FormatoHelper::formatoBs($pagado) ?></td></tr>
                        <?php endif; ?>
                        <tr style="font-size:16px"><th>Saldo</th><td class="text-right text-danger" id="si-saldo"
                            data-pagado="<?= Html::encode($pagado) ?>"><b>—</b></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <?= Html::submitButton('<i class="fa fa-save"></i> ' . ($paraPresupuesto ? 'Confirmar y armar el presupuesto' : ($esNueva ? 'Guardar e ir a Pagos' : 'Guardar')), ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', $esNueva ? ['reservas/view', 'id' => $reserva->idreservas] : ['pagos', 'id' => $reserva->idreservas], ['class' => 'btn btn-default']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
<?php /* Los totales en vivo están en assets_b/js/adminjs.js (bloque "Saldo de arranque") */ ?>

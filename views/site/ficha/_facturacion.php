<?php
/**
 * Pestaña FACTURACIÓN: totales, lista de pagos válidos con su comprobante
 * (miniatura que abre en popup; los PDF se abren en otra pestaña).
 *
 * @var $this       yii\web\View
 * @var $model      app\models\Reservas
 * @var $cotizacion app\models\ReservaCotizacion|null   null = reserva vieja
 * @var $pagos      app\models\ReservaPago[]            solo válidos
 * @var $cuentaInicial app\models\ReservaCuentaInicial|null  reserva vieja con Facturación
 */

use app\helpers\FormatoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$id = (int)$model->idreservas;

$fecha = function ($f) {
    $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $t = strtotime($f);
    return date('d', $t) . ' ' . $meses[(int)date('m', $t)] . ' ' . date('Y', $t);
};
$montoViejo = function ($x) {
    $x = trim((string)$x);
    if ($x === '') {
        return '—';
    }
    $n = FormatoHelper::normalizarMonto($x);
    return is_numeric($n) ? FormatoHelper::formatoBs($n) : $x;
};

// Totales: reserva nueva = cotización; vieja con Facturación = saldo de arranque
// (una sola cuenta: paquete + servicios); vieja sin Facturación = columnas de reservas1
$cuentaInicial = $cuentaInicial ?? null;
$cuenta = $cotizacion ?: $cuentaInicial;
$canceladoInicial = $cuentaInicial ? $cuentaInicial->getCanceladoInicial() : '0';
$hayInicial = (float)$canceladoInicial > 0;
if ($cuenta) {
    $total = FormatoHelper::formatoBs($cuenta->getTotal());
    $cancelado = FormatoHelper::formatoBs($cuenta->getTotalPagado());
    $saldo = FormatoHelper::formatoBs($cuenta->getSaldo());
} else {
    $total = $montoViejo($model->presupuesto);
    $cancelado = $montoViejo($model->monto_canceladop);
    $saldo = $montoViejo($model->saldo_pagarp);
}
?>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">
        Estado de cuenta
        <?php if ($cotizacion): ?>
            <?= Html::a('<i class="fa-solid fa-file-pdf"></i> Descargar estado de cuenta',
                ['site/reserva-pdf', 'id' => $id, 'tipo' => 'pagos'],
                ['class' => 'btn vm-btn-pdf pull-right', 'target' => '_blank', 'rel' => 'noopener']) ?>
        <?php endif; ?>
    </h2>

    <div class="row vm-cuentas">
        <div class="col-sm-4">
            <div class="vm-cuenta"><span>Total</span><strong><?= Html::encode($total) ?></strong></div>
        </div>
        <div class="col-sm-4">
            <div class="vm-cuenta vm-cuenta-verde"><span>Cancelado</span><strong><?= Html::encode($cancelado) ?></strong></div>
        </div>
        <div class="col-sm-4">
            <div class="vm-cuenta vm-cuenta-saldo"><span>Saldo</span><strong><?= Html::encode($saldo) ?></strong></div>
        </div>
    </div>

    <?php if (!$cuenta && trim((string)$model->servicios_adicionales) !== ''): ?>
        <div class="row vm-cuentas">
            <div class="col-sm-4">
                <div class="vm-cuenta vm-cuenta-chica"><span>Servicios adicionales</span><strong><?= Html::encode($montoViejo($model->servicios_adicionales)) ?></strong></div>
            </div>
            <div class="col-sm-4">
                <div class="vm-cuenta vm-cuenta-chica vm-cuenta-verde"><span>Cancelado</span><strong><?= Html::encode($montoViejo($model->monto_cancelados)) ?></strong></div>
            </div>
            <div class="col-sm-4">
                <div class="vm-cuenta vm-cuenta-chica vm-cuenta-saldo"><span>Saldo</span><strong><?= Html::encode($montoViejo($model->saldo_pagars)) ?></strong></div>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Pagos</h2>

    <?php if (empty($pagos) && !$hayInicial): ?>
        <p class="vm-vacio"><i class="fa-solid fa-receipt"></i> Todavía no hay pagos registrados.</p>
    <?php else: ?>
        <div class="vm-pagos vm-galeria">
            <?php if ($hayInicial): ?>
                <div class="vm-pago vm-pago-inicial">
                    <div class="vm-pago-comprobante">
                        <span class="vm-pago-sin"><i class="fa-solid fa-clock-rotate-left"></i><br>Registro anterior</span>
                    </div>
                    <div class="vm-pago-datos">
                        <span class="vm-pago-numero">Cancelado antes del sistema</span>
                        <strong class="vm-pago-monto"><?= Html::encode(FormatoHelper::formatoBs($canceladoInicial)) ?></strong>
                        <small class="vm-gris">Pagos registrados antes de los comprobantes.</small>
                    </div>
                </div>
            <?php endif; ?>
            <?php $numero = 0; foreach ($pagos as $p):
                if (!$p->esAnterior()) { $numero++; }
                $url = Url::to(['site/comprobante-pago', 'idpago' => $p->idpago]);
                $ext = strtolower(pathinfo((string)$p->comprobante, PATHINFO_EXTENSION));
                $hayArchivo = $p->getRutaComprobante() !== null;
                $titulo = ($p->esAnterior() ? 'Cancelado antes del sistema' : 'Pago ' . $numero) . ' · ' . $fecha($p->fecha_pago) . ' · ' . $p->getMontoTexto(); ?>
                <div class="vm-pago">
                    <div class="vm-pago-comprobante">
                        <?php if ($p->esAnterior()): ?>
                            <span class="vm-pago-sin"><i class="fa-solid fa-clock-rotate-left"></i><br>Registro anterior</span>
                        <?php elseif (!$hayArchivo): ?>
                            <span class="vm-pago-sin"><i class="fa-solid fa-file-circle-xmark"></i><br>Sin comprobante</span>
                        <?php elseif ($ext === 'pdf'): ?>
                            <a href="<?= Html::encode($url) ?>" target="_blank" rel="noopener" class="vm-pago-pdf" title="Abrir comprobante (PDF)">
                                <i class="fa-solid fa-file-pdf"></i><br>Ver PDF
                            </a>
                        <?php else: ?>
                            <a href="<?= Html::encode($url) ?>" class="vm-zoom" title="<?= Html::encode($titulo) ?>">
                                <img src="<?= Html::encode($url) ?>" alt="Comprobante" loading="lazy">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="vm-pago-datos">
                        <span class="vm-pago-numero"><?= $p->esAnterior() ? 'Cancelado antes del sistema' : 'Pago ' . $numero ?></span>
                        <strong class="vm-pago-monto"><?= Html::encode($p->getMontoTexto()) ?></strong>
                        <span><i class="fa-solid fa-calendar-day"></i> <?= Html::encode($fecha($p->fecha_pago)) ?></span>
                        <span><i class="fa-solid fa-money-bill-wave"></i> <?= Html::encode($p->getFormaPagoTexto()) ?></span>
                        <?php if (trim((string)$p->observacion) !== ''): ?>
                            <small class="vm-gris"><?= Html::encode($p->observacion) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

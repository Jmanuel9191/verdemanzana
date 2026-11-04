<?php

use app\helpers\FormatoHelper;
use app\models\ReservaPago;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $pagos ReservaPago[] */
/* @var $conAnulados bool  true en el PDF completo (interno) */

$c = $cotizacion;
$n = 0;
?>
<?= $this->render('_cabecera', ['reserva' => $reserva, 'cotizacion' => $cotizacion, 'titulo' => 'Estado de cuenta']) ?>

<?php if (empty($pagos)): ?>
    <p class="muted">Todavía no hay pagos registrados.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
        <tr>
            <th width="6%">N.º</th>
            <th width="16%">Fecha de pago</th>
            <th width="18%">Forma de pago</th>
            <th>Observación</th>
            <th class="num" width="18%">Monto</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagos as $p): ?>
            <?php $valido = $p->esValido(); ?>
            <tr>
                <td><?= $valido ? ++$n : '—' ?></td>
                <td class="<?= $valido ? '' : 'tachado' ?>"><?= Html::encode(date('d/m/Y', strtotime($p->fecha_pago))) ?></td>
                <td class="<?= $valido ? '' : 'tachado' ?>"><?= Html::encode($p->getFormaPagoTexto()) ?></td>
                <td class="chico">
                    <?= nl2br(Html::encode((string)$p->observacion)) ?>
                    <?php if (!$valido && $conAnulados): ?>
                        <br><span style="color:#a94442;">ANULADO el <?= Html::encode(date('d/m/Y H:i', strtotime((string)$p->fecha_anulacion))) ?>
                        por <?= Html::encode(ReservaPago::nombreAdmin($p->adminAnulo)) ?>: <?= Html::encode((string)$p->motivo_anulacion) ?></span>
                    <?php endif; ?>
                </td>
                <td class="num <?= $valido ? '' : 'tachado' ?>"><b><?= Html::encode($p->getMontoTexto()) ?></b></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<table width="100%" style="margin-top:2mm;"><tr><td width="50%"></td><td width="50%"><table width="100%">
    <tr><td class="tot-etq">Total del presupuesto</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($c->total) ?></td></tr>
    <tr><td class="tot-etq">Cancelado a la fecha</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($c->getTotalPagado()) ?></td></tr>
    <tr class="total"><td class="tot-etq">SALDO</td><td class="tot-val" width="40%"><?= FormatoHelper::formatoBs($c->getSaldo()) ?></td></tr>
</table></td></tr></table>
<?php if ($conAnulados): ?>
    <p class="muted chico">Los pagos tachados fueron anulados y no se suman a lo cancelado.</p>
<?php endif; ?>

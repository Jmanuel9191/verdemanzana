<?php

use app\components\PdfReserva;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $titulo string */
/* @var $reserva app\models\Reservas */
/* @var $empresa app\models\Configuracion|null */
/* @var $logo string|null */
/* @var $invitados app\models\Invitados[] */
/* @var $limite int */
/* @var $imagen string|null  ruta en disco de la imagen de distribución (o null) */

$nombreEmpresa = $empresa && trim((string)$empresa->nombre_empresa) !== '' ? $empresa->nombre_empresa : 'Verde Manzana';
$evento = PdfReserva::nombreEvento($reserva);

$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$t = $reserva->fecha_reserva ? strtotime($reserva->fecha_reserva) : null;
$fecha = $t ? (int)date('d', $t) . ' de ' . $meses[(int)date('m', $t)] . ' de ' . date('Y', $t) : '';
?>
<html>
<head>
<style>
    body { font-family: dejavusans; font-size: 9.5pt; color: #222; }
    h1.doc-titulo { font-size: 15pt; color: #527f1a; margin: 0 0 2mm; }
    h2.doc-seccion { font-size: 11.5pt; color: #fff; background: #527f1a; padding: 1.5mm 3mm; margin: 5mm 0 2.5mm; }
    table { border-collapse: collapse; }
    .tabla { width: 100%; }
    .tabla th { background: #eef4e6; color: #2f4a10; font-size: 8.5pt; text-align: left; padding: 1.6mm 2mm; border-bottom: 0.4mm solid #527f1a; }
    .tabla td { padding: 1.6mm 2mm; border-bottom: 0.2mm solid #dde5d3; vertical-align: top; }
    .tabla .num { text-align: center; white-space: nowrap; }
    .datos td { padding: 1mm 2mm 1mm 0; vertical-align: top; }
    .etq { color: #888888; font-size: 7pt; text-transform: uppercase; }
    .muted { color: #888; }
    .chico { font-size: 8pt; }
</style>
</head>
<body>

<htmlpageheader name="encabezado">
    <table width="100%">
        <tr>
            <td width="45%" style="vertical-align: middle; padding-bottom: 2mm;">
                <?php if ($logo): ?>
                    <img src="<?= Html::encode($logo) ?>" style="height: 14mm;">
                <?php else: ?>
                    <span style="font-size: 17pt; font-weight: bold; color: #527f1a;"><?= Html::encode($nombreEmpresa) ?></span><br>
                    <span class="muted chico">Salones de eventos</span>
                <?php endif; ?>
            </td>
            <td width="55%" style="text-align: right; vertical-align: middle; font-size: 7.5pt; color: #555; padding-bottom: 2mm;">
                <?php if ($empresa): ?>
                    <?= Html::encode((string)$empresa->direccion_empresa) ?><br>
                    Tel.: <?= Html::encode((string)$empresa->telefono_empresa) ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <div style="border-bottom: 0.6mm solid #527f1a; height: 0.1mm;"></div>
</htmlpageheader>

<htmlpagefooter name="pie">
    <table width="100%" style="border-top: 0.3mm solid #c5d9a8; font-size: 7pt; color: #888;">
        <tr>
            <td width="70%" style="padding-top: 1.5mm;">
                <?= Html::encode($titulo) ?> · <?= Html::encode(mb_strimwidth($evento, 0, 45, '…')) ?> · Reserva N.º <?= (int)$reserva->idreservas ?> · <?= date('d/m/Y H:i') ?>
            </td>
            <td width="30%" style="text-align: right; padding-top: 1.5mm;">Página {PAGENO} de {nbpg}</td>
        </tr>
    </table>
</htmlpagefooter>

<sethtmlpageheader name="encabezado" value="on" show-this-page="1" />
<sethtmlpagefooter name="pie" value="on" />

<h1 class="doc-titulo">Lista de invitados</h1>
<table class="datos" width="100%">
    <tr>
        <td width="60%"><span class="etq">Evento</span><br><?= Html::encode($evento) ?></td>
        <td width="25%"><span class="etq">Fecha</span><br><?= Html::encode($fecha) ?></td>
        <td width="15%"><span class="etq">Invitados</span><br><?= count($invitados) ?><?= $limite > 0 ? ' / ' . $limite : '' ?></td>
    </tr>
    <tr>
        <td><span class="etq">Cliente</span><br><?= Html::encode($reserva->nombre) ?></td>
        <td colspan="2"><span class="etq">Salón</span><br><?= Html::encode($reserva->sucursal) ?></td>
    </tr>
</table>

<h2 class="doc-seccion">Invitados (<?= count($invitados) ?>)</h2>

<?php if (empty($invitados)): ?>
    <p class="muted">Todavía no hay invitados cargados.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
        <tr>
            <th width="7%">#</th>
            <th width="30%">Apellido</th>
            <th width="30%">Nombre</th>
            <th width="18%">Teléfono</th>
            <th width="15%" class="num">N.º de mesa</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($invitados as $i => $inv): ?>
            <tr>
                <td class="num"><?= $i + 1 ?></td>
                <td><?= Html::encode($inv->apellido) ?></td>
                <td><?= Html::encode($inv->nombre) ?></td>
                <td><?= Html::encode((string)$inv->telefono) ?></td>
                <td class="num"><?= $inv->nro_mesa !== null && $inv->nro_mesa !== '' ? (int)$inv->nro_mesa : '—' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php if ($imagen && is_file($imagen)): ?>
    <pagebreak />
    <h2 class="doc-seccion">Distribución de la lista de invitados</h2>
    <div style="text-align: center;">
        <img src="<?= Html::encode($imagen) ?>" style="max-width: 100%; max-height: 220mm;">
    </div>
<?php endif; ?>

</body>
</html>

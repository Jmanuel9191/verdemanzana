<?php

use app\components\PdfReserva;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $tipo string */
/* @var $titulo string */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $empresa app\models\Configuracion|null */
/* @var $logo string|null */
/* @var $secciones string[] */
/* @var $conAnulados bool */
/* @var $pagos app\models\ReservaPago[] */
/* @var $cronograma app\models\ReservaCronograma */
/* @var $invitados int */

$nombreEmpresa = $empresa && trim((string)$empresa->nombre_empresa) !== '' ? $empresa->nombre_empresa : 'Verde Manzana';
$evento = PdfReserva::nombreEvento($reserva);
?>
<html>
<head>
<style>
    body { font-family: dejavusans; font-size: 9.5pt; color: #222; }
    .verde { color: #527f1a; }
    /* Estilos SOLO para los títulos propios del PDF (con clase), así no afectan al
       contenido pegado desde Word (el cronograma trae sus propios h1/h2/h3) */
    h1.doc-titulo { font-size: 15pt; color: #527f1a; margin: 0 0 2mm; }
    h2.doc-seccion { font-size: 11.5pt; color: #fff; background: #527f1a; padding: 1.5mm 3mm; margin: 5mm 0 2.5mm; }
    h3.doc-sub { font-size: 10pt; color: #527f1a; border-bottom: 0.3mm solid #c5d9a8; padding-bottom: 1mm; margin: 4mm 0 2mm; }
    .cronograma h1, .cronograma h2, .cronograma h3, .cronograma h4 { background: none; color: #000; }
    table { border-collapse: collapse; }
    .tabla { width: 100%; }
    .tabla th { background: #eef4e6; color: #2f4a10; font-size: 8.5pt; text-align: left; padding: 1.6mm 2mm; border-bottom: 0.4mm solid #527f1a; }
    .tabla td { padding: 1.6mm 2mm; border-bottom: 0.2mm solid #dde5d3; vertical-align: top; }
    .tabla .num { text-align: right; white-space: nowrap; }
    .tabla tr.paquete td { background: #f7faf2; }
    .datos { width: 100%; }
    .datos td { padding: 1mm 2mm 1mm 0; vertical-align: top; }
    .etq { color: #888888; font-size: 7pt; text-transform: uppercase; }
    td.tot-etq { padding: 1.2mm 3mm; text-align: right; color: #444; }
    td.tot-val { padding: 1.2mm 2mm; text-align: right; white-space: nowrap; }
    tr.total td { font-size: 12pt; font-weight: bold; color: #527f1a; border-top: 0.5mm solid #527f1a; }
    tr.saldo td { font-weight: bold; color: #8a5a00; }
    .caja { border: 0.3mm solid #dde5d3; background: #fafcf7; padding: 2mm 3mm; margin-bottom: 2mm; }
    .muted { color: #888; }
    .chico { font-size: 8pt; }
    .tachado { text-decoration: line-through; color: #999; }
    .etiqueta { font-size: 7pt; color: #fff; padding: 0.3mm 1.5mm; }
    .etq-paquete { background: #527f1a; }
    .etq-item { background: #3c8dbc; }
    .etq-proveedor { background: #e08e0b; }
    .cronograma p { margin: 0 0 1.5mm; }
    .cronograma table { border-collapse: collapse; }
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

<?php foreach ($secciones as $i => $seccion): ?>
    <?php if ($i > 0): ?><pagebreak /><?php endif; ?>
    <?= $this->render('_' . $seccion, [
        'reserva'     => $reserva,
        'cotizacion'  => $cotizacion,
        'pagos'       => $pagos,
        'conAnulados' => $conAnulados,
        'cronograma'  => $cronograma,
        'invitados'   => $invitados,
        'completo'    => count($secciones) > 1,
    ]) ?>
<?php endforeach; ?>

</body>
</html>

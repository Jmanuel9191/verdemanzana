<?php
/**
 * Ficha del evento con pestañas:
 *   Cotización · Facturación · Cronograma · Guía (solo si tiene datos) · Lista de invitados
 * Cada pestaña es un parcial de views/site/ficha/ (se reutilizan en la vista del cliente).
 *
 * @var $this            yii\web\View
 * @var $model           app\models\Reservas
 * @var $cotizacion      app\models\ReservaCotizacion|null   null = reserva vieja
 * @var $pagos           app\models\ReservaPago[]            solo válidos
 * @var $cronograma      app\models\ReservaCronograma
 * @var $tieneGuia       bool
 * @var $mostrarComision bool
 * @var $esCliente       bool   true = /perfilreserva (el cliente escribe observaciones,
 *                              sube/borra sus imágenes y carga invitados)
 */

use app\assets_b\AppAsset;
use yii\helpers\Html;
use yii\web\View;

// Si alguien la renderiza solo con 'model', se cargan los datos que faltan
if (!isset($cronograma)) {
    extract(\app\controllers\SiteController::datosFicha($model));
}

$esCliente = $esCliente ?? false;

// Popup de imágenes (Magnific Popup) + estilos de la ficha
$this->registerCssFile('@web/assets_b/css/plugins.css', ['depends' => AppAsset::class]);
// Letras del encabezado (estilo "Wedding timeline")
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Cormorant+Garamond:ital@1&display=swap');
$this->registerCssFile('@web/assets_b/css/ficha-reserva.css', ['depends' => AppAsset::class]);
$this->registerJsFile('@web/assets_b/js/plugins.js', ['depends' => AppAsset::class, 'position' => View::POS_END]);
if ($esCliente) {
    // Subir / borrar imágenes en "Mis observaciones"
    $this->registerJsFile('@web/assets_b/js/ficha-cliente.js', ['depends' => AppAsset::class, 'position' => View::POS_END]);
}

$datos = [
    'model'           => $model,
    'cotizacion'      => $cotizacion,
    'cuentaInicial'   => $cuentaInicial ?? null,
    'pagos'           => $pagos,
    'cronograma'      => $cronograma,
    'mostrarComision' => $mostrarComision && !$esCliente,
    'esCliente'       => $esCliente,
];

$pestanas = [
    'cotizacion'  => ['Cotización', '@app/views/site/ficha/_cotizacion'],
    'facturacion' => ['Estado de cuenta', '@app/views/site/ficha/_facturacion'],
    'cronograma'  => ['Cronograma', '@app/views/site/ficha/_cronograma'],
];
if ($tieneGuia) {
    $pestanas['guia'] = ['Guía del evento', '@app/views/site/reservas/guiadecoracion'];
}
// Guía (archivo que sube el administrador: PDF, Word, Excel o imagen)
$pestanas['guiaarchivo'] = ['Guía', '@app/views/site/ficha/_guia'];
$pestanas['invitados'] = ['Lista de invitados', '@app/views/site/ficha/_invitados'];
?>

<div class="container vm-ficha">

    <?php
    // ---- Encabezado: cliente, nombre del evento y la línea "fecha | salón (paquete) | invitados"
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $t = $model->fecha_reserva ? strtotime($model->fecha_reserva) : null;
    $fechaTexto = $t ? (int)date('d', $t) . ' de ' . $meses[(int)date('m', $t)] . ' de ' . date('Y', $t) : '';

    $lineaPaquete = $cotizacion ? $cotizacion->lineaPaquete : null;
    $paqueteTexto = trim($lineaPaquete ? (string)$lineaPaquete->nombre : (string)$model->paquete_reservar);
    // Límite de la lista de invitados (si no tiene, la cantidad del paquete) y cuántos ya cargaron
    $invitados = (int)$model->limite_invitados ?: ($lineaPaquete ? (int)round((float)$lineaPaquete->cantidad) : 0);
    $cargados = (int)\app\models\Invitados::find()->where(['idreservas' => (int)$model->idreservas])->count();
    $nombreEvento = trim((string)$model->tipo_evento);

    $partes = array_filter([
        Html::encode($fechaTexto),
        Html::encode(trim('Salón ' . $model->sucursal)) . ($paqueteTexto !== '' ? ' <span>(Paquete ' . Html::encode($paqueteTexto) . ')</span>' : ''),
        $invitados > 0 ? '<a href="#invitados" class="vm-ir-invitados">' . $cargados . ' de ' . $invitados . ' invitados</a>' : '',
    ]);
    ?>
    <div class="vm-ficha-cabecera">
        <h1><?= Html::encode($model->nombre) ?></h1>
        <?php if ($nombreEvento !== '' && mb_strtolower($nombreEvento) !== mb_strtolower(trim((string)$model->nombre))): ?>
            <h2><?= Html::encode($nombreEvento) ?></h2>
        <?php endif; ?>
        <p class="vm-ficha-linea"><?= implode(' <b>|</b> ', $partes) ?></p>
        <?php if ((int)$model->estado === 0): ?>
            <span class="vm-etiqueta vm-etiqueta-gris">Reserva inactiva</span>
        <?php endif; ?>
    </div>

    <ul class="nav nav-tabs vm-tabs" role="tablist">
        <?php $primera = true; foreach ($pestanas as $clave => [$titulo]): ?>
            <li role="presentation" class="<?= $primera ? 'active' : '' ?>">
                <a href="#<?= $clave ?>" aria-controls="<?= $clave ?>" role="tab" data-toggle="tab"><?= Html::encode($titulo) ?></a>
            </li>
        <?php $primera = false; endforeach; ?>
    </ul>

    <div class="tab-content vm-tab-content">
        <?php $primera = true; foreach ($pestanas as $clave => [$titulo, $vista]): ?>
            <div role="tabpanel" class="tab-pane <?= $primera ? 'active' : '' ?>" id="<?= $clave ?>">
                <?= $this->render($vista, $datos) ?>
            </div>
        <?php $primera = false; endforeach; ?>
    </div>
</div>

<?php
$js = <<<JS
// Popup de imágenes: cada bloque .vm-galeria es una galería con flechas propias
$('.vm-galeria').each(function () {
    $(this).magnificPopup({
        delegate: 'a.vm-zoom',
        type: 'image',
        closeOnContentClick: false,
        removalDelay: 300,
        mainClass: 'mfp-with-zoom',
        zoom: {enabled: true, duration: 300},
        image: {verticalFit: true, titleSrc: 'title'},
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1],
            tPrev: 'Anterior',
            tNext: 'Siguiente',
            tCounter: '%curr% de %total%'
        },
        tClose: 'Cerrar (Esc)',
        tLoading: 'Cargando...'
    });
});

// "200 de 500 invitados" del encabezado abre la pestaña Lista de invitados
$('.vm-ir-invitados').on('click', function (e) {
    e.preventDefault();
    $('.vm-tabs a[href="#invitados"]').tab('show');
});

// Recordar la pestaña abierta (#facturacion, #invitados...) al recargar o volver
var hash = window.location.hash;
if (hash && $('.vm-tabs a[href="' + hash + '"]').length) {
    $('.vm-tabs a[href="' + hash + '"]').tab('show');
}
$('.vm-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if (history.replaceState) {
        history.replaceState(null, '', $(e.target).attr('href'));
    }
});
JS;
$this->registerJs($js);
?>

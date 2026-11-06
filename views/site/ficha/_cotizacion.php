<?php
/**
 * Pestaña COTIZACIÓN: datos generales, presupuesto, detalles (con imágenes),
 * descargas y lo que cargó el cliente.
 *
 * @var $this            yii\web\View
 * @var $model           app\models\Reservas
 * @var $cotizacion      app\models\ReservaCotizacion|null   null = reserva vieja
 * @var $mostrarComision bool
 * @var $esCliente       bool  true = vista del cliente (observaciones editables)
 */

use app\helpers\FormatoHelper;
use app\models\PresupuestoForm;
use app\models\ReservaCotizacionDetalle;
use yii\helpers\Html;
use yii\helpers\Url;

$id = (int)$model->idreservas;
$galeria = '@app/views/site/ficha/_galeria';

// ---- auxiliares
$v = function ($x) {
    $x = trim((string)$x);
    return $x !== '' ? Html::encode($x) : '<span class="vm-guion">—</span>';
};
// Textos con formato (CKEditor): se limpian al mostrar
$h = function ($x) {
    $l = FormatoHelper::limpiarHtml($x);
    return $l !== null ? $l : '<span class="vm-guion">—</span>';
};
$fecha = function ($f) {
    if (!$f) {
        return '';
    }
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
        'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $t = strtotime($f);
    return date('d', $t) . ' de ' . $meses[(int)date('m', $t)] . ' de ' . date('Y', $t);
};
// Monto viejo (texto libre en reservas1): si es número se formatea en Bs
$montoViejo = function ($x) {
    $x = trim((string)$x);
    if ($x === '') {
        return '<span class="vm-guion">—</span>';
    }
    $n = FormatoHelper::normalizarMonto($x);
    return is_numeric($n) ? FormatoHelper::formatoBs($n) : Html::encode($x);
};
$cant = function ($x) {
    $f = (float)$x;
    return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '.');
};
$pct = function ($x) {
    $f = round((float)$x, 2);
    return floor($f) == $f ? (string)(int)$f : rtrim(rtrim(number_format($f, 2, ',', ''), '0'), ',');
};
// Archivo subido a /imagen/usuarios (solo si existe de verdad)
$archivo = function ($nombre, $texto) {
    $nombre = basename((string)$nombre);
    if ($nombre === '' || !is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . $nombre)) {
        return '';
    }
    return Html::a('<i class="fa-solid fa-download"></i> ' . Html::encode($texto),
        Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($nombre),
        ['class' => 'btn vm-btn-borde', 'target' => '_blank', 'rel' => 'noopener']);
};
$pdf = function ($tipo, $texto) use ($id) {
    return Html::a('<i class="fa-solid fa-file-pdf"></i> ' . Html::encode($texto),
        ['site/reserva-pdf', 'id' => $id, 'tipo' => $tipo],
        ['class' => 'btn vm-btn-pdf', 'target' => '_blank', 'rel' => 'noopener']);
};

$organizadora = $model->idorganizadora ? $model->organizadora : null;
$comision = trim((string)$model->comision_organizadora);
$paquete = $cotizacion ? $cotizacion->lineaPaquete : null;

// Descargas disponibles (si no hay ninguna, el bloque no se muestra)
// Los PDF generados por el sistema están comentados por ahora; solo se muestran
// los archivos subidos desde el admin. Para volver a mostrarlos, descomentar.
$descargas = array_filter([
    // $cotizacion ? $pdf('presupuesto', 'Presupuesto') : '',
    // $cotizacion ? $pdf('detalles', 'Detalles del evento') : '',
    // $cotizacion ? $pdf('completo', 'Ficha completa') : '',
    $archivo($model->presupuestofile, 'Archivo de presupuesto'),
]);
?>

<?php if ($descargas): ?>
    <div class="vm-descargas">
        <span>Descargar:</span> <?= implode(' ', $descargas) ?>
    </div>
<?php endif; ?>

<!-- ===================== DATOS GENERALES ===================== -->
<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Datos generales del evento</h2>
    <div class="row vm-datos">
        <div class="col-md-4 col-sm-6"><small>Cliente</small><?= $v($model->nombre) ?></div>
        <div class="col-md-4 col-sm-6"><small>Teléfono</small><?= $v($model->telefono) ?></div>
        <div class="col-md-4 col-sm-6"><small>Email</small><?= $v($model->email) ?></div>
        <div class="col-md-4 col-sm-6"><small>Nombre del evento</small><?= $v($model->tipo_evento) ?></div>
        <div class="col-md-4 col-sm-6"><small>Tipo de evento</small><?= $v(PresupuestoForm::TIPOS_EVENTO[$model->tipoevento] ?? $model->tipoevento) ?></div>
        <div class="col-md-4 col-sm-6"><small>Fecha del evento</small><?= $v($fecha($model->fecha_reserva)) ?></div>
        <div class="col-md-4 col-sm-6"><small>Hora de inicio</small><?= $v($model->hora_inicio) ?></div>
        <div class="col-md-4 col-sm-6"><small>Salón</small><?= $v($model->sucursal) ?></div>
        <div class="col-md-4 col-sm-6"><small>Paquete</small><?= $v($paquete ? $paquete->nombre : $model->paquete_reservar) ?></div>
        <?php if ($paquete): ?>
            <div class="col-md-4 col-sm-6"><small>Invitados</small><?= $v((string)(int)round((float)$paquete->cantidad)) ?></div>
        <?php endif; ?>
    </div>
</section>

<!-- ===================== PRESUPUESTO ===================== -->
<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Presupuesto</h2>
    <div class="row">
        <div class="<?= $model->galeriapresupuesto ? 'col-md-8' : 'col-md-12' ?>">

            <?php if ($cotizacion): ?>
                <?php $pagado = $cotizacion->getTotalPagado(); ?>
                <div class="table-responsive">
                    <table class="table vm-tabla">
                        <thead>
                        <tr>
                            <th>Descripción</th>
                            <th class="text-right">Cant.</th>
                            <th class="text-right hidden-xs">Precio</th>
                            <th class="text-right hidden-xs">Desc.</th>
                            <th class="text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cotizacion->detalles as $l):
                            $sec = $l->seccion;
                            $clase = $sec === ReservaCotizacionDetalle::SECCION_PAQUETE ? 'vm-etq-paquete'
                                : ($sec === ReservaCotizacionDetalle::SECCION_PROVEEDOR ? 'vm-etq-proveedor' : 'vm-etq-item'); ?>
                            <tr>
                                <td>
                                    <span class="vm-etiqueta <?= $clase ?>"><?= Html::encode($l->getSeccionTexto()) ?></span>
                                    <strong><?= Html::encode($l->nombre) ?></strong>
                                    <?php if ($l->empresa): ?><small class="vm-gris">· <?= Html::encode($l->empresa) ?></small><?php endif; ?>
                                </td>
                                <td class="text-right vm-nowrap"><?= $cant($l->cantidad) ?> <small class="vm-gris"><?= Html::encode(mb_strtolower($l->getUnidadTexto())) ?></small></td>
                                <td class="text-right vm-nowrap hidden-xs"><?= FormatoHelper::formatoBs($l->precio_unitario) ?></td>
                                <td class="text-right vm-nowrap hidden-xs">
                                    <?php if (ReservaCotizacionDetalle::aCentavos($l->descuento_monto) > 0): ?>
                                        − <?= FormatoHelper::formatoBs($l->descuento_monto) ?> <small class="vm-gris">(<?= $pct($l->descuento_porcentaje) ?>%)</small>
                                    <?php else: ?>
                                        <span class="vm-guion">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right vm-nowrap"><strong><?= FormatoHelper::formatoBs($l->total) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <?php if (trim((string)$cotizacion->observaciones) !== ''): ?>
                            <small class="vm-etq-campo">Observaciones del presupuesto</small>
                            <div class="vm-nota"><?= nl2br(Html::encode($cotizacion->observaciones)) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <table class="table vm-totales">
                            <tr><th>Subtotal</th><td><?= FormatoHelper::formatoBs($cotizacion->subtotal) ?></td></tr>
                            <?php if (ReservaCotizacionDetalle::aCentavos($cotizacion->descuento_total) > 0): ?>
                                <tr><th>Descuento</th><td>− <?= FormatoHelper::formatoBs($cotizacion->descuento_total) ?></td></tr>
                            <?php endif; ?>
                            <tr class="vm-fila-total"><th>Total</th><td><?= FormatoHelper::formatoBs($cotizacion->total) ?></td></tr>
                            <tr><th>Cancelado</th><td><?= FormatoHelper::formatoBs($pagado) ?></td></tr>
                            <tr class="vm-fila-saldo"><th>Saldo</th><td><?= FormatoHelper::formatoBs($cotizacion->getSaldo()) ?></td></tr>
                        </table>
                    </div>
                </div>

            <?php else: /* ---- reserva vieja: montos de reservas1 ---- */ ?>
                <div class="row vm-datos">
                    <div class="col-sm-4"><small>Presupuesto paquete</small><?= $montoViejo($model->presupuesto) ?></div>
                    <div class="col-sm-4"><small>Monto cancelado</small><?= $montoViejo($model->monto_canceladop) ?></div>
                    <div class="col-sm-4"><small>Saldo a pagar</small><?= $montoViejo($model->saldo_pagarp) ?></div>
                </div>
                <?php if (trim((string)$model->servicios_adicionales) !== ''): ?>
                    <h3 class="vm-subtitulo">Servicios adicionales</h3>
                    <div class="row vm-datos">
                        <div class="col-sm-4"><small>Servicios adicionales</small><?= $montoViejo($model->servicios_adicionales) ?></div>
                        <div class="col-sm-4"><small>Monto cancelado</small><?= $montoViejo($model->monto_cancelados) ?></div>
                        <div class="col-sm-4"><small>Saldo por pagar</small><?= $montoViejo($model->saldo_pagars) ?></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($model->galeriapresupuesto): ?>
            <div class="col-md-4">
                <?= $this->render($galeria, ['imagenes' => $model->galeriapresupuesto, 'carpeta' => 'galeriapresupuesto', 'titulo' => 'Presupuesto']) ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===================== DETALLES ===================== -->
<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Detalles del evento</h2>

    <h3 class="vm-subtitulo">Organizadora y personal</h3>
    <div class="row vm-datos">
        <div class="col-md-4 col-sm-6"><small>Organizadora</small><?= $v($organizadora ? $organizadora->nombre : 'Sin organizadora asignada') ?></div>
        <?php if ($organizadora && trim((string)$organizadora->telefono) !== ''):
            $tel = preg_replace('/\D+/', '', (string)$organizadora->telefono); ?>
            <div class="col-md-4 col-sm-6"><small>Teléfono de la organizadora</small>
                <?= Html::a(Html::encode($organizadora->telefono), 'tel:' . $tel) ?>
                <?php if (strlen($tel) >= 8): ?>
                    · <?= Html::a('<i class="fa-brands fa-whatsapp"></i> WhatsApp',
                        'https://wa.me/' . (strlen($tel) === 8 ? '591' . $tel : $tel),
                        ['target' => '_blank', 'rel' => 'noopener', 'class' => 'vm-link-wa']) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($mostrarComision): ?>
            <div class="col-md-4 col-sm-6"><small>Tu comisión</small>
                <?= $comision === '' ? '<span class="vm-gris"><em>No cargada</em></span>'
                    : (is_numeric($comision) ? FormatoHelper::formatoBs($comision) : Html::encode($comision)) ?>
            </div>
        <?php endif; ?>
        <div class="col-md-4 col-sm-6"><small>Cantidad de garzones</small><?= $v($model->cant_garzones) ?></div>
    </div>

    <?php
    // Secciones de detalle: [título, campos, galería, carpeta]
    $secciones = [
        ['Equipamiento', [
            ['Color de sillas', $v($model->color_sillas), 4],
            ['Color de mantel', $v($model->color_mantel), 4],
            ['Color de servilletas', $v($model->color_servilleta), 4],
            ['En mesa', $v($model->en_mesa), 4],
            ['Tipo de bebidas', $h($model->tipo_bebidas), 12],
            ['Vajillas y vasos en mesa', $h($model->vajillasyvasisenmesa), 12],
            ['Catering', $h($model->katering), 12],
        ], $model->galeriaequipamiento, 'galeriaequipamiento'],
        ['Decoración', [
            ['Decoradora', $v($model->nombre_decoradora), 12],
            ['Decoración del salón', $h($model->detalle_decoradora), 12],
            ['Decoración de la iglesia', $h($model->detalle_decoracioniglesia), 12],
            ['Otros detalles', $h($model->otros_detalles), 12],
        ], $model->galeriadecoracionus, 'galeriadecoracionus'],
        ['Coordinación', [
            ['Coordinadora', $v($model->nombre_cordinadora), 4],
            ['Sub-coordinadora', $v($model->nombre_subcordinadora), 4],
            ['Supervisora', $v($model->nombre_supervisora), 4],
            ['Otra información', $h($model->otrainfocoordinacion), 12],
        ], $model->galeriacoordinacion, 'galeriacoordinacion'],
    ];
    foreach ($secciones as [$titulo, $campos, $imagenes, $carpeta]):
        $conFotos = !empty($imagenes); ?>
        <h3 class="vm-subtitulo"><?= Html::encode($titulo) ?></h3>
        <div class="row">
            <div class="<?= $conFotos ? 'col-md-8' : 'col-md-12' ?>">
                <div class="row vm-datos">
                    <?php foreach ($campos as [$etq, $valor, $ancho]): ?>
                        <div class="col-sm-<?= $ancho === 12 ? 12 : 6 ?> col-md-<?= $conFotos && $ancho === 4 ? 6 : $ancho ?>">
                            <small><?= Html::encode($etq) ?></small><div class="vm-html"><?= $valor ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($conFotos): ?>
                <div class="col-md-4">
                    <?= $this->render($galeria, ['imagenes' => $imagenes, 'carpeta' => $carpeta, 'titulo' => $titulo]) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>

<?php if (!empty($esCliente)): ?>
    <?= $this->render('@app/views/site/ficha/_observaciones_cliente', ['model' => $model]) ?>
<?php else: ?>
<!-- ===================== LO QUE CARGÓ EL CLIENTE ===================== -->
<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Observaciones del cliente</h2>
    <div class="row">
        <?php foreach ([
            ['Decoración', 'fa-palette', $model->comentario_decoracion, $model->galeriadecoracion, 'reservasdecoracion'],
            ['Cronograma', 'fa-clock', $model->comentario_cronograma, $model->galeriacronograma, 'reservascronograma'],
        ] as [$tema, $icono, $comentario, $imagenes, $carpeta]):
            $comentario = trim(strip_tags((string)$comentario)); ?>
            <div class="col-md-6">
                <div class="vm-tarjeta">
                    <h3><i class="fa-solid <?= $icono ?>"></i> Sobre <?= mb_strtolower($tema) ?></h3>
                    <?php if ($comentario !== ''): ?>
                        <div class="vm-cita"><?= nl2br(Html::encode($comentario)) ?></div>
                    <?php else: ?>
                        <p class="vm-vacio"><i class="fa-solid fa-comment-slash"></i> El cliente no dejó observaciones.</p>
                    <?php endif; ?>

                    <small class="vm-etq-campo">Imágenes del cliente</small>
                    <?= $this->render($galeria, [
                        'imagenes' => $imagenes,
                        'carpeta'  => $carpeta,
                        'titulo'   => 'Cliente: ' . $tema,
                        'vacio'    => 'El cliente no subió imágenes.',
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

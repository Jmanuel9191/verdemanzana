<?php

use app\helpers\FormatoHelper;
use app\models\PresupuestoForm;
use app\models\ReservaCronograma;
use app\models\ReservaPago;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $form PresupuestoForm  (solo para reutilizar la tabla del presupuesto) */
/* @var $historial app\models\ReservaCotizacionHistorial[] */
/* @var $pagos ReservaPago[] */
/* @var $cronograma ReservaCronograma */
/* @var $invitados int */

$id = (int)$reserva->idreservas;
$this->title = 'Reserva: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = $reserva->nombre;

$paquete = $cotizacion->lineaPaquete;
$pagado = $cotizacion->getTotalPagado();
$saldo = $cotizacion->getSaldo();
$limite = (int)$reserva->limite_invitados;

// ---- auxiliares de presentación
$e = function ($v) {
    $v = trim((string)$v);
    return $v !== '' ? Html::encode($v) : '<span class="text-muted">—</span>';
};
// Los textos con formato se limpian también al mostrar (los datos viejos no pasaron por la limpieza)
$html = function ($v) {
    $l = FormatoHelper::limpiarHtml($v);
    return $l !== null ? $l : '<span class="text-muted">—</span>';
};
$texto = function ($v) {
    $v = trim(strip_tags((string)$v));
    return $v !== '' ? nl2br(Html::encode($v)) : '<span class="text-muted">—</span>';
};
$boton = function ($icono, $texto, $url, $clase = 'default') {
    return Html::a('<i class="fa ' . $icono . '"></i> ' . $texto, $url, ['class' => 'btn btn-' . $clase . ' btn-sm']);
};
$archivo = function ($nombre, $texto) {
    $nombre = basename((string)$nombre);
    if ($nombre === '' || !is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . $nombre)) {
        return null;
    }
    return Html::a('<i class="fa fa-download"></i> ' . Html::encode($texto),
        Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($nombre), ['target' => '_blank', 'class' => 'btn btn-default btn-sm']);
};
$galeria = function ($imagenes, $carpeta) {
    $out = '';
    foreach ($imagenes as $img) {
        $url = Yii::getAlias('@web') . '/imagen/' . $carpeta . '/' . rawurlencode(basename((string)$img->archivo));
        $out .= '<a href="' . Html::encode($url) . '" target="_blank" class="miniatura"><img src="' . Html::encode($url) . '" alt="" loading="lazy"></a>';
    }
    return $out !== '' ? '<div class="miniaturas">' . $out . '</div>' : '<p class="text-muted">Sin imágenes.</p>';
};

$organizadora = $reserva->idorganizadora ? $reserva->organizadora : null;
// Guía del cliente (formulario anterior): los botones salen solo si tiene algo cargado
$tieneGuia = \app\controllers\SiteController::reservaTieneGuia($id);
$urlCampos = $reserva->tipoevento === 'matrimonio'
    ? ['reservas/form-campos-extra', 'idreserva' => $id]
    : ['reservas/form-campos-extra-quince', 'idreserva' => $id];
$comision = trim((string)$reserva->comision_organizadora);
$comisionTxt = $comision === '' ? '' : (is_numeric($comision) ? FormatoHelper::formatoBs($comision) : $comision);
$fechaHora = function ($v) {
    return $v ? date('d/m/Y H:i', strtotime($v)) : '';
};
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?>
        <?php if ((int)$reserva->estado === 1): ?>
            <span class="label label-success" style="font-size:12px;vertical-align:middle">Activa</span>
        <?php else: ?>
            <span class="label label-default" style="font-size:12px;vertical-align:middle">Inactiva</span>
        <?php endif; ?>
    </h1>
</section>

<section class="content reserva-pasos ficha-reserva">

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
        'total'     => $cotizacion->total,
        'pagado'    => $pagado,
    ]) ?>

    <!-- ===================== ACCESOS ===================== -->
    <div class="box box-success">
        <div class="box-body accesos">
            <?= $boton('', 'Datos y presupuesto', ['presupuesto', 'id' => $id], 'success') ?>
            <?= $boton('', 'Modificar presupuesto', ['modificar-presupuesto', 'id' => $id], 'warning') ?>
            <?= $boton('', 'Pagos', ['pagos', 'id' => $id], 'primary') ?>
            <?= $boton('', 'Detalles', ['detalles', 'id' => $id], 'success') ?>
            <?= $boton('', 'Cronograma', ['cronograma', 'id' => $id], 'success') ?>
            <?= $boton('', 'Lista de invitados (' . $invitados . ($limite ? '/' . $limite : '') . ')', ['reservas/invitados', 'idreservas' => $id], 'info') ?>
            <?php if ($tieneGuia): ?>
                <?= $boton('', 'Guía del cliente', ['reservas/viewguia', 'id' => $id]) ?>
                <?= $boton('', 'Añadir u Ordenar Campos', $urlCampos) ?>
            <?php endif; ?>
            <?= $boton('', 'Cambiar contraseña', ['contrasena', 'id' => $id], 'danger') ?>
            <?= Html::a(' PDF completo', ['pdf', 'id' => $id, 'tipo' => 'completo'],
                ['class' => 'btn btn-danger btn-sm', 'target' => '_blank', 'title' => 'Todo junto: presupuesto, estado de cuenta, detalles y cronograma']) ?>
            <?= $boton('fa-list', 'Listado de reservas', ['reservas/index']) ?>
        </div>
    </div>

    <!-- ===================== 1. CLIENTE Y EVENTO ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> Cliente y evento</h3>
            <div class="box-tools"><?= $boton('fa-pencil', 'Editar', ['presupuesto', 'id' => $id]) ?></div>
        </div>
        <div class="box-body">
            <div class="row datos">
                <div class="col-md-4"><small>Cliente</small><?= $e($reserva->nombre) ?></div>
                <div class="col-md-4"><small>Teléfono</small><?= $e($reserva->telefono) ?></div>
                <div class="col-md-4"><small>Email / usuario</small><?= $e($reserva->email) ?>
                    <?php if (trim((string)$reserva->contrasena) === ''): ?>
                        <br><span class="label label-danger"><i class="fa fa-key"></i> Sin contraseña: no puede iniciar sesión</span>
                    <?php endif; ?>
                </div>
                <div class="col-md-4"><small>Nombre del evento</small><?= $e($reserva->tipo_evento) ?></div>
                <div class="col-md-4"><small>Tipo de evento</small><?= $e(PresupuestoForm::TIPOS_EVENTO[$reserva->tipoevento] ?? $reserva->tipoevento) ?></div>
                <div class="col-md-4"><small>Fecha · hora</small><?= $e(($reserva->fecha_reserva ? date('d/m/Y', strtotime($reserva->fecha_reserva)) : '') . ($reserva->hora_inicio ? ' · ' . $reserva->hora_inicio : '')) ?></div>
                <div class="col-md-4"><small>Sucursal</small><?= $e($reserva->sucursal) ?></div>
                <div class="col-md-4"><small>Registrada</small><?= $e($fechaHora($reserva->fecha_registro)) ?></div>
                <div class="col-md-4"><small>Archivo de presupuesto</small><?= $archivo($reserva->presupuestofile, 'Descargar') ?: '<span class="text-muted">—</span>' ?></div>
            </div>
        </div>
    </div>

    <!-- ===================== 2. PRESUPUESTO ===================== -->
    <div class="box box-default">
        <?= $this->render('_presupuesto_lectura', ['form' => $form, 'pagado' => $pagado]) ?>
        <?= $this->render('_historial', ['historial' => $historial, 'cotizacion' => $cotizacion]) ?>
    </div>

    <!-- ===================== 3. PAGOS ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> Pagos</h3>
            <div class="box-tools"><?= Html::a('<i class="fa fa-file-pdf-o"></i> Estado de cuenta', ['pdf', 'id' => $id, 'tipo' => 'pagos'], ['class' => 'btn btn-danger btn-sm', 'target' => '_blank']) ?> <?= $boton('fa-plus', 'Registrar / anular pagos', ['pagos', 'id' => $id]) ?></div>
        </div>
        <div class="box-body">
            <?php if (empty($pagos)): ?>
                <p class="text-muted">Todavía no hay pagos registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered tabla-pagos">
                        <thead><tr><th>N.º</th><th>Fecha</th><th class="text-right">Monto</th><th>Forma</th><th>Comprobante</th><th>Registrado</th><th>Estado</th></tr></thead>
                        <tbody>
                        <?php foreach ($pagos as $p): ?>
                            <tr class="<?= $p->esValido() ? '' : 'pago-anulado' ?>">
                                <td><?= (int)$p->idpago ?></td>
                                <td class="tachable"><?= Html::encode(date('d/m/Y', strtotime($p->fecha_pago))) ?></td>
                                <td class="text-right tachable"><b><?= Html::encode($p->getMontoTexto()) ?></b></td>
                                <td class="tachable"><?= Html::encode($p->getFormaPagoTexto()) ?></td>
                                <td><?= $p->getRutaComprobante() !== null
                                        ? Html::a('<i class="fa fa-paperclip"></i> Ver', ['comprobante', 'idpago' => $p->idpago], ['target' => '_blank', 'rel' => 'noopener'])
                                        : '<span class="text-danger">Falta</span>' ?></td>
                                <td><small><?= Html::encode($fechaHora($p->fecha_registro)) ?> · <?= Html::encode(ReservaPago::nombreAdmin($p->adminRegistro)) ?></small></td>
                                <td>
                                    <?php if ($p->esValido()): ?>
                                        <span class="label label-success">Válido</span>
                                    <?php else: ?>
                                        <span class="label label-default">Anulado</span>
                                        <br><small class="text-danger"><?= Html::encode($fechaHora($p->fecha_anulacion)) ?> · <?= Html::encode(ReservaPago::nombreAdmin($p->adminAnulo)) ?>: <?= Html::encode((string)$p->motivo_anulacion) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-5 col-md-offset-7">
                    <table class="table tabla-totales">
                        <tr><th>Total</th><td><?= FormatoHelper::formatoBs($cotizacion->total) ?></td></tr>
                        <tr><th>Cancelado a la fecha</th><td><?= FormatoHelper::formatoBs($pagado) ?></td></tr>
                        <tr class="fila-total"><th>Saldo</th><td><?= FormatoHelper::formatoBs($saldo) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== 4. DETALLES ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> Detalles del evento</h3>
            <div class="box-tools"><?= Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $id, 'tipo' => 'detalles'], ['class' => 'btn btn-danger btn-sm', 'target' => '_blank']) ?> <?= $boton('fa-pencil', 'Editar', ['detalles', 'id' => $id]) ?></div>
        </div>
        <div class="box-body">
            <h4 class="subtitulo">Organizadora y personal</h4>
            <div class="row datos">
                <div class="col-md-4"><small>Organizadora</small><?= $e($organizadora ? $organizadora->nombre : '') ?></div>
                <div class="col-md-4"><small>Comisión</small><?= $e($comisionTxt) ?></div>
                <div class="col-md-4"><small>Garzones</small><?= $e($reserva->cant_garzones) ?></div>
                <div class="col-md-12"><small>Lista de invitados</small>
                    <?= $invitados ?> cargados<?= $limite ? ' de ' . $limite : '' ?>
                    · <?= Html::a('Ver / cargar', ['reservas/invitados', 'idreservas' => $id]) ?>
                </div>
            </div>

            <h4 class="subtitulo">Equipamiento</h4>
            <div class="row datos">
                <div class="col-md-3"><small>Color de silla</small><?= $e($reserva->color_sillas) ?></div>
                <div class="col-md-3"><small>Color de servilletas</small><?= $e($reserva->color_servilleta) ?></div>
                <div class="col-md-3"><small>Color de mantel</small><?= $e($reserva->color_mantel) ?></div>
                <div class="col-md-3"><small>En mesa</small><?= $e($reserva->en_mesa) ?></div>
                <div class="col-md-4"><small>Tipo de bebidas</small><div class="html"><?= $html($reserva->tipo_bebidas) ?></div></div>
                <div class="col-md-4"><small>Vajillas y vasos en mesa</small><div class="html"><?= $html($reserva->vajillasyvasisenmesa) ?></div></div>
                <div class="col-md-4"><small>Catering</small><div class="html"><?= $html($reserva->katering) ?></div></div>
            </div>

            <h4 class="subtitulo">Decoración</h4>
            <div class="row datos">
                <div class="col-md-12"><small>Decoradora</small><?= $e($reserva->nombre_decoradora) ?></div>
                <div class="col-md-4"><small>Decoración del salón</small><div class="html"><?= $html($reserva->detalle_decoradora) ?></div></div>
                <div class="col-md-4"><small>Decoración de la iglesia</small><div class="html"><?= $html($reserva->detalle_decoracioniglesia) ?></div></div>
                <div class="col-md-4"><small>Otros detalles</small><div class="html"><?= $html($reserva->otros_detalles) ?></div></div>
            </div>

            <h4 class="subtitulo">Coordinación</h4>
            <div class="row datos">
                <div class="col-md-4"><small>Coordinadora</small><?= $e($reserva->nombre_cordinadora) ?></div>
                <div class="col-md-4"><small>Sub-coordinadora</small><?= $e($reserva->nombre_subcordinadora) ?></div>
                <div class="col-md-4"><small>Supervisora</small><?= $e($reserva->nombre_supervisora) ?></div>
                <div class="col-md-12"><small>Otra información</small><div class="html"><?= $html($reserva->otrainfocoordinacion) ?></div></div>
            </div>
        </div>
    </div>

    <!-- ===================== GALERÍAS DEL ADMIN (debajo de Detalles) ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> Galerías (admin)</h3>
        </div>
        <div class="box-body accesos">
            <?php foreach ([
                'galeriapresupuesto'  => ['Presupuesto', $reserva->galeriapresupuesto],
                'galeriaequipamiento' => ['Equipamiento', $reserva->galeriaequipamiento],
                'galeriadecoracionus' => ['Decoración', $reserva->galeriadecoracionus],
                'galeriacoordinacion' => ['Coordinación', $reserva->galeriacoordinacion],
            ] as $accion => [$nombre, $imagenes]): ?>
                <?= Html::a('<i class="fa fa-upload"></i> ' . $nombre . ' <span class="badge">' . count($imagenes) . '</span>',
                    ['reservas/' . $accion, 'id' => $id], ['class' => 'btn btn-default btn-sm', 'target' => '_blank']) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- ===================== 5. CRONOGRAMA ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> <?= Html::encode(ReservaCronograma::titulo($reserva)) ?></h3>
            <div class="box-tools"><?= Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $id, 'tipo' => 'cronograma'], ['class' => 'btn btn-danger btn-sm', 'target' => '_blank']) ?> <?= $boton('fa-pencil', 'Editar', ['cronograma', 'id' => $id]) ?></div>
        </div>
        <div class="box-body">
            <?php if (!$cronograma->isNewRecord && $cronograma->tieneContenido()): ?>
                <div class="cronograma-vista"><?= $cronograma->contenido /* limpio con HTMLPurifier al guardar */ ?></div>
            <?php else: ?>
                <p class="text-muted">Todavía no se cargó el cronograma.</p>
            <?php endif; ?>
            <?php if ($a = $archivo($reserva->cronogramafile, 'Archivo de cronograma (anterior)')): ?>
                <p style="margin-top:10px"><?= $a ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===================== 6. LO QUE CARGA EL CLIENTE ===================== -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"> Lo que cargó el cliente</h3>
        </div>
        <div class="box-body">
            <div class="row datos">
                <div class="col-md-6"><small>Comentarios sobre la decoración</small><?= $texto($reserva->comentario_decoracion) ?></div>
                <div class="col-md-6"><small>Comentarios sobre el cronograma</small><?= $texto($reserva->comentario_cronograma) ?></div>
            </div>
            <h4 class="subtitulo">Imágenes de decoración (cliente)</h4>
            <?= $galeria($reserva->galeriadecoracion, 'reservasdecoracion') ?>
            <h4 class="subtitulo">Imágenes de cronograma (cliente)</h4>
            <?= $galeria($reserva->galeriacronograma, 'reservascronograma') ?>
            <?php if ($a = $archivo($reserva->listainvitadosfile, 'Lista de invitados (archivo anterior)')): ?>
                <p style="margin-top:10px"><?= $a ?></p>
            <?php endif; ?>
        </div>
    </div>

</section>


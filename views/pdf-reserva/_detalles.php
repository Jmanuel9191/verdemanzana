<?php

use app\helpers\FormatoHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $invitados int */
/* @var $completo bool */
/* @var $mostrarComision bool  (false = no imprimir la comisión) */

$r = $reserva;
$v = function ($x) {
    $x = trim((string)$x);
    return $x !== '' ? Html::encode($x) : '<span class="muted">—</span>';
};
// Los textos con formato se limpian también al imprimir (los datos viejos no pasaron por la limpieza)
$h = function ($x) {
    $l = FormatoHelper::limpiarHtml($x);
    return $l !== null ? $l : '<span class="muted">—</span>';
};
$organizadora = $r->idorganizadora ? $r->organizadora : null;
$comision = trim((string)$r->comision_organizadora);
$limite = (int)$r->limite_invitados;
?>
<?= $this->render('_cabecera', ['reserva' => $reserva, 'cotizacion' => $cotizacion, 'titulo' => 'Detalles del evento']) ?>

<h2 class="doc-seccion">Organizadora y personal</h2>
<table class="datos">
    <tr>
        <td width="40%"><span class="etq">Organizadora</span><br><?= $v($organizadora ? $organizadora->nombre : '') ?></td>
        <?php if ($mostrarComision ?? true): ?>
        <td width="30%"><span class="etq">Comisión</span><br><?= $comision === '' ? '<span class="muted">—</span>' : Html::encode(is_numeric($comision) ? FormatoHelper::formatoBs($comision) : $comision) ?></td>
        <?php else: ?>
        <td width="30%"></td>
        <?php endif; ?>
        <td width="30%"><span class="etq">Garzones</span><br><?= $v($r->cant_garzones) ?></td>
    </tr>
    <tr>
        <td colspan="3"><span class="etq">Lista de invitados</span><br><?= $invitados ?> cargados<?= $limite ? ' de ' . $limite : '' ?></td>
    </tr>
</table>

<h2 class="doc-seccion">Equipamiento</h2>
<table class="datos">
    <tr>
        <td width="25%"><span class="etq">Color de silla</span><br><?= $v($r->color_sillas) ?></td>
        <td width="25%"><span class="etq">Color de servilletas</span><br><?= $v($r->color_servilleta) ?></td>
        <td width="25%"><span class="etq">Color de mantel</span><br><?= $v($r->color_mantel) ?></td>
        <td width="25%"><span class="etq">En mesa</span><br><?= $v($r->en_mesa) ?></td>
    </tr>
</table>
<h3 class="doc-sub">Tipo de bebidas</h3><div><?= $h($r->tipo_bebidas) ?></div>
<h3 class="doc-sub">Vajillas y vasos en mesa</h3><div><?= $h($r->vajillasyvasisenmesa) ?></div>
<h3 class="doc-sub">Catering</h3><div><?= $h($r->katering) ?></div>

<h2 class="doc-seccion">Decoración</h2>
<table class="datos"><tr><td><span class="etq">Decoradora</span><br><?= $v($r->nombre_decoradora) ?></td></tr></table>
<h3 class="doc-sub">Decoración del salón</h3><div><?= $h($r->detalle_decoradora) ?></div>
<h3 class="doc-sub">Decoración de la iglesia</h3><div><?= $h($r->detalle_decoracioniglesia) ?></div>
<h3 class="doc-sub">Otros detalles</h3><div><?= $h($r->otros_detalles) ?></div>

<h2 class="doc-seccion">Coordinación</h2>
<table class="datos">
    <tr>
        <td width="34%"><span class="etq">Coordinadora</span><br><?= $v($r->nombre_cordinadora) ?></td>
        <td width="33%"><span class="etq">Sub-coordinadora</span><br><?= $v($r->nombre_subcordinadora) ?></td>
        <td width="33%"><span class="etq">Supervisora</span><br><?= $v($r->nombre_supervisora) ?></td>
    </tr>
</table>
<h3 class="doc-sub">Otra información</h3><div><?= $h($r->otrainfocoordinacion) ?></div>

<?php if ($completo && (trim(strip_tags((string)$r->comentario_decoracion)) !== '' || trim(strip_tags((string)$r->comentario_cronograma)) !== '')): ?>
    <h2 class="doc-seccion">Comentarios del cliente</h2>
    <h3 class="doc-sub">Sobre la decoración</h3><div><?= nl2br(Html::encode(trim(strip_tags((string)$r->comentario_decoracion)))) ?: '<span class="muted">—</span>' ?></div>
    <h3 class="doc-sub">Sobre el cronograma</h3><div><?= nl2br(Html::encode(trim(strip_tags((string)$r->comentario_cronograma)))) ?: '<span class="muted">—</span>' ?></div>
<?php endif; ?>

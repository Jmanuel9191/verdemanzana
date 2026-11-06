<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $linea app\models\ReservaCotizacionDetalle|null  null = fila plantilla (nueva, con selector) */
/* @var $indice int|string  posición en el arreglo Lineas[] ('__i__' en la plantilla) */
/* @var $catalogoItems array  iditem => datos (PresupuestoForm::catalogoItems) */

$num = function ($valor, int $decimales = 2) {
    if ($valor === null || $valor === '') {
        return '';
    }
    return number_format((float)$valor, $decimales, ',', '.');
};
$cantidad = function ($valor) {
    if ($valor === null || $valor === '') {
        return '1';
    }
    $f = (float)$valor;
    return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '');
};
$porcentaje = function ($valor) {
    $f = round((float)$valor, 2);
    return floor($f) == $f ? (string)(int)$f : rtrim(rtrim(number_format($f, 2, ',', ''), '0'), ',');
};

$n = 'Lineas[' . $indice . ']';
$esPlantilla = $linea === null;
$seccion = $esPlantilla ? '' : $linea->seccion;
?>
<tr class="fila-linea fila-extra" data-seccion="<?= Html::encode($seccion) ?>">
    <td class="col-desc">
        <?php if ($esPlantilla): ?>
            <select name="<?= $n ?>[idorigen]" class="form-control input-sm sel-item">
                <option value="">— Elegí un ítem o proveedor —</option>
                <?php foreach (['proveedor' => 'Proveedores', 'item' => 'Ítems'] as $tipo => $grupo): ?>
                    <optgroup label="<?= $grupo ?>">
                        <?php foreach ($catalogoItems as $id => $it): ?>
                            <?php if ($it['tipo'] === $tipo): ?>
                                <option value="<?= (int)$id ?>" data-tipo-evento="<?= Html::encode($it['tipoEvento']) ?>">
                                    <?= Html::encode($it['nombre'] . ($it['empresa'] ? ' (' . $it['empresa'] . ')' : '')) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
            <small class="text-muted detalle-linea"></small>
        <?php else: ?>
            <?php if (!$linea->isNewRecord): ?>
                <?= Html::hiddenInput($n . '[iddetalle]', $linea->iddetalle) ?>
            <?php endif; ?>
            <?= Html::hiddenInput($n . '[idorigen]', $linea->idorigen) ?>
            <?php if ($linea->isNewRecord && $linea->idorigen === null): ?>
                <?= Html::hiddenInput($n . '[anterior]', 1) /* servicios adicionales de una reserva vieja */ ?>
            <?php endif; ?>
            <span class="label label-<?= $seccion === 'proveedor' ? 'warning' : 'info' ?>"><?= Html::encode($linea->getSeccionTexto()) ?></span>
            <b><?= Html::encode($linea->nombre) ?></b>
            <?php if ($linea->empresa): ?><small class="text-muted">· <?= Html::encode($linea->empresa) ?></small><?php endif; ?>
            <?php $det = trim(html_entity_decode(strip_tags((string)$linea->detalle), ENT_QUOTES, 'UTF-8')); ?>
            <?php if ($det !== ''): ?><br><small class="text-muted detalle-linea"><?= Html::encode(mb_strimwidth($det, 0, 160, '…')) ?></small><?php endif; ?>
        <?php endif; ?>
    </td>
    <td class="col-num">
        <input type="text" name="<?= $n ?>[cantidad]" class="form-control input-sm num campo-cantidad" inputmode="decimal"
               value="<?= $esPlantilla ? '1' : Html::encode($cantidad($linea->cantidad)) ?>">
        <small class="text-muted unidad-linea"><?= $esPlantilla ? '' : Html::encode(mb_strtolower($linea->getUnidadTexto())) ?></small>
    </td>
    <td class="col-num">
        <input type="text" name="<?= $n ?>[precio_unitario]" class="form-control input-sm num campo-precio" inputmode="decimal"
               value="<?= $esPlantilla ? '' : Html::encode($num($linea->precio_unitario)) ?>">
    </td>
    <td class="col-num col-subtotal">
        <span class="campo-subtotal"><?= $esPlantilla ? '0,00' : Html::encode($num($linea->subtotal)) ?></span>
    </td>
    <td class="col-pct">
        <div class="input-group input-group-sm">
            <input type="text" name="<?= $n ?>[descuento_porcentaje]" class="form-control num campo-pct" inputmode="decimal"
                   value="<?= $esPlantilla ? '0' : Html::encode($porcentaje($linea->descuento_porcentaje)) ?>">
            <span class="input-group-addon">%</span>
        </div>
    </td>
    <td class="col-num">
        <input type="text" name="<?= $n ?>[descuento_monto]" class="form-control input-sm num campo-desc" inputmode="decimal"
               value="<?= $esPlantilla ? '0,00' : Html::encode($num($linea->descuento_monto)) ?>">
    </td>
    <td class="col-num">
        <input type="text" name="<?= $n ?>[total]" class="form-control input-sm num campo-total" inputmode="decimal"
               value="<?= $esPlantilla ? '0,00' : Html::encode($num($linea->total)) ?>">
    </td>
    <td class="col-accion">
        <button type="button" class="btn btn-sm btn-danger btn-quitar-linea" title="Quitar"><i class="fa fa-trash"></i></button>
    </td>
</tr>

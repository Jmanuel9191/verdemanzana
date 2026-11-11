<?php
/**
 * Pestaña CRONOGRAMA: lo pegado desde Word en el admin (Paso 4), con su formato.
 *
 * @var $this       yii\web\View
 * @var $model      app\models\Reservas
 * @var $cotizacion app\models\ReservaCotizacion|null
 * @var $cronograma app\models\ReservaCronograma
 */

use app\models\ReservaCronograma;
use yii\helpers\Html;

$id = (int)$model->idreservas;
$tiene = !$cronograma->isNewRecord && $cronograma->tieneContenido();

// Archivo de cronograma del sistema anterior (si existe en el servidor)
$archivoViejo = basename((string)$model->cronogramafile);
$hayArchivoViejo = $archivoViejo !== '' && is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . $archivoViejo);
?>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">
        <?= Html::encode(ReservaCronograma::titulo($model)) ?>
        <?php if ($tiene && $cotizacion): ?>
            <?= Html::a('<i class="fa-solid fa-file-pdf"></i> Descargar PDF',
                ['site/reserva-pdf', 'id' => $id, 'tipo' => 'cronograma'],
                ['class' => 'btn vm-btn-pdf pull-right', 'target' => '_blank', 'rel' => 'noopener']) ?>
        <?php endif; ?>
    </h2>

    <?php if ($tiene): ?>
        <div class="vm-cronograma">
            <?= $cronograma->contenido /* limpiado con HTMLPurifier al guardar */ ?>
        </div>
    <?php else: ?>
        <p class="vm-vacio"><i class="fa-solid fa-clock"></i> Todavía no se cargó el cronograma.</p>
    <?php endif; ?>

    <?php if ($hayArchivoViejo): ?>
        <p class="vm-descargas">
            <?= Html::a('<i class="fa-solid fa-download"></i> Descargar archivo de cronograma',
                Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($archivoViejo),
                ['class' => 'btn vm-btn-borde', 'target' => '_blank', 'rel' => 'noopener']) ?>
        </p>
    <?php endif; ?>
</section>

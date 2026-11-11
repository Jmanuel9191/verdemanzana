<?php
/**
 * Pestaña GUÍA: archivo único (PDF, Word, Excel o imagen).
 * El cliente solo descarga; la organizadora también puede subir/reemplazar.
 *
 * @var $this      yii\web\View
 * @var $model     app\models\Reservas
 * @var $esCliente bool  true = vista del cliente (no sube)
 */

use app\components\GuiaReserva;
use yii\helpers\Html;

$esCliente = !empty($esCliente);
$id = (int)$model->idreservas;
$guia = GuiaReserva::buscar($id);
?>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Guía</h2>

    <?php if ($guia): ?>
        <p class="vm-descargas">
            <?= Html::a('<i class="fa-solid fa-download"></i> Descargar archivo',
                GuiaReserva::url($guia),
                ['class' => 'btn vm-btn-pdf', 'target' => '_blank', 'rel' => 'noopener',
                 'download' => GuiaReserva::nombreDescarga($model, $guia)]) ?>
        </p>
    <?php else: ?>
        <p class="vm-vacio"><i class="fa-solid fa-folder-open"></i> Todavía no se cargó nada.</p>
    <?php endif; ?>

    <?php if (!$esCliente): /* solo la organizadora puede subir */ ?>
        <div class="vm-distribucion">
            <?= Html::beginForm(['site/subir-guia', 'id' => $id], 'post', ['enctype' => 'multipart/form-data', 'class' => 'vm-form-distribucion']) ?>
                <label class="btn vm-btn-borde vm-btn-subir">
                    <i class="fa-solid fa-cloud-arrow-up"></i> <?= $guia ? 'Cambiar archivo' : 'Subir archivo' ?>
                    <input type="file" name="guia" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" hidden onchange="this.form.submit()">
                </label>
                <small class="vm-gris">Un archivo (PDF, Word, Excel o imagen), hasta 15 MB. Si subís otro, se reemplaza.</small>
            <?= Html::endForm() ?>
        </div>
    <?php endif; ?>
</section>

<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
?>
<div class="form-group">
    <label class="control-label">Presupuesto archivo <small class="text-muted">(opcional: PDF, Word, Excel o imagen, máx. 20 MB)</small></label>
    <input type="file" name="archivo_presupuesto" class="form-control"
           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp">
    <?php if (!$reserva->isNewRecord && $reserva->presupuestofile && is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . basename($reserva->presupuestofile))): ?>
        <p class="help-block">
            Archivo actual:
            <?= Html::a('<i class="fa fa-download"></i> ' . Html::encode($reserva->presupuestofile),
                Url::to('@web/imagen/usuarios/' . basename($reserva->presupuestofile)), ['target' => '_blank']) ?>
            — si subís otro, se reemplaza.
        </p>
    <?php endif; ?>
</div>

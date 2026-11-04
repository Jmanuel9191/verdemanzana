<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $nombre string  name del input, ej. "PresupuestoForm[contrasena]" */
/* @var $etiqueta string */
/* @var $error string|null */
/* @var $valor string|null  (solo se repite si hubo error al guardar) */

$id = 'campo-contrasena-' . substr(md5($nombre), 0, 6);
$min = \app\models\PresupuestoForm::CONTRASENA_MIN;
?>
<div class="form-group campo-contrasena <?= $error ? 'has-error' : '' ?>">
    <label class="control-label" for="<?= $id ?>"><?= Html::encode($etiqueta) ?></label>
    <div class="input-group">
        <?= Html::passwordInput($nombre, (string)$valor, [
            'id' => $id,
            'class' => 'form-control',
            // "new-password": que el navegador NO autocomplete con la contraseña del admin
            'autocomplete' => 'new-password',
            'minlength' => $min,
            'maxlength' => 100,
            'placeholder' => 'Mínimo ' . $min . ' caracteres',
        ]) ?>
        <span class="input-group-btn">
            <button type="button" class="btn btn-default btn-ver-contrasena" data-objetivo="<?= $id ?>" title="Mostrar / ocultar">
                <i class="fa fa-eye"></i>
            </button>
        </span>
    </div>
    <?php if ($error): ?>
        <p class="help-block help-block-error"><?= Html::encode($error) ?></p>
    <?php else: ?>
       
    <?php endif; ?>
</div>
<?php
$this->registerJs(<<<JS
$(document).off('click.verContrasena').on('click.verContrasena', '.btn-ver-contrasena', function () {
    var campo = $('#' + $(this).data('objetivo'));
    var mostrar = campo.attr('type') === 'password';
    campo.attr('type', mostrar ? 'text' : 'password');
    $(this).find('i').toggleClass('fa-eye', !mostrar).toggleClass('fa-eye-slash', mostrar);
    $(this).attr('title', mostrar ? 'Ocultar' : 'Mostrar');
    campo.focus();
});
JS, \yii\web\View::POS_READY, 'ver-contrasena');
?>

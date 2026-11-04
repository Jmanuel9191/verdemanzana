<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $error string|null */
/* @var $valor string */

$this->title = 'Cambiar contraseña';
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['ver', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = $this->title;

$tiene = trim((string)$reserva->contrasena) !== '';
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?> <small><?= Html::encode($reserva->nombre) ?></small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6 col-lg-5">
            <div class="box box-success">
                <?= Html::beginForm(['contrasena', 'id' => $reserva->idreservas], 'post', ['autocomplete' => 'off']) ?>
                <div class="box-body">
                    <p>
                        Usuario del cliente: <b><?= Html::encode($reserva->email) ?></b><br>
                        <?php if ($tiene): ?>
                            <span class="text-muted">Ya tiene contraseña. La nueva reemplaza a la anterior.</span>
                        <?php else: ?>
                            <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> Todavía no tiene contraseña: el cliente no puede iniciar sesión.</span>
                        <?php endif; ?>
                    </p>

                    <?= $this->render('_campo_contrasena', [
                        'nombre'   => 'contrasena',
                        'etiqueta' => 'Nueva contraseña',
                        'error'    => $error,
                        'valor'    => $valor,
                    ]) ?>
                </div>
                <div class="box-footer">
                    <?= Html::submitButton('<i class="fa fa-key"></i> Guardar contraseña', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Cancelar', ['ver', 'id' => $reserva->idreservas], ['class' => 'btn btn-default']) ?>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</section>

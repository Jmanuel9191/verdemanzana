<?php
/**
 * Pestaña LISTA DE INVITADOS: usa el módulo existente (viewinvitadosc).
 *
 * @var $this  yii\web\View
 * @var $model app\models\Reservas
 * @var $esCliente bool  true = lista editable del cliente (viewinvitados)
 */

use app\components\DistribucionInvitados;
use yii\helpers\Html;
use yii\helpers\Url;

$esCliente = !empty($esCliente);
$id = (int)$model->idreservas;
$archivoViejo = basename((string)$model->listainvitadosfile);
$hayArchivoViejo = $archivoViejo !== '' && is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . $archivoViejo);
$busqueda = new \app\models\InvitadosSearch(['idreservas' => $id]);
$distribucion = DistribucionInvitados::buscar($id);
?>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">
        Lista de invitados
        <?= Html::a('<i class="fa-solid fa-file-pdf"></i> Descargar PDF',
            ['site/invitados-pdf', 'id' => $id],
            ['class' => 'btn vm-btn-pdf pull-right', 'target' => '_blank', 'rel' => 'noopener']) ?>
    </h2>

    <?php if ($hayArchivoViejo): ?>
        <p class="vm-descargas">
            <?= Html::a('<i class="fa-solid fa-download"></i> Descargar lista de invitados (archivo)',
                Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($archivoViejo),
                ['class' => 'btn vm-btn-borde', 'target' => '_blank', 'rel' => 'noopener']) ?>
        </p>
    <?php endif; ?>

    <?= $this->render('@app/views/site/reservas/' . ($esCliente ? 'viewinvitados' : 'viewinvitadosc'), [
        'searchModel'  => $busqueda,
        'dataProvider' => $busqueda->search(Yii::$app->request->queryParams),
        'idreservas'   => $id,
    ]) ?>

    <!-- ===== Distribución de la lista de invitados (imagen) ===== -->
    <div class="vm-distribucion">
        <h3 class="vm-subtitulo">Distribución de la lista de invitados</h3>

        <?php if ($distribucion): ?>
            <div class="vm-galeria" style="margin-bottom:12px">
                <a href="<?= Html::encode(DistribucionInvitados::url($distribucion)) ?>" class="vm-zoom" title="Distribución de la lista de invitados">
                    <img src="<?= Html::encode(DistribucionInvitados::url($distribucion)) ?>" alt="Distribución" loading="lazy">
                </a>
            </div>
        <?php elseif ($esCliente): ?>
            <p class="vm-vacio"><i class="fa-solid fa-image"></i> Todavía no se cargó la distribución.</p>
        <?php endif; ?>

        <?php if (!$esCliente): /* solo organizadora puede subir */ ?>
            <?= Html::beginForm(['site/subir-distribucion', 'id' => $id], 'post', ['enctype' => 'multipart/form-data', 'class' => 'vm-form-distribucion']) ?>
                <label class="btn vm-btn-borde vm-btn-subir">
                    <i class="fa-solid fa-cloud-arrow-up"></i> <?= $distribucion ? 'Cambiar distribución' : 'Subir distribución' ?>
                    <input type="file" name="distribucion" accept="image/jpeg,image/png,image/webp" hidden onchange="this.form.submit()">
                </label>
                <small class="vm-gris">Una imagen (JPG, PNG o WEBP), hasta 15 MB. Aparece también en el PDF.</small>
            <?= Html::endForm() ?>
        <?php endif; ?>
    </div>
</section>

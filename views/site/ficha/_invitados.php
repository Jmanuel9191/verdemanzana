<?php
/**
 * Pestaña LISTA DE INVITADOS: usa el módulo existente (viewinvitadosc).
 *
 * @var $this  yii\web\View
 * @var $model app\models\Reservas
 * @var $esCliente bool  true = lista editable del cliente (viewinvitados)
 */

use yii\helpers\Html;

$id = (int)$model->idreservas;
$archivoViejo = basename((string)$model->listainvitadosfile);
$hayArchivoViejo = $archivoViejo !== '' && is_file(Yii::getAlias('@webroot') . '/imagen/usuarios/' . $archivoViejo);
$busqueda = new \app\models\InvitadosSearch(['idreservas' => $id]);
?>

<section class="vm-bloque">
    <h2 class="vm-bloque-titulo">Lista de invitados</h2>

    <?php if ($hayArchivoViejo): ?>
        <p class="vm-descargas">
            <?= Html::a('<i class="fa-solid fa-download"></i> Descargar lista de invitados (archivo)',
                Yii::getAlias('@web') . '/imagen/usuarios/' . rawurlencode($archivoViejo),
                ['class' => 'btn vm-btn-borde', 'target' => '_blank', 'rel' => 'noopener']) ?>
        </p>
    <?php endif; ?>

    <?= $this->render('@app/views/site/reservas/' . (!empty($esCliente) ? 'viewinvitados' : 'viewinvitadosc'), [
        'searchModel'  => $busqueda,
        'dataProvider' => $busqueda->search(Yii::$app->request->queryParams),
        'idreservas'   => $id,
    ]) ?>
</section>

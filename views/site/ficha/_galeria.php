<?php
/**
 * Miniaturas que abren en popup (Magnific Popup, se inicia en reservas/item.php).
 *
 * @var $imagenes array   modelos con ->archivo
 * @var $carpeta  string  carpeta dentro de /imagen
 * @var $titulo   string  texto que se ve en el popup
 * @var $vacio    string|null  texto si no hay imágenes (null = no mostrar nada)
 */

use yii\helpers\Html;

$titulo = $titulo ?? '';
$vacio = $vacio ?? null;
$base = Yii::getAlias('@web') . '/imagen/' . $carpeta . '/';
?>
<?php if (!empty($imagenes)): ?>
    <div class="vm-galeria">
        <?php foreach ($imagenes as $img):
            $url = $base . rawurlencode(basename((string)$img->archivo)); ?>
            <a href="<?= Html::encode($url) ?>" class="vm-zoom" title="<?= Html::encode($titulo) ?>">
                <img src="<?= Html::encode($url) ?>" alt="<?= Html::encode($titulo) ?>" loading="lazy">
            </a>
        <?php endforeach; ?>
    </div>
<?php elseif ($vacio !== null): ?>
    <p class="vm-vacio"><i class="fa-solid fa-image"></i> <?= Html::encode($vacio) ?></p>
<?php endif; ?>

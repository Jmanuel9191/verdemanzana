<?php
/**
 * "Mis observaciones" (solo el cliente, en /perfilreserva):
 *  - escribir / editar la observación de decoración y de cronograma (siempre, antes o después del evento)
 *  - subir varias imágenes ahí mismo y borrarlas (assets_b/js/ficha-cliente.js)
 *
 * Usa las rutas de siempre: site/comentarios (texto), site/upload2 + site/delete (decoración),
 * site/upload3 + site/borrar (cronograma). Todas verifican que la reserva sea del cliente logueado.
 *
 * @var $this  yii\web\View
 * @var $model app\models\Reservas
 */

use yii\helpers\Html;
use yii\helpers\Url;

$id = (int)$model->idreservas;

// El aviso "Observación enviada" lo muestra el layout (cartel emergente con los flash del sitio)

$temas = [
    [
        'clave'     => 'decoracion',
        'titulo'    => 'Decoración',
        'icono'     => 'fa-palette',
        'campo'     => 'comentario_decoracion',
        'imagenes'  => $model->galeriadecoracion,
        'carpeta'   => 'reservasdecoracion',
        'subir'     => Url::to(['site/upload2']),
        'borrar'    => Url::to(['site/delete']),
        'campoFile' => 'ReservasGaleriadecoracion[file]',
        'ayuda'     => 'Ej. colores, flores, estilo de mesas, ideas que te gustaron…',
    ],
    [
        'clave'     => 'cronograma',
        'titulo'    => 'Cronograma',
        'icono'     => 'fa-clock',
        'campo'     => 'comentario_cronograma',
        'imagenes'  => $model->galeriacronograma,
        'carpeta'   => 'reservascronograma',
        'subir'     => Url::to(['site/upload3']),
        'borrar'    => Url::to(['site/borrar']),
        'campoFile' => 'ReservasGaleriacronograma[file]',
        'ayuda'     => 'Ej. hora de ingreso, primer baile, brindis, canciones especiales…',
    ],
];
?>

<section class="vm-bloque" id="mis-observaciones">
    <h2 class="vm-bloque-titulo">Mis observaciones</h2>

    <p class="vm-gris">Contanos lo que quieras para tu evento y subí fotos de referencia. Podés cambiarlo cuando quieras.</p>

    <?= Html::beginForm(['site/comentarios'], 'post', ['id' => 'form-observaciones']) ?>
    <div class="row">
        <?php foreach ($temas as $t):
            $comentario = (string)$model->{$t['campo']}; ?>
            <div class="col-md-6">
                <div class="vm-tarjeta vm-tarjeta-cliente">
                    <h3><i class="fa-solid <?= $t['icono'] ?>"></i> <?= Html::encode($t['titulo']) ?></h3>

                    <label class="vm-etq-campo" for="obs-<?= $t['clave'] ?>">Tu observación</label>
                    <?= Html::textarea('Reservas[' . $t['campo'] . ']', $comentario, [
                        'id' => 'obs-' . $t['clave'],
                        'class' => 'form-control vm-textarea',
                        'rows' => 6,
                        'maxlength' => 5000,
                        'placeholder' => $t['ayuda'],
                    ]) ?>

                    <div class="vm-subida-cab">
                        <span class="vm-etq-campo">Tus imágenes</span>
                        <label class="btn vm-btn-borde vm-btn-subir">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Subir imágenes
                            <input type="file" class="vm-input-subir" accept="image/jpeg,image/png,image/webp,image/gif" multiple hidden
                                   data-url="<?= Html::encode($t['subir']) ?>"
                                   data-campo="<?= Html::encode($t['campoFile']) ?>"
                                   data-reserva="<?= $id ?>"
                                   data-destino="#galeria-<?= $t['clave'] ?>">
                        </label>
                    </div>
                    <div class="vm-subida-estado" id="estado-<?= $t['clave'] ?>"></div>

                    <div class="vm-galeria vm-galeria-cliente" id="galeria-<?= $t['clave'] ?>"
                         data-borrar="<?= Html::encode($t['borrar']) ?>" data-titulo="<?= Html::encode($t['titulo']) ?>">
                        <?php foreach ($t['imagenes'] as $img):
                            $url = Yii::getAlias('@web') . '/imagen/' . $t['carpeta'] . '/' . rawurlencode(basename((string)$img->archivo)); ?>
                            <div class="vm-miniatura" data-id="<?= (int)$img->idgaleria ?>">
                                <a href="<?= Html::encode($url) ?>" class="vm-zoom" title="<?= Html::encode($t['titulo']) ?>">
                                    <img src="<?= Html::encode($url) ?>" alt="" loading="lazy">
                                </a>
                                <button type="button" class="vm-borrar-img" title="Borrar imagen">&times;</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="vm-vacio vm-sin-imagenes" style="<?= $t['imagenes'] ? 'display:none' : '' ?>">
                        <i class="fa-solid fa-image"></i> Todavía no subiste imágenes.
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center">
        <button type="submit" class="btn vm-btn-guardar"><i class="fa-solid fa-paper-plane"></i> Guardar observaciones</button>
    </div>
    <?= Html::endForm() ?>
</section>

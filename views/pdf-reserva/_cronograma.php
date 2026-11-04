<?php

use app\models\ReservaCronograma;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $cronograma ReservaCronograma */
?>
<?= $this->render('_cabecera', ['reserva' => $reserva, 'cotizacion' => $cotizacion, 'titulo' => ReservaCronograma::titulo($reserva)]) ?>

<?php if (!$cronograma->isNewRecord && $cronograma->tieneContenido()): ?>
    <?php
    // En el PDF se quitan los fondos de color de las tablas (celdas, filas) para que se imprima limpio.
    // El resaltado del texto (ej. amarillo) se mantiene. El contenido ya viene limpio con HTMLPurifier.
    $contenido = preg_replace_callback('/<(table|thead|tbody|tr|td|th)\b([^>]*)>/i', function ($m) {
        $atributos = preg_replace('/background(-color)?\s*:[^;"]*;?/i', '', $m[2]);
        $atributos = preg_replace('/\sstyle="\s*"/i', '', $atributos);
        return '<' . $m[1] . $atributos . '>';
    }, (string)$cronograma->contenido);
    ?>
    <div class="cronograma"><?= $contenido ?></div>
<?php else: ?>
    <p class="muted">Todavía no se cargó el cronograma.</p>
<?php endif; ?>

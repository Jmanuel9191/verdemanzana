<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $actual string  'presupuesto' | 'pagos' | 'detalles' | 'cronograma' */
/* @var $idreservas int|null  null = reserva todavía no guardada */

$pasos = [
    'presupuesto' => ['1', 'Presupuesto', 'fa-calculator', 'presupuesto'],
    'pagos'       => ['2', 'Pagos', 'fa-money', 'pagos'],
    'detalles'    => ['3', 'Detalles', 'fa-list-alt', 'detalles'],
    'cronograma'  => ['4', 'Cronograma', 'fa-clock-o', 'cronograma'],
    'guia'        => ['5', 'Guía', 'fa-book', 'guia'],
];
// Pasos que todavía no están programados (se habilitan cuando existan las acciones)
$disponibles = ['presupuesto', 'pagos', 'detalles', 'cronograma', 'guia'];
?>
<ul class="nav nav-tabs pasos-reserva">
    <?php foreach ($pasos as $clave => [$numero, $texto, $icono, $accion]): ?>
        <?php
        $habilitado = $clave === $actual || ($idreservas && in_array($clave, $disponibles, true));
        $contenido = '<span class="paso-numero">' . $numero . '</span> <i class="fa ' . $icono . '"></i> ' . $texto;
        ?>
        <li class="<?= $clave === $actual ? 'active' : ($habilitado ? '' : 'disabled') ?>">
            <?php if ($habilitado && $clave !== $actual): ?>
                <?= Html::a($contenido, [$accion, 'id' => $idreservas]) ?>
            <?php else: ?>
                <a href="#" onclick="return false;"
                   title="<?= $habilitado ? '' : ($idreservas ? 'Próximamente' : 'Primero guardá el presupuesto') ?>"><?= $contenido ?></a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

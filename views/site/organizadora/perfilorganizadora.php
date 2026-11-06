<?php
/**
 * Perfil de la organizadora (/perfilorganizadora).
 * Los datos vienen de SiteController::actionPerfilorganizadora().
 *
 * @var $this            yii\web\View
 * @var $model           app\models\Organizadoras
 * @var $proximas        array  comisiones de eventos de hoy en adelante
 * @var $realizadasAnio  array  comisiones de eventos pasados del año elegido
 * @var $anios           array  años con eventos pasados (para el selector)
 * @var $anioElegido     int
 * @var $verRealizadas   bool   abrir directamente la pestaña "Realizadas"
 * @var $salones         array  nombre del salón => imagen
 * @var $eventosPorSalon array  nombre del salón => eventos próximos (de todas las organizadoras)
 */

use app\assets_b\AppAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Mi perfil - Organizadora';

$this->registerCssFile('@web/assets_b/css/plugins.css', ['depends' => AppAsset::class]);
$this->registerCssFile('@web/assets_b/css/perfil-organizadora.css', ['depends' => AppAsset::class]);

// "25 de Noviembre, 2026"
$fechaLarga = function ($fecha) {
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $t = strtotime($fecha);
    return date('d', $t) . ' de ' . $meses[(int) date('m', $t)] . ', ' . date('Y', $t);
};

// "Bs 1.500,00" (formato Bolivia)
$bs = function ($monto) {
    return 'Bs ' . number_format((float) $monto, 2, ',', '.');
};

$sumar = function (array $filas) {
    $total = 0;
    foreach ($filas as $f) {
        $total += (float) $f['monto'];
    }
    return $total;
};

$totalProximas = $sumar($proximas);
$totalRealizadas = $sumar($realizadasAnio);
$nombreOrg = $model ? $model->nombre : '';

/**
 * Dibuja la tabla de comisiones (se usa en las dos pestañas).
 */
$tablaComisiones = function (array $filas, $total, $textoVacio) use ($fechaLarga, $bs) {
    if (!$filas) {
        return '<p class="po-vacio">' . Html::encode($textoVacio) . '</p>';
    }
    ob_start(); ?>
    <div class="table-responsive">
        <table class="table po-tabla">
            <thead>
            <tr>
                <th>Evento</th>
                <th>Fecha del evento</th>
                <th class="hidden-xs">Salón</th>
                <th class="text-right">Comisión</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($filas as $f):
                $href = Url::to(['site/reserva', 'id' => $f['idreservas']]); ?>
                <tr class="<?= (int) $f['estado'] === 0 ? 'po-inactiva' : '' ?>">
                    <td>
                        <?= Html::encode($f['nombre']) ?>
                        <?php if ($f['finalizado']): ?>
                            <span class="po-etiqueta po-etiqueta-fin">Finalizado</span>
                        <?php endif; ?>
                        <?php if ((int) $f['estado'] === 0): ?>
                            <span class="po-etiqueta po-etiqueta-inactiva">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td class="po-nowrap"><?= $fechaLarga($f['fecha_reserva']) ?></td>
                    <td class="hidden-xs"><?= Html::encode($f['sucursal']) ?></td>
                    <td class="text-right po-nowrap">
                        <?php if ($f['monto'] === null): ?>
                            <span class="po-no-cargada">No cargada</span>
                        <?php else: ?>
                            <?= $bs($f['monto']) ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <a href="<?= $href ?>" target="_blank" rel="noopener" class="btn btn-sm po-btn">Ir al evento</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-right hidden-xs"><strong>Total</strong></td>
                <td colspan="2" class="text-right visible-xs-table-cell"><strong>Total</strong></td>
                <td class="text-right po-nowrap"><strong><?= $bs($total) ?></strong></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
    return ob_get_clean();
};
?>

<?= $this->render('../banners/fijo', ['titulo' => 'Organizadora / ' . Html::encode($nombreOrg)]) ?>

<div class="container po-perfil">

    <!-- ===================== COMISIONES ===================== -->
    <h2 class="po-titulo">Comisiones</h2>

    <div class="row po-resumen">
        <div class="col-sm-6">
            <div class="po-caja-total">
                <span>Próximos eventos</span>
                <strong><?= $bs($totalProximas) ?></strong>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="po-caja-total po-caja-total-gris">
                <span>Eventos realizados en <?= (int) $anioElegido ?></span>
                <strong><?= $bs($totalRealizadas) ?></strong>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs po-tabs" role="tablist">
        <li class="<?= $verRealizadas ? '' : 'active' ?>">
            <a href="#po-proximas" data-toggle="tab" role="tab">Próximas (<?= count($proximas) ?>)</a>
        </li>
        <li class="<?= $verRealizadas ? 'active' : '' ?>">
            <a href="#po-realizadas" data-toggle="tab" role="tab">Realizadas</a>
        </li>
    </ul>

    <div class="tab-content po-tab-content">
        <div class="tab-pane <?= $verRealizadas ? '' : 'active' ?>" id="po-proximas">
            <?= $tablaComisiones($proximas, $totalProximas, 'No tenés eventos próximos asignados.') ?>
        </div>

        <div class="tab-pane <?= $verRealizadas ? 'active' : '' ?>" id="po-realizadas">
            <?php if ($anios): ?>
                <form method="get" action="<?= Url::to(['site/perfilorganizadora']) ?>" class="form-inline po-anio">
                    <label for="po-anio">Año:</label>
                    <select name="anio" id="po-anio" class="form-control input-sm" onchange="this.form.submit()">
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a ?>" <?= $a === $anioElegido ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php endif; ?>
            <?= $tablaComisiones($realizadasAnio, $totalRealizadas, 'No hay eventos realizados en este año.') ?>
        </div>
    </div>

    <!-- ===================== EVENTOS (de todas) ===================== -->
    <h2 class="po-titulo">Eventos</h2>

    <div class="row">
        <?php foreach ($salones as $salon => $imagen):
            $idCal = 'po-cal-' . md5($salon);
            $eventos = $eventosPorSalon[$salon];
            $fechas = array_values(array_unique(array_column($eventos, 'fecha_reserva'))); ?>
            <div class="col-md-4 col-sm-6 po-salon">
                <h3 class="po-salon-nombre"><?= Html::encode($salon) ?></h3>
                <img src="<?= Url::to('@web/assets_b/img/' . $imagen) ?>" class="img-responsive" alt="<?= Html::encode($salon) ?>">

                <h4><strong>Eventos</strong></h4>
                <div class="text-center">
                    <div id="<?= $idCal ?>" class="center-block po-calendario"
                         data-fechas='<?= Html::encode(Json::encode($fechas)) ?>'></div>
                </div>

                <?php if ($eventos): ?>
                    <?php foreach ($eventos as $e): ?>
                        <div class="po-evento">
                            <p><strong>Nombre:</strong> <?= Html::encode($e['nombre']) ?></p>
                            <p><strong>Fecha de evento:</strong> <?= $fechaLarga($e['fecha_reserva']) ?></p>
                            <a href="<?= Url::to(['site/reserva', 'id' => $e['idreservas']]) ?>"
                               target="_blank" rel="noopener" class="po-link">Ir a ficha de evento</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="po-vacio">Sin eventos</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Calendarios: marcan en verde las fechas ocupadas (solo desde hoy).
$js = <<<JS
$('.po-calendario').each(function () {
    var ocupadas = $(this).data('fechas') || [];
    var dos = function (n) { return (n < 10 ? '0' : '') + n; };
    $(this).datepicker({
        startDate: 'now',
        language: 'es',
        todayHighlight: true,
        beforeShowDay: function (d) {
            var clave = d.getFullYear() + '-' + dos(d.getMonth() + 1) + '-' + dos(d.getDate());
            if (ocupadas.indexOf(clave) !== -1) {
                return {tooltip: 'Reservado', classes: 'active'};
            }
        }
    });
});
JS;
$this->registerJs($js);
?>

<?php
/**
 * Ficha del evento para la organizadora (/reserva?id=X).
 * Datos: SiteController::actionReserva() + SiteController::datosFicha().
 *
 * @var $this            yii\web\View
 * @var $model           app\models\Reservas
 * @var $cotizacion      app\models\ReservaCotizacion|null
 * @var $cuentaInicial   app\models\ReservaCuentaInicial|null
 * @var $pagos           app\models\ReservaPago[]
 * @var $cronograma      app\models\ReservaCronograma
 * @var $tieneGuia       bool
 * @var $mostrarComision bool
 */

use yii\helpers\Html;

$this->title = 'Ficha del evento - ' . $model->nombre;
?>

<?= $this->render('banners/fijo', ['titulo' => 'Ficha del evento']) ?>

<?= $this->render('reservas/item', [
    'model'           => $model,
    'cotizacion'      => $cotizacion,
    'cuentaInicial'   => $cuentaInicial,
    'pagos'           => $pagos,
    'cronograma'      => $cronograma,
    'tieneGuia'       => $tieneGuia,
    'mostrarComision' => $mostrarComision,
]) ?>

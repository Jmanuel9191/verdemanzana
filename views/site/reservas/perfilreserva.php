<?php
/**
 * Vista del CLIENTE: /perfilreserva ("Mi evento").
 * Usa la misma ficha con pestañas que la organizadora (views/site/reservas/item.php),
 * en modo cliente:
 *  - nunca ve comisiones
 *  - escribe/edita sus observaciones y sube/borra sus imágenes (pestaña Cotización)
 *  - ve sus pagos y comprobantes (Facturación), el cronograma y carga su lista de invitados
 * Datos: SiteController::actionPerfilreserva() + SiteController::datosFicha().
 *
 * @var $this            yii\web\View
 * @var $model           app\models\Reservas
 * @var $cotizacion      app\models\ReservaCotizacion|null
 * @var $cuentaInicial   app\models\ReservaCuentaInicial|null
 * @var $pagos           app\models\ReservaPago[]
 * @var $cronograma      app\models\ReservaCronograma
 * @var $tieneGuia       bool
 * @var $mostrarComision bool   siempre false acá
 * @var $esCliente       bool   siempre true acá
 */

$this->title = 'Mi evento - ' . $model->nombre;
?>

<?= $this->render('../banners/fijo', ['titulo' => 'Mi evento']) ?>

<?= $this->render('item', [
    'model'           => $model,
    'cotizacion'      => $cotizacion,
    'cuentaInicial'   => $cuentaInicial,
    'pagos'           => $pagos,
    'cronograma'      => $cronograma,
    'tieneGuia'       => $tieneGuia,
    'mostrarComision' => false,
    'esCliente'       => true,
]) ?>

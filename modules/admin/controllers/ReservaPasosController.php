<?php

namespace app\modules\admin\controllers;

use app\models\DetallesForm;
use app\models\Invitados;
use app\models\PresupuestoForm;
use app\models\ReservaCotizacion;
use app\models\ReservaCotizacionHistorial;
use app\models\ReservaCronograma;
use app\models\ReservaPago;
use app\models\Reservas;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Reserva nueva en pasos: 1) Presupuesto  2) Pagos  3) Detalles.
 *
 * Solo trabaja con reservas que tienen cotización (formulario nuevo).
 * Las reservas viejas siguen con ReservasController, sin cambios.
 * Lo usan administradores tipo 1 y tipo 2 (no está en $soloAdminPrincipal).
 */
class ReservaPasosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'anular-pago' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Paso 1 para una reserva nueva.
     */
    public function actionCreate()
    {
        $form = PresupuestoForm::nueva();

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->guardar()) {
                Yii::$app->session->setFlash('success', 'Reserva y presupuesto creados. Ahora podés registrar el primer pago.');
                return $this->redirect(['pagos', 'id' => $form->reserva->idreservas]);
            }
            Yii::$app->session->setFlash('error', 'Revisá los datos marcados en rojo.');
        }

        return $this->render('presupuesto', ['form' => $form, 'historial' => []]);
    }

    /**
     * Paso 1 de una reserva existente: se editan SOLO los datos del cliente/evento.
     * El presupuesto se muestra para ver; se cambia en "Modificar presupuesto".
     */
    public function actionPresupuesto($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);
        $form = PresupuestoForm::deReserva($reserva, $cotizacion);
        $form->modo = PresupuestoForm::MODO_DATOS;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->guardar()) {
                Yii::$app->session->setFlash('success', 'Datos de la reserva actualizados.');
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', 'Revisá los datos marcados en rojo.');
        }

        return $this->render('presupuesto', [
            'form'      => $form,
            'historial' => ReservaCotizacionHistorial::deCotizacion((int)$cotizacion->idcotizacion),
        ]);
    }

    /**
     * Pantalla aparte para cambiar paquete / ítems / proveedores de una reserva existente.
     * Cada guardado queda en el historial (quién, cuándo, qué cambió, motivo opcional).
     */
    public function actionModificarPresupuesto($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);
        $form = PresupuestoForm::deReserva($reserva, $cotizacion);
        $form->modo = PresupuestoForm::MODO_PRESUPUESTO;

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $totalAntes = $cotizacion->total;
            if ($form->guardar()) {
                if (empty($form->cambios)) {
                    Yii::$app->session->setFlash('success', 'No hubo cambios en el presupuesto.');
                } else {
                    $n = count($form->cambios);
                    Yii::$app->session->setFlash('success', 'Presupuesto modificado (' . $n . ' cambio' . ($n === 1 ? '' : 's') . '). Total: '
                        . \app\helpers\FormatoHelper::formatoBs($totalAntes) . ' → ' . \app\helpers\FormatoHelper::formatoBs($cotizacion->total) . '.');
                }
                return $this->redirect(['presupuesto', 'id' => $reserva->idreservas]);
            }
            Yii::$app->session->setFlash('error', 'No se guardó. Revisá los datos marcados en rojo.');
        }

        return $this->render('presupuesto', [
            'form'      => $form,
            'historial' => ReservaCotizacionHistorial::deCotizacion((int)$cotizacion->idcotizacion),
        ]);
    }

    /**
     * Paso 2: historial de pagos + registrar un pago nuevo.
     */
    public function actionPagos($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);

        $pago = new ReservaPago();
        $pago->scenario = 'registrar';
        $pago->fecha_pago = date('Y-m-d');

        if (Yii::$app->request->isPost && $pago->load(Yii::$app->request->post())) {
            $pago->idreservas = (int)$reserva->idreservas;   // nunca se toma del formulario
            $pago->archivo = UploadedFile::getInstance($pago, 'archivo');

            if ($this->registrarPago($pago)) {
                Yii::$app->session->setFlash('success', 'Pago de ' . $pago->getMontoTexto() . ' registrado.');
                return $this->redirect(['pagos', 'id' => $reserva->idreservas]);
            }
            Yii::$app->session->setFlash('error', 'No se registró el pago. Revisá los datos marcados en rojo.');
        }

        $pagos = ReservaPago::find()
            ->where(['idreservas' => (int)$reserva->idreservas])
            ->with(['adminRegistro', 'adminAnulo'])
            ->orderBy(['fecha_pago' => SORT_ASC, 'idpago' => SORT_ASC])
            ->all();

        return $this->render('pagos', [
            'reserva'    => $reserva,
            'cotizacion' => $cotizacion,
            'pagos'      => $pagos,
            'pago'       => $pago,
        ]);
    }

    /**
     * Paso 3: detalles del evento (organizadora, equipamiento, decoración, coordinación).
     * Guarda solo esas columnas de reservas1.
     */
    public function actionDetalles($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);
        $form = DetallesForm::deReserva($reserva);

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->guardar()) {
                Yii::$app->session->setFlash('success', 'Detalles del evento guardados.');
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', 'No se guardó. Revisá los datos marcados en rojo.');
        }

        return $this->render('detalles', [
            'form'       => $form,
            'reserva'    => $reserva,
            'cotizacion' => $cotizacion,
            'invitados'  => (int)Invitados::find()->where(['idreservas' => (int)$reserva->idreservas])->count(),
        ]);
    }

    /**
     * Paso 4: cronograma (se pega desde Word con su formato) + vista previa de cómo quedó guardado.
     */
    public function actionCronograma($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);
        $cronograma = ReservaCronograma::deReserva((int)$reserva->idreservas);

        if (Yii::$app->request->isPost && $cronograma->load(Yii::$app->request->post())) {
            if ($cronograma->save()) {
                Yii::$app->session->setFlash('success', 'Cronograma guardado. Abajo está la vista previa de cómo quedó.');
                return $this->redirect(['cronograma', 'id' => $reserva->idreservas, '#' => 'vista-previa']);
            }
            Yii::$app->session->setFlash('error', 'No se guardó el cronograma. ' . implode(' ', $cronograma->getFirstErrors()));
        }

        return $this->render('cronograma', [
            'reserva'    => $reserva,
            'cotizacion' => $cotizacion,
            'cronograma' => $cronograma,
        ]);
    }

    /**
     * Ficha general de la reserva (solo lectura) con accesos a cada parte para editarla.
     */
    public function actionVer($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);

        return $this->render('ver', [
            'reserva'    => $reserva,
            'cotizacion' => $cotizacion,
            'form'       => PresupuestoForm::deReserva($reserva, $cotizacion),
            'historial'  => ReservaCotizacionHistorial::deCotizacion((int)$cotizacion->idcotizacion),
            'pagos'      => ReservaPago::find()
                ->where(['idreservas' => (int)$reserva->idreservas])
                ->with(['adminRegistro', 'adminAnulo'])
                ->orderBy(['fecha_pago' => SORT_ASC, 'idpago' => SORT_ASC])
                ->all(),
            'cronograma' => ReservaCronograma::deReserva((int)$reserva->idreservas),
            'invitados'  => (int)Invitados::find()->where(['idreservas' => (int)$reserva->idreservas])->count(),
        ]);
    }

    /**
     * Cambiar la contraseña del cliente (login en /logueo). Se guarda en MD5, como el resto del sistema.
     */
    public function actionContrasena($id)
    {
        [$reserva] = $this->buscar($id);
        $error = null;
        $valor = '';

        if (Yii::$app->request->isPost) {
            $valor = trim((string)Yii::$app->request->post('contrasena', ''));
            $error = PresupuestoForm::errorContrasena($valor);
            if ($error === null) {
                // updateAttributes: cambia SOLO la contraseña, sin validar ni tocar el resto de reservas1
                $reserva->updateAttributes(['contrasena' => md5($valor)]);
                Yii::$app->session->setFlash('success', 'Contraseña del cliente actualizada.');
                return $this->redirect(['ver', 'id' => $reserva->idreservas]);
            }
        }

        return $this->render('contrasena', [
            'reserva' => $reserva,
            'error'   => $error,
            'valor'   => $error !== null ? $valor : '',
        ]);
    }

    /**
     * Anula un pago (solo POST). Queda en el historial con motivo, quién y cuándo.
     */
    public function actionAnularPago($id)
    {
        [$reserva] = $this->buscar($id);
        $idpago = (int)Yii::$app->request->post('idpago');
        $motivo = (string)Yii::$app->request->post('motivo', '');

        $pago = ReservaPago::findOne(['idpago' => $idpago, 'idreservas' => (int)$reserva->idreservas]);
        if ($pago === null) {
            throw new NotFoundHttpException('El pago no existe.');
        }

        if ($pago->anular($motivo)) {
            Yii::$app->session->setFlash('success', 'Pago N.º ' . $pago->idpago . ' (' . $pago->getMontoTexto() . ') anulado.');
        } else {
            $errores = $pago->getFirstErrors();
            Yii::$app->session->setFlash('error', $errores ? reset($errores) : 'No se pudo anular el pago.');
        }
        return $this->redirect(['pagos', 'id' => $reserva->idreservas]);
    }

    /**
     * Muestra / descarga el comprobante de un pago. Solo admin logueado
     * (la carpeta privado/ no se puede abrir desde el navegador).
     */
    public function actionComprobante($idpago)
    {
        $pago = ReservaPago::findOne((int)$idpago);
        $ruta = $pago ? $pago->getRutaComprobante() : null;
        if ($ruta === null) {
            throw new NotFoundHttpException('No se encontró el comprobante.');
        }

        $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        $nombre = 'comprobante_reserva' . $pago->idreservas . '_pago' . $pago->idpago . '.' . $extension;

        Yii::$app->response->headers->set('X-Content-Type-Options', 'nosniff');
        return Yii::$app->response->sendFile($ruta, $nombre, [
            'mimeType' => FileHelper::getMimeTypeByExtension($ruta) ?: 'application/octet-stream',
            'inline'   => true,   // se abre en otra pestaña; desde ahí se puede guardar
        ]);
    }

    /**
     * Registra el pago bloqueando la cotización mientras tanto, para que dos
     * pagos cargados al mismo tiempo no superen el saldo.
     */
    protected function registrarPago(ReservaPago $pago): bool
    {
        $db = Yii::$app->db;
        $transaccion = $db->beginTransaction();
        try {
            $db->createCommand(
                'SELECT idcotizacion FROM {{%reserva_cotizacion}} WHERE idreservas = :id FOR UPDATE',
                [':id' => (int)$pago->idreservas]
            )->queryScalar();

            if ($pago->registrar()) {
                $transaccion->commit();
                return true;
            }
            $transaccion->rollBack();
            return false;
        } catch (\Throwable $e) {
            $transaccion->rollBack();
            if ($pago->comprobante) {
                @unlink(ReservaPago::carpetaReserva((int)$pago->idreservas) . DIRECTORY_SEPARATOR . basename($pago->comprobante));
            }
            Yii::error($e->getMessage(), __METHOD__);
            $pago->addError('monto', 'Ocurrió un error al registrar el pago. Intentá de nuevo.');
            return false;
        }
    }

    /**
     * @return array [Reservas, ReservaCotizacion]
     * @throws NotFoundHttpException
     */
    protected function buscar($id): array
    {
        $reserva = Reservas::findOne((int)$id);
        if ($reserva === null) {
            throw new NotFoundHttpException('La reserva no existe.');
        }
        $cotizacion = ReservaCotizacion::deReserva((int)$reserva->idreservas);
        if ($cotizacion === null) {
            throw new NotFoundHttpException('Esta reserva fue creada con el formulario anterior y no tiene presupuesto por pasos.');
        }
        return [$reserva, $cotizacion];
    }
}

<?php

namespace app\modules\admin\controllers;

use app\models\PresupuestoForm;
use app\models\ReservaCotizacion;
use app\models\Reservas;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                Yii::$app->session->setFlash('success', 'Reserva y presupuesto creados. Siguiente paso: registrar pagos.');
                return $this->redirect(['presupuesto', 'id' => $form->reserva->idreservas]);
            }
            Yii::$app->session->setFlash('error', 'Revisá los datos marcados en rojo.');
        }

        return $this->render('presupuesto', ['form' => $form]);
    }

    /**
     * Paso 1 de una reserva existente (editar presupuesto).
     */
    public function actionPresupuesto($id)
    {
        [$reserva, $cotizacion] = $this->buscar($id);
        $form = PresupuestoForm::deReserva($reserva, $cotizacion);

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->guardar()) {
                Yii::$app->session->setFlash('success', 'Presupuesto actualizado.');
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', 'Revisá los datos marcados en rojo.');
        }

        return $this->render('presupuesto', ['form' => $form]);
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

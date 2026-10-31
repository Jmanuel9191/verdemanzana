<?php

namespace app\modules\admin\controllers;

use app\models\Paquetes;
use app\models\PaquetesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Catálogo de paquetes (admin).
 *
 * Permisos: solo administradores logueados (AccessControl) y solo tipo_admin = 1
 * (lista $soloAdminPrincipal en modules/admin/Module.php).
 */
class PaquetesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'estado' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Listado con buscador.
     */
    public function actionIndex()
    {
        $searchModel = new PaquetesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Ficha de un paquete.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Nuevo paquete.
     */
    public function actionCreate()
    {
        $model = new Paquetes();
        $model->loadDefaultValues(); // estado = 1, posicion = 0, tipo_evento = 'todos'
        $model->precio = null;       // vacío: obliga a escribir el precio (no 0.00)

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Paquete "' . $model->nombre . '" creado.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Paquete "' . $model->nombre . '" actualizado.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     */
    public function actionEstado($id)
    {
        $model = $this->findModel($id);
        $model->estado = ((int)$model->estado === Paquetes::ESTADO_ACTIVO)
            ? Paquetes::ESTADO_INACTIVO
            : Paquetes::ESTADO_ACTIVO;

        $ok = $model->save(false, ['estado']);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['ok' => $ok, 'estado' => (int)$model->estado];
        }

        if ($ok) {
            Yii::$app->session->setFlash('success', 'Paquete "' . $model->nombre . '" ahora está ' . mb_strtolower($model->getEstadoTexto()) . '.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo cambiar el estado.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $nombre = $model->nombre;

        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', 'Paquete "' . $nombre . '" eliminado.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo eliminar el paquete.');
        }

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Paquetes
    {
        $model = Paquetes::findOne((int)$id);
        if ($model === null) {
            throw new NotFoundHttpException('El paquete no existe.');
        }
        return $model;
    }
}
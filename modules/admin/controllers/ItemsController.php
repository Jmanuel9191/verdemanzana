<?php

namespace app\modules\admin\controllers;

use app\models\Items;
use app\models\ItemsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Catálogo de Ítems y Proveedores (admin).
 *
 * Permisos: admins logueados (AccessControl) y solo tipo_admin = 1
 * ('items' en $soloAdminPrincipal de modules/admin/Module.php).
 */
class ItemsController extends Controller
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
                    'delete' => ['POST'],
                    'estado' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Listado con pestañas Ítems / Proveedores / Todos.
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        // Pestaña por defecto: Ítems
        if (!isset($params['ItemsSearch']['tipo'])) {
            $params['ItemsSearch']['tipo'] = Items::TIPO_ITEM;
        }

        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totales' => $this->contarPorTipo(),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param string|null $tipo 'item' o 'proveedor' (viene del botón de la pestaña)
     */
    public function actionCreate($tipo = null)
    {
        $tipo = array_key_exists((string)$tipo, Items::tipos()) ? $tipo : null;

        $model = new Items();
        $model->loadDefaultValues();      // estado 1, posicion 0, unidad 'unidad', tipo_evento 'todos'
        $model->precio_unitario = null;   // vacío: obliga a escribir el precio
        if ($tipo !== null) {
            $model->tipo = $tipo;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $model->getTipoTexto() . ' "' . $model->nombre . '" creado.');
            return $this->redirect(['index', 'ItemsSearch[tipo]' => $model->tipo]);
        }

        return $this->render('create', [
            'model' => $model,
            'tipo' => $tipo,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $model->getTipoTexto() . ' "' . $model->nombre . '" actualizado.');
            return $this->redirect(['index', 'ItemsSearch[tipo]' => $model->tipo]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Activar / desactivar (solo POST). Inactivo = no se ofrece en reservas nuevas.
     */
    public function actionEstado($id)
    {
        $model = $this->findModel($id);
        $model->estado = ((int)$model->estado === Items::ESTADO_ACTIVO)
            ? Items::ESTADO_INACTIVO
            : Items::ESTADO_ACTIVO;

        $ok = $model->save(false, ['estado']);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['ok' => $ok, 'estado' => (int)$model->estado];
        }

        if ($ok) {
            Yii::$app->session->setFlash('success', '"' . $model->nombre . '" ahora está ' . mb_strtolower($model->getEstadoTexto()) . '.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo cambiar el estado.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Borrar (solo POST). Las reservas guardadas conservan su copia.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $nombre = $model->nombre;
        $tipo = $model->tipo;

        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', '"' . $nombre . '" eliminado.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo eliminar.');
        }

        return $this->redirect(['index', 'ItemsSearch[tipo]' => $tipo]);
    }

    /**
     * Cantidad por tipo para mostrar en las pestañas.
     */
    private function contarPorTipo(): array
    {
        $filas = Items::find()
            ->select(['tipo', 'total' => 'COUNT(*)'])
            ->groupBy('tipo')
            ->asArray()
            ->all();

        $totales = [Items::TIPO_ITEM => 0, Items::TIPO_PROVEEDOR => 0];
        foreach ($filas as $fila) {
            $totales[$fila['tipo']] = (int)$fila['total'];
        }
        return $totales;
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Items
    {
        $model = Items::findOne((int)$id);
        if ($model === null) {
            throw new NotFoundHttpException('El ítem no existe.');
        }
        return $model;
    }
}

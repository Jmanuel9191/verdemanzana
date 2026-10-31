<?php

namespace app\modules\admin\controllers;

use app\models\Categoria;
use app\models\CategoriaSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoriasController implements the CRUD actions for Categoria model.
 */
class CategoriasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],*/
            'access' => [
                'class' => AccessControl::className(),
                //'only'  => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }



 

    /**
     * Lists all Categoria models.
     * @return mixed
     */
    public function actionIndex()
    {


        if (!empty(Yii::$app->user->identity)) {
            if (Yii::$app->user->identity->tipo_admin != 1) {
                return $this->redirect(['default/index']);
            }
        }


        $searchModel = new CategoriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categoria model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Categoria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categoria();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Categoria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categoria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categoria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categoria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categoria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

       /**
     * Lista de categorías padre según el módulo elegido (para el DepDrop del formulario).
     * La primera opción es "Ninguna" (idpadre = 0) y queda seleccionada por defecto,
     * para poder crear categorías principales.
     */
    public function actionSubcat()
    {
        $out = [
            ['id' => '0', 'name' => '— Ninguna (categoría principal) —'],
        ];

        $parents = Yii::$app->request->post('depdrop_parents');
        if (!empty($parents)) {
            $idModulo = end($parents);
            if ($idModulo !== null && $idModulo !== '') {
                $lista = Categoria::find()
                    ->where(['modulo' => $idModulo])
                    ->orderBy(['nombre' => SORT_ASC])
                    ->all();
                foreach ($lista as $categoria) {
                    $out[] = ['id' => $categoria->idcategoria, 'name' => $categoria->nombre];
                }
            }
        }

        return Json::encode(['output' => $out, 'selected' => '0']);
    }
}

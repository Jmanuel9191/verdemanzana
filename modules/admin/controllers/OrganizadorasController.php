<?php

namespace app\modules\admin\controllers;

use app\models\Organizadoras;
use app\models\OrganizadorasSearch;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class OrganizadorasController extends Controller
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
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrganizadorasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'defaultOrder' => ['idorganizadora' => SORT_DESC]]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
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
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Organizadoras();


        if ($model->load(Yii::$app->request->post())) {
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->contrasena = md5($model->contrasena);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->idorganizadora]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);


    }

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }


        public function actionUpdate2($id)
    {
        $model = $this->findModel($id);
        $hashActual = $model->contrasena;

        if ($model->load(Yii::$app->request->post())) {
            $nueva = trim((string)$model->contrasena);
            // Vacío o sin cambios: se mantiene la contraseña actual. Si hay texto nuevo, se guarda.
            $model->contrasena = ($nueva === '' || $nueva === $hashActual) ? $hashActual : md5($nueva);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->idorganizadora]);
            }
        }

        // No mostrar el hash en el formulario
        $model->contrasena = '';
        return $this->render('update2', [
            'model' => $model,
        ]);
    }



    public function actionEstado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Organizadoras();
        $model = $model->findOne(['idorganizadora' => $id]);
        $model->estado = (string)!$model->estado;
        $model->save();
    }




    /**
     * Deletes an existing Usuarios model.
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
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organizadoras::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}

<?php

namespace app\modules\admin\controllers;

use app\models\Banner;
use app\models\BannerSeach;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }






    /**
     * Displays a single Banner model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();
        /*$model->fecha_registro = date('Y-m-d H:i:s');*/

        /*$model->nuevo = '1';*/
        if ($model->load(Yii::$app->request->post())) {
            $tmp = array();
            if (is_array($model->idbanner)) {
                foreach ($model->idbanner as $key => $item) {
                    $tmp[$key]->idcategoria = $item;
                    $tmp[$key]->fecha_registro = date('Y-m-d H:i:s');
                }
            }
            $model->file = UploadedFile::getInstance($model, 'file');
            $name = Yii::$app->security->generateRandomString();
            if ($model->validate()) {
                if ($model->upload($name)) {
                    $model->foto = $name . '.' . $model->file->extension;
                }
                if ($model->save(false)) {
                    foreach ($tmp as $item) {
                        $item->idbanner = $model->idbanner;
                        $item->save();
                    }
                    return $this->redirect(['index']);
                } else {
                    if ($model->foto) {
                        if (file_exists(Yii::$app->basePath . "/imagen/banners/" . $model->foto)) {
                            unlink(Yii::$app->basePath . "/imagen/banners/" . $model->foto);
                        }
                    }
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $name = Yii::$app->security->generateRandomString();
            if ($model->validate()) {

                if ($model->upload($name)) {
                    if (file_exists(Yii::$app->basePath . "/imagen/banners/" . $model->foto)) {
                        unlink(Yii::$app->basePath . "/imagen/banners/" . $model->foto);
                    }
                    $model->foto = $name . '.' . $model->file->extension;
                }
                if ($model->save(false)) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

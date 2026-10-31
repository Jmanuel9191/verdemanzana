<?php

namespace app\modules\admin\controllers;

use app\models\Noticias;
use app\models\NoticiasGaleria;
use app\models\NoticiasSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * NoticiasController implements the CRUD actions for Noticias model.
 */
class NoticiasController extends Controller
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
     * Lists all Noticias models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!empty(Yii::$app->user->identity)) {
            if (Yii::$app->user->identity->tipo_admin != 1) {
                return $this->redirect(['default/index']);
            }
        }



        $searchModel = new NoticiasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Noticias model.
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
     * Creates a new Noticias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Noticias();

        if ($model->load(Yii::$app->request->post())) {
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->estado = '1';

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Noticias model.
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

    /**
     * Deletes an existing Noticias model.
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
     * Finds the Noticias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Noticias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Noticias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGaleria($id)
    {
        $model = new NoticiasGaleria();
        $model->idnoticia = $id;
        $galeria = $model->findAll(['idnoticia' => $id]);
        return $this->render('galeria', ['id' => $id, 'model' => $model, 'galeria' => $galeria]);
    }

    public function actionUpload()
    {
        $model = new NoticiasGaleria();

        $model->file = UploadedFile::getInstance($model, 'file');
        $name = Yii::$app->security->generateRandomString();
        if ($model->upload($name)) {
            //$ext = explode('.',$model->archivo->name);
            //$ext = end($ext);
            // generate a unique file name to prevent duplicate filenames
            //$model->archivo = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/imagen/noticias/';
            //$path = Yii::$app->params['uploadPath'] . $model->archivo;
            $model->archivo = $name . '.' . $model->file->extension;
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->idnoticia = Yii::$app->request->post('id');
            if ($model->save(false)) {
                echo Json::encode([]);
                return $this->redirect(['galeria', 'id' => Yii::$app->request->post('id')]);
            }
        }
        var_dump($model->getErrors());
        die();
        return false;
    }

    public function actionErase()
    {
        $model = new NoticiasGaleria();
        $id = Yii::$app->request->post('key');
        $model = $model->findOne(['idgaleria' => $id]);
        if (empty($model->attributes)) {
            return false;
        }
        $idp = $model->idnoticia;
        $url = "/imagen/noticias/" . $model->archivo;
        $url = Yii::$app->basePath . $url;
        //$url=str_replace($model->archivo,'@web','@webroot');
        if ($model->delete()) {
            unlink($url);
            return $this->redirect(['galeria', 'id' => $idp]);
        }
        return false;
    }

    public function actionUp($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = Noticias::findOne(['idnoticia' => $id]);
        $model2 = Noticias::findOne(['idnoticia' => $id - 1]);
        if($model2) {
            $model->idnoticia = 0;
            $model->save();
            $model->idnoticia = $model2->idnoticia;
            $model2->idnoticia = $id;
            $model2->save();
            $model->save();
        }
        elseif(($id - 1)!=0){
            $model->idnoticia = $id - 1;
            $model->save();
        }
    }

    public function actionDown($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = Noticias::findOne(['idnoticia' => $id]);
        $model2 = Noticias::findOne(['idnoticia' => $id + 1]);
        if ($model2) {
            $model->idnoticia = 0;
            $model->save();
            $model->idnoticia = $model2->idnoticia;
            $model2->idnoticia = $id;
            $model2->save();
            $model->save();
        }
    }

    public function actionEstado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Noticias();
        $model = $model->findOne(['idnoticia' => $id]);
        $model->estado = (string)!$model->estado;
        $model->save();
    }

    public function actionDestacado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Noticias();
        $model = $model->findOne(['idnoticia' => $id]);
        $model->destacado = (string)!$model->destacado;
        $model->save();
    }

}

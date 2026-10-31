<?php

namespace app\modules\admin\controllers;

use app\models\ProyectoCategoria;
use app\models\ProyectoGaleria;
use app\models\ProyectoPlanos;
use app\models\Proyectos;
use app\models\ProyectosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * ProyectosController implements the CRUD actions for Proyectos model.
 */
class ProyectosController extends Controller
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
     * Lists all Proyectos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProyectosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proyectos model.
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
     * Creates a new Proyectos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proyectos();
        $model->fecha_registro=date('Y-m-d');
        $model->estado='1';
        $model->precio='1';
        if ($model->load(Yii::$app->request->post())) {
            $tmp = array();
            if (is_array($model->idcategoria)) {
                foreach ($model->idcategoria as $key => $item) {
                    $tmp[$key] = new ProyectoCategoria();
                    $tmp[$key]->idcategoria = $item;
                    $tmp[$key]->fecha_registro = date('Y-m-d');
                }
            }
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file2 = UploadedFile::getInstance($model, 'file2');
            $name = Yii::$app->security->generateRandomString();
            $name2 = Yii::$app->security->generateRandomString();
            if ($model->validate()) {
                if ($model->upload($name,$name2)) {
                    $model->foto_portada = $name . '.' . $model->file->extension;
                    $model->logo = $name2 . '.' . $model->file2->extension;
                }
                if ($model->save(false)) {
                    foreach ($tmp as $item) {
                        $item->idproyecto = $model->idproyecto;
                        $item->save();
                    }
                    return $this->redirect(['index']);
                } else {
                    if ($model->foto_portada) {
                        if (file_exists(Yii::$app->basePath . "/imagen/proyectos/" . $model->foto_portada)) {
                            unlink(Yii::$app->basePath . "/imagen/proyectos/" . $model->foto_portada);
                        }
                    }if ($model->logo) {
                        if (file_exists(Yii::$app->basePath . "imagen/proyectos/" . $model->logo)) {
                            unlink(Yii::$app->basePath . "imagen/proyectos/" . $model->logo);
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
     * Updates an existing Proyectos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $tmp=array();
            if(is_array($model->idcategoria))
            {
                $result = ProyectoCategoria::deleteAll(['idproyecto'=>$id]);
                foreach ($model->idcategoria as $key => $item)
                {
                    $tmp[$key] = new ProyectoCategoria();
                    $tmp[$key]->idcategoria = $item;
                    $tmp[$key]->fecha_registro = date('Y-m-d H:i:s');
                }
            }
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file2 = UploadedFile::getInstance($model, 'file2');
            $name = Yii::$app->security->generateRandomString();
            $name2 = Yii::$app->security->generateRandomString();
            if ($model->validate()) {

                if ($model->upload($name,$name2)) {
                    if (file_exists(Yii::$app->basePath . "imagen/proyectos/" . $model->foto_portada)) {
                        unlink(Yii::$app->basePath . "imagen/proyectos/" . $model->foto_portada);
                    }
                    if(!empty($model->file))
                    $model->foto_portada = $name . '.' . $model->file->extension;

                    if (file_exists(Yii::$app->basePath . "imagen/proyectos/" . $model->logo)) {
                        unlink(Yii::$app->basePath . "imagen/proyectos/" . $model->logo);
                    }
                    if(!empty($model->file2))
                        $model->logo = $name2 . '.' . $model->file2->extension;

                }
                if($model->save(false))
                {
                    foreach ($tmp as $item)
                    {
                        $item->idproyecto = $model->idproyecto;
                        $item->save();
                    }
                    return $this->redirect(['index']);
                }
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Proyectos model.
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
     * Finds the Proyectos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proyectos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Proyectos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGaleria($id)
    {
        $model = new ProyectoGaleria();
        $model->idproyecto = $id;
        $galeria = $model->findAll(['idproyecto'=>$id]);
        return $this->render('galeria',['id'=>$id,'model'=>$model,'galeria'=>$galeria]);
    }


    public function actionUpload()
    {
        $model = new ProyectoGaleria();

        $model->file = UploadedFile::getInstance($model, 'file');
        $name =Yii::$app->security->generateRandomString();
        if ($model->upload($name)) {
            //$ext = explode('.',$model->archivo->name);
            //$ext = end($ext);
            // generate a unique file name to prevent duplicate filenames
            //$model->archivo = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/imagen/proyectos/';
            //$path = Yii::$app->params['uploadPath'] . $model->archivo;
            $model->archivo = $name.'.'.$model->file->extension;
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->idproyecto = Yii::$app->request->post('id');
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
        $model = new ProyectoGaleria();
        $id = Yii::$app->request->post('key');
        $model = $model->findOne(['idgaleria'=>$id]);
        if(empty($model->attributes))
        {
            return false;
        }
        $idp = $model->idproyecto;
        $url = "/imagen/proyectos/".$model->archivo;
        $url=Yii::$app->basePath.$url;
        //$url=str_replace($model->archivo,'@web','@webroot');
        if($model->delete())
        {
            unlink($url);
            return $this->redirect(['galeria', 'id' => $idp]);
        }
        return false;
    }

    public function actionGaleria2($id)
    {
        $model = new ProyectoPlanos();
        $model->idproyecto = $id;
        $galeria = $model->findAll(['idproyecto'=>$id]);
        return $this->render('galeria2',['id'=>$id,'model'=>$model,'galeria'=>$galeria]);
    }

    public function actionUpload2()
    {
        $model = new ProyectoPlanos();

        $model->file = UploadedFile::getInstance($model, 'file');
        $name =Yii::$app->security->generateRandomString();
        if ($model->upload($name)) {
            //$ext = explode('.',$model->archivo->name);
            //$ext = end($ext);
            // generate a unique file name to prevent duplicate filenames
            //$model->archivo = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/imagen/proyectos/';
            //$path = Yii::$app->params['uploadPath'] . $model->archivo;
            $model->archivo = $name.'.'.$model->file->extension;
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->idproyecto = Yii::$app->request->post('id');
            if ($model->save(false)) {
                echo Json::encode([]);
                return $this->redirect(['galeria2', 'id' => Yii::$app->request->post('id')]);
            }
        }
        var_dump($model->getErrors());
        die();
        return false;
    }

    public function actionErase2()
    {
        $model = new ProyectoPlanos();
        $id = Yii::$app->request->post('key');
        $model = $model->findOne(['idplano'=>$id]);
        if(empty($model->attributes))
        {
            return false;
        }
        $idp = $model->idproyecto;
        $url = "/imagen/proyectos/".$model->archivo;
        $url=Yii::$app->basePath.$url;
        //$url=str_replace($model->archivo,'@web','@webroot');
        if($model->delete())
        {
            unlink($url);
            return $this->redirect(['galeria2', 'id' => $idp]);
        }
        return false;
    }


    public function actionUp($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = Proyectos::findOne(['idproyecto' => $id]);
        $model2 = Proyectos::findOne(['idproyecto' => $id - 1]);
        if($model2) {
            $model->idproyecto = 0;
            $model->save();
            $model->idproyecto = $model2->idproyecto;
            $model2->idproyecto = $id;
            $model2->save();
            $model->save();
        }
        elseif(($id - 1)!=0){
            $model->idproyecto = $id - 1;
            $model->save();
        }
    }

    public function actionDown($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = Proyectos::findOne(['idproyecto' => $id]);
        $model2 = Proyectos::findOne(['idproyecto' => $id + 1]);
        if ($model2) {
            $model->idproyecto = 0;
            $model->save();
            $model->idproyecto = $model2->idproyecto;
            $model2->idproyecto = $id;
            $model2->save();
            $model->save();
        }
    }

    public function actionEstado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Proyectos();
        $model = $model->findOne(['idproyecto' => $id]);
        $model->estado = (string)!$model->estado;
        $model->save();
    }

    public function actionDestacado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Proyectos();
        $model = $model->findOne(['idproyecto' => $id]);
        $model->destacado = (string)!$model->destacado;
        $model->save();
    }
}

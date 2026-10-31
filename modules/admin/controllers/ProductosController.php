<?php

namespace app\modules\admin\controllers;

use app\models\Productos;
use app\models\ProductosCategoria;
use app\models\ProductosGaleria;
use app\models\ProductosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * ProductosController implements the CRUD actions for Productos model.
 */
class ProductosController extends Controller
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
     * Lists all Productos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductosSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Productos model.
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
     * Creates a new Productos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Productos();
        $model->fecha_registro = date('Y-m-d H:i:s');
        $model->estado = '1';
        /*$model->nuevo = '1';*/
        if ($model->load(Yii::$app->request->post())) {
            $tmp = array();
            if (is_array($model->idcategorias)) {
                foreach ($model->idcategorias as $key => $item) {
                    $tmp[$key] = new ProductosCategoria();
                    $tmp[$key]->idcategoria = $item;
                    $tmp[$key]->fecha_registro = date('Y-m-d H:i:s');
                }
            }
            $model->file = UploadedFile::getInstance($model, 'file');
            $name = Yii::$app->security->generateRandomString();
            if ($model->validate()) {
                if ($model->upload($name)) {
                    $model->archivo = $name . '.' . $model->file->extension;
                }
                if ($model->save(false)) {
                    foreach ($tmp as $item) {
                        $item->idproducto = $model->idproducto;
                        $item->save();
                    }
                    return $this->redirect(['index']);
                } else {
                    if ($model->archivo) {
                        if (file_exists(Yii::$app->basePath . "imagen/productos/" . $model->archivo)) {
                            unlink(Yii::$app->basePath . "imagen/productos/" . $model->archivo);
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
     * Updates an existing Productos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $tmp=array();
            if(is_array($model->idcategorias))
            {
                $result = ProductosCategoria::deleteAll(['idproducto'=>$id]);
                foreach ($model->idcategorias as $key => $item)
                {
                    $tmp[$key] = new ProductosCategoria();
                    $tmp[$key]->idcategoria = $item;
                    $tmp[$key]->fecha_registro = date('Y-m-d H:i:s');
                }
            }

            $model->file = UploadedFile::getInstance($model, 'file');
            $name = Yii::$app->security->generateRandomString();
            if ($model->validate()) {

                if ($model->upload($name)) {
                    if (file_exists(Yii::$app->basePath . "imagen/productos" . $model->archivo)) {
                        unlink(Yii::$app->basePath . "imagen/productos" . $model->archivo);
                    }
                    $model->archivo = $name . '.' . $model->file->extension;
                }
                if($model->save(false))
                {
                    foreach ($tmp as $item)
                    {
                        $item->idproducto = $model->idproducto;
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
     * Deletes an existing Productos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Productos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return Galeria
     */

    /*  public function actionGaleria($id)
      {
          $model = new ProductosGaleria();
          $model->idproducto = $id;
          $galeria = $model->findAll(['idproducto' => $id]);
          return $this->render('galeria', ['id' => $id, 'model' => $model, 'galeria' => $galeria]);
      }*/

    public function actionGaleria($id)
    {
        $model = new ProductosGaleria();
        $model->idproducto = $id;
        $galeria = $model->findAll(['idproducto' => $id]);
        return $this->render('galeria', ['id' => $id, 'model' => $model, 'galeria' => $galeria]);
    }


    public function actionUpload()
    {
        $model = new ProductosGaleria();

        $model->file = UploadedFile::getInstance($model, 'file');
        $name = Yii::$app->security->generateRandomString();
        if ($model->upload($name)) {
            //$ext = explode('.',$model->archivo->name);
            //$ext = end($ext);
            // generate a unique file name to prevent duplicate filenames
            //$model->archivo = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/imagen/productos/';
            //$path = Yii::$app->params['uploadPath'] . $model->archivo;
            $model->archivo = $name . '.' . $model->file->extension;
            $model->fecha_registro = date('Y-m-d H:i:s');
            $model->idproducto = Yii::$app->request->post('id');
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
        $model = new ProductosGaleria();
        $id = Yii::$app->request->post('key');
        $model = $model->findOne(['idgaleria' => $id]);
        if (empty($model->attributes)) {
            return false;
        }
        $idp = $model->idproducto;
        $url = "/imagen/productos/" . $model->archivo;
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
        $model = new Productos();
        $model2 = new Productos();
        $model = $model->findOne(['idproducto' => $id]);
        $model2 = $model2->findOne(['idproducto' => $id - 1]);
        $model->idproducto = $model2->idproducto;
        $model2->idproducto = $id;
        $model->save();
    }

    public function actionDown($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Productos();
        $model2 = new Productos();
        $model = $model->findOne(['idproducto' => $id]);
        $model2 = $model2->findOne(['idproducto' => $id + 1]);
        $model->idproducto = $model2->idproducto;
        $model2->idproducto = $id;
        $model->save();
    }

    public function actionEstado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Productos();
        $model = $model->findOne(['idproducto' => $id]);
        $model->estado = (string)!$model->estado;
        $model->save();
    }

    public function actionDestacado($id)
    {
        if (!Yii::$app->request->isAjax)
            $this->redirect(['index']);
        $model = new Productos();
        $model = $model->findOne(['idproducto' => $id]);
        $model->destacado = (string)!$model->destacado;
        $model->save();
    }

}

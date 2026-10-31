<?php

namespace app\controllers;

use app\components\Correos;
use app\models\Categoria;
use app\models\Configuracion;
use app\models\ContactForm;
use app\models\Cotizaciones;
use app\models\Invitados;
use app\models\LoginWeb;
use app\models\LoginWeb2;
use app\models\Noticias;
use app\models\NoticiasSearch;
use app\models\Organizadoras;
use app\models\Productos;
use app\models\ProductosSearch;
use app\models\Promociones;
use app\models\PromocionesSearch;
use app\models\Proveedores;
use app\models\ProveedoresSearch;
use app\models\Reservas;
use app\models\Reservasform;
use app\models\ReservasGaleriacronograma;
use app\models\ReservasGaleriadecoracion;
use app\models\VideosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\UploadedFile;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['vender', 'logout', 'perfilorganizadora', 'deseos'],
                'rules' => [
                    // allow authenticated users
                    [

                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $ref = Yii::$app->request->referrer;

        if ($action->id === 'login' && $ref != Url::to(['organizadora/login'], true)) {
            Yii::$app->user->returnUrl = $ref;
        }

        if ($action->id === 'logueo' && $ref != Url::to(['reservas/logueo'], true)) {
            Yii::$app->user->returnUrl = $ref;
        }

        return true;
    }



    public function actionIndex()
    {
        $data['configuracion']=Configuracion::find()->one();
        return $this->render('index',$data);
    }

    /**
     * Login action.
     *
     * @return string
     */
        public function actionLogin()
    {
        // Ya logueada de verdad: ir al perfil
        if (!Yii::$app->user->isGuest && !empty(Yii::$app->session->get('user'))) {
            return $this->redirect(['perfilorganizadora']);
        }
        // Sesión a medias (de un intento fallido o vieja): limpiar
        Yii::$app->session->remove('user');

        $model = new LoginWeb();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['perfilorganizadora']);
        }
        return $this->render('organizadora/login', ['model' => $model]);
    }



 private function verificarAccesoReserva($idreservas)
    {
        $esOrganizadora = !Yii::$app->user->isGuest && !empty(Yii::$app->session->get('user'));
        $esClienteDueno = !Yii::$app->user2->isGuest && (int)Yii::$app->user2->id === (int)$idreservas;

        if (!$esOrganizadora && !$esClienteDueno) {
            throw new \yii\web\ForbiddenHttpException('No tenés permiso para acceder a esta reserva.');
        }
    }


       public function actionLogueo()
    {
        // Ya logueado de verdad: ir al perfil
        if (!Yii::$app->user2->isGuest) {
            return $this->redirect(['perfilreserva']);
        }
        // Sesión a medias (vieja o de un intento fallido): limpiar
        Yii::$app->session->remove('user2');

        $model = new LoginWeb2();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['perfilreserva']);
        }
        return $this->render('reservas/logueo', ['model' => $model]);
    }


      public function actionPerfilorganizadora()
    {
        if (Yii::$app->user->isGuest || empty(Yii::$app->session->get('user'))) {
            Yii::$app->session->remove('user');
            return $this->redirect(['site/login']);
        }
        $model = Organizadoras::findOne(['idorganizadora' => Yii::$app->user->id]);
        return $this->render('organizadora/perfilorganizadora', ['model' => $model]);
    }




       public function actionPerfilreserva()
    {
        if (Yii::$app->user2->isGuest) {
            Yii::$app->session->remove('user2');
            return $this->redirect(['site/logueo']);
        }

        $model = \app\models\Reservas::find()
            ->where(['idreservas' => Yii::$app->user2->id])
            ->with([
                'galeriapresupuesto',
                'galeriaequipamiento',
                'galeriadecoracionus',
                'galeriacoordinacion',
                'galeriadecoracion',
                'galeriacronograma',
            ])
            ->one();

        return $this->render('reservas/perfilreserva', ['model' => $model]);
    }



    public function actionLogout2()
    {
        Yii::$app->user2->logout(); // esto cierra la autenticación del componente user2
        Yii::$app->session->remove('user2'); // esto elimina los datos adicionales que guardaste
        Yii::$app->session->destroy(); // esto destruye toda la sesión PHP

        return $this->redirect(['site/logueo']);
    }



       public function actionComentarios()
    {
        if (Yii::$app->user2->isGuest) {
            return $this->redirect(['site/logueo']);
        }

        $model = Reservas::findOne(['idreservas' => Yii::$app->user2->id]);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('La reserva no existe.');
        }

        // El cliente SOLO puede modificar sus dos campos de observaciones.
        // (Antes se aceptaba cualquier campo: presupuesto, montos, saldo, contraseña, estado...)
        $permitidos = ['comentario_decoracion', 'comentario_cronograma'];
        $datos = Yii::$app->request->post('Reservas', []);
        $hayCambios = false;

        foreach ($permitidos as $campo) {
            if (is_array($datos) && array_key_exists($campo, $datos)) {
                $model->$campo = trim((string)$datos[$campo]);
                $hayCambios = true;
            }
        }

        if ($hayCambios) {
            if ($model->save(false, $permitidos)) {
                // Si el correo falla, el comentario igual queda guardado
                try {
                    Correos::nuevoComentario($model);
                } catch (\Throwable $e) {
                    Yii::error('No se pudo enviar el aviso de comentario: ' . $e->getMessage(), __METHOD__);
                }
                Yii::$app->session->setFlash('success', ['message' => 'Observación enviada', 'type' => 'success']);
            } else {
                Yii::$app->session->setFlash('error', ['message' => 'Hubo un error, inténtelo más tarde']);
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['perfilreserva']);
    }



   public function actionGaleriadecoracion($id)
    {
        $this->verificarAccesoReserva($id);

        $model = new ReservasGaleriadecoracion();
        $model->idreservas = $id;
        $galeria = ReservasGaleriadecoracion::findAll(['idreservas' => $id]);

        return $this->render('reservas/galeriadecoracion', ['id' => $id, 'model' => $model, 'galeria' => $galeria]);
    }

    public function actionGaleriacronograma($id)
    {
        $this->verificarAccesoReserva($id);

        $model = new ReservasGaleriacronograma();
        $model->idreservas = $id;
        $galeria = ReservasGaleriacronograma::findAll(['idreservas' => $id]);

        return $this->render('reservas/galeriacronograma', ['id' => $id, 'model' => $model, 'galeria' => $galeria]);
    }



    public function actionUpload2()
    {
        return $this->subirImagenCliente(new ReservasGaleriadecoracion());
    }




     public function actionUpload3()
    {
        return $this->subirImagenCliente(new ReservasGaleriacronograma());
    }


    public function actionDelete()
    {
        return $this->borrarImagenCliente(ReservasGaleriadecoracion::class, 'reservasdecoracion');
    }

    public function actionBorrar()
    {
        return $this->borrarImagenCliente(ReservasGaleriacronograma::class, 'reservascronograma');
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->remove('user');
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContacto()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contacto', [
            'model' => $model,
        ]);
    }



    public function actionContacto2()
    {
        $model = new ContactForm();
        $model['subject']='Formulario de Contacto';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',['message'=>'Mensaje enviado','type'=>'success']);
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }



    public function actionReservas2()
    {


        $model = new Reservasform();
        $model['subject']='Formulario de Reserva';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',['message'=>'Reserva Enviada, enseguida no contactaremos con usted!','type'=>'success']);
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);

    }
    public function actionCotizacionesasdw()
    {

        $model = new Cotizaciones();

        if ($model->load(Yii::$app->request->post())) {
            $model->fecha_registro = date('Y-m-d H:i:s');

            if ($model->validate()) {

                if ($model->save(false)) {
                    Correos::nuevoCotizacion($model);
                    Yii::$app->session->setFlash('success',['message'=>'Cotizacion enviada, Pronto nos contactaremos con usted','type'=>'success']);
                    return $this->refresh();


                } else {


                    Yii::$app->session->setFlash('error', ['message' => 'Se encontro algun error en el formulario, vuelva a intentarlo']);
                }


            }


        }
        return $this->render('cotizaciones', [
            'model' => $model,
        ]);

    }

    public function actionReservas()
    {
        $model = new Reservas();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('reservas', [
            'model' => $model,
        ]);
    }


    public function actionSalones()
    {
        $data['configuracion']=Configuracion::find()->one();
        return $this->render('sucursales',$data);
    }


    public function actionMapa()
    {
        $location = ['lat' => '', 'lng' => ''];
        $coord = \app\models\Configuracion::find()->one()['coordgoogle'];
        switch (Yii::$app->request->get('target')){
            case '1':
                $coord = " -17.819022, -63.216821";
                break;
            case '2':
                $coord = "-17.802409, -63.217653";
                break;
            case '3':
                $coord = "-17.792389, -63.172810'";
                break;



        }
        if (Yii::$app->request->get('lat') && Yii::$app->request->get('lng')) {
            $location['lat']=Yii::$app->request->get('lat');
            $location['lng']=Yii::$app->request->get('lng');
        }
        else {
            $ip = Yii::$app->geoip->ip();
            if(!empty($ip->data))
            {
                $location['lat']=$ip->location->lat;
                $location['lng']=$ip->location->lng;
            }
        }

        //return Json::encode($location);
        //return $this->renderAjax('mapa',['location'=>$location]);
        return $this->render('mapa',['location'=>$location,'coord'=>$coord]);
        //return $this->render('mapa',['coordgoogle'=>$la.','.$lo]);
    }



    public function actionQuienes()
    {
        return $this->render('quienes');
    }




    public function actionServiciosasdw()
    {
        return $this->render('servicios');
    }









    public function actionAuxiliares($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'auxiliares'])->one();
        return $this->Productos($alias['idcategoria'],$id);
    }

    public function actionGratuitos($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'gratuitos'])->one();
        return $this->Productos($alias['idcategoria'],$id);
    }

    public function actionReciclajedemateriales($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'reciclajedemateriales'])->one();
        return $this->Productos($alias['idcategoria'],$id,false);
    }

    public function actionAccesorios($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'accesorios'])->one();
        return $this->Productos($alias['idcategoria'],$id,false);
    }

    public function actionPaquetes($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'paquetes'])->one();
        return $this->Productos($alias['idcategoria'],$id,false);
    }







    private function Productos($categ=null,$categ2=null,$with=true)
    {
        $searchModel = New ProductosSearch();
        $seach = ['ProductosSearch'=>['estado'=>'1']];
        $cat = new Categoria();
        if(!empty(Yii::$app->getRequest()->getQueryParam('cat'))) {
            $seach['ProductosSearch']['categoria'] = Yii::$app->getRequest()->getQueryParam('cat');
            $cat = Categoria::findOne(['idcategoria'=>Yii::$app->getRequest()->getQueryParam('cat')]);
        }

        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['ProductosSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        if($with) {
            if (!empty($categ)) {
                $dataProvider->query->andWhere(['idcategoria' => $categ]);
                $dataProvider->query->joinWith('categorias');
                $cat = Categoria::findOne(['idcategoria' => $categ]);
                if (!empty($categ2)) {
                    $tmp = Productos::find()
                        ->where(['idcategoria' => $categ2])
                        ->joinWith('categorias')
                        ->all();
                    $tmp2 = ArrayHelper::getColumn($tmp, 'idproducto');
                    //print_r($tmp2);
                    $dataProvider->query->andWhere(['in', 'productos.idproducto', $tmp2]);
                }
                $dataProvider->query->distinct();
            }
        }
        else
        {
            $cat = Categoria::findOne(['idcategoria' => $categ]);
            if(empty($categ2))
            {
                $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
                //print_r($tmp2);
                $dataProvider->query->andWhere(['in', 'idcategoria', $tmp2]);

            }
            else
                $dataProvider->query->andWhere(['idcategoria' => $categ2]);
            $dataProvider->query->joinWith('categorias');
            $dataProvider->query->distinct();
        }
        $dataProvider->pagination->pageSize=9;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('productos',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);
    }
    public function actionProducto($id=null)
    {
        $model = Productos::findOne(['idproducto'=>$id]);
        $cat = Categoria::findOne(['idcategoria'=>Yii::$app->request->get('cat')]);
        return $this->render('productos',['model'=>$model,'categoria'=>$cat]);
    }


/*

    public function actionServicios($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'servicios'])->one();
        return $this->Noticias($alias['idcategoria'],$id);
    }
*/
    public function actionArticulos($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'articulos'])->one();
        return $this->Noticias($alias['idcategoria'],$id);
    }


    public function actionConsejos($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'consejos'])->one();
        return $this->Noticias($alias['idcategoria'],$id);
    }



    private function Noticias($categ=null,$categ2=null)
    {


        $searchModel = New NoticiasSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['NoticiasSearch'=>['estado'=>'1']];
        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['NoticiasSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['NoticiasSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);

        $cat = Categoria::findOne(['idcategoria' => $categ]);


        if(empty($categ2))
        {
            if (!empty($cat->categorias)){
            $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
            //print_r($tmp2);
            $dataProvider->query->andWhere(['in', 'idcategoria', $tmp2]);
            }
            else
                $dataProvider->query->andWhere(['idcategoria' => $categ]);
        }
        else
            $dataProvider->query->andWhere(['idcategoria' => $categ2]);
        $dataProvider->query->distinct();
        $dataProvider->setSort([
            'defaultOrder' => ['fecha_registro'=>SORT_DESC]]);
        $dataProvider->pagination->pageSize=6;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('noticias',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);
    }



    private function Proveedoresasdw($categ=null,$categ2=null)
    {


        $searchModel = New ProveedoresSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['ProveedoresSearch'=>['estado'=>'1']];
        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['ProveedoresSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['ProveedoresSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);

        $cat = Categoria::findOne(['idcategoria' => $categ]);


        if(empty($categ2))
        {
            if (!empty($cat->categorias)){
                $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
                //print_r($tmp2);
                $dataProvider->query->andWhere(['in', 'idcategoria', $tmp2]);
            }
            else
                $dataProvider->query->andWhere(['idcategoria' => $categ]);
        }
        else
            $dataProvider->query->andWhere(['idcategoria' => $categ2]);
        $dataProvider->query->distinct();
        $dataProvider->setSort([
            'defaultOrder' => ['fecha_registro'=>SORT_DESC]]);
        $dataProvider->pagination->pageSize=300;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('proveedores',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);
    }

/*
    public function actionProveedores($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'proveedores'])->one();
        return $this->Proveedores($alias['idcategoria'],$id);
    }
*/

    public function actionProveedores($categ=null,$categ2=null)
    {

        $cat = Categoria::findOne(['idcategoria' => $categ]);

        $searchModel = New ProveedoresSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['ProveedoresSearch'=>['estado'=>'1']];
        //$seach = ['ProveedoresSearch'=>['estado'=>'1', '>','idpadre', 0]];

        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['ProveedoresSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['ProveedoresSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        $dataProvider->setSort([
            'defaultOrder' => ['fecha_registro'=>SORT_DESC]]);
        $dataProvider->pagination->pageSize=9;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('proveedores',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);



    }



    public function actionNoticias($categ=null,$categ2=null)
    {

        $cat = Categoria::findOne(['idcategoria' => $categ]);

        $searchModel = New NoticiasSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['NoticiasSearch'=>['estado'=>'1']];
        //$seach = ['NoticiasSearch'=>['estado'=>'1', '>','idpadre', 0]];

        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['NoticiasSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['NoticiasSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        $dataProvider->setSort([
            'defaultOrder' => ['fecha_noticia'=>SORT_DESC]]);
        $dataProvider->pagination->pageSize=6;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('noticias',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);



    }


     private function subirImagenCliente($model)
    {
        $idreservas = (int)Yii::$app->request->post('id');
        $this->verificarAccesoReserva($idreservas);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model->file = UploadedFile::getInstance($model, 'file');
        if ($model->file === null) {
            return ['error' => 'No se recibió ninguna imagen.'];
        }

        $name = Yii::$app->security->generateRandomString();
        if (!$model->upload($name)) {
            $errores = $model->getFirstErrors();
            return ['error' => $errores ? reset($errores) : 'No se pudo guardar la imagen.'];
        }

        $model->archivo = $name . '.' . $model->file->extension;
        $model->fecha_registro = date('Y-m-d H:i:s');
        $model->idreservas = $idreservas;

        if (!$model->save(false)) {
            return ['error' => 'No se pudo registrar la imagen.'];
        }
        return [];
    }

    /**
     * Borra una imagen, solo si pertenece a una reserva a la que el usuario tiene acceso.
     */
    private function borrarImagenCliente($clase, $carpeta)
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException('Acción no permitida.');
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $imagen = $clase::findOne(['idgaleria' => (int)Yii::$app->request->post('key')]);
        if ($imagen === null) {
            return ['error' => 'La imagen no existe.'];
        }

        // La reserva se toma de la imagen guardada, no de lo que mande el navegador
        $this->verificarAccesoReserva($imagen->idreservas);

        $ruta = Yii::getAlias('@webroot') . '/imagen/' . $carpeta . '/' . basename($imagen->archivo);
        if ($imagen->delete() && is_file($ruta)) {
            @unlink($ruta);
        }
        return [];
    }



    public function actionPromocionesasdw($id=null)
    {
        $alias = Categoria::find()->where(['alias'=>'promociones'])->one();
        return $this->Promociones($alias['idcategoria'],$id);
    }


    private function Promociones($categ=null,$categ2=null)
    {


        $cat = Categoria::findOne(['idcategoria' => $categ]);

        $searchModel = New PromocionesSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['PromocionesSearch'=>['estado'=>'1']];
        //$seach = ['NoticiasSearch'=>['estado'=>'1', '>','idpadre', 0]];

        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['PromocionesSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['PromocionesSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        $dataProvider->pagination->pageSize=6;
        $dataProvider->setSort([
            'defaultOrder' => ['fecha_promocion'=>SORT_DESC]]);
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('promociones',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);
    }

/*
    public function actionPromociones($categ=null,$categ2=null)
    {

        $cat = Categoria::findOne(['idcategoria' => $categ]);

        $searchModel = New PromocionesSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['PromocionesSearch'=>['estado'=>'1']];
        //$seach = ['NoticiasSearch'=>['estado'=>'1', '>','idpadre', 0]];

        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['PromocionesSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['PromocionesSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        $dataProvider->pagination->pageSize=6;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('promociones',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);



    }*/



    public function actionPromocion($id=null)
    {
        $model = Promociones::findOne(['idpromocion'=>$id]);
        $cat = Categoria::findOne(['idcategoria'=>Yii::$app->request->get('cat')]);
        return $this->render('promociones',['model'=>$model,'categoria'=>$cat]);
    }



    public function actionReserva($id = null)
    {
        // Solo organizadoras con sesión iniciada pueden ver la ficha de un evento
        // (contiene datos personales y montos del cliente).
        if (empty(Yii::$app->session->get('user'))) {
            return $this->redirect(['site/login']);
        }

        $model = Reservas::findOne(['idreservas' => $id]);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('La reserva no existe.');
        }

        return $this->render('reservasint', ['model' => $model]);
    }



    public function actionNoticia($id=null)
    {
        $model = Noticias::findOne(['idnoticia'=>$id]);
        $cat = Categoria::findOne(['idcategoria'=>Yii::$app->request->get('cat')]);
        return $this->render('noticias',['model'=>$model,'categoria'=>$cat]);
    }

    public function actionProveedor($id=null)
    {
        $model = Proveedores::findOne(['idproovedor'=>$id]);
        $cat = Categoria::findOne(['idcategoria'=>Yii::$app->request->get('cat')]);
        return $this->render('proveedores',['model'=>$model,'categoria'=>$cat]);
    }


    private function Videos($categ=null,$categ2=null)
    {


        $searchModel = New VideosSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['VideosSearch'=>['estado'=>'1']];
        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['VideosSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['VideosSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);

        $cat = Categoria::findOne(['idcategoria' => $categ]);


        if(empty($categ2))
        {
            if (!empty($cat->categorias)){
                $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
                //print_r($tmp2);
                $dataProvider->query->andWhere(['in', 'idcategoria', $tmp2]);
            }
            else
                $dataProvider->query->andWhere(['idcategoria' => $categ]);
        }
        else
            $dataProvider->query->andWhere(['idcategoria' => $categ2]);
        $dataProvider->query->distinct();
        $dataProvider->pagination->pageSize=9;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('videos',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);
    }




    public function actionVideos($categ=null,$categ2=null)
    {

        $cat = Categoria::findOne(['idcategoria' => $categ]);

        $searchModel = New VideosSearch();
        Yii::$app->getRequest()->getQueryParam('cat');
        $seach = ['VideosSearch'=>['estado'=>'1']];
        //$seach = ['NoticiasSearch'=>['estado'=>'1', '>','idpadre', 0]];

        if(!empty(Yii::$app->getRequest()->getQueryParam('cat')))
            $seach['VideosSearch']['idcategoria']=Yii::$app->getRequest()->getQueryParam('cat');
        if(!empty(Yii::$app->getRequest()->getQueryParam('s')))
            $seach['VideosSearch']['titulo']=Yii::$app->getRequest()->getQueryParam('s');

        $dataProvider = $searchModel->search($seach);
        $dataProvider->pagination->pageSize=9;
        $dataProvider->pagination->forcePageParam = false;
        return $this->render('videos',['model'=>$dataProvider,'all'=>true,'categoria'=>$cat]);



    }



    private function validarFechaLimite($idreservas)
    {
        $reserva = \app\models\Reservas::findOne($idreservas);

        if (!$reserva || !$reserva->fecha_reserva) {
            throw new \yii\web\NotFoundHttpException('No se encontró la reserva o no tiene fecha definida.');
        }

        $fechaLimite = date('Y-m-d', strtotime($reserva->fecha_reserva . ' -1 day'));

        if (date('Y-m-d') > $fechaLimite) {
            throw new \yii\web\ForbiddenHttpException('Ya no es posible modificar la lista de invitados.');
        }
    }

    /**
     * Quién puede gestionar invitados de una reserva:
     * - una organizadora con sesión iniciada (ven todos los eventos)
     * - el cliente dueño de esa reserva
     */
    private function verificarAccesoInvitados($idreservas)
    {
        $esOrganizadora = !empty(Yii::$app->session->get('user'));
        $esClienteDueno = !Yii::$app->user2->isGuest && (int)Yii::$app->user2->id === (int)$idreservas;

        if (!$esOrganizadora && !$esClienteDueno) {
            throw new \yii\web\ForbiddenHttpException('No tenés permiso para modificar esta lista de invitados.');
        }
    }

    /**
     * Después de crear/editar/borrar un invitado, vuelve a la página correcta:
     * la ficha del evento (organizadora) o el perfil (cliente).
     */
    private function redirigirAInvitados($idreservas)
    {
        if (!empty(Yii::$app->session->get('user'))) {
            return $this->redirect(['reserva', 'id' => $idreservas, '#' => 'invitados']);
        }
        return $this->redirect(['perfilreserva', '#' => 'invitados']);
    }

    public function actionCreateinvitado($idreservas)
    {
        $this->verificarAccesoInvitados($idreservas);
        $this->validarFechaLimite($idreservas);

        $model = new Invitados();
        $model->idreservas = $idreservas;

        if ($model->load(Yii::$app->request->post())) {
            $model->idreservas = $idreservas; // no se puede cambiar desde el formulario
            if ($model->save()) {
                Yii::$app->session->setFlash('invitadoCreado', true);
                return $this->redirigirAInvitados($idreservas);
            }
        }

        return $this->renderPartial('reservas/createinvitado', ['model' => $model]);
    }

    public function actionCreateinvitadoc($idreservas)
    {
        // Misma lógica; se mantiene esta URL porque la usa la vista de la organizadora
        return $this->actionCreateinvitado($idreservas);
    }

    public function actionUpdateinv($id)
    {
        $model = \app\models\Invitados::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('El invitado no fue encontrado.');
        }

        $idreservas = $model->idreservas;
        $this->verificarAccesoInvitados($idreservas);
        $this->validarFechaLimite($idreservas);

        if ($model->load(Yii::$app->request->post())) {
            $model->idreservas = $idreservas; // no se puede mover a otra reserva
            if ($model->save()) {
                Yii::$app->session->setFlash('invitadoEditado', true);
                return $this->redirigirAInvitados($idreservas);
            }
        }

        return $this->renderPartial('reservas/updateinv', ['model' => $model]);
    }

    public function actionDeleteinvitado($id)
    {
        $model = \app\models\Invitados::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('El invitado no fue encontrado.');
        }

        $idreservas = $model->idreservas;
        $this->verificarAccesoInvitados($idreservas);
        $this->validarFechaLimite($idreservas);

        if ($model->delete()) {
            Yii::$app->session->setFlash('invitadoEliminado', true);
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo eliminar el invitado.');
        }

        return $this->redirigirAInvitados($idreservas);
    }

    public function actionDeleteinvitadoc($id)
    {
        // Misma lógica; se mantiene por compatibilidad con enlaces existentes
        return $this->actionDeleteinvitado($id);
    }



/*
    public function actionMapa($la,$lo)
    {
        return $this->renderAjax('mapa',['coordgoogle'=>$la.','.$lo]);
        //return $this->render('mapa',['coordgoogle'=>$la.','.$lo]);
    }
*/

}
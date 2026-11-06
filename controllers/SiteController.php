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
        $idOrganizadora = (int) Yii::$app->user->id;
        $model = Organizadoras::findOne(['idorganizadora' => $idOrganizadora]);
        $hoy = date('Y-m-d');

        // ---------- COMISIONES (todas las reservas asignadas a esta organizadora) ----------
        // Se muestran también las reservas inactivas (estado 0), marcadas como "Inactiva".
        $filas = Reservas::find()
            ->select(['idreservas', 'nombre', 'fecha_reserva', 'sucursal', 'estado', 'comision_organizadora'])
            ->where(['idorganizadora' => $idOrganizadora])
            ->orderBy(['fecha_reserva' => SORT_ASC])
            ->asArray()
            ->all();

        $proximas = [];
        $realizadas = [];
        $anios = [];
        foreach ($filas as $f) {
            // La comisión se guarda como texto ("1500.00"). Vacía o no numérica = no cargada.
            $texto = trim(str_replace(',', '.', (string) $f['comision_organizadora']));
            $f['monto'] = is_numeric($texto) ? (float) $texto : null;
            $f['finalizado'] = $f['fecha_reserva'] < $hoy;

            if ($f['finalizado']) {
                $anio = (int) substr($f['fecha_reserva'], 0, 4);
                $anios[$anio] = $anio;
                $realizadas[] = $f;
            } else {
                $proximas[] = $f;
            }
        }
        $realizadas = array_reverse($realizadas);   // las más recientes primero
        krsort($anios);

        // Año elegido para "Realizadas" (por defecto el año del evento más reciente)
        $anioElegido = (int) Yii::$app->request->get('anio', $anios ? reset($anios) : date('Y'));
        $realizadasAnio = array_values(array_filter($realizadas, function ($f) use ($anioElegido) {
            return (int) substr($f['fecha_reserva'], 0, 4) === $anioElegido;
        }));

        // ---------- EVENTOS DE TODOS (por salón, desde hoy, solo activos) ----------
        // Campestre está cerrado: si vuelve a abrir, descomentar aquí y en la vista.
        $salones = [
            'Doble via a la guardia' => 'salondoblevia1.jpg',
            'Garden'                 => 'salongarden1.jpg',
            'Parque Urbano'          => 'salonparqueurbano1.jpg',
            // 'Campestre'           => 'verdemanzana.jpg',
        ];
        $eventosPorSalon = array_fill_keys(array_keys($salones), []);
        $proximosEventos = Reservas::find()
            ->select(['idreservas', 'nombre', 'fecha_reserva', 'sucursal'])
            ->where(['estado' => 1, 'sucursal' => array_keys($salones)])
            ->andWhere(['>=', 'fecha_reserva', $hoy])
            ->orderBy(['fecha_reserva' => SORT_ASC])
            ->asArray()
            ->all();
        foreach ($proximosEventos as $e) {
            $eventosPorSalon[$e['sucursal']][] = $e;
        }

        return $this->render('organizadora/perfilorganizadora', [
            'model'           => $model,
            'proximas'        => $proximas,
            'realizadasAnio'  => $realizadasAnio,
            'anios'           => $anios,
            'anioElegido'     => $anioElegido,
            'verRealizadas'   => Yii::$app->request->get('anio') !== null,
            'salones'         => $salones,
            'eventosPorSalon' => $eventosPorSalon,
        ]);
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
                'organizadora',
                'galeriapresupuesto',
                'galeriaequipamiento',
                'galeriadecoracionus',
                'galeriacoordinacion',
                'galeriadecoracion',
                'galeriacronograma',
            ])
            ->one();
        if ($model === null) {
            Yii::$app->user2->logout(false);
            return $this->redirect(['site/logueo']);
        }

        // Mismas pestañas que la ficha de la organizadora, en modo cliente:
        // nunca ve comisiones; puede escribir observaciones, subir/borrar sus imágenes y cargar invitados.
        $datos = self::datosFicha($model);
        $datos['mostrarComision'] = false;

        return $this->render('reservas/perfilreserva', ['model' => $model, 'esCliente' => true] + $datos);
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

        // Vuelve a "Mis observaciones" (donde se ve el aviso de guardado)
        return $this->redirect(['perfilreserva', '#' => 'mis-observaciones']);
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
        return $this->subirImagenCliente(new ReservasGaleriadecoracion(), 'reservasdecoracion');
    }




     public function actionUpload3()
    {
        return $this->subirImagenCliente(new ReservasGaleriacronograma(), 'reservascronograma');
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
        // Cierra la sesión de la organizadora (false = no destruye la sesión PHP
        // completa, así no se cierra un admin o cliente abierto en el mismo navegador).
        Yii::$app->user->logout(false);
        Yii::$app->session->remove('user');
        return $this->redirect(['site/login']);
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


     private function subirImagenCliente($model, string $carpeta = '')
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
        // Datos de la imagen nueva (la ficha del cliente la muestra sin recargar la página)
        return [
            'id'  => (int)$model->idgaleria,
            'url' => Yii::getAlias('@web') . '/imagen/' . $carpeta . '/' . rawurlencode($model->archivo),
        ];
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

        $model = Reservas::find()
            ->where(['idreservas' => (int)$id])
            ->with(['organizadora', 'galeriapresupuesto', 'galeriaequipamiento', 'galeriadecoracionus',
                'galeriacoordinacion', 'galeriadecoracion', 'galeriacronograma'])
            ->one();
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('La reserva no existe.');
        }

        return $this->render('reservasint', ['model' => $model] + self::datosFicha($model));
    }

    /**
     * Datos de las pestañas de la ficha (Cotización, Facturación, Cronograma, Guía).
     * Se arma aparte para reutilizarlo en la vista del cliente.
     */
    public static function datosFicha(Reservas $model): array
    {
        $id = (int)$model->idreservas;
        $cotizacion = \app\models\ReservaCotizacion::deReserva($id);

        return [
            // null = reserva vieja (sin presupuesto por pasos)
            'cotizacion'     => $cotizacion,
            // Reserva vieja con Facturación: su saldo de arranque (null si no tiene)
            'cuentaInicial'  => $cotizacion ? null : \app\models\ReservaCuentaInicial::deReserva($id),
            // Solo pagos válidos: los anulados no se muestran fuera del admin
            'pagos'          => \app\models\ReservaPago::find()
                ->where(['idreservas' => $id, 'estado' => \app\models\ReservaPago::ESTADO_VALIDO])
                ->orderBy(['fecha_pago' => SORT_ASC, 'idpago' => SORT_ASC])
                ->all(),
            'cronograma'     => \app\models\ReservaCronograma::deReserva($id),
            'tieneGuia'      => self::reservaTieneGuia($id),
            'mostrarComision' => self::puedeVerComision($model),
        ];
    }

    /** La comisión solo la ve la organizadora asignada a ese evento. */
    public static function puedeVerComision(Reservas $model): bool
    {
        return !Yii::$app->user->isGuest
            && !empty(Yii::$app->session->get('user'))
            && (int)$model->idorganizadora > 0
            && (int)$model->idorganizadora === (int)Yii::$app->user->id;
    }

    /** ¿El cliente llenó algo en la guía (cronograma de boda / 15 años)? */
    public static function reservaTieneGuia(int $idreservas): bool
    {
        // Fila con algún dato real (hora, proveedor, teléfono o descripción)
        $conDatos = function ($t) {
            return ['or',
                ['and', ['not', [$t . 'hora_inicio' => null]], ['<>', $t . 'hora_inicio', '00:00:00']],
                ['<>', $t . 'proveedor', ''],
                ['<>', $t . 'telefono', ''],
                ['<>', $t . 'descripcion', ''],
            ];
        };

        $fijos = \app\models\DetalleCronograma::find()
            ->alias('d')
            ->innerJoin('cronograma_boda c', 'c.idcronograma = d.idcronograma')
            ->where(['c.idreserva' => $idreservas])
            ->andWhere($conDatos('d.'))
            ->exists();

        return $fijos || \app\models\DetalleCronogramaExtra::find()
            ->where(['idreserva' => $idreservas])
            ->andWhere($conDatos(''))
            ->exists();
    }

    /**
     * Comprobante de un pago (están en privado/, no se pueden abrir por URL directa).
     * Lo ven la organizadora logueada o el cliente dueño de la reserva. Solo pagos válidos.
     */
    public function actionComprobantePago($idpago)
    {
        $pago = \app\models\ReservaPago::findOne([
            'idpago' => (int)$idpago,
            'estado' => \app\models\ReservaPago::ESTADO_VALIDO,
        ]);
        if ($pago === null) {
            throw new \yii\web\NotFoundHttpException('No se encontró el comprobante.');
        }
        $this->verificarAccesoReserva($pago->idreservas);

        $ruta = $pago->getRutaComprobante();
        if ($ruta === null) {
            throw new \yii\web\NotFoundHttpException('No se encontró el comprobante.');
        }
        $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        Yii::$app->response->headers->set('X-Content-Type-Options', 'nosniff');
        return Yii::$app->response->sendFile($ruta, 'comprobante_pago' . (int)$pago->idpago . '.' . $extension, [
            'mimeType' => \yii\helpers\FileHelper::getMimeTypeByExtension($ruta) ?: 'application/octet-stream',
            'inline'   => true,
        ]);
    }

    /**
     * PDF de la reserva (presupuesto, pagos, detalles, cronograma, completo).
     * Organizadora logueada o cliente dueño. La comisión solo sale si es la organizadora del evento.
     */
    public function actionReservaPdf($id, $tipo = 'completo')
    {
        $this->verificarAccesoReserva($id);
        $reserva = Reservas::findOne((int)$id);
        if ($reserva === null) {
            throw new \yii\web\NotFoundHttpException('La reserva no existe.');
        }
        [$contenido, $nombre] = \app\components\PdfReserva::generar($reserva, (string)$tipo, [
            'mostrarComision' => self::puedeVerComision($reserva),
        ]);
        return Yii::$app->response->sendContentAsFile($contenido, $nombre, [
            'mimeType' => 'application/pdf',
            'inline'   => true,
        ]);
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
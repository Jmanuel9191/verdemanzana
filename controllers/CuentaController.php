<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\ContactForm;
use app\models\Forget;
use app\models\LoginWeb2;
use app\models\Reservasform;
use app\models\Suscribe;
use app\models\SuscribeForm;
use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CuentaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'perfilorganizadora', 'perfilreserva', 'deseos', 'logout2',],
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
    public function actionIndex()
    {
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionSuscribe()
    {
        $model = new SuscribeForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $confEmail = Configuracion::find()->one();
            $email = Yii::$app->mailer->compose()
                ->setFrom($model->correo)
                ->setTo($confEmail)
                ->setSubject('Correo de suscripcion')
                ->setHtmlBody("<h3>Correo Registrado</h3><p><strong>Email:</strong>&nbsp;{$model->correo}</p>");
            if ($email->send()) {
                Yii::$app->session->setFlash('success',['message'=>'Mensaje enviado','type'=>'success']);
            } else {
                Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
            }
        } else {
            Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionContacto()
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
        }else {
            Yii::$app->session->setFlash('success',['message'=>'Algunos datos estan incompletos"']);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }



    public function actionReservas()
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


    public function actionConsulta()
    {
        $model = new ContactForm();
        $model['subject']='Formulario de Consultas';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact2(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',['message'=>'Mensaje enviado','type'=>'success']);
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
            }
        }else {
            Yii::$app->session->setFlash('success',['message'=>'Algunos datos estan incompletos"']);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Displays contact page.
     *
     * @return string
     */

    public function actionInformacion()
    {
        $model = new Suscribe();
        $model['subject'] = 'Formulario para pedir informacion';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->suscrib(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',['message'=>'Mensaje enviado','type'=>'success']);
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
            }
        } else {
            Yii::$app->session->setFlash('error',['message'=>'Algunos datos estan incompletos']);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }






    public function actionForget()
    {

        if (!empty(Yii::$app->session->get('user'))) {
            return $this->goHome();
        }

        $model = new Forget();
        if ($model->load(Yii::$app->request->post())) {
            $user = Usuarios::findOne(['email'=>$model->email]);
            if($user) {
                if ($model->send($user['idusuario']))
                    Yii::$app->session->setFlash('success',['message'=>'Correo enviado','type'=>'success']);
                else
                    Yii::$app->session->setFlash('error',['message'=>'Error en el envio, intentelo mas tarde']);
            }
            else{
                Yii::$app->session->setFlash('error',['message'=>'Correo no registrado']);
            }
            return $this->refresh();
        }
        return $this->render('forget', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {

        if (!empty(Yii::$app->session->get('user'))) {
            return $this->goHome();
        }

        $model = new Usuarios();
        $model->fecha_registro = date('Y-m-d H:i:s');
        $model->estado = '1';
        if(!empty(Yii::$app->getRequest()->getQueryParam('email')))
            $model->email=Yii::$app->getRequest()->getQueryParam('email');
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $model->contrasena = md5($model->contrasena);
                if ($model->save()) {
                    Yii::$app->session->setFlash('success',['message'=>'Registro Realizado','type'=>'success']);
                    return $this->refresh();
                }
                Yii::$app->session->setFlash('error',['message'=>'Hubo un error, intentelo mas tarde']);
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionCuenta()
    {

        if (empty(Yii::$app->session->get('user'))) {
            return $this->redirect(['site/login']);
        }

        $model = Usuarios::findOne(['idusuario'=>Yii::$app->session->get('user')['id']]);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->contrasena!=Yii::$app->session->get('user')['password'])
                    $model->contrasena=md5($model->contrasena);
                if ($model->save()) {
                    Yii::$app->session->setFlash('success',['message'=>'actualizacion Realizada','type'=>'success']);
                    return $this->refresh();
                }
                return $this->refresh();
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }




    public function actionLogin()
    {

        if (!empty(Yii::$app->session->get('user2'))) {
            return $this->goHome();
        }

        $model = new LoginWeb2();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            return $this->redirect('perfilreserva');
        }
        /*else{
            Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
            echo Yii::$app->user->getReturnUrl();

        }*/
        return $this->render('cuenta/login', ['model' => $model]);
    }







}

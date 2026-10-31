<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Reservasform extends Model
{
    public $nombre;
    public $apellidos;
    public $email;
    public $pais;
    public $telefono;
    public $ciudad;
    public $salon;
    public $fecha_reserva;
    public $paquete;
    public $mensaje;
    public $subject;
    public $verifyCode;




    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // nombre, email, subject and mensaje are required
            [['nombre', 'email','fecha_reserva','paquete', 'telefono'], 'required'],
            [['fecha_reserva','paquete','mensaje','apellidos','ciudad','pais','telefono' ,'nombre'], 'safe'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [

            'verifyCode' => false,
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */




    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->nombre])
                ->setHtmlBody("<h3> Formulario de Reserva </h3>".
                    "<p><strong>Nombre: </strong>{$this->nombre}</p>".
                    "<p><strong>Apellidos: </strong>{$this->apellidos}</p>".
                    "<p><strong>Correo: </strong>{$this->email}</p>".
                    "<p><strong>Pais: </strong>{$this->pais}</p>".
                    "<p><strong>Ciudad: </strong>{$this->ciudad}</p>".
                    "<p><strong>Telefono: </strong>{$this->telefono}</p>".
                    "<p><strong>Salon: </strong>{$this->salon}</p>".
                    "<p><strong>Paquete requerido: </strong>{$this->paquete}</p>".
                    "<p><strong>Fecha de Reserva: </strong>{$this->fecha_reserva}</p>".
                    "<p><strong>Mensaje: </strong>{$this->mensaje}</p>")
                ->send();

            return true;
        }
        return false;
    }




   /* public function contact2($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->nombre])
                ->setHtmlBody("<h3> Formulario de Suscripcion </h3>".
                    "<p><strong>Nombre: </strong>{$this->nombre}</p>".
                    "<p><strong>Correo: </strong>{$this->email}</p>".
                    "<p><strong>Pais: </strong>{$this->pais}</p>".
                    "<p><strong>Ciudad: </strong>{$this->ciudad}</p>".
                    "<p><strong>Telefono: </strong>{$this->telefono}</p>".
                    "<p><strong>Mensaje: </strong>{$this->mensaje}</p>")
                ->send();

            return true;
        }
        return false;
    }*/





    /*
        public function contact2($email)
        {
            if ($this->validate()) {
                Yii::$app->mailer->compose('layouts/template',[
                    'titulo'=>'Formulario de Consulta de la pagina web',
                    'titulo2'=>'se escribio el siguiente mensaje',
                    'contenido'=>$this->mensaje,
                    'config'=>Configuracion::find()->one(),
                ])
                    ->setTo($email)
                    ->setFrom([$this->email => $this->nombre])
                    ->setSubject($this->subject)
                    //->setTextBody($this->mensaje)
                    ->send();

                return true;
            }
            return false;
        }*/


}

<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SuscribeForm extends Model
{
    public $nombre;
    public $correo;
    public $telefono;
    public $register;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nombre', 'correo', 'telefono'], 'required'],
            ['correo', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Codigo de verificacion',
        ];
    }

    public function suscrib($correo)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($correo)
                ->setFrom([$this->correo => $this->nombre])
                ->setHtmlBody("<h3> Formulario de Suscripcion </h3>".
                    "<p><strong>Nombre: </strong>{$this->nombre}</p>".
                    "<p><strong>Telefono: </strong>{$this->telefono}</p>".
                    "<p><strong>Correo: </strong>{$this->correo}</p>")
                ->send();

            return true;
        }
        return false;
    }

}

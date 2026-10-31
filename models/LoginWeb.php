<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login de organizadoras (sitio web).
 *
 * @property Organizadoras|null $user This property is read-only.
 */
class LoginWeb extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Valida la contraseña.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Email o contraseña incorrectos.');
            }
        }
    }

    /**
     * Inicia sesión SOLO si email y contraseña son correctos.
     * Recién ahí se guarda la organizadora en la sesión.
     */
    public function login()
    {
        if ($this->validate()) {
            $usuario = $this->getUser();
            Yii::$app->session->set('user', $usuario); // lo usan las vistas del perfil
            return Yii::$app->user->login($usuario, 0);
        }
        return false;
    }

    /**
     * Busca la organizadora activa por email (no toca la sesión).
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Organizadoras::findOne(['email' => $this->username, 'estado' => 1]);
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Email',
            'password' => 'Contraseña',
        ];
    }
}
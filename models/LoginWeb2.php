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
class LoginWeb2 extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = null;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Email o usuario',
            'password' => 'Contraseña',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
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
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if (empty($this->_user->auth_key)) {
                $this->_user->generateAuthKey();
                $this->_user->save(false);
            }

            Yii::$app->session->set('user2', [
                'idreservas' => $this->_user->idreservas,
                'nombre' => $this->_user->nombre,
            ]);




            return Yii::$app->user2->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }




    public function logueo()
    {
        if ($this->validate()) {
            if (empty($this->_user->auth_key)) {
                $this->_user->generateAuthKey();
                $this->_user->save(false);
            }
            Yii::$app->session->set('user2', [
                'idreservas' => $this->_user->idreservas,
                'nombre' => $this->_user->nombre,
            ]);



            return Yii::$app->user2->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Reservas::findOne(['email' => $this->username, 'estado' => 1]);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */

}
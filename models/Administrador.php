<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "administrador".
 *
 * @property integer $idadmin
 * @property string $nombre
 * @property string $mail
 * @property string $usuario
 * @property string $contrasena
 * @property string $fecha_registro
 * @property string $estado
 * @property integer $tipo_admin
 * @property string $authKey
 * @property string $accessToken
 */
class Administrador extends ActiveRecord implements IdentityInterface
{
     public $authKey;
    /**
     * 
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'administrador';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_registro'], 'required'],
            [['fecha_registro'], 'safe'],
            [['tipo_admin'], 'integer'],
            [['nombre'], 'string', 'max' => 150],
            [['mail'], 'string', 'max' => 50],
            [['usuario'], 'string', 'max' => 25],
            [['contrasena'], 'string', 'max' => 100],
            [['estado'], 'string', 'max' => 1],
           // [['authKey', 'accessToken'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idadmin' => 'Idadmin',
            'nombre' => 'Nombre',
            'mail' => 'Mail',
            'usuario' => 'Usuario',
            'contrasena' => 'Contrasena',
            'fecha_registro' => 'Fecha Registro',
            'estado' => 'Estado',
            'tipo_admin' => 'Tipo Admin',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
        ];
    }
    
    //
    public static function findIdentity($id)
    {
        return static::findOne(['idadmin' => $id, 'estado' => true]);
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($usuario)
    {
        return static::findOne(['usuario' => $usuario]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Security::generateRandomKey();
    }

    /**
     * Validates password
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($contrasena)
    {
        return $this->contrasena === md5($contrasena);
    }
}

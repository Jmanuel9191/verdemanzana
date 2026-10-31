<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $idusuario
 * @property string $nombres
 * @property string $apellidos
 * @property string $fecha_nacimiento
 * @property string $fecha_registro
 * @property string $direccion
 * @property string $telefono
 * @property string $movil
 * @property string $ciudad
 * @property string $pais
 * @property string $email
 * @property int $sexo
 * @property int $estado
 * @property int $tipo
 * @property string $contrasena
 * @property string $alias
 * @property string $foto
 * @property string $descripcion
 * @property string $facebook
 * @property string $twitter
 * @property string $youtube
 * @property string $instagram
 * @property string $intereses
 * @property string $nombrenit
 * @property string $nit
 *
 * @property Anuncios[] $anuncios
 * @property Compra[] $compras
 * @property Deseo[] $deseos
 * @property PromocionesUsos[] $promocionesUsos
 */
class Organizadoras extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    var $authKey;
    var $contrasena2;



    public static function tableName()
    {
        return 'organizadoras';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['email', 'nombre', 'contrasena'], 'required'],
             [['fecha_registro',], 'safe'],
            //[['estado'], 'integer'],
            [['email'], 'email'],
            [['direccion', 'telefono'], 'string', 'max' => 500],
            // [['telefono', 'movil'], 'string', 'max' => 20],
            [['estado'], 'string', 'max' => 1],
            [['contrasena'], 'string', 'max' => 500],

       /*     [['file', 'file2', 'file3', 'file4'], 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png']*/
            ['contrasena2', 'compare', 'compareAttribute' => 'contrasena'],
            //['file', 'file', 'extensions' => 'pdf', 'mimeTypes' => 'application/pdf'],
            //[['file'], 'file', 'maxSize' => '20000000'],




        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idorganizadora' => 'idorganizadora',

            'email' => 'Correo eléctronico para usuario',
            'nombre' => 'Nombre completo',

            'fecha_registro' => 'Fecha registro',
            'contrasena' => 'Contraseña',

            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',




       
            
            

        ];
    }


    public function getOrganizadoras()
    {
        return $this->hasMany(Organizadoras::className(), ['idreserva' => 'idreserva']);
    }

    public static function getSelectOrganizadoras()
    {
        $result =[];

        $temp = Organizadoras::find()->all();




        foreach ($temp as $item) {

            $result[$item->idorganizadora]="".$item->nombre;

        }

        return $result;
    }


    /**
     * @return \yii\db\ActiveQuery
     */



    //
    public static function findIdentity($id)
    {
        return static::findOne(['idorganizadora' => $id, 'estado' => true]);
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($usuario)
    {
        return static::findOne(['email' => $usuario]);
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


    public static function countries()
    {
        return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
    }

    public function exist()
    {
        $tmp = $this->find()->where(['email' => $this->email])->count();
        return ($tmp > 0);
    }

    static public function getEstado($id = null)
    {
        $estado = ['Inactivo', 'Activo'];
        if (is_null($id))
            return $estado;
        return $estado[$id];
    }

    static public function getTipo($id = null)
    {
        $estado = ['usuario', 'vendedor'];
        if (is_null($id))
            return $estado;
        return $estado[$id];
    }
    
    
    public function upload($name)
    {
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path = 'imagen/usuarios/' . $name . '.' . $this->file->extension;
                if ($this->file->saveAs($path))
                    return true;
            }
        }
        return false;
    }
    public function upload2($name2)
    {
        if ($this->validate()) {
            if (!empty($this->file2)) {
                $path = Yii::getAlias('@webroot') . '/imagen/usuarios/';
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $path = $path . $name2 . '.' . $this->file2->extension;
                if ($this->file2->saveAs($path))
                    return true;
            }
        }
        return false;
    }





}

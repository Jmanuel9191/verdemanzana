<?php

namespace app\models;

use sadovojav\cutter\behaviors\CutterBehavior;
use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "usuarios".
 *
 * @property integer $idusuario
 * @property string $nombre
 * @property string $apellido
 * @property string $direccion
 * @property string $telefono
 * @property string $pais
 * @property string $ciudad
 * @property string $email
 * @property string $contrasena
 * @property string $fecha_reserva
 * @property string $movil
 * @property string $fecha_registro
 * @property string $estado
 * @property string $sexo
 */
class Reservas extends \yii\db\ActiveRecord implements IdentityInterface

{

    var $file;
    var $file2;
    var $file3;
    var $contrasena2;
    /**
     * @inheritdoc
     */
    //var $authKey;

    public static function tableName()
    {
        return 'reservas1';
    }

    public static function primaryKey()
    {
        return ['idreservas'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['telefono','estado', 'idorganizadora'], 'integer'],
            [['limite_invitados'], 'integer', 'min' => 1],
            [['nombre', 'email','telefono', ], 'required'],
            [['paquete_reservar', 'nombre','email', 'mensaje', 'comision_organizadora', 'comentario_decoracion', 'comentario_cronograma', 'presupuesto', 'servicios_adicionales', 'tipo_bebidas', 'vajillasyvasisenmesa', 'katering', 'nombre_decoradora', 'detalle_decoradora'], 'string'],
            [['fecha_registro', 'fecha_reserva', 'detalle_decoracioniglesia', 'otrainfocoordinacion', 'nombre_subcordinadora', 'otros_detalles', 'paquete_reservar','mensaje','presupuesto', 'servicios_adicionales', 'tipo_bebidas', 'vajillasyvasisenmesa', 'katering', 'nombre_decoradora', 'detalle_decoradora'], 'safe'],
            [['sucursal', 'email', 'monto_canceladop', 'saldo_pagarp', 'monto_cancelados', 'saldo_pagars', 'tipo_evento', 'hora_inicio', 'cant_garzones', 'color_sillas', 'color_mantel', 'color_servilleta', 'en_mesa', 'nombre_cordinadora', 'nombre_supervisora', 'contrasena'], 'string', 'max' => 500],
          //  [['file', 'file2', 'file3', 'file4'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
            [
                ['cronogramafile', 'listainvitadosfile', 'presupuestofile'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'pdf, doc, docx, xls, xlsx, ppt, pptx',
                'mimeTypes' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ],
                'maxSize' => 100000000 // 100 MB
            ],

            [['file', 'file2', 'file3'], 'file',
                'extensions' => 'pdf, doc, docx, xls, xlsx, ppt, pptx',
                'mimeTypes' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  // DOCX
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // XLSX
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation' // PPTX
                ]
            ],
            [['file', 'file2', 'file3'], 'file', 'maxSize' => 100000000], // 10MB
           //[['cronogramafile', 'listainvitadosfile', 'presupuestofile'], 'file', 'maxSize' => 100000000],

            [['fecha_boda', 'nombrenovios', 'salondeeventos', 'paqueteeventos', 'horainvitacionevento', 'iglesia', 'direccioniglesia', 'horaiglesia', 'musica', 'horasmusicas', 'maestradeceremonia', 'recomendacionsalon', 'recomendacioniglesia', 'recomendaciondj'], 'string'],
            [['aproxcoordinadora', 'aproxbarra', 'aproxtorta', 'aproxcomida', 'aproxdecoradora', 'aproxcelebrante', 'coordinadorar', 'degustacionbarra', 'desgustaciontorta', 'degustacioncomida', 'decoradorar', 'celebranter'], 'string'],
            [['ccordinacions', 'horaceremonia', 'cdecoracion', 'censamblajemusical', 'cbouquet', 'ctransporte', 'caccesorios', 'csalidasesposos', 'cessionfotos', 'crecmcoodinacion', 'crecmdecoracion', 'crecmensamblaje', 'csugsaludasesposo'], 'string'],
            [['horacivil', 'oficialregistrocivil', 'tipodeceremoniaciv', 'testigoscv', 'cvensabmlemusical', 'cvefectosespec', 'ccprecoctel', 'recmregistrocv', 'recmensambcivil', 'recmefectos', 'detallesimpcv'], 'string'],
            [['cerveza', 'agua', 'otrosbebidas', 'barracocteles', 'horadeapertura', 'recmbebidas', 'recmbarradecocteles', 'sodas'], 'string'],
            [['coreografo', 'maestoceremoniadet', 'bailedeingreso', 'baileprincip', 'brindis', 'baileespecial', 'efectosespeciales', 'recmcoreografo', 'recmmaestrocemonia', 'recmefectosd', 'horaingresonovios', 'detallesimpingresonovis'], 'string'],
            [['horacena', 'cenac', 'tortac', 'bocaditosc', 'dulcesc', 'horalocac', 'recmcena', 'recmtorta', 'recmbocaditos', 'recmdul', 'recmhoraloca'], 'string'],
            [['invitaciones', 'fotografia', 'video', 'vestidodenovia', 'salondebelleza', 'trajenovio', 'glitterbar', 'espejomagico', 'video360', 'memoriphone', 'recmfotoyvideo' , 'recmvestidonovia', 'recmsalonbelleza', 'recmtrajenovio', 'recmgiiterbar', 'recmemori', 'detallesimpoprov'], 'string'],
            [['tipoevento', 'limosna', 'horantesfiesta', 'dulcebienvenida', 'mesadulces', 'mesabocaditos', 'maquetatortafalsa', 'ingresoin', 'experienciasin', 'mariachis','otrosdetallesfiestas', 'detallesimportantesbebidas'], 'string'],


           //['contrasena2', 'compare', 'compareAttribute' => 'contrasena'],
           //[['fuente'], 'string', 'max' => 50],
           //[['video'], 'string', 'max' => 450],
            //[['foto_portada'], 'string', 'max' => 100],
           //[['foto_contenido'], 'string', 'max' => 150],
           //[['estado'], 'string', 'max' => 50],
            //['foto_portada', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            //['foto_contenido', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idreservas' => 'idreservas',
            'nombre' => 'Nombre(s)',
            'email' => 'Email para iniciar session',
            'sucursal' => 'Sucursal',
            'telefono' => 'Telefono',
            'fecha_reserva' => 'Fecha de reserva',
            'mensaje' => 'Mensaje',
            'presupuesto' => 'Presupuesto',
             'monto_canceladop' => 'Monto cancelado',
            'saldo_pagarp' => 'Saldo a pagar',
            'servicios_adicionales' => 'Servicios adicionales',
            'monto_cancelados' => 'Monto cancelado',
            'saldo_pagars' => 'Saldo a pagar',
            'idorganizadora' => 'Organizadora asociada',
            'tipo_evento' => 'Nombre de otro tipo de evento',
            'hora_inicio' => 'Hora de inicio',
            'cant_garzones' => 'Cantidad de garzones',
            'color_sillas' => 'Color de silla',
            'color_mantel' => 'Color de mantel',
            'color_servilleta' => 'Color de servilletas',
            'paquete_reservar' => 'Tipo de Paquete',
            'en_mesa' => 'En mesa',
            'tipo_bebidas' => 'Tipo de bebidas',
            'vajillasyvasisenmesa' => 'Vajillas y vasos en mesa',
            'katering' => 'Katering',
            'nombre_decoradora' => 'Nombre de la decoradora',
            'detalle_decoradora' => 'Detalle de la decoradoracion salon ',
            'detalle_decoracioniglesia' => 'Detalle de la decoracion iglesia',
            'otros_detalles' => 'Otros detalles',
            'nombre_cordinadora' => 'Nombre de la  coordinadora',
            'nombre_subcordinadora' => 'Nombre de la  sub-coordinadora',
            'nombre_supervisora' => 'Nombre de la supervisora',
            'contrasena' => 'Contraseña',
            'comision_organizadora' => 'Comision de la organizadora',
            'comentario_decoracion' => 'Comentario sobre la decoracion',
            'comentario_cronograma' => 'Comentario sobre el cronograma',
            'cronogramafile' => 'Cronograma archivo',
            'file' => 'Cronograma archivo',
            'listainvitadosfile' => 'Lista de invitados',
            'file2' => 'Lista de invitados',
            'file3' => 'Presupuesto archivo',
            'otrainfocoordinacion' => 'Otra información',
            'presupuestofile' => 'Presupuesto archivo',



            'fecha_boda'=>'Fecha de tu boda o fecha de 15 años',
            'nombrenovios'=>'Nombre de novios o nombre quinceañera',
            'salondeeventos'=>'Salon de evento para matrimonio o quinceaños',
            'paqueteeventos'=>'Paquete para matrimonio o quinceaños',
            'horainvitacionevento'=>'Hora de invitación para matrimonio o quinceaños',
            'iglesia'=>'Iglesia',
            'direccioniglesia'=>'Dirección',
            'horaiglesia'=>'Hora para matrimonio o quinceaños',
            'musica'=>'Música para matrimonio o quinceaños',
            'horasmusicas'=>'Horas para matrimonio o quinceaños',
            'maestradeceremonia'=>'Maestro de ceremonia o Animador en quinceaños',
            'recomendacionsalon'=>'Recomendación salon para matrimonio o quinceaños',
            'recomendacioniglesia'=>'Recomendación iglesia',
            'recomendaciondj'=>'Recomendación DJ para matrimonio o quinceaños',



            'aproxcoordinadora'=>'Aprox reunión coordinadora',
            'aproxbarra'=>'Aprox degustación barra',
            'aproxtorta'=>'Aprox degustación Torta',
            'aproxcomida'=> 'Aprox degustación comida',
            'aproxdecoradora'=> 'Aprox reunión decoradora',
            'aproxcelebrante'=> 'Aprox reunión celebrante',
            'coordinadorar'=>'Coordinadora',
            'degustacionbarra'=>'Degustación barra',
            'desgustaciontorta'=>'Degustación torta',
            'degustacioncomida'=>'Degustación comida',
            'decoradorar'=>'Decoradora',
            'celebranter'=>'Celebrante',




            'ccordinacions'=>'Coordinación',
            'horaceremonia'=>'Hora',
            'cdecoracion'=>'Decoración',
            'censamblajemusical'=>'Ensamble musical',
            'cbouquet'=>'Bouquet y botiener',
            'ctransporte'=>'Transporte',
            'caccesorios'=>'Accesorios(aras, alianzas)',
            'csalidasesposos'=>'Salida de esposos',
            'cessionfotos'=>'Sesión de fotos exterior',
            'crecmcoodinacion'=>'Recomendacion coordinación',
            'crecmdecoracion'=>'Recomendacion decoracióm',
            'crecmensamblaje'=>'Recomendacion ensamble musical',
            'csugsaludasesposo'=>'Recomendacion salidas de esposos',


            'horacivil'=>'Hora civil',
            'oficialregistrocivil'=>'Oficial de registro civil',
            'tipodeceremoniaciv'=>'Tipo de ceremonia',
            'testigoscv'=>'Testigos',
            'cvensabmlemusical'=>'Ensamble musical',
            'cvefectosespec'=>'Efectos especiales y pirotecnia',
            'ccprecoctel'=>'Precoctel',
            'recmregistrocv'=>'Recomendación registro civil',
            'recmensambcivil'=>'Recomendación ensamble musical',
            'recmefectos'=>'Recomendación efectos',
            'detallesimpcv'=>'Detalles importantes ',


            'cerveza'=>'Cerveza para matrimonio o quinceaños',
            'agua'=>'Agua para matrimonio o quinceaños ',
            'otrosbebidas'=>'Otros para matrimonio o quinceaños',
            'barracocteles'=>'Barra de cócteles para matrimonio o quinceaños ',
            'horadeapertura'=>'Hora de apertura para matrimonio o quinceaños',
            'recmbebidas'=>'Recomendación bebidas para matrimonio o quinceaños',
            'recmbarradecocteles'=>'Recomendación cócteles para matrimonio o quinceaños',
            'sodas'=>'Soda para matrimonio o quinceaños',


            'coreografo'=>'Coreógrafo para matrimonio o quinceaños',
            'maestoceremoniadet'=>'Maestro de ceremonia para matrimonio o quinceaños',
            'bailedeingreso'=>'Baile ingreso con canción o musica de ingreso quinceaños',
            'baileprincip'=>'Baile principal o vals para matrimonio o quinceaños',
            'brindis'=>'Brindis para matrimonio o quinceaños',
            'baileespecial'=>'Baile especial o inicio fiesta para matrimonio o quinceaños',
            'efectosespeciales'=>'Efectos especiales y pirotecnia para matrimonio o quinceaños',
            'recmcoreografo'=>'Recomendación coreógrafo para matrimonio o quinceaños',
            'recmmaestrocemonia'=>'Recomendación maestro de ceremonia para matrimonio o quinceaños',
            'recmefectosd'=>'Recomendación efectos para matrimonio o quinceaños',
            'horaingresonovios'=>'Hora para matrimonio o quinceaños',
            'detallesimpingresonovis'=>'Detalles importantes',


            'horacena'=>'Hora para matrimonio o quinceaños',
            'cenac'=>'Cena para matrimonio o quinceaños',
            'tortac'=>'Torta para matrimonio o quinceaños',
            'bocaditosc'=>'Bocaditos para matrimonio o quinceaños',
            'dulcesc'=>'Dulces',
            'horalocac'=>'Hora loca para matrimonio o quinceaños',
            'recmcena'=>'Recomendación cena para matrimonio o quinceaños',
            'recmtorta'=>'Recomendación torta para matrimonio o quinceaños',
            'recmbocaditos'=>'Recomendación bocaditos para matrimonio o quinceaños',
            'recmdul'=>'Recomendación dulces',
            'recmhoraloca'=>'Recomendación Hora loca para matrimonio o quinceaños',


            'invitaciones'=>'Invitaciones para matrimonio o quinceaños',
            'fotografia'=>'Fotografía para matrimonio o quinceaños',
            'video'=>'Video para matrimonio o quinceaños',
            'vestidodenovia'=>'Vestido de novia o de quinceañera ',
            'salondebelleza'=>'Salón de belleza para matrimonio o quinceaños',
            'trajenovio'=>'Traje de novio',
            'glitterbar'=>'Glitterbar para matrimonio o quinceaños',
            'espejomagico'=>'Espejo mágico para matrimonio o quinceaños ',
            'video360'=>'Video 360° o Tunel para matrimonio o quinceaños',
            'memoriphone'=>'Memori phone para matrimonio o quinceaños',
            'recmfotoyvideo' =>'Recomendación foto y video para matrimonio o quinceaños',
            'recmvestidonovia'=>'Recomendación vestido de novia',
            'recmsalonbelleza'=>'Recomendación salón de belleza para matrimonio o quinceaños',
            'recmtrajenovio'=>'Recomendación traje de novio',
            'recmgiiterbar'=>'Recomendación Glitterbar para matrimonio o quinceaños',
            'recmemori'=>'Recomendación Memori phone para matrimonio o quinceaños',
            'detallesimpoprov'=>'Detalles importantes',


            'tipoevento'=>'Tipo de evento',
            'limosna'=>'Limosna',
            'horantesfiesta'=>'Hora',

            'dulcebienvenida'=>'Dulce de bienvenida',
            'mesadulces'=>'Mesa de dulces',
            'mesabocaditos'=>'Mesa de bocaditos',
            'maquetatortafalsa'=>'Maqueta torta falsa',
            'ingresoin'=>'Ingreso',
            'experienciasin'=>'Experiencia',
            'mariachis'=>'Mariachis Para quince años',
            'detallesimportantesbebidas'=>'Detalles importantes',
            'otrosdetallesfiestas'=>'Detalles importantes',


            'limite_invitados'=>'Cantidad de invitados',











        ];
    }

    public function getOrganizadora()
    {
        return $this->hasOne(Organizadoras::className(), ['idorganizadora' => 'idorganizadora']);
    }



    public function getOrganizadoras()
    {
        return $this->hasMany(Organizadoras::className(), ['idorganizadora' => 'idorganizadora']);
    }



    public function getGaleriacronograma()
    {
        return $this->hasMany(ReservasGaleriacronograma::className(), ['idreservas' => 'idreservas']);
    }

    public function getGaleriadecoracion()
    {
        return $this->hasMany(ReservasGaleriadecoracion::className(), ['idreservas' => 'idreservas']);
    }

    public function getGaleriapresupuesto()
    {
        return $this->hasMany(ReservasGaleriapresupuesto::className(), ['idreservas' => 'idreservas']);
    }

    public function getGaleriaequipamiento()
    {
        return $this->hasMany(ReservasGaleriaequipamiento::className(), ['idreservas' => 'idreservas']);
    }

    public function getGaleriadecoracionus()
    {
        return $this->hasMany(ReservasGaleriadecoracionus::className(), ['idreservas' => 'idreservas']);
    }

    public function getGaleriacoordinacion()
    {
        return $this->hasMany(ReservasGaleriacoordinacion::className(), ['idreservas' => 'idreservas']);
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

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }

    /*public function getGaleria()
    {
        return $this->hasMany(NoticiasGaleria::className(), ['idreservas' => 'idreservas']);
    }*/


    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Noticias::find()
            ->andWhere(['estado' => 1])
            //->andWhere(['destacado' => 1])
            //->andWhere(['modulo' => Modulos::findOne(['estado'=>1,'alias'=>'productos'])['idmodulo']])
            ->distinct();
        if(!empty($cat->categorias))
        {
            $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
            $data->andWhere(['in', 'idcategoria', $tmp2]);
        }
        else {
            $data->andWhere(['idcategoria' => $cat['idcategoria']]);
        }
        $data->orderBy(['idcategoria'=>SORT_DESC]);
        return $data->all();
    }



    public static function findIdentity($id)
    {
        return static::findOne(['idreservas' => $id, 'estado' => 1]);
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
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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



    public function uploadfiles($name)
    {
        $paths = [];
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path1 = 'imagen/usuarios/' . $name . '_cronograma.' . $this->file->extension;
                if ($this->file->saveAs($path1)) {
                    $paths['file'] = $path1;
                }
            }

            if (!empty($this->file2)) {
                $path2 = 'imagen/usuarios/' . $name . '_listainvitados.' . $this->file2->extension;
                if ($this->file2->saveAs($path2)) {
                    $paths['file2'] = $path2;
                }
            }

            if (!empty($this->file3)) {
                $path3 = 'imagen/usuarios/' . $name . '_presupuesto.' . $this->file3->extension;
                if ($this->file3->saveAs($path3)) {
                    $paths['file3'] = $path3;
                }
            }


            return $paths;
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

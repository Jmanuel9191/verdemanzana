<?php

namespace app\models;
use Yii;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property integer $idusuario
 * @property string $nombre
 * @property string $telefono
 * @property string $ciudad
 * @property string $email
 * @property string $fecha_registro
 * @property string $estado
 */
class Cotizaciones extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cotizaciones';
    }

    /**
     * @inheritdoc
     */




    public function rules()
    {
        return [


            [['nombre', 'telefono', 'tipo_evento',  'fecha_evento', 'email', 'sucursal'], 'required'],
            [['fecha_registro', 'email', 'fecha_evento', 'cantidad_invitados', 'sucursal', 'comentario'], 'safe'],
            [['email', 'fecha_evento', 'cantidad_invitados', 'sucursal' ], 'string', 'max' => 500],
            [['telefono'], 'string', 'max' => 500],
            [['estado'], 'string', 'max' => 1],
            [['comentario'], 'string'],
            [['email'], 'email'],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idcotizacion' => 'idcotizacion',
            'nombre' => 'Nombre Completo',
           'telefono' => 'Telefono',
           'tipo_evento' => 'Tipo de evento',
           'fecha_evento' => 'fecha del evento',
           'email' => 'Email',
           'cantidad_invitados' => 'Cantidad de invitados',
           'sucursal' => 'Sucursal',
            'comentario' => 'Comentario',

            'fecha_registro' => 'Fecha Registro',
        

        ];
    }



    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->nombre])
                ->setHtmlBody("<h3> Formulario de Suscripcion </h3>".
                    "<p><strong>Mensaje: </strong>{$this->mensaje}</p>")
                ->send();
            return true;
        }
        return false;
    }


    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Cotizaciones::find()
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

    public function exist()
    {
        $tmp = $this->find()->where(['email' => $this->email])->count();
        return ($tmp > 0);
    }


    public static function getSelectMenu()
    {

        $result =[];
        $temp = Club::find()->all();

        foreach ($temp as $item) {

            $result[$item->nombre]="<span class='fa fa-square'></span> ".$item->nombre;

        }

        return $result;
    }

    public function getEstado($all = false)
    {
        $estado = [
           'No tickeado',
           'Tickeado'
        ];
        if ($all)
            return $estado;

        return $estado[$this->estado];
    }

    public function getEstado2($all = false)
    {
        $estado2 = [
            'No tickeado',
            'Tickeado'
        ];
        if ($all)
            return $estado2;

        return $estado2[$this->estado2];
    }
}

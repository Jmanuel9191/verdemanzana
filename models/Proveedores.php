<?php

namespace app\models;

use sadovojav\cutter\behaviors\CutterBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "noticias".
 *
 * @property integer $idnoticia
 * @property string $titulo
 * @property integer $idcategoria
 * @property string $resumen
 * @property string $descripcion
 * @property string $otradescripcion
 * @property string $fuente
 * @property string $video
 * @property string $fecha_registro
 * @property string $fecha_noticia
 * @property string $foto_portada
 * @property string $foto_contenido
 * @property string $estado
 * @property string $destacado
 * @property string $tags
 * @property integer $posicion
 */
class Proveedores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proveedores';
    }

   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria', 'posicion'], 'integer'],
            [['descripcion','idcategoria'], 'required'],
            [['descripcion', 'otrascaracteristicas'], 'string'],
            [['fecha_registro'], 'safe'],
            [['titulo', 'facebook', 'twitter', 'youtube', 'linkedin', 'pinterest'], 'string', 'max' => 250],
            //[['fuente'], 'string', 'max' => 50],
            //[['video'], 'string', 'max' => 450],
            [['foto'], 'string', 'max' => 250],
            //[['foto_contenido'], 'string', 'max' => 150],
            [['estado', 'destacado'], 'string', 'max' => 1],
            ['foto', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
           // ['foto_contenido', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idprooveedor' => 'idproovedor',
            'titulo' => 'Titulo o Empresa',
            'idcategoria' => 'Idcategoria',
            'descripcion' => 'Descripcion',
            'otrascaracteristicas' => 'Otras caracteristicas',

            'movil' => 'Movil',
            'facebook' => 'Facebook ',
            'twitter' => 'Twitter',
            'youtube' => 'Youtube ',
            'linkedin' => 'Linkedin',
            'estado' => 'Estado',
            'destacado' => 'Destacado',
            'foto' => 'Foto',
            'posicion' => 'Posicion',
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }

    public function getGaleria()
    {
        return $this->hasMany(ProveedorGaleria::className(), ['idproovedor' => 'idproovedor']);
    }

    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Proveedores::find()
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
}

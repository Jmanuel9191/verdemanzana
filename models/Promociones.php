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
class Promociones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promociones';
    }

  
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria', 'posicion'], 'integer'],
            [['resumen', 'descripcion','idcategoria'], 'required'],
            [['resumen', 'descripcion',], 'string'],
            [['fecha_registro', 'fecha_promocion', 'fecha_promocion_fin'], 'safe'],
            [['titulo'], 'string', 'max' => 250],
            //[['fuente'], 'string', 'max' => 50],
            //[['video'], 'string', 'max' => 450],
            [['precio_antes', 'precio_despues'], 'number'],
            [['foto'], 'string', 'max' => 100],
            [['estado', 'destacado'], 'string', 'max' => 1],
            ['foto', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idpromocion' => 'idpromocion',
            'titulo' => 'Titulo',
            'idcategoria' => 'Idcategoria',
            'resumen' => 'Resumen',
            'descripcion' => 'Descripcion',

            'video' => 'Video',
            'fecha_registro' => 'Fecha Registro',
            'fecha_promocion' => 'Fecha Promocion',
            'fecha_promocion_fin' => 'Fecha Fin de Promocion',
            'foto' => 'Foto',
            'precio_antes' => 'Precio Antes',
            'precio_despues' => 'Precio Despues',

            'estado' => 'Estado',
            'destacado' => 'Destacado',
            //'tags' => 'Tags',
            'posicion' => 'Posicion',
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }

    public function getGaleria()
    {
        return $this->hasMany(PromocionesGaleria::className(), ['idpromocion' => 'idpromocion']);
    }

    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Promociones::find()
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

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
class Noticias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'noticias';
    }

   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria', 'posicion'], 'integer'],
            [['titulo','idcategoria'], 'required'],
            [['resumen', 'descripcion',], 'string'],
            [['fecha_registro', 'fecha_noticia'], 'safe'],
            [['titulo', 'otradescripcion'], 'string', 'max' => 250],
            [['fuente'], 'string', 'max' => 50],
            [['video'], 'string', 'max' => 450],
            [['foto_portada'], 'string', 'max' => 100],
            [['foto_contenido'], 'string', 'max' => 150],
            [['estado', 'destacado'], 'string', 'max' => 1],
            ['foto_portada', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            ['foto_contenido', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idnoticia' => 'Idnoticia',
            'titulo' => 'Titulo',
            'idcategoria' => 'Idcategoria',
            'resumen' => 'Resumen',
            'descripcion' => 'Descripcion',
            'otradescripcion' => 'Otradescripcion',
            'fuente' => 'Fuente',
            'video' => 'Video',
            'fecha_registro' => 'Fecha Registro',
            'fecha_noticia' => 'Fecha Noticia',
            'foto_portada' => 'Foto Portada',
            'foto_contenido' => 'Foto Contenido',
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
        return $this->hasMany(NoticiasGaleria::className(), ['idnoticia' => 'idnoticia']);
    }

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
}

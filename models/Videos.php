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
class Videos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'videos';
    }

  
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria', 'posicion'], 'integer'],
            [['url','idcategoria'], 'required'],
            [['fecha_registro'], 'safe'],
            [['titulo'], 'string', 'max' => 250],
            [['url'], 'string', 'max' => 450],
            [['foto_portada'], 'string', 'max' => 250],
            [['estado', 'destacado'], 'string', 'max' => 1],
            ['foto_portada', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idvideo' => 'Idvideo',
            'titulo' => 'Titulo',
            'idcategoria' => 'Idcategoria',
            'url' => 'url',
            'fecha_registro' => 'Fecha Registro',
            'foto_portada' => 'Foto Portada',
            'estado' => 'Estado',
            'destacado' => 'Destacado',
            //'tags' => 'Tags',

        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }



    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Videos::find()
            ->andWhere(['estado' => 1])
            //->andWhere(['destacado' => 1])
            //->andWhere(['modulo' => Modulos::findOne(['estado'=>1,'alias'=>'proyectos'])['idmodulo']])
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

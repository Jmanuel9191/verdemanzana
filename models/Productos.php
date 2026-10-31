<?php

namespace app\models;

use sadovojav\cutter\behaviors\CutterBehavior;
use yii\helpers\ArrayHelper;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "productos".
 *
 * @property integer $idproducto
 * @property string $titulo
 * @property string $resumen
 * @property string $descripcion
 * @property string $otradescripcion
 * @property string $precio
 * @property string $preciooferta
 * @property string $video
 * @property string $codigo
 * @property string $archivo
 * @property string $fecha_registro
 * @property string $foto
 * @property string $estado
 * @property string $destacado
 * @property integer $posicion
 */
class Productos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'productos';
    }

    /**
     * @inheritdoc
     */
    

    var $file;
    var $idcategorias;

    public function rules()
    {
        return [
            [['titulo',], 'required'],
            [['resumen', 'descripcion','otradescripcion'], 'string'],
             [['preciooferta'], 'number'],
            [['fecha_registro', 'idcategorias'], 'safe'],
            [['posicion'], 'integer'],
            [['titulo', 'resumen','precio'], 'string', 'max' => 250],
            [['video'], 'string', 'max' => 500],
            [['codigo'], 'string', 'max' => 50],
            [['archivo'], 'string', 'max' => 150],
            [['foto'], 'string', 'max' => 100],
            [['estado', 'destacado'], 'string', 'max' => 1],

            ['file', 'file', 'extensions' => 'pdf', 'mimeTypes' => 'application/pdf'],
            [['file'], 'file', 'maxSize' => '2000000'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idproducto' => 'Idproducto',
            'titulo' => 'Titulo',
            'resumen' => 'Resumen',
            'descripcion' => 'Descripcion',
            'otradescripcion' => 'Otradescripcion',
            'precio' => 'Precio',
            'preciooferta' => 'Preciooferta',
            'video' => 'Video',
            'codigo' => 'Codigo',
            'archivo' => 'Archivo',
            'fecha_registro' => 'Fecha Registro',
            'foto' => 'Foto',
            'estado' => 'Estado',
            'destacado' => 'Destacado',
            'posicion' => 'Posicion',
        ];
    }

    public function getCategorias()
    {
        return $this->hasMany(ProductosCategoria::className(), ['idproducto' => 'idproducto']);
    }

    public function getGaleria()
    {
        return $this->hasMany(ProductosGaleria::className(), ['idproducto' => 'idproducto']);
    }

    static public function getAlies($alias)
    {
        $cat = Categoria::findOne(['alias' => $alias]);

        $data = Productos::find()
            ->andWhere(['estado' => 1])
            //->andWhere(['productos.destacado' => 1])
            //->andWhere(['modulo' => Modulos::findOne(['estado'=>1,'alias'=>'productos'])['idmodulo']])
            ->joinWith(['categorias'])
            ->distinct();
        if (!empty($cat->categorias)) {
            $tmp2 = ArrayHelper::getColumn($cat->categorias, 'idcategoria');
            $data->andWhere(['in', 'idcategoria', $tmp2]);
        } else {
            $data->andWhere(['idcategoria' => $cat['idcategoria']]);
        }
        $data->orderBy(['idproducto' => SORT_ASC]);
        return $data->all();
    }

    public function upload($name)
    {
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path = 'imagen/productos/' . $name . '.' . $this->file->extension;
                if ($this->file->saveAs($path))
                    return true;
            }
        }
        return false;
    }


    public static function getSelectMenu()
    {

        $result =[];
        $temp = Productos::findAll(['estado'=> '1']);

        foreach ($temp as $item) {

            $result[$item->titulo]=$item->titulo;

        }

        return $result;
    }



}

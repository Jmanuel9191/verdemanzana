<?php

namespace app\models;

use Yii;
use sadovojav\cutter\behaviors\CutterBehavior;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categoria".
 *
 * @property integer $idcategoria
 * @property string $nombre
 * @property string $descripcion
 * @property string $alias
 * @property string $imagen
 * @property integer $idpadre
 * @property integer $modulo
 * @property string $estado
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria';
    }

/*
    public function behaviors()
    {
        return [
            'image' => [
                'class' => CutterBehavior::className(),
                'attributes' => 'imagen',
                //'attributes' => ['foto_portada', 'foto_contenido'],
                'baseDir' => '/imagen/categorias',
                'basePath' => '@webroot',
                'baseWeb' => '@web',
            ],
        ];
    }
*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'modulo'], 'required'],
            [['descripcion'], 'string'],
            [['idpadre', 'modulo'], 'integer'],
            [['nombre'], 'string', 'max' => 250],
            [['alias'], 'string', 'max' => 20],
            [['imagen'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idcategoria' => 'Idcategoria',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'alias' => 'Alias',
            'imagen' => 'Imagen',
            'idpadre' => 'Idpadre',
            'modulo' => 'Modulo',
            'estado' => 'Estado',
        ];
    }

    public function getCategorias()
    {
        return $this->hasMany(Categoria::className(), ['idpadre' => 'idcategoria']);
    }
    public function getCategorias2()
    {
        return $this->hasMany(Categoria::className(), ['idpadre' => 'idcategoria'])->orderBy(['nombre' => SORT_ASC]);
    }

    public function getCategoryMenu($modulo,$limit=0, $offset=7)
    {
        $data = Categoria::find()
            ->asArray()
            ->where(['modulo'=>Modulos::findOne(['alias'=>$modulo])['idmodulo'],'idpadre'=>'0','estado'=>'1'])
            ->orderBy(['idcategoria' => SORT_ASC])
            ->limit($offset, $limit)
            ->all();

        return $data;
    }

    public static function getSelectMenu($modulo)
    {
        $cat = Categoria::find()->where(['=','modulo',Modulos::findOne(['alias'=>$modulo])['idmodulo']])->all();
        $result =[];
        $temp =[];
        foreach ($cat as $item){
            if(empty($item->idpadre)) {
                array_push($temp, $item);
            }
        }
        foreach ($temp as $key => $item) {
            $temps = Categoria::find()->where(['=', 'idpadre', $item->idcategoria])->all();
            if (count($temps)>0) {
                foreach ($temps as $value) {
                    $temps2 = Categoria::find()->where(['=', 'idpadre', $value->idcategoria])->all();
                    if (count($temps2)>0) {
                        foreach ($temps2 as $value2) {
                            $result["<span class='fa fa-square'></span> ".$item->nombre]["<span class='fa fa-caret-right'></span> ".$value->nombre][$value2->idcategoria] = "<span class='fa fa-angle-double-right'></span> ".$value2->nombre;
                        }
                    } else {
                        $result["<span class='fa fa-square'></span> ".$item->nombre][$value->idcategoria] = "<span class='fa fa-caret-right'></span> ".$value->nombre;
                    }
                }
            }
            else{
                $result[$item->idcategoria]="<span class='fa fa-square'></span> ".$item->nombre;
            }
        }
        return $result;
    }

    public function getMenu($modulo)
    {
        $data = Categoria::find()
            ->where(['modulo'=>Modulos::findOne(['alias'=>$modulo])['idmodulo'],'idpadre'=>'0','estado'=>'1'])
            ->orderBy(['idcategoria' => SORT_ASC])
            ->all();

        return $data;
    }

    static public function getMenu2($alias,$limit=null)
    {
        $data = Categoria::find()
            ->where(['idpadre'=>Categoria::findOne(['estado'=>1,'alias'=>$alias])['idcategoria'],'estado'=>'1'])
            ->orderBy(['idcategoria' => SORT_ASC]);
        if(!is_null($limit))
        {
            $data->limit($limit);
        }
        return $data->all();
    }


    static public function getMenu3($alias,$limit=null)
    {
        $data = Categoria::find()
            ->where(['idpadre'=>Categoria::findOne(['estado'=>1,'alias'=>$alias])['idcategoria'],'estado'=>'1'])
            ->orderBy(['idcategoria' => SORT_ASC]);
        if(!is_null($limit))
        {
            $data->limit($limit);
        }
        return $data->all();
    }

}

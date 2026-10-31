<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "productos_categoria".
 *
 * @property integer $idpc
 * @property integer $idproducto
 * @property integer $idcategoria
 * @property string $fecha_registro
 */
class ProductosCategoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'productos_categoria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idproducto', 'idcategoria'], 'required'],
            [['idproducto', 'idcategoria'], 'integer'],
            [['fecha_registro'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idpc' => 'Idpc',
            'idproducto' => 'Idproducto',
            'idcategoria' => 'Idcategoria',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }

    public function getCategorias()
    {
        return $this->hasMany(Categoria::className(), ['idcategoria' => 'idcategoria']);
    }
}

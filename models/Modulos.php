<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modulos".
 *
 * @property integer $idmodulo
 * @property string $nombre
 * @property string $alias
 * @property string $icono
 * @property string $fecha_registro
 * @property string $estado
 */
class Modulos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modulos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['icono'], 'required'],
            [['fecha_registro'], 'safe'],
            [['nombre'], 'string', 'max' => 200],
            [['alias'], 'string', 'max' => 100],
            [['icono'], 'string', 'max' => 250],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idmodulo' => 'Idmodulo',
            'nombre' => 'Nombre',
            'alias' => 'Alias',
            'icono' => 'Icono',
            'fecha_registro' => 'Fecha Registro',
            'estado' => 'Estado',
        ];
    }
}

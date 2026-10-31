<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form_layout_orden".
 */
class FormLayoutOrden extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'form_layout_orden';
    }

    public function rules()
    {
        return [
            [['grupo', 'campo', 'etiqueta', 'tipo'], 'required'],
            [['orden'], 'integer'],
            [['activo'], 'boolean'],
            [['grupo'], 'string', 'max' => 100],
            [['campo'], 'string', 'max' => 255],
            [['etiqueta'], 'string', 'max' => 255],
            [['tipo'], 'in', 'range' => ['fijo', 'extra']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo' => 'Grupo',
            'campo' => 'Campo',
            'etiqueta' => 'Etiqueta Visible',
            'tipo' => 'Tipo (fijo o extra)',
            'orden' => 'Orden',
            'activo' => 'Activo',
        ];
    }
}

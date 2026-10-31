<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "noticias_galeria".
 *
 * @property integer $idgaleria
 * @property integer $idnoticia
 * @property string $archivo
 * @property string $fecha_registro
 */
class ProveedorGaleria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $file;
    public static function tableName()
    {
        return 'proveedores_galeria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idproovedor'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['archivo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idgaleria' => 'Idgaleria',
            'idproovedor' => 'Idproovedor',
            'archivo' => 'Archivo',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    public function upload($name)
    {
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path = 'imagen/proveedores/' . $name . '.' . $this->file->extension;
                if ($this->file->saveAs($path))
                    return true;
            }
        }
        return false;
    }
}

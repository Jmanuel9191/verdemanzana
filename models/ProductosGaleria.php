<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "productos_galeria".
 *
 * @property integer $idgaleria
 * @property integer $idproducto
 * @property string $archivo
 * @property string $fecha_registro
 */
class ProductosGaleria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $file;
    public static function tableName()
    {
        return 'productos_galeria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idproducto'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['archivo'], 'string', 'max' => 255],
            ['file', 'file', 'extensions' => 'jpg, gif, png', 'mimeTypes' => 'image/jpeg, image/gif, image/png'],
            [['file'], 'file', 'maxSize'=>'2000000'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idgaleria' => 'Idgaleria',
            'idproducto' => 'Idproducto',
            'archivo' => 'Archivo',
            'fecha_registro' => 'Fecha Registro',
        ];
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
}

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
class ReservasGaleriadecoracionus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $file;
    public static function tableName()
    {
        return 'galeriadecoracion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idreservas'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['archivo'], 'string', 'max' => 255],
                        ['file', 'file', 'extensions' => 'jpg, jpeg, png, gif, webp', 'mimeTypes' => 'image/jpeg, image/png, image/gif, image/webp'],
                        [['file'], 'file', 'maxSize'=>'8000000'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idgaleria' => 'Idgaleria',
            'idreservas' => 'idreservas',
            'archivo' => 'Archivo',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    public function upload($name)
    {
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path = 'imagen/galeriadecoracionus/' . $name . '_galdecoracionus.' . $this->file->extension;
                if ($this->file->saveAs($path))
                    return true;
            }

        }
        return false;
    }
}

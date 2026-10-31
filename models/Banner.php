<?php

namespace app\models;

use Yii;
use sadovojav\cutter\behaviors\CutterBehavior;

/**
 * This is the model class for table "banner".
 *
 * @property string $idbanner
 * @property integer $idcategoria
 * @property string $titulo
 * @property string $resumen
 * @property string $resumen2
 * @property string $resumen3
 * @property string $foto
 * @property string $fecha_registro
 * @property string $estado
 * @property string $url
 * @property string $destino
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $file;
    public static function tableName()
    {
        return 'banner';
    }

    



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria', ], 'required'],
            [['idcategoria'], 'integer'],
            [['fecha_registro'.'tags'], 'safe'],
            [['titulo', 'foto'], 'string', 'max' => 120],
            [['resumen', 'resumen2'], 'string', 'max' => 350],
            [['resumen3', 'url'], 'string', 'max' => 150],
            [['estado'], 'string', 'max' => 1],
            [['logo'], 'string', 'max' => 250],
            ['logo', 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            ['file', 'file', 'extensions' => 'jpg, gif, png', 'mimeTypes' => 'image/jpeg, image/gif, image/png'],
            [['file'], 'file', 'maxSize'=>'2000000']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idbanner' => 'Idbanner',
            'idcategoria' => 'Idcategoria',
            'titulo' => 'Titulo',
            'logo' => 'Logo',
            'resumen' => 'Resumen',
            'resumen2' => 'Resumen2',
            'resumen3' => 'Resumen3',
            'foto' => 'Foto',
            'fecha_registro' => 'Fecha Registro',
            'estado' => 'Estado',
           // 'url' => 'Url',

        ];
    }

    public function upload($name)
    {
        if ($this->validate()) {
            if (!empty($this->file)) {
                $path = 'imagen/banners/' . $name . '.' . $this->file->extension;
                if ($this->file->saveAs($path))
                    return true;
            }
        }
        return false;
    }
}

<?php

namespace app\models;

/**
 * This is the model class for table "paises".
 *
 * @property integer $id
 * @property string $iso
 * @property string $nombre
 */
class Paises extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paises';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso'], 'string', 'max' => 2],
            [['nombre'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso' => 'Iso',
            'nombre' => 'Nombre',
        ];
    }
}

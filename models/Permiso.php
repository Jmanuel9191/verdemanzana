<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permiso".
 *
 * @property integer $idpermiso
 * @property integer $idadmin
 * @property integer $idmodulo
 */
class Permiso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permiso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idadmin', 'idmodulo'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idpermiso' => 'Idpermiso',
            'idadmin' => 'Idadmin',
            'idmodulo' => 'Idmodulo',
        ];
    }
}

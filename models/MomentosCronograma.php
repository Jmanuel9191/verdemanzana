<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "momentos_cronograma".
 *
 * @property int $idmomento
 * @property string $seccion
 * @property string $momento
 * @property int $orden
 * @property string $evento_tipo
 *
 * @property DetalleCronograma[] $detalles
 */
class MomentosCronograma extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'momentos_cronograma';
    }

    public function rules()
    {
        return [
            [['seccion', 'momento', 'orden', 'evento_tipo'], 'required'],
            [['orden'], 'integer'],
            [['seccion', 'momento'], 'string', 'max' => 100],
            [['evento_tipo'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idmomento' => 'ID',
            'seccion' => 'Sección',
            'momento' => 'Momento',
            'orden' => 'Orden',
            'evento_tipo' => 'Tipo de Evento',
        ];
    }

    public function getDetalles()
    {
        return $this->hasMany(DetalleCronograma::class, ['idmomento' => 'idmomento']);
    }
}

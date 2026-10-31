<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_cronograma".
 *
 * @property int $iddetalle
 * @property int $idcronograma
 * @property int $idmomento
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property string|null $proveedor
 * @property string|null $telefono
 * @property string|null $descripcion
 *
 * @property CronogramaBoda $cronograma
 * @property MomentosCronograma $momento
 */
class DetalleCronograma extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'detalle_cronograma';
    }

    public function rules()
    {
        return [
            [['idcronograma', 'idmomento'], 'required'],
            [['idcronograma', 'idmomento'], 'integer'],
            [['hora_inicio', 'hora_fin'], 'safe'],
            [['descripcion'], 'string'],
            [['proveedor'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 50],
            [['idcronograma'], 'exist', 'skipOnError' => true, 'targetClass' => CronogramaBoda::class, 'targetAttribute' => ['idcronograma' => 'idcronograma']],
            [['idmomento'], 'exist', 'skipOnError' => true, 'targetClass' => MomentosCronograma::class, 'targetAttribute' => ['idmomento' => 'idmomento']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iddetalle' => 'ID',
            'idcronograma' => 'ID Cronograma',
            'idmomento' => 'Momento',
            'hora_inicio' => 'Hora de Inicio',
            'hora_fin' => 'Hora de Fin',
            'proveedor' => 'Proveedor',
            'telefono' => 'Teléfono',
            'descripcion' => 'Descripción',
        ];
    }

    public function getCronograma()
    {
        return $this->hasOne(CronogramaBoda::class, ['idcronograma' => 'idcronograma']);
    }

    public function getMomento()
    {
        return $this->hasOne(MomentosCronograma::class, ['idmomento' => 'idmomento']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cronograma_boda".
 *
 * @property int $idcronograma
 * @property int $idreserva
 * @property string|null $creado_en
 *
 * @property Reservas $reserva
 * @property DetalleCronograma[] $detalles
 */
class CronogramaBoda extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'cronograma_boda';
    }

    public function rules()
    {
        return [
            [['idreserva'], 'required'],
            [['idreserva'], 'integer'],
            [['creado_en'], 'safe'],
            [['idreserva'], 'exist', 'skipOnError' => true, 'targetClass' => Reservas::class, 'targetAttribute' => ['idreserva' => 'idreservas']], // ✅ corregido aquí también
        ];
    }

    public function attributeLabels()
    {
        return [
            'idcronograma' => 'ID Cronograma',
            'idreserva' => 'ID Reserva',
            'creado_en' => 'Fecha de Creación',
        ];
    }

    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreserva']);
    }

    public function getDetalles()
    {
        return $this->hasMany(DetalleCronograma::class, ['idcronograma' => 'idcronograma']);
    }
}

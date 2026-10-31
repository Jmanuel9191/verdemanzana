<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form_campos_extra".
 */
class FormCamposExtra extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'form_campos_extra';
    }

    public function rules()
    {
        return [
            [['idreserva', 'grupo', 'etiqueta'], 'required'],
            [['idreserva', 'orden', 'cantidadestrellas'], 'integer'],
            [['valor'], 'string'],
            [['grupo'], 'string', 'max' => 100],
            [['etiqueta'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idcampo_extra' => 'ID',
            'idreserva' => 'ID Reserva',
            'grupo' => 'Grupo o sección',
            'etiqueta' => 'Etiqueta del campo',
            'valor' => 'Valor ingresado',
            'cantidadestrellas' => 'Estrellas',
            'orden' => 'Orden',
        ];
    }

    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreserva']);
    }
}

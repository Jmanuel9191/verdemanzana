<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form_layout_orden".
 */
class FormLayoutOrden extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'form_layout_orden';
    }

    public function rules()
    {
        return [
            [['idreserva', 'grupo', 'campo', 'etiqueta', 'tipo'], 'required'],
            [['idreserva', 'orden'], 'integer'],
            [['activo'], 'boolean'],
            [['tipo_evento'], 'string', 'max' => 20],
            [['grupo', 'campo', 'etiqueta'], 'string', 'max' => 255],
            [['tipo'], 'in', 'range' => ['fijo', 'extra']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idreserva' => 'ID Reserva',
            'tipo_evento' => 'Tipo de Evento',
            'grupo' => 'Grupo',
            'campo' => 'Campo',
            'etiqueta' => 'Etiqueta Visible',
            'tipo' => 'Tipo (fijo o extra)',
            'orden' => 'Orden',
            'activo' => 'Activo',
        ];
    }

    // Opcional: Relación con la reserva
    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreserva']);
    }
}

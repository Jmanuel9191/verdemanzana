<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_cronograma_extra".
 *
 * @property int $idcampo_extra
 * @property int $idreserva
 * @property string|null $grupo
 * @property string|null $etiqueta
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property string|null $proveedor
 * @property string|null $telefono
 * @property string|null $descripcion
 * @property int|null $orden
 * @property string|null $fecha_creacion
 */
class DetalleCronogramaExtra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_cronograma_extra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idreserva'], 'required'],
            [['idreserva', 'orden'], 'integer'],
            [['hora_inicio', 'hora_fin', 'fecha_creacion'], 'safe'],
            [['descripcion'], 'string'],
            [['grupo', 'etiqueta', 'proveedor', 'telefono'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcampo_extra' => 'ID',
            'idreserva' => 'Reserva',
            'grupo' => 'Grupo',
            'etiqueta' => 'Etiqueta',
            'hora_inicio' => 'Hora de Inicio',
            'hora_fin' => 'Hora de Fin',
            'proveedor' => 'Proveedor',
            'telefono' => 'Teléfono',
            'descripcion' => 'Descripción',
            'orden' => 'Orden',
            'fecha_creacion' => 'Fecha de Creación',
        ];
    }

    /**
     * Relación con Reserva (si existe el modelo)
     */
    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreserva']);
    }
}

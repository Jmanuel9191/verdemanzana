<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invitados".
 *
 * @property int $idinvitado
 * @property int $idreservas
 * @property string $apellido
 * @property string $nombre
 * @property string|null $telefono
 * @property int|null $nro_mesa
 * @property string $fecha_registro
 *
 * @property Reservas $reservas
 */
class Invitados extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'invitados';
    }

    public function rules()
    {
        return [
            [['idreservas', 'apellido', 'nombre'], 'required'],
            [['idreservas', 'nro_mesa'], 'integer'],
            [['idreservas'], 'validarLimiteInvitados'],

            [['fecha_registro'], 'safe'],
            [['apellido', 'nombre'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 20],
            [['idreservas'], 'exist', 'skipOnError' => true, 'targetClass' => Reservas::class, 'targetAttribute' => ['idreservas' => 'idreservas']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idinvitado' => 'ID',
            'idreservas' => 'Reserva',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'telefono' => 'Teléfono',
            'nro_mesa' => 'Nro. de Mesa',
            'fecha_registro' => 'Fecha de Registro',
        ];
    }

    public function validarLimiteInvitados($attribute, $params)
    {
        $reserva = Reservas::findOne($this->idreservas);

        if (!$reserva || !$reserva->limite_invitados) {
            return; // No valida si no hay reserva o no se definió límite
        }

        $cantidadActual = self::find()->where(['idreservas' => $this->idreservas])->count();

        // Si está creando uno nuevo (no tiene id aún)
        // o si está actualizando uno que cambiará de reserva (raro, pero posible)
        $esNuevo = $this->isNewRecord || !$this->idinvitado;

        if ($esNuevo && $cantidadActual >= $reserva->limite_invitados) {
            $this->addError($attribute, 'Ya se alcanzó el límite máximo de invitados para esta reserva.');
        }
    }



    /**
     * Relación con la tabla reservas
     */
    public function getReservas()
    {
        return $this->hasOne(Reservas::class, ['idreservas' => 'idreservas']);
    }
}

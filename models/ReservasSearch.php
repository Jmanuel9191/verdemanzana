<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UsuariosSearch represents the model behind the search form about `app\models\Usuarios`.
 */
class ReservasSearch extends Reservas
{
    /** @var string|null rango de fecha del evento (Y-m-d) */
    public $fecha_desde;
    public $fecha_hasta;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idreservas'], 'integer'],
            [['idorganizadora'], 'integer'],
            [['tipoevento'], 'safe'],
            [['fecha_desde', 'fecha_hasta'], 'date', 'format' => 'php:Y-m-d'],
            [['fecha_registro', 'fecha_reserva','paquete_reservar','mensaje','presupuesto', 'servicios_adicionales', 'tipo_bebidas', 'vajillasyvasisenmesa', 'katering', 'nombre_decoradora', 'detalle_decoradora', 'paquete_reservar', 'nombre','email', 'mensaje','presupuesto', 'servicios_adicionales', 'tipo_bebidas', 'vajillasyvasisenmesa', 'katering', 'nombre_decoradora', 'detalle_decoradora', 'sucursal', 'nombre_subcordinadora','otros_detalles', 'email', 'monto_canceladop', 'saldo_pagarp', 'monto_cancelados', 'saldo_pagars', 'tipo_evento', 'hora_inicio', 'cant_garzones', 'color_sillas', 'color_mantel', 'color_servilleta', 'en_mesa', 'nombre_cordinadora', 'nombre_supervisora', 'contrasena', 'detalle_decoracioniglesia' ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Reservas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sucursal' => $this->sucursal,
            'tipoevento' => $this->tipoevento,
            'idorganizadora' => $this->idorganizadora,
            'idreservas' => $this->idreservas,
            'fecha_reserva' => $this->fecha_reserva,
            'fecha_registro' => $this->fecha_registro,
        ]);

        // Rango de fecha del evento
        $query->andFilterWhere(['>=', 'fecha_reserva', $this->fecha_desde ?: null])
            ->andFilterWhere(['<=', 'fecha_reserva', $this->fecha_hasta ?: null]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}

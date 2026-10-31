<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class DetalleCronogramaExtraSearch extends DetalleCronogramaExtra
{
    public function rules()
    {
        return [
            [['iddetalle_extra', 'idreserva', 'orden', 'cantidadestrellas'], 'integer'],
            [['grupo', 'etiqueta', 'hora_inicio', 'hora_fin', 'proveedor', 'telefono', 'descripcion'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DetalleCronogramaExtra::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['orden' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'iddetalle_extra' => $this->iddetalle_extra,
            'idreserva' => $this->idreserva,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'orden' => $this->orden,
            'cantidadestrellas' => $this->cantidadestrellas,
        ]);

        $query->andFilterWhere(['like', 'grupo', $this->grupo])
            ->andFilterWhere(['like', 'etiqueta', $this->etiqueta])
            ->andFilterWhere(['like', 'proveedor', $this->proveedor])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}

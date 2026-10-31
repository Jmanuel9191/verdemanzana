<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetalleCronograma;

class DetalleCronogramaSearch extends DetalleCronograma
{
    public function rules()
    {
        return [
            [['iddetalle', 'idcronograma', 'idmomento'], 'integer'],
            [['hora_inicio', 'hora_fin', 'proveedor', 'telefono', 'descripcion'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DetalleCronograma::find()
            ->joinWith(['momento']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['momento.orden' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'iddetalle' => $this->iddetalle,
            'idcronograma' => $this->idcronograma,
            'idmomento' => $this->idmomento,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
        ]);

        $query->andFilterWhere(['like', 'proveedor', $this->proveedor])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}

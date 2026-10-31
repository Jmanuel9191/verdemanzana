<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MomentosCronograma;

class MomentosCronogramaSearch extends MomentosCronograma
{
    public function rules()
    {
        return [
            [['idmomento', 'orden'], 'integer'],
            [['seccion', 'momento', 'evento_tipo'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MomentosCronograma::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['orden' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idmomento' => $this->idmomento,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['like', 'seccion', $this->seccion])
            ->andFilterWhere(['like', 'momento', $this->momento])
            ->andFilterWhere(['like', 'evento_tipo', $this->evento_tipo]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CronogramaBoda;

class CronogramaBodaSearch extends CronogramaBoda
{
    public function rules()
    {
        return [
            [['idcronograma', 'idreserva'], 'integer'],
            [['creado_en'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CronogramaBoda::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idcronograma' => $this->idcronograma,
            'idreserva' => $this->idreserva,
            'creado_en' => $this->creado_en,
        ]);

        return $dataProvider;
    }
}

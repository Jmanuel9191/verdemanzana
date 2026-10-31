<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BannerSeach represents the model behind the search form about `app\models\Banner`.
 */
class BannerSeach extends Banner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idbanner', 'idcategoria'], 'integer'],
            [['titulo', 'foto', 'fecha_registro', 'estado'], 'safe'],
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
        $query = Banner::find();

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
            'idbanner' => $this->idbanner,
            'idcategoria' => $this->idcategoria,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}

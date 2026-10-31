<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NoticiasSearch represents the model behind the search form about `app\models\Noticias`.
 */
class VideosSearch extends Videos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvideo', 'idcategoria'], 'integer'],
            [['titulo', 'fecha_registro', 'url', 'foto_portada',  'estado', 'destacado'], 'safe'],
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
        $query = Videos::find();

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
            'idvideo' => $this->idvideo,
            'idcategoria' => $this->idcategoria,
            'fecha_registro' => $this->fecha_registro,

            'posicion' => $this->posicion,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])


            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'foto_portada', $this->foto_portada])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'destacado', $this->destacado]);
        //  ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }
}

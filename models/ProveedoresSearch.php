<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NoticiasSearch represents the model behind the search form about `app\models\Noticias`.
 */
class ProveedoresSearch extends Proveedores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idproovedor', 'idcategoria', 'posicion'], 'integer'],
            [['titulo',  'descripcion',  'otrascaracteristicas', 'fecha_registro', 'facebook', 'twitter', 'youtube', 'estado', 'destacado'], 'safe'],
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
        $query = Proveedores::find();

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
            'idproovedor' => $this->idproovedor,
            'idcategoria' => $this->idcategoria,
            'fecha_registro' => $this->fecha_registro,
            'posicion' => $this->posicion,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'otrascaracteristicas', $this->otrascaracteristicas])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])

            ->andFilterWhere(['like', 'facebook', $this->facebook])

            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'destacado', $this->destacado]);
          //  ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }
}

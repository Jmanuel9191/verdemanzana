<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UsuariosSearch represents the model behind the search form of `app\models\Usuarios`.
 */
class OrganizadorasSearch extends Organizadoras
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idorganizadora'], 'integer'],
            [['email', 'nombre', 'telefono', 'direccion',  'contrasena', 'fecha_registro'], 'safe'],
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
        $query = Organizadoras::find();

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
            'idorganizadora' => $this->idorganizadora,
            'fecha_registro' => $this->fecha_registro,

        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
  
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contrasena', $this->contrasena]);


        return $dataProvider;
    }
}

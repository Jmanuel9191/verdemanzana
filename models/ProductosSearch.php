<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductosSearch represents the model behind the search form about `app\models\Productos`.
 */
class ProductosSearch extends Productos
{
    /**
     * @inheritdoc
     */
    var $categoria;
    public function rules()
    {
        return [
            [['idproducto', 'posicion'], 'integer'],
            [['categoria','titulo', 'resumen', 'descripcion', 'codigo', 'archivo', 'fecha_registro', 'foto', 'tags', 'estado', 'destacado'], 'safe'],
            // [['precio', 'preciooferta'], 'number'],
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
        $query = Productos::find();

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
            'idproducto' => $this->idproducto,
            'precio' => $this->precio,
            'preciooferta' => $this->preciooferta,
            'fecha_registro' => $this->fecha_registro,
            'posicion' => $this->posicion,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'resumen', $this->resumen])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'otradescripcion', $this->otradescripcion])
            // ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'archivo', $this->archivo])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'destacado', $this->destacado]);

        if(!empty($this->categoria)) {
            $query->joinWith('categorias');
            $query->andFilterWhere(['idcategoria' => $this->categoria]);

        }
        $query->orderBy(['productos.idproducto'=>SORT_ASC]);

        return $dataProvider;
    }
}

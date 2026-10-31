<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UsuariosSearch represents the model behind the search form about `app\models\Usuarios`.
 */
class CotizacionesSearch extends Cotizaciones
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcotizacion'], 'integer'],
            [['fecha_registro', 'email', 'fecha_evento', 'cantidad_invitados', 'sucursal', 'comentario','nombre', 'telefono', 'tipo_evento',  'comentario', ], 'safe'],
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
        $query = Cotizaciones::find();

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
            'idcotizacion' => $this->idcotizacion,
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'sucursal' => $this->sucursal,
            'estado' => $this->estado,
            'fecha_evento' => $this->fecha_evento,
            'email' => $this->email,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])

            ->andFilterWhere(['like', 'idcotizacion', $this->idcotizacion])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'sucursal', $this->sucursal])

            ->andFilterWhere(['like', 'telefono', $this->telefono])

            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}

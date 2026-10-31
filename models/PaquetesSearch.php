<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 */
class PaquetesSearch extends Paquetes
{
    public function rules()
    {
        return [
            [['idpaquete', 'estado', 'invitados_incluidos', 'horas_incluidas', 'posicion'], 'integer'],
            [['nombre', 'detalle', 'tipo_evento'], 'safe'],
            [['precio'], 'number'],
        ];
    }

    public function scenarios()
    {
        // Ignora los escenarios del modelo padre
        return Model::scenarios();
    }

    /**
     * El buscador no debe convertir ni validar el precio como el formulario.
     */
    public function beforeValidate()
    {
        return Model::beforeValidate();
    }

    /**
     * @param array $params parámetros de la URL (filtros de la grilla)
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Paquetes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => ['posicion' => SORT_ASC, 'nombre' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Filtros exactos
        $query->andFilterWhere([
            'idpaquete'           => $this->idpaquete,
            'estado'              => $this->estado,
            'tipo_evento'         => $this->tipo_evento,
            'precio'              => $this->precio,
            'invitados_incluidos' => $this->invitados_incluidos,
            'horas_incluidas'     => $this->horas_incluidas,
            'posicion'            => $this->posicion,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
              ->andFilterWhere(['like', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
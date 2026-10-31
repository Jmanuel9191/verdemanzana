<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Buscador / filtros de la grilla de Ítems y Proveedores.
 */
class ItemsSearch extends Items
{
    public function rules()
    {
        return [
            [['iditem', 'estado', 'posicion'], 'integer'],
            [['tipo', 'nombre', 'unidad', 'tipo_evento'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    /** El buscador no normaliza precios. */
    public function beforeValidate()
    {
        return Model::beforeValidate();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Items::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 25],
            'sort' => [
                'defaultOrder' => ['posicion' => SORT_ASC, 'nombre' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'iditem'      => $this->iditem,
            'tipo'        => $this->tipo,
            'unidad'      => $this->unidad,
            'tipo_evento' => $this->tipo_evento,
            'estado'      => $this->estado,
            'posicion'    => $this->posicion,
        ]);

        // El buscador de nombre también busca en la empresa del proveedor
        if ($this->nombre !== null && trim($this->nombre) !== '') {
            $texto = trim($this->nombre);
            $query->andWhere(['or', ['like', 'nombre', $texto], ['like', 'empresa', $texto]]);
        }

        return $dataProvider;
    }
}

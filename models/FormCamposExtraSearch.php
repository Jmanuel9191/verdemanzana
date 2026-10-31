<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FormCamposExtraSearch representa el modelo de búsqueda para FormCamposExtra.
 */
class FormCamposExtraSearch extends FormCamposExtra
{
    public function rules()
    {
        return [
            [['id', 'idreserva', 'orden'], 'integer'],
            [['grupo', 'etiqueta', 'valor'], 'safe'],
            [['visible'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FormCamposExtra::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idreserva' => $this->idreserva,
            'orden' => $this->orden,
            'visible' => $this->visible,
        ]);

        $query->andFilterWhere(['like', 'grupo', $this->grupo])
            ->andFilterWhere(['like', 'etiqueta', $this->etiqueta])
            ->andFilterWhere(['like', 'valor', $this->valor]);

        return $dataProvider;
    }
}

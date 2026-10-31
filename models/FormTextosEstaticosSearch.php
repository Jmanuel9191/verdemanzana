<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FormTextosEstaticosSearch representa el modelo de búsqueda.
 */
class FormTextosEstaticosSearch extends FormTextosEstaticos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'orden'], 'integer'],
            [['grupo', 'etiqueta_label', 'contenido_html'], 'safe'],
            [['visible'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     */
    public function search($params)
    {
        $query = FormTextosEstaticos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['grupo' => SORT_ASC, 'orden' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id, 'orden' => $this->orden, 'visible' => $this->visible])
            ->andFilterWhere(['like', 'grupo', $this->grupo])
            ->andFilterWhere(['like', 'etiqueta_label', $this->etiqueta_label])
            ->andFilterWhere(['like', 'contenido_html', $this->contenido_html]);

        return $dataProvider;
    }
}

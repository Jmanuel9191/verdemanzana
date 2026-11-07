<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invitados;

class InvitadosSearch extends Invitados
{
    public function rules()
    {
        return [
            [['idinvitado', 'idreservas', 'nro_mesa'], 'integer'],
            [['apellido', 'nombre', 'telefono', 'fecha_registro'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios(); // mantiene los escenarios por defecto
    }

    public function search($params)
    {
        // orden base: las mesas sin número van al final (si no, MySQL pone los NULL primero)
        $query = Invitados::find()->orderBy(new \yii\db\Expression('nro_mesa IS NULL'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['nro_mesa' => SORT_ASC, 'apellido' => SORT_ASC]],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1'); // no mostrar resultados si hay error de validación
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idinvitado' => $this->idinvitado,
            'idreservas' => $this->idreservas,
            'nro_mesa' => $this->nro_mesa,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'telefono', $this->telefono]);

        return $dataProvider;
    }
}

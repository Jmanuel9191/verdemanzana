<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form_textos_estaticos".
 */
class FormTextosEstaticos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_textos_estaticos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo', 'etiqueta_label'], 'required'],
            [['orden'], 'integer'],
            [['contenido_html'], 'string'],
            [['visible'], 'boolean'],
            [['grupo'], 'string', 'max' => 100],
            [['etiqueta_label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo' => 'Grupo',
            'orden' => 'Orden',
            'etiqueta_label' => 'Título',
            'contenido_html' => 'Contenido HTML',
            'visible' => 'Visible',
        ];
    }
}

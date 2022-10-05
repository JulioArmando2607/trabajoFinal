<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "correlativos".
 *
 * @property int $id_correlativo
 * @property string $modulo
 * @property int $anio
 * @property string|null $serie
 * @property int $numero_correlativo
 * @property int $estado
 */
class Correlativos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'correlativos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modulo', 'anio', 'numero_correlativo', 'estado'], 'required'],
            [['anio', 'numero_correlativo', 'estado'], 'integer'],
            [['modulo', 'serie'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_correlativo' => 'Id Correlativo',
            'modulo' => 'Modulo',
            'anio' => 'Anio',
            'serie' => 'Serie',
            'numero_correlativo' => 'Numero Correlativo',
            'estado' => 'Estado',
        ];
    }
}

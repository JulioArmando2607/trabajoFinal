<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_estado".
 *
 * @property int $id_tipo_estado
 * @property string $siglas
 * @property string $nombre_tipo
 * @property int $flg_estado
 * @property int|null $id_usuario_reg
 * @property string|null $fecha_reg
 * @property string|null $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 */
class TipoEstado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siglas', 'nombre_tipo', 'flg_estado'], 'required'],
            [['flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['siglas', 'nombre_tipo', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_estado' => 'Id Tipo Estado',
            'siglas' => 'Siglas',
            'nombre_tipo' => 'Nombre Tipo',
            'flg_estado' => 'Flg Estado',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
        ];
    }
}

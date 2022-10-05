<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codigos_nota_credito".
 *
 * @property int $id_cod_nota_credito
 * @property string $codigo
 * @property string|null $descripcion
 * @property int $flg_estado
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 */
class CodigosNotaCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codigos_nota_credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['codigo', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cod_nota_credito' => 'Id Cod Nota Credito',
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
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

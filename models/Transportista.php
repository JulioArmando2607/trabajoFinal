<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transportista".
 *
 * @property int $id_transportista
 * @property string|null $razon_social
 * @property string|null $id_tipo_documento
 * @property string|null $numero_documento
 * @property string|null $direccion
 * @property int|null $ubigeo
 * @property string|null $correo
 * @property string|null $urbanizacion
 * @property string|null $referencia
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
 * @property string|null $telefono
 */
class Transportista extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transportista';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ubigeo', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['razon_social', 'id_tipo_documento', 'direccion', 'correo', 'urbanizacion', 'referencia'], 'string', 'max' => 255],
            [['numero_documento'], 'string', 'max' => 30],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['telefono'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transportista' => 'Id Transportista',
            'razon_social' => 'Razon Social',
            'id_tipo_documento' => 'Id Tipo Documento',
            'numero_documento' => 'Numero Documento',
            'direccion' => 'Direccion',
            'ubigeo' => 'Ubigeo',
            'correo' => 'Correo',
            'urbanizacion' => 'Urbanizacion',
            'referencia' => 'Referencia',
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
            'telefono' => 'Telefono',
        ];
    }
}

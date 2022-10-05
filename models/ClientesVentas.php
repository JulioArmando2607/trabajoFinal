<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clientes_ventas".
 *
 * @property int $id_entidad
 * @property int $id_tipo_entidad
 * @property int $id_tipo_documento
 * @property string $numero_documento
 * @property string|null $razon_social
 * @property string|null $telefono
 * @property string|null $correo
 * @property int|null $id_persona
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
class ClientesVentas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes_ventas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_entidad', 'id_tipo_documento', 'numero_documento', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_tipo_entidad', 'id_tipo_documento', 'id_persona', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['numero_documento', 'telefono'], 'string', 'max' => 50],
            [['razon_social'], 'string', 'max' => 200],
            [['correo'], 'string', 'max' => 100],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entidad' => 'Id Entidad',
            'id_tipo_entidad' => 'Id Tipo Entidad',
            'id_tipo_documento' => 'Id Tipo Documento',
            'numero_documento' => 'Numero Documento',
            'razon_social' => 'Razon Social',
            'telefono' => 'Telefono',
            'correo' => 'Correo',
            'id_persona' => 'Id Persona',
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

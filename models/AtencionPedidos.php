<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "atencion_pedidos".
 *
 * @property int $id_atencion_pedidos
 * @property int $id_pedido_cliente
 * @property int|null $conductor
 * @property int|null $id_auxiliar
 * @property int|null $unidad
 * @property int|null $auxiliar
 * @property string|null $observacion
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
class AtencionPedidos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'atencion_pedidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pedido_cliente', 'flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_pedido_cliente', 'conductor', 'id_auxiliar', 'unidad', 'auxiliar', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['observacion'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_atencion_pedidos' => 'Id Atencion Pedidos',
            'id_pedido_cliente' => 'Id Pedido Cliente',
            'conductor' => 'Conductor',
            'id_auxiliar' => 'Id Auxiliar',
            'unidad' => 'Unidad',
            'auxiliar' => 'Auxiliar',
            'observacion' => 'Observacion',
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

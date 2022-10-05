<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_guia_venta".
 *
 * @property int $id_detalle_guia_venta
 * @property int $id_guia_venta
 * @property int|null $id_producto
 * @property string|null $descripcion_producto
 * @property int|null $cantidad
 * @property float|null $peso
 * @property float|null $volumen
 * @property int|null $id_forma_envio
 * @property float|null $monto_envio
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
class DetalleGuiaVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_guia_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_venta', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_guia_venta', 'id_producto', 'cantidad', 'id_forma_envio', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['peso', 'volumen', 'monto_envio'], 'number'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['descripcion_producto'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_guia_venta' => 'Id Detalle Guia Venta',
            'id_guia_venta' => 'Id Guia Venta',
            'id_producto' => 'Id Producto',
            'descripcion_producto' => 'Descripcion Producto',
            'cantidad' => 'Cantidad',
            'peso' => 'Peso',
            'volumen' => 'Volumen',
            'id_forma_envio' => 'Id Forma Envio',
            'monto_envio' => 'Monto Envio',
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

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ventas_factura".
 *
 * @property int $id_ventas_factura
 * @property int|null $id_guia_ventas
 * @property string|null $serie
 * @property string|null $correlativo
 * @property int|null $id_tipo_comprobante
 * @property string|null $tipo_comprobante
 * @property string|null $forma_pago
 * @property string|null $tipo_forma_pago
 * @property float|null $total
 * @property float|null $igv
 * @property float|null $subtotal
 * @property string|null $cliente
 * @property string|null $numero_documento
 * @property int|null $cantidad
 * @property string|null $producto
 * @property string|null $fecha
 * @property string|null $fechacuota
 * @property int|null $estado
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
class VentasFactura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ventas_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_ventas', 'id_tipo_comprobante', 'cantidad', 'estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['total', 'igv', 'subtotal'], 'number'],
            [['fecha', 'fechacuota', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['serie', 'correlativo', 'forma_pago', 'cliente', 'producto'], 'string', 'max' => 255],
            [['tipo_comprobante', 'numero_documento', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['tipo_forma_pago'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ventas_factura' => 'Id Ventas Factura',
            'id_guia_ventas' => 'Id Guia Ventas',
            'serie' => 'Serie',
            'correlativo' => 'Correlativo',
            'id_tipo_comprobante' => 'Id Tipo Comprobante',
            'tipo_comprobante' => 'Tipo Comprobante',
            'forma_pago' => 'Forma Pago',
            'tipo_forma_pago' => 'Tipo Forma Pago',
            'total' => 'Total',
            'igv' => 'Igv',
            'subtotal' => 'Subtotal',
            'cliente' => 'Cliente',
            'numero_documento' => 'Numero Documento',
            'cantidad' => 'Cantidad',
            'producto' => 'Producto',
            'fecha' => 'Fecha',
            'fechacuota' => 'Fechacuota',
            'estado' => 'Estado',
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

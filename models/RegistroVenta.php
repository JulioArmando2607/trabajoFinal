<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_venta".
 *
 * @property int $id_registro_venta
 * @property string|null $fecha_emision
 * @property string|null $serie
 * @property string|null $factura
 * @property int|null $id_cliente
 * @property float|null $valor_venta
 * @property float|null $igv
 * @property float|null $total
 * @property string|null $fecha_cancelacion
 * @property float|null $monto_depositado
 * @property float|null $monto_diferencia
 * @property int|null $id_estado
 * @property string|null $gr
 * @property int|null $provincia
 * @property int|null $agente
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
class RegistroVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_emision', 'fecha_cancelacion', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['id_cliente', 'id_estado', 'provincia', 'agente', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['valor_venta', 'igv', 'total', 'monto_depositado', 'monto_diferencia'], 'number'],
            [['serie', 'factura', 'gr'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_registro_venta' => 'Id Registro Venta',
            'fecha_emision' => 'Fecha Emision',
            'serie' => 'Serie',
            'factura' => 'Factura',
            'id_cliente' => 'Id Cliente',
            'valor_venta' => 'Valor Venta',
            'igv' => 'Igv',
            'total' => 'Total',
            'fecha_cancelacion' => 'Fecha Cancelacion',
            'monto_depositado' => 'Monto Depositado',
            'monto_diferencia' => 'Monto Diferencia',
            'id_estado' => 'Id Estado',
            'gr' => 'Gr',
            'provincia' => 'Provincia',
            'agente' => 'Agente',
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

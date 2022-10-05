<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guia_venta".
 *
 * @property int $id_guia_venta
 * @property string $serie
 * @property int $numero_guia
 * @property string|null $fecha
 * @property int $id_forma_pago
 * @property int $id_tipo_comprobante
 * @property int $id_conductor
 * @property int $id_vehiculo
 * @property int|null $id_tipo_documento_remitente
 * @property int $id_estado
 * @property int|null $id_archivo
 * @property string|null $factura_boleta
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $id_entidad_
 * @property string|null $comentario
 * @property int|null $id_estados_venta
 */
class GuiaVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guia_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serie', 'numero_guia', 'id_forma_pago', 'id_tipo_comprobante', 'id_conductor', 'id_vehiculo', 'id_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['numero_guia', 'id_forma_pago', 'id_tipo_comprobante', 'id_conductor', 'id_vehiculo', 'id_tipo_documento_remitente', 'id_estado', 'id_archivo', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'id_entidad_', 'id_estados_venta'], 'integer'],
            [['fecha', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['serie', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['factura_boleta'], 'string', 'max' => 200],
            [['comentario'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_guia_venta' => 'Id Guia Venta',
            'serie' => 'Serie',
            'numero_guia' => 'Numero Guia',
            'fecha' => 'Fecha',
            'id_forma_pago' => 'Id Forma Pago',
            'id_tipo_comprobante' => 'Id Tipo Comprobante',
            'id_conductor' => 'Id Conductor',
            'id_vehiculo' => 'Id Vehiculo',
            'id_tipo_documento_remitente' => 'Id Tipo Documento Remitente',
            'id_estado' => 'Id Estado',
            'id_archivo' => 'Id Archivo',
            'factura_boleta' => 'Factura Boleta',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'id_entidad_' => 'Id Entidad',
            'comentario' => 'Comentario',
            'id_estados_venta' => 'Id Estados Venta',
        ];
    }
}

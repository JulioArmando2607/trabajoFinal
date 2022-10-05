<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guia_venta_destino".
 *
 * @property int $id_guia_venta_destino
 * @property int $id_guia_remision
 * @property int|null $id_tipo_documento
 * @property string|null $numero_documento
 * @property string|null $nombres_destinatario
 * @property string|null $apellidos_destinatario
 * @property string|null $celular_destinatario
 * @property int|null $id_tipo_entrega
 * @property int|null $id_ubigeo
 * @property string|null $direccion_destinatario
 * @property int|null $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $id_agente
 * @property string|null $otro_consignado
 * @property string|null $observacion
 */
class GuiaVentaDestino extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guia_venta_destino';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_remision', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_guia_remision', 'id_tipo_documento', 'id_tipo_entrega', 'id_ubigeo', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'id_agente'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['numero_documento', 'nombres_destinatario', 'apellidos_destinatario', 'celular_destinatario'], 'string', 'max' => 100],
            [['direccion_destinatario', 'observacion'], 'string', 'max' => 200],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['otro_consignado'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_guia_venta_destino' => 'Id Guia Venta Destino',
            'id_guia_remision' => 'Id Guia Remision',
            'id_tipo_documento' => 'Id Tipo Documento',
            'numero_documento' => 'Numero Documento',
            'nombres_destinatario' => 'Nombres Destinatario',
            'apellidos_destinatario' => 'Apellidos Destinatario',
            'celular_destinatario' => 'Celular Destinatario',
            'id_tipo_entrega' => 'Id Tipo Entrega',
            'id_ubigeo' => 'Id Ubigeo',
            'direccion_destinatario' => 'Direccion Destinatario',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'id_agente' => 'Id Agente',
            'otro_consignado' => 'Otro Consignado',
            'observacion' => 'Observacion',
        ];
    }
}

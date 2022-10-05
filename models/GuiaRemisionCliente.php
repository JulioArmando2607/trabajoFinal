<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guia_remision_cliente".
 *
 * @property int $id_guia_remision_cliente
 * @property int $id_guia_remision
 * @property string|null $grs
 * @property string|null $gr
 * @property string|null $ft
 * @property string|null $delivery
 * @property string|null $oc
 * @property int|null $cantidad
 * @property float|null $peso
 * @property float|null $volumen
 * @property float|null $alto
 * @property float|null $largo
 * @property float|null $ancho
 * @property int|null $id_tipo_carga
 * @property string|null $descripcion
 * @property int|null $id_archivo
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $id_estado_mercaderia
 * @property int|null $id_estado_cargo
 * @property string|null $recibido_por
 * @property string|null $entregado_por
 * @property string|null $observacion
 * @property string|null $fecha_hora_entrega
 * @property string|null $fecha_cargo
 * @property string|null $hora_entrega
 * @property string|null $tipo_carga
 */
class GuiaRemisionCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guia_remision_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_remision', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_guia_remision', 'cantidad', 'id_tipo_carga', 'id_archivo', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'id_estado_mercaderia', 'id_estado_cargo'], 'integer'],
            [['peso', 'volumen', 'alto', 'largo', 'ancho'], 'number'],
            [['fecha_reg', 'fecha_act', 'fecha_del', 'fecha_hora_entrega', 'fecha_cargo', 'hora_entrega'], 'safe'],
            [['grs', 'entregado_por', 'tipo_carga'], 'string', 'max' => 255],
            [['gr', 'ft', 'delivery', 'oc', 'recibido_por'], 'string', 'max' => 100],
            [['descripcion', 'observacion'], 'string', 'max' => 500],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_guia_remision_cliente' => 'Id Guia Remision Cliente',
            'id_guia_remision' => 'Id Guia Remision',
            'grs' => 'Grs',
            'gr' => 'Gr',
            'ft' => 'Ft',
            'delivery' => 'Delivery',
            'oc' => 'Oc',
            'cantidad' => 'Cantidad',
            'peso' => 'Peso',
            'volumen' => 'Volumen',
            'alto' => 'Alto',
            'largo' => 'Largo',
            'ancho' => 'Ancho',
            'id_tipo_carga' => 'Id Tipo Carga',
            'descripcion' => 'Descripcion',
            'id_archivo' => 'Id Archivo',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'id_estado_mercaderia' => 'Id Estado Mercaderia',
            'id_estado_cargo' => 'Id Estado Cargo',
            'recibido_por' => 'Recibido Por',
            'entregado_por' => 'Entregado Por',
            'observacion' => 'Observacion',
            'fecha_hora_entrega' => 'Fecha Hora Entrega',
            'fecha_cargo' => 'Fecha Cargo',
            'hora_entrega' => 'Hora Entrega',
            'tipo_carga' => 'Tipo Carga',
        ];
    }
}

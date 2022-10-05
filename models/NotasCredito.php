<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notas_credito".
 *
 * @property int $id_notas_credito
 * @property int|null $id_ventas_factura
 * @property string|null $fecha_emision
 * @property string $serie
 * @property string|null $correlativo
 * @property string|null $codigo_motivo_nota
 * @property string|null $motivo_n_credito
 * @property string|null $hora
 * @property string|null $documento_electronico_aplicar
 * @property string|null $tipo_documento_c
 * @property int|null $cod_tipo_doc_cliente
 * @property string|null $doc_cliente
 * @property int|null $estado
 * @property string|null $des_tipo_doc
 * @property int|null $id_guia_venta
 * @property string|null $nombre_razon_cliente
 * @property int|null $cantidad
 * @property float|null $total
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
class NotasCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notas_credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ventas_factura', 'cod_tipo_doc_cliente', 'estado', 'id_guia_venta', 'cantidad', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_emision', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['serie', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['total'], 'number'],
            [['serie', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['correlativo', 'codigo_motivo_nota', 'motivo_n_credito', 'hora', 'documento_electronico_aplicar', 'tipo_documento_c', 'doc_cliente', 'des_tipo_doc', 'nombre_razon_cliente'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_notas_credito' => 'Id Notas Credito',
            'id_ventas_factura' => 'Id Ventas Factura',
            'fecha_emision' => 'Fecha Emision',
            'serie' => 'Serie',
            'correlativo' => 'Correlativo',
            'codigo_motivo_nota' => 'Codigo Motivo Nota',
            'motivo_n_credito' => 'Motivo N Credito',
            'hora' => 'Hora',
            'documento_electronico_aplicar' => 'Documento Electronico Aplicar',
            'tipo_documento_c' => 'Tipo Documento C',
            'cod_tipo_doc_cliente' => 'Cod Tipo Doc Cliente',
            'doc_cliente' => 'Doc Cliente',
            'estado' => 'Estado',
            'des_tipo_doc' => 'Des Tipo Doc',
            'id_guia_venta' => 'Id Guia Venta',
            'nombre_razon_cliente' => 'Nombre Razon Cliente',
            'cantidad' => 'Cantidad',
            'total' => 'Total',
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

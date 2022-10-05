<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidacion".
 *
 * @property int $id_liquidacion
 * @property int|null $id_guia_remision
 * @property string|null $fecha
 * @property string|null $guia_pegaso
 * @property int|null $id_guia_cliente
 * @property string|null $guia_cliente
 * @property string|null $origen
 * @property string|null $destino
 * @property float|null $tarifa_provincia_kg_adicional
 * @property int|null $bultos
 * @property float|null $peso
 * @property int|null $id_tipo_carga
 * @property int|null $id_via
 * @property float|null $tarifa_base
 * @property float|null $peso_exceso
 * @property float|null $reembarque
 * @property float|null $costo
 * @property int|null $id_tarifa
 * @property int|null $id_tarifa_provincia_ent
 * @property int $id_usuario_reg
 * @property string|null $fecha_reg
 * @property string|null $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $flg_liquidado
 * @property string|null $observacion
 */
class Liquidacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_remision', 'id_guia_cliente', 'bultos', 'id_tipo_carga', 'id_via', 'id_tarifa', 'id_tarifa_provincia_ent', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'flg_liquidado'], 'integer'],
            [['fecha', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['tarifa_provincia_kg_adicional', 'peso', 'tarifa_base', 'peso_exceso', 'reembarque', 'costo'], 'number'],
            [['id_usuario_reg'], 'required'],
            [['guia_pegaso', 'guia_cliente', 'origen', 'destino'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['observacion'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_liquidacion' => 'Id Liquidacion',
            'id_guia_remision' => 'Id Guia Remision',
            'fecha' => 'Fecha',
            'guia_pegaso' => 'Guia Pegaso',
            'id_guia_cliente' => 'Id Guia Cliente',
            'guia_cliente' => 'Guia Cliente',
            'origen' => 'Origen',
            'destino' => 'Destino',
            'tarifa_provincia_kg_adicional' => 'Tarifa Provincia Kg Adicional',
            'bultos' => 'Bultos',
            'peso' => 'Peso',
            'id_tipo_carga' => 'Id Tipo Carga',
            'id_via' => 'Id Via',
            'tarifa_base' => 'Tarifa Base',
            'peso_exceso' => 'Peso Exceso',
            'reembarque' => 'Reembarque',
            'costo' => 'Costo',
            'id_tarifa' => 'Id Tarifa',
            'id_tarifa_provincia_ent' => 'Id Tarifa Provincia Ent',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'flg_liquidado' => 'Flg Liquidado',
            'observacion' => 'Observacion',
        ];
    }
}

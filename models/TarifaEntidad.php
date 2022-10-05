<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarifa_entidad".
 *
 * @property int $id_tarifa
 * @property int|null $id_entidad
 * @property string|null $id_tipo_carga
 * @property int|null $id_via
 * @property string|null $observacion
 * @property int $flg_estado
 * @property int|null $tipo_tarifa
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property float|null $peso_t_base_general
 * @property float|null $peso_t_base_ref
 * @property float|null $peso_t_base_pel
 * @property float|null $peso_a_base_general
 * @property float|null $peso_a_base_ref
 * @property float|null $peso_a_base_pel
 * @property float|null $tarifa_m_t_costo
 * @property float|null $tarifa_m_t_igv
 * @property float|null $tarifa_m_t_total
 * @property float|null $tarifa_m_t_costo_ref
 * @property float|null $tarifa_m_t_igv_ref
 * @property float|null $tarifa_m_t_total_ref
 * @property float|null $tarifa_m_a_c
 * @property float|null $tarifa_m_a_total
 * @property float|null $tarifa_m_a_igv
 * @property float|null $tarifa_m_a_c_ref
 * @property float|null $tarifa_m_a_igv_ref
 * @property float|null $tarifa_m_a_total_ref
 * @property float|null $tarifa_m_a_c_pel
 * @property float|null $tarifa_m_a_igv_pel
 * @property float|null $tarifa_m_a_total_pel
 * @property float|null $carga_dificil_manejo_sobre
 * @property float|null $tarifa_m_t_c_pel
 * @property float|null $tarifa_m_t_igv_pel
 * @property float|null $tarifa_m_t_total_pel
 */
class TarifaEntidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarifa_entidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entidad', 'id_via', 'flg_estado', 'tipo_tarifa', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['peso_t_base_general', 'peso_t_base_ref', 'peso_t_base_pel', 'peso_a_base_general', 'peso_a_base_ref', 'peso_a_base_pel', 'tarifa_m_t_costo', 'tarifa_m_t_igv', 'tarifa_m_t_total', 'tarifa_m_t_costo_ref', 'tarifa_m_t_igv_ref', 'tarifa_m_t_total_ref', 'tarifa_m_a_c', 'tarifa_m_a_total', 'tarifa_m_a_igv', 'tarifa_m_a_c_ref', 'tarifa_m_a_igv_ref', 'tarifa_m_a_total_ref', 'tarifa_m_a_c_pel', 'tarifa_m_a_igv_pel', 'tarifa_m_a_total_pel', 'carga_dificil_manejo_sobre', 'tarifa_m_t_c_pel', 'tarifa_m_t_igv_pel', 'tarifa_m_t_total_pel'], 'number'],
            [['id_tipo_carga', 'observacion'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tarifa' => 'Id Tarifa',
            'id_entidad' => 'Id Entidad',
            'id_tipo_carga' => 'Id Tipo Carga',
            'id_via' => 'Id Via',
            'observacion' => 'Observacion',
            'flg_estado' => 'Flg Estado',
            'tipo_tarifa' => 'Tipo Tarifa',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'peso_t_base_general' => 'Peso T Base General',
            'peso_t_base_ref' => 'Peso T Base Ref',
            'peso_t_base_pel' => 'Peso T Base Pel',
            'peso_a_base_general' => 'Peso A Base General',
            'peso_a_base_ref' => 'Peso A Base Ref',
            'peso_a_base_pel' => 'Peso A Base Pel',
            'tarifa_m_t_costo' => 'Tarifa M T Costo',
            'tarifa_m_t_igv' => 'Tarifa M T Igv',
            'tarifa_m_t_total' => 'Tarifa M T Total',
            'tarifa_m_t_costo_ref' => 'Tarifa M T Costo Ref',
            'tarifa_m_t_igv_ref' => 'Tarifa M T Igv Ref',
            'tarifa_m_t_total_ref' => 'Tarifa M T Total Ref',
            'tarifa_m_a_c' => 'Tarifa M A C',
            'tarifa_m_a_total' => 'Tarifa M A Total',
            'tarifa_m_a_igv' => 'Tarifa M A Igv',
            'tarifa_m_a_c_ref' => 'Tarifa M A C Ref',
            'tarifa_m_a_igv_ref' => 'Tarifa M A Igv Ref',
            'tarifa_m_a_total_ref' => 'Tarifa M A Total Ref',
            'tarifa_m_a_c_pel' => 'Tarifa M A C Pel',
            'tarifa_m_a_igv_pel' => 'Tarifa M A Igv Pel',
            'tarifa_m_a_total_pel' => 'Tarifa M A Total Pel',
            'carga_dificil_manejo_sobre' => 'Carga Dificil Manejo Sobre',
            'tarifa_m_t_c_pel' => 'Tarifa M T C Pel',
            'tarifa_m_t_igv_pel' => 'Tarifa M T Igv Pel',
            'tarifa_m_t_total_pel' => 'Tarifa M T Total Pel',
        ];
    }
}

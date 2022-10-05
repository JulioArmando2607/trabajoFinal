<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarifa_provincia_ent".
 *
 * @property int $id_tarifa_provincia_ent
 * @property int|null $id_tarifa_entidad
 * @property int|null $id_via
 * @property float|null $tarifa_provincia
 * @property float|null $tarifa_m_t_costo
 * @property float|null $tarifa_m_t_ref
 * @property float|null $tarifa_m_t_vol
 * @property float|null $tarifa_m_t_dm
 * @property float|null $tarifa_m_a_cg
 * @property float|null $tarifa_m_a_vr
 * @property float|null $tarifa_m_a_pd
 * @property float|null $tarifa_m_a_dm
 * @property float|null $carga_dificil_manejo_sobre
 * @property int $id_provincia
 * @property float|null $monto
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
 * @property float|null $tarifa_m_t_cg
 */
class TarifaProvinciaEnt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarifa_provincia_ent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tarifa_entidad', 'id_via', 'id_provincia', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['tarifa_provincia', 'tarifa_m_t_costo', 'tarifa_m_t_ref', 'tarifa_m_t_vol', 'tarifa_m_t_dm', 'tarifa_m_a_cg', 'tarifa_m_a_vr', 'tarifa_m_a_pd', 'tarifa_m_a_dm', 'carga_dificil_manejo_sobre', 'monto', 'tarifa_m_t_cg'], 'number'],
            [['id_provincia', 'flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tarifa_provincia_ent' => 'Id Tarifa Provincia Ent',
            'id_tarifa_entidad' => 'Id Tarifa Entidad',
            'id_via' => 'Id Via',
            'tarifa_provincia' => 'Tarifa Provincia',
            'tarifa_m_t_costo' => 'Tarifa M T Costo',
            'tarifa_m_t_ref' => 'Tarifa M T Ref',
            'tarifa_m_t_vol' => 'Tarifa M T Vol',
            'tarifa_m_t_dm' => 'Tarifa M T Dm',
            'tarifa_m_a_cg' => 'Tarifa M A Cg',
            'tarifa_m_a_vr' => 'Tarifa M A Vr',
            'tarifa_m_a_pd' => 'Tarifa M A Pd',
            'tarifa_m_a_dm' => 'Tarifa M A Dm',
            'carga_dificil_manejo_sobre' => 'Carga Dificil Manejo Sobre',
            'id_provincia' => 'Id Provincia',
            'monto' => 'Monto',
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
            'tarifa_m_t_cg' => 'Tarifa M T Cg',
        ];
    }
}

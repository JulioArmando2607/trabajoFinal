<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rendicion_cuentas".
 *
 * @property int $id_rendicion_cuentas
 * @property string|null $fecha
 * @property string|null $nr_operacion
 * @property int|null $id_abono_cuenta_de
 * @property int|null $rinde
 * @property float|null $importe_entregado
 * @property float|null $diferencia_depositar_reembolsar
 * @property int|null $flg_estado
 * @property int $id_usuario_reg
 * @property string|null $fecha_reg
 * @property string|null $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 */
class RendicionCuentas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rendicion_cuentas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_abono_cuenta_de', 'rinde', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['importe_entregado', 'diferencia_depositar_reembolsar'], 'number'],
            [['id_usuario_reg'], 'required'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['fecha', 'nr_operacion', 'ipmaq_del'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rendicion_cuentas' => 'Id Rendicion Cuentas',
            'fecha' => 'Fecha',
            'nr_operacion' => 'Nr Operacion',
            'id_abono_cuenta_de' => 'Id Abono Cuenta De',
            'rinde' => 'Rinde',
            'importe_entregado' => 'Importe Entregado',
            'diferencia_depositar_reembolsar' => 'Diferencia Depositar Reembolsar',
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
        ];
    }
}

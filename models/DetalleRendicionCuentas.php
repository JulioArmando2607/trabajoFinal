<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_rendicion_cuentas".
 *
 * @property int $id_detalle_rendicion_cuentas
 * @property int $id_rendicion_cuentas
 * @property string|null $fecha
 * @property string|null $proveedor
 * @property string|null $nm_documento
 * @property string|null $concepto
 * @property float|null $monto
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
class DetalleRendicionCuentas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_rendicion_cuentas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rendicion_cuentas', 'id_usuario_reg'], 'required'],
            [['id_rendicion_cuentas', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['monto'], 'number'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['fecha', 'proveedor', 'nm_documento', 'concepto', 'ipmaq_del'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_rendicion_cuentas' => 'Id Detalle Rendicion Cuentas',
            'id_rendicion_cuentas' => 'Id Rendicion Cuentas',
            'fecha' => 'Fecha',
            'proveedor' => 'Proveedor',
            'nm_documento' => 'Nm Documento',
            'concepto' => 'Concepto',
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
        ];
    }
}

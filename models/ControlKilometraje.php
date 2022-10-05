<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "control_kilometraje".
 *
 * @property int $id_control_kilometraje
 * @property int|null $id_vehiculo
 * @property string|null $hora_salida
 * @property string|null $hora_llegada
 * @property float|null $kilometraje_salida
 * @property float|null $kilometraje_llegada
 * @property float $kilometro_recorrido
 * @property int|null $lugar_destino
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
 */
class ControlKilometraje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'control_kilometraje';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_vehiculo', 'lugar_destino', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['kilometraje_salida', 'kilometraje_llegada', 'kilometro_recorrido'], 'number'],
            [['kilometro_recorrido', 'flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['hora_salida', 'hora_llegada', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_control_kilometraje' => 'Id Control Kilometraje',
            'id_vehiculo' => 'Id Vehiculo',
            'hora_salida' => 'Hora Salida',
            'hora_llegada' => 'Hora Llegada',
            'kilometraje_salida' => 'Kilometraje Salida',
            'kilometraje_llegada' => 'Kilometraje Llegada',
            'kilometro_recorrido' => 'Kilometro Recorrido',
            'lugar_destino' => 'Lugar Destino',
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

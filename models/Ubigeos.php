<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ubigeos".
 *
 * @property int $id_ubigeo
 * @property string|null $ubigeo_departamento
 * @property string|null $ubigeo_provincia
 * @property string|null $ubigeo_distrito
 * @property string|null $nombre_departamento
 * @property string|null $nombre_provincia
 * @property string|null $nombre_distrito
 * @property int|null $IdPais
 * @property bool|null $flg_estado
 * @property bool|null $flg_estado_
 * @property string|null $fecha_del
 * @property int|null $id_usuario_reg
 * @property string|null $fecha_reg
 * @property string|null $iqmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property int|null $id_usuario_del
 * @property string|null $ipmaq_del
 * @property string|null $ipmaq_act
 */
class Ubigeos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ubigeos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdPais', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['flg_estado', 'flg_estado_'], 'boolean'],
            [['fecha_del', 'fecha_reg', 'fecha_act'], 'safe'],
            [['ubigeo_departamento', 'ubigeo_provincia', 'ubigeo_distrito'], 'string', 'max' => 2],
            [['nombre_departamento', 'nombre_provincia', 'nombre_distrito'], 'string', 'max' => 50],
            [['iqmaq_reg', 'ipmaq_del', 'ipmaq_act'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ubigeo' => 'Id Ubigeo',
            'ubigeo_departamento' => 'Ubigeo Departamento',
            'ubigeo_provincia' => 'Ubigeo Provincia',
            'ubigeo_distrito' => 'Ubigeo Distrito',
            'nombre_departamento' => 'Nombre Departamento',
            'nombre_provincia' => 'Nombre Provincia',
            'nombre_distrito' => 'Nombre Distrito',
            'IdPais' => 'Id Pais',
            'flg_estado' => 'Flg Estado',
            'flg_estado_' => 'Flg Estado',
            'fecha_del' => 'Fecha Del',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'iqmaq_reg' => 'Iqmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'id_usuario_del' => 'Id Usuario Del',
            'ipmaq_del' => 'Ipmaq Del',
            'ipmaq_act' => 'Ipmaq Act',
        ];
    }
}

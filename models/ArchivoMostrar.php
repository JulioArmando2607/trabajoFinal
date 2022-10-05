<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "archivo_mostrar".
 *
 * @property int $id_archivo
 * @property string|null $nombre_archivo
 * @property string|null $ruta_archivo
 * @property string|null $ip_server
 * @property int $flg_estado
 * @property string|null $tipo_tabla
 * @property int|null $id_guia
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
class ArchivoMostrar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archivo_mostrar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['flg_estado', 'id_guia', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['nombre_archivo'], 'string', 'max' => 200],
            [['ruta_archivo', 'ip_server', 'tipo_tabla'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_archivo' => 'Id Archivo',
            'nombre_archivo' => 'Nombre Archivo',
            'ruta_archivo' => 'Ruta Archivo',
            'ip_server' => 'Ip Server',
            'flg_estado' => 'Flg Estado',
            'tipo_tabla' => 'Tipo Tabla',
            'id_guia' => 'Id Guia',
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

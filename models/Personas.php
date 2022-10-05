<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personas".
 *
 * @property int $id_persona
 * @property string $dni
 * @property string $nombres
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string|null $fecha_nacimiento
 * @property int|null $id_sexo
 * @property string|null $telefono
 * @property string|null $correo
 * @property int|null $id_entidad
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $flg_conductor
 */
class Personas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni', 'nombres', 'apellido_paterno', 'apellido_materno', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['fecha_nacimiento', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['id_sexo', 'id_entidad', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'flg_conductor'], 'integer'],
            [['dni'], 'string', 'max' => 8],
            [['nombres', 'apellido_paterno', 'apellido_materno', 'correo'], 'string', 'max' => 100],
            [['telefono', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_persona' => 'Id Persona',
            'dni' => 'Dni',
            'nombres' => 'Nombres',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'id_sexo' => 'Id Sexo',
            'telefono' => 'Telefono',
            'correo' => 'Correo',
            'id_entidad' => 'Id Entidad',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'flg_conductor' => 'Flg Conductor',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehiculos_".
 *
 * @property int $id_vehiculo
 * @property int $marca
 * @property string|null $version
 * @property string|null $modelo
 * @property string|null $matricula
 * @property string|null $denominacion_comercial
 * @property string|null $medidas_neumaticos
 * @property string|null $altura
 * @property string|null $anchura
 * @property string|null $longitud
 * @property string|null $distancia_entre_ejes
 * @property string|null $masa_maxima_autorizada
 * @property string|null $tipo_motor
 * @property string|null $numero_cilindros
 * @property string|null $cilindarada
 * @property string|null $potencia_expresada_en_cv
 * @property string|null $potencia_expresada_en_kw
 * @property string|null $numero_bastidor
 * @property string|null $numero_plazas
 * @property string|null $tara
 * @property string|null $descripcion
 * @property string|null $incripcion
 * @property string|null $config_vehicular
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
class Vehiculos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehiculos_';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marca', 'flg_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['marca', 'flg_estado', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['version', 'modelo', 'matricula', 'denominacion_comercial', 'medidas_neumaticos', 'altura', 'anchura', 'longitud', 'distancia_entre_ejes', 'masa_maxima_autorizada', 'tipo_motor', 'numero_cilindros', 'cilindarada', 'potencia_expresada_en_cv', 'potencia_expresada_en_kw', 'numero_bastidor', 'numero_plazas', 'tara', 'config_vehicular'], 'string', 'max' => 255],
            [['descripcion', 'incripcion'], 'string', 'max' => 200],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_vehiculo' => 'Id Vehiculo',
            'marca' => 'Marca',
            'version' => 'Version',
            'modelo' => 'Modelo',
            'matricula' => 'Matricula',
            'denominacion_comercial' => 'Denominacion Comercial',
            'medidas_neumaticos' => 'Medidas Neumaticos',
            'altura' => 'Altura',
            'anchura' => 'Anchura',
            'longitud' => 'Longitud',
            'distancia_entre_ejes' => 'Distancia Entre Ejes',
            'masa_maxima_autorizada' => 'Masa Maxima Autorizada',
            'tipo_motor' => 'Tipo Motor',
            'numero_cilindros' => 'Numero Cilindros',
            'cilindarada' => 'Cilindarada',
            'potencia_expresada_en_cv' => 'Potencia Expresada En Cv',
            'potencia_expresada_en_kw' => 'Potencia Expresada En Kw',
            'numero_bastidor' => 'Numero Bastidor',
            'numero_plazas' => 'Numero Plazas',
            'tara' => 'Tara',
            'descripcion' => 'Descripcion',
            'incripcion' => 'Incripcion',
            'config_vehicular' => 'Config Vehicular',
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

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_guia_remitente".
 *
 * @property int $id_detalle_guia
 * @property int $id_guia_remision
 * @property int|null $id_producto
 * @property int|null $cantidad
 * @property float|null $peso
 * @property float|null $volumen
 * @property float|null $alto
 * @property float|null $largo
 * @property float|null $ancho
 * @property string|null $descripcion
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
class DetalleGuiaRemitente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_guia_remitente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_guia_remision', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_guia_remision', 'id_producto', 'cantidad', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['peso', 'volumen', 'alto', 'largo', 'ancho'], 'number'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['descripcion'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_guia' => 'Id Detalle Guia',
            'id_guia_remision' => 'Id Guia Remision',
            'id_producto' => 'Id Producto',
            'cantidad' => 'Cantidad',
            'peso' => 'Peso',
            'volumen' => 'Volumen',
            'alto' => 'Alto',
            'largo' => 'Largo',
            'ancho' => 'Ancho',
            'descripcion' => 'Descripcion',
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

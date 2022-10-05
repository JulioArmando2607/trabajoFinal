<?php

namespace app\modules\rendicioncuentas\query;

use Yii;

class Consultas {

    public static function getPersonas() {
        $command = Yii::$app->db->createCommand("SELECT
                                        e.id_empleado,
                                        p.id_persona,
                                        concat( p.nombres, ' ', p.apellido_paterno, ' ', p.apellido_materno ) AS empleado 
                                    FROM
                                        empleados e
                                        INNER JOIN personas p ON e.id_persona = p.id_persona 
                                    WHERE   e.fecha_del IS NULL");
        $result = $command->queryAll();
        return $result;
    }
    public static function  getDetalleRemdicion(){

    }

}

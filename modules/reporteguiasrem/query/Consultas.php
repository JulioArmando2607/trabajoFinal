<?php

namespace app\modules\reporteguiasrem\query;

use Yii;

class Consultas {


    public static function getReporteGuias($fechainicio,$fechafin,$cliente,$estado) {
        $command = Yii::$app->db->createCommand('call RporteExcelGuias(:fechainicio,:fechafin,:cliente,:estado )');
        $command->bindValue(':fechainicio', $fechainicio);
        $command->bindValue(':fechafin', $fechafin);
        $command->bindValue(':cliente', $cliente);
        $command->bindValue(':estado', $estado);
        $result = $command->queryAll();
        return $result;
    }


}

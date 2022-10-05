<?php

namespace app\modules\reportetecnocom\query;

use Yii;

class Consultas {


    public static function getReporteExcelTecnocom($fechainicio,$fechafin,$estado, $via) {
        $command = Yii::$app->db->createCommand('call ReporteExcelTecnocom(:fechainicio,:fechafin,:estado, :via )');
        $command->bindValue(':fechainicio', $fechainicio);
        $command->bindValue(':fechafin', $fechafin);
        $command->bindValue(':estado', $estado);
        $command->bindValue(':via', $via);
        $result = $command->queryAll();
        return $result;
    }


}

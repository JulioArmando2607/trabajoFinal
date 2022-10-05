<?php

namespace app\modules\manifiesto\query;

use Yii;

class ConsultasManifiesto {


    public static function getImprimirExcel($id_remitente,$fecha,$id_vehiculo,$serie) {
        $command = Yii::$app->db->createCommand('call manifiesto(:fecha,:id_remitente,:id_vehiculo,:serie )');
        $command->bindValue(':fecha', $fecha);
        $command->bindValue(':id_remitente', $id_remitente);
        $command->bindValue(':id_vehiculo', $id_vehiculo);
        $command->bindValue(':serie', $serie);
        $result = $command->queryAll();
        return $result;
    }


}

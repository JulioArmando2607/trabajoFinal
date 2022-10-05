<?php

namespace app\modules\seguimiento\query;

use Yii;

class ConsultasG {
 
     
    public static function getGuiaCliente($id) {
        $command = Yii::$app->db->createCommand('call guiaClienteSeg(:idGuia)');
        $command->bindValue(':idGuia', $id);
        $result = $command->queryAll();
        return $result;
    }

    public static function getImprimirGuia($id) {
        $command = Yii::$app->db->createCommand('call imprimirGuiaTransportista(:idGuia)');
        $command->bindValue(':idGuia', $id);
        $result = $command->queryOne();
        return $result;
    }
     
      public static function getEnvioCorreoSegGuia($id) {
        $command = Yii::$app->db->createCommand('call EnvioCorreoSegGuia(:idGuiaRemision)');
        $command->bindValue(':idGuiaRemision', $id);
        $result = $command->queryall();
        return $result;
    }

    public static function getContarGuiasEs($id) {
        $command = Yii::$app->db->createCommand('call ContarGuiasAte(:numeroSolicitud)');
        $command->bindValue(':numeroSolicitud', $id);
        $result = $command->queryOne();
        return $result;
    }


}

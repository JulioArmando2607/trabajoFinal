<?php

namespace app\modules\seguimientocliente\query;

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

}

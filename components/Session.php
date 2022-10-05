<?php

namespace app\components;

use Yii;

class Session {

    public static function getDatosUsuario() {
        
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call datoUsuario(:idUsuario)');
            $command->bindValue(':idUsuario',  Yii::$app->user->getId());
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $result;
    }

}

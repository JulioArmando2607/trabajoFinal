<?php

namespace app\modules\manifiestoventa\query;

use Yii;

class ConsultasMVenta {
 //hola
     
    public static function getImprimirExcel($fecha,$serie) {
        $command = Yii::$app->db->createCommand('call manifiestov(:fecha,:serie)');
            $command->bindValue(':fecha', $fecha); 
            $command->bindValue(':serie', $serie);  
            
            $result = $command->queryAll();
        return $result;
    }

       public static function getCierre($fecha,$serie) {
        $command = Yii::$app->db->createCommand('call cierreCaja(:fecha,:serie)');
            $command->bindValue(':fecha', $fecha); 
            $command->bindValue(':serie', $serie);  
            
            $result = $command->queryAll();
        return $result;
    }

   

}

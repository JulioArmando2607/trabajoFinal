<?php

namespace app\modules\facturas\query;

use Yii;

class Consultas {

    public static function getImprimirFactura($id) {

        $command = Yii::$app->db->createCommand('call imprimirFactura(:idventafac)');
        $command->bindValue(':idventafac', $id);
        $result = $command->queryOne();
        return $result;
    }

    public static function getExportarFactura() {

        $command = Yii::$app->db->createCommand('call imprimirFactura()');
        $command->bindValue(':idventafac', $id);
        $result = $command->queryOne();
        return $result;
    }
   
    public static function getNotasCredito() {

        $command = Yii::$app->db->createCommand('call imprimirFactura()');
        $command->bindValue(':idventafac', $id);
        $result = $command->queryOne();
        return $result;
    }
    public static function getSerie() {
        $command = Yii::$app->db->createCommand("SELECT "
                . "CONCAT(tipo_serie,serie) as serie "
                . "from correlativos WHERE serie "
                . "and tipo_serie is not null and estado = 1");
        $result = $command->queryAll();
        return $result;
    }
    public static function getTipoComprobante() {
        $command = Yii::$app->db->createCommand("SELECT "
                . "CONCAT(tipo_serie,serie) as serie "
                . "from correlativos WHERE serie "
                . "and tipo_serie is not null and estado = 1");
        $result = $command->queryAll();
        return $result;
    }
    public static function getImprimirNotas($id) {

        $command = Yii::$app->db->createCommand('call imprimirNotas(:idnotascredito)');
        $command->bindValue(':idnotascredito', $id);
        $result = $command->queryOne();
        return $result;
    }

    public static function getConsultaUsuario($id) {

        $command = Yii::$app->db->createCommand("
        SELECT 
        usu.usuario, agc.nombre_agencia, 
        CONCAT(ubi.nombre_departamento,'-',ubi.nombre_provincia,'-', ubi.nombre_distrito)as origen from usuarios usu  
        inner join perfiles per on usu.id_perfil = per.id_perfil
        inner join agencia agc on usu.id_agencia=agc.id_agencia 
        inner join ubigeos ubi on agc.id_ubigeo=ubi.id_ubigeo 
        where usu.id_perfil = 7 and id_usuario = $id");
       // $command->bindValue(':id_guia', $id);
        $result = $command->queryOne();
        return $result;
    }

}

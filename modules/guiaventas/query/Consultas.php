<?php

namespace app\modules\guiaventas\query;

use Yii;

class Consultas {

    public static function getConductor() {
        $command = Yii::$app->db->createCommand("select 
                                                    e.id_empleado,
                                                    concat(p.nombres,' ',p.apellido_paterno,' ',p.apellido_materno) as empleado
                                                from empleados e
                                                inner join personas p on e.id_persona = p.id_persona
                                                where e.flg_conductor = 1 and e.fecha_del is null");
        $result = $command->queryAll();
        return $result;
    }

    public static function getVehiculo() {
        $command = Yii::$app->db->createCommand("select 
                                                    v.id_vehiculo,
                                                    concat(mv.nombre_marca,' - ',v.placa,'::',v.descripcion) as vehiculo	
                                                from vehiculos v 
                                                inner join marca_vehiculo mv on v.id_marca = mv.id_marca
                                                where v.fecha_del is null");
        $result = $command->queryAll();
        return $result;
    }

    public static function getDetalleGuia($id) {
        $command = Yii::$app->db->createCommand('call detalleGuiaV(:idGuia)');
        $command->bindValue(':idGuia', $id);
        $result = $command->queryAll();
        return $result;
    }

    public static function getGuiaCliente($id) {
        $command = Yii::$app->db->createCommand('call guiaCliente(:idGuia)');
        $command->bindValue(':idGuia', $id);
        $result = $command->queryAll();
        return $result;
    }

    public static function getImprimirGuiaV($id, $usuario) {
        $command = Yii::$app->db->createCommand('call imprimirGuiaVenta(:idGuia, :idUsuario)');
        $command->bindValue(':idGuia', $id);
        $command->bindValue(':idUsuario', $usuario);
        $result = $command->queryOne();
        return $result;
    }

    public static function getImprimirRotulado($id, $idUsuario) {
        $command = Yii::$app->db->createCommand('call imprimirGuiaVRotulado(:idGuia,:idUsuario)');
        $command->bindValue(':idGuia', $id);
        $command->bindValue(':idUsuario', $idUsuario);
        $result = $command->queryOne();
        return $result;
    }

    public static function getImprimirFactura($id) {

        $command = Yii::$app->db->createCommand('call imprimirFactura(:idventafac)');
        $command->bindValue(':idventafac', $id);
        $result = $command->queryOne();
        return $result;
    }

    public static function getConsultaFacturacion($id) {

        $command = Yii::$app->db->createCommand('call consultaFacturacion(:id_guia)');
        $command->bindValue(':id_guia', $id);
        $result = $command->queryOne();
        return $result;
    }
    public static function getImprimirExcel() {
        $command = Yii::$app->db->createCommand('call totalGuiasV()');
        $result = $command->queryAll();
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

        
    public static function getCantidadGv($numero_guia, $serie_guia) {
        $command = Yii::$app->db->createCommand("select 
        count(gr.numero_guia) as total
        FROM
        guia_venta gr 
        WHERE
        gr.fecha_del IS NULL and gr.numero_guia = $numero_guia and gr.serie = $serie_guia;");
        $result = $command->queryOne();
        return $result;
    }
  
}

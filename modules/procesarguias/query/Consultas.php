<?php

namespace app\modules\procesarguias\query;

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
        $command = Yii::$app->db->createCommand('call detalleGuia(:idGuia)');
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

    public static function getImprimirGuia($id) {
        $command = Yii::$app->db->createCommand('call imprimirGuiaTransportista(:idGuia)');
        $command->bindValue(':idGuia', $id);
        $result = $command->queryOne();
        return $result;
    }

    public static function getImprimirRotulado($id, $idUsuario) {


        $command = Yii::$app->db->createCommand('call imprimirGuiaRotulado(:idGuia,:idUsuario)');
        $command->bindValue(':idGuia', $id);
        
        $command->bindValue(':idUsuario', $idUsuario);
        $result = $command->queryOne();
        return $result;
    }

    public static function getImprimirExcel() {
        $command = Yii::$app->db->createCommand('call totalGuias()');

        $result = $command->queryAll();
        return $result;
    }

}

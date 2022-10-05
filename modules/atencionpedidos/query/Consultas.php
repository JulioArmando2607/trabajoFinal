<?php

namespace app\modules\atencionpedidos\query;

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

    public static function getPersona() {
        $command = Yii::$app->db->createCommand("select 
                                                    e.id_empleado,
                                                    concat(p.nombres,' ',p.apellido_paterno,' ',p.apellido_materno) as empleado
                                                from empleados e
                                                inner join personas p on e.id_persona = p.id_persona
                                                where e.fecha_del is null");
        $result = $command->queryAll();
        return $result;
    }

    public static function getAuxiliar() {
        $command = Yii::$app->db->createCommand("SELECT
	                                       e.id_empleado,
	                                       concat( p.nombres, ' ', p.apellido_paterno, ' ', p.apellido_materno ) AS empleado 
                                               FROM
	                                       empleados e
	                                       INNER JOIN personas p ON e.id_persona = p.id_persona 
	                                       INNER JOIN usuarios usu on p.id_persona = usu.id_persona
                                               WHERE
	                                       usu.id_perfil = 12
	                                       AND e.fecha_del IS NULL and usu.fecha_del is null");
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

    public static function getImprimirExcel() {
        $command = Yii::$app->db->createCommand('call totalGuias()');

        $result = $command->queryAll();
        return $result;
    }

      public static function getDatosAuxCVh($id)
    {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call datoscav(:idAtencionPedidos)');
            $command->bindValue(':idAtencionPedidos', $id);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $result;
    }
       public static function getEquipoSiemens($id) {
        $command = Yii::$app->db->createCommand('call ConsultaEquipoSiemens(:idPedido)');
        $command->bindValue(':idPedido', $id);
        $result = $command->queryall();
        return $result;
    }
}

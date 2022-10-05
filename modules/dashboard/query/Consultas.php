<?php

namespace app\modules\dashboard\query;

use Yii;

class Consultas {

    public static function getTotalPedidosPendiente() {
        $command = Yii::$app->db->createCommand(" 
                                                       select 
                                                COUNT(pe.nm_solicitud)  as cantidadpe
                                                from pedido_cliente pe 
                                                inner join guia_remision gr on trim(pe.nm_solicitud) = trim(gr.nm_solicitud)
                                                inner join estados esg on gr.id_estado = esg.id_estado
                                                inner JOIN estados esp on pe.id_estado = esp.id_estado
                                                inner join usuarios usug on gr.id_usuario_reg = usug.id_usuario
                                                inner join usuarios usugrac on gr.id_usuario_act = usugrac.id_usuario
                                                inner join usuarios usupe on pe.id_usuario_reg = usupe.id_usuario
                                                where pe.fecha_del is null and gr.fecha_del is null
                                                and gr.fecha_del is null 
                                                and usupe.usuario <>'pruebasiemens' 
                                                and esp.id_estado <> 28
                                                and pe.fecha_reg > '2021-07-17 00:00:00';");
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalPedidosAtendidos() {
        $command = Yii::$app->db->createCommand(" 
                                                       select 
                                                COUNT(pe.nm_solicitud)  as cantidadpe
                                                from pedido_cliente pe 
                                                inner join guia_remision gr on trim(pe.nm_solicitud) = trim(gr.nm_solicitud)
                                                inner join estados esg on gr.id_estado = esg.id_estado
                                                inner JOIN estados esp on pe.id_estado = esp.id_estado
                                                inner join usuarios usug on gr.id_usuario_reg = usug.id_usuario
                                                inner join usuarios usugrac on gr.id_usuario_act = usugrac.id_usuario
                                                inner join usuarios usupe on pe.id_usuario_reg = usupe.id_usuario
                                                where pe.fecha_del is null and gr.fecha_del is null
                                                and gr.fecha_del is null 
                                                and usupe.usuario <>'pruebasiemens' 
                                                and esp.id_estado = 28
                                                and pe.fecha_reg > '2021-07-17 00:00:00';");
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalGuiaEntregado() {
        $command = Yii::$app->db->createCommand("select COUNT(gr.numero_guia) AS cantidadpe from guia_remision gr  where gr.fecha_del is null and id_estado = 4;");
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalGuiaRecogido() {
        $command = Yii::$app->db->createCommand("select COUNT(gr.numero_guia) AS cantidadpe from guia_remision gr  where gr.fecha_del is null and id_estado = 20;");
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalVentas() {
        $command = Yii::$app->db->createCommand("call TotalVentasPegaso()");
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

    public static function getPedidos() {
        $command = Yii::$app->db->createCommand("select pe.nm_solicitud as solicitud_cliente, 
                                                    pe.id_cliente,
                                                    cl.razon_social,
                                                    pe.fecha_reg as fecha_reg_pedido,
                                                    CONCAT(gr.serie,'-',gr.numero_guia) as numeroGuia,
                                                    if(gr.numero_guia is null, 'sin guia','con guia') as pedido_scon_guia,
                                                    esg.nombre_estado as estado_guia,
                                                    esp.nombre_estado as estado_pedido,
                                                    usug.usuario as usuario_registro_guia,
                                                    usupe.usuario as usuario_registro_pedido,
                                                    usugrac.usuario as usuario_actualizo_guia
                                                    from pedido_cliente pe 
                                                    inner join guia_remision gr on trim(pe.nm_solicitud) = trim(gr.nm_solicitud)
                                                    inner join estados esg on gr.id_estado = esg.id_estado
                                                    inner JOIN estados esp on pe.id_estado = esp.id_estado
                                                    inner join usuarios usug on gr.id_usuario_reg = usug.id_usuario
                                                    inner join usuarios usugrac on gr.id_usuario_act = usugrac.id_usuario
                                                    inner join usuarios usupe on pe.id_usuario_reg = usupe.id_usuario
                                                    inner join entidades cl on pe.id_cliente = cl.id_entidad
                                                    where pe.fecha_del is null and gr.fecha_del is null
                                                    and gr.fecha_del is null 
                                                    and usupe.usuario <>'pruebasiemens' 
                                                    and pe.fecha_reg > '2021-10-17 00:00:00';");

        $result = $command->queryAll();
        return $result;
    }

      public static function getTotalesPorCliente() {
        $command = Yii::$app->db->createCommand("select                                                     
                                                    pe.id_cliente,
                                                    cl.razon_social,
                                                    COUNT(pe.id_pedido_cliente) as cantidad
                                                    from pedido_cliente pe 
                                                    inner join guia_remision gr on trim(pe.nm_solicitud) = trim(gr.nm_solicitud)
                                                    inner join estados esg on gr.id_estado = esg.id_estado
                                                    inner JOIN estados esp on pe.id_estado = esp.id_estado
                                                    inner join usuarios usug on gr.id_usuario_reg = usug.id_usuario
                                                    inner join usuarios usugrac on gr.id_usuario_act = usugrac.id_usuario
                                                    inner join usuarios usupe on pe.id_usuario_reg = usupe.id_usuario
                                                    inner join entidades cl on pe.id_cliente = cl.id_entidad
                                                    where pe.fecha_del is null and gr.fecha_del is null
                                                    and gr.fecha_del is null 
                                                    and usupe.usuario <>'pruebasiemens' 
                                                    and pe.fecha_reg > '2021-10-17 00:00:00'
                                                    GROUP BY cl.razon_social, pe.id_cliente,cl.razon_social");

        $result = $command->queryAll();
        return $result;
    }
    /* */
}

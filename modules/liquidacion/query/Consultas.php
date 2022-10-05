<?php

namespace app\modules\liquidacion\query;

use Yii;

class Consultas
{

    public static function getLiquidacion($id)
    {
        $command = Yii::$app->db->createCommand('call tarifas(:entidad)');
        $command->bindValue(':entidad', $id);
        $result = $command->queryAll();
        return $result;
    }


    public static function getLiquidacionExport()
    {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call ListaLiquidacion(:row,:length,:busca,@total)');

            $command->bindValue(':row', 0);
            $command->bindValue(':length', 10000);
            $command->bindValue(':busca', '');

            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $result;
    }

    public static function getExportLiquidado($idCliente, $fechal)
    {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call listadoliquidacion(:row,:length,:busca,:idCliente, :fechal, @total)');

            $command->bindValue(':row', 0);
            $command->bindValue(':length', 100);
            $command->bindValue(':busca', '');
            $command->bindValue(':idCliente', $idCliente);
            $command->bindValue(':fechal', $fechal . "-00");

            $result = $command->queryAll();


            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $result;
    }

    public static function getTotalesLiquidacion($entidad, $fechainicio, $fechafin)
    {
        $command = Yii::$app->db->createCommand('call totalesLiquidacion(:entidad,:fechainicio,:fechafin)');
        $command->bindValue(':entidad', $entidad);
        $command->bindValue(':fechainicio', $fechainicio);
        $command->bindValue(':fechafin', $fechafin);
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalesLiquiD($idEntidad)
    {
        $command = Yii::$app->db->createCommand('call totalListaLiquidacion(:idCliente)');
        $command->bindValue(':idCliente', $idEntidad);
        $result = $command->queryOne();
        return $result;
    }

    public static function getTotalesLiquidado($fechal, $idCliente)
    {
        $command = Yii::$app->db->createCommand('call totalLiquidado(:fechal, :idCliente)');
        $command->bindValue(':fechal', $fechal);
        $command->bindValue(':idCliente', $idCliente);
        $result = $command->queryOne();
        return $result;
    }

    public static function getCalculoTarifa($idLiquidacion, $pesoin)
    {
        $command = Yii::$app->db->createCommand('call calculoTarifa(:idLiquidacion, :pesoin)');
        $command->bindValue(':idLiquidacion', $idLiquidacion);
        $command->bindValue(':pesoin', $pesoin);
        $result = $command->queryOne();
        return $result;
    }
}

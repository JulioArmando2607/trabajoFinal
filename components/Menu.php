<?php

namespace app\components;

use Yii;

class Menu
{

    public static function getListaMenu()
    {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call menu(:idPerfil)');
            $command->bindValue(':idPerfil', Yii::$app->user->identity->id_perfil);
            $result = $command->queryAll();

            $final = [];

            foreach ($result as $r) {
                if ($r["flg_padre"] == 1 && $r["id_padre"] == 0) {
                    array_push($final, [
                        "nombre_opcion" => $r["nombre_opcion"],
                        "flg_padre" => 1,
                        "hijo" => self::getHijos($result, $r["id_opcion"])
                    ]);
                } else if ($r["flg_padre"] == 0 && $r["id_padre"] == 0) {
                    array_push($final, $r);
                }
            }

        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $final;
    }

    private static function getHijos($array, $id_padre)
    {
        $data = [];
        foreach ($array as $a) {
            if ($a["id_padre"] == $id_padre) {
                array_push($data, $a);
            }
        }
        return $data;
    }

    public static function getValidateMenu($array, $url)
    {
        $rpta = false;
        foreach ($array as $a) {
            if ($a["url"] == $url) {
                $rpta = true;
            }
        }
        return $rpta;
    }

}

<?php

namespace app\components;

use app\models\Correlativos;
use Yii;

class Utils
{

    const ACTIVO = 1;
    const INACTIVO = 0;
    const TIPO_ENTIDAD_CLIENTE = 1;
    const TIPO_ENTIDAD_PROVEEDOR = 2;
    const FLG_CONDUCTOR = 1;
    const PENDIENTE = 1;
    const ENTREGADO = 2;
    const CUSTODIA = 3;
    const RETORNO = 4;
    const TRAYECTO = 5;
    const RECOGIDO = 20;
    const PENDIENTE_GUIA = 24;
    const ASIGNADO = 22;
    const ANULADO = 29;
    const DB_STORAGE = '/archivos/';

    public static function getUrlStorage()
    {
        $url = "C:/xampp/htdocs/archivos/";
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $url = "/var/www/html/archivos/";
        }

        return $url;
    }

    public static function getUrl()
    {
        $url = "";
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $url = "http://147.182.244.87";
        }

        return $url;
    }

    public static function encodeUrlTripleDes($string)
    {
        return str_replace(" ", "+", $string);
    }

    public static function show($data, $detenerProcesos = false, $titulo = 'Datos')
    {
        echo "<code class='code'><b>{$titulo} :</b></code>";
        echo "<pre>";
        print_r($data);
        echo '</pre>';
        if ($detenerProcesos) {
            die();
        }
    }

    /**
     * Funcion que encripta un valor para ser usado como token.
     * @param string $valor valor a ser encriptado
     * @return string valor encriptado
     */
    public static function token($valor)
    {
        return sha1(TripleDes::Encrypt($valor));
    }

    public static function validarToken($token, $valor)
    {
        if ($token === self::token($valor)) {
            return true;
        }
        return false;
    }

    public static function getFechaActual()
    {
        return date('Y-m-d H:i:s');
    }

    public static function getFechaaActual()
    {
        return date('Y-m-d');
    }

    public static function getHoraActual()
    {
        return date('H:i:s');
    }

    public static function obtenerIP()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } elseif (isset($_SERVER["REMOTE_ADDR"])) {
            return $_SERVER["REMOTE_ADDR"];
        } else {
            return "000.000.000.000";
        }
    }

    public static function jsonEncode($param)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $param;
    }

    public static function getGenerarNumero($modulo)
    {
        $numero_numero = 0;
        $correlativo = Correlativos::find()->where(["modulo" => $modulo, "estado" => 1, "anio" => date("Y")])->one();
        if (empty($correlativo)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $cn = new Correlativos();
                $cn->modulo = $modulo;
                $cn->anio = date("Y");
                $cn->numero_correlativo = 1;
                $cn->estado = 1;

                if (!$cn->save()) {
                    show($cn->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }

                $transaction->commit();

                $numero_numero = $cn->numero_correlativo;
            } catch (Exception $ex) {
                show($ex, true);
                $transaction->rollback();
            }
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $correlativo->numero_correlativo = $correlativo->numero_correlativo + 1;

                if (!$correlativo->save()) {
                    show($correlativo->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }

                $transaction->commit();

                $numero_numero = $correlativo->numero_correlativo;
            } catch (Exception $ex) {
                show($ex, true);
                $transaction->rollback();
            }
        }

        return date("Y") . "-" . str_pad($numero_numero, 10, '0', STR_PAD_LEFT);
    }

    public static function getGenerarNumeroFB($modulo, $serie)
    {
        $numero_numero = 0;
        $correlativo = Correlativos::find()->where(["modulo" => $modulo, "estado" => 1, "serie" => $serie])->one();
        if (empty($correlativo)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $cn = new Correlativos();
                $cn->modulo = $modulo;
                $cn->anio = date("Y");
                $cn->serie = $serie;
                $cn->numero_correlativo = 1;
                $cn->estado = 1;

                if (!$cn->save()) {
                    show($cn->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }

                $transaction->commit();

                $numero_numero = $cn->numero_correlativo;
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $correlativo->numero_correlativo = $correlativo->numero_correlativo + 1;

                if (!$correlativo->save()) {
                    show($correlativo->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }

                $transaction->commit();

                $numero_numero = $correlativo->numero_correlativo;
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
        }

        return str_pad($numero_numero, 6, '0', STR_PAD_LEFT);
    }

    public static function getVentasFactura($id_guia_ventas, $serie, $correlativo, $id_tipo_comprobante, $tipo_comprobante, $forma_pago, $tipo_forma_pago, $total, $igv, $subtotal, $cliente, $numero_documento, $cantidad, $producto, $fecha)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $cn = new \app\models\VentasFactura();
            $cn->id_guia_ventas = $id_guia_ventas;
            $cn->serie = $serie;
            $cn->correlativo = $correlativo;
            $cn->id_tipo_comprobante = $id_tipo_comprobante;
            $cn->tipo_comprobante = $tipo_comprobante;
            $cn->forma_pago = $forma_pago;
            $cn->tipo_forma_pago = $tipo_forma_pago;
            $cn->total = $total;
            $cn->igv = $igv;
            $cn->subtotal = $subtotal;
            $cn->cliente = $cliente;
            $cn->numero_documento = $numero_documento;
            $cn->cantidad = $cantidad;
            $cn->producto = $producto;
            $cn->fecha = $fecha;
            $cn->id_usuario_reg = Yii::$app->user->getId();
            $cn->fecha_reg = Utils::getFechaActual();
            $cn->ipmaq_reg = Utils::obtenerIP();


            if (!$cn->save()) {
                Utils::show($cn->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }
            $guiaVenta = \app\models\GuiaVenta::findOne($id_guia_ventas);
            $guiaVenta->flg_factura = 1;
            $guiaVenta->id_usuario_act = Yii::$app->user->getId();
            $guiaVenta->fecha_act = Utils::getFechaActual();
            $guiaVenta->ipmaq_act = Utils::obtenerIP();

            if (!$guiaVenta->save()) {
                show($guiaVenta->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }
            $transaction->commit();
        } catch (Exception $ex) {
            Utils::show($ex, true);
            $transaction->rollback();
        }
        Utils::jsonEncode($cn->id_ventas_factura);
    }


    public static function getVentasFacturaUp($id, $estado)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $upfac = \app\models\VentasFactura::findOne($id);
            $upfac->estado = $estado;
            $upfac->id_usuario_act = Yii::$app->user->getId();
            $upfac->fecha_act = Utils::getFechaActual();
            $upfac->ipmaq_act = Utils::obtenerIP();

            if (!$upfac->save()) {
                show($upfac->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }

            $transaction->commit();
        } catch (Exception $ex) {
            Utils::show($ex, true);
            $transaction->rollback();
        }
        Utils::jsonEncode($upfac->id_ventas_factura);
    }


    public static function unidad($numero)
    {
        switch ($numero) {
            case 9:
            {
                $num = "nueve";
                break;
            }
            case 8:
            {
                $num = "ocho";
                break;
            }
            case 7:
            {
                $num = "siete";
                break;
            }
            case 6:
            {
                $num = "seis";
                break;
            }
            case 5:
            {
                $num = "cinco";
                break;
            }
            case 4:
            {
                $num = "cuatro";
                break;
            }
            case 3:
            {
                $num = "tres";
                break;
            }
            case 2:
            {
                $num = "dos";
                break;
            }
            case 1:
            {
                $num = "uno";
                break;
            }
        }
        return $num;
    }

    public static function decena($numero)
    {
        if ($numero >= 90 && $numero <= 99) {
            $num_letra = "noventa ";
            if ($numero > 90)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 90);
        } elseif ($numero >= 80 && $numero <= 89) {
            $num_letra = "ochenta ";
            if ($numero > 80)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 80);
        } elseif ($numero >= 70 && $numero <= 79) {
            $num_letra = "setenta ";
            if ($numero > 70)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 70);
        } elseif ($numero >= 60 && $numero <= 69) {
            $num_letra = "sesenta ";
            if ($numero > 60)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 60);
        } elseif ($numero >= 50 && $numero <= 59) {
            $num_letra = "cincuenta ";
            if ($numero > 50)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 50);
        } elseif ($numero >= 40 && $numero <= 49) {
            $num_letra = "cuarenta ";
            if ($numero > 40)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 40);
        } elseif ($numero >= 30 && $numero <= 39) {
            $num_letra = "treinta ";
            if ($numero > 30)
                $num_letra = $num_letra . "y " . Utils::unidad($numero - 30);
        } elseif ($numero >= 20 && $numero <= 29) {
            if ($numero == 20)
                $num_letra = "veinte ";
            else
                $num_letra = "veinti" . Utils::unidad($numero - 20);
        } elseif ($numero >= 10 && $numero <= 19) {
            switch ($numero) {
                case 10:
                {
                    $num_letra = "diez ";
                    break;
                }
                case 11:
                {
                    $num_letra = "once ";
                    break;
                }
                case 12:
                {
                    $num_letra = "doce ";
                    break;
                }
                case 13:
                {
                    $num_letra = "trece ";
                    break;
                }
                case 14:
                {
                    $num_letra = "catorce ";
                    break;
                }
                case 15:
                {
                    $num_letra = "quince ";
                    break;
                }
                case 16:
                {
                    $num_letra = "dieciseis ";
                    break;
                }
                case 17:
                {
                    $num_letra = "diecisiete ";
                    break;
                }
                case 18:
                {
                    $num_letra = "dieciocho ";
                    break;
                }
                case 19:
                {
                    $num_letra = "diecinueve ";
                    break;
                }
            }
        } else
            $num_letra = Utils::unidad($numero);
        return $num_letra;
    }

    public static function centena($numero)
    {
        if ($numero >= 100) {


            if ($numero >= 900 & $numero <= 999) {

                $num_letra = "novecientos ";

                if ($numero > 900)
                    $num_letra = $num_letra . Utils::decena($numero - 900);
            } elseif ($numero >= 800 && $numero <= 899) {

                $num_letra = "ochocientos ";

                if ($numero > 800)
                    $num_letra = $num_letra . Utils::decena($numero - 800);
            } elseif ($numero >= 700 && $numero <= 799) {

                $num_letra = "setecientos ";

                if ($numero > 700)
                    $num_letra = $num_letra . Utils::decena($numero - 700);
            } elseif ($numero >= 600 && $numero <= 699) {

                $num_letra = "seiscientos ";

                if ($numero > 600)
                    $num_letra = $num_letra . Utils::decena($numero - 600);
            } elseif ($numero >= 500 && $numero <= 599) {

                $num_letra = "quinientos ";

                if ($numero > 500)
                    $num_letra = $num_letra . Utils::decena($numero - 500);
            } elseif ($numero >= 400 && $numero <= 499) {

                $num_letra = "cuatrocientos ";

                if ($numero > 400)
                    $num_letra = $num_letra . Utils::decena($numero - 400);
            } elseif ($numero >= 300 && $numero <= 399) {

                $num_letra = "trescientos ";

                if ($numero > 300)
                    $num_letra = $num_letra . Utils::decena($numero - 300);
            } elseif ($numero >= 200 && $numero <= 299) {

                $num_letra = "doscientos ";

                if ($numero > 200)
                    $num_letra = $num_letra . Utils::decena($numero - 200);
            } elseif ($numero >= 100 && $numero <= 199) {

                if ($numero == 100)
                    $num_letra = "cien ";
                else
                    $num_letra = "ciento " . Utils::decena($numero - 100);
            }
        } else
            $num_letra = Utils::decena($numero);

        return $num_letra;
    }

    public function cien()
    {

        global $importe_parcial;

        $parcial = 0;
        $car = 0;

        while (substr($importe_parcial, 0, 1) == 0)
            $importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);

        if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
            $car = 1;

        elseif ($importe_parcial >= 10 && $importe_parcial <= 99.99)
            $car = 2;

        elseif ($importe_parcial >= 100 && $importe_parcial <= 999.99)
            $car = 3;

        $parcial = substr($importe_parcial, 0, $car);

        $importe_parcial = substr($importe_parcial, $car);

        $num_letra = Utils::centena($parcial);

        return $num_letra;
    }

    public static function cien_mil()
    {

        global $importe_parcial;

        $parcial = 0;
        $car = 0;

        while (substr($importe_parcial, 0, 1) == 0)
            $importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);

        if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
            $car = 1;

        elseif ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
            $car = 2;

        elseif ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
            $car = 3;

        $parcial = substr($importe_parcial, 0, $car);

        $importe_parcial = substr($importe_parcial, $car);

        if ($parcial > 0) {

            if ($parcial == 1)
                $num_letra = "mil ";
            else
                $num_letra = Utils::centena($parcial) . " mil ";
        }

        return $num_letra;
    }

    public static function millon()
    {

        global $importe_parcial;

        $parcial = 0;
        $car = 0;

        while (substr($importe_parcial, 0, 1) == 0)
            $importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);

        if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
            $car = 1;

        elseif ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
            $car = 2;

        elseif ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
            $car = 3;

        $parcial = substr($importe_parcial, 0, $car);

        $importe_parcial = substr($importe_parcial, $car);

        if ($parcial == 1)
            $num_letras = "un mill�n ";
        else
            $num_letras = Utils::centena($parcial) . " millones ";

        return $num_letras;
    }

    public static function convertirLetras($numero)
    {
        global $importe_parcial;

        $importe_parcial = $numero;

        $decimal = 0.0;
        $num_letras = "";

        if ($numero < 1000000000) {
            if ($numero >= 1000000 && $numero <= 999999999.99)
                $num_letras = Utils::millon() . Utils::cien_mil() . Utils::cien();

            elseif ($numero == 1000)
                $num_letras = 'mil';

            elseif ($numero == 2000)
                $num_letras = 'dos mil';

            elseif ($numero == 3000)
                $num_letras = 'tres mil';

            elseif ($numero == 4000)
                $num_letras = 'cuatro mil';

            elseif ($numero == 5000)
                $num_letras = 'cinco mil';

            elseif ($numero == 6000)
                $num_letras = 'seis mil';

            elseif ($numero == 7000)
                $num_letras = 'siete mil';

            elseif ($numero == 8000)
                $num_letras = 'ocho mil';

            elseif ($numero == 9000)
                $num_letras = 'nueve mil';

            elseif ($numero == 10000)
                $num_letras = 'diez mil';

            elseif ($numero == 11000)
                $num_letras = 'once mil';

            elseif ($numero == 12000)
                $num_letras = 'doce mil';

            elseif ($numero == 13000)
                $num_letras = 'trece mil';

            elseif ($numero == 14000)
                $num_letras = 'catorce mil';

            elseif ($numero == 15000)
                $num_letras = 'quince mil';

            elseif ($numero == 16000)
                $num_letras = 'dieciseis mil';

            elseif ($numero == 17000)
                $num_letras = 'diecisiete mil';

            elseif ($numero == 18000)
                $num_letras = 'dieciocho mil';

            elseif ($numero == 19000)
                $num_letras = 'diecinueve mil';

            elseif ($numero == 20000)
                $num_letras = 'veinte mil';

            elseif ($numero == 21000)
                $num_letras = 'veintiuno mil';

            elseif ($numero == 22000)
                $num_letras = 'veintidos mil';

            elseif ($numero == 23000)
                $num_letras = 'veintitres mil';

            elseif ($numero == 24000)
                $num_letras = 'veinticuatro mil';

            elseif ($numero == 25000)
                $num_letras = 'veinticinco mil';

            elseif ($numero == 26000)
                $num_letras = 'veintiseis mil';

            elseif ($numero == 27000)
                $num_letras = 'veintisiete mil';

            elseif ($numero == 28000)
                $num_letras = 'veintiocho mil';

            elseif ($numero == 29000)
                $num_letras = 'veintinueve mil';

            elseif ($numero == 30000)
                $num_letras = 'treinta mil';

            elseif ($numero > 1000 && $numero <= 999999.99)
                $num_letras = Utils::cien_mil() . Utils::cien();


            elseif ($numero >= 1 && $numero <= 999.99)
                $num_letras = Utils::cien();

            $longitud = strlen($numero);

            for ($i = 0; $i <= $longitud - 1; $i++) {

                $punto = substr($numero, $i, 1);

                if ($punto == ".")
                    $decimal = substr($numero, $i + 1);
            }

            if (strlen($decimal) == 1) {

                $decimal = $decimal . "0";
            } elseif (strlen($decimal) == 0) {

                $decimal = "00";
            }
        }

        return $num_letras . " y $decimal/100 soles";
    }

    public static function getConsultaDocumento($tipodoc, $documento)
    {
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/' . $tipodoc . '?numero=' . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        ///  curl_close($curl);
        $response = json_decode($response);

        $cod = "";
        foreach ($response as $k => $r) {
            $cod = $response->nombre;
            //  $mensaje = $response->mensaje;
        }

        return $cod;
        // var_dump($cod);

    }

    public static function getMes($mes)
    {
        switch ($mes) {
            case '01';
                return 'Enero';
            case '02';
                return 'Febrero';
            case '03';
                return 'Marzo';
            case '04';
                return 'Abril';
            case '05';
                return 'Mayo';
            case '06';
                return 'Junio';
            case '07';
                return 'Julio';
            case '08';
                return 'Agosto';
            case '09';
                return 'Setiembre';
            case '10';
                return 'Octubre';
            case '11';
                return 'Noviembre';
            case '12';
                return 'Diciembre';


        }

    }


}

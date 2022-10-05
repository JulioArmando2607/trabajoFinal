<?php

namespace app\controllers;

use app\components\Utils;
use app\modules\atenderasignacion\query\Consultas;
use yii\web\Controller;
use Yii;

class ApiController extends Controller
{

    public function actionPass($id)
    {
        return Yii::$app->security->generatePasswordHash($id);
    }

    public function actionMail()
    {
        $numero_solicitud = "2021-0000000021";
        $numero_guia = "";
        $correo_cliente = null;
        $nombre_cliente = "Cliente";
        $origen = "";
        $destino = "";
        $pedido = "";


        $final = false;
//        if (Yii::$app->request->post()) {


//            $post = Yii::$app->request->post();

        $datos_correo = Consultas::getDatosCorreo($numero_solicitud);
        $tabla = "";

        foreach ($datos_correo as $r) {
            $correo_cliente = $r["correo"];
            $nombre_cliente = $r["cliente"];
            $origen = $r["origen"];
            $destino = $r["destino"];
            $pedido = $numero_solicitud;
//            $numero_guia = $r["numero_guia"];

            $lista = "";
            $result = Consultas::getListaGuia($r["numero_guia"]);
            foreach ($result as $s) {
                $lista .= '<tr>
                            <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                        Guia Cliente
                            </td>
                            <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                     ' . $s["guia_cliente"] . '
                            </td>
                       </tr>';
            }

            $tabla .= '  <tr>
                        <td align="left" style="padding-top: 20px;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                        Guia Pegaso Service Express
                                    </td>
                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                       ' . $r["numero_guia"] . '
                                    </td>
                                </tr>
                                 ' . $lista . '
                                 <tr>
                                 
                                  <tr>
                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                    <b>  Total de Bultos </b>
                                    </td>
                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                   <b>   ' . $r["peso"] . '</b>
                                    </td>
                                    
                                </tr>
                           
                        
                            
                       </tr>
                             
                            </table>
                           
                        </td>
                    </tr>
                     <td align="center" valign="top" style="font-size:0;"> 
                            <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                            <p style="font-weight: 800;">Origen</p>
                                            <p>' . $origen . '</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                            <p style="font-weight: 800;">Destino</p>
                                            <p>' . $destino . '</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>';


        }


        $mensaje = '<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
img { -ms-interpolation-mode: bicubic; }
img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
table { border-collapse: collapse !important; }
body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
a[x-apple-data-detectors] {
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}
@media screen and (max-width: 480px) {
    .mobile-hide {
        display: none !important;
    }
    .mobile-center {
        text-align: center !important;
    }
}
div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus dolor aliquid omnis consequatur est deserunt, odio neque blanditiis aspernatur, mollitia ipsa distinctio, culpa fuga obcaecati!
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
            <tr>
                <td align="center" valign="top" style="font-size:0; padding: 20px;" bgcolor="#0836D6">
                <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                        <tr>
                            <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                <img src="https://pegasoserviceexpress.com/logo_pegaso.png" width="250" style="display: block; border: 0px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bolder; line-height: 24px; padding-top: 25px;">                                  
                            <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                <img data-imagetype="External" src="https://s3.amazonaws.com/linio-live-transactional/REVAMP/general/icons/icon_tick_green.png" alt="Icono" width="20" height="20">
                                 ¡Tu pedido ha sido confirmado!
                            </h2>
                            <br>
                            <p>PEDIDO : ' . $pedido . '</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                Hola <b>' . $nombre_cliente . ',</b>
                                Te informamos que hemos generado las siguientes guias para su pedido.
                            </p>
                        </td>
                    </tr>
                    <tr>
                    ' . $tabla . '
                    
                    </tr>
                    <tr>
                      
                    </tr>
                </table>
                </td>
            </tr>
             <tr>
                <td align="center" height="100%" valign="top" width="100%" style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                    <tr>
                       
                    </tr>
                </table>
                </td>
            </tr>
            <tr>
                <td align="center" style=" padding: 20px; background-color: #06099b;" bgcolor="#1b9ba3">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" style="padding: 25px 0 15px 0;">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7">
                                      <a href="https://pegasoserviceexpress.com/ClientePegaso/web/" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #66b3b7; padding: 15px 30px; border: 1px solid #66b3b7; display: block;">Seguimiento de carga</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#ffffff" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                    <tr>
                        <td bgcolor="#ffffff" align="center" style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                            <p style="margin: 0;">Este correo electrónico fue creado y probado con PEGASO SERVICE EXPRESS. <a href="https://pegasoserviceexpress.com/" style="color: #5db3ec;">Quienes Somos PEGASO SERVICE EXPRESS</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

        try {
            Yii::$app->mailer->compose()
                ->setFrom('seguimiento@pegasoserviceexpress.com')
                ->setTo(['armandojulio82@gmail.com'])
                ->setSubject('Pedido N°' . $numero_solicitud)
                ->setHtmlBody($mensaje)
                //->attach($path)
                ->send();
            $final = true;
        } catch (Exception $ex) {
            Utils::show($ex, true);
            $final = false;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $final;
//        } else {
//            throw new HttpException(404, 'The requested Item could not be found.');
//        }

    }
}

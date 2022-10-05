<?php

namespace app\modules\conductores\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Empleados;
use app\models\Personas;

/**
 * Default controller for the `conductores` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;

    //Hola, soy Franklin y yo Dayron
// hola soy paolo 
    // soy marco xd

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGetModal() {
         $personas =  Personas::find()->where(["fecha_del" => null, "flg_conductor" => 0])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "personas"=>$personas
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $conductores = new Empleados();
               
                $conductores->id_persona = $post['personas'];
                $conductores->flg_conductor= $post['conductor'];                 
                $conductores->licencia= $post['licencia'];                 
                
                $conductores->id_usuario_reg = Yii::$app->user->getId();
                $conductores->fecha_reg = Utils::getFechaActual();
                $conductores->ipmaq_reg = Utils::obtenerIP();

                if (!$conductores->save()) {
                    Utils::show($conductores->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }
                $personas =  Personas::findOne($post['personas']);
                $personas->flg_conductor = $post['conductor'];       
           
                $personas->id_usuario_act = Yii::$app->user->getId();
                $personas->fecha_act = Utils::getFechaActual();
                $personas->ipmaq_act = Utils::obtenerIP();
                
                
                if (!$personas->update()) {
                    Utils::show($personas->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($conductores->id_empleado);
  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $conductores->id_empleado;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Empleados::findOne($id);
      //  $tipo_estado = TipoEstado::find()->where(["fecha_del" => null])->all();
        //$personas = \app\models\Personas::find()->where(["fecha_del" => null] , "id_entidad" => $data->id_entidad])->all();
        $personas =  Personas::find()->where(["fecha_del" => null])->all();
      //  $conductores = Empleados::find()->where(["fecha_del" => null, "flg_conductor" => 0])->one();
        $plantilla = Yii::$app->controller->renderPartial("editar", [
                  "personas"=>$personas,
            "conductores" => $data,
      
           // "conductores"=>$conductores
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $conductores = Empleados::findOne($post['id_empleados']);
                $conductores->dni = $post['dni'];
                $conductores->nombres = $post['nombres'];
                $conductores->apellido_paterno = $post['apellido_paterno'];
                $conductores->apellido_materno = $post['apellido_materno'];
                $conductores->sexo = $post['sexo'];
                $conductores->id_usuario_act = Yii::$app->user->getId();
                $conductores->fecha_act = Utils::getFechaActual();
                $conductores->ipmaq_act = Utils::obtenerIP();

                if (!$conductores->update()) {
                    Utils::show($conductores->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($conductores->id_empleados);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $conductores->id_empleado;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $conductores = Empleados::findOne($post['id_empleado']);
                $conductores->id_usuario_del = Yii::$app->user->getId();
                $conductores->fecha_del = Utils::getFechaActual();
                $conductores->ipmaq_del = Utils::obtenerIP();

                if (!$conductores->save()) {
                    Utils::show($conductores->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro conductores");
                }

                $personas = \app\models\Personas::findOne($post['id_persona']);
                $personas->flg_conductor = $post['conductor'];    
           
                $personas->id_usuario_act = Yii::$app->user->getId();
                $personas->fecha_act = Utils::getFechaActual();
                $personas->ipmaq_act = Utils::obtenerIP();
                
                
                if (!$personas->update()) {
                    Utils::show($personas->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }
                
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
           // echo json_encode($conductores->id_empleado);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $conductores->id_empleado;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoConsuctores(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "licencia" => $row['licencia'],
                "nombres" => $row['nombres'],
                "apellido_paterno" => $row['apellido_paterno'],
                "apellido_materno" => $row['apellido_materno'],
                
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_empleado"] .','.$row["id_persona"]. ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_empleado"] .','.$row["id_persona"]. ')"><i class="flaticon-delete"></i>Eliminar</button>',
            ];
        }

        $totalData = isset($result[0]['total']) ? $result[0]['total'] : 0;

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $totalData
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

}

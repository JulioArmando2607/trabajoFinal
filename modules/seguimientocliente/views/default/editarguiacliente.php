<?php

use app\modules\seguimiento\bundles\SeguimientoAsset;

$bundle = SeguimientoAsset::register($this);
?>

<form id="form-editargcliente">
    <input id="id_guia_remision_cliente" class="form-control form-control-sm" disabled name="id_guia_remision_cliente" type="hidden" value="<?= $guiaRC->id_guia_remision_cliente ?>" />

    <input id="id_archivo" class="form-control form-control-sm" disabled name="id_archivo" type="hidden" value="<?= $guiaRC->id_archivo ?>" />

    <div class="row">

        <div class="col-md-12">
            <div class="form-row">
                <div class="form-group input-group-sm col-md-2">
                    <label class="font-small">Nro.GR</label>

                    <input class="form-control" disabled="" value="<?= $guiaRC->gr ?>" name="gr" type="text">

                </div>
                <div class="form-group input-group-sm col-md-2"><label class="font-small">Nro.Fac</label><input class="form-control" disabled="" name="NumFTCliente" type="text" value="<?= $guiaRC->ft ?>"></div>

                <div class="form-group input-group-sm col-md-2"><label class="font-small">OC</label><input class="form-control" disabled="" name="OCCliente" type="text" value="<?= $guiaRC->oc ?>"></div>
                <div class="form-group input-group-sm col-md-2"><label class="font-small">Tipo</label><input class="form-control" disabled="" name="TipoCargaCliente" type="text" value="<?= $tipo_carga->nombre ?>"></div>
                <div class="form-group input-group-sm col-md-3"><label class="font-small">Descripción</label><input class="form-control" disabled="" name="DescripCliente" type="text" value="<?= $guiaRC->descripcion ?>"></div>
            </div>
            <div class="form-group">
                <div class="row"><label class="col-sm-2 col-form-label">Estado Mercadería</label>
                    <div class="col-sm-3">

                        <select class="form-control" id="estado_mercaderia" name="estado_mercaderia" style="width: 100%;">


                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($estados as $v) : ?>
                                <option value="<?= $v->id_estado ?>" <?= $guiaRC->id_estado_mercaderia == $v->id_estado ? 'selected' : '' ?>>
                                    <?= $v->nombre_estado ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div><label class="col-sm-2 col-form-label">Fec/Hora Entrega</label>
                    <div class="col-sm-3"><input class="form-control " name="fecha_hora_entrega" id="fecha_hora_entrega" type="date" value="<?= $guiaRC->fecha_hora_entrega ?>"></div>
                    <div class="col-sm-2"><input class="form-control  " name="hora_entrega" id="hora_entrega" type="time" value="<?= $guiaRC->hora_entrega ?>"></div>
                </div>
                <div class="row"><label class="col-sm-2 col-form-label">Estado Cargo</label>
                    <div class="col-sm-3"><select class="custom-select form-control ng-valid ng-touched ng-dirty" id="estado_cargo" name="estado_cargo">
                            <!---->
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($estados as $v) : ?>
                                <option value="<?= $v->id_estado ?>" <?= $guiaRC->id_estado_cargo == $v->id_estado ? 'selected' : '' ?>>
                                    <?= $v->nombre_estado ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div><label class="col-sm-2 col-form-label">Fec Cargo</label>
                    <div class="col-sm-3"><input class="form-control ng-valid ng-dirty ng-touched" name="fecha_cargo" id="fecha_cargo" type="date" value="<?= empty($guiaRC->fecha_cargo) ? date("Y-m-d") : $guiaRC->fecha_cargo ?>"></div>
                </div>

                <br>
                <div class="row"><label class="col-sm-2 col-form-label">Recibido por</label>
                    <div class="col-sm-10"><input class="form-control ng-valid ng-touched ng-dirty" name="recibido_por" type="text" id="recibido_por" value="<?= $guiaRC->recibido_por ?>"></div>
                </div>
                <div class="row"><label class="col-sm-2 col-form-label">Entregado por</label>
                    <div class="col-sm-10"><input class="form-control ng-valid ng-touched ng-dirty" name="entregado_por" type="text" id="entregado_por" value="<?= $guiaRC->entregado_por ?>"></div>
                </div>
                <div class="row"><label class="col-sm-2 col-form-label">Observación</label>
                    <div class="col-sm-10"><input class="form-control ng-valid ng-touched ng-dirty" name="obsevacion" type="text" id="obsevacion" value="<?= $guiaRC->obsevacion ?>"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row"><label class="col-sm-2 col-form-label">Adjuntar imagen</label>
        <div class="col-sm-10">
            <div class="card" style="width: 10rem;">

                <div class="form-group">

                    <input disabled value="<?= $seguimientorc["id_archivo"] ?>">
                    <img class="card-img-top" src="<?= $seguimientorc["nombre_ruta"] ?>">
                    <input type="button" id="loadFileXml" value="Cargar Imagen" onclick="document.getElementById('image').click();" />

                    <input type="file" style="display:none;" class="form-control-file" name="<?= $guiaRC->id_archivo ?>" id="image" data-buttonText="Your label here.">
                </div>

            </div>



        </div>
    </div>
    <!---->
    <div class="row">
        <div class="col-12">
            <div class="form-row mt-50 mb-50">
                <div class="col-md-2 offset-md-4">


<!--<div class="form-check"><input class="form-check-input ng-valid" id="defaultCheck1" name="boxValue" type="checkbox"><label class="form-check-label" for="defaultCheck1"> Aplicar a todos </label></div>-->
                </div>


            </div>

        </div>
    </div>
    <div class="card-footer text-right">
        <button class="btn btn-primary mr-2" id="btn-guardarc">Actualizar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
    </div>
</form>
<?php

use app\modules\seguimientoventa\bundles\SeguimientoVAsset;

$bundle = SeguimientoVAsset::register($this);
?>

<form id="frm-seguimiento-gv">


    <div class="card card-custom gutter-b example example-compact">
        <input id="id_guia_venta" class="form-control form-control-sm" disabled name="id_guia_venta" type="hidden" value="<?= $guia_remision->id_guia_venta ?>" />
         <input id="id_archivo" class="form-control form-control-sm" disabled name="id_archivo" type="hidden" value="<?= $seguimiento["id_archivo"] ?>" />
        <!--begin::Form-->


        <div class="card-body">

            <div class="row">
                <div class="col-md-8 offset-md-1 box-manage">
                    <form name="templateForm" novalidate="" class="ng-untouched ng-valid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="form-group input-group-sm col-md-3">
                                        <label class="font-small">Documento</label>
                                        <input type="text" class="form-control form-control-sm" id="numero_guia" disabled name="numero_guia" value="<?= $seguimiento["numero_guia"] ?>" />
                                    </div>
                                    <div class="form-group input-group-sm col-md-2">
                                        <label class="font-small">Fecha</label>
                                        <input class="form-control ng-untouched" disabled="" name="fecha" type="text" value="<?= $seguimiento["fecha"] ?>">
                                    </div>
                                    <div class="form-group input-group-sm col-md-2">
                                        <label class="font-small">Traslado</label>
                                        <input class="form-control ng-untouched" disabled="" name="fecha_traslado" type="text" value="<?= $seguimiento["fecha_traslado"] ?>">
                                    </div>
                                    <div class="form-group input-group-sm col-md-2">
                                        <label class="font-small">Vía</label>
                                        <input class="form-control ng-untouched" disabled="" name="via" type="text" value="<?= $seguimiento["via"] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row"><label class="col-sm-2 col-form-label">Remitente</label>
                                        <div class="col-sm-10">

                                            <input id="remitente" class="form-control form-control-sm" disabled name="remitente" type="text" value="<?= $seguimiento["remitente"] ?>" />
                                        </div>
                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">Dir.Partida</label>
                                        <div class="col-sm-10">
                                            <input id="direccion_partida" class="form-control form-control-sm" disabled name="direccion_partida" type="text" value="<?= $seguimiento["direccion_partida"] ?>" />
                                        </div>
                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">Destinatario</label>
                                        <div class="col-sm-10">

                                            <input id="destinatario" class="form-control form-control-sm" disabled name="destinatario" type="text" value="<?= $seguimiento["destinatario"] ?>" />

                                        </div>
                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">Dir.Llegada</label>
                                        <div class="col-sm-10">
                                            <input id="direccion_llegada" class="form-control form-control-sm" disabled name="direccion_llegada" type="text" value="<?= $seguimiento["direccion_llegada"] ?>" />
                                        </div>
                                    </div>


                                    <div class="row">

                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-5"> <select id="estado" class="form-control form-control-sm" name="estado">
                                                <?php foreach ($estados as $v) : ?>
                                                    <option value="<?= $v->id_estado ?>" <?= $seguimiento["id_estado"] == $v->id_estado ? 'selected' : '' ?>>
                                                        <?= $v->nombre_estado ?>
                                                    </option>
                                                <?php endforeach; ?>

                                            </select></div>
                                    </div>
                                    
                                     <div class="row"><label class="col-sm-2 col-form-label">FECHA ENTREGA</label>
                                        <div class="col-sm-5">
                                             <input type="date" class="form-control form-control-sm" id="fecha_entrega" name="fecha_entrega" min="2013-01-01" max="2050-12-31" value="<?= $seguimiento["fecha_entrega"] ?>">
                                        </div>
                                    </div>

                                    <div class="row"><label class="col-sm-2 col-form-label">Estado Venta</label>
                                        <div class="col-sm-5"> <select id="id_estado_venta" class="form-control form-control-sm" name="id_estado_venta">
                                                <?php foreach ($estados_venta as $v) : ?>
                                                    <option value="<?= $v->id_estado_venta ?>" <?= $seguimiento["id_estado_venta"] == $v->id_estado_venta ? 'selected' : '' ?>>
                                                        <?= $v->nombre_estado ?>
                                                    </option>
                                                <?php endforeach; ?>

                                            </select></div>
                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">FACT/BOLETA</label>
                                        <div class="col-sm-10">
                                               <input id="factura_boleta" class="form-control form-control-sm" name="factura_boleta" type="text" value="<?= $seguimiento["factura_boleta"];?>"/>
                                        </div>
                                    </div>
                                    <div class="row"><label class="col-sm-2 col-form-label">Comentario</label>
                                        <div class="col-sm-10">
                                            <textarea style="text-align: start;" class="form-control ng-untouched ng-valid" cols="30" name="comentario" id="comentario" rows="3"><?php echo $seguimiento["comentario"]; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row"><label class="col-sm-2 col-form-label">Adjuntar imagen</label>
                                        <div class="col-sm-10">
                                            <div class="card" style="width: 25rem;">

                                                <div class="form-group">

                                                    <input disabled value="<?= $seguimiento["id_archivo"] ?>">
 
                                                    <img class="card-img-top" src="<?= $seguimiento["nombre_ruta"] ?>">
                                                    <input type="button" id="loadFileXml" value="Cargar Imagen" onclick="document.getElementById('image').click();" />

                                                    <input type="file" style="display:none;" class="form-control-file"  id="image" data-buttonText="Your label here.">
                                                </div>

                                            </div>



                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </form>
                </div>

            </div>


        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary mr-2" id="btn-guardar-gvs">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("seguimientoventa"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
<!--begin::Card-->

<!--end::Card-->
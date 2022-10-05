<?php

use app\modules\atenderasignacion\bundles\AtenderAsignacionAsset;

$bundle = AtenderAsignacionAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-guia-remision">
        <input type="hidden" id="solicitud" name="solicitud" value=" <?= $consultaatepedidos["nm_solicitud"] ?>"/>
        <input type="hidden" id="id_cliente" name="id_cliente" value=" <?= $consultaatepedidos["id_cliente"] ?>"/>
        <input  id="id_pedido_cliente" type="hidden" name="id_pedido_cliente" value=" <?= $consultaatepedidos["id_pedido_cliente"] ?>"/>
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos guía de remisión</div>
            </div>
            <div class="row">
                <!--<div class="col-md-2">
                    <div class="form-group">
                        <label>Serie</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="serie" name="serie" value="0001"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="numero" name="numero"/>
                    </div>        
                </div>-->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha"
                               value="<?= date("Y-m-d") ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Traslado</label>
                        <input type="date" class="form-control form-control-sm" id="traslado" name="traslado"
                               value="<?= date("Y-m-d") ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Via</label>
                        <select class="form-control form-control-sm" id="via" name="via">
                            <?php foreach ($via as $v): ?>
                                <option value="<?= $v->id_via ?>"><?= $v->nombre_via ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Via Carga</label>
                        <select class="form-control form-control-sm" id="via_tipo" name="via_tipo">
                            <?php foreach ($via_tipo as $v): ?>
                                <option value="<?= $v->id_tipo_via_carga ?>"><?= $v->tipo_via_carga ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label>Agente</label>
                        <select class="form-control select2" id="agente" name="agente">
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-agente" onclick="funcionAgregarAgente()">
                            <i class="flaticon-add-circular-button"></i>
                        </button>
                    </div>
                </div>--->

            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos Origen</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remitente</label>
                        <select disabled="" class="form-control select2" id="remitente" name="remitente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $consultaatepedidos["id_remitente"] == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <input type="hidden" id="id_direccion_partida" value="<?= $consultaatepedidos["id_direccion_partida"] ?>">
                        <label>Dirección de partida</label>
                        <select disabled="" class="form-control select2" id="direccion_partida"
                                name="direccion_partida"></select>

                    </div>
                </div>
            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos destino</div>
            </div>
            <div class="row">
                <div class="col-md-6">


                        <div class="form-group">
                            <label>Destsnatario</label>
                            <select  class="form-control select2" id="destinatarioa" name="destinatarioa">
                                <option   selected>Seleccione</option>
                                <?php foreach ($rem_des_client as $d): ?>
                                    <option value="<?= $d->id_entidad ?>" <?= $consultaatepedidos["id_destinatario"] == $d->id_entidad ? 'selected' : '' ?>>
                                        <?= $d->razon_social .'-'.  $d->numero_documento?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">

                        <input  hidden id="id_direccion_destino" value="<?= $consultaatepedidos["id_direccion_destino"] ?>">
                        <label>Dirección de Destino</label>
                        <select class="form-control select2" id="direccion_destino"
                                name="direccion_destino"></select>

                    </div>
                </div>

            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos transportista</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Conductor</label>
                        <select disabled="" class="form-control select2" id="conductor" name="conductor">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($conductor as $c): ?>
                                <option value="<?= $c["id_empleado"] ?>" <?= $consultaatepedidos["id_conductor"] == $c["id_empleado"] ? 'selected' : '' ?>>
                                    <?= $c["empleado"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vehiculo</label>
                        <select disabled="" class="form-control select2" id="vehiculo" name="vehiculo">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($vehiculo as $v): ?>
                                <option value="<?= $v["id_vehiculo"] ?>" <?= $consultaatepedidos["id_vehiculo"] == $v["id_vehiculo"] ? 'selected' : '' ?>>
                                    <?= $v["vehiculo"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>


            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Detalle de remisión cliente</div>
            </div>
            <div class="row">

                <div class="col-md-1">
                    <div class="form-group">
                        <label>GR-SERIE</label>
                        <input type="text" style="text-transform:uppercase;"
                               onkeyup="javascript:this.value = this.value.toUpperCase();"
                               class="form-control form-control-sm" id="grserie" name="grserie"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>GR</label>
                        <input type="text" style="text-transform:uppercase;"
                               onkeyup="javascript:this.value = this.value.toUpperCase();"
                               class="form-control form-control-sm" id="gr" name="gr"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>FT</label>
                        <input type="text" style="text-transform:uppercase;"
                               onkeyup="javascript:this.value = this.value.toUpperCase();"
                               class="form-control form-control-sm" id="ft" name="ft"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>O/C</label>
                        <input type="text" style="text-transform:uppercase;"
                               onkeyup="javascript:this.value = this.value.toUpperCase();"
                               class="form-control form-control-sm" id="oc" name="oc"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control form-control-sm" id="cantidad" name="cantidad"/>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label>Peso(kg)</label>
                        <input type="number" class="form-control form-control-sm" id="peso" name="peso" value="<?= $consultaatepedidos["peso"]?>"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Largo</label>
                        <input type="number" class="form-control form-control-sm" id="largo" name="largo"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Ancho</label>
                        <input type="number" class="form-control form-control-sm" id="ancho" name="ancho"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Alto</label>
                        <input type="number" class="form-control form-control-sm" id="alto" name="alto"/>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <label>PESO VOL</label>
                        <input type="number" class="form-control form-control-sm" id="pesovol" step=".01" name="pesovol"
                               value="0"/>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tipo de carga</label>
                        <select class="form-control form-control-sm" id="tipo_carga" name="tipo_carga">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($tipoCarga as $t): ?>
                                <option value="<?= $t->id_tipo_carga ?>"><?= $t->siglas . '::' . $t->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" style="text-transform:uppercase;"
                               onkeyup="javascript:this.value = this.value.toUpperCase();"
                               class="form-control form-control-sm" id="descripcion" name="descripcion" value="<?= $consultaatepedidos["descripcion"]?>"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-detalle-rc">
                            <i class="flaticon-add-circular-button"></i>
                        </button>
                    </div>
                </div>

                <hr>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">GR</th>
                        <th scope="col">GR</th>
                        <th scope="col">FT</th>
                        <th scope="col">O/C</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Largo</th>
                        <th scope="col">Ancho</th>
                        <th scope="col">Alto</th>
                        <th scope="col">Peso Vol</th>
                        <th scope="col">Tipo carga</th>
                        <th scope="col" >Descripción</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody id="tabla-detalle-guia-rc">

                    </tbody>
                </table>

                <!-- <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                     <div class="alert-text font-weight-bold">Detalle de guia</div>
                 </div>
               <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Producto</label>
                        <select disabled="" class="form-control form-control-sm" id="producto" name="producto">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($producto as $p): ?>
                                <option value="<?= $p->id_producto ?>"><?= $p->cod_producto . '::' . $p->nombre_producto . '::' . $p->unidad_medida ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="cantidad" name="cantidad"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Peso(kg)</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="peso" name="peso"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Largo</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="largo" name="largo"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Ancho</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="ancho" name="ancho"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Alto</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="alto" name="alto"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>PESO VOL</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="pesovol" step=".01" name="pesovol" value="0"/>
                    </div>
                </div>

            <div class="col-md-1">
                <div class="form-group">
                    <button disabled="" type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-detalle">
                        <i class="flaticon-add-circular-button"></i>
                    </button>
                </div>
            </div>
        </div>
                <hr>
             <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Unidad</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th scope="col">largo</th>
                        <th scope="col">ancho</th>
                        <th scope="col">alto</th>
                        <th scope="col">Volumen</th>
                         <th scope="col">Descr</th>
                <th scope="col"></th>
                </tr>
                </thead>
                <tbody id="tabla-detalle-guia">

                </tbody>
                </table>
-->

            </div>

            <div class="row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <div class="card" style="width:10rem;">

                        <div class="form-group">

                            <input disabled value="">

                            <img class="card-img-top" id="segpeg" src="">
                            <input type="button" id="loadFileXml" value="Cargar Imagen"
                                   onclick="document.getElementById('image').click();"/>
                            <input type="file" style="display:none;" class="form-control-file" name="" id="image"
                                   data-buttonText="Your label here.">
                        </div>

                    </div>


                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
                <a href="<?= Yii::$app->urlManager->createUrl("atenderasignacion"); ?>" class="btn btn-secondary">Cancelar</a>
            </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



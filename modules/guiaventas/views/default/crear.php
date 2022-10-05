<?php

use app\modules\guiaventas\bundles\GuiaVentasAsset;

$bundle = GuiaVentasAsset::register($this);
?>

<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-guia-venta">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos guía de remisión</div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Serie</label>
                        <input value="0002" type="text" class="form-control form-control-sm" id="serie" name="serie" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" style="text-transform:uppercase;"   class="form-control form-control-sm" id="numero" name="numero" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>


                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" min="2013-01-01" max="2050-12-31" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Forma Pago</label>
                        <select class="form-control form-control-sm" id="id_forma_pago" name="id_forma_pago">
                        <option disabled selected>Seleccione</option>
                            <?php foreach ($formapago as $v) : ?>
                                <option value="<?= $v->id_forma_pago ?>"><?= $v->forma_pago ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tipo Comprobante</label>
                        <select class="form-control form-control-sm" id="id_tipo_comprobante" name="id_tipo_comprobante">
                        <option disabled selected>Seleccione</option>
                            <?php foreach ($tipocomprobante as $v) : ?>
                               
                                <option value="<?= $v->id_tipo_comprobante ?>"><?= $v->descripcion ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos transportista</div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Conductor</label>
                        <select class="form-control select2" id="conductor" name="conductor">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($conductor as $c) : ?>
                                <option value="<?= $c["id_empleado"] ?>"><?= $c["empleado"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Vehiculo</label>
                        <select class="form-control select2" id="vehiculo" name="vehiculo">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($vehiculo as $v) : ?>
                                <option value="<?= $v["id_vehiculo"] ?>"><?= $v["vehiculo"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Agente</label>
                        <select class="form-control select2" id="agenteas" name="agenteas">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($agente as $a): ?>
                                <option value="<?= $a->id_agente ?>"><?= $a->cuenta . ' - ' . $a->agente ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos Remitente</div>
            </div>
            <div class="row">

                <div class="form-group input-group-sm col-md-2">
                    <label class="font-small">Tipo Documento </label>
                    <select class="form-control form-control-sm" id="tipo_documento" name="tipo_documento">
                         <option disabled selected>Seleccione</option>
                        <?php foreach ($tipodocumento as $v) : ?>
                            <option value="<?= $v->id_tipo_documento ?>"><?= $v->documento ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="form-group input-group-sm col-md-3">
                    <label class="font-small">Número de Documento <span class="text-danger">*</label>
                    <input autocomplete="off" style="text-transform:uppercase;" onkeyup="valida_longitud()" class="form-control" name="numero_documentob" id="numero_documentob" type="text">
                </div>
                <div class="form-group input-group-sm col-md-1">

                    <button class="btn btn-icon btn-primary btn-sm mt-7" id="buscar-documento-gv">
                        <i class="flaticon-search-magnifier-interface-symbol"></i>
                    </button>
                </div>
                <div class="form-group input-group-sm col-md-6">
                    <label class="font-small">Nombre / Razón Social</label>
                    <input class="form-control"  disabled="" name="nombrecliente" id="nombrecliente" value="" type="text">
                    <input class="form-control" disabled type="hidden" name="id_entidad_" id="id_entidad_" value="" type="text">
                </div>


            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Datos de envío</div>
            </div>
            <div class="row">
                <div class="col-12">


                    <div class="form-row">
                        <div class="form-group input-group-sm col-md-3">
                            <label>Tipo Envío</label>
                            <select class="form-control form-control-sm" id="producto" name="producto">
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($producto as $p) : ?>
                                    <option value="<?= $p->id_producto ?>"><?= $p->cod_producto . '::' . $p->nombre_producto . '::' . $p->unidad_medida ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group input-group-sm col-md-9">
                            <label class="font-small">Producto a enviar</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" name="descripcion_producto" id="descripcion_producto" type="text">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group input-group-sm col-md-3">
                            <label class="font-small">Forma de envío</label>
                            <select class="form-control form-control-sm" id="forma_envio" name="forma_envio">
                                <?php foreach ($via as $v) : ?>
                                    <option value="<?= $v->id_via ?>"><?= $v->nombre_via ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="form-group input-group-sm col-md-2">
                            <label class="font-small">Cantidad</label>
                            <input class="form-control" name="cantidad" id="cantidad" type="number">
                        </div>
                        <div class="form-group input-group-sm col-md-2">
                            <label class="font-small">Peso <span class="text-danger">*</label>
                            <input class="form-control" name="peso" id="peso" type="text">
                        </div>
                        <div class="form-group input-group-sm col-md-2">
                            <label class="font-small">Volumen (opcional)</label>
                            <input class="form-control" name="volumen" id="volumen" type="text">
                        </div>
                        <div class="form-group input-group-sm col-md-2">
                            <label class="font-small">Monto de envío</label>
                            <input class="form-control" name="monto_envio" id="monto_envio" type="text">
                        </div>
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="col-12">
                            <h4>Usuario Destino</h4><br>
                        </div>
                        <div class="form-group input-group-sm col-md-2">
                            <label class="font-small">Documento </label>
                            <select class="form-control form-control-sm" id="tipo_dni_usuario_des" name="tipo_dni_usuario_des">
                                <option disabled selected>Seleccione</option>
                                <?php foreach ($tipodocumento as $v) : ?>
                                    <option value="<?= $v->id_tipo_documento ?>"><?= $v->documento ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="form-group input-group-sm col-md-3">
                            <label class="font-small">Número documento</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="valida_longitud2()" onKeyDown="valida_longitud2()" class="form-control" name="numero_documento" id="numero_documento" placeholder="DNI / RUC" type="number">
                        </div>
                        <div class="form-group input-group-sm col-md-7">
                            <label class="font-small">Nombres / Razon Social</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" name="nombre_destinatario" id="nombre_destinatario" type="text">
                        </div>
                  <!---      <div class="form-group input-group-sm col-md-3">
                            <label class="font-small">Apellidos destinatario </label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" name="apellido_destinatario" id="apellido_destinatario" type="text">
                        </div>-->
                    </div>
                    <div class="form-row">
                        <div class="form-group input-group-sm col-md-3">
                            <label class="font-small">Celular destinatario</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" name="celular_destinatario" id="celular_destinatario" type="number">
                        </div>
                        <div class="form-group input-group-sm col-md-3">

                            <label class="font-small">Tipo de entrega</label>

                            <select class="form-control form-control-sm" id="tipo_entrega" name="tipo_entrega">
                                <?php foreach ($tipoentrega as $v) : ?>
                                    <option value="<?= $v->id_tipo_entrega ?>"><?= $v->descripcion ?></option>
                                <?php endforeach; ?>
                            </select>



                        </div>


                        <!---->
                        <div class="form-group input-group-sm col-md-6" id="divagente">
                            <label>Agente</label>
                            <select class="form-control select2" id="agente" name="agente">
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($agente as $a) : ?>
                                    <option value="<?= $a->id_agente ?>"><?= $a->cuenta . ' - ' . $a->agente ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group input-group-sm col-md-6" id="divdistrito">
                            <label>Distrito</label>
                            <select class="form-control select2" id="ubigeos_gv" name="ubigeos_gv" style="width: 100%;">
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($ubigeos as $v) : ?>
                                    <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!---->
                    </div>

                    <div class="form-row">
                        <!---->
                        <div class="form-group input-group-sm col-md-12" id="divdirec">
                            <label class="font-small">Dirección destinatario</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" id="direccion_gv" name="address" rows="3" >
                        </div>
                    </div>
                    <div class="form-row">
                        <!---->
                        <div class="form-group input-group-sm col-md-12">
                            <label class="font-small">Otro Consigando</label>
                            <input autocomplete="off" class="form-control" id="otroconsigando_gv" name="address" rows="3"
                                   >
                        </div>

                    </div>
                    <div class="form-row">
                        <!---->
                        <div class="form-group input-group-sm col-md-12">
                            <label class="font-small">Observacion</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" id="observacion_gv" name="address" rows="3">
                        </div>
                    </div>
                    <div class="form-row">
                        <!---->
                        <div class="form-group input-group-sm col-md-12">
                            <label class="font-small">Guia Cliente</label>
                            <input autocomplete="off" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" id="guia_cliente" name="address" rows="3">
                        </div>
                    </div>

                </div>
            </div>

            <hr>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-guardar-gv">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("guiaventas"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->
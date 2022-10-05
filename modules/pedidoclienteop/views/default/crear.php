<?php
$bundle = \app\modules\pedidoclienteop\bundles\PedidoClientesOpAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-pedidoclienteop">      
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">DATOS PEDIDOS</div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?= date("Y-m-d") ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input class="form-control  " name="hora" id="hora" type="time">
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo Servicio</label>
                        <select class="form-control form-control-sm" id="tipo_servicio" name="tipo_servicio">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($producto as $p): ?>
                                <option value="<?= $p->id_producto ?>"><?= $p->cod_producto . '::' . $p->nombre_producto . '::' . $p->unidad_medida ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control select2" id="entidades_cliente" name="entidades_cliente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>"><?= $d->razon_social ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remitente</label>
                        <select class="form-control select2" id="entidades" name="entidades">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>"><?= $d->razon_social ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dirección</label>
                        <select class="form-control select2"  id="direccion_partida" name="direccion_partida"></select>
                    </div>        
                </div>
                <!---    <div class="form-group input-group-sm col-md-1">
    
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-direccion" onclick="funcionNuevaDireccion()">
                                <i class="flaticon-add-circular-button"></i>
                            </button>
                    </div>-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contacto</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="contacto" name="contacto"/>
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Area</label>
                        <select class="form-control select2" id="area" name="area">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($areas as $d): ?>
                                <option value="<?= $d->id_area ?>"><?= $d->nombre_area ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Referencia</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="referencia" name="referencia"/>
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="telefono" name="telefono"/>
                    </div>      
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cantidad Personas </label>
                        <input type="number" class="form-control form-control-sm" id="cantidad_personas" name="cantidad_personas"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Unidad </label>
                        <select class="form-control form-control-sm" id="tipo_unidad" name="tipo_unidad">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($tipo_unidad as $p): ?>
                                <option value="<?= $p->id_tipo_unidad ?>"><?= $p->descripcion ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stoka </label>
                        <select class="form-control form-control-sm" id="stoka" name="stoka">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Frágil </label>
                        <select class="form-control form-control-sm" id="fragil" name="fragil">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>        
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control form-control-sm" id="cantidad" name="cantidad"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Peso(opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="peso" name="peso"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Alto (opcional) </label>
                        <input type="number" class="form-control form-control-sm" id="alto" name="alto"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Ancho (opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="ancho" name="ancho"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Largo (opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="largo" name="largo"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Esta Listo </label>
                        <select class="form-control form-control-sm" id="esta_listo" name="esta_listo">
                            <option value="0">No</option>
                            <option value="1">Si</option>

                        </select>
                    </div>        
                </div>



            </div>

            <div id="id_div_not" class="row"> 

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación</label>
                        <input type="text" class="form-control form-control-sm" id="notificacion_" name="notificacion_"/>
                    </div>     

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación de descarga</label>
                        <input type="text" class="form-control form-control-sm" id="notificacion_descarga" name="notificacion_descarga"/>
                    </div>        
                </div>


            </div>
            
            <div class="row"> 

                <div class="col-md-12">
                <div class="form-group">
                    <label>Observacion </label>
                    <textarea class="form-control form-control-sm"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" id="observacion" name="observacion"></textarea>
                </div>   
            </div>


            </div>

           
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-guardarop">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("pedidoclienteop"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



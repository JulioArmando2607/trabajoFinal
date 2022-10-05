<?php

 

$bundle = \app\modules\pedidoclienteop\bundles\PedidoClientesOpAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <input type="hidden" id="id_pedido_cliente" value="<?= $pedidosCliente["id_pedido_cliente"] ?>">
  <input type="hidden" id="id_cliente_v" value="<?= $pedidosCliente["id_cliente"] ?>">
    <form class="form" id="frm-pedidosclientes">
        <div class="card-body">


            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">DATOS PEDIDOS</div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?= $pedidosCliente["fecha"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora</label>
                        <input class="form-control  " name="hora" id="hora" type="time" value="<?= $pedidosCliente["hora_recojo"] ?>">
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo Servicio</label>

                        <select class="form-control form-control-sm" id="tipo_servicio" name="tipo_servicio" >
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($producto as $p): ?>
                                <option value="<?= $p->id_producto ?>" <?= $pedidosCliente["tipo_servicio"] == $p->id_producto ? 'selected' : '' ?>>
                                    <?= $p->cod_producto . '::' . $p->nombre_producto . '::' . $p->unidad_medida ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                    <div class="col-md-12">
                       <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control select2" id="cliente" name="cliente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $pedidosCliente["id_cliente"] == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
               
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remitente</label>
                        <select class="form-control select2" id="remitente" name="remitente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $pedidosCliente["id_remitente"] == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" id="id_direccion_partida" value="<?= $pedidosCliente["id_direccion_recojo"] ?>">
                        <label>Dirección de partida</label>
                        <select class="form-control select2" id="direccion_partida" name="direccion_partida"></select>
                    </div>        
                </div>
            
             
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contacto</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="contacto" name="contacto" value="<?= $pedidosCliente["contacto"] ?>"/>
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Area</label>
                        <select class="form-control select2" id="area" name="area">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($areas as $d): ?>
                                <option value="<?= $d->id_area ?>" <?= $pedidosCliente["id_area"] == $d->id_area ? 'selected' : '' ?>>
                                    <?= $d->nombre_area ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Referencia</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="referencia" name="referencia" value="<?= $pedidosCliente["referencia"] ?>"/>
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="telefono" name="telefono" value="<?= $pedidosCliente["telefono"] ?>"/>
                    </div>      
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cantidad Personas </label>
                        <input type="number" class="form-control form-control-sm" id="cantidad_personas" name="cantidad_personas" value="<?= $pedidosCliente["cantidad_personal"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Unidad </label>
                        <select class="form-control form-control-sm" id="tipo_unidad" name="tipo_unidad">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($tipo_unidad as $p): ?>
                                <option value="<?= $p->id_tipo_unidad ?>" <?= $pedidosCliente["id_tipo_unidad"] == $p->id_tipo_unidad ? 'selected' : '' ?>>
                                    <?= $p->descripcion ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stoka </label>
                        <select class="form-control form-control-sm" id="stoka" name="stoka">
                            
                            <option value="0" <?= $pedidosCliente["stoka"]  == '0' ? 'selected' : '' ?>>NO</option>
                            <option value="1" <?= $pedidosCliente["stoka"] == '1' ? 'selected' : '' ?>>SI</option>
 
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Frágil </label>
                        <select class="form-control form-control-sm" id="fragil" name="fragil">
                             <option value="0" <?= $pedidosCliente["fragil"]  == '0' ? 'selected' : '' ?>>NO</option>
                            <option value="1" <?= $pedidosCliente["fragil"] == '1' ? 'selected' : '' ?>>SI</option>
                     
                        </select>
                    </div>        
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control form-control-sm" id="cantidad" name="cantidad" value="<?= $pedidosCliente["cantidad"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Peso(opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="peso" name="peso" value="<?= $pedidosCliente["peso"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Alto (opcional) </label>
                        <input type="number" class="form-control form-control-sm" id="alto" name="alto" value="<?= $pedidosCliente["alto"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Ancho (opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="ancho" name="ancho" value="<?= $pedidosCliente["ancho"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Largo (opcional)</label>
                        <input type="number" class="form-control form-control-sm" id="largo" name="largo" value="<?= $pedidosCliente["largo"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Esta Listo </label>
                        <select class="form-control form-control-sm" id="esta_listo" name="esta_listo">
                              <option value="0" <?= $pedidosCliente["estado_mercaderia"]  == '0' ? 'selected' : '' ?>>NO</option>
                            <option value="1" <?= $pedidosCliente["estado_mercaderia"] == '1' ? 'selected' : '' ?>>SI</option>
            
                        </select>
                    </div>        
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observacion </label>
                        <textarea class="form-control form-control-sm"  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" id="observacion" name="observacion" ><?php echo $pedidosCliente["observacion"]; ?></textarea>
                    </div>   
                </div>
            </div>
       <div id="id_div_not" class="row"> 

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación</label>
                        <input type="text"   class="form-control form-control-sm" id="notificacion_" name="notificacion_" value="<?=$pedidosCliente["notificacion"] ? '' : $pedidosCliente["notificacion"] ?>"/>
                    </div>     

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación de descarga</label>
                        <input type="text"   class="form-control form-control-sm" id="notificacion_descarga" name="notificacion_descarga" value="<?=$pedidosCliente["notificacion_descarga"] ? '' :$pedidosCliente["notificacion_descarga"] ?>"/>
                    </div>        
                </div>


            </div>


        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-actualizar">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("pedidoclienteop"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



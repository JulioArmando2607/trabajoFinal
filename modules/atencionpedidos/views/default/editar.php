<?php

use app\modules\atencionpedidos\bundles\AtencionPedidosAsset;

$bundle = AtencionPedidosAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <input type="hidden" id="id_pedido_cliente" value="<?= $pedidosCliente["id_pedido_cliente"] ?>">
    <input  type="hidden" id="id_atencion_pedidos" value="<?= empty($atecliente["id_atencion_pedidos"]) ? '' : $atecliente["id_atencion_pedidos"] ?>"/>
    <form class="form" id="frm-pedidosclientes">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold" ><?php echo 'PEDIDO N°: ' . $pedidosCliente["nm_solicitud"] ?> </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Recojo</label>

                        <input type="text" class="form-control form-control-sm" id="fecha" disabled name="fecha" value="<?= $pedidosCliente["fecha"] ?>"/>


                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora Recojo</label>

                        <input type="text" class="form-control form-control-sm" id="hora_recojo" disabled name="hora_recojo" value="<?= $pedidosCliente["hora_recojo"] ?>"/>
                    </div>        
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Entrega(Opcional)</label>

                        <input type="text" class="form-control form-control-sm" id="fecha" disabled name="fecha" value="<?= $pedidosCliente["fecha"] ?>"/>


                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora Entrega(Opcional)</label>

                        <input type="text" class="form-control form-control-sm" id="hora_recojo" disabled name="hora_recojo" value="<?= $pedidosCliente["hora_recojo"] ?>"/>
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remitente</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="direccion" name="direccion" value="<?= $pedidosCliente["remitente"] ?>"/>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dirección Partida</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="direccion" name="direccion" value="<?= $pedidosCliente["direccion_recojo"] ?>"/>
                        </select>
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Destinatario</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="direccion" name="direccion" value="<?= $pedidosCliente["destinatario"] ?>"/>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dirección Llegada</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="direccion" name="direccion" value="<?= $pedidosCliente["direccion_llegada"] ?>"/>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo Servicio</label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["servicio"] ?>"/>

                    </div>        
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contacto</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="contacto" name="contacto" value="<?= $pedidosCliente["contacto"] ?>"/>
                    </div>      
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="telefono" name="telefono" value="<?= $pedidosCliente["telefono"] ?>"/>
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["nombre_area"] ?>"/>

                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Referencia</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="referencia" name="referencia" value="<?= $pedidosCliente["referencia"] ?>"/>
                    </div>      
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Estibas </label>
                        <input type="number" class="form-control form-control-sm"  id="cantidad_personas" name="cantidad_personas" value="<?= $pedidosCliente["cantidad_personal"] ?>"/>
                    </div>        
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Unidad </label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["nombre_tipo_unidad"] ?>"/>

                    </div>        
                </div>

                <div hidden class="col-md-3">
                    <div hidden class="form-group">
                        <label>Stoka </label>
                        <input type="text" hidden="" class="form-control form-control-sm"  disabled value="<?= $pedidosCliente["valor_stoka"] ?>"/>

                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stoka </label>
                        <select class="form-control form-control-sm" id="stoka" name="stoka">

                            <option value="0" <?= $pedidosCliente["stoka"] == '0' ? 'selected' : '' ?>>NO</option>
                            <option value="1" <?= $pedidosCliente["stoka"] == '1' ? 'selected' : '' ?>>SI</option>

                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Frágil </label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["valor_fragil"] ?>"/>

                    </div>        
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>N° Bulto</label>
                        <input type="text" class="form-control form-control-sm" disabled id="cantidad" name="cantidad" value="<?= $pedidosCliente["cantidad"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Peso(opcional)</label>
                        <input type="number" class="form-control form-control-sm" disabled id="peso" name="peso" value="<?= $pedidosCliente["peso"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Alto (opcional) </label>
                        <input type="number" class="form-control form-control-sm" disabled id="alto" name="alto" value="<?= $pedidosCliente["alto"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Ancho (opcional)</label>
                        <input type="number" class="form-control form-control-sm" disabled id="ancho" name="ancho" value="<?= $pedidosCliente["ancho"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Largo (opcional)</label>
                        <input type="number" class="form-control form-control-sm" disabled id="largo" name="largo" value="<?= $pedidosCliente["largo"] ?>"/>
                    </div>        
                </div>
                <div  hidden class="col-md-1">
                    <div hidden class="form-group">
                        <label>Esta Listo </label> 
                        <input type="text" hidden="" class="form-control form-control-sm" disabled id="nom_estado_mercaderia" name="nom_estado_mercaderia" value="<?= $pedidosCliente["nom_estado_mercaderia"] ?>"/>
                    </div>        
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación</label>
                        <input type="text" disabled class="form-control form-control-sm" id="notificacion_" name="notificacion_" value="<?= $pedidosCliente["notificacion"] ?>"/>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Notificación de descarga</label>
                        <input type="text" disabled="" class="form-control form-control-sm" id="notificacion_descarga" name="notificacion_descarga" value="<?= $pedidosCliente["notificacion_descarga"] ?>"/>
                    </div>






                </div>
                <table disabled class="table">
                    <thead>
                        <tr>
                            <th scope="col">OC</th>
                            <th scope="col">MATERIAL NUMBER</th>
                            <th scope="col">DESCRIPCION</th>
                            <th scope="col">BATCH</th>
                            <th scope="col">PSO</th>
                            <th scope="col">ALT.</th>
                            <th scope="col">ANCH.</th>
                            <th scope="col">LARG.</th>
                            <th scope="col">VOLMEN</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="tabla-detalle-guia">
                    </tbody>
                </table>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observacion </label>
                        <textarea class="form-control form-control-sm"  disabled style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" id="observacion" name="observacion" ><?php echo $pedidosCliente["observacion"]; ?></textarea>
                    </div>   
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Conductor</label>

                        <select class="form-control select2" id="conductor" name="conductor">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($conductor as $c): ?>
                                <option value="<?= $c["id_empleado"] ?>" <?= empty($atecliente["conductor"]) ? '' : $atecliente["conductor"] == $c["id_empleado"] ? 'selected' : '' ?>>
                                    <?= $c["empleado"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>


                    </div>        
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Auxiliar</label>
                        <select class="form-control select2" id="auxiliar" name="auxiliar">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($auxiliar as $c): ?>
                                <option value="<?= $c["id_empleado"] ?>"  <?= empty($atecliente["auxiliar"]) ? '' : $atecliente["auxiliar"] == $c["id_empleado"] ? 'selected' : '' ?>>
                                    <?= $c["empleado"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>


                    </div>        
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Vehiculo</label>

                        <select class="form-control select2" id="vehiculo" name="vehiculo">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($vehiculo as $v): ?>
                                <option value="<?= $v["id_vehiculo"] ?>" <?= empty($atecliente["unidad"]) ? '' : $atecliente["unidad"] == $v["id_vehiculo"] ? 'selected' : '' ?>>
                                    <?= $v["vehiculo"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>        
                </div>

            </div>



        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-actualizar">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("atencionpedidos"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



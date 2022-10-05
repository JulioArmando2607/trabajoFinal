<?php

use app\modules\pedidosclientes\bundles\PedidosClientesAsset;

$bundle = PedidosClientesAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <input type="hidden" id="id_pedido_cliente" value="<?= $pedidosCliente["id_pedido_cliente"] ?>">
 
    <form class="form" id="frm-pedidosclientes">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Pedido N° 13456</div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha</label>

                        <input type="text" class="form-control form-control-sm" id="fecha" disabled name="fecha" value="<?= $pedidosCliente["fecha"] ?>"/>


                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Hora</label>

                        <input type="text" class="form-control form-control-sm" id="hora_recojo" disabled name="hora_recojo" value="<?= $pedidosCliente["hora_recojo"] ?>"/>
                    </div>        
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Servicio</label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["servicio"] ?>"/>

                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Unidad </label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["nombre_tipo_unidad"] ?>"/>

                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["nombre_area"] ?>"/>

                    </div>        
                </div>
               
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="direccion" name="direccion" value="<?= $pedidosCliente["direccion_recojo"] ?>"/>
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="telefono" name="telefono" value="<?= $pedidosCliente["telefono"] ?>"/>
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
                        <label>Referencia</label>
                        <input type="text"  style="text-transform:uppercase;" disabled onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="referencia" name="referencia" value="<?= $pedidosCliente["referencia"] ?>"/>
                    </div>      
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad Personas </label>
                        <input type="number" class="form-control form-control-sm" disabled id="cantidad_personas" name="cantidad_personas" value="<?= $pedidosCliente["cantidad_personal"] ?>"/>
                    </div>        
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Stoka </label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["valor_stoka"] ?>"/>

                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Frágil </label>
                        <input type="text" class="form-control form-control-sm"   disabled value="<?= $pedidosCliente["valor_fragil"] ?>"/>

                    </div>        
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label>Cantidad</label>
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Ancho (opcional)</label>
                        <input type="number" class="form-control form-control-sm" disabled id="ancho" name="ancho" value="<?= $pedidosCliente["ancho"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Largo (opcional)</label>
                        <input type="number" class="form-control form-control-sm" disabled id="largo" name="largo" value="<?= $pedidosCliente["largo"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Esta Listo </label> 
                        <input type="text" class="form-control form-control-sm" disabled id="nom_estado_mercaderia" name="nom_estado_mercaderia" value="<?= $pedidosCliente["nom_estado_mercaderia"] ?>"/>
                    </div>        
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observacion </label>
                        <textarea class="form-control form-control-sm"  disabled style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" id="observacion" name="observacion" ><?php echo $pedidosCliente["observacion"]; ?></textarea>
                    </div>   
                </div>

              
            </div>



        </div>
        <div class="card-footer text-right">
           
            <a href="<?= Yii::$app->urlManager->createUrl("pedidosclientes"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



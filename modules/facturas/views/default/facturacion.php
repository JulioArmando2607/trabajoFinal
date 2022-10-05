<?php

use app\modules\facturas\bundles\FacturasAsset;

$bundle = FacturasAsset::register($this);
?>
<form id="frm-facturacion">
    <input   id="id_ventas_factura" value="<?= $lfactura["id_ventas_factura"] ?>"/>
        <div class="row">
        <div class="form-group col-md-6">
            <label>Serie</label>
            <input type="text" disabled class="form-control" name="razon_social" id="serie" value="<?= $lfactura['serie'] ?>"/>
        </div>  

        <div class="form-group col-md-6">
            <label>Correlativo</label>
            <input type="text"   disabled class="form-control"  name="razon_social" id="correlativo" value="<?= $lfactura["correlativo"] ?>"/>
        </div>  
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Fecha</label>
            <input type="text" disabled class="form-control" name="razon_social" id="razon_social" value="<?= $lfactura['fecha_reg'] ?>"/>
        </div>  

        <div class="form-group col-md-6">
            <label>Tipo Comprobante</label>
            <input type="text"   disabled class="form-control"  name="razon_social" id="razon_social" value="<?= $lfactura["tipo_comprobante"] ?>"/>
        </div>  
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label class="font-small">Tipo Documento </label>

            <input type="text" disabled  class="form-control"  name="numero_documento_entidad" id="numero_documento_entidad" 
                   value="<?= $lfactura["documento"] ?>"/>
        </div>  

        <div class="form-group col-md-6">
            <label>Numero Documento </label>
            <input type="text" disabled class="form-control" name="numero_documento_entidad" id="numero_documento_entidad" 
                   value="<?= $lfactura["numero_documento"] ?>"/>
        </div>
    </div>


    <div class="form-group">
        <label>Nombre </label>
        <input type="text" disabled class="form-control" placeholder="Ingrese Nombre" name="razon_social" id="razon_social"  value="<?= $lfactura["cliente"] ?>"/>
    </div>


    <div class="row">
        <div class="form-group col-md-6">
            <label>Moneda </label>
            <input type="text" disabled  class="form-control"  name="tipo_moneda" id="tipo_moneda" value="<?= $lfactura["tipo_moneda"] ?>" />
        </div>
        <div class="form-group col-md-6">
            <label>Unidad Medida </label>
            <input type="text" disabled class="form-control"  name="direccion" id="direccion" value="<?= $lfactura["unidad"] ?>" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label>Valor Unitario</label>
            <input type="text" disabled class="form-control" placeholder="Ingrese Valor Unitario" name="precio_unitario" id="precio_unitario" value="<?= empty($lfactura["subtotal"]) ? $guiaventas["precio_unitario"] : $lfactura["subtotal"] ?>"/>
        </div>

          <div class="form-group col-md-3">
            <label>IGV</label>
      
            <input type="text" disabled class="form-control"  name="igv" id="igv"  value="<?= empty($lfactura["igv"]) ? $guiaventas["igv"] : $lfactura["igv"] ?>"/>
        </div> 
        <div class="form-group col-md-3">
            <label>Importe venta</label>
            <input type="text" disabled class="form-control"  name="monto_envio" id="monto_envio" value="<?= empty($lfactura["total_m"]) ? $guiaventas["monto_envio"] :$lfactura["total_m"] ?>"/>
        </div> 
        
    </div>

<!--    <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono</label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" value="<?= $lfactura["telefono"] ?>"/>
        </div>

        <div class="form-group col-md-6">
            <label>Correo</label>
            <input type="email" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" value="<?= $lfactura["correo"] ?>" />
        </div>
    </div>
-->


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-factura">Factura</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

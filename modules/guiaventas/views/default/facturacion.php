<?php

use app\modules\guiaventas\bundles\GuiaVentasAsset;

$bundle = GuiaVentasAsset::register($this);
?>
<form id="frm-guia-venta-facturacion">
    <input   id="id_guia_venta" value="<?= $facturac["id_guia_venta"] ?>"/>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Fecha</label>
            <input type="text" disabled class="form-control" name="razon_social" id="razon_social" value="<?= $facturac["fecha"] ?>"/>
        </div>  

        <div class="form-group col-md-6">
            <label>Tipo Comprobante</label>
            <input type="text"   disabled class="form-control"  name="razon_social" id="razon_social" value="<?= $facturac["tipo_comprobante"] ?>"/>
        </div>  
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label class="font-small">Tipo Documento </label>

            <input type="text" disabled  class="form-control"  name="numero_documento_entidad" id="numero_documento_entidad" 
                   value="<?= $facturac["documento"] ?>"/>
        </div>  

        <div class="form-group col-md-6">
            <label>Numero Documento </label>
            <input type="text" disabled class="form-control" name="numero_documento_entidad" id="numero_documento_entidad" 
                   value="<?= $facturac["numero_documento"] ?>"/>
        </div>
    </div>


    <div class="form-group">
        <label>Nombre </label>
        <input type="text" disabled class="form-control" placeholder="Ingrese Nombre" name="razon_social" id="razon_social"  value="<?= $facturac["razon_social"] ?>"/>
    </div>


    <div class="row">
        <div class="form-group col-md-6">
            <label>Moneda </label>
            <input type="text" disabled  class="form-control"  name="tipo_moneda" id="tipo_moneda" value="<?= $facturac["tipo_moneda"] ?>" />
        </div>
        <div class="form-group col-md-6">
            <label>Unidad Medida </label>
            <input type="text" disabled class="form-control"  name="direccion" id="direccion" value="<?= $facturac["unidad"] ?>" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Valor Unitario</label>
            <input type="text" disabled class="form-control" placeholder="Ingrese Valor Unitario" name="valor_unitario" id="precio_unitario"  value="<?= $facturac["precio_unitario"] ?>"/>
        </div>

        <div class="form-group col-md-6">
            <label>Importe venta</label>
            <input type="text" disabled class="form-control"  name="importe_venta" id="monto_envio" value="<?= $facturac["monto_envio"] ?>" />
        </div> 
    </div>

<!--    <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono</label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" value="<?= $facturac["telefono"] ?>"/>
        </div>

        <div class="form-group col-md-6">
            <label>Correo</label>
            <input type="email" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" value="<?= $facturac["correo"] ?>" />
        </div>
    </div>
-->


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-factura-crear">Factura</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

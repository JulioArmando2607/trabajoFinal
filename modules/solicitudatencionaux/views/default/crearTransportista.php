<?php

use app\modules\solicitudatencionaux\bundles\SolicitudAtencionAuxAsset;

$bundle = SolicitudAtencionAuxAsset::register($this);
?>
<!--begin::Card-->
<form id="form-transportista-reg">

  
    <div class="row">
        <div class="form-group col-md-6">
            <label>Tipo Documento</label>
            <select class="form-control" id="tipo_documento" name="tipo_documento" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($tipo_documento as $v) : ?>
                    <option value="<?= $v->id_tipo_documento ?>"><?= $v->documento ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label>Numero Documento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Numero Documento" name="numero_documento" id="numero_documento" />
        </div>
    </div>


    <div class="form-group">
        <label>Nombre<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Nombre" name="razon_social" id="razon_social" />
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono</label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" />
        </div>

        <div class="form-group col-md-6">
            <label>Correo</label>
            <input type="email" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Ubigeo<span class="text-danger">*</span></label>
            <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($ubigeos as $v) : ?>
                    <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Direccion<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Urbanizacion</label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Urbanizacion" name="urbanizacion" id="urbanizacion" />
        </div>

        <div class="form-group col-md-6">
            <label>Referencias</label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="referencias" id="referencias" />
        </div>
    </div>

 
    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-transportista">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>
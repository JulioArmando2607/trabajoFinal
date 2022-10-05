<?php

use app\modules\atenderasignacion\bundles\AtenderAsignacionAsset;

$bundle = AtenderAsignacionAsset::register($this);
?>
<form id="frm-guia-venta-entidad">
 

    <div class="row">
        <div class="form-group col-md-6">
             <label class="font-small">Tipo Documento </label>
                    <select class="form-control form-control-sm" id="tipo_documento_entidad" name="tipo_documento_entidad">
                        <?php foreach ($tipo_documento as $v) : ?>
                            <option value="<?= $v->id_tipo_documento ?>"><?= $v->documento ?></option>
                        <?php endforeach; ?>
                    </select>
            
        </div>  

        <div class="form-group col-md-6">
            <label>Numero Documento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Numero Documento" name="numero_documento_entidad" id="numero_documento_entidad" 
                    />
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
            <input type="email" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Ubigeo<span class="text-danger">*</span></label>
            <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($ubigeos as $v): ?>
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
        <button class="btn btn-primary mr-2" id="btn-guardar-entidad">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

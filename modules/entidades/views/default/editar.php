<?php

use app\modules\entidades\bundles\EntidadesAsset;

$bundle = EntidadesAsset::register($this);
?>
<form id="form-entidades">

    <div class="form-group">
        <label>Tipo Entidad<span class="text-danger">*</span></label>

        <select class="form-control" id="tipo_entidad" name="tipo_entidad" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($tipo_entidad as $v): ?>
                <option value="<?= $v->id_tipo_entidad ?>" <?= $entidad->id_tipo_entidad == $v->id_tipo_entidad ? 'selected' : '' ?>>
                    <?= $v->descripcion ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>  

    <div class="row">
        <div class="form-group col-md-6">

            <label>Tipo Documento<span class="text-danger">*</span></label>
            <select class="form-control" id="tipo_documento" name="tipo_documento" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($tipo_documento as $v): ?>
                    <option value="<?= $v->id_tipo_documento ?>" <?= $entidad->id_tipo_documento == $v->id_tipo_documento ? 'selected' : '' ?>>
                        <?= $v->documento ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>  

        <div class="form-group col-md-6">
            <label>Numero Documento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Numero Documento" name="numero_documento" id="numero_documento" value="<?= $entidad->numero_documento ?>"/>
        </div>
    </div>


    <div class="form-group">
        <label>Nombre<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Nombre" name="razon_social" id="razon_social" value="<?= $entidad->razon_social ?>"/>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" value="<?= $entidad->telefono ?>"/>
        </div>

        <div class="form-group col-md-6">
            <label>Correo<span class="text-danger">*(Solo 2 correos separar por ;)</span></label>
            <input type="text" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" value="<?= $entidad->correo ?>"/>
        </div>
    </div>
    <input type="hidden" id="id_direccion" value="<?= empty($direccion) ? 0 : $direccion->id_direccion ?>">
    <div class="row">
        <div class="form-group col-md-6">
            <label>Ubigeo<span class="text-danger">*</span></label>
            <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($ubigeos as $v): ?>
                    <option value="<?= $v->id_ubigeo ?>" <?= $direccion->id_ubigeo == $v->id_ubigeo ? 'selected' : '' ?>>
                        <?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Direccion<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" value="<?= $direccion->direccion ?>" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Urbanizacion<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Urbanizacion" name="urbanizacion" id="urbanizacion" value="<?= $direccion->urbanizacion ?>" />
        </div>

        <div class="form-group col-md-6">
            <label>Referencias<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="referencias" id="referencias" value="<?= $direccion->referencias ?>"/>
        </div> 
    </div>

    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

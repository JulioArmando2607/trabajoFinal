<?php

 

$bundle = \app\modules\pedidoclienteop\bundles\PedidoClientesOpAsset::register($this);
?>
<form id="form-direcciones">
    <input type="hidden"   id="entidad" name="entidad" value="<?= $entidades["id_entidad"] ?>"/>
    <div class="form-group">
        <label>Ubigeo</label>
        <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($ubigeos as $v) : ?>
                <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Direccion<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" />
    </div>

    <div class="form-group">
        <label>Urbanizacion<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Urbanizacion" name="urbanizacion" id="urbanizacion" />
    </div>

    <div class="form-group">
        <label>Referencias<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="referencias" id="referencias" />
    </div>
    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-direccion">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>
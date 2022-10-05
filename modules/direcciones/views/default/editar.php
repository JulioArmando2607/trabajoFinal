<form id="form-direcciones">
    
       <div class="form-group">
        <label>Entidad</label>
        <select class="form-control form-control-sm select2" id="entidad" name="entidad" style="width: 100%;">
            <?php foreach ($entidad as $v): ?>
                <option value="<?= $v->id_entidad?>" <?= $direcciones->id_entidad== $v->id_entidad ? 'selected' : '' ?>>
                    <?= $v->razon_social ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div> 
     
       <div class="form-group">
        <label>Ubigeo</label>
        <select class="form-control form-control-sm select2" id="ubigeos" name="ubigeos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
            <?php foreach ($ubigeos as $v): ?>
                <option value="<?= $v->id_ubigeo ?>" <?= $direcciones->id_ubigeo == $v->id_ubigeo ? 'selected' : '' ?>>
                    <?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div> 
    
   
    <div class="form-group">
        <label>Direccion<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion"  value="<?= $direcciones->direccion ?>"/>
    </div>

    <div class="form-group">
        <label>Urbanizacion </label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Urbanizacion" name="urbanizacion" id="urbanizacion" value="<?= $direcciones->urbanizacion ?>"/>
    </div>

    <div class="form-group">
        <label>Referencias</label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="referencias" id="referencias" value="<?= $direcciones->referencias ?>" />
    </div>
   
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
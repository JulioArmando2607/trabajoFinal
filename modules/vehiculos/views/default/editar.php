<form id="form-vehiculos">
      
     
       <div class="form-group">
        <label>Marca Vehiculo</label>
        <select class="form-control form-control-sm select2" id="marca_vehiculo" name="marca_vehiculo" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
            <?php foreach ($marcavehiculos as $v): ?>
                <option value="<?= $v->id_marca ?>" <?= $vehiculos->id_marca == $v->id_marca ? 'selected' : '' ?>>
                    <?= $v->nombre_marca ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div> 
    
   
    <div class="form-group">
        <label>Placa<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Direccion" name="placa" id="placa"  value="<?= $vehiculos->placa ?>"/>
    </div>

    <div class="form-group">
        <label>Descripcion </label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Descripcion" name="descripcion" id="descripcion" value="<?= $vehiculos->descripcion ?>"/>
    </div>

    <div class="form-group">
        <label>Incripcion</label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="incripcion" id="incripcion" value="<?= $vehiculos->incripcion ?>" />
    </div>
      <div class="form-group">
        <label>Configuracion Vehicular</label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Referencias" name="config_vehicular" id="config_vehicular" value="<?= $vehiculos->config_vehicular ?>" />
    </div>
   
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
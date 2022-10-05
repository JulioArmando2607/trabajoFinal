<form id="form-conductores">
      <div class="form-group">
       
            <label>Personas<span class="text-danger">*</span></label>
            <select class="form-control select2" id="personas" name="personas" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($personas as $v): ?>
                    <option value="<?= $v->id_persona ?>" <?= $conductores->id_persona == $v->id_persona ? 'selected' : '' ?>>
                    <?= $v->dni . ' - ' . $v->nombres . ' - ' . $v->apellido_paterno ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    
    <div class="form-group">
        <label>Conductor<span class="text-danger">*</span></label>
        <select class="form-control" name="conductor" id="conductor">
            <option value="1" <?= $conductores->flg_conductor == 'Si' ? 'selected' : '' ?>>Si</option>
            <option value="0" <?= $conductores->flg_conductor == 'No' ? 'selected' : '' ?>>No</option>
        </select>
    </div>
    
      <div class="form-group">
        <label>Licencia<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Licencia" name="licencia" id="licencia" value="<?= $conductores->licencia ?>"/>
    </div>
    
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

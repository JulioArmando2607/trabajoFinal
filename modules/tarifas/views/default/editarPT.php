 
<form id="form-tarifaproe">

    <div class="form-group">
        <label>Provincia</label>
        
        <select hidden="" disabled="" class="form-control form-control-sm select2" id="provinciae" name="provinciae" style="width: 100%;">
               
            <?php foreach ($provincia as $v): ?>
                <option value="<?= $v["id_ubigeo"] ?>" <?= $tarifaprovincia["id_provincia"] == $v["id_ubigeo"] ? 'selected' : '' ?>>
                    <?= $v["nombre_provincia"]  ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>   

    <div class="form-group">
        <label>TAFIFA GENERAL<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="tarifa" id="tarifa_m_t_cg" value="<?= $tarifaprovincia['tarifa_m_t_cg']?>"/>
   </div>
      <div class="form-group">
        <label>REFRIGERADAS<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="vacunas_ref" id="tarifa_m_t_ref" value="<?= $tarifaprovincia['tarifa_m_t_ref']?>" />
    </div>
    <div hidden="" class="form-group">
        <label>VOLUMEN<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="mercancia_pel" id="tarifa_m_t_vol" value="<?= $tarifaprovincia['tarifa_m_t_vol']?>"/>
    </div>
      <div class="form-group">
        <label>DIFICIL MANEJO<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="dificil_manejo" id="tarifa_m_t_dm" value="<?= $tarifaprovincia['tarifa_m_t_dm']?>"/>
    </div>
 
    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-tarp">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
    
</form>


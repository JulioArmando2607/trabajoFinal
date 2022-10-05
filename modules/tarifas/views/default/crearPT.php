 
<form id="form-tarifaprot">

    <div class="form-group">
        <label>Provincia</label>
        <select class="form-control form-control-sm select2" id="provincia_te" name="provincia_te" style="width: 100%;">
        <option value="" disabled selected>Seleccione</option>
            <?php foreach ($provincia as $v) : ?>
                <option value="<?= $v["id_ubigeo"] ?>"><?= $v["nombre_provincia"] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
   

    <div class="form-group">
        <label>TAFIFA GENERAL<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="tarifa_terrestre" id="tarifa_terrestre"  />
   </div>
      <div class="form-group">
        <label>REFRIGERADAS<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="refrigeradas_te" id="refrigeradas_te" value="3.50"/>
    </div>
       
      <div class="form-group">
        <label>DIFICIL MANEJO<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="dificil_manejo_te" id="dificil_manejo_te" value="5"/>
    </div>
   <div class="form-group">        
       <input disabled="" type="hidden" class="form-control" placeholder="Ingrese" name="id_entidad_te" id="id_entidad_te" />
    </div>


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-prt">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
    
</form>


 
<form id="form-tarifapro">

    <div class="form-group">
        <label>Provincia</label>
        <select class="form-control form-control-sm select2" id="provincia" name="provincia" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($provincia as $v): ?>
                <option value="<?= $v["id_ubigeo"] ?>"><?= $v["nombre_provincia"] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

  <!--  <div class="form-group">
        <label>Ubigeo</label>
        <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($ubigeos as $v) : ?>
                <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
            <?php endforeach; ?>
        </select>
    </div>
-->

    <div class="form-group">
        <label>TAFIFA GENERAL TERRESTRE<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="tarifa" id="tarifa" value="5.50"/>
    </div>
    <div class="form-group">
        <label>VACUNAS REFRIGERADAS T<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="vacunas_ref" id="vacunas_ref" value="8.40" />
    </div>
    <div class="form-group">
        <label>MERCANCIA PELIGROSA T<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="mercancia_pel" id="mercancia_pel" value="14.80" />
    </div>
    <div class="form-group">
        <label>DIFICIL MANEJO T<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese" name="dificil_manejo" id="dificil_manejo" value="11.70"/>
    </div>

    <!--  <div class="form-group">
         <label>TAFIFA GENERAL T<span class="text-danger">*</span></label>
         <input type="number" class="form-control" placeholder="Ingrese" name="tarifa_terrestre" id="tarifa_terrestre" />
    </div>
       <div class="form-group">
         <label>REFRIGERADAS T<span class="text-danger">*</span></label>
         <input type="number" class="form-control" placeholder="Ingrese" name="refrigeradas_te" id="refrigeradas_te" value="3.50"/>
     </div>
         <div class="form-group">
         <label>VOLUMEN T<span class="text-danger">*</span></label>
         <input type="number" class="form-control" placeholder="Ingrese" name="volumen_te" id="volumen_te" value="4.50"/>
     </div>
        
       <div class="form-group">
         <label>DIFICIL MANEJO T<span class="text-danger">*</span></label>
         <input type="number" class="form-control" placeholder="Ingrese" name="dificil_manejo_te" id="dificil_manejo_te" value="5"/>
     </div>-->
    <div class="form-group">

        <input disabled=""   type="hidden" class="form-control" placeholder="Ingrese" name="id_entidad" id="id_entidad" />
    </div>


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-pr">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>

</form>


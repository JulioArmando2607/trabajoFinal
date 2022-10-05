<form id="form-control-kilometraje">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Vehiculo<span class="text-danger">*</span></label>

                <select class="form-control  " id="vehiculo" name="vehiculo">
                    <option value="" disabled selected>Seleccione</option>
                    <?php foreach ($vehiculo as $v): ?>
                        <option value="<?= $v["id_vehiculo"] ?>"><?= $v["vehiculo"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>HORA SALIDA<span class="text-danger">*</span></label>
           <input class="form-control" name="hora_salida" id="hora_salida" type="time">

        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>HORA LLEGADA<span class="text-danger">*</span></label>
            <input class="form-control" name="hora_llegada" id="hora_llegada" type="time">
        </div>
        <div class="form-group col-md-6">
            <label>KILOMETRAJE SALIDA<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="kilometraje_salida" id="kilometraje_salida"/>
        </div>
    </div>
 <div class="row">
        <div class="form-group col-md-6">
            <label>KILOMETRAJE LLEGADA<span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="kilometraje_llegada" id="kilometraje_llegada"/>
        </div>
        <div class="form-group col-md-6">
            <label>KILOMETRO RECORRIDO<span class="text-danger">*</span></label>
            <input type="number" disabled class="form-control" name="kilometro_recorrido" id="kilometro_recorrido"/>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>DISTRITO </label>
            <select class="form-control select2" id="distrito" name="distrito" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($ubigeos as $v) : ?>
                    <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>
  
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

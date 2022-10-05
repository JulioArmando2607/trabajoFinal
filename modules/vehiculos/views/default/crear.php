<?php

use app\modules\vehiculos\bundles\VehiculosAsset;

$bundle = VehiculosAsset::register($this);
?>
<form id="form-vehiculos">


    <div class="form-group">

        <label>Marca Vehiculo</label>
        <select class="form-control select2" id="marca_vehiculo" name="marca_vehiculo" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($marcavehiculos as $v) : ?>
                <option value="<?= $v->id_marca ?>"><?= $v->nombre_marca?></option>
            <?php endforeach; ?>
        </select>
    </div> 

    <div class="form-group">
        <label>Placa<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Placa" name="placa" id="placa" />
    </div>

    <div class="form-group">
        <label>Descripcion<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Descripcion" name="descripcion" id="descripcion" />
    </div>

    <div class="form-group">
        <label>Inscripcion<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Incripcion" name="incripcion" id="incripcion" />
    </div>
    
      <div class="form-group">
        <label>Configuracion Vehicular </span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" placeholder="Ingrese Configuracion Vehicular" name="config_vehicular" id="config_vehicular" />
    </div>
    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>
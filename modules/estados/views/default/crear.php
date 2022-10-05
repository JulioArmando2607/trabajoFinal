<?php

use app\modules\estados\bundles\EstadosAsset;

$bundle = EstadosAsset::register($this);
?>
<form id="form-estados">

    <div class="form-group">
        <label>Tipo Estado</label>
        <select class="form-control form-control-sm" id="tipo_estado" name="tipo_estado" >
              <option value="" disabled selected>Seleccione</option>
            <?php foreach ($tipo_estado as $v): ?>
                <option value="<?= $v->id_tipo_estado?>"><?= $v->nombre_tipo ?></option>
            <?php endforeach; ?>
        </select>
    </div>  
    
    <div class="form-group">
        <label>Nombre Estado<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese nombres" name="nombre_estado" id="nombre_estado" />
    </div>

    <div class="form-group">
        <label>Siglas<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese agentePersona" name="siglas" id="siglas" />
    </div>


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

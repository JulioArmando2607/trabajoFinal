<?php

use app\modules\atenderasignacion\bundles\AtenderAsignacionAsset;

$bundle = AtenderAsignacionAsset::register($this);
?>
<form id="form-agente-reg">

    <div class="form-group">
        <label>Nombre Agente<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese nombres" name="cuenta" id="cuenta_ag" />
    </div>
    <div class="form-group">
        <label>Agente Persona<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese agentePersona" name="agente" id="agente_nm" />
    </div>

    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-agente">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>
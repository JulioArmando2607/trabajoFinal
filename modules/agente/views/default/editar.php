<form id="form-agente">
    
    <div class="form-group">
        <label>Nombre Agente<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese nombres" name="cuenta" id="cuenta" value="<?= $agente->cuenta ?>"/>
    </div>
    
      <div class="form-group">
        <label>Agente Persona<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese agentePersona" name="agente" id="agente" value="<?= $agente->agente ?>"/>
    </div>
    
     
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
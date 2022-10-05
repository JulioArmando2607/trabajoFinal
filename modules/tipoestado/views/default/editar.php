<form id="form-tipoestado">
    
    <div class="form-group">
        <label>Siglas<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombres" name="siglas" id="siglas" value="<?= $tipoestado->siglas ?>"/>
    </div>
    
      <div class="form-group">
        <label>Nombre Tipo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese Tipo" name="nombre_tipo" id="nombre_tipo" value="<?= $tipoestado->nombre_tipo ?>"/>
    </div>
    
     
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
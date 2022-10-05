<form id="form-tipodocumento">
    
 
    
      <div class="form-group">
        <label>Nombre Documento<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Nombre Documento" name="documento" id="documento" value="<?= $tipodocumento->documento ?>"/>
    </div>
    
     
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
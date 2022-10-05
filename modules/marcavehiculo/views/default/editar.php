<form id="form-marcavehiculo">
    
    <div class="form-group">
        <label>Marca Vehiculo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese Marca Vehiculo" name="nombre_marca" id="nombre_marca" value="<?= $marcavehiculo->nombre_marca ?>"/>
    </div>
    
 
     
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
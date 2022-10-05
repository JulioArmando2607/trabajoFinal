<form id="form-modulo">
    <div class="form-group">
        <label>Nombre Módulo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombre del módulo" name="nombre" id="nombre" value="<?= $modulo->nombre_opcion ?>"/>
    </div>
    <div class="form-group">
        <label>Ruta<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese ruta" name="ruta" id="ruta" value="<?= $modulo->url ?>"/>
    </div>
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
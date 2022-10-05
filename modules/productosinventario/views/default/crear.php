<form id="form-producto">

    <div class="form-group">
        <label>Nombre Producto<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombre" name="nombre" id="nombre" />
    </div>
    <div class="form-group">
        <label>Precio unitario<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese precio" name="precio" id="precio"/>
    </div>
    <div class="form-group">
        <label>Cantidad</span></label>
        <input type="number" class="form-control" placeholder="Ingrese cantidad" name="cantidad" id="cantidad"/>
    </div>
    <div class="form-group">
        <label>Medida</span></label>
        <input type="number" class="form-control" placeholder="Ingrese medida" name="medida" id="medida"/>
    </div>
    <div class="form-group">
        <label>Descripción</span></label>
        <input type="number" class="form-control" placeholder="Ingrese descripcion" name="descripcion" id="descripcion"/>
    </div>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

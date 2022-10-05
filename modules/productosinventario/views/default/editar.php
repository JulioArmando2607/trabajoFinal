<form id="form-producto">
     <div class="form-group">
        <label>Nombre<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombre" name="nombre" id="nombre" value="<?= $producto->nombre?>"/>
    </div>
    <div class="form-group">
        <label>Precio<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Ingrese precio" name="precio" id="precio" value="<?= $producto->precio?>"/>
    </div>
    <div class="form-group">
        <label>Cantidad</label>
        <input type="number" class="form-control" placeholder="Ingrese cantidad" name="cantidad" id="cantidad" value="<?= $producto->cantidad?>"/>
    </div>
    <div class="form-group">
        <label>Medida</label>
        <input type="text" class="form-control" placeholder="Ingrese medida" name="medida" id="medida" value="<?= $producto->medida?>"/>
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" class="form-control" placeholder="Ingrese descripción" name="descripcion" id="descripcion" value="<?= $producto->descripcion?>"/>
    </div>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
<form id="form-productos">
 
    <div class="form-group">
        <label>CodProducto<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese CodProducto" name="cod_producto" id="cod_producto" />
    </div>
    <div class="form-group">
        <label>Nombre Producto<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Nombre Producto" name="nombre_producto" id="nombre_producto"/>
    </div>
    <div class="form-group">
        <label>Unidad Medida<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Unidad Medida" name="unidad_medida" id="unidad_medida"/>
    </div>
 
    <hr>
    <div class="text-right">
           <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a> 
        
    </div>

</form>

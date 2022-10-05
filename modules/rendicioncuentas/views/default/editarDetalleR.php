<form id="form-edit-detalle">

    <div class="form-group">
        <label>Fecha<span class="text-danger">*</span></label>
        <input type="date" class="form-control"  name="fecha_ed" id="fecha_ed"
               value="<?= $data->fecha ?>"/>
    </div>
    <div class="form-group">
        <label>Proveedor<span class="text-danger">*</span></label>
        <input type="text" class="form-control"  name="proveedor" id="proveedor"
               value="<?= $data->proveedor ?>"/>
    </div>
    <div class="form-group">
        <label>Numero Documento<span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="nm_documento" id="nm_documento"
               value="<?= $data->nm_documento ?>"/>
    </div>
    <div class="form-group">
        <label>Concepto<span class="text-danger">*</span></label>
        <input type="text" class="form-control"   name="concepto" id="concepto"
               value="<?= $data->concepto ?>"/>
    </div>
    <div class="form-group">
        <label>Monto<span class="text-danger">*</span></label>
        <input type="number" class="form-control"  name="monto" id="monto"
               value="<?= $data->monto ?>"/>
    </div>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
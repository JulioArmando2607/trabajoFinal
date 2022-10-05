<form id="form-venta">
    <div class="form-group">
        <label>Cliente<span class="text-danger">*</span></label>
        <div class="input-group">
            <select type="text" class="form-control" name="cliente" id="cliente" style="width: 92%;">
                <option>Seleccione</option>
            </select>
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">+</button>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-7">
            <label>Producto</label>
            <select class="form-control select2" id="producto" name="producto" style="width: 100%;">
                <option value="" selected disabled>Seleccione</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p->id_producto . '|' . $p->nombre . '|' . $p->precio ?>"><?= $p->nombre ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Cantidad</label>
            <input type="number" class="form-control" min="1" id="cantidad" name="cantidad" value="1">
        </div>
        <div class="col-md-2">
            <label><></label>
            <a class="btn btn-outline-primary" id="add-producto">
                <i class="flaticon-plus"></i>
            </a>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Total</th>
            <th scope="col">Accion</th>
        </tr>
        </thead>
        <tbody id="datos_producto"></tbody>
    </table>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

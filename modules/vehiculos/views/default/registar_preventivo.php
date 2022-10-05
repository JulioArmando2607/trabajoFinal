<form id="form-vehiculos">


    <div class="form-group">
        <label>Vehiculo</label>
        <input type="text"  disabled class="form-control" placeholder="Ingrese Referencias" name="incripcion" id="incripcion" value="<?= $vehiculos->incripcion ?>" />
    </div>

    <div class="form-group">
        <label>Kilometraje recorrido</label>
        <input type="text" disabled class="form-control" placeholder="Ingrese Direccion" name="placa" id="placa"  value="20"/>
    </div>

    <div class="form-group">
        <label>Producto</label>
        <select class="form-control form-control-sm select2" id="marca_vehiculo" name="marca_vehiculo" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($marcavehiculos as $v): ?>
                <option value="<?= $v->id_marca ?>" <?= $vehiculos->id_marca == $v->id_marca ? 'selected' : '' ?>>
                    <?= $v->nombre_marca ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Cantidad</label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Ingrese Descripcion" name="descripcion" id="descripcion"  />
    </div>

    <div class="form-group">
        <label>Fecha</label>
        <input type="date" class="form-control" placeholder="Ingrese Referencias" name="incripcion" id="incripcion"  value="<?=date("Y-m-d")?>"/>
    </div>


    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
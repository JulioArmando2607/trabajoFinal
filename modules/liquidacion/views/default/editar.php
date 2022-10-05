<form id="form-editar-liqui">
    <label>Peso<span class="text-danger">*</span></label>
    <input    type="number" class="form-control" placeholder="Peso" name="peso_liquidacion" id="peso_liquidacion"   value="<?= $dato["peso"] ?>" />
    <label>Peso Exceso<span class="text-danger">*</span></label>
    <input  disabled type="number" class="form-control" placeholder="Peso Exceso" name="peso_exceso_liquidacion" id="peso_exceso_liquidacion"   value="<?= $dato["peso_exceso"] ?>" />

    <label>Reembarque<span class="text-danger">*</span></label>
    <input type="number" class="form-control" placeholder="Ingrese" name="reembarque_liquidacion" id="reembarque_liquidacion"   value="<?= $dato["reembarque"] ?>" />

    <label>Observacion Reembarque</label>
    <input type="text" class="form-control" placeholder="Observacion" name="obs_reembarque" id="obs_reembarque"   value="<?= $dato["observacion"] ?>" />

    <label>costo<span class="text-danger"></span></label>
    <input hidden="" type="number" class="form-control" placeholder="costo" name="costo_vlorliquidacion" id="costo_vlorliquidacion"   value="<?= $dato["costo"] ?>" /> 
    <input disabled="" type="number" class="form-control" placeholder="costo" name="costo_liquidacion" id="costo_liquidacion"   value="<?= $dato["costo"] ?>" /> 

    <hr>
    <button class="btn btn-primary mr-2" id="btn-actualizar-ed">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
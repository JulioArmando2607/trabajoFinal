<form id="form-ubigeos">

    <div class="row"> 
        <div class="form-group col-md-4">
            <label>Ubigeo Dep.<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. dep" name="ubdep" id="ubdep" value="<?= $ubigeo->ubigeo_departamento ?>"/>
        </div>
        <div class="form-group col-md-8">
            <label>Nombre Departamento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Departamento" name="departamento" id="departamento" value="<?= $ubigeo->nombre_departamento ?>" />
        </div>
    </div>
  
    <div class="row">
          <div class="form-group col-md-4">
        <label>Ubigeo Pro.<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. prov" name="ubprov" id="ubprov" value="<?= $ubigeo->ubigeo_provincia ?>" />
    </div>

    <div class="form-group col-md-8">
        <label>Nombre Provincia<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Provincia" name="provincia" id="provincia" value="<?= $ubigeo->nombre_provincia ?>" />
    </div>
    </div>

    <div class="row"> 
        <div class="form-group col-md-4">
            <label>Ubigeo Dist.<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. distr" name="ubdistr" id="ubdistr" value="<?= $ubigeo->ubigeo_distrito ?>" />
        </div>
        <div class="form-group col-md-8">
            <label>Nombre Distrito<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Distrito" name="distrito" id="distrito" value="<?= $ubigeo->nombre_distrito ?>" />
        </div>
    </div>
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
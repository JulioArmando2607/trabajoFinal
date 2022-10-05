<form id="form-ubigeos">

    <div class="row"> 
        <div class="form-group col-md-4">
            <label>Ubigeo Dep.<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. dep" name="ubdep" id="ubdep" />
        </div>
        <div class="form-group col-md-8">
            <label>Nombre Departamento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Departamento" name="departamento" id="departamento" />
        </div>
    </div>
  
    <div class="row">
          <div class="form-group col-md-4">
        <label>Ubigeo Pro.<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. prov" name="ubprov" id="ubprov" />
    </div>

    <div class="form-group col-md-8">
        <label>Nombre Provincia<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Provincia" name="provincia" id="provincia" />
    </div>
    </div>

    <div class="row"> 
        <div class="form-group col-md-4">
            <label>Ubigeo Dist.<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese ub. distr" name="ubdistr" id="ubdistr" />
        </div>
        <div class="form-group col-md-8">
            <label>Nombre Distrito<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"  class="form-control" placeholder="Ingrese Distrito" name="distrito" id="distrito"/>
        </div>
    </div>
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
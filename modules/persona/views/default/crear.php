<form id="form-persona">

    <div class="row">   
        <div class="form-group col-md-6">
            <label>DNI<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese DNI" name="dni" id="dni" />
        </div>
        <div class="form-group col-md-6">
            <label>Nombres<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese nombres" name="nombres" id="nombres" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Apellido Paterno<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido paterno" name="apellido_paterno" id="apellido_paterno"/>
        </div>
        <div class="form-group col-md-6">
            <label>Apellido Materno<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido materno" name="apellido_materno" id="apellido_materno"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Fecha Nacimiento</label>
                <input type="date" class="form-control form-control-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= date("Y-m-d") ?>"/>
            </div>        
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Entidad</label>
                <select class="form-control select2" id="entidad" name="entidad" style="width: 100%;">
                    <option value="" disabled selected>Seleccione</option>
                    <?php foreach ($entidad as $v) : ?>
                        <option value="<?= $v->id_entidad ?>"><?= $v->razon_social ?></option>
                    <?php endforeach; ?>
                </select>
            </div> </div>

    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label>Sexo<span class="text-danger">*</span></label>
            <select class="form-control" name="sexo" id="sexo">
                <option value="1">Masculino</option>
                <option value="0">Femenino</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Empleado<span class="text-danger">*</span></label>
            <select class="form-control" name="empleado" id="empleado">
                <option value="0">NO</option>
                <option value="1">SI</option>
            </select>
        </div>

        <div class="form-group col-md-4">
            <label>Conductor<span class="text-danger">*</span></label>
            <select class="form-control" name="conductor" id="conductor">
                <option  value="0">NO</option>             
                <option  value="1">SI</option>
               
            </select>
        </div>
    </div>
    <div id="divlicencia" class="form-group col-md-12">
            <label>Licencia<span class="text-danger">*</span></label>
              <input type="text" class="form-control" placeholder="Ingrese Licencia" name="licencia" id="licencia"/>
        </div>
    
   <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido paterno" name="telefono" id="telefono"/>
        </div>
        <div class="form-group col-md-6">
            <label>Correo<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido materno" name="correo" id="correo"/>
        </div>
    </div>
  
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

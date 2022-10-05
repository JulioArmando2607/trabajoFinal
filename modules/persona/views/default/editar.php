<!--<form id="form-persona">
    <div class="form-group">
        <label>DNI<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese DNI" name="dni" id="dni" value="<?= $persona->dni ?>"/>
    </div>
    <div class="form-group">
        <label>Nombres<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombres" name="nombres" id="nombres" value="<?= $persona->nombres ?>"/>
    </div>
    <div class="form-group">
        <label>Apellido Paterno<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese apellido paterno" name="apellido_paterno" id="apellido_paterno" value="<?= $persona->apellido_paterno ?>"/>
    </div>
    <div class="form-group">
        <label>Apellido Materno<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese apellido materno" name="apellido_materno" id="apellido_materno" value="<?= $persona->apellido_materno ?>"/>
    </div>
    <div class="form-group">
        <label>Sexo<span class="text-danger">*</span></label>
        <select class="form-control" name="sexo" id="sexo">
            <option value="1" <?= $persona->id_sexo == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="0" <?= $persona->id_sexo == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
        </select>
    </div>
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>-->

<form id="form-persona">

    <div class="row">   
        <div class="form-group col-md-6">
            <label>DNI<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese DNI" name="dni" id="dni" value="<?= $persona->dni ?>"/>
        </div>
        <div class="form-group col-md-6">
            <label>Nombres<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese nombres" name="nombres" id="nombres"  value="<?= $persona->nombres ?>" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Apellido Paterno<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido paterno" name="apellido_paterno" id="apellido_paterno"value="<?= $persona->apellido_paterno ?>"/>
        </div>
        <div class="form-group col-md-6">
            <label>Apellido Materno<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido materno" name="apellido_materno" id="apellido_materno" value="<?= $persona->apellido_materno ?>"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Fecha Nacimiento</label>
                <input type="date" class="form-control form-control-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $persona->fecha_nacimiento ?>"/>
            </div>        
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Entidad</label>
                <select class="form-control select2" id="entidad" name="entidad" style="width: 100%;">
                    <option value="" disabled selected>Seleccione</option>
                    <?php foreach ($entidad as $v) : ?>
                        <option value="<?= $v->id_entidad ?>"<?= $persona->id_entidad == $v->id_entidad ? 'selected' : '' ?>>
                        <?= $v->razon_social ?></option>
                    <?php endforeach; ?>
                </select>
            </div> 
        
        </div>
        
       

    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Sexo<span class="text-danger">*</span></label>
            <select class="form-control" name="sexo" id="sexo">
            <option value="1" <?= $persona->id_sexo == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="0" <?= $persona->id_sexo == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
        </select>
        </div>
       
        <div class="form-group col-md-6">
            <label>Conductor<span class="text-danger">*</span></label>
            <select class="form-control" name="conductor" id="conductor">
                <option value="1" <?= $persona->flg_conductor == 'SI' ? 'selected' : '' ?>>SI</option>             
                <option value="0"  <?= $persona->flg_conductor == 'NO' ? 'selected' : '' ?>>NO</option>
               
            </select>
        </div>
    </div>
    <div id="divlicencia" class="form-group col-md-12">
            <label>Licencia<span class="text-danger">*</span></label>
              <input type="text" class="form-control" placeholder="Ingrese Licencia" name="licencia" id="licencia" value=" <?= empty($empleados->licencia) ? '' : $empleados->licencia ?>  " />
        </div>
    
   <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido paterno" name="telefono" id="telefono" value="<?= $persona->telefono ?>"/>
        </div>
        <div class="form-group col-md-6">
            <label>Correo<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese apellido materno" name="correo" id="correo"  value="<?= $persona->correo ?>"/>
        </div>
    </div>
  
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

<form id="form-usuario">
    <div class="form-group">
        <label>Persona<span class="text-danger">*</span></label>
        <select class="form-control select2" id="persona" name="persona" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($persona as $p): ?>
                <option value="<?= $p->id_persona ?>"><?= $p->nombres . ' ' . $p->apellido_paterno . ' ' . $p->apellido_materno ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Area<span class="text-danger">*</span></label>
        <select class="form-control select2" id="area" name="area" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($area as $a): ?>
                <option value="<?= $a->id_area ?>"><?= $a->codigo_area . ' - ' . $a->nombre_area ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Perfil<span class="text-danger">*</span></label>
        <select class="form-control select2" id="perfil" name="perfil" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($perfil as $p): ?>
                <option value="<?= $p->id_perfil ?>"><?= $p->nombre_perfil ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="divagente" class="form-group">
        <label>Agente<span class="text-danger">*</span></label>
        <select class="form-control select2" id="agente" name="agente" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($agente as $p): ?>
                <option value="<?= $p->id_agente ?>"><?= $p->cuenta ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="divagencia" class="form-group">
        <label>Agencia<span class="text-danger">*</span></label>
        <select class="form-control select2" id="agencia" name="agencia" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($agencia as $p): ?>
                <option value="<?= $p->id_agencia ?>"><?= $p->nombre_agencia ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Correo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese correo" name="correo" id="correo"/>
    </div>
    <div class="form-group">
        <label>Usuario<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese usuario" name="usuario" id="usuario"/>
    </div>
    <div class="form-group">
        <label>Contraseña<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese contraseña" name="password" id="password"/>
    </div>
    <hr>
    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
    </div>
</form>

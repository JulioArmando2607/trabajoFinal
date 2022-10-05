<form id="form-usuario">
    <div class="form-group">
        <label>Persona<span class="text-danger">*</span></label>
        <select class="form-control select2" id="persona" name="persona" style="width: 100%;">
            <?php foreach ($persona as $p): ?>
                <option value="<?= $p->id_persona ?>" <?= $usuario->id_persona == $p->id_persona ? 'selected' : '' ?>>
                    <?= $p->nombres . ' ' . $p->apellido_paterno . ' ' . $p->apellido_materno ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Area<span class="text-danger">*</span></label>
        <select class="form-control select2" id="area" name="area" style="width: 100%;">
            <?php foreach ($area as $a): ?>
                <option value="<?= $a->id_area ?>" <?= $usuario->id_area == $a->id_area ? 'selected' : '' ?>>
                    <?= $a->codigo_area . ' - ' . $a->nombre_area ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
   
    <div class="form-group">
        <label>Perfil<span class="text-danger">*</span></label>
        <select class="form-control select2" id="perfil" name="perfil" style="width: 100%;">
            <?php foreach ($perfil as $p): ?>
                <option value="<?= $p->id_perfil ?>" <?= $usuario->id_perfil == $p->id_perfil ? 'selected' : '' ?>>
                    <?= $p->nombre_perfil ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
       <div id="divagente" class="form-group">
        <label>Agente<span class="text-danger">*</span></label>
        <select class="form-control select2" id="agente" name="agente" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($agente as $p): ?>
                <option value="<?= $p->id_agente ?>" <?= $usuario->id_agente == $p->id_agente ? 'selected' : '' ?>>
                <?= $p->cuenta ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="divagencia" class="form-group">
        <label>Agencia<span class="text-danger">*</span></label>
        <select class="form-control select2" id="agencia" name="agencia" style="width: 100%;">
            <option value="" selected disabled>Seleccione</option>
            <?php foreach ($agencia as $p): ?>
                <option value="<?= $p->id_agencia ?>" <?= $usuario->id_agencia == $p->id_agencia ? 'selected' : '' ?>>
                <?= $p->nombre_agencia?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Correo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese correo" name="correo" id="correo" value="<?= $usuario->correo ?>"/>
    </div>
    <div class="form-group">
        <label>Usuario<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese usuario" name="usuario" id="usuario" value="<?= $usuario->usuario ?>"/>
    </div>
    <div class="form-group">
        <label>Contraseña</label>
        <input type="text" class="form-control" placeholder="Ingrese contraseña" name="password" id="password"/>
    </div>
    <hr>
    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
    </div>
</form>

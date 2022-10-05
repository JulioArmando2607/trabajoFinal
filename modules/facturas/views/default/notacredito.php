<form id="form-notacredito">


    <div class="form-group">
        <label>Fecha de Emisión<span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="fechaemision" id="fechaemision" value="<?= date("Y-m-d") ?>">

    </div> 

    <div class="form-group">
        <label>Tipo de Nota de Crédito</label>
        <select class="form-control form-control-sm" id="tipo_nota_cred" name="tipo_nota_cred" >
            <?php foreach ($cod_notas as $v): ?>
                <option value="<?= $v->codigo ?>"><?= $v->descripcion ?></option>
            <?php endforeach; ?>
        </select>
    </div> 

    <div class="form-group">
        <label>Tipo Comprobante</label>
        <select class="form-control form-control-sm" id="tipo_comprobante" name="tipo_comprobante" >
            <option value="1">Boleta</option>
            <option value="2">Factura</option>
        </select>
    </div> 

    <div class="form-group">
        <label>Numero de FE respecto de la cual se emite la Nota de credito<span class="text-danger">*</span></label>
        <div class="row">
            <div class="col-md-4">   
                <select class="form-control form-control-sm" id="serie_doc" name="serie_doc" >
                    <?php foreach ($series as $v): ?>
                        <option value="<?= $v["serie"] ?>">
                            <?= $v["serie"] ?></option>

                    <?php endforeach; ?>
                </select>

            </div>

            <div class="col-md-8"> 
                <input type="text" class="form-control" placeholder="correlativo" name="correlativo" id="correlativo" />
            </div>  
        </div>
    </div>

    <div hidden="" class="form-group">
        <label>Motivo o sustento por el cual se emitirá la Nota de Crédito <span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Motivo o sustento por el cual se emitirá la Nota de Crédito" name="sustento_nota_credito" id="sustento_nota_credito" />
    </div>
    <div class="form-group">
        <label>Tipo de Documento Cliente</label>
        <select class="form-control form-control-sm" id="tipo_doc_cliente" name="tipo_doc_cliente" >
            <?php foreach ($tipo_documento as $v): ?>
                <option value="<?= $v->tipo_doc_sunat ?>"><?= $v->documento ?></option>

            <?php endforeach; ?>
        </select>

    </div> 

    <div class="form-group">
        <label>Numero Documento<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Numero" name="numero_doc" id="numero_doc" />
    </div>
    <div class="form-group">
        <label>Nombre Razon Social<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Numero" name="nombre_razon_cliente" id="nombre_razon_cliente" />
    </div>
    <div class="form-group">
        <label>Total<span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="Numero" name="total" id="total" />
    </div>

    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar">Emitir</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

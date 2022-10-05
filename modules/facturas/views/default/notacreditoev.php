<form id="form-notacredito-n">

    <input hidden="" type="text" class="form-control" placeholder="Numero" name="id_guia_ventas" id="id_guia_venta"
           value="<?= $notacredito["id_guia_venta"] ?>"/>
    <div class="form-group">
        <label>Fecha de Emisión<span class="text-danger">*</span></label>
        <input disabled type="date" class="form-control" name="fechaemision" id="fechaemision" value="<?= date("Y-m-d") ?>">
    </div>

    <div  class="form-group">
        <label>Tipo de Nota de Crédito</label>
        <select class="form-control form-control-sm" id="tipo_nota_cred" name="tipo_nota_cred">
            <?php foreach ($cod_notas as $v): ?>
                <option value="<?= $v->codigo ?>"><?= $v->descripcion ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div disabled class="form-group">
        <label>Tipo Comprobante</label>
        <input disabled type="text" class="form-control" placeholder="Numero" name="tipo_comprobante" id="tipo_comprobante"
               value="<?= $notacredito["tipo_comprobante"] ?>"/>
    </div>
    <div  class="form-group">
        <label>Numero de FE respecto de la cual se emite la Nota de credito<span class="text-danger">*</span></label>
        <div class="row">
            <div class="col-md-4">
                <input disabled type="text" class="form-control" placeholder="Numero" name="serie_doc" id="serie_doc"
                       value="<?= $notacredito["serie"] ?>"/>

            </div>

            <div class="col-md-8">
                <input  disabled type="text" class="form-control" placeholder="correlativo" name="correlativo" id="correlativo"
                       value="<?= $notacredito["correlativo"] ?>"/>
            </div>
        </div>
    </div>

    <div hidden="" class="form-group">
        <label>Motivo o sustento por el cual se emitirá la Nota de Crédito <span class="text-danger">*</span></label>
        <input type="text" class="form-control"
               placeholder="Motivo o sustento por el cual se emitirá la Nota de Crédito" name="sustento_nota_credito"
               id="sustento_nota_credito"/>
    </div>
    <div hidden class="form-group">
        <label>Tipo de Documento Cliente</label>


        <input disabled type="text" class="form-control" placeholder="tipo_doc_cliente" name="tipo_doc_cliente"
               id="tipo_doc_cliente" value="<?= $notacredito["tipo_comprobante"] ?>"/>


    </div>

    <div class="form-group">
        <label>Numero Documento<span class="text-danger">*</span></label>
        <input disabled type="text" class="form-control" placeholder="Numero" name="numero_doc" id="numero_doc"
               value="<?= $notacredito["doc_cliente"] ?>"/>
    </div>
    <div disabled class="form-group">
        <label>Nombre Razon Social<span class="text-danger">*</span></label>
        <input  disabled type="text" class="form-control" placeholder="Numero" name="nombre_razon_cliente"
               id="nombre_razon_cliente" value="<?= $notacredito["nombre_razon_cliente"] ?>"/>
    </div>
    <div   class="form-group">
        <label>Total<span class="text-danger">*</span></label>
        <input disabled type="number" class="form-control" placeholder="Numero" name="total" id="total"
               value="<?= $notacredito["total_monto"] ?>"/>
    </div>

    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-emitir">Emitir</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>

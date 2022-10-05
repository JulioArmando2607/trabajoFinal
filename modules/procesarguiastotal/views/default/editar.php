<?php

use app\modules\procesarguiastotal\bundles\ProcesarGuiasTotalAsset;

$bundle = ProcesarGuiasTotalAsset::register($this);
?>
 
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <input type="hidden" id="id_guia" value="<?= $guia->id_guia_remision ?>">
    <form class="form" id="frm-guia-remision">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Datos guía de remisión</div>
            </div>
            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?= $guia->fecha ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Traslado</label>
                        <input type="date" class="form-control form-control-sm" id="traslado" name="traslado" value="<?= $guia->fecha_traslado ?>"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Via</label>
                        <select class="form-control form-control-sm" id="via" name="via" >
                            <?php foreach ($via as $v): ?>
                                <option value="<?= $v->id_via ?>" <?= $guia->id_via == $v->id_via ? 'selected' : '' ?>>
                                    <?= $v->nombre_via ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo Via Carga</label>
                        <select class="form-control form-control-sm" id="via_tipo" name="via_tipo" >
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($via_tipo as $v): ?>
                                <option value="<?= $v->id_tipo_via_carga ?>" <?= $guia->id_tipo_via == $v->id_tipo_via_carga ? 'selected' : '' ?>>
                                    <?= $v->tipo_via_carga ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control select2" id="cliente" name="cliente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $guia->id_cliente == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Agente</label>
                        <select class="form-control select2" id="agente" name="agente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($agente as $a): ?>
                                <option value="<?= $a->id_agente ?>" <?= $guia->id_agente == $a->id_agente ? 'selected' : '' ?>>
                                    <?= $a->cuenta . ' - ' . $a->agente ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Datos origen</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remitente</label>
                        <select class="form-control select2" id="remitente" name="remitente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $guia->id_remitente == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" id="id_direccion_partida" value="<?= $guia->id_direccion_partida ?>">
                        <label>Dirección de partida</label>
                        <select class="form-control select2" id="direccion_partida" name="direccion_partida"></select>
                    </div>        
                </div>
            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Datos destino</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Destinatario</label>
                        <select class="form-control select2" id="destinatario" name="destinatario">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>" <?= $guia->id_destinatario == $d->id_entidad ? 'selected' : '' ?>>
                                    <?= $d->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" id="id_direccion_llegada" value="<?= $guia->id_direccion_llegada ?>" >
                        <label>Dirección de llegada</label>
                        <select class="form-control select2" id="direccion_llegada" name="direccion_llegada"></select>
                    </div>        
                </div>
            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Datos transportista</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Conductor</label>
                        <select class="form-control select2" id="conductor" name="conductor">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($conductor as $c): ?>
                                <option value="<?= $c["id_empleado"] ?>" <?= $guia->id_conductor == $c["id_empleado"] ? 'selected' : '' ?>>
                                    <?= $c["empleado"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vehiculo</label>
                        <select class="form-control select2" id="vehiculo" name="vehiculo">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($vehiculo as $v): ?>
                                <option value="<?= $v["id_vehiculo"] ?>" <?= $guia->id_vehiculo == $v["id_vehiculo"] ? 'selected' : '' ?>>
                                    <?= $v["vehiculo"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
            </div>

            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Otros datos</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Transportista</label>
                        <select class="form-control select2" id="transportista" name="transportista">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($transportista as $v): ?>
                                <option value="<?= $v->id_transportista ?>" <?= $guia->transportista == $v->id_transportista ? 'selected' : '' ?>>
                                    <?= $v->razon_social ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input hidden="" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="transportista" name="transportista" value="<?= $guia->transportista ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Guia remisión</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="guia_remision" name="guia_remision" value="<?= $guia->guia_remision_transportista ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Factura</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="factura" name="factura" value="<?= $guia->factura_transportista ?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Importe</label>
                        <input type="number" class="form-control form-control-sm" id="importe" name="importe" value="<?= $guia->importe_transportista ?>"/>
                    </div>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Comentario</label>
                        <textarea class="form-control form-control-sm" id="comentario" name="comentario"><?= $guia->comentario_transportista ?></textarea>
                    </div>   
                </div>
            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Detalle de remisión cliente</div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>GR-SERIE</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="grserie" name="grserie"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>GR-NUMERO</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="gr" name="gr"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>FT</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="ft" name="ft"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>O/C</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="oc" name="oc"/>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de carga</label>
                        <select class="form-control form-control-sm" id="tipo_carga" name="tipo_carga">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($tipoCarga as $t): ?>
                                <option value="<?= $t->id_tipo_carga ?>"><?= $t->siglas . '::' . $t->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="descripcion" name="descripcion"/>
                    </div>  
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-detalle-rc-edit">
                            <i class="flaticon-add-circular-button"></i>
                        </button>
                    </div>  
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                        <th scope="col">GRS</th>
                        <th scope="col">GR</th>
                        <th scope="col">FT</th>
                        <th scope="col">O/C</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Largo</th>
                        <th scope="col">Ancho</th>
                        <th scope="col">Alto</th>
                        <th scope="col">Peso Vol</th>
                        <th scope="col">Tipo carga</th>
                        <th scope="col" >Descripción</th>
                        <th scope="col"></th>
                    </tr>
                  <!--  <tr>
                        <th scope="col">GRS</th>
                        <th scope="col">GR</th>
                        <th scope="col">FT</th>
                        <th scope="col">O/C</th>
                        <th scope="col">Tipo carga</th>
                        <th scope="col" style="width: 300px;">Descripción</th>
                        <th scope="col"></th>
                    </tr>-->
                </thead>
                <tbody id="tabla-detalle-guia-rc">

                </tbody>
            </table>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Detalle de guia</div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Producto</label>
                        <select disabled=""  class="form-control form-control-sm" id="producto" name="producto">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($producto as $p): ?>
                                <option value="<?= $p->id_producto ?>"><?= $p->cod_producto . '::' . $p->nombre_producto . '::' . $p->unidad_medida ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input disabled=""  type="number" class="form-control form-control-sm" id="cantidad" name="cantidad"/>
                    </div>
                </div>
               <div class="col-md-1">
                    <div class="form-group">
                        <label>Peso(kg)</label>
                        <input disabled=""  type="number" class="form-control form-control-sm" id="peso" name="peso"/>
                    </div>
                </div>
                  <div class="col-md-1">
                    <div class="form-group">
                        <label>Largo</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="largo" name="largo"/>
                    </div>
                </div>
                  <div class="col-md-1">
                    <div class="form-group">
                        <label>Ancho</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="ancho" name="ancho"/>
                    </div>
                </div>
                  <div class="col-md-1">
                    <div class="form-group">
                        <label>Alto</label>
                        <input disabled="" type="number" class="form-control form-control-sm" id="alto" name="alto"/>
                    </div>
                </div>
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>PESO VOL</label>
                        <input disabled=""   type="number" class="form-control form-control-sm" id="pesovol" name="pesovol"/>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <button disabled=""  type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-detalle-edit">
                            <i class="flaticon-add-circular-button"></i>
                        </button>
                    </div>
                </div>
            </div>

            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Unidad</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Volumen</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="tabla-detalle-guia">

                </tbody>
            </table>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-actualizar">Actualizar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("procesarguiastotal"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



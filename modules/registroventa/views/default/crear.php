    <?php

use app\modules\registroventa\bundles\RegistroVentaAsset;

$bundle = RegistroVentaAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-registro-venta">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Datos guía de remisión</div>
            </div>
            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?=date("Y-m-d")?>"/>
                    </div>        
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>SERIE</label>
                        <input type="text"  class="form-control form-control-sm" id="serie" name="serie"  value="E001"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>FACTURA</label>
                        <input type="number" class="form-control form-control-sm" id="factura" name="factura"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control select2" id="idCliente" name="idCliente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>"><?= $d->razon_social .'   -  '. $d->numero_documento ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>VALOR VENTA</label>
                        <input type="text"  disabled class="form-control form-control-sm" id="valor_venta" name="valor_venta"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>I.G.V.</label>
                        <input type="number" disabled class="form-control form-control-sm" id="igv" name="igv"/>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>TOTAL</label>
                        <input type="text" class="form-control form-control-sm" id="total" name="total"/>
                    </div>
                </div>
<!--
                <div class="col-md-2">
                    <div class="form-group">
                        <label>FECHA DE CANCELACION</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_cancelacion" name="fecha_cancelacion" value="<?=date("Y-m-d")?>"/>
                    </div>

                </div>
-->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>MONTO DEPOSITADO</label>
                        <input type="text" class="form-control form-control-sm" id="monto_depositado" name="monto_depositado"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>MONTO DIFERENCIA</label>
                        <input type="text" disabled class="form-control  form-control-sm" id="monto_diferencia" name="monto_diferencia"/>
                    </div>
                </div>
          <!---      <div  class="col-md-2">
                    <div class="form-group">
                        <label>ESTADO</label>
                        <select class="form-control select2" id="idEstado" name="idEstado">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($estado as $a): ?>
                                <option value="<?= $a->id_estado ?>"><?= $a->nombre_estado . ' - ' . $a->nombre_estado ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>-->

            </div>

            <hr>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("registroventa"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->



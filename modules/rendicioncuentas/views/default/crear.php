<?php

use app\modules\rendicioncuentas\bundles\RendicionCuentasAsset;

$bundle =  RendicionCuentasAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->

    <form class="form" id="frm-rendicion-cuenta">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Rendidicon Cuentas</div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?=date("Y-m-d")?>" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Número Operacion</label>
                        <input type="text" class="form-control form-control-sm" id="nr_operacion" name="nr_operacion"/>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Abono Cuenta De</label>
                        <select class="form-control form-control-sm" id="abono_cuenta_de" name="abono_cuenta_de" >
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($personas as $v): ?>
                                <option value="<?= $v["id_empleado"] ?>"><?= $v["empleado"] ?></option>
                            <?php endforeach; ?>
                        </select>


                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Rinde</label>
                        <select class="form-control form-control-sm" id="rinde" name="rinde" >
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($personas as $v): ?>
                                <option value="<?= $v["id_empleado"] ?>"><?= $v["empleado"] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Importe Entregado</label>
                        <input type="number" class="form-control form-control-sm" id="importe_entregado" name="importe_entregado">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Vuelto o Reembolso</label>
                        <input type="number" class="form-control form-control-sm" id="diferencia_depo" disabled name="diferencia_depo"/>
                    </div>
                </div>

            </div>
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Detalle Rendicion de Cuentas</div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="fecha_d_rc" name="fecha_d_rc" value="<?=date("Y-m-d")?>" />

                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Proveedor</label>
                        <input type="text" class="form-control form-control-sm" id="proveedor_d_rc" name="proveedor_d_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Numero Documento</label>
                        <input type="text" class="form-control form-control-sm" id="ndocumento_d_rc" name="ndocumento_d_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Concepto</label>
                        <input type="text" class="form-control form-control-sm" id="concepto_d_rc" name="concepto_d_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Monto</label>
                        <input type="number" class="form-control form-control-sm" id="monto_d_rc" name="monto_d_rc"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="agregar-detalle-rc">
                            <i class="flaticon-add-circular-button"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="row">


            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Numero Documento</th>
                    <th scope="col">Concepto</th>
                    <th scope="col">Monto</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody id="tabla-detalle-rc">
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("rendicioncuentas"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->


<?php

use app\modules\rendicioncuentas\bundles\RendicionCuentasAsset;

$bundle =  RendicionCuentasAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <input type="hidden" id="id_rendicion_cuentas" value="<?= $rd->id_rendicion_cuentas ?>">
    <form class="form" id="frm-rendicion-cuenta-edit">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Rendidicon Cuentas</div>
            </div>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="fecha" name="fecha" value="<?= $rd->fecha ?>"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Número Operacion</label>
                    <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="nr_operacion" name="nr_operacion" value="<?= $rd->nr_operacion ?>"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Abono Cuenta De</label>
                    <select class="form-control select2" id="abono_cuenta_de" name="abono_cuenta_de">
                        <option value="" disabled selected>Seleccione</option>
                        <?php foreach ($personas as $c): ?>
                            <option value="<?= $c["id_empleado"] ?>"  <?= empty($rd->id_abono_cuenta_de) ? '' : $rd->id_abono_cuenta_de == $c["id_empleado"] ? 'selected' : '' ?>>
                                <?= $c["empleado"] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>


    </div>
      <div>
          <div class="row">


              <div class="col-md-4">
                  <div class="form-group">
                      <label>Rinde</label>
                      <select class="form-control select2" id="rinde" name="rinde">
                          <option value="" disabled selected>Seleccione</option>
                          <?php foreach ($personas as $c): ?>
                              <option value="<?= $c["id_empleado"] ?>"  <?= empty($rd->rinde) ? '' : $rd->rinde == $c["id_empleado"] ? 'selected' : '' ?>>
                                  <?= $c["empleado"] ?>
                              </option>
                          <?php endforeach; ?>
                      </select>

                  </div>
              </div>


              <div class="col-md-4">
                  <div class="form-group">
                      <label>Importe Entregado</label>
                      <input type="text" class="form-control form-control-sm" id="importe_entregado" name="importe_entregado" value="<?= $rd->importe_entregado ?>"/>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label>Diferencia Depositar Reembolsar</label>
                      <input type="text" class="form-control form-control-sm" id="diferencia_depo" name="diferencia_depo" value="<?= $rd->diferencia_depositar_reembolsar ?>"/>
                  </div>
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
                        <input type="date" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="fecha_rc" name="fecha_rc" value="<?=date("Y-m-d")?>" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Proveedor</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="proveedor_rc" name="proveedor_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Numero Documento</label>
                        <input type="number" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="nm_documento_rc" name="nm_documento_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Concepto</label>
                        <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control form-control-sm" id="concepto_rc" name="concepto_rc"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Monto</label>
                        <input type="number" class="form-control form-control-sm" id="monto_rc" name="monto_rc"/>
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
                <tbody id="tabla-detalle-rendicioncuenta-ed">

                </tbody>
            </table>


        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-actualizar">Actualizar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("rendicioncuentas"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->


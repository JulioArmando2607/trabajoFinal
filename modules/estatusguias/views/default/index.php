<?php

use app\modules\estatusguias\bundles\EstatusGuiasAsset;

$bundle = EstatusGuiasAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-guia-remision">
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Reporte Guia</div>
            </div>
            <div class="row">


                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" class="form-control form-control-sm" id="fechaInicio" name="fechaInicio" value="<?= date("Y-m-d") ?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_fin" name="fecha_fin" value="<?= date("Y-m-d") ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control select2" id="cliente" name="cliente">
                            <option value="" disabled selected>Seleccione</option>
                            <?php foreach ($rem_des_client as $d): ?>
                                <option value="<?= $d->id_entidad ?>"><?= $d->razon_social ?> <?= $d->numero_documento ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Estado</label>

                    <select id="estado" class="form-control form-control-sm" name="estado">

                        <option value="0" selected>Todos</option>
                        <?php foreach ($estados as $e) : ?>
                            <option value="<?= $e->id_estado ?>"><?= $e->nombre_estado ?>  </option>
                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7"   onclick="listaresportes()">
                            <i class="flaticon-search-1 circular-button"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-light-success btn-sm mt-7"  onclick="funcionDescargarReporte()" >
                            <i class="icon-xl fas fa-file-excel "></i>
                        </button>
                    </div>
                </div>


            </div>

            <hr>


            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-estatusguias"></div>


        </div>


        <!---->
    </form>
    <!--end::Form-->
</div>
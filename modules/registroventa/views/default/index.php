<?php

use app\modules\registroventa\bundles\RegistroVentaAsset;

$bundle = RegistroVentaAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de registro venta </h3>
        </div>
        <div class="card-toolbar">

            <!--begin::Button-->
            <a href="registroventa/default/crear" class="btn btn-primary">
                <i class="text-white flaticon-file-1"></i>
                Registro Venta
            </a>

            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Search Form-->
        <!--begin::Search Form-->
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="input-icon">
                        <input type="month" name="fecha_liquidacion" id="fecha_liquidacion" class="form-control"
                               val="2021-12-00">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="input-icon">

                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-registro-venta-buscar" />
                        <span>
                            <i class="flaticon2-search-1 text-muted"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Search Form-->
        <!--end: Search Form-->
        <!--begin: Datatable-->
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-registro-venta"></div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->
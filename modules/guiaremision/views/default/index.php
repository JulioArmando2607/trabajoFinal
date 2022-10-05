<?php

use app\modules\guiaremision\bundles\GuiaremisionAsset;

$bundle = GuiaremisionAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de Guía Remisión</h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <div class="col-md-4">
                <input type="month" name="fecha_exportar" id="fecha_exportar" class="form-control"
                       val="2021-12">

            </div>
            <a  onclick="funcionDescargarExcel()" class="btn btn-light-success mr-5"  >
                <i  class="icon-xl fas fa-file-excel "></i>
                Total Guias
            </a>
            
             <a href="guiaremision/default/crear" class="btn btn-primary">
                <i class="text-white flaticon-file-1"></i>
                Registrar Guía Remisión
            </a>
            <!--end::Button-->
        </div>
        
    </div>
    <div class="card-body">
        <!--begin: Search Form-->
        <!--begin::Search Form-->
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-guia-remision-buscar" />
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
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-guia-remision"></div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->
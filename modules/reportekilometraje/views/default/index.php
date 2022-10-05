<?php

use app\modules\reportekilometraje\bundles\ReporteKilometrajeAsset;

$bundle = ReporteKilometrajeAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-guia-remision">
        <div class="card-body">

            <hr>

            <!-- <div class="mb-7">
                     <div class="row align-items-center">

                         <div class="col-md-9">
                             <div class="input-icon">
                                 <input type="text" class="form-control" placeholder="Buscar..." id="tabla-reportekilometraje-buscar" />
                                 <span>
                                 <i class="flaticon2-search-1 text-muted"></i>
                                 </span>
                             </div>
                         </div>

                     </div>
                 </div>--->

            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-reportekilometraje"></div>


        </div>


        <!---->
    </form>
    <!--end::Form-->
</div>
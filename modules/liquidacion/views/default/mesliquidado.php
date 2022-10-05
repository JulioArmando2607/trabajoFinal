<?php

use app\modules\liquidacion\bundles\LiquidacionAsset;

$bundle = LiquidacionAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->

    <div class="card-body">
        <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
            <div class="alert-text font-weight-bold">Mes Liquidado</div>
        </div>


        <hr>
        <input type="text" hidden id="idEntidad" value="<?=$idEntidad?>">
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <input type="month" name="fecha_liquidacion" id="fecha_liquidacion" class="form-control"
                           val="2021-12">

                </div>
                <div class="col-md-6">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-liquidado-buscar"/>
                        <span>
                            <i class="flaticon2-search-1 text-muted"></i>
                        </span>
                    </div>
                </div>


                <div class="col-md-1">
                    <label> SubTotal: </label> <label id="subtotaliqui"> </label>


                </div>
                <div class="col-md-1">
                    <label> Igv: </label> <label id="igvliqui"> </label>

                </div>
                <div class="col-md-1">

                    <label> Tsotal: </label> <label id="totalliqui"> </label>

                </div>
                <div id="id_divsi_not" class="col-md-1">
                    <button onclick="funcionDescargarMesLiquidado()"
                       class="btn btn-light-success mr-5">
                        <i class="icon-xl fas fa-file-excel "></i>

                    </button>
                </div>
            </div>
        </div>
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-liquidado"></div>


    </div>


    <!--onclick="funcionLiquidar()" -->
    <div class="card-footer text-right">


        <a href="<?= Yii::$app->urlManager->createUrl("liquidacion"); ?>" class="btn btn-secondary">Cancelar</a>
    </div>
    <!--end::Form-->
</div>

<!--end::Card-->



<?php

use app\modules\liquidacion\bundles\LiquidacionAsset;

$bundle = LiquidacionAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->

    <div class="card-body">
        <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
            <div class="alert-text font-weight-bold">Liquidar</div>
        </div>


        <hr>
        <input hidden id="idEntidad" value="<?= $idEntidad ?>">
        <input hidden id="fechali" value="<?= $mesc ?>">
        <div class="mb-7">
            <div class="row align-items-center">

                <div class="col-md-8">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-liquidacions-buscar"/>
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
                    <a href="<?= Yii::$app->urlManager->createUrl("liquidacion/default/liquidacion-entidad/" . $idEntidad); ?>"
                       class="btn btn-light-success mr-5">
                        <i class="icon-xl fas fa-file-excel "></i>

                    </a>
                </div>
            </div>
        </div>
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-liquidacions"></div>


    </div>


    <!--onclick="funcionLiquidar()" -->
    <div class="card-footer text-right">
        <button class="btn btn-primary mr-2" id="button-liquida-guardar">Liquidar-Enviar-Correo</button>

        <a href="<?= Yii::$app->urlManager->createUrl("liquidacion"); ?>" class="btn btn-secondary">Cancelar</a>
    </div>
    <!--end::Form-->
</div>

<!--end::Card-->



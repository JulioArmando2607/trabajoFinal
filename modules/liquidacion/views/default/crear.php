<?php

use app\modules\liquidacion\bundles\LiquidacionAsset;

$bundle = LiquidacionAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
     
        <div class="card-body">
            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1" >
                <div class="alert-text font-weight-bold">Liquidacion Entidad</div>
            </div>
            <div class="row">

                <input type="hidden" id="idEntidad" value="<?= $entidad ?>">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" class="form-control form-control-sm" id="fechaInicio" name="fechaInicio" value="<?= date("Y-m-d") ?>"/>
                    </div>        
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="date" class="form-control form-control-sm" id="fecha_fin" name="fecha_fin" value="<?= date("Y-m-d") ?>"/>
                    </div>        
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-icon btn-primary btn-sm mt-7"   onclick="listar()">
                            <i class="flaticon-search-1 circular-button"></i>
                        </button>
                    </div>
                </div>

            </div>

            <hr>

            <div class="mb-7">
                <div class="row align-items-center">

                    <div class="col-md-9">
                        <div class="input-icon">
                            <input type="text" class="form-control" placeholder="Buscar..." id="tabla-liquidacion-buscar" />
                            <span>
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
                    </div>
                 
                    <div class="col-md-1">
                   <label> SubTotal: </label> <label  id="subtotaliquiini"> </label>
                    

                    </div>
                    <div class="col-md-1">
                        <label> Igv: </label> <label  id="igvliquiini"> </label>
                      
                    </div>
                       <div class="col-md-1">

                        <label> Total: </label> <label  id="totalliquiini"> </label>
                     
                    </div>
                </div>
            </div>
            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-liquidacion"></div>


        </div>


        <div class="card-footer text-right">
            
            <button  class="btn btn-primary mr-2" id="button-liquida">Liquidar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("liquidacion"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    
    <!--end::Form-->
</div>
<!--end::Card-->



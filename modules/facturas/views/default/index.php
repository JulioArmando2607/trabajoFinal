<?php

use  app\modules\facturas\bundles\FacturasAsset;

$bundle = FacturasAsset::register($this);
?>
<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="example-preview">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_boletas">Boletas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_facturas">Facturas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_nota_credito">Notas de Credito</a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_boletas" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-boletas-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                         <!--   <button id="modal-ncredito" class="btn btn-primary">
                                <i class="text-white flaticon-avatar"></i>
                                Nota de Fac
                            </button>-->
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-boletas"></div>
                    <!--end: Datatable-->
                </div>
                <div class="tab-pane fade" id="tab_facturas" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-facturas-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                          <!---  <button id="modal-perfil" class="btn btn-primary">
                                <i class="text-white flaticon-network"></i>
                                Registrar Perfil
                            </button>-->
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-facturas" style="width: 100%;"></div>
                    <!--end: Datatable-->
                </div>
                
                <div class="tab-pane fade" id="tab_nota_credito" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-notascredito-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                           <button id="modal-ncredito" class="btn btn-primary">
                                <i class="text-white flaticon-avatar"></i>
                                Nota de Credito
                            </button>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-notascredito"></div>
                    <!--end: Datatable-->
                </div>
            </div>
        </div>
    </div>
</div>
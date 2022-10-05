<?php

use app\modules\seguridad\bundles\SeguridadAsset;

$bundle = SeguridadAsset::register($this);
?>
<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="example-preview">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_usuario">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_perfil">Perfiles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_modulo">Modulos</a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_usuario" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-usuario-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <button id="modal-usuario" class="btn btn-primary">
                                <i class="text-white flaticon-user-add"></i>
                                Registrar Usuario
                            </button>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-usuario"></div>
                    <!--end: Datatable-->
                </div>
                <div class="tab-pane fade" id="tab_perfil" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-perfil-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <button id="modal-perfil" class="btn btn-primary">
                                <i class="text-white flaticon-network"></i>
                                Registrar Perfil
                            </button>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-perfil" style="width: 100%;"></div>
                    <!--end: Datatable-->
                </div>
                <div class="tab-pane fade" id="tab_modulo" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-modulo-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <button id="modal-modulo" class="btn btn-primary">
                                <i class="text-white flaticon-browser"></i>
                                Registrar Módulo
                            </button>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-modulo"></div>
                    <!--end: Datatable-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php

use app\modules\procesarguiastotal\bundles\ProcesarGuiasTotalAsset;

$bundle = ProcesarGuiasTotalAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label"></h3>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label> Ultima Guia : </label> <label  id="ultimaguia"> Ultima Guia</label>


            </div>        
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label> Solicitud Pendiente Guias</label>

                <select class="form-control form-control-sm" id="pendguias" name="pendguias" >
                    <option value=""   selected>todos</option>
                    <?php foreach ($result as $v): ?>
                        <option value="<?= $v['nm_solicitud'] ?>"><?= $v['nm_solicitud'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>        
        </div>

       <!-- <div class="col-md-3">
            <div class="form-group">
                <label>Fecha</label>
                <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?= date("Y-m-d") ?>"/>
            </div>        
        </div>-->
        <div class="card-toolbar">
            <!--begin::Button-->


            <a onclick="funcionProcesarPend()" class="btn btn-primary">
                <i class="text-white flaticon-file-1"></i>
                Procesar Guias
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
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-procesarguiastotal-buscar" />
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
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-procesarguiastotal"></div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->
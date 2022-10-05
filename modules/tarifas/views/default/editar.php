<?php

use app\modules\tarifas\bundles\TarifasAsset;

$bundle = TarifasAsset::register($this);
?>

<div class="card card-custom gutter-b example example-compact">
    <div class="card-body">
        <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
            <div class="alert-text font-weight-bold">  ENTIDAD  </div>
        </div>
        <input type="hidden" class="form-control" name="entidad_edit" id="entidad_edit" value="<?= $tarifaEnt->id_entidad ?>" >
          <div class="row">
            
            <div class="col-md-9">
             <input disabled="" class="form-control" name="entidadess" id="entidadess" value="<?= $tarifaentidad["razon_social"] ?>" >
            </div>
              
          <!-- <div  class="col-md-3" >
               <div class="col-md-12" >
                <div class="form-group">
                    <select class="form-control select2" id="tipotarifa" name="tipotarifa">
                        <option value="" disabled selected>Seleccione Tipo</option>
                       
                    </select>
                </div>
            </div>

            </div> -->
        
           
        </div>
        
     
        <div class="example" id="divtabs_ed">

            <div class="example-preview">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="aereo-tab" data-toggle="tab" href="#aereo">
                            <span class="nav-icon">
                                <i class="flaticon2-chat-1"></i>
                            </span>
                            <span class="nav-text">AEREO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="terrestre-tab" data-toggle="tab" href="#terrestre" aria-controls="terrestre">
                            <span class="nav-icon">
                                <i class="flaticon2-layers-1"></i>
                            </span>
                            <span class="nav-text">TERRESTRE</span>
                        </a>
                    </li>


                </ul>
                <div class="tab-content mt-5" id="myTabContent">
                    <div class="tab-pane fade active show" id="aereo" role="tabpanel" aria-labelledby="aereo-tab">

                        <div class="example">
                            <div class="example-preview">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tarifageneralaereo-tab" data-toggle="tab" href="#tarifageneralaereo">
                                            <span class="nav-icon">
                                                <i class="flaticon2-chat-1"></i>
                                            </span>
                                            <span class="nav-text">TARIFA GENERAL AEREO</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tarifarefaereo-tab" data-toggle="tab" href="#tarifarefaereo" aria-controls="tarifarefaereo">
                                            <span class="nav-icon">
                                                <i class="flaticon2-layers-1"></i>
                                            </span>
                                            <span class="nav-text">TARIFA REFRIGERADA Y VACUNAS</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="cargadificilmanejoaereo-tab" data-toggle="tab" href="#cargadificilmanejoaereo" aria-controls="cargadificilmanejoaereo">
                                            <span class="nav-icon">
                                                <i class="flaticon2-rocket-1"></i>
                                            </span>
                                            <span class="nav-text">CARGA MERCANCIA PELIGROSA Y DIFICL</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-5" id="myTabContent">
                                    <div class="tab-pane fade active show" id="tarifageneralaereo" role="tabpanel" aria-labelledby="tarifageneralaereo-tab">

                                        <div >
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo" id="costo" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_c"]) ? '' : $tarifaentidad["tarifa_m_a_c"] ?>" >
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv" id="igv" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_igv"]) ? '' : $tarifaentidad["tarifa_m_a_igv"] ?>" >
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="igv_total_a" id="igv_total_a" type="number"  value="<?= empty($tarifaentidad["tarifa_m_a_total"]) ? '' : $tarifaentidad["tarifa_m_a_total"] ?>" >
                                                </div>

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_a_base_general" id="tarifa_peso_a_base_general" type="number" value="<?= empty($tarifaentidad["peso_a_base_general"]) ? '' : $tarifaentidad["peso_a_base_general"] ?>">
                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="tarifarefaereo" role="tabpanel" aria-labelledby="tarifarefaereo-tab">
                                        <div >
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo" id="costo_t_a_ref" type="number"  value="<?= empty($tarifaentidad["tarifa_m_a_c_ref"]) ? '' : $tarifaentidad["tarifa_m_a_c_ref"] ?>" >
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv" id="igv_t_a_ref" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_igv_ref"]) ? '' : $tarifaentidad["tarifa_m_a_igv_ref"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="total_t_a_ref" id="total_t_a_ref" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_total_ref"]) ? '' : $tarifaentidad["tarifa_m_a_total_ref"] ?>">
                                                </div>

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_a_base_ref" id="tarifa_peso_a_base_ref" type="number" value="<?= empty($tarifaentidad["peso_a_base_ref"]) ? '' : $tarifaentidad["peso_a_base_ref"] ?>">
                                                </div>


                                            </div>

                                            <!--<div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                                                <div class="alert-text font-weight-bold">PROVINCIAS</div>
                                            </div>

                                            <div class="mb-7">
                                                <div class="row align-items-center">

                                                    <div class="col-md-10">
                                                        <div class="input-icon">
                                                            <input type="text" class="form-control" placeholder="Buscar..." id="tabla-provincias-buscar" />
                                                            <span>
                                                                <i class="flaticon2-search-1 text-muted"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="input-icon">

                                                            <button onclick="funcionAgregarPro()" class="btn btn-primary">
                                                                <i class="text-white flaticon-graphic-1"></i>
                                                                Registrar Provincia
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <!-- <div class="datatable datatable-bordered datatable-head-custom" id="tabla-provincias"></div>-->


                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="cargadificilmanejoaereo" role="tabpanel" aria-labelledby="cargadificilmanejoaereo-tab">
                                        <div >
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo_t_a_pel" id="costo_t_a_pel" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_c_pel"]) ? '' : $tarifaentidad["tarifa_m_a_c_pel"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv_t_a_pel" id="igv_t_a_pel" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_igv_pel"]) ? '' : $tarifaentidad["tarifa_m_a_igv_pel"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="total_t_a_pel" id="total_t_a_pel" type="number" value="<?= empty($tarifaentidad["tarifa_m_a_total_pel"]) ? '' : $tarifaentidad["tarifa_m_a_total_pel"] ?>">
                                                </div>

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_a_base_pel" id="tarifa_peso_a_base_pel" type="number" value="<?= empty($tarifaentidad["peso_a_base_pel"]) ? '' : $tarifaentidad["peso_a_base_pel"] ?>">
                                                </div>

                                            </div>

                                            <!--<div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                                                <div class="alert-text font-weight-bold">PROVINCIAS</div>
                                            </div>

                                            <div class="mb-7">
                                                <div class="row align-items-center">

                                                    <div class="col-md-10">
                                                        <div class="input-icon">
                                                            <input type="text" class="form-control" placeholder="Buscar..." id="tabla-provincias-buscar" />
                                                            <span>
                                                                <i class="flaticon2-search-1 text-muted"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="input-icon">

                                                            <button onclick="funcionAgregarPro()" class="btn btn-primary">
                                                                <i class="text-white flaticon-graphic-1"></i>
                                                                Registrar Provincia
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <!-- <div class="datatable datatable-bordered datatable-head-custom" id="tabla-provincias"></div>-->


                                        </div>

                                    </div>
                                </div>



                            </div>
                            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                                <div class="alert-text font-weight-bold">PROVINCIAS</div>
                            </div>

                            <div class="mb-7">
                                <div class="row align-items-center">

                                    <div class="col-md-10">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" placeholder="Buscar..." id="tabla-provincias-buscar" />
                                            <span>
                                                <i class="flaticon2-search-1 text-muted"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-icon">

                                            <button onclick="funcionAgregarEPro()" class="btn btn-primary">
                                                <i class="text-white flaticon-graphic-1"></i>
                                                Registrar Provincia
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-provincias">


                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary mr-2" onclick="funcionEditar(<?= empty($tarifaentidad["id_tarifa"]) ? '' : $tarifaentidad["id_tarifa"] ?>)">GuardarE</button>
                                <a href="<?= Yii::$app->urlManager->createUrl("tarifas"); ?>" class="btn btn-secondary">Cancelar</a>
                            </div>

                        </div>


                    </div>
                    <div class="tab-pane fade" id="terrestre" role="tabpanel" aria-labelledby="terrestre-tab">

                        <div class="example">


                            <div class="example-preview">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tarifageneralterrestre-tab" data-toggle="tab" href="#tarifageneralterrestre">
                                            <span class="nav-icon">
                                                <i class="flaticon2-chat-1"></i>
                                            </span>
                                            <span class="nav-text">TARIFA GENERAL</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tarifarefterrestre-tab" data-toggle="tab" href="#tarifarefterrestre" aria-controls="tarifarefterrestre">
                                            <span class="nav-icon">
                                                <i class="flaticon2-layers-1"></i>
                                            </span>
                                            <span class="nav-text">TARIFA REFRIGERADA</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="cargadificilmanejterrestre-tab" data-toggle="tab" href="#cargadificilmanejterrestre" aria-controls="cargadificilmanejterrestre">
                                            <span class="nav-icon">
                                                <i class="flaticon2-rocket-1"></i>
                                            </span>
                                            <span class="nav-text">CARGA MERCANCIA PELIGROSA Y DIFICIL MANEJO</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-5" id="myTabContent">
                                    <div class="tab-pane fade active show" id="tarifageneralterrestre" role="tabpanel" aria-labelledby="tarifageneralterrestre-tab">
                                        <div >
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo_t_c_p_d" id="costo_t_c_p_d" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_costo"]) ? '' : $tarifaentidad["tarifa_m_t_costo"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv_t_c_p_d" id="igv_t_c_p_d" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_igv"]) ? '' : $tarifaentidad["tarifa_m_t_igv"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="total_t_c_p_d" id="total_t_c_p_d" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_total"]) ? '' : $tarifaentidad["tarifa_m_t_total"] ?>">
                                                </div>

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_t_base_general" id="tarifa_peso_t_base_general" type="number" value="<?= empty($tarifaentidad["peso_t_base_general"]) ? '' : $tarifaentidad["peso_t_base_general"] ?>">
                                                </div>


                                            </div>


                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tarifarefterrestre" role="tabpanel" aria-labelledby="tarifarefterrestre-tab">

                                        <div >
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo_t_c_p_d" id="tarifa_m_t_costo_ref" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_costo_ref"]) ? '' : $tarifaentidad["tarifa_m_t_costo_ref"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv_t_c_p_d" id="tarifa_m_t_igv_ref" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_igv_ref"]) ? '' : $tarifaentidad["tarifa_m_t_igv_ref"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="total_t_c_p_d" id="tarifa_m_t_total_ref" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_total_ref"]) ? '' : $tarifaentidad["tarifa_m_t_total_ref"] ?>">
                                                </div>

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_t_base_ref" id="tarifa_peso_t_base_ref" type="number" value="<?= empty($tarifaentidad["peso_t_base_ref"]) ? '' : $tarifaentidad["peso_t_base_ref"] ?>">
                                                </div>

                                            </div>


                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="cargadificilmanejterrestre" role="tabpanel" aria-labelledby="cargadificilmanejterrestre-tab"> 
                                        <div>
                                            <div class="form-row">

                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">COSTO</label>
                                                    <input class="form-control" name="costo_t_c_p_d" id="tarifa_m_t_c_pel" type="number"  value="<?= empty($tarifaentidad["tarifa_m_t_c_pel"]) ? '' : $tarifaentidad["tarifa_m_t_c_pel"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">IGV</label>
                                                    <input class="form-control" name="igv_t_c_p_d" id="tarifa_m_t_igv_pel" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_igv_pel"]) ? '' : $tarifaentidad["tarifa_m_t_igv_pel"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">TOTAL</label>

                                                    <input class="form-control" name="total_t_c_p_d" id="tarifa_m_t_total_pel" type="number" value="<?= empty($tarifaentidad["tarifa_m_t_total_pel"]) ? '' : $tarifaentidad["tarifa_m_t_total_pel"] ?>">
                                                </div>
                                                <div class="form-group input-group-sm col-md-3">
                                                    <label class="font-small">Peso base </label>

                                                    <input class="form-control" name="tarifa_peso_t_base_pel" id="tarifa_peso_t_base_pel" type="number" value="<?= empty($tarifaentidad["peso_t_base_pel"]) ? '' : $tarifaentidad["peso_t_base_pel"] ?>">
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                            <div class="alert-text font-weight-bold">PROVINCIAS</div>
                        </div>

                        <div class="mb-7">
                            <div class="row align-items-center">

                                <div class="col-md-10">
                                    <div class="input-icon">
                                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-provincias-terrestre-buscar" />
                                        <span>
                                            <i class="flaticon2-search-1 text-muted"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-icon">

                                        <button onclick="funcionAgregarProETerrestre()" class="btn btn-primary">
                                            <i class="text-white flaticon-graphic-1"></i>
                                            Registrar Provincia
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-provincias-terrestre">


                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary mr-2" onclick="funcionEditar(<?= $tarifaEnt->id_tarifa ?>)">Guardar</button>
                            <a href="<?= Yii::$app->urlManager->createUrl("tarifas"); ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
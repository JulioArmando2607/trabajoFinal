<?php

use app\modules\dashboard\bundles\DashboardAsset;

$bundle = DashboardAsset::register($this);
?>

<body>
    <div class="">


        <div class="row">

            <div class="col-lg-3">
                <div class="card card-custom gutter-b">                     
                    <div id="spark1" style="min-height: 360px;">
                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="card card-custom gutter-b">
                    <div id="spark2" style="min-height: 360px;">
                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="card card-custom gutter-b">
                    <div id="spark3" style="min-height: 360px;">
                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="card card-custom gutter-b">
                    <div id="spark4" style="min-height: 360px;">
                    </div>

                </div>

            </div>
            <div class="col-lg-6">
               
                <div class="card card-custom gutter-b">
                    <div id="chart1" style="min-height: 360px;">
                    </div>

                </div>

            </div>
           
             <div class="col-lg-12">
               
                <div class="card card-custom gutter-b">
                      <div id="chartdiv1" style="width: 100%;height: 600px;"></div>
                </div>

            </div>
            <!---<div   id="chartdiv" style="width: 500px;height: 360px;"></div>
-->
        
          
        </div>
    </div>
</body>

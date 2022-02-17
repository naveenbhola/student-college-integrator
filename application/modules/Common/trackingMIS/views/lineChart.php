<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="showView">
            <div style="float:left" >
                <h6>
                    <div class='dateRangeLineChart'></div>
                    <div class='lineChartHeading'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="square"></span >&nbsp;&nbsp;<span id="line_heading"><?php
                            if(isset($pageDetails['LINE_CHART']['heading'])){
                                $heading = $pageDetails['LINE_CHART']['heading'];
                            } else {
                                if(isset($tileNames) && ($actionName == 'engagement' || $actionName == 'traffic')){
                                    $heading = $tileNames[$pivotName];
                                } else {
                                    $heading = ucfirst($pivotName);
                                }
                            }
                            echo $heading;
                            ?></span></div>
                </h6>
                <h6>
                <div class='dateRangeLineChart1' style="display: none;"></div>
                    <div class='lineChartHeading1' style="display: none;">&nbsp&nbsp&nbsp&nbsp<span class="square1"></span>&nbsp&nbsp<span id="line_heading1"><?php
                            if(isset($pageDetails['LINE_CHART']['heading'])){
                                echo $pageDetails['LINE_CHART']['heading'];
                            } else {
                                echo ucfirst($pivotName);
                            }
                            ?></span></div>
                </h6>
            </div>
            <div style="float:right">
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100 bgcolor" type="button" id="daily" >
                            Day
                        </button>
                    </div> 
                </div>

                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default  fixed_width_100" type="button" id="weekly" >
                            Week
                        </button>      
                    </div>  
                </div>

                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100" type="button" id="monthly" >
                            Month
                        </button> 
                    </div>
                </div>
            </div>
        </div>
        <br><br><br>
        <div class="col-md-9 col-sm-9 col-xs-12" style="width:100%">
            <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
            <div style="width: 90%;" id='lineChartDiv'>
                <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
            </div>
            <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 bg-transparent">
        <div class="clearfix"></div>
        <!--   </div> -->
    </div>
</div>
<div id="message" class="message" >
<h2>No record found for selected criteria.</h2>
</div>


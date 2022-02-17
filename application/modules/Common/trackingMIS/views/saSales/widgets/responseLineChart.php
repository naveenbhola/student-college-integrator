<div id = "responseLineChartContainer" class="col-md-6 col-sm-6 col-xs-12" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Responses
            <br><small >Total Responses </small><small  style="margin-left:0px;" id="totalResponseTxt"></small>
                <small>RMC Responses   </small><small  style="margin-left:0px;" id="rmcResponseTxt"></small></h2>
            <div class="clearfix"></div>
            <span class="totalresponse" style="position:absolute;top:10px;right:5px;"></span>
        </div>
        <div class="x_content"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
            <canvas id="responseLineChart" style="width: 453px; height: 226px;" ></canvas>
        </div>
        <h2 id="noResLineChart" style="display:none;position: absolute;top:160px;left:35%;">No records found</h2>
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "loader_response"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>
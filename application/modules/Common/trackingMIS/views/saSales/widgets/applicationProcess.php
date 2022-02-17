<?php
$id = 'applicationProcess';
?>
<div id = "applicationProcessContainer" class="col-md-12 container" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Application Process: (<span class="applicationProcessStudentCount"></span>) <small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
            <div class="col-md-4">
                <div style="width:200px;margin-left:50px;">
                    <canvas id="applicationProcessDonut"></canvas>
                </div>
            </div>
            <div class="col-md-7" style="padding:35px 10px;">
                <div class="x_title" style="padding:0px;">
                    <h2>Total students: (<span class="applicationProcessStudentCount"></span>)</h2>
                    <div class="clearfix"></div>
                </div>
                <div id="applicationProcessDonutLegends"></div>
            </div>
        </div>
        
        <div class="col-md-12 col-xs-12 bg-col aplProc" id="AcceptedStdDtl" style="display: none;"></div>
        <div class="col-md-12 col-xs-12 bg-col aplProc" id="SubmittedStdDtl" style="display: none;"></div>
        <div class="col-md-12 col-xs-12 bg-col aplProc" id="RejectedStdDtl" style="display: none;"></div>
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>
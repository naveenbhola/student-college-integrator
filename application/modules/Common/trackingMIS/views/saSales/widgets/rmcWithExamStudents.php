<?php
$id = 'rmcExamStudents';
?>
<div id = "rmcExamStudentsContainer" class="col-md-12 container" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Students in apply loop : (<span class="rmcExamStudentCount"></span>) <small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
            <div class="col-md-4">
                <div style="width:200px;margin-left:50px;">
                    <canvas id="rmcExamDonut"></canvas>
                </div>
            </div>
            <div class="col-md-5" style="padding:35px;">
                <div class="x_title" style="padding:0px;">
                    <h2>Total students: (<span class="rmcExamStudentCount"></span>)</h2>
                    <div class="clearfix"></div>
                </div>
                <div id="rmcExamDonutLegends" style="padding: 0px 60px 20px 10px;"></div>
            </div>
        </div>
        
        
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>

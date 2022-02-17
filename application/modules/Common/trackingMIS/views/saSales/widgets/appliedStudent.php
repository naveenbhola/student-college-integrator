<div id = "appliedStudentContainer" class="col-md-12 container" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Applied Student: (<span class="appliedStudentCount">4</span>) <small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
            <div class="col-md-7" style="">
                <div class="" style="padding:0px;">
                    <h2>Total students: (<span class="appliedStudentCount">4</span>)</h2>
                    <div class="clearfix"></div>
                </div>
                <div id="appliedStudentLegends">
                    <ul>
                    <li class="apldStdLgnd saDonutLegend" linkfor="visaStdDtl" hideclass="apldStd" ><i class="fa fa-square" style="color:#3498DB"></i>Visa : <span id="visaCount">1</span></li>
                    <li class="apldStdLgnd saDonutLegend" linkfor="admittedStdDtl" hideclass="apldStd" ><i class="fa fa-square" style="color:#26B99A"></i>Admitted : <span id="admittedCount">1</span></li>
                    </ul>
                </div>
            </div>
        </div>        
    <div class="col-md-12 col-xs-12 bg-col apldStd" id="visaStdDtl" style="display: none;"></div>
    <div class="col-md-12 col-xs-12 bg-col apldStd" id="admittedStdDtl" style="display: none;"></div>
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "loader_appliedStudent"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>
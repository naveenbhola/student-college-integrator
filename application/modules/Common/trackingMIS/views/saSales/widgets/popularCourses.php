<?php
$id = 'popularCourses';
?>
<div id = "popularCoursesContainer" class="col-md-12 col-sm-12 col-xs-12" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>University Courses (<span id="popularCourseCount"></span>)<small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
            <div id="">
                <table class="table" style="margin-bottom: 0px !important">
                      <thead>
                        <tr style="border:0px !important;">
                          <th style="border:0px !important;width: 9%;" >#</th>
                          <th style="border:0px !important;width:38%;" >Course Name</th>
                          <th style="border:0px !important;width:20%;" >Status</th>
                          <th style="border:0px !important;width:30%;" >Response &#8593;</th>
                        </tr>
                      </thead>
                </table>
                <div class="overflow_y" style="height: 210px !important; border-top:2px solid #e6e9ed;">
                    <table class="table" id="popularCourseRowTable" style="margin-bottom:0px !important;">
                          <tbody>
                            <tr style="display: none;">
                                <td class="sl" style="border-top:none;width:10%;"></td>
                                <td class="courseName" style="border-top:none;width:40%;"></td>
                                <td class="status" style="border-top:none;width:20%;"></td>
                                <td class="respCount" style="border-top:none;width:30%;"></td>
                            </tr>
                          </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>
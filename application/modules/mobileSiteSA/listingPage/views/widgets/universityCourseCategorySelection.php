<div class="available-course-detail clearfix" style="">
    <h3>Find course in this university</h3>
    <div id="findCourseSelectionContainer">
        <p style="margin-top:10px;"><?= $findCourseWidgetData['courseCount'].(($findCourseWidgetData['courseCount'] >1)?" Courses":" Course"); ?> available</p>
        <ul class="form-display customInputs flLt" style="margin:12px 0 10px; width:100%">
            <li>
                <div class="custom-dropdown">
                   <select class="universal-select" id="findCourseLevel" onchange="changeCourseLevel(this);">
                        <option value="">Select Course Level</option>
                        <?php
                        $count=0;
                        foreach($findCourseWidgetData['dataByCourseLevel'] as $courseLevel=>$courseDetails)
                        {?>
                        <option  value="<?= $courseLevel;?>"><?= $courseLevel;?></option>    
                        <?php
                        $count++;
                        }
                        if($count >1)
                        {
                        ?>
                        <option  value="all">All Course Levels</option>
                        <?php } ?>
                   </select>
                </div>
            </li>
            <li>
                <div class="custom-dropdown">
                   <select disabled="disabled" class="universal-select" id="findCourseStream" onchange="findCoursesDataForWidget(true);">
                        <option value="">Select Stream</option>
                        <?php foreach($findCourseWidgetData['dataByStream'] as $stream=>$data){?>
                            <option value="<?=$stream?>"><?=$stream?></option>
                        <?php } ?>
                        <?php if(count(array_keys($findCourseWidgetData['dataByStream'])) > 1){ ?>
                            <option value="all">All Streams</option>
                        <?php } ?>
                   </select>
                </div>
            </li>
            <li>
                <a href="javascript:void(0);" onclick="viewAllCoursesInUniv();" style="">View all <?=count($universityCourseBrowseSectionByStream['urls']['courses'])?> courses available</a>
            </li>
        </ul>
        
    </div>
</div>
<div id="findCourseResult">
    <?php $this->load->view('widgets/universityCoursesDetailList'); ?>
</div>
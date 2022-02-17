<script>
    var coursesByCourseLevel = <?=json_encode($findCourseWidgetData['dataByCourseLevel'])?>;
    var coursesByStream = <?=json_encode($findCourseWidgetData['dataByStream'])?>;
</script>
<div id="findCoursesFormDiv">
    <strong class="font-14">Find courses at this university </strong> (<?=$findCourseWidgetData['courseCount']?> course<?=$findCourseWidgetData['courseCount']==1?'':'s'?> available)
    <a class="flRt font-14" href="javascript:void(0);" onclick="viewAllCoursesInUniv();">View all courses</a>
    <div class="updated-find-courses-list">
        <ul class="customInputs-large">
            <li class="flLt" style="width:49%">
                <div class="custom-dropdown">
                    <select class="universal-select" id="findCourseLevel" onchange="changeCourseLevel(this);">
                        <option value="">Select Course Level</option>
                        <?php foreach($findCourseWidgetData['dataByCourseLevel'] as $courseLevel=>$data){?>
                            <option value="<?=$courseLevel?>"><?=$courseLevel?></option>
                        <?php } ?>
                        
                        <?php if(count(array_keys($findCourseWidgetData['dataByCourseLevel'])) > 1){ ?>
                            <option value="all">All Course Levels</option>
                        <?php } ?>
                    </select>
                </div>
            </li>
            <li class="flRt" style="width:49%">
                <div class="custom-dropdown">
                    <select class="universal-select" id="findCourseStream" disabled="disabled" onchange="findCoursesDataForWidget(true);">
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
        </ul>
    </div>
</div>
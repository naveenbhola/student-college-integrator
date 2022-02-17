<div class="abroad-cms-wrapper" style="margin: 0px;">
    <div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
            <div class="abroad-cms-head">
            <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
                <h1 class="abroad-title">Order Exams</h1>
                <div class="clearFix"></div>
            </div>
            
            <a class="orange-btn" href="/examPages/ExamMainCMS/showMainExamList" style="padding:6px 7px 8px;float: right;margin-top: -40px;">< Back</a> 

            <form id ="form_<?=$formName?>" name="<?=$formName?>" action="/examPages/ExamPagesCMS/saveExamPageSortOrder"  method="POST" enctype="multipart/form-data">

            <div style="border: 1px dotted;margin:10px;padding:5px">
                <div class="cms-fields" style="margin-left: 10px;margin-top: 10px;">
                    <span style="display: inline-block;width: 20% !important;">Stream : </span>
                    <select style="margin-left:30px;" id="streamId" name="examData[streamId]" class="universal-select cms-field" onchange ="fetchSubStreamByStream(this, '<?= $formName ?>');" >                         
                        <option value="">Select Stream</option>
                        <?php
                        foreach ($streams as $stream) {
                            echo "<option value='" . $stream['id'] . "' >" . $stream['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="cms-fields" style="margin-left: 10px;margin-top: 10px;">
                    <span style="display: inline-block;width: 20% !important;">Sub Stream : </span>
                    <select style="margin-left:30px;" id="subStreamId" name="examData[subStreamId]" class="universal-select cms-field" >
                        <option value="">Select Sub Stream</option>
                    </select>
                    <div style="margin-bottom: 15px;margin-top: 15px;text-align: center;">OR</div>
                </div>
                
                
            
                <div class="cms-fields" style="margin-left: 10px;margin-top: 10px;">
                    <span style="display: inline-block;width: 20% !important;">Popular Courses : </span>
                    <select style="margin-left:30px;" id="courseId" name="examData[courseId]" class="universal-select cms-field" >
                        <option value="">Select Course</option>
                        <?php
                        foreach ($popularCourses as $popularCourse) {
                            echo "<option value='" . $popularCourse['base_course_id'] . "' >" . $popularCourse['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="button-wrap" >
                    <a  href="JavaScript:void(0);" onclick ="fetchExamsByHierarachy('<?= $formName ?>');" class="orange-btn">Filter Exams</a>    
                </div>
            </div>

            <ul id="examList_<?= $formName ?>" style="margin-top:20px;">
            </ul>

            <div class="button-wrap saveExamOrderWithHierarchy">
                <a  href="JavaScript:void(0);" onclick ="submitExamPageSortOrder('<?= $formName ?>');" class="orange-btn">Save</a>
                <a  href="/examPages/ExamMainCMS/showMainExamList" class="cancel-btn">Cancel</a>
            </div>
            <div class="button-wrap saveExamOrderWithHierarchy" style="display:none">
                <img src="/public/images/loader_small_size.gif">
            </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<script type="text/javascript">
    var proceedFormSubmission = 1;
    $j(function() {
        $j("#examList_" + '<?= $formName ?>').sortable();
        $j("#examList_" + '<?= $formName ?>').disableSelection();
    });
</script>
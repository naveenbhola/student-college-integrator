<?php
    $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
    $numDesiredCourses = 0;
    foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
        $numDesiredCourses += count($coursesInGroup);
    }
?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
        <label>Desired Course<span>*</span></label>
        <?php
                if($numDesiredCourses == 1) {
                        foreach($desiredCourses as $groupName => $courses) {
                                if(count($courses) > 0) {
                                        foreach($courses as $courseId => $courseName) {
                                                echo "<label style='padding:10px 5px;'>$courseName</label>";
                                        }
                                }
                        }
                }
        ?>
        <div class="frmoutBx">
                <select class="inptBx" <?php if($numDesiredCourses == 1) { echo "style='display:none;'"; } else { echo "style='padding:5px 5px;'"; } ?> name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse(); updateTinyScrollBar(isRequiredTinyUpd);">
                        <?php if($numDesiredCourses > 1) { ?>
                                <option value="">Select</option>
                        <?php } ?>
                        <?php $this->load->view('registration/common/dropdowns/desiredCourse',array('desiredCourses' => $desiredCourses)); ?>
                </select>
                <div>
                        <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>
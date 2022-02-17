<?php
    $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
    $numDesiredCourses = 0;
    foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
        $numDesiredCourses += count($coursesInGroup);
    }
?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
    <label>Desired Course<span>*</span></label>
    <span class="colon-separater">:&nbsp;</span>
    <div class="fields-col">
        <?php
        if($numDesiredCourses == 1) {
            foreach($desiredCourses as $groupName => $courses) {
                if(count($courses) > 0) {
                    foreach($courses as $courseId => $courseName) {
                        echo "<div style='margin-top:3px;'>$courseName</div>";
                    }
                }
            }
        }
        ?>
        <select <?php if($numDesiredCourses == 1) { echo "style='display:none;';"; } ?> name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse()">
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
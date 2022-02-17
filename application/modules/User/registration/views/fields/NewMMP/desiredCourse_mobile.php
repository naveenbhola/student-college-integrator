<?php
    $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
    $numDesiredCourses = 0;
    foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
        $numDesiredCourses += count($coursesInGroup);
    }
    if($numDesiredCourses == 1) {
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse','display:none;'); ?>>
<?php } else { ?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
<?php } ?>
    <select name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse()">
        <?php if($numDesiredCourses > 1) { ?>
            <option value="">Desired Course</option>
        <?php } ?>
        <?php $this->load->view('registration/common/dropdowns/desiredCourse',array('desiredCourses' => $desiredCourses)); ?>
    </select>
    <div>
        <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
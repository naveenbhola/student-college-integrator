<li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
    <select name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();">
        <option value="">Desired Course</option>
    </select>
    <div class="icon-wrap"><i class="reg-sprite des-course-icon" ></i></div>
    <div>
        <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
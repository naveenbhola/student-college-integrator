<li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
    <select name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses();">
        <option value="">Education Interest</option>
        <?php foreach($fields['fieldOfInterest']->getValues() as $categoryId => $categoryName) { ?>
            <option value="<?php echo $categoryId; ?>" <?php if($selectedFieldOfInterest==$categoryId){ echo "selected='selected'";}?>><?php echo $categoryName; ?></option>
        <?php } ?>
    </select>
    <div class="icon-wrap"><i class="reg-sprite degree-icon" ></i></div>
    <div>
        <div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
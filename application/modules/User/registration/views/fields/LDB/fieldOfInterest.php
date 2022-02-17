<li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
    <div class="custom-dropdown">
        <select name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses();">
            <option value="">Education Interest</option>
            <?php foreach($fields['fieldOfInterest']->getValues() as $categoryId => $categoryName) { ?>
                <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
            <?php } ?>
        </select>
    </div>
    <div>
        <div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
 <li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
    <?php 
        $fieldOfInterestValues = $fields['fieldOfInterest']->getValues();
        $selectedLabel ='';
        if(!empty($formData['fieldOfInterest'])){
            $selectedValue = $formData['fieldOfInterest'];
            $selectedLabel = $fieldOfInterestValues[$selectedValue];
        }

    ?>
    <div>
        <div class="reg-dropdwn ssLayer" id="fieldOfInterest_<?php echo $regFormId; ?>_input" mandatory="1">
            <span class="textHolder"><?php if(!empty($selectedLabel)){ echo $selectedLabel; }else{ echo 'Education Interest'; } ?></span>
            <i class="rightPointer"></i>
        </div>

        <div style="display:none" class="select-Class">
            <select name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses();">
                <option value="">Education Interest</option>
                <?php foreach($fields['fieldOfInterest']->getValues() as $categoryId => $categoryName) { ?>
                    <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
        </div>
    </div>
</li>
<li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
    <div>
        <div class="reg-dropdwn disableField" id="desiredCourse_<?php echo $regFormId; ?>_input" mandatory="1" searchRequired="1">
            <span class="textHolder">Desired Course</span>
            <i class="rightPointer"></i>
        </div>

        <div style="display:none" class="select-Class">
            <select name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();">
                <option value="">Desired Course</option>
            </select>
        </div>
        <div>
            <div class="errorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
        </div>
    </div>
</li>
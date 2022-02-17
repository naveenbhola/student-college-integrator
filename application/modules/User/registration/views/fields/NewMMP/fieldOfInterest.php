<?php
    $fieldOfInterestValues = $fields['fieldOfInterest']->getValues(array('mmpFormId' => $mmpFormId))
?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
        <label>Field of Interest<span>*</span></label>
        <div class="frmoutBx">
                <select style="padding:5px 5px;" class="inptBx" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php if(count($fieldOfInterestValues) > 1) { ?>
                                <option value="">Select</option>
                        <?php } ?>
                        <?php foreach($fieldOfInterestValues as $categoryId => $categoryName) { ?>
                                <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                        <?php } ?>
                </select>
                <div>
                        <div class="regErrorMsg" id= "fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>
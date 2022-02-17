<li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
        <label>Email<span><?php echo $registrationHelper->markMandatory('email'); ?></span></label>
        <div class="frmoutBx">
                <input type="text" class="inptBx" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" maxlength="125" tip="email_idM" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> <?php if($formData['userId']) echo "readonly='readonly'; style='background:#eee;'"; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                <div>
                        <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>

<?php  if(isset($fields['isdCode'])) {

$ISDCodeValues = $fields['isdCode']->getValues();   ?>

<li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?> >
        <label>Country Code<span><?php echo $registrationHelper->markMandatory('isdCode'); ?></span></label>
        <div class="frmoutBx">
                <select class="inptBx" name="isdCode" id="isdCode_<?php echo $regFormId; ?>"  <?php if($mmpData['page_type'] != 'abroadpage') { ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" <?php } else { ?> class="universal-select" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" <?php } ?> regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                   <?php foreach($ISDCodeValues as $key=>$value){ ?>
                            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                     <?php } ?>
                </select>
                <div>
                        <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>

<?php } ?>

<li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
        <label>Mobile No<span><?php echo $registrationHelper->markMandatory('mobile'); ?></span></label>
        <div class="frmoutBx">
                <input type="text" class="inptBx" name="mobile" id="mobile_<?php echo $regFormId; ?>" maxlength="10" minlength="10" tip="mobile_numM" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                <?php  if(!isset($fields['isdCode'])) { ?>
                <input type="hidden" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" value="91-2" />
                <?php } ?>
                <div>
                        <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>

<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
        <label>First Name<span><?php echo $registrationHelper->markMandatory('firstName'); ?></span></label>
        <div class="frmoutBx">
                <input meta="first name" type="text" class="inptBx" name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                <div>
                        <div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>
    
<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
        <label>Last Name<span><?php echo $registrationHelper->markMandatory('lastName'); ?></span></label>
        <div class="frmoutBx">
                <input meta="last name" type="text" class="inptBx" name="lastName" id="lastName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                <div>
                        <div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
</li>

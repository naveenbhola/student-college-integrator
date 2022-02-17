<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
        <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
            <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields" maxlength="125" value="Email" default="Email" <?php if($formData['userId']) { echo "disabled='disabled'; style='background:#eee;color:'"; } ?> <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>


    <?php  if(isset($fields['isdCode'])) {

    $ISDCodeValues = $fields['isdCode']->getValues();   ?>
    
    <li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?> >
        <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].resetResidentCityDropDownMMP(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> >
           <?php foreach($ISDCodeValues as $key=>$value){ ?>
                    <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                 <?php } ?>
        </select>
            <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
       
    </li>

    <?php } ?>

    <li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
            <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="register-fields" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <?php  if(!isset($fields['isdCode'])) { ?>
            <input type="hidden" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" value="91-2" />
            <?php } ?>
            <div>
                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>


<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
            <div>
                    <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                    <div>
                        <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                    </div>
                <div class='clearFix'></div>
            </div>
</li>

<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
            <div>
                <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                    <div>
                       <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                    </div>
                <div class='clearFix'></div>
            </div>
            <div class="clearFix"></div>    
</li>
</ul>
<?php

foreach($fields as $fieldId => $field) {
    
switch($fieldId) {
    case 'firstName': ?><li class="clearfix">
    <div <?php echo $registrationHelper->getBlockCustomAttributes('firstName','width:49%;float:left;'); ?>>
        <div class="form-field">
            <i class="exam-sprite user-icon"></i>
            <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="update-field" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" style="width:85%;"/>
        </div>
        <div>
            <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
        </div>
    </div>
<?php break;
    case 'lastName': ?>
    <div <?php echo $registrationHelper->getBlockCustomAttributes('lastName','width:49%;float:right;'); ?>>
        <div class="form-field">
            <i class="exam-sprite user-icon"></i>
            <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="update-field" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" style="width:84%;"/>
        </div>
        <div>
            <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
        </div>  
    </div>
</li>
<?php break;
    case 'email': ?>
<li class="clearfix">
    <div <?php echo $registrationHelper->getBlockCustomAttributes('email','width:34%;float:left;margin-right:7px'); ?>>
        <div class="form-field">
            <i class="exam-sprite mail-icon"></i>
            <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="update-field" maxlength="125" value="Email Id" default="Email Id" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="registration_context = 'MMP';shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : false; shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" style="width:80%;" />
        </div>
        <div>
            <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
        </div>
    </div>
<?php break;
    case 'isdCode': ?>
        <div <?php echo $registrationHelper->getBlockCustomAttributes('isdCode','width:25%; float:left'); ?>>
        
            <div class="custom-dropdown" style="padding-right: 29px; box-sizing: border-box;overflow: hidden;">
                <?php $ISDCodeValues = $fields['isdCode']->getValues(); ?>
                <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" style="width:300px; padding:6px 3px;" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();">
                <?php foreach($ISDCodeValues as $key=>$value){ ?>
                    <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                 <?php } ?>
                </select>
            </div>
        
        <!-- <input type="text" name="isdCode" id="isdCode_<?php echo $regFormId; ?>" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" /> -->
        <div>
            <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
        </div>
       

            <!-- <i class="exam-sprite user-icon"></i>
            <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="update-field" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" style="width:84%;"/> -->
       
       <!--  <div>
            <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
        </div>   -->
    </div>
<?php break;
    case 'mobile': ?>
    <div <?php echo $registrationHelper->getBlockCustomAttributes('mobile','width:38%;float:right;'); ?>>
        <div class="form-field">
            <i class="exam-sprite phone-icon"></i>
            <input type="text" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" caption="your mobile number" label="Mobile" mandatory="1" regfieldid="mobile" default="Mobile No" value="Mobile No" minlength="10" maxlength="10" class="update-field" id="mobile_<?php echo $regFormId; ?>" name="mobile" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> style="width:80%;"/>
        </div>
        <div>
            <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
        </div>  
    </div>
</li>
<?php break;
}
}
?>
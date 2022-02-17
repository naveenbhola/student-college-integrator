<?php
$isLoggedIn = false;
if($formData['userId'] > 0) {
    $isLoggedIn = true;
}
?>

<li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
    <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields <?php if($isLoggedIn == true) echo 'disabled-field'; ?>" <?php if($isLoggedIn == true) echo 'disabled'; ?> maxlength="125" value="Email Id" default="Email Id" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" <?php if($context == 'registerResponseLPR' || $context == 'default' || $context == 'askQuestionBottom' || $context == 'askQuestionBottomCompare' || $context == 'inlineANA') { ?> onblur="registration_context = '<?php echo $context;?>';shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : false; shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } else { ?> onblur="if(typeof(registration_context) != 'undefined' && registration_context == 'MMP') { delete registration_context; }shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } ?> />
    <div>
        <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>

<li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode','float:left !important'); ?>>
    <div class="custom-dropdown two-column-fields" style="width:50% !important;">
        <?php 
            $ISDCodeValues = $fields['isdCode']->getValues(); ?>
            <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
            <?php foreach($ISDCodeValues as $key=>$value){ ?>
                <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
         <?php } ?>
        </select>
        <div>
            <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
        </div>
        <div class='clearFix'></div>
    </div>
<div <?php echo $registrationHelper->getBlockCustomAttributes('mobile',''); ?>>
    <div class="two-column-fields" style="padding-left:7px;">
        <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
        <div>
            <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
        </div>  
    </div>
</li>

<?php if($widget == 'listingPageRightNational'): ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName','float:left !important'); ?>>
    <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="register-fields" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
    <div>
        <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
    </div>
    <div class='clearFix'></div>
</li>
<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName', ''); ?>>
    <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="register-fields" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
    <div>
        <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
    </div>
    <div class='clearFix'></div>
</li>
<?php else: ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName','float:left !important'); ?>>
    <div class="two-column-fields">
        <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="register-fields" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
        <div>
            <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
        </div>
        <div class='clearFix'></div>
    </div>
    <div <?php echo $registrationHelper->getBlockCustomAttributes('lastName', ''); ?>>
        <div class="two-column-fields" style="padding-left:7px;">
            <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="register-fields" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
            </div>
            <div class='clearFix'></div>
        </div>
    </div>
    <div class="clearFix"></div>	
</li>
<?php endif; ?>
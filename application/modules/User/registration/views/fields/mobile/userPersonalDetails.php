    <?php
    if($signedInUser!='false' && $isFullRegisteredUser_mobile){
	?>
	<li style="display:none;" >
	<?php
    }
    else{
	?>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?> >
	<?php
    }
    ?>
        <input type="email" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields" maxlength="125" value="Email" default="Email" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
        <div class="icon-wrap"><i class="reg-sprite mail-icon" ></i></div>
        <div>
            <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
        </div>
    </li>

    <?php
    if($signedInUser!='false' && $isFullRegisteredUser_mobile){	?>

	<li style="display:none;" >

	<?php
    	$ISDCodeValues = $fields['isdCode']->getValues();	
    
    }else{

    	$ISDCodeValues = $fields['isdCode']->getValues();	?>
	
	<li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?> >

	<?php
    }
    ?>
        <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);">
			<?php foreach($ISDCodeValues as $key=>$value){ ?>
                <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
             <?php } ?>
        </select>
    	<div class="icon-wrap"><i style="float:left;padding-left:6px;padding-top:6px;"><img width="23px" height="23px" src="<?php echo SHIKSHA_HOME.'/public/mobile5/images/country_code_image.png';?>" /></i></div>
        <div>
            <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
        </div>  
    </li>

    <?php
    if($signedInUser!='false' && $isFullRegisteredUser_mobile){
	?>
	<li style="display:none;" >
	<?php
    }
    else{
	?>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?> >
	<?php
    }
    ?>
            <input type="text" pattern="\d*" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="register-fields" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
	    <div class="icon-wrap"><i class="reg-sprite mob-icon" ></i></div>
            <div>
                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
	
    <?php
    if($signedInUser!='false' && $isFullRegisteredUser_mobile){
	?>
	<li style="display:none;" >
	<?php
    }
    else{
	?>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?> >
	<?php
    }
    ?>
			<div>
					<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div class="icon-wrap"><i class="reg-sprite fname-icon" ></i></div>
					<div>
						<div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
	</li>
	
    <?php
    if($signedInUser!='false' && $isFullRegisteredUser_mobile){
	?>
	<li style="display:none;" >
	<?php
    }
    else{
	?>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?> >
	<?php
    }
    ?>
			<div>
				<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
				<div class="icon-wrap"><i class="reg-sprite lname-icon" ></i></div>
					<div>
					   <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
			<div class="clearFix"></div>	
        </li>
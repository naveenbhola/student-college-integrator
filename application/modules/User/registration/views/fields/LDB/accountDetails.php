<?php $customFormFields = $customFormData['customFields']; ?>

<div class="emailFld-block reg-form invalid <?php if(!empty($customFormFields['email']['hidden'])){ echo 'ih'; } if(!empty($customFormFields['email']['value'])){ echo ' filled'; } ?>" type="input" regfieldid="email" id="email_block_<?php echo $regFormId; ?>">
	<div class="ngPlaceholder">Email address</div>
	<input class="ngInput emailFld emailFld-inpt" type="text" id="email_<?php echo $regFormId; ?>" readonly  
     onfocus="this.removeAttribute('readonly');" regformid="<?php echo $regFormId; ?>" regFieldId="email" maxlength="125" default="Email Address" autocomplete="off" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> name="email" tabindex="2" value="<?php if(!empty($customFormFields['email']['value'])){ echo $customFormFields['email']['value']; } ?>" <?php if(!empty($customFormFields['email']['disabled'])){ echo 'disabled'; } ?>">
    <i class="d-info"></i>
    <div class="input-help ds">
		<div class="up-arrow"></div>
		<div class="help-text">This will be your Shiksha user ID</div>
	</div>

	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text">Please Enter Email.</div>
	</div>

	<div class="regform-email-suggestor">
		<ul class="regform-email-suggestor-list"></ul>	
	</div>
</div>

<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){ ?>
	<div class="reg-form invalid" type="input" regfieldid="password" id="password_block_<?php echo $regFormId; ?>">
		<div class="ngPlaceholder">Create a password</div>
		<input class="ngInput psd" id="password_<?php echo $regFormId; ?>" type="Password" readonly onfocus="this.removeAttribute('readonly');" required="" name="password" tabindex="2" <?php echo $registrationHelper->getFieldCustomAttributes('password'); ?> maxlength="25">
		<span class="hideblk">show</span>
		<span class="hideblk ih">hide</span>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Password.</div>
		</div>
	</div>	
<?php } ?>
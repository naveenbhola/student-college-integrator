<?php if(($logged == "Yes" && strlen($userData[0]['mobile']) !=10)) { $userData[0]['mobile']  = ''; } ?>
<div class="find-field-row">
	<input type="text" class="form-txt-field" onblur="validatePhoneAbroad($('homephone').value,$('homephone').getAttribute('caption'),$('homephone').getAttribute('maxlength'),$('homephone').getAttribute('minlength'),$('homephone').getAttribute('required'));" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number" tip="mobile_numM" maxlength="<?php if(($logged == "Yes")&&(strlen($userData[0]['mobile']) > 10)) {echo strlen($userData[0]['mobile']);}else {echo "10";}?>" maxlength1="10" minlength = "10" value="Mobile" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile"/>
	<div class="clearFix"></div>
	<div class="errorPlace" style="display:none;">
		<div class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>
	</div>
</div>

<?php if($logged=="No") {?>
<div class="find-field-row">
	<input type="text" class="form-txt-field" onblur="validateEmailAbroad($('homeemail').value,$('homeemail').getAttribute('caption'),$('homeemail').getAttribute('maxlength'),$('homeemail').getAttribute('minlength'));" id = "homeemail" name = "homeemail" value="<?php echo 'Email'; ?>" validate = "validateEmail" required = "true" caption = "email id" maxlength = "125" tip="email_idM" onfocus="checkTextElementOnTransition(this,'focus')" default="Email"/>
	<div class="errorPlace">
		<div class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>
    </div>
<?php }else {?>
	<input type="hidden" id = "<?php echo $prefix; ?>homeemail" value=""/>
   	<input type="hidden" id = "<?php echo $prefix; ?>userId" value="<?php echo $userData[0]['userid']; ?>"/>
<?php }?>
</div>

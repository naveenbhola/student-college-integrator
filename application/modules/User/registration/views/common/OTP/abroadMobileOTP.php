<article id="user-Authentication-otp_<?php echo $regFormId; ?>" style="display:none" class="abroad-otp-layer content-inner clearfix">
	<div id="user-Authentication_<?php echo $regFormId; ?>" >
		<div id="enterMobilePin" class="enterMobilePin">
		<div class="abroad-verified-title abroad-layer-title">Verify Mobile Number to Continue</div>
			<p>OTP (One time password) has been sent to your mobile <span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile"> <?php if(isset($userData['mobile'])){ echo $userData['mobile']; } ?></span>&nbsp;&nbsp;<a href="javascript:void(0);" id="changeMobile" class="changeMobile" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileLayer();" >Change Number</a></p>
			<div class="digit-text">Please enter 4 digit OTP received</div>
			<input type="number" step="0.01" pattern="[0-9]*" class="universal-txt" id="one-time-pass_<?php echo $regFormId; ?>" maxLength="4" value="Enter OTP" style="width:60%;"onfocus="(this.value=='Enter OTP') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter OTP');" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
			<div id="pin-Error_<?php echo $regFormId; ?>" class="errorMsg" style="color:red;"></div>
			<p class="resend-otp">If you do not receive OTP within 2-3 minutes, you can request to <a href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerificationLayer(null, 1);">resend the OTP</a></p>
			<a class="btn btn-default btn-full" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();">Verify & Continue</a>
		</div>

		<div class="changeMobileLayer" id="changeMobileLayer" style="display:none">
			<div class="abroad-verified-title abroad-layer-title">Change Mobile Number</div>
			<p>Existing mobile number: <span id="yourChangeMobile_<?php echo $regFormId; ?>" class="yourMobile"> </span></p>
			<div class="digit-text">Enter your new mobile number</div>
			<input type="number" step="0.01" pattern="[0-9]*" class="universal-txt" id="newMobileNumber_<?php echo $regFormId; ?>" maxLength="10" value="Enter New Mobile" onfocus="(this.value=='Enter New Mobile') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter New Mobile'); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].updatedMobileValidation(this.value);" size="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
			<div id="mobile_errors" class="regErrorMsgForOTP" style="color:red;"></div>	
			<a class="btn btn-default btn-full" href="javascript:void(0);" style="margin-top:10px;" id="newMobileSubmit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();">Submit</a>
		</div>
	</div>
	</article>

	<script>
	<?php if($context !== 'editProfileSAMobile'){ // getting js in footer so cant run below code mid render ?>
		
		$j('#newMobileNumber_<?php echo $regFormId; ?>').keyup(function(e){
			if(e.keyCode == 13)
			{
				shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();
			}
		});
		
		$j('#one-time-pass_<?php echo $regFormId; ?>').keyup(function(e){
			if(e.keyCode == 13)
			{
				shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();
			}
		});
	<?php } ?>	

    <?php if($OTPforReturningUser){ ?>
	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].isOTPVerificationAttemptsDone('<?php if(isset($userData['email'])) echo $userData['email']; ?>');
   <?php } ?>
</script>
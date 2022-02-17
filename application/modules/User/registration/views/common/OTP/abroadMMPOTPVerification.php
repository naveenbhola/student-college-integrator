<div>
	<div id="user-Authentication-otp_<?php echo $regFormId; ?>" style="<?php if($defaultDisplay || $OTPforReturningUser){ echo 'display:block'; }else{ echo 'display:none';} ?>" >
		<div class="abroad-layer-content clearfix" id="user-Authentication_<?php echo $regFormId; ?>" >
			<div id="enterMobilePin" class="enterMobilePin">
				<div class="abroad-layer-title" style="margin:0 0 10px 0;">Verify Mobile Number to Continue</div>
				<p>OTP (One time password) has been sent to your mobile
				<span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile"> <?php if(isset($userData['mobile'])){ echo $userData['mobile']; } ?></span>
				<a href="javascript:void(0);" id="changeMobile" class="changeMobile" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileLayer();" style="font-size : 11px; opacity : 0.8">change number</a></p>	
					<p class="digit-text">Please enter 4 digit OTP received</p>
					<input type="text" class="universal-text" value="Enter OTP" id="one-time-pass_<?php echo $regFormId; ?>" maxlength="4" style="width:210px; padding:5px; font-size:12px;" onfocus="(this.value=='Enter OTP') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter OTP');"  />
					<div id="pin-Error_<?php echo $regFormId; ?>" class="errorMsg" style="margin-bottom:8px; float:left; width:100%;"></div>
					<p class="resend-otp">If you do not receive OTP within 2-3 minutes, you can request to <a href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerificationLayer(null, 1);">resend the OTP</a></p>
					<div class="clearwidth" style="margin:8px 0 0;">
						<a href="javascript:void(0);" class="button-style verify-btn" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();" style="margin-top:10px;">Verify & Continue</a>
					</div>
				</div>
				
				<div id="changeMobileLayer" class="changeMobileLayer" style="display:none">
					<div class="abroad-layer-title" style="margin:0 0 10px 0;">Change Mobile Number</div>
					<p>Existing mobile number:
					<span id="yourChangeMobile_<?php echo $regFormId; ?>" class="yourMobile"> </span>
					</p>
					<p class ="digit-text" >Enter your new mobile number</p>
				    
					<div>
					<div style="width:100%; float:left">
						<input type="text" class="universal-text" id="newMobileNumber_<?php echo $regFormId; ?>" maxlength="10" value="Enter New Mobile" onfocus="(this.value=='Enter New Mobile') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter New Mobile'); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].updatedMobileValidation(this.value);" size="10" style="width:220px; padding:5px; font-size:12px;"/>
					</div>
					<div id="mobile_errors" class="regErrorMsgForOTP" style="display : block; margin-top:5px;"><font color="red"></font></div>	
				</div>
				<div>
					<a href="javascript:void(0);" class="button-style verify-btn" id="newMobileSubmit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();">Submit</a>
				</div>
			
		</div>
	</div>
</div>

<script>
    
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
    
    <?php if($OTPforReturningUser){ ?>
	shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].isOTPVerificationAttemptsDone('<?php if(isset($userData['email'])) echo $userData['email']; ?>');
   <?php } ?>
</script>


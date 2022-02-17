<div class="mobile-otp-layer" style="padding:15px; display:none;" id="user-Authentication-otp_<?php echo $regFormId; ?>" >
	<div id="user-Authentication_<?php echo $regFormId; ?>" class="userOTPVerification">
      <strong>Verify Mobile Number</strong>
      <p>PIN has been sent to your mobile <span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile"></span> </p>
      <a href="javascript:void(0);" style="font-size:12px;" id="changeMobile" class="changeMobile" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileLayer();" >Change Number</a>
      <span class="enterMobilePin" >
      <p style="color:#656565">Please enter the PIN received on your mobile below</p>
      <div style="width:120px; float:left">
      	<input type="text" class="register-fields" id="one-time-pass_<?php echo $regFormId; ?>" maxlength="4" /> 
      </div>
      <a href="javascript:void(0);" style="margin:7px 0 0 5px; display:inline-block;" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerificationLayer(null, 1);" >Resend PIN</a>
	   	<div class="clearfix"></div>
      <div id="pin-Error_<?php echo $regFormId; ?>" class="errorMsg" ></div>
      <div class="clearfix"></div>
      <a href="javascript:void(0);" class="otp-submit-btn" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();" >Submit</a>
  </span>


      <div id="changeMobileLayer" class="changeMobileLayer" style="display:none">
	    <p style="color:#656565; margin-bottom:7px;">Enter your new mobile number</p>
	    
	    <div>
		<div style="float: left; width: 70%;">
			<input type="text" id="newMobileNumber_<?php echo $regFormId; ?>" maxlength="10" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].updatedMobileValidation(this.value);" class="register-fields"/>
		</div>
		<div class="clearfix"></div>
		<div id="mobile_errors" class="regErrorMsgForOTP" style="display:block; margin-top:5px; color:red; font-size:12px;"></div>
	    </div>
	   	<div class="clearfix"></div>
	      <a href="javascript:void(0);" class="otp-submit-btn" id="newMobileSubmit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();" >Submit</a>
	    
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
</script>
<?php 
    $jsObject = 'abroadSignupFormObj';
?>
	<div id="user-Authentication-otp" style="<?php if($defaultDisplay || $OTPforReturningUser){ echo 'display:block'; }else{ echo 'display:none';} ?>" >
		<div class="abroad-layer-content clearfix" id="user-Authentication" >
			<div id="enterMobilePin" class="enterMobilePin">
                            <div style="margin:0 0 10px 0;font-size: 16px;font-weight: bold;">Verify mobile number to continue</div>
				<p>OTP (One time password) has been sent to your mobile
				<span id="yourMobile" class="yourMobile"> <?php if(isset($mobile)){ echo $mobile; } ?></span>
				<a href="javascript:void(0);" id="changeMobile" class="changeMobile gaTrack" gaparams="SA_SINGLE_SIGNUP_PAGE_OTP_LAYER,changeNumber" onclick="<?php echo $jsObject; ?>.changeMobileLayer();" style="font-size : 11px; opacity : 0.8">change number</a></p>	
					<p class="digit-text">Please enter 4 digit OTP received</p>
					<input type="text" class="universal-text" value="Enter OTP" id="one-time-pass" maxlength="4" style="width:210px; padding:5px; font-size:12px;" onfocus="(this.value=='Enter OTP') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter OTP');"  />
					<div id="pin-Error" class="errorMsg" style="margin-bottom:8px; float:left; width:100%;"></div>
					<p class="resend-otp " >If you do not receive OTP within 2-3 minutes, you can request to <a href="javascript:void(0);" class="gaTrack" gaparams="SA_SINGLE_SIGNUP_PAGE_OTP_LAYER,resendOTP"  onclick="<?php echo $jsObject; ?>.userVerificationLayer(null, 1);">resend the OTP</a></p>
					<div class="clearwidth" style="margin:8px 0 0;">
						<a href="javascript:void(0);" class="button-style verify-btn gaTrack"  gaparams="SA_SINGLE_SIGNUP_PAGE_OTP_LAYER,verifyOTP" onclick="<?php echo $jsObject; ?>.userVerifyAuthentication();" style="margin-top:10px;">Verify & Continue</a>
					</div>
				</div>
				
				<div id="changeMobileLayer" class="changeMobileLayer" style="display:none">
					<div class="abroad-layer-title" style="margin:0 0 10px 0;">Change Mobile Number</div>
					<p>Existing mobile number:
					<span id="yourChangeMobile" class="yourMobile"> </span>
					</p>
					<p class ="digit-text" >Enter your new mobile number</p>
				    
					<div>
					<div style="width:100%; float:left">
						<input type="text" class="universal-text" id="newMobileNumber" maxlength="10" value="Enter New Mobile" onfocus="(this.value=='Enter New Mobile') && (this.value ='')" onblur="(this.value=='') && (this.value='Enter New Mobile'); <?php echo $jsObject; ?>.updatedMobileValidation(this.value);" size="10" style="width:220px; padding:5px; font-size:12px;"/>
					</div>
					<div id="mobile_errors" class="regErrorMsgForOTP" style="display : block; margin-top:5px;"><font color="red"></font></div>	
				</div>
				<div>
					<a href="javascript:void(0);" class="button-style verify-btn gaTrack" id="newMobileSubmit" gaparams="SA_SINGLE_SIGNUP_PAGE_OTP_LAYER,submitNewMobileNumber" onclick="<?php echo $jsObject; ?>.changeMobile();">Submit</a>
				</div>
			
		</div>
	</div>
<script>
    
    $j('#newMobileNumber').keyup(function(e){
	if(e.keyCode == 13)
	{
	    <?php echo $jsObject; ?>.changeMobile();
	}
    });
    
    $j('#one-time-pass').keyup(function(e){
	if(e.keyCode == 13)
	{
	    <?php echo $jsObject; ?>.userVerifyAuthentication();
	}
    });
    
    <?php if($OTPforReturningUser){ ?>
	<?php echo $jsObject; ?>.isOTPVerificationAttemptsDone('<?php if(isset($userData['email'])) echo $userData['email']; ?>');
   <?php } ?>
</script>


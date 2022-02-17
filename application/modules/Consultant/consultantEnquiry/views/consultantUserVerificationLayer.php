<?php

?>
<div id = "consultantOTPContainer">
    <div style="width:585px; background:#f8f8f8;">
	<div style="display: block;" id="user-Authentication-otp">
		<div id="user-Authentication" class="abroad-layer-content clearfix">
			<div class="enterMobilePin" id="enterMobilePin">
				<div style="margin:0 0 10px 0;" class="abroad-layer-title">Verify Mobile Number to Continue</div>
				<p>OTP (One time password) has been sent to your mobile
				<span class="yourMobile" id="yourMobile"><?=($mobile)?></span>
				<a style="font-size : 11px; opacity : 0.8" onclick="changeMobileLayer();" class="changeMobile" id="changeMobile" href="javascript:void(0);">change number</a></p>	
					<p class="digit-text">Please enter 4 digit OTP received</p>
					<input type="text" onblur="(this.value=='') && (this.value='Enter OTP');" onfocus="(this.value=='Enter OTP') && (this.value ='')" style="width:210px; padding:5px; font-size:12px;" maxlength="4" id="one-time-pass" value="Enter OTP" class="universal-text">
					<div style="margin-bottom:8px; float:left; width:100%;" class="errorMsg" id="pin-Error"></div>
					<p class="resend-otp">If you do not receive OTP within 2-3 minutes, you can request to <a onclick="resendOTP(true);" href="javascript:void(0);">resend the OTP</a></p>
					<div style="margin:8px 0 0;" class="clearwidth">
						<a style="margin-top:10px;" onclick="userOTPAuthentication();" class="button-style verify-btn" href="javascript:void(0);">Verify &amp; Continue</a>
					</div>
				</div>
				
				<div style="display:none" class="changeMobileLayer" id="changeMobileLayer">
					<div style="margin:0 0 10px 0;" class="abroad-layer-title">Change Mobile Number</div>
					<p>Existing mobile number:
					<span class="yourMobile" id="yourChangeMobile"><?=($mobile)?></span>
					</p>
					<p class="digit-text">Enter your new mobile number</p>
				    
					<div>
					<div style="width:100%; float:left">
						<input type="text" style="width:220px; padding:5px; font-size:12px;" size="10" onblur="(this.value=='') && (this.value='Enter New Mobile'); updatedMobileValidation(this.value);" onfocus="(this.value=='Enter New Mobile') && (this.value ='')" value="Enter New Mobile" maxlength="10" id="newMobileNumber" class="universal-text">
					</div>
					<div style="display : block; margin-top:5px;" class="regErrorMsgForOTP" id="mobile_errors"><font color="red"></font></div>	
				</div>
				<div>
					<a onclick="changeMobile();" id="newMobileSubmit" class="button-style verify-btn" href="javascript:void(0);">Submit</a>
				</div>
			
		</div>
	</div>
</div>

<script>
	$j(document).ready(function(){
		// submit on enter keypress
		$j('#newMobileNumber').keyup(function(e){
		if(e.keyCode == 13)
		{
			changeMobile();
		}
		});
		$j('#one-time-pass').keyup(function(e){
		if(e.keyCode == 13)
		{
			userOTPAuthentication();
		}
		});
	});
	
</script>

    </div>
	<div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
</div>

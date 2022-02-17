<?php 
	$hrefLink = 'tel:'.$missedCallNumber;
	if($_COOKIE['AndroidSource'] === 'AndroidWebView'){
		$hrefLink='javascript:void(0)';
	}
?>

<div class="SgUp-lyr" data-enhance="false">
	<div class="head">
		<p class="head-title">Verify Mobile Number</p>
		<a href="javascript:void(0);" class="lyr-cls otpCross" id="otpBack_<?php echo $regFormId; ?>" location="-3">&times;</a>
		<input type='hidden' id='email_<?php echo $regFormId; ?>' name='email' value='<?php echo $email; ?>' />
		<div class="clear"></div>
	</div>
	<div class="Sgup-FrmSec" id="user-Authentication-otp_<?php echo $regFormId; ?>">
		
		<div class="showOnlyMisdCallLyr">
			<?php if($isNewUser == "true"){?>
			<p class="field-title">Your account is created on Shiksha.</p>
			<p class="field-title">To <?php echo (!empty($formHeading)?$formHeading:'proceed');?>, verify your mobile number using One Time Password (OTP) sent on <span id='otp-mbl'><?php echo '+'.$isdCode.'-'.$mobile; ?></span> 
			<?php }else{?>
			<p class="field-title">
				One Time Password (OTP) has been sent to your mobile number <span id='otp-mbl'><?php echo '+'.$isdCode.'-'.$mobile; ?></span> 
			<?php }?>
			<a href="javascript:void(0);" id="changeMobile" class="lyrEdit changeMobile" regformid="<?php echo $regFormId; ?>"><i class="editIcn"></i> Change Number</a></p>
		</div>
		<div class="reg-form invalid showOnlyMisdCallLyr" id="otp_block_<?php echo $regFormId; ?>">
			<div class="ngPlaceholder">Enter OTP</div>
			<input class="ngInput otp-enter maxLength"  type="number" step="0.01" pattern="[0-9]*" data-enhance="false" name="one-time-pass" id="one-time-pass_<?php echo $regFormId; ?>" minlength="1" maxlength="4" default="OTP" profanity="1" required="" tabindex="2" mandatory="1" caption="OTP">
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text" id="pin-Error_<?php echo $regFormId; ?>">Please Enter Password.</div>
			</div>
		</div>
		<div class="reg-form invalid border-none invld-otp showOnlyMisdCallLyr" id="sendOTP_<?php echo $regFormId; ?>" >
			<p class="ques-txt-otp flLt">Did not receive OTP?</p>
			<a href="javascript:void(0);" class="otp-blk rsndclr rsnd-otp flRt" regformid="<?php echo $regFormId; ?>">Resend OTP
              <span class="input-helper">
				<span class="up-arrow"></span>
				<span class="helper-text" id="otp-msg">OTP Sent</span>
			</span>
			</a>
		</div>
		<div class="otp-rst-lgn showOnlyMisdCallLyr">
		   <a tabindex="2" class="reg-btn usr-vfy-auth" regformid="<?php echo $regFormId; ?>" href="javascript:void(0);">Verify</a>
		</div>
		<?php if(USE_MISSED_CALL_VERIFICATION === true && $_COOKIE['AndroidSource'] !== 'AndroidWebView' ){ ?>
		<div class="break_line showOnlyMisdCallLyr">
		  <span>Or</span>
		</div>
		<div class="try_a_call">
			<p class="add_verify">Verify your mobile number by giving a </p>
         	<a class="give_a_call usr-vfy-misd-call" phone-no= id='miss_call' href="<?php echo $hrefLink;?>"> <i class="otp_icons make_call"></i>Missed call</a>

	        <p class="free_call">This call is free of charge &amp; it will <br/> automatically disconnect</p>
		</div>
		<?php } ?>
	</div>
	<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
	<input type="hidden" id='user_phoneNo' value="<?php echo $missedCallNumber;?>" />
	<?php if($showOnlyOTP == 'yes'){?>
		<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
		<input type='hidden' id='mobile_<?php echo $regFormId; ?>' name='mobile' value='<?php echo $mobile; ?>' />
		<input type='hidden' id='email_<?php echo $regFormId; ?>' name='email' value='<?php echo $email; ?>' />
		<input type='hidden' id='isdCode_<?php echo $regFormId; ?>' name='isdCode' value='<?php echo $isdCode.'-2'; ?>' />
		<input type='hidden' id='tracking_keyid_<?php echo $regFormId; ?>' name='tracking_keyid' value='<?php echo $trackingKeyId; ?>' />
		<input type='hidden' id='showOnlyOTP_<?php echo $regFormId; ?>' name='showOnlyOTP' value='<?php echo $showOnlyOTP; ?>' />
		<input type='hidden' id='dumpResponseData_<?php echo $regFormId; ?>' name='dumpResponseData' value='<?php echo $dumpResponseData; ?>' />
		<div style="display: none">
			<div class="multiinput" id="isdCode_input_<?php echo $regFormId; ?>" style="line-height:8px; margin-top:0;">
				<span class="moreLnk"><?php echo $isdCodeMapping['abbreviation']; ?>
				</span> <span class="text">+<?php echo $isdCode; ?></span>
			</div>
		</div>
	<?php } ?>
</div>
<div class="acc-psnlInfo">
	<div class="regtbs clearfix">
		<div class="rstPse-txt fOTP">
			<p id='otp-iTxt' ></p><p class="otp-isText">One Time Password (OTP) has been sent to your mobile number(<span id='otp-mbl'></span>) <a href="javascript:void(0);" id="changeMobile" class="lyrEdit changeMobile" regformid="<?php echo $regFormId; ?>"><i class="editIcn"></i>Change Number</a></p>
		</div>
		<div class="reg-form invalid fOTP" id="otp_block_<?php echo $regFormId; ?>">
			<div class="ngPlaceholder">Enter OTP</div>
			<input class="ngInput otp-enter" type="text" name="one-time-pass" id="one-time-pass_<?php echo $regFormId; ?>"" minlength="1" maxlength="50" default="OTP" profanity="1" required="" tabindex="2" mandatory="1" caption="OTP">
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text" id="pin-Error_<?php echo $regFormId; ?>">Please Enter OTP.</div>
			</div>
		</div>
		<div style="display: none;" id="otpTryAgain"></div>
		<div class="reg-form invalid border-none invld-otp fOTP" id="sendOTP_<?php echo $regFormId; ?>">
			<p class='ques-txt-otp'>Did not receive OTP?</p>
			<a href="javascript:void(0);" class="otp-blk rsndclr rsnd-otp" regformid="<?php echo $regFormId; ?>">Resend OTP
			</a>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text" id='otp-msg'>OTP Sent</div>
			</div>
		</div>
		<a href="javascript:void(0);" class="reg-btn sup-btn usr-vfy-auth fOTP" tabindex="2" regformid="<?php echo $regFormId; ?>" style='right: 460px !important;'>Verify</a>

	 <?php 	$this->load->config('registration/registrationFormConfig');
				global $missedCallNumber;
			
		?>

		<?php if(USE_MISSED_CALL_VERIFICATION){ ?>
		<!--first step-->
	<div class="break_line fOTP">
	  <span>Or</span>
	</div>
	
	 <div class="try_a_call">
	 	<div id="misd_cal_lyr">
		<p class="give_a_call fOTP">Did not receive OTP on SMS?</p>
	        <p class="confirm_call mb_14">
	          <a style="font-size: 16px;" regformid="<?php echo $regFormId; ?>" class="blue_bold1 vfy_msd">Click Here to Verify via a missed call</a>
	        </p>
	        <p class="confirm_call">
	         from your mobile number (<span id="otp-mbl-1" ></span>)
	        </p>
	        <p class="free_call">This call is free of charge &amp; it will <br/> automatically disconnect </p>
	   </div>

	   <div  id="misd_cal_timr" style="display: none">
	   		<p class="confirm_call mb_14">This session will expire in</p>
	        <p class="confirm_call mb_14"><strong class="be_bold timer_cls">02:00</strong> minutes</p>
	        <p class="confirm_call">
	           Give a missed call to <a class="blue_bold" style="cursor: default;"><span><?php echo $missedCallNumber; ?></span></a> 
	        </p>
	         <p class="give_a_call">from your mobile number (<span id="otp-mbl-3" ></span>)</p>
	         <p class="free_call">This call is free of charge &amp; it will <br/> automatically disconnect </p>
	   </div>

		<div  id="misd_cal_tmout" style="display: none">
			<p class="err_num">Oops! The mobile number verification failed.</p>
		   <p class="ensure_num">Ensure you have given missed call from your mobile number (<span id="otp-mbl-4" ></span>)</p>
		   <p> <a regformid="<?php echo $regFormId; ?>" class="blue_bold msd_try">Try Again</a></p>
		</div>

	  </div>

	  <?php }?>

	</div>
</div>

<div  id="misd_cal_sccs" class="outer_block" style="display: none">
    <div class="middle_block">
      <div class="inner_cell">
	   <div class="check_marks">
               <div class="tick_mark"></div>
             </div>
         </div>
	<p>Your mobile number <strong>(<span id="otp-mbl-5"></span>)</strong> is verified</p>
  </div>
</div>

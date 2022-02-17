<div class="otp-col clearFix mobile-otp-layer" id="user-Authentication-otp_<?php echo $regFormId; ?>" <?php if($showVerificationLayer){echo 'style="display : block"';}else{ echo 'style="display : none"';} ?> >

      <div class="otp-box userOTPVerification" id="user-Authentication_<?php echo $regFormId; ?>">
          <a href="#" class="otp-cls close" data-rel="back">&times;</a>
          <p class="v-number">Verify Mobile Number</p>
          <p class="pin-sent">OTP has been sent to your mobile <span id="yourMobile_<?php echo $regFormId; ?>" class="yourMobile"><?php echo $mobile; ?></span></p>
          <?php if($changeMobileLink != 'no') { ?>
          <a href="javascript:void(0);" id="changeMobile" class="change-num changeMobile" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileLayer();">Change Number</a>
          <?php } ?>
          <div class="change-box changeMobileLayer" id="changeMobileLayer" style="display:none">
               <p class="label">Enter your new mobile number</p>
                 <p class="enter-m-number"> 
                  <input type="number" step="0.01" pattern="[0-9]*"  id="newMobileNumber_<?php echo $regFormId; ?>" maxLength="10" class="enter-pin enter-m-number" placeholder="ENTER MOBILE NUMBER" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                 </p>
                 <div id="mobile_errors" class="regErrorMsg regErrorMsgForOTP"></div>
                  <input type="button" class="common-btn" value="Submit" id="newMobileSubmit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobile();">
          </div>
             <div class="show-otp-enter-box enterMobilePin" id="enterMobilePin">
                <p class="enter-rcv-pin" >Please enter the OTP received on your mobile</p>
                  <input type="number" step="0.01" pattern="[0-9]*"  maxLength="4" class="enter-pin" placeholder="ENTER OTP" id="one-time-pass_<?php echo $regFormId; ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                    <a class="r-pin" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerificationLayer(null, 1);">Resend OTP</a> 
                    <div id="pin-Error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                  <input type="button" class="common-btn" value="Submit" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].userVerifyAuthentication();" >
              
             </div>
      </div>
   </div>
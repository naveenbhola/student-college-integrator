<div class="SgUp-lyr">
		<div class="head">
			<p class="head-title">Update Mobile Number</p>
			<a class="lyr-cls cmcross" id="otpEditBack_<?php echo $regFormId; ?>" location="-4">×</a>
			<div class="clear"></div>
		</div>
		<div class="Sgup-FrmSec">
			<div><p class="field-title">Enter your new Mobile Number.</p></div>
				<div class="">
						<div style="float:left; width:29%; position:relative;" class="signup-fld">
							
						<div class="invalid reg-form bdr-btm" style="text-align:center; line-height:53px;" extraClass="isd-lyr" label="Country" position="top" id="isdChangeMobile_<?php echo $regFormId; ?>">
								
							</div>
							<!-- <input type="text" style="border:1px solid #e6e6e6; height:52px;" tabindex="2" name="code" value="IND +91 " required="" class="ngInput codeFld"> -->
						</div>
						<div style="float:left;" data-enhance="false" id="newMobileNumber_block_<?php echo $regFormId; ?>" type="input" regfieldid="mobile" class="reg-form invalid mblFld">
							<div class="ngPlaceholder">Mobile Number</div>
								<input class="ngInput maxLength" type="tel" pattern="[0-9]*" caption="your Mobile Number" tabindex="2" name="newMobileNumber" id="newMobileNumber_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields" maxlength="10" minlength="10" oninput="userRegistrationRequest['<?php echo $regFormId; ?>'].digitsCheck(this);">
								<div class="input-helper">
									<div class="up-arrow"></div>
									<div class="helper-text" id="newMob-Error_<?php echo $regFormId; ?>">Please Enter Mobile Number.</div>
								</div>
						</div>
						<div class="clear"></div>
					</div>
				<div class="rst-lgn">
					<a tabindex="2" type="submit" class="reg-btn updt-snd" regformid="<?php echo $regFormId; ?>">Update & send OTP</a>
				</div>
			
		</div>
		
	</div>
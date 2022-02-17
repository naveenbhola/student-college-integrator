<!--change mobile number layer-->           
    <div class="regTab-cont">
    	<form>
    		<div class="regtbs clearfix">
    			<div class="">
    				<div class="reg-form signup-fld isdC" style="width:29%; float:left;" id="isdCodeOTP_block_<?php echo $regFormId; ?>" type="layer" regfieldid="isdCode">
    					<div class="invalid reg-form bdr-btm" extraClass="isd-lyr" label="Country" position="top" id="isdChangeMobile_<?php echo $regFormId; ?>">
    						<?php
                                if($customFormData['showOTPOnly'] == 'yes'){
                                    $preSelectedIsdCode = $customFormData['customFields']['isdCode']['value']; 
                                    $ISDCodeValues = $fields['isdCode']->getValues(array('source'=>'DB')); 
                            ?>
                                <span class="moreLnk"><?php echo $ISDCodeValues[$preSelectedIsdCode]['abbreviation']; ?></span> <span class="text">+<?php echo $ISDCodeValues[$preSelectedIsdCode]['isdCode']; ?></span>
                            <?php } ?>
    					</div>

    					<div class="cusLayer ctmScroll layerHtml ih">
    					</div>
    				</div>
    				<div class="reg-form invalid mblFld" id="newMobileNumber_block_<?php echo $regFormId; ?>" type="input" regfieldid="mobile">
    					<div class="ngPlaceholder">Mobile Number</div>
    					<input class="ngInput" type="text" tabindex="2" name="newMobileNumber_" id="newMobileNumber_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields" maxlength="10" minlength="10" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?>>
    					<div class="input-helper">
    						<div class="up-arrow"></div>
    						<div class="helper-text" id="newMob-Error_<?php echo $regFormId; ?>">Please Enter Mobile Number.</div>
    					</div>
    				</div>
    				<div class="clear"></div>
    			</div>
    		</div>
    		<div class="otp-send">
    			<a href="javascript:void(0);" id='newMobileSubmit' class="reg-btn newMobileSubmit" regformid="<?php echo $regFormId; ?>">Update & send OTP</a>
    		</div>
    	</form>

    </div>
<!--end of mobile change layer-->
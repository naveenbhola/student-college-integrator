<?php $customFormFields = $customFormData['customFields']; ?>
<!-- <div><p class="field-title" style="margin-top:10px;">Personal Info</p></div>					 -->
<div class="reg-form invalid <?php if(!empty($customFormFields['firstName']['hidden'])){ echo 'ih'; } if(!empty($customFormFields['firstName']['value'])){ echo ' filled'; } ?>" id="firstName_block_<?php echo $regFormId; ?>" type="input" regfieldid="firstName" >
	<div class="ngPlaceholder">First name</div>
	<input class="ngInput" type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" minlength="1" maxlength="50" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> required="" tabindex="2" mandatory="1" caption="stream of interest" value="<?php if(!empty($customFormFields['firstName']['value'])){ echo $customFormFields['firstName']['value']; } ?>">
	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text">Please enter first name.</div>
	</div>
</div>
<div class="reg-form invalid <?php if(!empty($customFormFields['lastName']['value']) && !empty($customFormFields['lastName']['hidden'])){ echo 'ih'; } if(!empty($customFormFields['lastName']['value'])){ echo ' filled'; } ?>" id="lastName_block_<?php echo $regFormId; ?>" type="input" regfieldid="lastName">
	<div class="ngPlaceholder">Last name</div>
	<input class="ngInput" type="text" required="" tabindex="2" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" minlength="1" maxlength="50" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> mandatory="1" caption="stream of interest" value="<?php if(!empty($customFormFields['lastName']['value'])){ echo $customFormFields['lastName']['value']; } ?>" >
	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text">Please enter last name.</div>
	</div>
</div>
<div class="">
	<?php $preSelectedIsdCode = $customFormFields['isdCode']['value']; 
		  $ISDCodeValues = $fields['isdCode']->getValues(array('source'=>'DB')); ?>
	<div class="reg-form signup-fld isdC <?php if(!empty($customFormFields['isdCode']['hidden']) && !empty($customFormFields['mobile']['value'])){ echo ' ih'; } if(!empty($customFormFields['isdCode']['value'])){ echo ' filled'; } ?>" style="width:35%; float:left; margin-right:0px !important;" id="isdCode_block_<?php echo $regFormId; ?>" type="layer" regfieldid="isdCode" layerHeading="Country">
		<div class="isdLayer invalid signup-fld bdr-btm" extraClass="isd-lyr" label="country" position="top" layerFor="isdCode" regFormId="<?php echo $regFormId; ?>" hasCustomSelection="yes" style="border-left:none;border-right:none;border-top:none;width:100%;" layerTitle="Country" layerHeading="Country">
			<div class="multiinput" id="isdCode_input_<?php echo $regFormId; ?>" style="line-height:8px; margin-top:0;">
				<span class="moreLnk"><?php echo $ISDCodeValues[$preSelectedIsdCode]['abbreviation']; ?></span> <span class="text">+<?php echo $ISDCodeValues[$preSelectedIsdCode]['isdCode']; ?></span>
			</div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please enter ISD code.</div>
			</div>
		</div>

		<div class="cusLayer ctmScroll layerHtml ih">
		</div>

		<div class="ih sValue">
            <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].triggerISDCodeChange(this);">
	            <?php foreach($ISDCodeValues as $key=>$value){ ?>
	                <option value="<?php echo $value['isdCode'].'-'.$value['shiksha_countryId']; ?>" <?php if($preSelectedIsdCode == $key){ echo 'selected';} ?> shrtDpl="<?php echo $value['abbreviation']; ?>" lrgDpl="<?php echo $value['isdCode']; ?>"> <?php echo $value['shiksha_countryName'].' (+'.$value['isdCode'].')'; ?> </option>
		         <?php } ?>
	        </select>
		</div>

	</div>
	<div style="height:51px;" class="reg-form invalid mblFld <?php if(!empty($customFormFields['mobile']['value']) && !empty($customFormFields['mobile']['hidden'])){ echo ' ih'; } if(!empty($customFormFields['mobile']['value'])){ echo ' filled'; } ?>" id="mobile_block_<?php echo $regFormId; ?>" type="input" regfieldid="mobile">
		<div class="ngPlaceholder">Mobile number</div>
		<input class="ngInput maxLength" type="tel" tabindex="2" pattern="[0-9]*" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields" maxlength="10" minlength="10" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> value="<?php if(!empty($customFormFields['mobile']['value'])){ echo $customFormFields['mobile']['value']; } ?>" oninput="userRegistrationRequest['<?php echo $regFormId; ?>'].digitsCheck(this);">
		<i class="d-info"></i>
          
          <div class="input-help">
			<div class="up-arrow"></div>
			<div class="help-text">Please enter correct number. One time password (OTP) will be sent for verification.</div>
		</div>

		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please enter mobile number.</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php
	/* Study abroad user */
	if(!empty($customFormFields['residenceCityLocality']['value']) && $customFormFields['residenceCityLocality']['value'] > 1){
		$customFormFields['residenceCity'] = $customFormData['customFields']['residenceCityLocality'];
	}
?>

<div class="posrel">
	<div class="reg-form signup-fld invalid ssLayer <?php if(!empty($customFormFields['residenceCityLocality']['hidden'])){ echo ' ih'; } if(!empty($customFormFields['residenceCityLocality']['value'])){ echo ' filled'; } ?>" position="top" extraClass="bt-pos" label="city" layerFor="residenceCityLocality" regFormId="<?php echo $regFormId; ?>" hasOptGroup="yes" type="layer" regfieldid="residenceCityLocality" id="residenceCityLocality_block_<?php echo $regFormId; ?>" layerTitle="City" planeSearch="yes" layerHeading="City" sub-label="Select one">
		<div class="ngPlaceholder">City you live in</div>
		<div class="multiinput" id="residenceCityLocality_input_<?php echo $regFormId; ?>"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please enter current city.</div>
		</div>
	</div>
	<div class="cusLayer ctmScroll layerHtml ih">
	</div>

	<div class="ih sValue">
	<select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getLocalitiesOfaCity(this.value);">
        <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('removeVirtualCities'=>'yes')); ?>
    </select>
	</div>

</div>

<div class="ih" id="locality_block_<?php echo $regFormId; ?>">
	
</div>

<?php 
if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){
	?>
	<div>
		<div class="reg-form invalid agreepolicy" id="agreeterms_block_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" regfieldid="agreeterms" type="checkbox">
			<input type="checkbox" <?php echo $countryName == 'INDIA' ? 'checked' : ''; ?> class="ngInput chk-bx"  id="agreeterms_<?php echo $regFormId; ?>" regFieldId="agreeterms" caption="accept the Privacy Policy and T&C" />
			<label  class="agreeterms" for="agreeterms_<?php echo $regFormId; ?>"></label>
			<p class="policyTxt"> Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy </a>and the <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition" target="_blank">Terms and Conditions.</a></p>
			<div class="input-helper errdisplay">
				<div class="up-arrow"></div>
				<div class="helper-text">Please accept privacy terms.</div>
			</div>
		</div>
		<div class="reg-form invalid agreepolicy" id="serviceterms_block_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" regfieldid="serviceterms" type="checkbox">
			<input type="checkbox" <?php echo $countryName == 'INDIA' ? 'checked' : ''; ?> class="ngInput chk-bx" id="serviceterms_<?php echo $regFormId; ?>" regFieldId="serviceterms" caption="provide permission to be contacted" />
			<label class="agreeterms" for="serviceterms_<?php echo $regFormId; ?>"></label>
			<div class="policyTxt">I agree to be contacted for service related information and promotional purposes.
				<div class="reg-info-icn">
					<i class="d-info"></i>
					<div class="up-arrow"></div>
				</div>
		        <div class="input-help tm">
					<div class="help-text">I can edit my communication preferences at any time in “My Profile“ section and/or may withdraw and/or restrict my consent in full or in part.</div>
				</div>
			</div>
			<div class="input-helper errdisplay">
				<div class="up-arrow"></div>
				<div class="helper-text">Please accept privacy terms.</div>
			</div>
		</div>
	</div>
<?php
}
?>
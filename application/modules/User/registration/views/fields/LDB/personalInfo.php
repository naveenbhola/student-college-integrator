<?php $customFormFields = $customFormData['customFields']; 
	if ($customFormFields['mmpFormId'] && $customFormFields["language"]== 'hi'){
		$this->config->load('multipleMarketingPage/MMPConfig', TRUE);
		$MMPConfigForHindiFont = $this->config->item('MMPConfig');
		$hindiFont = $MMPConfigForHindiFont['hindiFont'][$customFormFields['mmpFormId']];
		unset($MMPConfigForHindiFont);
	}
?>
<!-- <div><p class="field-title" style="margin-top:10px;">Personal Info</p></div>		
			 -->
<div <?php if(empty($customFormFields['firstName']['hidden']) || empty($customFormFields['lastName']['value'])){ ?>class="table-block" <?php } ?>>
<div class="reg-form invalid <?php if(!empty($customFormFields['firstName']['hidden'])){ echo ' ih'; } if(!empty($customFormFields['firstName']['value'])){ echo ' filled'; } ?>" id="firstName_block_<?php echo $regFormId; ?>" type="input" regfieldid="firstName" >
	<div class="ngPlaceholder">First name</div>
	<input class="ngInput" type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" minlength="1" maxlength="50" default="First name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> required="" tabindex="2" mandatory="1" caption="stream of interest" value="<?php if(!empty($customFormFields['firstName']['value'])){ echo $customFormFields['firstName']['value']; } ?>" <?php if(!empty($customFormFields['firstName']['disabled']) && !empty($customFormFields['lastName']['value'])){ echo 'disabled'; } ?>>
	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text">Please Enter First name.</div>
	</div>
</div>
<div class="reg-form invalid <?php if(!empty($customFormFields['lastName']['value']) && !empty($customFormFields['lastName']['hidden'])){ echo ' ih'; } if(!empty($customFormFields['lastName']['value'])){ echo ' filled'; } ?>" id="lastName_block_<?php echo $regFormId; ?>" type="input" regfieldid="lastName">
	<div class="ngPlaceholder">Last name</div>
	<input class="ngInput" type="text" required="" tabindex="2" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" minlength="1" maxlength="50" default="Last name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> mandatory="1" caption="stream of interest" value="<?php if(!empty($customFormFields['lastName']['value'])){ echo $customFormFields['lastName']['value']; } ?>" <?php if(!empty($customFormFields['lastName']['value']) && !empty($customFormFields['lastName']['disabled'])){ echo 'disabled'; } ?>>
	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text">Please Enter Last name.</div>
	</div>
</div>
</div>
<div class="reg-form-mobile <?php if(!empty($customFormFields['mobile']['value']) && !empty($customFormFields['mobile']['hidden'])){ echo ' ih'; } ?>">
	<?php $preSelectedIsdCode = $customFormFields['isdCode']['value']; 
		  $ISDCodeValues = $fields['isdCode']->getValues(array('source'=>'DB')); ?>
	<div class="reg-form signup-fld isdC <?php if(!empty($customFormFields['isdCode']['hidden']) && !empty($customFormFields['mobile']['value'])){ echo ' ih'; } if(!empty($customFormFields['isdCode']['value'])){ echo ' filled'; } ?>" style="width:29%; float:left;" id="isdCode_block_<?php echo $regFormId; ?>" type="layer" regfieldid="isdCode">
		<div class="csLayer invalid reg-form signup-fld bdr-btm  <?php if(!empty($customFormFields['isdCode']['hidden']) && !empty($customFormFields['mobile']['value'])){ echo ' isdCD'; } ?> " extraClass="isd-lyr" label="Country" position="<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes' && 0){ echo 'top'; }else{ echo 'bottom';} ?>" layerFor="isdCode"  tabindex="2" regFormId="<?php echo $regFormId; ?>" hasCustomSelection="yes">
			<div class="multiinput" id="isdCode_input_<?php echo $regFormId; ?>" style="line-height:8px">
				<span class="moreLnk"><?php echo $ISDCodeValues[$preSelectedIsdCode]['abbreviation']; ?></span> <span class="text">+<?php echo $ISDCodeValues[$preSelectedIsdCode]['isdCode']; ?></span>
			</div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please Enter ISD Code.</div>
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
	<div class="reg-form invalid mblFld <?php if(!empty($customFormFields['mobile']['value']) && !empty($customFormFields['mobile']['hidden'])){ echo ' ih'; } if(!empty($customFormFields['mobile']['value'])){ echo ' filled'; } ?>" id="mobile_block_<?php echo $regFormId; ?>" type="input" regfieldid="mobile">
		<div class="ngPlaceholder">Mobile number</div>
		<input class="ngInput" type="tel" tabindex="2" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields" pattern="[0-9]*" maxlength="10" minlength="10" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> value="<?php if(!empty($customFormFields['mobile']['value'])){ echo $customFormFields['mobile']['value']; } ?>" oninput="userRegistrationRequest['<?php echo $regFormId; ?>'].digitsCheck(this);">
		<i class="d-info"></i>
	    <div class="input-help ds">
			<div class="up-arrow"></div>
			<div class="help-text">Please enter correct number. One time password (OTP) will be sent for verification.</div>
		</div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Mobile number.</div>
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
	<div class="reg-form signup-fld invalid ssLayer" tabindex="2" position="<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){ echo 'top'; }else{ echo 'bottom';} ?>" extraClass="<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){ echo 'bt-pos'; } ?>" label="City" layerFor="residenceCityLocality" regFormId="<?php echo $regFormId; ?>" hasOptGroup="yes" type="layer" regfieldid="residenceCityLocality" id="residenceCityLocality_block_<?php echo $regFormId; ?>">
		<div class="ngPlaceholder">City you live in</div>
		<div class="multiinput" id="residenceCityLocality_input_<?php echo $regFormId; ?>"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter City you live in.</div>
		</div>
	</div>
	<div class="cusLayer ctmScroll layerHtml ih">
	</div>

	<div class="ih sValue">
	<select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getLocalitiesOfaCity(this.value);">
        <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('residenceCityLocality'=>$customFormFields['residenceCity']['value'], 'isUnifiedProfile'=>'YES', 'removeVirtualCities'=>'yes')); ?>
    </select>
	</div>

</div>

<div class="posrel ih" id="locality_block_<?php echo $regFormId; ?>">
	
</div>

<?php 
if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){
	?>
	<div>
		<div class="reg-form invalid agreepolicy" id="agreeterms_block_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" regfieldid="agreeterms" type="checkbox">
			<input type="checkbox" <?php echo $countryName == 'INDIA' ? 'checked' : ''; ?> class="ngInput chk-bx"  id="agreeterms_<?php echo $regFormId; ?>" regFieldId="agreeterms" caption="accept the Privacy Policy and T&C" />
			<label  class="agreeterms" for="agreeterms_<?php echo $regFormId; ?>"></label>
			<p class="policyTxt"> 
				<?php if (!empty($hindiFont)){
					echo $hindiFont['agreeTerms'];
				?>
				<?php }else {?>
				Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy </a>and the <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition" target="_blank">Terms and Conditions.
				</a>
				<?php }?>
			</p>
			<div class="input-helper errdisplay">
				<div class="up-arrow"></div>
				<div class="helper-text">Please accept privacy terms.</div>
			</div>
		</div>
		<div class="reg-form invalid agreepolicy" id="serviceterms_block_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" regfieldid="serviceterms" type="checkbox">
			<input type="checkbox" <?php echo $countryName == 'INDIA' ? 'checked' : ''; ?> class="ngInput chk-bx" id="serviceterms_<?php echo $regFormId; ?>" regFieldId="serviceterms" caption="provide permission to be contacted" />
			<label class="agreeterms" for="serviceterms_<?php echo $regFormId; ?>"></label>
			<div class="policyTxt">
				<?php if (!empty($hindiFont)){
					echo $hindiFont["serviceTerms"]; ?>

				<?php }else {?>
				I agree to be contacted for service related information and promotional purposes.

				<?php }?>

				<span class="reg-info-icn"><i class="d-info"></i></span>
		        <div class="input-help tm">
					<div class="up-arrow"></div>
					<div class="help-text">
					<?php if (!empty($hindiFont)){
						echo $hindiFont["hoverTextOnTerms"]; ?>
					<?php }else {?>
					I can edit my communication preferences at any time in “My Profile“ section and/or may withdraw and/or restrict my consent in full or in part.
					<?php }?>
				</div>
				
				</div>
				<div class="input-helper errdisplay">
					<div class="up-arrow"></div>
					<div class="helper-text">Please accept privacy terms.</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>

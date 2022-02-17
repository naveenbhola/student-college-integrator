<?php

if(isset($fields['residenceCity'])){ ?>
	<div class="account-setting-fields flLt">
		<label>Current city</label>
		<div class="custom-dropdown">
			<select name="residenceCity" class="universal-select signup-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
				<?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
			</select>
	   </div>
	   <div><div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div></div>
	</div>
<?php } ?>
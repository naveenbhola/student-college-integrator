<?php
if(isset($fields['residenceCity'])){ ?>
<label>Residence Location<span><?php echo $registrationHelper->markMandatory('residenceCity'); ?></span></label>
	<div class="frmoutBx">
	    <select style="padding:5px 5px;" name="residenceCity" class="inptBx" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
	    <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Select your residence location', 'order' => 'alphabetical', 'formData'=>$formData)); ?>
	    </select>
	    <div>
	        <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
	    </div>
	</div>

	<?php } 
?>
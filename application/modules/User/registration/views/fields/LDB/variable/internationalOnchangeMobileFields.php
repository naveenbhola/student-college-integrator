<?php
	

	if(isset($fields['emptyResidenceCity']) && 0){ ?>  
       
       <div class="custom-dropdown" >
            <select class="universal-select" >
               <option value="-1" > City not required </option>
            </select>

        </div>

<?php } 

	if(isset($fields['residenceCity'])){ ?>
		<div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <select name="residenceCity" class="universal-select" data-enhance = "false" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical')); ?>
            </select>
            <div style="display:none;">
                <div class="errorMsg error-msg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
        </div>
 <?php } ?>
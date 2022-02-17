<?php

if(isset($fields['residenceCity'])){ ?>
	<select name="residenceCity" class="universal-select universal-select-2" <?=($disabled)?> data-enhance = "false" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
            </select>
            <div style="display:none;">
                <div class="errorMsg error-msg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
<?php } ?>
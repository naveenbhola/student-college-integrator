

<?php
if(isset($fields['residenceCity'])){
?>
	<span class="colon-separater">:&nbsp;</span>
	<div class="fields-col">
		<select name="residenceCity" class="universal-select"
			id="residenceCity_<?php echo $regFormId; ?>"
			<?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?>
			onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"
			onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Select your residence location', 'order' => 'alphabetical', 'formData'=>$formData)); ?>
                        </select>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div>
	<?php } ?>
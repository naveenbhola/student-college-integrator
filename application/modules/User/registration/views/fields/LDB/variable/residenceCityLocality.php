<div <?php echo $registrationHelper->getBlockCustomAttributes('residenceCityLocality'); ?>>
	<div class="custom-dropdown" style="width: 100%">
		<select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getLocalitiesOfaCity(this.value);">
		<?php $this->load->view('registration/common/dropdowns/residenceLocality', array('isUnifiedProfile'=>'YES', 'fields'=>$fields)); ?>
		</select>
	</div>
	<div>
		<div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
	</div>
</div>
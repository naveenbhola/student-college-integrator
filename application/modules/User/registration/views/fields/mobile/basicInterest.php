

<?php 
	$streams = array();
	
	$customStreamIds = array();
	$customFormFields = $customFormData['customFields'];
	if(!empty($customFormData['customFieldValueSource'])){
		$customStreamIds = array_keys($customFormData['customFieldValueSource']);
	}

	$streamDrpDown = '';
	$streams = $fields['stream']->getValues(array('customStreamIds'=>$customStreamIds));
	foreach($streams as $key => $streamValues) { 
		$selected = '';
		if($streamValues['id'] == $customFormFields['stream']['value']){
			$selected = 'selected';
		}
		$streams[$streamValues['id']] = $streamValues['name'];
		$streamDrpDown .= '<option value="'.$streamValues['id'].'" '.$selected.'>'.$streamValues['name'].'</option>';
 } ?>

<div><p class="field-title">Tell us the kind of courses &amp; programs you are looking for. We will send you insights, recommendations &amp; updates.</p></div>
<div <?php if(!empty($customFormFields['stream']['hidden'])){ echo 'class="ih"'; } ?> >
	<div style="position:relative;">

		<!-- defocused -->
		<div class="reg-form signup-fld invalid <?php if(empty($customFormFields['stream']['disabled'])) { echo 'sLayer'; }else{ echo 'disabled'; } if(!empty($customFormFields['stream']['value'])){ echo ' filled'; } if(!empty($customFormFields['stream']['hidden'])){ echo ' hdn'; } ?>" layerFor="stream" regFormId="<?php echo $regFormId; ?>" regfieldid="stream" id="stream_block_<?php echo $regFormId; ?>" type="layer" layerHeading="Education Stream" sub-label="Select one">
			<div class="ngPlaceholder">Education Stream</div>
			<div class="multiinput" id="stream_input_<?php echo $regFormId; ?>"> <?php if(!empty($customFormFields['stream']['value'])){ echo $streams[$customFormFields['stream']['value']]; } ?></div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please Enter Stream.</div>
			</div>
		</div>
		<div class="ih sValue" data-role="none">
			<select id="stream_<?php echo $regFormId; ?>" name="stream" onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream(this);" <?php echo $registrationHelper->getFieldCustomAttributes('stream'); ?> class="remCl" data-role="none">
				<option value="-1">Select Stream</option>
				<?php echo $streamDrpDown; ?>
			</select>
		</div>
	</div>
</div>
<div id="userInterestArea_<?php echo $regFormId; ?>">
	<?php $this->load->view('registration/fields/mobile/variable/defaultFields'); ?>
</div>
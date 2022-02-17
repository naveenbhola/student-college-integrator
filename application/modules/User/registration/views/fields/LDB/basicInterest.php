
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

<div><p class="field-title ft-fix">Tell us the kind of courses &amp; programs you are looking for. We will send you insights, recommendations &amp; updates.</p></div>
<div <?php if(!empty($customFormFields['stream']['hidden'])){ echo 'class="ih"'; } ?> >
<div style="position:relative;">

	<!-- defocused -->
	<div class="reg-form signup-fld invalid <?php if(empty($customFormFields['stream']['disabled'])) { echo 'sLayer'; }else{ echo 'disabled'; } ?> <?php if(!empty($customFormFields['stream']['value'])){ echo 'filled'; } ?> <?php if(!empty($customFormFields['stream']['hidden'])){ echo 'ih'; } ?>" layerFor="stream" regFormId="<?php echo $regFormId; ?>" regfieldid="stream" id="stream_block_<?php echo $regFormId; ?>" type="layer">
		<div class="ngPlaceholder">Education Stream</div>
		<div class="multiinput" id="stream_input_<?php echo $regFormId; ?>"> <?php if(!empty($customFormFields['stream']['value'])){ echo $streams[$customFormFields['stream']['value']]; } ?></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Stream.</div>
		</div>
	</div>
	<div class="layerHtml ih">

	</div>
	<div class="ih sValue">
		<select id="stream_<?php echo $regFormId; ?>" class="streamFld" regformid="<?php echo $regFormId; ?>" name="stream" <?php echo $registrationHelper->getFieldCustomAttributes('stream'); ?> class="remCl">
			<option value="-1">Select Stream</option>
			<?php echo $streamDrpDown; ?>
			</select>
		</div>
	</div>
</div>
	<div id="userInterestArea_<?php echo $regFormId; ?>">
		<?php $this->load->view('registration/fields/LDB/defaultFields'); ?>
	</div>

	<div id="emptyFirstScreenHandler_<?php echo $regFormId; ?>">
	</div>
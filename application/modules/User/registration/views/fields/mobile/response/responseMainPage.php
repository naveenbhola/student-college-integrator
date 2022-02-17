<?php 
	echo $this->load->view('registration/fields/mobile/response/preferredLocations'); 

	if(!empty($workExList)){ 
		echo $this->load->view('registration/fields/mobile/response/workExp'); 
	}
?>

<div><p class="field-title mt10p">The course you're interested in is available in multiple streams. What is your preferred stream?</p></div>
	<div style="position:relative;">

	<!-- defocused -->
	<div class="reg-form signup-fld invalid <?php if(empty($customFormFields['stream']['disabled'])) { echo 'sLayer'; } ?> <?php if(!empty($customFormFields['stream']['value'])){ echo 'filled'; } ?>" layerFor="stream" regFormId="<?php echo $regFormId; ?>" regfieldid="stream" id="stream_block_<?php echo $regFormId; ?>" type="layer">
		<div class="ngPlaceholder">Education Stream</div>
		<div class="multiinput" id="stream_input_<?php echo $regFormId; ?>"> <?php if(!empty($customFormFields['stream']['value'])){ echo $streams[$customFormFields['stream']['value']]; } ?></div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please enter stream.</div>
			</div>
		</div>
		<div class="layerHtml ih">

		</div>
		<div class="ih sValue">
			<select id="stream_<?php echo $regFormId; ?>" class="RespStmFld" regformid="<?php echo $regFormId; ?>" name="stream" class="remCl" caption="Stream">
				<option value="-1">Select Stream</option>
				<?php foreach ($streams as $key => $streamObj) { ?>
					<option value="<?php echo $key; ?>"> <?php echo $streamObj->getName(); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
</div>

<div id="dependentFields_<?php echo $regFormId; ?>">
	
</div>
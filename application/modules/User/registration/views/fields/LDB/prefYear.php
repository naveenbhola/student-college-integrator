<?php 
$customFormFields = $customFormData['customFields'];
$prefYearData = $fields['prefYear']->getValues();
if (!empty($userDetails['prefYear'])) {
    $customFormFields['prefYear']['value'] = $userDetails['prefYear'];
}
?>
<div class="posrel">
	<div class="reg-form signup-fld invalid <?php if(empty($customFormFields['prefYear']['disabled'])) { echo 'sLayer'; } ?> <?php if(!empty($customFormFields['prefYear']['value'])){ echo 'filled ih'; } ?> <?php if(!empty($customFormFields['prefYear']['hidden'])){ echo 'ih'; } ?>" layerFor="prefYear" regFormId="<?php echo $regFormId; ?>" type="layer" regfieldid="prefYear" id="prefYear_block_<?php echo $regFormId; ?>">
		<div class="ngPlaceholder">Preferred Year of Admission <?php if(!PREF_YEAR_MANDATORY){ ?><span class="optl">(Optional)</span><?php } ?></div>
		<div class="multiinput" id="prefYear_input_<?php echo $regFormId; ?>"> <?php if(!empty($customFormFields['prefYear']['value'])){ echo $prefYearData[$customFormFields['prefYear']['value']]; } ?></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Preferred Year of Admission.</div>
		</div>
	</div>
	<div class="layerHtml ih">
	</div>

	<div class="ih sValue">
		<select id="prefYear_<?php echo $regFormId; ?>" name="prefYear" regformid="<?php echo $regFormId; ?>" caption="<?php echo $fields['prefYear']->getCaption(); ?>">
			<option value="">Select Preferred Year of Admission</option>
			<?php 
			if($customFormFields['prefYear']['value'] != '') {
				echo '<option value="'.$customFormFields['prefYear']['value'].'" selected>'.$customFormFields['prefYear']['value'].'</option>';
			}
			foreach ($prefYearData as $key => $value) {
				?>
				<option value="<?php echo $key; ?>" <?php echo ($customFormFields['prefYear']['value'] == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
				<?php
			}
			?>
	    </select>
	</div>

</div>

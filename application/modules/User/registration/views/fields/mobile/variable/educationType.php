<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative" >
	<div class="reg-form signup-fld invalid tDis" layerTitle="Mode of Study" regfieldid="educationType" id="educationType_block_<?php echo $regFormId; ?>" hasSearch='0' mandatory="1" caption="choose education type" type="layer" layerHeading="Mode of study" sub-label="Select one or more">
		<div class="ngPlaceholder">Mode of Study</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please select Mode of Study.</div>
		</div>
	</div>
	<div class="layerHtml ih" id="eduTypeField_<?php echo $regFormId; ?>">
		<?php $this->load->view('registration/fields/mobile/variable/educationTypeFields'); ?>
	</div>
	<div class="ih">
		<input type="hidden" flow="<?php echo $flow; ?>" id="loadEMDependencies_<?php echo $regFormId; ?>" onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getSubStreamSpecFieldValues(this)">
	</div>
</div>
<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative" >
	
	<div class="reg-form signup-fld invalid tDis" layerFor="subStreamSpec" regfieldid="subStreamSpecializations" id="subStreamSpecializations_block_<?php echo $regFormId; ?>" mandatory="<?php if($isSpecMand == 'no'){ echo '0'; }else{ echo '1'; } ?>" caption="Choose Specializations" type="layer" hasSearch='1' layerTitle="Specialization" layerHeading="Specialization" sub-label="Select one or more">
		<div class="ngPlaceholder">Specialization <span class="optl">(Optional)</span></div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Specialization.</div>
		</div>
	</div>

	<div class="layerHtml ih" id="subStreamSpecField_<?php echo $regFormId; ?>">
		<?php $this->load->view('registration/fields/mobile/variable/subStreamSpecField'); ?>
	</div>
	<div class="ih onchg">
		<input type="hidden" flow="<?php echo $flow; ?>" id="loadSpecDependencies_<?php echo $regFormId; ?>" onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getBaseCoursesValues(this)">
	</div>

	</div>
</div>
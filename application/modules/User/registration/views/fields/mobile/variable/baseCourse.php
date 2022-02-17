<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative" >
	<div class="reg-form signup-fld invalid tDis" layerFor="baseCourses" regfieldid="baseCourses" id="baseCourses_block_<?php echo $regFormId; ?>" hasSearch='1' type="layer" <?php echo $registrationHelper->getFieldCustomAttributes('baseCourses'); ?> layerTitle="Course" addClass="twinSelect levelId"  group-restn='yes' layerHeading="Course" sub-label="Select one or more">
		<div class="ngPlaceholder">Course</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Courses.</div>
		</div>
	</div>

	<div class="layerHtml ih" id="baseCourseField_<?php echo $regFormId; ?>"">
		<?php $this->load->view('registration/fields/mobile/variable/baseCourseField'); ?>
	</div>
	<div class="ih onchg">
		<input type="hidden" flow="<?php echo $flow; ?>" id="loadBCDependencies_<?php echo $regFormId; ?>" onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getSubStreamSpecFieldValues(this);">
	</div>
</div>
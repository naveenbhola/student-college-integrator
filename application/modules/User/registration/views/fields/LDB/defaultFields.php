<div class="selectionType" style="position:relative">
	<div class="reg-form signup-fld disabled" layerFor="baseCourses" regfieldid="baseCourses" id="baseCourses_block_<?php echo $regFormId; ?>" hasSearch='1' type="layer">
		<div class="ngPlaceholder">Course</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Course.</div>
		</div>
	</div>
</div>

<div class="selectionType" style="position:relative" >
	<div class="reg-form signup-fld disabled" layerFor="subStreamSpec" regfieldid="subStreamSpecializations" id="subStreamSpecializations_block_<?php echo $regFormId; ?>" caption="Choose Specializations" type="layer" hasSearch='1'>
		<div class="ngPlaceholder">Specialization <span class="optl">(Optional)</span></div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Specialization.</div>
		</div>
	</div>
</div>

<div style="position:relative">
	<div class="reg-form signup-fld disabled" layerFor="baseCourses" regfieldid="educationType" id="educationType_block_<?php echo $regFormId; ?>" hasSearch='0' mandatory="1" caption="choose education type" type="layer">
		<div class="ngPlaceholder">Mode of Study</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please select Mode of Study.</div>
		</div>
	</div>
</div>
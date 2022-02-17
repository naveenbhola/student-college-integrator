<div class="stu-prf-sec invalid" id="flowChoice_block_<?php echo $regFormId; ?>" type="radio"> 
	<p class="field-title">Do you want to proceed by course or specialization?</p>
	<div class="lg-radio" regfieldid="flowChoice" >

		<div class="customRdChkbx rad-padng">
			<input type="radio" id="flow_course_<?php echo $regFormId; ?>" class="fchoice remCl" name="flow" value="course" regFormId="<?php echo $regFormId; ?>">
            <label for="flow_course_<?php echo $regFormId; ?>"><span>Course</span></label>
        </div>
        <div class="customRdChkbx rad-padng">
			<input type="radio" id="flow_specialization_<?php echo $regFormId; ?>" class="fchoice remCl" name="flow" value="specialization" regFormId="<?php echo $regFormId; ?>">
            <label for="flow_specialization_<?php echo $regFormId; ?>"><span>Specialization</span></label>
        </div>

	</div>
	<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please select your choice.</div>
	</div>
		
</div>

<div id="flowChose_<?php echo $regFormId; ?>">
</div>
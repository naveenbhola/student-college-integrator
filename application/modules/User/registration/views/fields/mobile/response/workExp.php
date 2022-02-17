<!-- work Experience -->
<?php if(!empty($workExList) && $workExperience > -2){ ?>
	<input type="hidden" name="workExperience" value="<?php echo $workExperience; ?>" />
<?php } else if (!empty($workExList)) { ?>
	
	<?php if(!empty($userDetails['experience'])){ 
 		$customFormFields['workExp']['value'] = $userDetails['experience'];
	} ?>
	<div style="position:relative;">

		<!-- defocused -->
		<div class="reg-form signup-fld invalid <?php if(empty($customFormFields['workExp']['disabled'])) { echo 'sLayer'; } ?> <?php if(!empty($customFormFields['workExp']['value'])){ echo 'filled ih'; } ?>" layerFor="workExp" regFormId="<?php echo $regFormId; ?>" regfieldid="workExp" id="workExp_block_<?php echo $regFormId; ?>" type="layer" incDef="1" layerHeading="Work experience" sub-label="Select one">
			<div class="ngPlaceholder">Work experience</div>
			<div class="multiinput" id="workExp_input_<?php echo $regFormId; ?>"> <?php if(!empty($customFormFields['workExp']['value'])){ echo $workExList[$customFormFields['workExp']['value']]; } ?></div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please enter your work experience.</div>
			</div>
		</div>
		<div class="layerHtml ih">

		</div>
		<div class="ih sValue">

			<select id="workExp_<?php echo $regFormId; ?>" class="workExpFld" regformid="<?php echo $regFormId; ?>" name="workExperience" caption="Work Experience">
			<option value="">Select work experience</option>
				<?php foreach($workExList as $key=>$value){ ?>
					<option value="<?php echo $key;?>" <?php if(isset($customFormFields['workExp']['value']) && $key == $customFormFields['workExp']['value']){ echo 'selected'; } ?>><?php echo $value; ?> </option>
				<?php } ?>
			</select>
		</div>
	</div>
<?php } ?>

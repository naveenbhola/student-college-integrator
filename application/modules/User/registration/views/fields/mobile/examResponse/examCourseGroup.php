<?php if(!empty($customFormData['groupMappedArr'])){ 
		$groupsArray = array(); $selectedValue = ''; ?>
	<?php 	foreach($customFormData['groupMappedArr'] as $index => $groupDetails){
				if($groupDetails['id'] == $customFormData['clientCourseId']) { 
					$selectedValue = $groupDetails['name']; 
				}
				$groupsArray[$groupDetails['id']] = $groupDetails['name'];
			} 
			asort($groupsArray);
	?>
	<div>
		<div style="position:relative;">
			<div class="reg-form signup-fld invalid ssLayer <?php if($selectedValue) echo 'filled'; ?>" layerFor="clientCourse" regFormId="<?php echo $regFormId; ?>" regfieldid="clientCourse" id="clientCourse_block_<?php echo $regFormId; ?>" withoutOptGrp="yes" type="layer" label="Course" layertitle="Course" layerHeading="Course" sub-label="Select one" hasSearch='1'>
				<div class="ngPlaceholder">Course you're interested in</div>
				<div class="multiinput" id="clientCourse_input_<?php echo $regFormId; ?>"><?php echo $selectedValue; ?></div>
				<div class="input-helper">
					<div class="up-arrow"></div>
					<div class="helper-text">Please Enter course.</div>
				</div>
			</div>

			<div class="cusLayer ctmScroll layerHtml ih">

			</div>
			
			<div class="ih sValue">
				<select id="clientCourse_<?php echo $regFormId; ?>" class="egFld" regformid="<?php echo $regFormId; ?>" name="clientCourse" mandatory="1" caption="Course">
					<?php foreach($groupsArray as $groupId => $groupName){ ?>
 						<option value="<?php echo $groupId; ?>" <?php if($groupId == $customFormData['clientCourseId']) echo 'selected'; ?> ><?php echo $groupName; ?></option>
 					<?php } ?>
				</select>
			</div>
		</div>
	</div>
<?php }else if(empty($customFormData['groupMappedArr']) && !empty($customFormData['clientCourseId'])){ ?>
		<input type="hidden" name="clientCourse" value="<?php echo $customFormData['clientCourseId']; ?>" id="clientCourse_<?php echo $regFormId; ?>" class="egFld" regformid="<?php echo $regFormId; ?>"/>
<!--    <div class="emptyFix">
		</div> -->
<?php } ?>
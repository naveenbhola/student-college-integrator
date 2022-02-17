<?php 
if(!empty($customFormData['institute_courses'])){ ?>
	<div>
		<!-- <div><p class="field-title">Please select client course</p></div> -->
		<div style="position:relative;">
			<div class="reg-form signup-fld invalid ssLayer" layerFor="clientCourse" regFormId="<?php echo $regFormId; ?>" regfieldid="clientCourse" id="clientCourse_block_<?php echo $regFormId; ?>" type="layer" hasOptGroup="yes" type="layer" label="Course" planesearch="yes" layertitle="Course" layerHeading="Course" sub-label="Select one">
				<div class="ngPlaceholder">Course <?php echo $courseDDLabel; ?></div>
				<div class="multiinput" id="clientCourse_input_<?php echo $regFormId; ?>"> </div>
				<div class="input-helper">
					<div class="up-arrow"></div>
					<div class="helper-text">Please enter course.</div>
				</div>
			</div>

			<div class="cusLayer ctmScroll layerHtml ih">

			</div>
			
			<div class="ih sValue">
				<select id="clientCourse_<?php echo $regFormId; ?>" class="ccFld" regformid="<?php echo $regFormId; ?>" name="clientCourse" mandatory="1" caption="Course">
					<option value="">Current City</option>
					<?php foreach($customFormData['institute_courses'] as $listing_id => $listingName_Data){ ?>
 						<optgroup label="<?php echo $listingName_Data['name']; ?>">
 						<?php foreach ($listingName_Data['courses'] as $id => $name) { ?>
 							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
 						<?php }
					} ?>
					</optgroup>
				</select>
			</div>
		</div>
	</div>
<?php }else if(empty($customFormData['institute_courses']) && !empty($customFormData['clientCourseId'])){ ?>
		<input type="hidden" name="clientCourse" value="<?php echo $customFormData['clientCourseId']; ?>" id="clientCourse_<?php echo $regFormId; ?>" class="ccFld" regformid="<?php echo $regFormId; ?>"/>
<!-- 
		<div class="emptyFix">
			
		</div> -->
<?php } ?>
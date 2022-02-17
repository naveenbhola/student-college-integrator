<h3>Responses</h3>
<?php
	if((count($instituteList) && $extraFlag !== 'studyabroad') || (count($universityList) && $extraFlag == 'studyabroad')) {
?>
<div class="form-section">
	<ul>
		<?php if(count($universityList)) { ?>
		<li>
			<label>Universities:</label>
			<div class="form-fields">
				<div class="course-box">
					<div id="all_universities_holder">
						<input type="checkbox" id="all_universities" onClick="checkUncheckChilds1(this, 'all_universities_holder');" <?php echo (count($universityList) == count($portingConditions['university']) ? 'checked' : ''); ?> /><b>All</b>
					<?php
						foreach ($universityList as $universityId => $universityData) {
					?>
						<ol id="<?php echo $universityId; ?>_university_holder">
							<li><input type="checkbox" name="university_id[]" id="<?php echo $universityId; ?>_all_courses" onClick="checkUncheckChilds1(this, '<?php echo $universityId; ?>_university_holder');uncheckElement1(this, 'all_universities', 'all_universities_holder');" <?php echo (!empty($portingConditions['university'][$universityId]) ? 'checked' : ''); ?> value="<?php echo $universityId; ?>" /><b><?php echo $universityData['universityDetails']['listing_title']; ?></b></li>
						<?php
							foreach ($universityData['courseList'] as $courseId => $courseTitle) {
						?>
							<li style="padding-left:18px;"><input type="checkbox" name="course_id[]" onClick="uncheckElement1(this, '<?php echo $universityId; ?>_all_courses', '<?php echo $universityId; ?>_university_holder');uncheckElement1(this, 'all_universities', 'all_universities_holder');" <?php echo (!empty($portingConditions['course'][$courseId]) ? 'checked' : ''); ?> value="<?php echo $courseId; ?>" /><?php echo $courseTitle; ?></li>
						<?php
							}
						?>
						</ol>
					<?php
						}
					?>
					</div>
				</div>
			</div>
		</li>
		<?php } 
		if(count($instituteList)) { ?>
		<li>
			<label>Universities and Colleges:</label>
			<div class="form-fields">
				<div class="course-box">
					<div id="all_institue_holder">
						<input type="checkbox" id="all_institutes" onClick="checkUncheckChilds1(this, 'all_institue_holder');" <?php echo (count($instituteList) == count($portingConditions['institute']) ? 'checked' : ''); ?> /><b>All</b>
						<?php
							foreach ($instituteList as $instituteId => $instituteCourseData) {
								if($instituteCourseData['listing_type'] == 'university') {
						?>
							<ol id="<?php echo $instituteId; ?>_institue_holder">
								<li><input type="checkbox" name="institute_id[]" id="<?php echo $instituteId; ?>_all_courses" onClick="checkUncheckChilds1(this, '<?php echo $instituteId; ?>_institue_holder');uncheckElement1(this, 'all_institutes', 'all_institue_holder');" <?php echo (!empty($portingConditions['institute'][$instituteId]) ? 'checked' : ''); ?> value="<?php echo $instituteId; ?>" /><b><?php echo $instituteCourseData['listing_title']; ?></b></li>
							<?php
								foreach ($instituteCourseData['courseList'] as $courseId => $courseTitle) {
							?>
								<li style="padding-left:18px;"><input type="checkbox" name="course_id[]" onClick="uncheckElement1(this, '<?php echo $instituteId; ?>_all_courses', '<?php echo $instituteId; ?>_institue_holder');uncheckElement1(this, 'all_institutes', 'all_institue_holder');" <?php echo (!empty($portingConditions['course'][$courseId]) ? 'checked' : ''); ?> value="<?php echo $courseId; ?>" /><?php echo $courseTitle; ?></li>
							<?php
								}
							?>
							</ol>
						<?php
								}
							}
						?>

						<?php
							foreach ($instituteList as $instituteId => $instituteCourseData) {
								if($instituteCourseData['listing_type'] == 'institute') {
						?>
							<ol id="<?php echo $instituteId; ?>_institue_holder">
								<li><input type="checkbox" name="institute_id[]" id="<?php echo $instituteId; ?>_all_courses" onClick="checkUncheckChilds1(this, '<?php echo $instituteId; ?>_institue_holder');uncheckElement1(this, 'all_institutes', 'all_institue_holder');" <?php echo (!empty($portingConditions['institute'][$instituteId]) ? 'checked' : ''); ?> value="<?php echo $instituteId; ?>" /><b><?php echo $instituteCourseData['listing_title']; ?></b></li>
							<?php
								foreach ($instituteCourseData['courseList'] as $courseId => $courseTitle) {
							?>
								<li style="padding-left:18px;"><input type="checkbox" name="course_id[]" onClick="uncheckElement1(this, '<?php echo $instituteId; ?>_all_courses', '<?php echo $instituteId; ?>_institue_holder');uncheckElement1(this, 'all_institutes', 'all_institue_holder');" <?php echo (!empty($portingConditions['course'][$courseId]) ? 'checked' : ''); ?> value="<?php echo $courseId; ?>" /><?php echo $courseTitle; ?></li>
							<?php
								}
							?>
							</ol>
						<?php
								}
							}
						?>
					</div>
				</div>
			</div>
		</li>
		<?php } ?>
		<li>
			<?php
				$this->load->config('response/responseConfig');
				// $this->load->config('ResponseTypes',TRUE);
				//$responseTypes = $this->config->item('responseTypes', 'ResponseTypes');
				global $responseGrades;
				$responseTypes = array_keys($responseGrades);
			?>
			<label>Response type:</label>
			<div class="form-fields">
				<div class="course-box" id="response_type_holder">
					<ol>
						<li><input type="checkbox" name="response_types[]" id="all_response_types" onClick="checkUncheckChilds1(this, 'response_type_holder')" <?php echo (!empty($portingConditions['responsetype']['All']) ? 'checked' : ''); ?> value="All" />All</li>
						<?php foreach($responseTypes as $resType) { ?>
						<li><input type="checkbox" name="response_types[]" onClick="uncheckElement1(this,'all_response_types','response_type_holder');" <?php echo ((!empty($portingConditions['responsetype']['All']) || !empty($portingConditions['responsetype'][$resType])) ? 'checked' : ''); ?> value="<?php echo $resType; ?>"/><?php echo $resType; ?></li>
						<?php } ?>
						<li><input type="checkbox" name="response_types[]" onClick="uncheckElement1(this,'all_response_types','response_type_holder');" <?php echo ((!empty($portingConditions['responsetype']['All']) || !empty($portingConditions['responsetype']['Others'])) ? 'checked' : ''); ?> value="Others"/>Others</li>
					</ol>
				</div>
			</div>
		</li>
	</ul>
</div>
<?php
	} else {
?>
	<ul><li><div class="main-leads"><label>No Institutes/Universities found</label></div></li></ul>
<?php
	}
?>

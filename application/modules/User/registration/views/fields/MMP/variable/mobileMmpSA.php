<?php
// code added for visibility

$desired_course_visibility = TRUE;
$desired_category_visibility = TRUE;
$desired_exam_visibility = TRUE;
$exam_preselected_value = '';
$desired_abroad_specialization_visibility = TRUE;
foreach ( $fields as $fieldId => $field ) {
	
	if ($field->getId () == 'abroadDesiredCourse') {
		$desired_course_visibility = $field->isVisible ();
	} else if ($field->getId () == 'fieldOfInterest') {
		$desired_category_visibility = $field->isVisible ();
		// var_dump($desired_category_visibility);
	} else if ($field->getId () == 'examsAbroad') {
		$desired_exam_visibility = $field->isVisible ();
		$abroad_exam_preselected_value = $field->getPreSelectedValues ();
		if (count ( $abroad_exam_preselected_value ) > 0) {
			$exam_preselected_value = $abroad_exam_preselected_value [0];
		}
	} else if ($field->getId () == 'abroadSpecialization') {
		$desired_abroad_specialization_visibility = $field->isVisible ();
	}
}

$show_abroad_popular_course = "style='display:block;'";
$show_abroad_categories = "style='display:block;'";
if ($mmpFormId > 0) {
	
	if (isset ( $fields ['abroadDesiredCourse'] ) && gettype ( $fields ['abroadDesiredCourse'] ) == 'object') {
		$abroad_popular_saved_course = $fields ['abroadDesiredCourse']->getValues ( array (
				'mmpFormId' => $mmpFormId 
		) );
	}
	if (count ( $abroad_popular_saved_course ) == 0) {
		$show_abroad_popular_course = "style='display:none;'";
		$desired_course_visibility = FALSE;
	}
	if (isset ( $fields ['fieldOfInterest'] ) && gettype ( $fields ['fieldOfInterest'] ) == 'object') {
		$abroad_saved_categories = $fields ['fieldOfInterest']->getValues ( array (
				'mmpFormId' => $mmpFormId 
		) );
	}
	// _P($abroad_saved_categories);
	if (count ( $abroad_saved_categories ) == 0) {
		$show_abroad_categories = "style='display:none;'";
		$desired_category_visibility = FALSE;
	}
	
	$abroad_cat_id = "";
	if (count ( $abroad_saved_categories ) == 1) {
		foreach ( $abroad_saved_categories as $abroad_cat_key => $value ) {
			$abroad_cat_id = $abroad_cat_key;
		}
	}
}
// var_dump($desired_course_visibility);
// var_dump($desired_category_visibility);
foreach ( $fields as $fieldId => $field ) {
	if ($field->isCustom ()) {
		/*
		 * ************************************
		 * Render the custom fields
		 * ************************************
		 */
		$id = $field->getId ();
		$type = $field->getType ();
		$label = $field->getLabel ();
		$values = $field->getValues ();
		
		switch ($type) {
			case 'select' :
				?>
<li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>><label><?php echo $label; ?></label>
	<span class="colon-separater">:&nbsp;</span>
	<div class='fields-col'>
		<select name="<?php echo $id; ?>"
			id="<?php echo $id; ?>_<?php echo $regFormId; ?>"
			<?php echo $registrationHelper->getFieldCustomAttributes($id); ?>
			onblur="registrationCustomLogic['<?php echo $regFormId; ?>'].validateField(this);"
			onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
			<option value="">Select</option>
                            <?php foreach($values as $key => $value) { ?>
                                <option value="<?php echo $key; ?>">
                                    <?php echo $value; ?>
                                </option>
                            <?php } ?>
                        </select>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div></li>
<?php
				break;
			case 'checkbox' :
				?>
<li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>><label><?php echo $label; ?></label>
	<span class="colon-separater">:&nbsp;</span>
	<div class='fields-col' style="padding-top: 5px;">
		<div>
                        <?php foreach($values as $value) { ?>
                            <input type="checkbox"
				name="<?php echo $id; ?>[]"
				class="<?php echo $id; ?>_<?php echo $regFormId; ?>"
				<?php echo $registrationHelper->getFieldCustomAttributes($id); ?>
				value="<?php echo $value; ?>"
				onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div></li>
<?php
				break;
			case 'multiple' :
				?>
<li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>><label><?php echo $label; ?></label>
	<span class="colon-separater">:&nbsp;</span>
	<div class='fields-col'>
		<div>
                        <?php foreach($values as $value) { ?>
                            <input type="radio"
				name="<?php echo $id; ?>"
				class="<?php echo $id; ?>_<?php echo $regFormId; ?>"
				<?php echo $registrationHelper->getFieldCustomAttributes($id); ?>
				value="<?php echo $value; ?>"
				onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div></li>
<?php
				break;
			case 'paragraph' :
				?>
<li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>><label><?php echo $label; ?></label>
	<span class="colon-separater">:&nbsp;</span>
	<div class='fields-col'>
		<textarea class="register-fields2"
			style="width: 225px; height: 70px; color: #000;"
			name="<?php echo $id; ?>"
			id="<?php echo $id; ?>_<?php echo $regFormId; ?>"
			<?php echo $registrationHelper->getFieldCustomAttributes($id); ?>></textarea>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div></li>
<?php
				break;
		}
	} else {
		/*
		 * ************************************
		 * Render the core fields
		 * ************************************
		 */
		switch ($fieldId) {
			case 'residenceCity' :
				?>
<li
	<?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
	<span class="colon-separater">:&nbsp;</span>
	<div class="fields-col">
		<select name="residenceCity" class="universal-select"
			id="residenceCity_<?php echo $regFormId; ?>"
			<?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?>
			onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"
			onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Select your residence location', 'order' => 'alphabetical', 'formData'=>$formData)); ?>
                        </select>
		<p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="residenceCity_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div>
</li>
<?php
				break;
			case 'examTaken' :
				?>
<li class="form-bg"
	<?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
	<label>Have you given any exam?</label>
	<div class="ExamTaken">
		<div class="radioSeperation">
			<input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>"
				class="examTaken_<?php echo $regFormId; ?>" name="examTaken"
				value="yes"
				<?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?>
				onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="examTaken_yes_<?php echo $regFormId; ?>">Yes</label>
		</div>
		<div class="radioSeperation">
			<input type="radio" id="examTaken_no_<?php echo $regFormId; ?>"
				class="examTaken_<?php echo $regFormId; ?>" name="examTaken"
				value="no"
				<?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?>
				onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="examTaken_no_<?php echo $regFormId; ?>">No</label>
		</div>
		<?php if($fields['bookedExamDate']){?>
		<div class="radioSeperation">
			<input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>"
				class="examTaken_<?php echo $regFormId; ?>" name="examTaken"
				value="bookedExamDate"
				<?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?>
				onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>"><?php echo $fields['bookedExamDate']->getLabel(); ?></label>
			<input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value=""/>
		</div>
		<?php } ?>
	</div>
	<p class="clearfix"></p>
	<div>
		<div class="errorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
	</div>

</li>
<?php
				break;
			case 'examsAbroad' :
				?>
<li class="form-bg"
	<?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad'); ?>>
	<label>Select & Enter your exam score</span></label> <span
	class="colon-separater">:&nbsp;</span>
	<div class='fields-col'>
		<ul>
                        <?php
				$examCount = 0;
				foreach ( $fields ['examsAbroad']->getValues () as $examId => $exam ) {
					$count ++;

						echo '<li class="examsTaken" id="examAbroadBlock_' . $regFormId . '_' . (($count + 1) / 2) . '">';
					?>
                                        <div
				style="width: 80px; float: left;">
				<input type="checkbox" name="exams[]"
					class="exams_<?php echo $regFormId; ?>"
					id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"
					value="<?php echo $exam['name']; ?>"
					<?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?>
					onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].enableAbroadExamScore(this);">
                                                    <label for="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"><?php echo $exam['name']; ?></label>
                                                </div>
			<input style="width: 155px;" type="text"
				id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>"
				exam="<?php echo $exam['name']; ?>"
				class="universal-text text-width disable-field" disabled="disabled"
				maxlength="4" minscore="<?php echo $exam['minScore']; ?>"
				maxscore="<?php echo $exam['maxScore']; ?>"
				range="<?php echo $exam['range']; ?>"
				caption="<?php echo $exam['name']; ?> score"
				default="<?php echo $exam['name']; ?> Score"
				value="<?php echo $exam['name']; ?> Score"
				regfieldid="<?php echo $exam['name']; ?>_score"
				name="<?php echo $exam['name']; ?>_score"
				onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"
				onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
			<input type="hidden"
				id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>"
				regfieldid="<?php echo $exam['name']; ?>_scoreType"
				name="<?php echo $exam['name']; ?>_scoreType"
				value="<?php echo $exam['scoreType']; ?>">
			<div>
				<div class="errorMsg"
					id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
			</div>

</li>
<?php
				}
				?>
</ul>
<div>
	<div id="examsAbroad_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
</div>
</div>
</li>
<?php
				break;
			case 'passport' :
				?>
<li class="form-bg"
	<?php echo $registrationHelper->getBlockCustomAttributes('passport'); ?>>
	<label>Do you have a valid passport?</label> <span
	class="colon-separater">:&nbsp;</span>
	<div class="fields-col">
		<div class="radioSeperation">
			<input type="radio" name="passport"
				class="passport_<?php echo $regFormId; ?>"
				id="passport_yes_<?php echo $regFormId; ?>" value="yes" onclick="checkerrorMsg();"
				<?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
			<label for="passport_yes_<?php echo $regFormId; ?>" >Yes</label>
		</div>
		<div class="radioSeperation">
			<input type="radio" name="passport"
				class="passport_<?php echo $regFormId; ?>"
				id="passport_no_<?php echo $regFormId; ?>" value="no" onclick="checkerrorMsg();"
				<?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
			<label for="passport_no_<?php echo $regFormId; ?>">No</label>
		</div>
		<p class="clearfix"></p>
		<div>
			<div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
		</div>
	</div>
</li>
<?php
				break;
			case 'contactByConsultant' :
				?>
<li class="form-bg" style="display: none;"
	<?php echo $registrationHelper->getBlockCustomAttributes('contactByConsultant'); ?>>
	<label>Do you want to be contacted by a consultant?</label> <span
	class="colon-separater">:&nbsp;</span>
	<div class="fields-col">
		<div class="radioSeperation">
			<input type="radio" checked="checked" name="contactByConsultant"
				class="contactByConsultant_<?php echo $regFormId; ?>"
				id="contactByConsultant_yes_<?php echo $regFormId; ?>" value="yes"
				<?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?>>
			<label for="contactByConsultant_yes_<?php echo $regFormId; ?>">Yes</label>
		</div>
		<div class="radioSeperation">
			<input type="radio" name="contactByConsultant"
				class="contactByConsultant_<?php echo $regFormId; ?>"
				id="contactByConsultant_no_<?php echo $regFormId; ?>" value="no"
				<?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?>>
			<label for="contactByConsultant_no_<?php echo $regFormId; ?>">No</label>
		</div>
		<!--div>
			    <div id="contactByConsultant_error_< ?php echo $regFormId; ?>" class="errorMsg"></div>
			</div-->
	</div>
</li>
<?php
				break;
			case 'whenPlanToGo' :
				if (is_array ( $formData ['whenPlanToGo'] )) {
					$whenPlanToGo = $formData ['whenPlanToGo'] [0];
				} else {
					$whenPlanToGo = $formData ['whenPlanToGo'];
				}
				?>
<li class="form-bg"
	<?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
	<label>When do you plan to go?</label>
	<div class=''>
                        
                        <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                            <div class="radioSeperation">
			<input type="radio" name="whenPlanToGo"
				<?php if($whenPlanToGo == $plannedToGoValue) { echo 'checked="checked"'; } ?>
				class="whenPlanToGo_<?php echo $regFormId; ?>"
				id="whenPlanToGo_<?php echo $plannedToGoText; ?>_<?php echo $regFormId; ?>"
				value="<?php echo $plannedToGoValue; ?>"
				<?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?>
				onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="whenPlanToGo_<?php echo $plannedToGoText; ?>_<?php echo $regFormId; ?>"><?php echo $plannedToGoText; ?></label>
		</div>
                        <?php } ?>
                    <p class="clearfix"></p>
		<div>
			<div class="errorMsg"
				id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
		</div>
	</div>
</li>
<?php
				break;
			case 'abroadDesiredCourse' :
				$abroad_desired_course_preselected_values = $field->getPreSelectedValues ();
				// echo $registrationHelper->getBlockCustomAttributes('abroadDesiredCourse');
				?>
<li
	<?php echo $registrationHelper->getBlockCustomAttributes('abroadDesiredCourse'); ?>>
	<div id="twoStepChooseCourse" <?php echo $show_abroad_popular_course;?>>
		<label>Choose from popular courses</label>
		<div>
                                    <?php
				if ($mmpFormId > 0) {
					$desiredCourses = $fields ['abroadDesiredCourse']->getValues ( array (
							'mmpFormId' => $mmpFormId 
					) );
				} else {
					$desiredCourses = $fields ['abroadDesiredCourse']->getValues ();
				}
				foreach ( $desiredCourses as $course ) {
					
					if ($course ['SpecializationId'] == $currentLDBCourseId) {
						$checked = 'checked';
					} else if ($abroad_desired_course_preselected_values [0] == $course ['CourseName']) {
						$checked = 'checked';
					} else {
						$checked = '';
					}
					?>
                                            <div class="radioSeperation">
                  <label >
				<input id="abroadDesiredCourse_<?php echo $regFormId; ?>"
					style="display: inline;" <?=$checked?> type="radio"
					class="desiredCourse_<?php echo $regFormId; ?>"
					onclick="hideFOI();"
					course="<?=$course['CourseName']?>" name="desiredCourse"
					courseLevel="<?=$course['CourseLevel1']?>"
					onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].desiredCourseOnChangeActions(); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].switchEducationFields('<?=$course["CourseLevel1"]?>');"
					value="<?=$course['SpecializationId']?>"
					<?php echo $registrationHelper->getFieldCustomAttributes('abroadDesiredCourse'); ?>>
				 <?=$course['CourseName']?> </label>
			</div>
                                    <?php } ?>
                                    <p class="clearfix"></p>
			<div>
				<div class="errorMsg"
					id="abroadDesiredCourse_error_<?php echo $regFormId; ?>"></div>
			</div> 
				<?php if($desired_course_visibility && $desired_category_visibility):?>
                                <div id="twoStepOr"
				style="text-align: center; color: #676767">
				<center>-- OR --</center>
			</div>
				<?php endif;?>

                        </div>
	</div>
</li>
<?php if($desired_course_visibility && $desired_category_visibility):?>
<li id="twoStepShowMore"
	style="display: none; margin-bottom: 0px; background: #f3f3f3; margin-bottom: 2px;">
	<div class="h-rule" style="">
		<span class="pop-opt"
			onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showMoreOnclick();"
			class=""></span>
	</div>
</li>
<?php endif;?>
        <?php
				break;
			case 'fieldOfInterest' :
				
				// _P($field);
				// echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest');
				$abroad_cat_preseleted = $field->getPreSelectedValues ();
				?>
<li class=""
	<?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
	<div <?php echo $show_abroad_categories;?>>
		<div class="fields-col">
			<select class="universal-select" name="fieldOfInterest"
				id="fieldOfInterest_<?php echo $regFormId; ?>"
				<?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?>
				onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"
				onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
				<option value="">Choose field of interest</option>
                                <?php
				$fieldOfInterestArray = array ();
				if (count ( array (
						'mmpFormId' => $mmpFormId 
				) ) > 0) {
					$fieldOfInterestArray = array (
							'mmpFormId' => $mmpFormId 
					);
					foreach ( $fields ['fieldOfInterest']->getValues ( $fieldOfInterestArray ) as $categoryId => $categoryName ) {
						?>
                                       <option
					value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                                <?php
					
}
				} else {
					$fieldOfInterestArray = array (
							'twoStep' => 1,
							'registerationDomain' => 'studyAbroad' 
					);
					foreach ( $fields ['fieldOfInterest']->getValues ( $fieldOfInterestArray ) as $categoryId => $categoryName ) {
						?>
                                        <option
					value="<?php echo $categoryId; ?>"><?php echo $categoryName['name']; ?></option>
                                <?php
					
}
				}
				?>
                            </select>
			<p class="clearfix"></p>
			<div>
				<div class="errorMsg"
					id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
			</div>
		</div>
		<div class="clearFix"></div>
	</div>
</li>
<?php
				break;
			case 'desiredGraduationLevel' :
				
				// _P($field);
				?>
<li class="form-bg"
	<?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
	<label>Choose your level of study</label>
	<div class="fields-col" id="twoStepLevelOfStudy">
                                <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                                        <div class="radioSeperation">
			<input type="radio"
				id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>"
				courseLevel="<?=$desiredGraduationLevelText['CourseLevel1']?>"
				name="desiredGraduationLevel"
				class="desiredGraduationLevel_<?php echo $regFormId; ?>"
				<?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?>
				value="<?php echo $desiredGraduationLevelText['CourseName']; ?>"
				onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses(); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].switchEducationFields('<?=$desiredGraduationLevelText["CourseLevel1"]?>');">
			<label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>"><?=$desiredGraduationLevelText['CourseName']?></label>
		</div>
                                <?php } ?>
                                <div class="clearFix"></div>
		<div>
			<div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>"
				class="errorMsg"></div>
		</div>
	</div>
</li>
<?php
				break;
			case 'abroadSpecialization' :
				
				// echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization');
				// echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization');
				$abroad_desired_specialization_preselected_values = $field->getPreSelectedValues ();
				// _P($abroad_desired_specialization_preselected_values);
				?>
<li
	<?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>

	<div class="fields-col">
		<select name="abroadSpecialization" class="universal-select"
			id="abroadSpecialization_<?php echo $regFormId; ?>"
			<?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
			<option>Select specialization</option>
		</select>
	</div>
	<p class="clearfix"></p>
	<div>
		<div class="errorMsg"
			id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
	</div>
</li>
<?php
				break;
			case 'destinationCountry' :
				?>
<li
	<?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
	<div class='' style="position: relative;">
		<div id="twoStepChooseCourseCountryDropDown"
			onclick="toggleCountryDropDown()"
			style="height: 41px; position: absolute; left: 0; top: 0; width: 100%; background: rgba(0, 0, 0, 0); z-index: 9">
		</div>
                        <?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); ?>
                        <select class="universal-select"
			id="twoStepCountrySelect">
			<option id="twoStepCountrySelectOption">Destination Country(s)</option>
		</select>

		<div id="countryDropdownLayer"
			class="customDropLayer customInputs fontRest" style="display: none;"
			data-enhance="false">
			<div class="drop-title">You can select multiple countries here</div>

			<div class="drop-layer-cont">
				<ul class="countryList">
                                <?php
				
foreach ( $destinationCountry as $key => $country ) {
					if ($country->getId () == 1) {
						continue;
					}
					?>
                                <li>

						<div>
							<input type="checkbox" name="destinationCountry[]"
								id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>"
								value="<?php echo $country->getId(); ?>"
								onclick = "limitCountrySelection(this.id)"
								<?php if($country->getId() == $ischecked){ echo 'checked = "checked" disabled = "disabled"';} ?>>
							<label data-enhance="false"
								for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>"
								style="cursor: pointer;">
								<!-- onclick="toggleCheckbox(event,this);"--> <span
								class="sprite flLt"></span>
								<p><?php echo $country->getName();?></p>
							</label>
						</div>

					</li>
                                <?php } ?>
                            </ul>
			</div>

		</div>



		    <div>
                        <div class="errorMsg error-msg" id="destinationCountry_error<?php echo $regFormId ?>"></div>
                    </div>
	</div>
</li>
<!--?php
                    break;
                case 'budget':
        ?>
                    <li class="form-bg" < ?php echo $registrationHelper->getBlockCustomAttributes('budget'); ?>>
                    <label>What is your budget for entire program?<span>< ?php echo $registrationHelper->markMandatory('budget'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                            <select class="universal-select" name="budget" id="budget_< ?php echo $regFormId; ?>" < ?php echo $registrationHelper->getFieldCustomAttributes('budget'); ?> onblur="shikshaUserRegistrationForm['< ?php echo $regFormId; ?>'].validateField(this);">
                                <option value="">Select Budget</option>
                                < ?php foreach($fields['budget']->getValues() as $budgetValue => $budgetText) { ?>
                                    <option value="< ?php echo $budgetValue; ?>">< ?php echo $budgetText; ?></option>
                                < ?php } ?>
                            </select>
                            <div>
                                <div class="errorMsg" id="budget_error_< ?php echo $regFormId; ?>"></div>
                            </div>
                    </div>
                    <div class="clearFix"></div>
                    </li-->
<?php 
				break;

				case 'graduationStream' :

			?>
				<li class="MastersEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
					<div>
						<div class="fields-col">
							<select class="universal-select datafieldPG" name="graduationStream" id="graduationStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream'); ?> >
								<option value="">Graduation Stream</option>
								<?php foreach($fields['graduationStream']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php if($formData['graduationStream'] == $key){ echo 'selected';} ?>><?php echo $val; ?></option>
                                <?php } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="graduationStream_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php
				break;

				case 'graduationMarks' :

			?>
				<li class="MastersEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
					<div>
						<div class="fields-col">
							<select class="universal-select datafieldPG" name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> >
								<option value="">Graduation Percentage</option>
								<?php foreach($fields['graduationMarks']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php if($formData['graduationMarks'] == $key){ echo 'selected';} ?>><?php echo $val; ?></option>
                                <?php } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php

				break;

				case 'workExperience' :

			?>
				<li class="MastersEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
					<div>
						<div class="fields-col">
							<select class="universal-select datafieldPG" name="workExperience" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> >
								<option value="">Work Experience</option>
								<?php foreach($fields['workExperience']->getSAValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php if(isset($formData['experience']) && $formData['experience'] == $key){ echo 'selected';} ?>><?php echo $val; ?></option>
                                <?php } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="workExperience_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php

				break;

				case 'tenthBoard' :

			?>
				<li class="BachelorsEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
					<div>
						<div class="fields-col">
							<select class="universal-select datafieldUG" name="tenthBoard" id="tenthBoard_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthBoard'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].reset10thCGPAListMobile();"; >
								<option value="">Class 10th Board</option>
								<?php foreach($fields['tenthBoard']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php if($formData['tenthBoard'] == $key){ echo 'selected';} ?>><?php echo $val; ?></option>
                                <?php } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="tenthBoard_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php

				break;

				case 'tenthmarks' :

			?>
				<li class="BachelorsEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
					<div>
						<div class="fields-col">
							<select class="universal-select datafieldUG" name="tenthmarks" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks'); ?> >
								<option value="">Class 10th Marks/CGPA/Grade</option>
								<?php $marksValues = $fields['tenthmarks']->getValues(); 
                                    foreach($marksValues as $boardName => $valueSet) {
                                        foreach($valueSet as $key => $val) { ?>
                                        <?php if(($boardName != 'CBSE' && !isset($formData['tenthmarks'])) || (isset($formData['tenthBoard']) && $formData['tenthBoard'] != $boardName))  { continue; } ?>
                                    	<option value="<?php echo $key; ?>" <?php if($formData['tenthmarks'] == $key && $formData['tenthBoard'] == $boardName){ echo 'selected';} ?> databoard = "<?php error_log('####board '.$boardName); echo $boardName; ?>" ><?php echo $val; ?></option> 
                                <?php 	}
                                    } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="tenthmarks_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php

				break;

				case 'currentClass' :

			?>
				<li class="BachelorsEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
					<div>
						<div class="fields-col currSchoolField">
							<select class="universal-select datafieldUG" name="currentClass" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass'); ?> >
								<option value="">Current Class</option>
								<?php foreach($fields['currentClass']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php if($formData['currentClass'] == $key){ echo 'selected';} ?>><?php echo $val; ?></option>
                                <?php } ?>
							</select>
							<p class="clearfix"></p>
							<div>
								<div class="errorMsg" id="currentClass_error_<?php echo $regFormId; ?>">
								</div>
							</div>
						</div>
						<div class="clearFix"></div>
					</div>
				</li>

			<?php

				break;

				case 'currentSchool' :

			?>

				<li class="BachelorsEdu ih" <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
            		<div>
                    	<input type="text" name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="100" value="Current School Name" default="Current School Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
	                    <div>
	                        <div class="regErrorMsg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
	                    </div>
                	<div class='clearFix'></div>
            		</div>
				</li>

<?php
				break;
		}
	}
}
?>
<script>
var desired_course_visibility = '<?php echo $desired_course_visibility;?>';
var desired_category_visibility = '<?php echo $desired_category_visibility;?>';
var count_abroad_preselected = '<?php echo count($abroad_desired_course_preselected_values);?>';
var abroad_preselected_specialization = '<?php echo count($abroad_desired_specialization_preselected_values)>0?$abroad_desired_specialization_preselected_values[0]:"";?>'; 
var abroad_preselected_specialization_default = true;
var count_abroad_desired_saved_course = '<?php echo count($abroad_popular_saved_course);?>';
var count_abroad_saved_categories = '<?php echo count($abroad_saved_categories);?>';
if(count_abroad_saved_categories == 1) {
	//document.getElementById('<?php echo "fieldOfInterest_".$regFormId; ?>').value = '<?php echo $abroad_cat_id;?>';
}

var count_abroad_cat_preselected = '<?php echo count($abroad_cat_preseleted);?>';
var desired_exam_visibility = '<?php echo $desired_exam_visibility;?>';
var exam_preselected_value = '<?php echo $exam_preselected_value;?>';
if(exam_preselected_value) {	
	document.getElementById('exam_'+exam_preselected_value+'_'+'<?php echo $regFormId;?>').checked = true;	
}

var desired_abroad_specialization_visibility = '<?php echo $desired_abroad_specialization_visibility;?>';
</script>

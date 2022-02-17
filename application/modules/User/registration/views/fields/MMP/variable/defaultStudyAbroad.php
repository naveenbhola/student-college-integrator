<?php
//code added for visibility
$desired_course_visibility = TRUE;
$desired_category_visibility = TRUE;
$desired_exam_visibility = TRUE;
$exam_preselected_value = '';
$desired_abroad_specialization_visibility = TRUE;
foreach($fields as $fieldId => $field) {

	if($field->getId() == 'abroadDesiredCourse') {
		$desired_course_visibility = $field->isVisible();
	} else if($field->getId() == 'fieldOfInterest') {
		$desired_category_visibility = $field->isVisible();
		//var_dump($desired_category_visibility);	
	} else if($field->getId() == 'examsAbroad') {
		$desired_exam_visibility = $field->isVisible();	
		$abroad_exam_preselected_value = $field->getPreSelectedValues();		
		if(count($abroad_exam_preselected_value)>0) {
			$exam_preselected_value = $abroad_exam_preselected_value[0];			
		}
	} else if($field->getId() == 'abroadSpecialization') {
		$desired_abroad_specialization_visibility = $field->isVisible();	
	}
}

$show_abroad_popular_course = "style='display:block;'";
$show_abroad_categories = "style='display:block;'";
if($mmpFormId>0) {

	if(isset($fields['abroadDesiredCourse']) && gettype($fields['abroadDesiredCourse']) == 'object') {
		$abroad_popular_saved_course = $fields['abroadDesiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
	}
	if(count($abroad_popular_saved_course) == 0) {
		$show_abroad_popular_course = "style='display:none;'";
		$desired_course_visibility = FALSE;
	}		
	if(isset($fields['fieldOfInterest']) && gettype($fields['fieldOfInterest']) == 'object') {
		$abroad_saved_categories = $fields['fieldOfInterest']->getValues(array('mmpFormId' => $mmpFormId));
	}
        //_P($abroad_saved_categories);
        if(count($abroad_saved_categories) == 0) {
		$show_abroad_categories = "style='display:none;'";
		$desired_category_visibility = FALSE;
	}

	$abroad_cat_id = "";
        if(count($abroad_saved_categories) == 1) {
		foreach($abroad_saved_categories as $abroad_cat_key=>$value) {
			$abroad_cat_id = $abroad_cat_key;
		}
	} 
				
}
//var_dump($desired_course_visibility);
//var_dump($desired_category_visibility);
foreach($fields as $fieldId => $field) {
    if($field->isCustom()) {
        /*
         *************************************
         * Render the custom fields
         *************************************
         */ 
        $id = $field->getId();
        $type = $field->getType();
        $label = $field->getLabel();
        $values = $field->getValues();
    
        switch($type) {
            case 'select':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <select name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?> onblur="registrationCustomLogic['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($values as $key => $value) { ?>
                                <option value="<?php echo $key; ?>">
                                    <?php echo $value; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'checkbox':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col' style="padding-top:5px;">
                        <div>
                        <?php foreach($values as $value) { ?>
                            <input type="checkbox" name="<?php echo $id; ?>[]" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'multiple':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div>
                        <?php foreach($values as $value) { ?>
                            <input type="radio" name="<?php echo $id; ?>" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'paragraph':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <textarea class="register-fields2" style="width:225px; height:70px; color:#000;" name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>></textarea>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
        }
    }
    else {
        /*
         *************************************
         * Render the core fields
         *************************************
         */ 
        switch($fieldId) {
                case 'residenceCity':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
                    <label>Residence Location<span><?php echo $registrationHelper->markMandatory('residenceCity'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="residenceCity" class="universal-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Select your residence location', 'order' => 'alphabetical', 'formData'=>$formData)); ?>
                        </select>
                        <div>
                            <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
                case 'examTaken':
        ?>
            <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
                <label>Have you given any exam?<span><?php echo $registrationHelper->markMandatory('examTaken'); ?></span></label>
                <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                            <input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                            Yes
                            <input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                            No
                    <div>
			<div class="errorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
		    </div>
                    </div>
            </li>
    <?php
                break;
            case 'examsAbroad':
    ?>
            <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad'); ?>>
            <label>Exams Given<span><?php echo $registrationHelper->markMandatory('examsAbroad'); ?></span></label>
                <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                    <ul>
                        <?php
                                $examCount = 0;
                                foreach($fields['examsAbroad']->getValues() as $examId => $exam) {
                                    $count++;
                                    echo '<li id="examAbroadBlock_'.$regFormId.'_'.(($count+1)/2).'">'; 
                        ?>
                                        <div style="width:80px; float: left;">
                                                    <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"  value="<?php echo $exam['name']; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].enableAbroadExamScore(this);">
                                                    <?php echo $exam['name']; ?>
                                                </div>
                                                <input style="width: 155px;" type="text" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" class="universal-text text-width disable-field" disabled="disabled" maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score" default="<?php echo $exam['name']; ?> Score" value="<?php echo $exam['name']; ?> Score" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
                                                <input type="hidden" id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>" regfieldid="<?php echo $exam['name']; ?>_scoreType" name="<?php echo $exam['name']; ?>_scoreType" value="<?php echo $exam['scoreType']; ?>">
                                                <div>
                                                    <div class="errorMsg" id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
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
                case 'passport':
        ?>
                <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('passport'); ?>>
                    <label>Do you have a valid passport?<span><?php echo $registrationHelper->markMandatory('passport'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                        <div class="fields-col">
                                    <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                    Yes
                                    <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                    No
                        <div>
			    <div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
			</div>
                        </div>
                </li>
        <?php
                    break;
                case 'contactByConsultant':
        ?>
                <li class="form-bg" style="display: none;" <?php echo $registrationHelper->getBlockCustomAttributes('contactByConsultant'); ?>>
                    <label>Do you want to be contacted by a consultant?<span><?php echo $registrationHelper->markMandatory('contactByConsultant'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                        <div class="fields-col">
                                    <input type="radio" checked="checked" name="contactByConsultant" class="contactByConsultant_<?php echo $regFormId; ?>" id="contactByConsultant_yes_<?php echo $regFormId; ?>"  value="yes" <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?>>
                                    Yes
                                    <input type="radio" name="contactByConsultant" class="contactByConsultant_<?php echo $regFormId; ?>" id="contactByConsultant_no_<?php echo $regFormId; ?>"  value="no" <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?>>
                                    No
                        <!--div>
			    <div id="contactByConsultant_error_< ?php echo $regFormId; ?>" class="errorMsg"></div>
			</div-->
                        </div>
                </li>
        <?php        
                    break;		
                case 'whenPlanToGo':
					if(is_array($formData['whenPlanToGo'])) {
						$whenPlanToGo = $formData['whenPlanToGo'][0];
					} else {
						$whenPlanToGo = $formData['whenPlanToGo'];
					}
        ?>        
                <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                    <label>When do you plan to go?<span><?php echo $registrationHelper->markMandatory('whenPlanToGo'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                            <input type="radio" name="whenPlanToGo" <?php if($whenPlanToGo == $plannedToGoValue) { echo 'checked="checked"'; } ?>  class="whenPlanToGo_<?php echo $regFormId; ?>" id="whenPlanToGo_<?php echo $plannedToGoText; ?>_<?php echo $regFormId; ?>" value="<?php echo $plannedToGoValue; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
				<?php echo $plannedToGoText; ?>
                        <?php } ?>
                    <div>
			<div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
		    </div>
                    </div>
                </li>
        <?php
                    break;
                case 'abroadDesiredCourse':	
		$abroad_desired_course_preselected_values = $field->getPreSelectedValues();		
		//echo $registrationHelper->getBlockCustomAttributes('abroadDesiredCourse');
        ?>                
                    <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('abroadDesiredCourse'); ?>>
                    <div id="twoStepChooseCourse" <?php echo $show_abroad_popular_course;?>>
                        <label>Choose from popular courses<span><?php echo $registrationHelper->markMandatory('abroadDesiredCourse'); ?></span></label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class='fields-col'>
                                    <?php 
				    if($mmpFormId>0) {
					$desiredCourses = $fields['abroadDesiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
				    } else {
					$desiredCourses = $fields['abroadDesiredCourse']->getValues();
				    }	
				    //var_dump($currentLDBCourseId);
				    //_P($desiredCourses);
                                    foreach($desiredCourses as $course) {
					    //echo $course['CourseName']."____".$abroad_desired_course_preselected_values[0];	
                                            if($course['SpecializationId'] == $currentLDBCourseId) {
                                                    $checked = 'checked';
                                            } else if($abroad_desired_course_preselected_values[0] == $course['CourseName']) {
					            //_P($abroad_desired_course_preselected_values);		
						    $checked = 'checked';	
					    }else {
                                                    $checked = '';
                                            } ?>
                                                    <input id="abroadDesiredCourse_<?php echo $regFormId; ?>" style="display: inline;" <?=$checked?> type="radio" class="desiredCourse_<?php echo $regFormId; ?>" course="<?=$course['CourseName']?>" name="desiredCourse" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].desiredCourseOnChangeActions();" value="<?=$course['SpecializationId']?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadDesiredCourse'); ?>>
                                                    <?=$course['CourseName']?>
                                    <?php } ?>
                                <div>
                            		<div class="errorMsg" id="abroadDesiredCourse_error_<?php echo $regFormId; ?>"></div>
                        	</div> 
				<?php if($desired_course_visibility && $desired_category_visibility):?>
                                <div id="twoStepOr" class="h-rule"><span class="opt-txt">OR</span></div>
				<?php endif;?>

                        </div>                        
                    </div>
                    </li>
		    <?php if($desired_course_visibility && $desired_category_visibility):?>
                    <li id="twoStepShowMore" style="display:none;margin-bottom: 0px;background: #f3f3f3;">            	 
                        <div class="h-rule" style="width:200px; margin-left:190px;"><span onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showMoreOnclick();" class="more-opt-box"><i class="common-sprite opt-arr-d"></i>more options</span></div>
                    </li>
                   <?php endif;?>
        <?php
                    break;
                case 'fieldOfInterest':
                //_P($field);
		//echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest');
		$abroad_cat_preseleted = $field->getPreSelectedValues();
        ?>  
                    <li class="form-bg"  <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
                        <div <?php echo $show_abroad_categories;?>>
                        <label>Choose field of interest<span><?php echo $registrationHelper->markMandatory('fieldOfInterest'); ?></span></label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class="fields-col">
                            <select class="universal-select" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                                <option value="">Select</option>
                                <?php
                                $fieldOfInterestArray = array();
                                if(count(array('mmpFormId' => $mmpFormId)) > 0) {
                                    $fieldOfInterestArray = array('mmpFormId' => $mmpFormId);
                                    foreach($fields['fieldOfInterest']->getValues($fieldOfInterestArray) as $categoryId => $categoryName) { ?>
                                       <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                                <?php }
                                } else {
                                    $fieldOfInterestArray = array('twoStep'=>1,'registerationDomain'=>'studyAbroad');
                                    foreach($fields['fieldOfInterest']->getValues($fieldOfInterestArray) as $categoryId => $categoryName) { ?>
                                        <option value="<?php echo $categoryId; ?>"><?php echo $categoryName['name']; ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                            <div>
                                <div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
                            </div>
                        </div>
                        <div class="clearFix"></div>
			</div>
                    </li>
        <?php
                    break;
                case 'desiredGraduationLevel':
                //_P($field);
        ?>  
                    <li class="form-bg" <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
                        <label>Choose your level of study<span><?php echo $registrationHelper->markMandatory('desiredGraduationLevel'); ?></span></label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class="fields-col" id="twoStepLevelOfStudy">
                                <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                                        <input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
                                        <?=$desiredGraduationLevelText['CourseName']?>
                                <?php } ?>
                                <div class="clearFix"></div>
                                <div>
                                    <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                                </div>
                        </div>                    
                    </li>
        <?php
                    break;
                case 'abroadSpecialization':
		//echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization');
		//echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization');
		$abroad_desired_specialization_preselected_values = $field->getPreSelectedValues();
		//_P($abroad_desired_specialization_preselected_values);
        ?>
                    <li <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
                    <label>Choose specialization</label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class="fields-col">
                	<select name="abroadSpecialization" class="universal-select" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                            <option>Select specialization</option>
                        </select>
                    </div>
                    <div>
                        <div class="errorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                    </div>
                    </li>
        <?php
                    break;
                case 'destinationCountry':
                $defaultdestinationCountry = $formData['destinationCountry'];
        ?>
                   <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
                    <label>Destination Country(s)<span><?php echo $registrationHelper->markMandatory('destinationCountry'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col' style="position:relative;">
                        <div id="twoStepChooseCourseCountryDropDown" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedMMP();" style="height: 41px; position: absolute; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0); z-index: 9">
                        </div>
                        <?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); ?>
                        <select class="universal-select" id="twoStepCountrySelect">
                            <option id="twoStepCountrySelectOption">Choose Country</option>
                        </select>
                        <div class="select-opt-layer" style="display:none; width: 400px; z-index: 1;" id="twoStepCountryDropdownLayer">
                         <div style=" height: 200px; overflow-y: auto;" id="twoStepCountryDropdownCont">
                            <p class="font-9"><strong>TOP COUNTRIES</strong></p>
                                 <ul class="mmp-registration-list">
                        		<li>
                                            <?php global $studyAbroadPopularCountries;
                                            foreach($studyAbroadPopularCountries as $key => $popularCountry){?>
                                                <div class="flLt country-width">
                                                    <input type="checkbox" id="country-<?php echo $popularCountry; ?>" name="destinationCountry[]" class="flLt destinationCountry_<?php echo $regFormId; ?>" value="<?php echo $key; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedMMP(this);" <?php if(in_array($key,$defaultdestinationCountry)) { echo 'checked="checked"';} ?>>
                                                    <label style="text-align: left;" for="country-<?php echo $popularCountry; ?>" class="font-10"><span class="common-sprite"></span><?php echo $popularCountry; ?></label>
                                                </div>
                                            <?php } ?>
                                        
                            		</li>
                                 </ul>                    
                                <p class="font-9 clearfix" style="margin-bottom:15px;"><strong>OTHER COUNTRIES</strong></p> 
                                 <ul class="mmp-registration-list">
                        		<li>
                                        <?php foreach ($destinationCountry as $key => $country) {
                                            if($country->getId() == 1) {
                                                continue;
                                            }
                                            
                                            if(!in_array($country->getName(),$studyAbroadPopularCountries)){ ?>
                                            <div style="z-index:100;" class="flLt country-width">
                                                <input type="checkbox" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" name="destinationCountry[]" class="flLt destinationCountry_<?php echo $regFormId; ?>" value="<?php echo $country->getId(); ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedMMP(this);" <?php if(in_array($country->getId(),$defaultdestinationCountry)) { echo 'checked="checked"';} ?>>
                                                <label style="text-align: left;" for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" class="font-10"><?php echo $country->getName();?></label>
                                            </div>
                                        <?php }
                                            } ?>
                                        </li>
                                 </ul>
                         </div>
                         <div class="btn-area">
                            <span class=font-11>Choose up to 3 countries</span>
                            <a href="JavaScript:void(0);" class="orange-button" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelectedMMP();" style="display: inline-block; margin: 8px 0 3px 40px; width: 20px; height: 20px;">OK</a>
                         </div>
                        </div>
                        <div>
                        <div style="color: red; display: none;" class="errorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
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

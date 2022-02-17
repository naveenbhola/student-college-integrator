<?php
foreach($fields as $fieldId => $field) {
    if($field->getStep() == $step) {
        switch($fieldId) {
            case 'firstName':
		$firstName = 'First Name';
		if(!empty($abroadShortRegistrationData['firstName'])) {
		    $firstName = $abroadShortRegistrationData['firstName'];
		}
?>
	    <li>
            <div class="flLt name-txtfield" <?php echo $registrationHelper->getBlockCustomAttributes('firstName');?>>
		<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="universal-text" minlength="1" maxlength="50" default="First Name" value="<?php echo $firstName; ?>" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
		<div>
		    <div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
		</div>
            </div>
    <?php
                break;
            case 'lastName':
		$lastName = 'Last Name';
		if(!empty($abroadShortRegistrationData['lastName'])) {
		    $lastName = $abroadShortRegistrationData['lastName'];
		}
    ?>
            <div  class="flRt name-txtfield" <?php echo $registrationHelper->getBlockCustomAttributes('lastName','margin-left:10px;'); ?>>
		<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="universal-text" minlength="1" maxlength="50" default="Last Name" value="<?php echo $lastName; ?>" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
		<div>
		    <div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
		</div>
            </div>
	    <div class="clearfix"></div>
	    </li>
    <?php
                break;
            case 'mobile':
		$mobile = 'Mobile No.';
		if(!empty($abroadShortRegistrationData['mobile'])) {
		    $mobile = $abroadShortRegistrationData['mobile'];
		}
    ?>
    <li>
            <div  class="flLt name-txtfield"<?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
		<input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="universal-text" maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
		<div>
		    <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
		</div>
	    </div>
    <?php
                break;
            case 'email':
		$email = 'Email';
		$disabled = '';
		if(!empty($abroadShortRegistrationData['email'])) {
		    $email = $abroadShortRegistrationData['email'];
		    $disabled = 'disabled="disabled"';
		}
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
		<input type="text" name="email" id="email_<?php echo $regFormId; ?>" class="universal-text" class="register-fields" maxlength="125" default="Email" value="<?php echo $email; ?>" <?php echo $disabled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)"/>
		<div>
		    <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
		</div>
            </li>
    <?php
                break;
            case 'residenceCity':
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
	    <p class="mb4">Where do you live?</p>
	    <div class="custom-dropdown">
		<select name="residenceCity" class="universal-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
		    <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Select your city', 'order' => 'alphabetical')); ?>
		</select>
	    </div>
		<div>
		    <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
		</div>
            </li>
    <?php
                break;
            case 'examTaken':
		if(!empty($abroadShortRegistrationData['examsAbroad'])) {
		    $examTaken = 'yes';
		}
		else if(!empty($abroadShortRegistrationData['passport'])) {
		    $examTaken = 'no';
		}
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes('examTaken','margin-bottom:0;'); ?>>
	    <div id="examTakenDiv" style="padding:0px;margin:0" class="exam-bg clearfix">
		<div class="signUp-child-wrap clearfix" style="padding:4px 0 4px 10px">
			<p class="mb8"> Have you given any study abroad exam?</p>
		    <div class="columns">
			<input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="examTaken_yes_<?php echo $regFormId; ?>">
			    <span class="common-sprite"></span>
			    <p><strong>Yes</strong></p>
			</label>
		    </div>
		    <div class="columns">
			<input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			<label for="examTaken_no_<?php echo $regFormId; ?>">
			    <span class="common-sprite"></span>
			    <p><strong>No</strong></p>
			</label>
		    </div>
		</div>
            </li>
	    <div>
		<div class="errorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
	    </div>
    <?php
                break;
            case 'examsAbroad':
		$visible = 'No';
		$style = 'display:none;margin-bottom:0;';
		if(!empty($abroadShortRegistrationData['examsAbroad'])) {
		    $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
		    $visible = 'Yes';
		    $style = 'display:block;margin-bottom:0;';
		}
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
                <div class="exam-bg clearfix" style=" border-top:0; margin:0">
		    <p class="mb8">Select & enter your exam score</p>
                    <ul class="customInputs-large">
                        <?php
				global $examGrades;
				global $examFloat;
				$count = 0;
				$display = false;
				
				$examsAbroadMasterList = $fields['examsAbroad']->getValues();
				foreach($examsAbroadMasterList as $examId => $exam) {
				    $count++;
				    if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']])) {
					if($count > 4) {
					    $display = true;
					    break;
					}
				    }
				}
				
				if($display) {
				    $displayExam = 'display:block;';
				    $displayMore = 'display:none;';
				}
				else {
				    $displayExam = 'display:none;';
				    $displayMore = 'display:block;';
				}
				
                                $count = 0;
                                foreach($examsAbroadMasterList as $examId => $exam) {
                                    $count++;
				    
				    $display = '';
				    $checked = '';
				    $value = 'Score';
				    $labelFor = 'exam_'.$exam['name'].'_'.$regFormId;
				    if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']])) {
					$display = 'style="visibility:hidden;"';
					$checked = 'filled = "1"';
					if(isset($examGrades[$exam['name']])) {
					    $value = $examGrades[$exam['name']][(int)$examsAbroad[$exam['name']]];
					}
					else {
					    $value = $examsAbroad[$exam['name']];
					    if($examFloat[$exam['name']] !== TRUE) {
						$value = (int)$value;
					    }
					}
					
					$labelFor = '';
				    }

				    if($count % 2 == 1) { echo '<li id="examAbroadBlock_'.$regFormId.'_'.(($count+1)/2).'">'; } 
                        ?>
                                        <div <?php if($count % 2 == 1) { echo 'class="flLt mR30 examBlock-width"'; } else { echo 'class="flLt examBlock-width"'; } ?>>
                                            <div class="flLt exam-name">
                                                <input type="checkbox" name="exams[]" class="examsAbroad_<?php echo $regFormId; ?>" <?php echo $display; ?> id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"  value="<?php echo $exam['name']; ?>" <?php echo $checked; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].enableAbroadExamScore(this);">
                                                <label for="<?php echo $labelFor; ?>"><span class="common-sprite" <?php echo $display; ?>></span><?php echo $exam['name']; ?></label>
                                            </div>
                                            <input type="text" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" class="universal-text text-width disable-field" disabled="disabled" maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="Score" default="Score" value="<?php echo $value; ?>" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
					    <input type="hidden" id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>" regfieldid="<?php echo $exam['name']; ?>_scoreType" name="<?php echo $exam['name']; ?>_scoreType" value="<?php echo $exam['scoreType']; ?>">
					    <div class="clearfix">
						<div class="errorMsg" id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
					    </div>
                                        </div>
                        <?php
				    if($count % 2 == 0) { echo '</li>'; }

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
		$visible = 'No';
		$style = 'display:none;margin-bottom:0;';
		if(!empty($abroadShortRegistrationData['passport'])) {
		    $passport = $abroadShortRegistrationData['passport'];
		    if($examTaken == 'no') {
			$visible = 'Yes';
			$style = 'display:block;margin-bottom:0;';
		    }
		}
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes('passport', 'margin-bottom:0;', array('visible' => $visible, 'style' => $style)); ?>>
                    <div class="exam-bg clearfix" style="padding:0px;margin:0; border-top:0 none;">
                        <div class="signUp-child-wrap clearfix">
			    <p class="mb8">Do you have a valid passport?</p>
                            <div class="columns">
                                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                <label for="passport_yes_<?php echo $regFormId; ?>">
                                    <span class="common-sprite"></span>
                                    <p><strong>Yes</strong></p>
                                </label>
                            </div>
                            <div class="columns">
                                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                <label for="passport_no_<?php echo $regFormId; ?>">
                                    <span class="common-sprite"></span>
                                    <p><strong>No</strong></p>
                                </label>
                            </div>
                        </div>
			<div>
			    <div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
			</div>
                    </div>
            </li>
    <?php        
                break;		
            case 'whenPlanToGo':
		if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
		    $creationDate = strtotime($abroadShortRegistrationData['creationDate']);
		    $creationDate = (int)date('Y', $creationDate);
		    $whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
		    $whenPlanToGo = (int)date('Y', $whenPlanToGo);
		    
		    if($creationDate == $whenPlanToGo) {
			$whenPlanToGo = 'thisYear';
		    }
		    else if($creationDate + 1 == $whenPlanToGo) {
			$whenPlanToGo = 'in1Year';
		    }
		    else {
			$whenPlanToGo = 'later';
		    }
		}
    ?>        
            <div class="flRt name-txtfield" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
		    <div class="custom-dropdown">
			<select name="whenPlanToGo" class="universal-select" style="width:142px" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
			    <option value="">Intake Year</option>
			    <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
			    <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
			    <?php } ?>
			</select>
		    </div>
		<div>
		    <div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
		</div>
	    </div>
        </li>
    <?php
                break;
            case 'abroadDesiredCourse':
    ?>                
                <li <?php echo $registrationHelper->getBlockCustomAttributes('abroadDesiredCourse','margin-bottom:0px;'); ?>>
                    <div class="form-sec" id="twoStepChooseCourse">
                            <div class="field-child-wrap">
                                <?php $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                                foreach($desiredCourses as $course) {
                                        if($course['SpecializationId'] == $currentLDBCourseId) {
                                                $checked = 'checked';
                                        } else {
                                                $checked = '';
                                        } ?>
                                        <div class="columns">
                                            <input <?=$checked?> type="radio" name="desiredCourse" class="abroadDesiredCourse_<?=$regFormId ?>" id="<?=$course['CourseName'].$regFormId ?>" course="<?=$course['CourseName']?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].desiredCourseOnChangeActions();" value="<?=$course['SpecializationId']?>">
                                                <label for="<?=$course['CourseName'].$regFormId ?>">
                                                        <span class="common-sprite"></span>
                                                        <p><strong><?=$course['CourseName']?></strong></p>
                                                </label>
                                        </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div>
                            <div class="errorMsg" id="abroadDesiredCourse_error_<?php echo $regFormId; ?>"></div>
                        </div>
                </li>
    <?php
                break;
            case 'fieldOfInterest':
    ?>  
                <li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
		<div class="custom-dropdown">
                        <select class="universal-select" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                            <option value="">Or select from other courses</option>
                        <?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) { ?>
                            <option value="<?php echo $categoryId; ?>"><?php echo $categoryName['name']; ?></option>
                        <?php } ?>
                        </select>
		</div>
                        <div>
                            <div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    <div class="clearfix"></div>
                </li>
    <?php
                break;
            case 'desiredGraduationLevel':
    ?>  
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
		<p class="mb4">Choose your level of study</p>
                    <div class="form-sec" id="twoStepLevelOfStudy" style="margin-bottom: 0">
                            <div class="field-child-wrap">
                            <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                                <div class="columns" style="width:35%">
                                    <input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
                                    <label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
                                        <span class="common-sprite"></span>
                                        <p><strong><?=$desiredGraduationLevelText['CourseName']?></strong></p>
                                    </label>
                                </div>
                            <?php } ?>
                            </div>
                            <div class="clearFix"></div>
                            <div>
                                <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                            </div>
                        </div>               
                </li>
    <?php
                break;
            case 'abroadSpecialization':
    ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
		<div class="custom-dropdown">
                	<select name="abroadSpecialization" class="universal-select" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                            <option>Select specialization (optional)</option>
                        </select>
		</div>
                    <div>
                        <div class="errorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </li>
    <?php
                break;
            case 'destinationCountry':
    ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
		<p class="mb4">Choose study destination</p>
                        <div id="twoStepChooseCourseCountryDropDown" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" style="height: 41px; position: relative; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0);">
                        
                        <?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); //print_r($desiredCourses);exit;?>
			
                      <div class="custom-dropdown">
			<div class="select-overlap"></div>
		        <select class="universal-select" id="twoStepCountrySelect">
                            <option id="twoStepCountrySelectOption">Select country</option>
                        </select>
		      </div>
                        
                            <div class="select-opt-layer" style="display:none; width: 435px; z-index: 99;right:0 !important; left:auto;" id="twoStepCountryDropdownLayer" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();">
                            
                            <p class="font-9"><strong>TOP COUNTRIES</strong></p>
                            <div class="scrollbar1" id="twoStepChooseCourseCountryLayerScrollbar">
                                <div class="scrollbar courseCountryScrollbarHeight disable" style="height: 145px;">
                                    <div class="track courseCountryScrollbarHeight" style="height: 145px;">
                                        <div class="thumb" style="top: 0px; height: 145px;">
                                            <div class="end"></div>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .flags {background: url("/public/images/flags-sprite5.png") no-repeat scroll 0 0; display: inline-block; height: 35px; width: 53px; }
                                </style>
                                <div class="viewport courseCountryScrollbarHeight" style="height: 145px;">
                                    <div class="overview" style="top: 0px;" id = "twoStepCountryDropdownCont">
                                        <ol>
                                            <li>
						<?php global $studyAbroadPopularCountries;
						foreach($studyAbroadPopularCountries as $key => $popularCountry){?>
						    <div class="country-flag-cont" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected(this); ">
                                                        <div class="flag-main-box">
                                                            <p class="clearfix"><span class="flags flLt <?php echo str_replace(' ', '', strtolower($popularCountry)); ?>">
                                                            <!--img src="/public/images/abroadCountryFlags/<?php echo $popularCountry; ?>.gif"--></span>
                                                            <strong><?php echo $popularCountry; ?></strong></p>
                                                        </div>
                                                        <div class="flag-chkbox">
                                                            <input type="checkbox" name="destinationCountry[]" id="<?php echo $popularCountry; ?>-flag" value="<?php echo $key; ?>">
                                                            <label for="<?php echo $popularCountry; ?>-flag" onclick="return false;"><span class="common-sprite"></span></label>
                                                        </div>
                                                    </div>
						    
						<?php } ?>
					    </li>
                                        </ol>
                                        <p class="font-9 clearfix" style="margin-bottom:15px"><strong>OTHER COUNTRIES</strong></p> 
                                        <ul class="customInputs-large">
                                            
                                        <?php 
                                        $liCount = 0;
                                        $countryCount = count($destinationCountry); 
                                        foreach ($destinationCountry as $key => $country) {
					    if($country->getId() == 1) {
						continue;
					    }
					    
                                            if(!in_array($country->getName(),$studyAbroadPopularCountries)){
                                                $liCount++; 
                                                if($liCount%3 == 1) {echo '<li>';} ?>
                                                    <div class="flLt country-width" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected(this);">
                                                        <input type="checkbox" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" >
                                                        <label for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" class="font-10" onclick="return false;"><span class="common-sprite"></span> <?php echo $country->getName();?></label>
                                                    </div>
                                            <?php if($liCount == $countryCount || $liCount%3 == 0) {echo '</li>';} 
                                            }
                                        } ?>
                                            

                                       </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin: 8px 0 0px 0;border-top: 1px solid #ccc;">
                                <p class="font-11 choose-CountryTitle">Choose up to 3 countries
                                    <a href="JavaScript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" class="button-style" style="padding: 6px 30px;margin-left:35px;">OK</a>
                                </p>
                            </div>
                        </div>
			</div>
                        <div>
                        <div style="color: red; display: none;" class="errorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
                        </div>
                </li>
    <?php
                break;
            case 'budget':
    ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('budget'); ?>>
		<p class="mb4">Choose your budget for the course</p>
		<div class="custom-dropdown">
                	<select class="universal-select" name="budget" id="budget_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('budget'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                            <option value="">Select budget</option>
                            <?php foreach($fields['budget']->getValues() as $budgetValue => $budgetText) { ?>
                                <option value="<?php echo $budgetValue; ?>"><?php echo $budgetText; ?></option>
                            <?php } ?>
			</select>
		</div>
                        <div>
                            <div class="errorMsg" id="budget_error_<?php echo $regFormId; ?>"></div>
                        </div>
                </li>
<?php
            break;
        }
    }
}
?>

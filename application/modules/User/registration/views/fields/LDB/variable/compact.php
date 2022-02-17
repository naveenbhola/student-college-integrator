<?php
foreach($fields as $fieldId => $field) {
switch($fieldId) {
    case 'whenPlanToStart':
        $fields['whenPlanToStart']->getValues();
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToStart','float:left !important;'); ?>>
            <select name="whenPlanToStart" id="whenPlanToStart_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToStart'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                <option value="">When do you plan to start ?</option>
                <?php foreach($fields['whenPlanToStart']->getValues() as $plannedToStartValue => $plannedToStartText) { ?>
                    <option value="<?php echo $plannedToStartValue; ?>" <?php if($formData['whenPlanToStart'] == $plannedToStartValue) echo "selected='selected'"; ?>>
                        <?php echo $plannedToStartText; ?>
                    </option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="whenPlanToStart_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php        
        break;		
    case 'whenPlanToGo':
?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
            <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                <option value="">When do you plan to go ?</option>
                <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                    <option value="<?php echo $plannedToGoValue; ?>"><?php echo $plannedToGoText; ?></option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'mode':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('mode'); ?>>
            <label style="width: 35px;">Mode:</label>
            <div style="float:left; width: 200px">
            <?php foreach($fields['mode']->getValues() as $modeValue => $modeText) { ?>
                <input type="checkbox" name="mode[]" class="mode_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mode'); ?>  value="<?php echo $modeValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $modeText; ?> &nbsp;&nbsp;
            <?php } ?>
            </div>
			<div class="clearFix"></div>
            <div>
                <div id="mode_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'preferredStudyLocation':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocation'); ?>>
            <div class="selectStyleDiv" id="preferredStudyLocationLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showPreferredStudyLocationLayer(event)">
                <span class="selectStyleArrow"></span>
                <div id="preferredStudyLocationContainer_<?php echo $regFormId; ?>">Preferred Study Location(s)</div>
            </div>
            
            <?php $this->load->view('registration/common/layers/preferredStudyLocation'); ?>
            
            <div>
                <div id="preferredStudyLocation_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'destinationCountry':
?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
            <div class="selectStyleDiv" id="destinationCountryLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showDestinationCountryLayer(event)">
                <span class="selectStyleArrow"></span>
                <div id="destinationCountryContainer_<?php echo $regFormId; ?>">Destination Country(s)</div>
            </div>
            
			<?php $this->load->view('registration/common/layers/destinationCountry'); ?>
            
            <div>
                <div class="regErrorMsg" id="destinationCountry_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'degreePreference':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('degreePreference'); ?>>
            <label>Degree Preference:</label>
            <div>
                <?php $x = 1; foreach($fields['degreePreference']->getValues() as $degreePreferenceValue => $degreePreferenceText) { ?>
                    <input type="checkbox" name="degreePreference[]" class="degreePreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('degreePreference'); ?> value="<?php echo $degreePreferenceValue; ?>" id="degreePreference_<?php echo $degreePreferenceValue; ?>_<?php echo $regFormId; ?>" <?php if(is_array($formData['degreePreference']) && in_array($degreePreferenceValue,$formData['degreePreference'])) echo "checked='checked'"; ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleDegreePreferences();"> <?php echo $degreePreferenceText; ?>
                    <?php
                    if($x%2 == 0) {
                        echo "<br />";
                    }
                    $x++;
                    ?>
                <?php } ?>
            </div>
            <div>
                <div class="regErrorMsg" id="degreePreference_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
	case 'exams':
?>          
		<li <?php echo $registrationHelper->getBlockCustomAttributes('exams'); ?>>
            <div class="selectStyleDiv" id="examLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showExamLayer(event)">
                <span class="selectStyleArrow"></span>
                <div id="examContainer_<?php echo $regFormId; ?>">Exams Taken/Plan to Take</div>
            </div>
            
            <?php $this->load->view('registration/common/layers/exams'); ?>
            
            <div>
                <div id="exams_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
	case 'examsAbroad':
?>          
			<li <?php echo $registrationHelper->getBlockCustomAttributes('exams','float:left !important;'); ?>>
				<label>Exams Taken<span><?php echo $registrationHelper->markMandatory('exams'); ?></span> :</label>
				<div class='fields-col'>
					<div>
						<input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="TOEFL"> TOEFL &nbsp;
						<input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="IELTS"> IELTS &nbsp;
						<span id="SAT_wrapper_<?php echo $regFormId; ?>">
							<input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="SAT" id="exam_SAT_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this);"> SAT
							<input type="text" class="register-fields" name="SAT_score" id="SAT_score_<?php echo $regFormId; ?>" regFieldId="SAT_score" style="width:50px !important; display: none" value="Score" default="Score" caption="SAT score" maxlength="4" minscore="600" maxscore="2400" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
						</span>
						<div>
							<span id="GMAT_wrapper_<?php echo $regFormId; ?>" style="display:none;">
								<input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="GMAT" id="exam_GMAT_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this);"> GMAT &nbsp;
								<input type="text" class="register-fields" name="GMAT_score" id="GMAT_score_<?php echo $regFormId; ?>" regFieldId="GMAT_score" style="width:50px !important; display: none" value="Score" default="Score" caption="GMAT score" maxlength="3" minscore="400" maxscore="800" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
							</span>
							<span id="GRE_wrapper_<?php echo $regFormId; ?>" style="display:none;">
								<input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="GRE" id="exam_GRE_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this);"> GRE
								<input type="text" class="register-fields" name="GRE_score" id="GRE_score_<?php echo $regFormId; ?>" regFieldId="GRE_score" style="width:50px !important; display: none" value="Score" default="Score" caption="GRE score" maxlength="4" minscore="500" maxscore="1600" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
							</span>
						</div>
					</div>
					<div>
						<div id="exams_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
					</div>
					<?php foreach(array('SAT','GMAT','GRE') as $examWithScore) { ?>
						<div>
							<div id="<?php echo $examWithScore; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
						</div>
					<?php } ?>
				</div>
			</li>  
<?php
        break;
    case 'graduationStatus':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationStatus'); ?>>
           <label style="width: 105px;">Graduation Status:</label> 
            <div style="float: left; width:195px">
                <?php foreach($fields['graduationStatus']->getValues() as $graduationStatusValue) { ?>
                    <input type="radio" name="graduationStatus" class="graduationStatus_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStatus'); ?> value="<?php echo $graduationStatusValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"> <?php echo $graduationStatusValue; ?> &nbsp;
                <?php } ?>
            </div>
			<div class="clearFix"></div>
            <div>
                <div class="regErrorMsg" id="graduationStatus_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'graduationDetails':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationDetails'); ?>>
            <select name="graduationDetails" id="graduationDetails_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationDetails'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">Graduation Details</option>
                <?php foreach($fields['graduationDetails']->getValues() as $graduationCourse) { ?>
                    <option value='<?php echo $graduationCourse; ?>'><?php echo $graduationCourse; ?></option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="graduationDetails_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'graduationMarks':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
            <select name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">Graduation Marks</option>
                <?php foreach($fields['graduationMarks']->getValues() as $graduationMarks) { ?>
                    <option value='<?php echo $graduationMarks; ?>'><?php echo $graduationMarks; ?>%</option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'graduationCompletionYear':
?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?>>
            <select name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">Graduation Completion Year</option>
                <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
                    <option value='<?php echo $graduationCompletionYear; ?>'><?php echo $graduationCompletionYear; ?></option>
                <?php } ?>	
            </select>
            <div>
                <div class="regErrorMsg" id="graduationCompletionYear_error_<?php echo $regFormId; ?>"></div>
            </div>
			<div>
				<div class="regErrorMsg" id="graduationCompletionDate_error_<?php echo $regFormId; ?>"></div>
			</div>
        </li>
<?php
        break;
    case 'xiiStream':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
            <label>Std. XII Stream:</label>
            <div>
            <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
                <input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" /> <?php echo $xiiStreamValue; ?> &nbsp;
            <?php } ?>
            </div>
            <div>
                <div id= "xiiStream_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'xiiYear':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?>>
            <select name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">XII Completion Year</option>
                <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
                    <option value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
                <?php } ?>	
            </select>
            <div>
                <div class="regErrorMsg" id="xiiYear_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'xiiMarks':
?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiMarks'); ?>>
            <select name="xiiMarks" id="xiiMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">XII Marks</option>
                <?php foreach($fields['xiiMarks']->getValues() as $xiiMarks) { ?>
                    <option value='<?php echo $xiiMarks; ?>'><?php echo $xiiMarks; ?></option>
                <?php } ?>	
            </select>
            <div>
                <div class="regErrorMsg" id="xiiMarks_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'workExperience':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
            <select name="workExperience" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="" title="Select">Work Experience</option>
                <?php foreach($fields['workExperience']->getValues() as $workExperienceValue => $workExperienceText) { ?>
                    <option value='<?php echo $workExperienceValue; ?>'><?php echo htmlentities($workExperienceText); ?></option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'preferredStudyLocality':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocality'); ?>>
            <div>
                <strong>Preferred Localities for Studies</strong>
                <label>Preference 1: </label>
                <div>
                    <select id="preferredStudyLocalityCity_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='1' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <?php $this->load->view('registration/common/dropdowns/preferredLocalityCity'); ?>
                    </select>
                    <div class="clearFix spacer5"></div>
                    <select id="preferredStudyLocality_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <option value="">Select Area</option>
                    </select>
                    <div class="clearFix"></div>
                    <div>
                        <div class="regErrorMsg" id="preferredStudyLocality1_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </div>
            
            <?php for($i = 2;$i <= 4;$i++) { ?>
                <div id="preferredStudyLocality_<?php echo $i; ?>_block_<?php echo $regFormId; ?>" style="display:none; padding-top:5px;">
                    <label>Preference <?php echo $i; ?>: </label>
                    <div>
                        <select id="preferredStudyLocalityCity<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='<?php echo $i; ?>' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                            <option value="">Select City</option>
                            <?php echo $fields['preferredStudyLocality']->getValues(array('cities' => TRUE)); ?>
                        </select>
                        <div class="clearFix spacer5"></div>
                        <select id="preferredStudyLocality<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality',array('num' => $i)); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                            <option value="">Select Area</option>
                        </select>
                        <div class="clearFix"></div>
                        <div>
                            <div class="regErrorMsg" id="perference<?php echo $i; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div>
                <div class="regErrorMsg" id="preferredStudyLocality_error_<?php echo $regFormId; ?>"></div>
            </div>
            <div id="preferredStudyLocality_add_action_block_<?php echo $regFormId; ?>">
                <div class="spacer10 clearFix"></div>
                <div><a id="addLocalityPreference_<?php echo $regFormId; ?>" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].addLocalityPreference();">+ Add another preference</a></div>
                <div class="clearFix"></div>
            </div>
        </li>
<?php
        break;
    case 'firstName':
?>
		<li <?php echo $registrationHelper->getBlockCustomAttributes('firstName','float:left !important'); ?>>
			<div style="float:left; width:47%">
					<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your First Name" default="Your First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div>
						<div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
			<div <?php echo $registrationHelper->getBlockCustomAttributes('lastName','float:left; width:47%; margin-left:10px'); ?>>
				<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your Last Name" default="Your Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div>
					   <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
			<div class="clearFix"></div>	
        </li>
<?php
        break;
    case 'lastName11111111':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('lastName','width:47%; float:left !important; clear: none !important; margin-left:10px;'); ?>>
            <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your Last Name" default="Your Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'email':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
            <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields" maxlength="125" value="Email" default="Email" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'mobile':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
            <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="register-fields" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
<?php
        break;
    case 'residenceCity':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
            <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Residence Location')); ?>
            </select>
            <div>
                <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'desiredGraduationLevel':
?>  
        <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
            <label style="float:left; width:150px;">Desired Graduation Level:</label>
            <div style="float:left; width:120px;">
            <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                <input type="radio" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadExams(this)"> <?php echo $desiredGraduationLevelText; ?> &nbsp;&nbsp;
            <?php } ?>
            </div>
			<div class="clearFix"></div>
            <div>
                <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'callPreference':
?>          
        <li <?php echo $registrationHelper->getBlockCustomAttributes('callPreference'); ?>>
            <label>Would you like our partner consultants to call you & assist with process</label>
            <select name="callPreference" id="callPreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('callPreference'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                <option value="">Select</option>
                <?php foreach($fields['callPreference']->getValues() as $callPreferenceValue => $callPreferenceText) { ?>
                    <option value="<?php echo $callPreferenceValue; ?>"><?php echo $callPreferenceText; ?></option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="callPreference_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'age':
?>           
        <li <?php echo $registrationHelper->getBlockCustomAttributes('age'); ?>>
            <input type="text" name="age" id="age_<?php echo $regFormId; ?>" class="register-fields" maxlength="2" value="Your Age" default="Your Age" <?php echo $registrationHelper->getFieldCustomAttributes('age'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
            <div>
                <div class="regErrorMsg" id="age_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
<?php
        break;
    case 'gender':
?>           
        <li <?php echo $registrationHelper->getBlockCustomAttributes('gender'); ?>>
            <label style="width:48px; float: left">Gender:</label>
            <div style="width: 200px; float: left">
            <?php foreach($fields['gender']->getValues() as $genderValue => $genderText) { ?>
                <input type="radio" name="gender" class="gender_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('gender'); ?>  value="<?php echo $genderValue; ?>" /> <?php echo $genderText; ?> &nbsp;
            <?php } ?>
            </div>
            <div>
                <div class="regErrorMsg" id="gender_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
<?php
        break;
    case 'fund':
?>                  
        <li <?php echo $registrationHelper->getBlockCustomAttributes('fund'); ?>>
            <label>How do you plan to fund your education:</label>
            <div class='fields-col'>
                <div>
                <?php foreach($fields['fund']->getValues() as $fundValue => $fundText) { ?>
                    <input type="checkbox" name="fund[]" class="fund_<?php echo $regFormId; ?>" id="fund_<?php echo $fundValue; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fund'); ?>  value="<?php echo $fundValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); <?php if($fundValue == 'other') echo "shikshaUserRegistrationForm['".$regFormId."'].toggleOtherFundingDetails();"; ?>"> <?php echo $fundText; ?> 
                <?php } ?>
                </div>
                <div>
                    <div id="fund_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                </div>
            </div>
        </li>
        
        <li id="otherFundingDetails_block_<?php echo $regFormId; ?>" style="display: none">
            <label>Other Funding Details:</label>
            <div class='fields-col'>
                <input type="text" name="otherFundingDetails" id="otherFundingDetails_<?php echo $regFormId; ?>" regFieldId="otherFundingDetails" class="register-fields" />
            </div>
        </li>         
<?php
    break;
}
}
?>
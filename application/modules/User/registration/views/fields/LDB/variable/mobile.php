<?php
global $user_logged_in;
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
	    <div class="icon-wrap"><i class="reg-sprite calendar-icon" ></i></div>
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
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('mode'); ?>>
            <p>Mode:</p>
            <div>
            <?php foreach($fields['mode']->getValues() as $modeValue => $modeText) { ?>
                <label><input type="checkbox" name="mode[]" class="mode_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mode'); ?>  value="<?php echo $modeValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"> <?php echo $modeText; ?></label>
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
	    <a href="#preferredStudyLocations" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox" id="preferredStudyLocationLayerButton_<?php echo $regFormId; ?>">
		<p>
			<span id="preferredStudyLocationContainer_<?php echo $regFormId; ?>" >Preferred Study Location(s)</span>
			<i class="icon-select2"></i>
		</p>
            </a>
	    <div class="icon-wrap"><i class="reg-sprite pref-loc" ></i></div>
            <div>
                <div id="preferredStudyLocation_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'destinationCountry':
?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>

	    <a href="#destinationCountryDiv" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox" id="destinationCountryLayerButton_<?php echo $regFormId; ?>">
		<p>
			<span id="destinationCountryContainer_<?php echo $regFormId; ?>" >Destination Country(s)</span>
			<i class="icon-select2"></i>
		</p>
            </a>
            <div>
                <div id="destinationCountry_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>

        </li>
<?php
        break;
    case 'degreePreference':
?>
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('degreePreference'); ?>>
            <p>Degree Preference:</p>
            <div>
                <?php $x = 1; foreach($fields['degreePreference']->getValues() as $degreePreferenceValue => $degreePreferenceText) { ?>
                    <label><input type="checkbox" name="degreePreference[]" class="degreePreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('degreePreference'); ?> value="<?php echo $degreePreferenceValue; ?>" id="degreePreference_<?php echo $degreePreferenceValue; ?>_<?php echo $regFormId; ?>" <?php if(is_array($formData['degreePreference']) && in_array($degreePreferenceValue,$formData['degreePreference'])) echo "checked='checked'"; ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleDegreePreferences();"> <?php echo $degreePreferenceText; ?></label>
                    <?php
                    if($x%2 == 0) {
                        echo "<br />";
                    }
		    else{
			echo "&nbsp;&nbsp";
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
            <?php $this->load->view('registration/common/layers/mobileExams'); ?>
<?php
        break;
    case 'graduationStatus':
?>
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStatus'); ?>>
           <p>Graduation Status:</p> 
            <div>
                <?php foreach($fields['graduationStatus']->getValues() as $graduationStatusValue) { ?>
                    <label><input type="radio" name="graduationStatus" class="graduationStatus_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStatus'); ?> value="<?php echo $graduationStatusValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"> <?php echo $graduationStatusValue; ?></label>
                <?php } ?>
            </div>
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
	    <div class="icon-wrap"><i class="reg-sprite grad-details-icon" ></i></div>
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
	    <div class="icon-wrap"><i class="reg-sprite grad-marks-icon" ></i></div>
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
	    <div class="icon-wrap"><i class="reg-sprite completed-icon" ></i></div>
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
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
            <p>Std. XII Stream:</p>
            <div>
            <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
                <label><input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" /> <?php echo $xiiStreamValue; ?></label>
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
	    <div class="icon-wrap"><i class="reg-sprite completed-icon" ></i></div>
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
	    <div class="icon-wrap"><i class="reg-sprite marks-icon" ></i></div>
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
	    <div class="icon-wrap"><i class="reg-sprite work-exp-icon" ></i></div>
            <div>
                <div class="regErrorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'preferredStudyLocality':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocality','float:left !important;'); ?>>
            <div>
				<?php if($context == 'findInstitute'): ?>
					<strong>Preferred Locality for Studies</strong>
				<?php else: ?>
					<strong>Preferred Localities for Studies</strong>
					<p>Preference 1: </p>
				<?php endif; ?>
                <div style="position: relative">
                    <select id="preferredStudyLocalityCity_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='1' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <?php $this->load->view('registration/common/dropdowns/preferredLocalityCity'); ?>
                    </select>
		    <div class="icon-wrap"><i class="reg-sprite city-icon" ></i></div>
		</div>
                    <div class="clearFix spacer10"></div>
		<div style="position: relative">
                    <select id="preferredStudyLocality_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <option value="">Select Area</option>
                    </select>
		    <div class="icon-wrap"><i class="reg-sprite area-icon" ></i></div>
                    <div class="clearFix"></div>
                    <div>
                        <div class="regErrorMsg" id="preferredStudyLocality1_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </div>
            
			<?php if($context != 'findInstitute'): ?>
            <?php for($i = 2;$i <= 4;$i++) { ?>
                <div id="preferredStudyLocality_<?php echo $i; ?>_block_<?php echo $regFormId; ?>" style="display:none; padding-top:5px;">
                    <p>Preference <?php echo $i; ?>: </p>
                    <div style="position: relative">
                        <select id="preferredStudyLocalityCity<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='<?php echo $i; ?>' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                            <option value="">Select City</option>
                            <?php echo $fields['preferredStudyLocality']->getValues(array('cities' => TRUE)); ?>
                        </select>
			<div class="icon-wrap"><i class="reg-sprite city-icon" ></i></div>
		    </div>
                        <div class="clearFix spacer10"></div>
		    <div style="position: relative">
                        <select id="preferredStudyLocality<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality',array('num' => $i)); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                            <option value="">Select Area</option>
                        </select>
			<div class="icon-wrap"><i class="reg-sprite area-icon" ></i></div>
                        <div class="clearFix"></div>
                        <div>
                            <div class="regErrorMsg" id="perference<?php echo $i; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
			<?php endif; ?>
            <div>
                <div class="regErrorMsg" id="preferredStudyLocality_error_<?php echo $regFormId; ?>"></div>
            </div>
			<?php if($context != 'findInstitute'): ?>
            <div id="preferredStudyLocality_add_action_block_<?php echo $regFormId; ?>">
                <div class="spacer5 clearFix"></div>
                <div><a class="add-txt" id="addLocalityPreference_<?php echo $regFormId; ?>" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].addLocalityPreference();">+ Add another preference</a></div>
                <div class="clearFix"></div>
            </div>
			<?php endif; ?>
        </li>
<?php
        break;
   /* case 'firstName':
?>

        <li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
			<div>
					<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your First Name" default="Your First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div class="icon-wrap"><i class="reg-sprite fname-icon" ></i></div>
					<div>
						<div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
	</li>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
			<div>
				<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your Last Name" default="Your Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
				<div class="icon-wrap"><i class="reg-sprite lname-icon" ></i></div>
					<div>
					   <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
			<div class="clearFix"></div>	
        </li>
<?php
        break;*/
    case 'lastName1111111':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
            <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Your Last Name" default="Your Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
            <div>
                <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    /*case 'email':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
            <input type="email" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields" maxlength="125" value="Email" default="Email" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php if($user_logged_in=='true'){ echo "disabled='disabled';";}?>/>
	    <div class="icon-wrap"><i class="reg-sprite mail-icon" ></i></div>
            <div>
                <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
    case 'mobile':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
            <input type="text" pattern="\d*" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="register-fields" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
	    <div class="icon-wrap"><i class="reg-sprite mob-icon" ></i></div>
            <div>
                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
<?php
        break;*/
    case 'residenceCity':
?>
        
	 <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <div class="custom-dropdown">
                <select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="getMultiLocationDiv(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Current City', 'isNational' => true)); ?>
                </select>
            </div>
	    <div class="icon-wrap"><i class="reg-sprite res-loc-icon" ></i></div>
            <div>
                <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
      case 'residenceLocality':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCityLocality'); ?>>
            <div>
                <div class="custom-dropdown">
                    <!--<select id="preferredStudyLocalityCity_<?php //echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php //echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php //echo $regFormId; ?>'].populateLocalities(this); autoPopulateLocality(this, '<?php //echo $formData['widget']; ?>')" onblur="shikshaUserRegistrationForm['<?php //echo $regFormId; ?>'].validateField(this)">-->
		    <select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this); getMultiLocationDiv(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <?php $this->load->view('registration/common/dropdowns/residenceLocality'); ?>
                    </select>
                </div>
		 <div class="icon-wrap"><i class="reg-sprite res-loc-icon" ></i></div>
                <div>
                    <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?>>
            <div>
                <div class="custom-dropdown">
                    <select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <option value="">Select Area</option>
                    </select>
                </div>
                <div class="icon-wrap"><i class="reg-sprite city-icon" ></i></div>
                <!--div>
                    <div class="regErrorMsg" id="preferredStudyLocality1_error_<?php //echo $regFormId; ?>"></div>
                </div-->
                <div>
                    <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
<?php
        break;
    case 'desiredGraduationLevel':
?>  
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
            <p>Desired Graduation Level:</p>
            <div>
            <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                <label><input type="radio" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadExams(this)"> <?php echo $desiredGraduationLevelText; ?></label>
            <?php } ?>
            </div>
            <div>
                <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'callPreference':
?>          
        <li <?php echo $registrationHelper->getBlockCustomAttributes('callPreference'); ?>>
            <p>Would you like our partner consultants to call you & assist with process</p>
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
                <div class="regErrorMsg" id="age_error"></div>
            </div>  
        </li>
<?php
        break;
    case 'gender':
?>           
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('gender'); ?>>
            <p>Gender:</p>
            <div>
            <?php foreach($fields['gender']->getValues() as $genderValue => $genderText) { ?>
                <label><input type="radio" name="gender" class="gender_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('gender'); ?>  value="<?php echo $genderValue; ?>" /> <?php echo $genderText; ?></label>
            <?php } ?>
            </div>
            <div>
                <div class="regErrorMsg" id="gender_error"></div>
            </div>  
        </li>
<?php
        break;
    case 'fund':
?>                  
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('fund'); ?>>
            <p>How do you plan to fund your education:</p>
            <div class='fields-col'>
                <div>
                <?php foreach($fields['fund']->getValues() as $fundValue => $fundText) { ?>
                    <label><input type="checkbox" name="fund[]" class="fund_<?php echo $regFormId; ?>" id="fund_<?php echo $fundValue; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fund'); ?>  value="<?php echo $fundValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); <?php if($fundValue == 'other') echo "shikshaUserRegistrationForm['".$regFormId."'].toggleOtherFundingDetails();"; ?>"> <?php echo $fundText; ?> </label>
                <?php } ?>
                </div>
                <div>
                    <div id="fund_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                </div>
            </div>
        </li>
        
        <li id="otherFundingDetails_block_<?php echo $regFormId; ?>" style="display: none">
            <p>Other Funding Details:</p>
            <div class='fields-col'>
                <input type="text" name="otherFundingDetails" id="otherFundingDetails_<?php echo $regFormId; ?>" regFieldId="otherFundingDetails" class="register-fields" />
            </div>
        </li>         
<?php
    break;
}
//}
}
?>

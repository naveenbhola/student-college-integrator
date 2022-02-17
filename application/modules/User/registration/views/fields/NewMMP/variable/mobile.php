<?php
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
                <select name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?> onblur="registrationCustomLogic['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                    <option value="">Locality</option>
                    <?php foreach($values as $key => $value) { ?>
                        <option value="<?php echo $key; ?>">
                            <?php echo $value; ?>
                        </option>
                    <?php } ?>
                </select>
                <div>
                    <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                </div>
            </li>
    <?php
            break;
        case 'checkbox':
    ?>
            <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                <p><?php echo $label; ?></p>
                <div>
                <?php foreach($values as $value) { ?>
                    <input type="checkbox" name="<?php echo $id; ?>[]" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                <?php } ?>
                </div>
                <div class="clearFix"></div>
                <div>
                    <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                </div>
            </li>
    <?php
            break;
        case 'multiple':
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                <p><?php echo $label; ?><p>
                <div>
                <?php foreach($values as $value) { ?>
                    <input type="radio" name="<?php echo $id; ?>" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                <?php } ?>
                </div>
                <div class="clearFix"></div>
                <div>
                    <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                </div>
            </li>
    <?php
            break;
        case 'paragraph':
    ?>
            <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                <p><?php echo $label; ?></p>
                <div>
                    <textarea class="register-fields2" style="width:225px; height:70px; color:#000;" name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>></textarea>
                    <div>
                        <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </li>
    <?php
            break;
    }
}
else {
switch($fieldId) {
    case 'whenPlanToStart':
        $fields['whenPlanToStart']->getValues();
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToStart','float:left !important;'); ?>>
            <select name="whenPlanToStart" id="whenPlanToStart_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToStart'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
            <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"  onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
                <label><input type="checkbox" name="mode[]" class="mode_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mode'); ?>  value="<?php echo $modeValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $modeText; ?></label>
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
            <div>
                <div id="preferredStudyLocation_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
    case 'destinationCountry':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
	    <a href="#destinationCountry" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox" id="destinationCountryLayerButton_<?php echo $regFormId; ?>">
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
                    <label><input type="checkbox" name="degreePreference[]" class="degreePreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('degreePreference'); ?> value="<?php echo $degreePreferenceValue; ?>" id="degreePreference_<?php echo $degreePreferenceValue; ?>_<?php echo $regFormId; ?>" <?php if(is_array($formData['degreePreference']) && in_array($degreePreferenceValue,$formData['degreePreference'])) echo "checked='checked'"; ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleDegreePreferences(); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $degreePreferenceText; ?></label>
                    <?php
                    if($x%2 == 0) {
                        echo "<br />";
                    }
		    else{
			echo "&nbsp;&nbsp;";
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
        
             $examLayerStyle= "height:100px; overflow:auto;";
             if(preg_match('/Android\s[0-9\.]*/',$_SERVER['HTTP_USER_AGENT'],$matches)){
             $match = explode(' ', $matches[0]);
             $androidVersion = $match[1];
             if($androidVersion >= 3){
                     $examLayerStyle= "height:100px; overflow:auto;";
             }else{
                      $examLayerStyle= '';
             }
             }
             
             $listExams = $fields['exams']->getValues();
             if(!empty($listExams)){ ?>
                 <li <?php echo $registrationHelper->getBlockCustomAttributes('exams','position:relative;'); ?> >
                <div class="ui-select" onclick="showHideExamLayer('examLayer_<?php echo $regFormId; ?>');">
                    <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d" data-iconpos="right" data-theme="c" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><span  id="examContainer_<?php echo $regFormId; ?>">Exams Taken</span></span><span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span></span>
                    </div>
                </div>
                   
                    <div>
                        <div class="regErrorMsg" id="exams_error_<?php echo $regFormId; ?>"></div>
                    </div>
                <div class="exam-layer" data-enhance="false" style="display: none;" id="examLayer_<?php echo $regFormId; ?>">
                                <ul class="cstm_css" style="<?php echo $examLayerStyle;?>">
                                <?php
                                $examBlockCount = 1;
                        foreach($listExams as $exams) {
                                    foreach($exams as $examId => $exam) {
                                ?>
                                        <li>
                                            <label>
                                                <input <?php if(in_array($examId, $formData['exams'])) { echo 'checked="checked"'; } ?>  type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" />
                                                <?php echo $exam->getDisplayNameForUser(); ?>
                                            </label>
                                            <?php if($exam->getScoreType()) { ?>
                                            <div class='exam-layer-details-box' id='examDetails_<?php echo $examId; ?>_<?php echo $regFormId; ?>' onmouseout="hideExamLayer('examLayer_<?php echo $regFormId; ?>');">
                                                    <input type='hidden' value='<?php echo $exam->getScoreType(); ?>' name="<?php echo $examId; ?>_scoreType" />
                                                    <?php if($exam->hasNameBox()) { ?>
                                                    <div id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="margin-bottom: 5px">
                                                    <input type='text' mandatory="1" value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox_<?php echo $regFormId; ?>" name="otherExamName" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                                    <div>
                                                        <div id="<?php echo $examId; ?>_nameBox_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                                    </div>
                                                    </div>
                                                    <?php } ?>
                                                    <div>
                                                        <input type='text' value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                                        <?php if($exam->getState()) { ?>
                                                        <div>
                                                                <div id="<?php echo $examId; ?>_state_<?php echo $regFormId; ?>"  style="color:#777;font-size:12px;">
                                                                        Accepted for colleges in <?php echo $exam->getState(); ?>
                                                                </div>
                                                        </div>
                                                        <?php } ?>
                                                        <div>
                                                            <div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                                        </div>
                                                    </div>
                                                    <?php if($exam->hasPassingYear()) { ?>
                                                    <div id='examYear_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
                                                        <div>
                                                            <select caption="year appeared in" regFieldId="<?php echo $examId; ?>_year" id="<?php echo $examId; ?>_year_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_year" mandatory='1' onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                                                    <option value=''>Appeared in</option>
                                                                    <?php foreach($exam->getPassingYearValues() as $yk => $yv) { ?>
                                                                            <option value='<?php echo $yk; ?>'><?php echo $yv; ?></option>
                                                                    <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class=''>
                                                            <div id="<?php echo $examId; ?>_year_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </li>        
                                    <?php    
                                    }
                                    if($examBlockCount++ < count($listExams)) { ?>
                            <li><div class="divider"></div></li>
                                    <?php }
                                }
                                ?>
                
                    </ul>
                
                    <div class="btn-row2">
                    <a class="button blue small" style="background-color: #2e4674;padding: 5px;display: inline-block;" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setSelectedExams();">OK</a>
                    </div>
                </div>
                </li>
             <?php } ?>
             <script>
                 function showHideExamLayer(id){
                     $j('#'+id).toggle();
                 }
                 function hideExamLayer(id) {
                     //$j('#'+id).hide();
                 }
             </script>
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
            <select name="graduationDetails" id="graduationDetails_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationDetails'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
            <select name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
            <select name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                <option value="">Graduation Completion Year</option>
                <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
                    <option <?php if($formData['graduationCompletionYear'][0] == $graduationCompletionYear) { echo 'selected=selected'; } ?> value='<?php echo $graduationCompletionYear; ?>'><?php echo $graduationCompletionYear; ?></option>
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
        <li data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
            <p>Std. XII Stream:</p>
            <div>
            <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
                <label><input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" /> <?php echo $xiiStreamValue; ?></label>
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
            <select name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                <option value="">XII Completion Year</option>
                <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
                    <option <?php if($formData['xiiYear'][0] == $xiiYear) { echo 'selected=selected'; } ?> value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
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
            <select name="xiiMarks" id="xiiMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
            <select name="workExperience" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
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
        <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocality','float:left !important;'); ?>>
            <div>
				<?php if($context == 'findInstitute'): ?>
					<strong>Preferred Locality for Studies</strong>
				<?php else: ?>
					<strong>Preferred Localities for Studies</strong>
					<p>Preference 1: </p>
				<?php endif; ?>
                <div>
                    <select id="preferredStudyLocalityCity_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='1' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <?php $this->load->view('registration/common/dropdowns/preferredLocalityCity'); ?>
                    </select>
                    <div class="clearFix spacer10"></div>
                    <select id="preferredStudyLocality_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <option value="">Select Area</option>
                    </select>
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
                    <div>
                        <select id="preferredStudyLocalityCity<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='<?php echo $i; ?>' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                            <option value="">Select City</option>
                            <?php echo $fields['preferredStudyLocality']->getValues(array('cities' => TRUE)); ?>
                        </select>
                        <div class="clearFix spacer10"></div>
                        <select id="preferredStudyLocality<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality',array('num' => $i)); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select Area</option>
                        </select>
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
    
        case 'email':
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
            <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields" maxlength="125" value="Email" default="Email" <?php if($formData['userId']) { echo "readonly='readonly'; style='background:#eee;color:'"; } ?> <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
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
            <?php  if(!isset($fields['isdCode'])) { ?>
            <input type="hidden" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" value="91-2" />
            <?php } ?>
            <div>
                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
            </div>  
        </li>
<?php
        break;

        case 'isdCode':

            $ISDCodeValues = $fields['isdCode']->getValues();

?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>

                <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);">
                        <?php foreach($ISDCodeValues as $key=>$value){ ?>
                            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                     <?php } ?>
                </select>

                <div>
                        <div class="errorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                </div>
      
        </li>
<?php
        break;

    case 'firstName':
?>

        <li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
			<div>
					<input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div>
						<div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
	</li>
	<li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
			<div>
				<input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
					<div>
					   <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
					</div>
				<div class='clearFix'></div>
			</div>
			<div class="clearFix"></div>	
        </li>
<?php
        break;
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
    
    case 'desiredCourse':
            $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
            $numDesiredCourses = 0;
            foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
                $numDesiredCourses += count($coursesInGroup);
            }
            if($numDesiredCourses == 1) {
                $selectedDesiredCourse = $formData['desiredCourse'][0];
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
        <?php } else { 
                $selectedDesiredCourse = $desiredCourse;
                ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
        <?php } ?>
            <select name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse()">
                <?php if($numDesiredCourses > 1) { ?>
                    <option value="">Desired Course</option>
                <?php } ?>
                <?php $this->load->view('registration/common/dropdowns/desiredCourse',array('desiredCourses' => $desiredCourses, 'selectedDesiredCourse' => $selectedDesiredCourse)); ?>
            </select>
            <div>
                <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php        
        break;
     case 'residenceLocality':            
            if(is_array($formData['residenceCityLocality'])) {
                $residenceCityLocality = (int)$formData['residenceCityLocality'][0];
            } else {
                $residenceCityLocality = $formData['residenceCityLocality'];
            }
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCityLocality'); ?>>
            <select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this); autoPopulateLocality(this, '<?php echo $formData['widget']; ?>')" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
            <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('defaultOptionText' => 'Current City', 'residenceCityLocality'=>$residenceCityLocality)); ?>
            </select>
            <div>
                <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
            </div>            
        </li>
        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?>>
            <select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <option value="">Locality</option>
            </select>
            <div>
                <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>   
        <?php
                break;
    case 'residenceCity':
            if(is_array($formData['residenceCity'])) {
                $residenceCity = (int)$formData['residenceCity'][0];
            } else {
                $residenceCity = $formData['residenceCity'];
            }
?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
            <?php $this->load->view('registration/common/dropdowns/residenceCity',array('isNational' => true,'defaultOptionText' => 'Current City','residenceCity'=>$residenceCity)); ?>
            </select>
            <div>
                <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
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
                    <label><input type="checkbox" name="fund[]" class="fund_<?php echo $regFormId; ?>" id="fund_<?php echo $fundValue; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fund'); ?>  value="<?php echo $fundValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); <?php if($fundValue == 'other') echo "shikshaUserRegistrationForm['".$regFormId."'].toggleOtherFundingDetails();"; ?>"> <?php echo $fundText; ?> </label>
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
 case 'specialization':
                $mmp_courses = $fields['desiredCourse']->getValues(array('mmpFormId'=>$mmpFormId));
                if(empty($desiredCourse) && is_array($mmp_courses) && ( (!empty($mmp_courses[0])) || (!empty($mmp_courses['UG Courses'])) )) {
					if(!empty($mmp_courses[0])) {
						foreach($mmp_courses[0] as $mmp_course_id=>$val) {
								$desiredCourse = $mmp_course_id;
						}
					}
					if(!empty($mmp_courses['UG Courses'])) {
						foreach($mmp_courses['UG Courses'] as $mmp_course_id=>$val) {
								$desiredCourse = $mmp_course_id;
						}
					}
					$single_default_course = "YES";
				}
                $specializations = $fields['specialization']->getValues(array('desiredCourse' => $desiredCourse));
                if(is_array($specializations) && count($specializations) > 0) {
                        $specialization = $formData['specialization'];
                    if(is_array($specialization)) {
                        $specialization = (int)$formData['specialization'][0];
                    }
        ?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('specialization'); ?>>
            <select name="specialization" id="specialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('specialization'); ?>>
                <option value="">Desired Specialization</option>
                <?php foreach($specializations as $specializationValue => $specializationText) { ?>
                <option <?php if($specialization == $specializationValue) { echo 'selected="selected"'; } ?> value='<?php echo $specializationValue; ?>'><?php echo $specializationText; ?></option>
                <?php } ?>
            </select>
            <div>
                <div class="regErrorMsg" id="specialization_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
        <?php
                }
                break;
}
}
}
?>
<script>
    var single_default_course = '<?php echo $single_default_course;?>';
</script>
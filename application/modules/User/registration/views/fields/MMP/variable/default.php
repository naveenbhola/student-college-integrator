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
                            <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
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
                            <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
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
                            <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
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
                            <div class="regErrorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
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
            case 'mode':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('mode'); ?>>
                    <label>Mode<span><?php echo $registrationHelper->markMandatory('mode'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                    	<div>
                        <?php foreach($fields['mode']->getValues() as $modeValue => $modeText) { ?>
                            <input type="checkbox" name="mode[]" class="mode_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mode'); ?>  value="<?php echo $modeValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" > <?php echo $modeText; ?> &nbsp;&nbsp;
                        <?php } ?>
                        </div>
                        <div>
                            <div id="mode_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                </li>    
        <?php
                break;
            case 'preferredStudyLocation':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocation'); ?>>
                    <label>Preferred Study Location(s)<span><?php echo $registrationHelper->markMandatory('preferredStudyLocation'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div class="selectStyleDiv" id="preferredStudyLocationLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showPreferredStudyLocationLayer()">
                            <span class="selectStyleArrow"></span>
                            <div id="preferredStudyLocationContainer_<?php echo $regFormId; ?>" default="Select">Select</div>
                        </div>
                    
                        <?php $this->load->view('registration/common/layers/preferredStudyLocation'); ?>
                        
                        <div>
                            <div id="preferredStudyLocation_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                </li>
                <?php
                break;
            case 'destinationCountry':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
                    <label>Destination Country(s)<span><?php echo $registrationHelper->markMandatory('destinationCountry'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div class="selectStyleDiv" id="destinationCountryLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showDestinationCountryLayer()">
                            <span class="selectStyleArrow"></span>
                            <div id="destinationCountryContainer_<?php echo $regFormId; ?>" default="Select">Select</div>
                        </div>
                        <?php $this->load->view('registration/common/layers/destinationCountry'); ?>
                        <div>
                            <div class="regErrorMsg" id="destinationCountry_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'degreePreference':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('degreePreference'); ?>>
                    <label>Degree Preference<span><?php echo $registrationHelper->markMandatory('degreePreference'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div>
                            <?php $x = 0; foreach($fields['degreePreference']->getValues() as $degreePreferenceValue => $degreePreferenceText) { ?>
                                <input type="checkbox" name="degreePreference[]" class="degreePreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('degreePreference'); ?> value="<?php echo $degreePreferenceValue; ?>" id="degreePreference_<?php echo $degreePreferenceValue; ?>_<?php echo $regFormId; ?>" <?php if(is_array($formData['degreePreference']) && in_array($degreePreferenceValue,$formData['degreePreference'])) echo "checked='checked'"; ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleDegreePreferences(); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $degreePreferenceText; ?>
                            <?php echo $x%2 == 0 ? '&nbsp;' : '<br />'; $x++; } ?>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="degreePreference_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'exams':
                if($fields['exams']->getValues()) {
        ?>
                    <li <?php echo $registrationHelper->getBlockCustomAttributes('exams'); ?>>
                        <label>Exams Taken/Plan to Take<span><?php echo $registrationHelper->markMandatory('exams'); ?></span></label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class='fields-col'>
                            <div class="selectStyleDiv" id="examLayerButton_<?php echo $regFormId; ?>" <?php if ($mmpData['display_on_page'] == 'normalmmp') { ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showExamLayer()" <?php } else { ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showExamLayer(event)" <?php } ?> >
                                <span class="selectStyleArrow"></span>
                                <div id="examContainer_<?php echo $regFormId; ?>" default="Select">Select</div>
                            </div>
                        
                            <?php $this->load->view('registration/common/layers/exams', array('formData'=>$formData, 'mmpData'=>$mmpData)); ?>
                            
                            <div>
                                <div id="exams_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                            </div>
                        </div>
                    </li>
        <?php
                }
                break;
            case 'examsAbroad':
        ?>          
                <li <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad'); ?>>
                    <label>Exams Taken<span><?php echo $registrationHelper->markMandatory('examsAbroad'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div>
                            <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="TOEFL" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> TOEFL &nbsp;
                            <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="IELTS" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> IELTS &nbsp;
                            
                            <span id="SAT_wrapper_<?php echo $regFormId; ?>">
                                <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="SAT" id="exam_SAT_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> SAT
                                <input type="text" class="register-fields2" name="SAT_score" id="SAT_score_<?php echo $regFormId; ?>" regFieldId="SAT_score" style="width:50px !important; display: none" value="Score" default="Score" caption="SAT score" maxlength="4" minscore="600" maxscore="2400" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                            </span>
                            
                            <div>
                                <span id="GMAT_wrapper_<?php echo $regFormId; ?>" style="display:none;">
                                    <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="GMAT" id="exam_GMAT_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> GMAT &nbsp;
                                    <input type="text" class="register-fields2" name="GMAT_score" id="GMAT_score_<?php echo $regFormId; ?>" regFieldId="GMAT_score" style="width:50px !important; display: none" value="Score" default="Score" caption="GMAT score" maxlength="3" minscore="400" maxscore="800" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                </span>
                                <span id="GRE_wrapper_<?php echo $regFormId; ?>" style="display:none;">
                                    <input type="checkbox" name="exams[]" class="exams_<?php echo $regFormId; ?>" regfieldid="exams" caption="exams" value="GRE" id="exam_GRE_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamScore(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> GRE
                                    <input type="text" class="register-fields2" name="GRE_score" id="GRE_score_<?php echo $regFormId; ?>" regFieldId="GRE_score" style="width:50px !important; display: none" value="Score" default="Score" caption="GRE score" maxlength="4" minscore="500" maxscore="1600" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
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
            case 'whenPlanToStart':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToStart'); ?>>
                    <label>When do you plan to start ?<span><?php echo $registrationHelper->markMandatory('whenPlanToStart'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <select name="whenPlanToStart" id="whenPlanToStart_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToStart'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['whenPlanToStart']->getValues() as $plannedToStartValue => $plannedToStartText) { ?>
                                <option value="<?php echo $plannedToStartValue; ?>" <?php if($formData['whenPlanToStart'] == $plannedToStartValue) echo "selected='selected'"; ?>>
                                    <?php echo $plannedToStartText; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="whenPlanToStart_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'whenPlanToGo':
        ?>          
                <li <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                    <label>When do you plan to go ?<span><?php echo $registrationHelper->markMandatory('whenPlanToGo'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                                <option value="<?php echo $plannedToGoValue; ?>"><?php echo $plannedToGoText; ?></option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'graduationStatus':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationStatus'); ?>>
                   <label>Graduation Status<span><?php echo $registrationHelper->markMandatory('graduationStatus'); ?></span></label>
                   <span class="colon-separater">:&nbsp;</span>
                   <div class="fields-col">
                        <div>
                            <?php foreach($fields['graduationStatus']->getValues() as $graduationStatusValue) { ?>
                                <input type="radio" name="graduationStatus" class="graduationStatus_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStatus'); ?> value="<?php echo $graduationStatusValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"> <?php echo $graduationStatusValue; ?> &nbsp;
                            <?php } ?>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="graduationStatus_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'graduationDetails':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationDetails'); ?>>
                <div style='float:left; width:350px;'>
                    <label>Graduation Details<span><?php echo $registrationHelper->markMandatory('graduationDetails'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col" style="width:150px;">
                        <select style="width:150px;" name="graduationDetails" id="graduationDetails_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationDetails'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); " onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['graduationDetails']->getValues() as $graduationCourse) { ?>
                                <option value='<?php echo $graduationCourse; ?>'><?php echo $graduationCourse; ?></option>
                            <?php } ?>
                        </select>
                        
                    </div>
                </div>
                
                <div <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks','float:left; width:70px;'); ?>>
                    <div class="fields-col" style="width:100%;">
                        <select style="width:100%;" name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Marks</option>
                            <?php foreach($fields['graduationMarks']->getValues() as $graduationMarks) { ?>
                                <option value='<?php echo $graduationMarks; ?>'><?php echo $graduationMarks; ?>%</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="clearFix"></div>
                
                <div style="margin-left: 190px;">
                    <div>
                        <div class="regErrorMsg" id="graduationDetails_error_<?php echo $regFormId; ?>"></div>
                    </div>
                    <div>
                        <div class="regErrorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                </li>
        <?php
                break;
            case 'graduationMarks123':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
                    <label>Graduation Marks<span><?php echo $registrationHelper->markMandatory('graduationMarks'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?>>
                            <option value="">Select</option>
                            <?php foreach($fields['graduationMarks']->getValues() as $graduationMarks) { ?>
                                <option value='<?php echo $graduationMarks; ?>'><?php echo $graduationMarks; ?>%</option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'graduationCompletionYear':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?>>
                    <label>Graduation Completion Year<span><?php echo $registrationHelper->markMandatory('graduationCompletionYear'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select style="width:150px;" name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
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
                    </div>
                </li>
        <?php
                break;
            case 'xiiStream':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
                    <label>Std. XII Stream<span><?php echo $registrationHelper->markMandatory('xiiStream'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <div>
                        <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
                            <input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" /> <?php echo $xiiStreamValue; ?> &nbsp;
                        <?php } ?>
                        </div>
                        <div>
                            <div id= "xiiStream_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'xiiYear':
        ?>                        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?>>
                <div style="float:left; width:350px;">
                    <label>XII Completion Year<span><?php echo $registrationHelper->markMandatory('xiiYear'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col" style="width:150px;">
                        <select  style="width:100%;" name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <option value="">Select</option>
                        <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
                            <option <?php if($formData['xiiYear'][0] == $xiiYear) { echo 'selected=selected'; } ?> value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
                        <?php } ?>	
                        </select>
                    </div>
                </div>
                </li>
        <?php
                break;
            case 'xiiMarks123':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiMarks'); ?>>
                    <label>XII Marks<span><?php echo $registrationHelper->markMandatory('xiiMarks'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="xiiMarks" id="xiiMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiMarks'); ?>>
                        <option value="">Select</option>
                        <?php foreach($fields['xiiMarks']->getValues() as $xiiMarks) { ?>
                            <option value='<?php echo $xiiMarks; ?>'><?php echo $xiiMarks; ?></option>
                        <?php } ?>	
                        </select>
                        <div>
                            <div id= "xiiMarks_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'workExperience':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
                    <label>Work Experience<span><?php echo $registrationHelper->markMandatory('workExperience'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="workExperience" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="" title="Select">Work Experience</option>
                            <?php foreach($fields['workExperience']->getValues() as $workExperienceValue => $workExperienceText) { ?>
                                <option value='<?php echo $workExperienceValue; ?>'><?php echo htmlentities($workExperienceText); ?></option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'preferredStudyLocality':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('preferredStudyLocality'); ?>>
                    <label style="width:250px; font-weight:bold;">Preferred Localities for Studies<span><?php echo $registrationHelper->markMandatory('preferredStudyLocality'); ?></span></label>
                    <div class="spacer5 clearFix"></div>
                    <label>Preference 1</label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <div style="float:left; width:115px;">
                            <select style="width:98%;" id="preferredStudyLocalityCity_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='1' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                                <?php $this->load->view('registration/common/dropdowns/preferredLocalityCity'); ?>
                            </select>
                        </div>
                        <div style="float:left; width:115px;">
                            <select style="width:98%;" id="preferredStudyLocality_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                        <div class="clearFix"></div>
                    </div>
                    <div class="clearFix"></div>
                    
                    <?php for($i = 2;$i <= 4;$i++) { ?>
                        <div id="preferredStudyLocality_<?php echo $i; ?>_block_<?php echo $regFormId; ?>" style="display:none; padding-top:5px;">
                            <label>Preference <?php echo $i; ?></label>
                            <span class="colon-separater">:&nbsp;</span>
                            <div class="fields-col">
                                <div style="float:left; width:115px;">
                                    <select style="width:98%;" id="preferredStudyLocalityCity<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocalityCity[]" prefNum='<?php echo $i; ?>' onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalities(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                                        <?php $this->load->view('registration/common/dropdowns/preferredLocalityCity'); ?>
                                    </select>
                                </div>
                                <div style="float:left; width:115px;">
                                    <select style="width:98%;" id="preferredStudyLocality<?php echo $i; ?>_<?php echo $regFormId; ?>" name="preferredStudyLocality[]" class="preferredStudyLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocality',array('num' => $i)); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                                        <option value="">Select Area</option>
                                    </select>
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            <div class="clearFix"></div>
                        </div>
                    <?php } ?>
                    <div style="margin-left: 192px;">
                        <div>
                            <div class="regErrorMsg" id="preferredStudyLocality_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div id="preferredStudyLocality_add_action_block_<?php echo $regFormId; ?>">
                            <div class="spacer5 clearFix"></div>
                            <div><a id="addLocalityPreference_<?php echo $regFormId; ?>" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].addLocalityPreference();">+ Add another preference</a></div>
                            <div class="clearFix"></div>
                        </div>
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
            <label>Residence Location<span><?php echo $registrationHelper->markMandatory('residenceCityLocality'); ?></span></label>
            <span class="colon-separater">:&nbsp;</span>
            <div class="fields-col">
                <select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this); autoPopulateLocality(this, '<?php echo $formData['widget']; ?>')" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('defaultOptionText' => 'Select','residenceCityLocality'=>$residenceCityLocality)); ?>
                </select>
                <div>
                    <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?>>
            <label>Residence Locality<span><?php echo $registrationHelper->markMandatory('residenceLocality'); ?></span></label>
            <span class="colon-separater">:&nbsp;</span>
            <div class="fields-col">
                <select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <option value="">Select</option>
                </select>
                <div>
                    <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
                </div>
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
                    <label>Residence Location<span><?php echo $registrationHelper->markMandatory('residenceCity'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity', array('isNational' => true,'formData'=>$formData,'residenceCity'=>$residenceCity)); ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
                <?php
                break;
            case 'desiredGraduationLevel':
        ?>  
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
                    <label>Desired Graduation Level<span><?php echo $registrationHelper->markMandatory('desiredGraduationLevel'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <div>
                        <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                            <input type="radio" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadExams(this)"> <?php echo $desiredGraduationLevelText; ?> &nbsp;&nbsp;
                        <?php } ?>
                        </div>
                        <div>
                            <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'callPreference':
        ?>          
                <li <?php echo $registrationHelper->getBlockCustomAttributes('callPreference'); ?>>
                    <label>Would you like our partner consultants to call you & assist with process<span><?php echo $registrationHelper->markMandatory('callPreference'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="callPreference" id="callPreference_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('callPreference'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['callPreference']->getValues() as $callPreferenceValue => $callPreferenceText) { ?>
                                <option value="<?php echo $callPreferenceValue; ?>"><?php echo $callPreferenceText; ?></option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="callPreference_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'fund':
        ?>                  
                <li <?php echo $registrationHelper->getBlockCustomAttributes('fund'); ?>>
                    <label>How do you plan to fund your education<span><?php echo $registrationHelper->markMandatory('fund'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class='fields-col'>
                        <div>
                        <?php $i = 1; foreach($fields['fund']->getValues() as $fundValue => $fundText) { ?>
                            <input type="checkbox" name="fund[]" class="fund_<?php echo $regFormId; ?>" id="fund_<?php echo $fundValue; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fund'); ?>  value="<?php echo $fundValue; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); <?php if($fundValue == 'other') echo "shikshaUserRegistrationForm['".$regFormId."'].toggleOtherFundingDetails();"; ?>"> <?php echo $fundText; ?> &nbsp;&nbsp;
                        <?php if($i%2 == 0) { echo "<br />"; } $i++; } ?>
                        </div>
                        <div>
                            <div id="fund_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                        </div>
                    </div>
                    
                    <div id="otherFundingDetails_block_<?php echo $regFormId; ?>" style="display: none; padding-top:2px; clear:both">
                        <label>Other Funding Details</label>
                        <span class="colon-separater">:&nbsp;</span>
                        <div class='fields-col'>
                            <input type="text" name="otherFundingDetails" id="otherFundingDetails_<?php echo $regFormId; ?>" regFieldId="otherFundingDetails" class="register-fields2" />
                        </div>
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
            <label>Desired Specialization</label>
            <span class="colon-separater">:&nbsp;</span>
            <div class="fields-col" style="width:150px;">
                <select  style="width:100%;" name="specialization" id="specialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('specialization'); ?>>
                    <option value="">Select</option>
                    <?php foreach($specializations as $specializationValue => $specializationText) { ?>
                    <option <?php if($specialization == $specializationValue) { echo 'selected="selected"'; } ?> value='<?php echo $specializationValue; ?>'><?php echo $specializationText; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <div class="regErrorMsg" id="specialization_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
        <?php
                }
                break;

            case 'desiredCourse':
                $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
                $numDesiredCourses = 0;
                foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
                    $numDesiredCourses += count($coursesInGroup);
                }
            ?>

                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
                    <label>Desired Course<span>*</span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <?php
                        if($numDesiredCourses == 1) {
                            foreach($desiredCourses as $groupName => $courses) {
                                if(count($courses) > 0) {
                                    foreach($courses as $courseId => $courseName) {
                                        echo "<div style='margin-top:3px;'>$courseName</div>";
                                    }
                                }
                            }
                            $selectedDesiredCourse = $formData['desiredCourse'][0];
                        } else {
                            $selectedDesiredCourse = $desiredCourse;
                        }
                        ?>
                        <select <?php if($numDesiredCourses == 1) { echo "style='display:none;';"; } ?> name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse()">
                            <?php if($numDesiredCourses > 1) { ?>
                                <option value="">Select</option>
                            <?php } ?>
                            <?php $this->load->view('registration/common/dropdowns/desiredCourse',array('desiredCourses' => $desiredCourses, 'selectedDesiredCourse' => $selectedDesiredCourse)); ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>

            <?php
                break;

            case 'email':
            ?>

                <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
                    <label>Email<span><?php echo $registrationHelper->markMandatory('email'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields2" maxlength="125" tip="email_idM" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> <?php if($formData['userId']) echo "readonly='readonly'; style='background:#eee;'"; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : false" />
                        <div>
                            <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>

            <?php 
                break;

            case 'isdCode';
                $ISDCodeValues = $fields['isdCode']->getValues();  
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
                    <label>Country Code<span><?php echo $registrationHelper->markMandatory('isdCode'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" <?php if($mmpData['page_type'] != 'abroadpage') { ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" <?php } else { ?> class="universal-select" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" <?php } ?> regFieldId="isdCode" minlength="2" maxlength="4" value="ISD Code"  profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                            <?php foreach($ISDCodeValues as $key=>$value){ ?>
                                <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                         <?php } ?>
                        </select>
                        <div>
                            <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
            
            <?php
            break;

            case 'mobile';
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
                    <label>Mobile No<span><?php echo $registrationHelper->markMandatory('mobile'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="register-fields2" maxlength="10" minlength="10" tip="mobile_numM" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                        <?php  if(!isset($fields['isdCode'])) { ?>
                        <input type="hidden" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" value="91-2" />
                        <?php } ?>
                        <div>
                            <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
            <?php
            break;

            case 'firstName';
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
                    <label>First Name<span><?php echo $registrationHelper->markMandatory('firstName'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <input meta="first name" type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="register-fields2" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                        <div>
                            <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
            <?php
            break;

            case 'lastName';
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
                    <label>Last Name<span><?php echo $registrationHelper->markMandatory('lastName'); ?></span></label>
                    <span class="colon-separater">:&nbsp;</span>
                    <div class="fields-col">
                        <input meta="last name" type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="register-fields2" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                        <div>
                            <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
            <?php
            break;

        }
    }
}
?>
<script>
var single_default_course = '<?php echo $single_default_course;?>';
</script>

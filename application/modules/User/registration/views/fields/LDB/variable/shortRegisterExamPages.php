<?php

foreach($fields as $fieldId => $field) {
switch($fieldId) {
    case 'residenceCity': ?>
    <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <div class="custom-dropdown">
                <select name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Current City', 'isNational' => true)); ?>
                </select>
            </div>
            <div>
                <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php break;
    case 'xiiYear': ?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?>>
            <div class="custom-dropdown">
                <select name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <option value="">XII Completion Year</option>
                    <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
                        <option value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
                    <?php } ?>	
                </select>
            </div>
            <div>
                <div class="regErrorMsg" id="xiiYear_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php break;
    case 'exams': ?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('exams','position:relative;'); ?>>
            <div style="padding: 0;width:100%;" class="selectStyleDiv custom-dropdown" id="examLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showExamLayer(event)">
                <!--<span class="selectStyleArrow"></span>-->
                <div class="select-overlap"></div>
                <select id="examContainer_<?php echo $regFormId; ?>">
                    <option value="">Exams Taken</option>
                </select>
            </div>
            
            <?php $this->load->view('registration/common/layers/exams'); ?>
            
            <div>
                <div id="exams_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php break;
    case 'graduationCompletionYear': ?>        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?>>
            <div class="custom-dropdown">
                <select name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <option value="">Graduation Completion Year</option>
                    <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
                        <option value='<?php echo $graduationCompletionYear; ?>'><?php echo $graduationCompletionYear; ?></option>
                    <?php } ?>	
                </select>
            </div>
            <div>
                <div class="regErrorMsg" id="graduationCompletionYear_error_<?php echo $regFormId; ?>"></div>
            </div>
            <div>
                    <div class="regErrorMsg" id="graduationCompletionDate_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
<?php
        break;
            case 'desiredGraduationLevel':
?>  
        <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
            <label>Desired Graduation Level:</label>
            <div>
            <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                <input type="radio" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadExams(this)"> <?php echo $desiredGraduationLevelText; ?> &nbsp;&nbsp;
            <?php } ?>
            </div>
            <div>
                <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
            </div>
        </li>
<?php
        break;
}
}
?>
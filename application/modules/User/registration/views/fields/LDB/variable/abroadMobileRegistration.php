<ul class="form-display customInputs">
    <?php
    if(isset($fields['email'])){
        $email = 'Email Id';
        $disabled = '';
        if(!empty($abroadShortRegistrationData['email'])) {
            $email = $abroadShortRegistrationData['email'];
            $disabled = 'disabled="disabled"';

            //in case of loggedinuser set contactByConsultant field visible
            if(!$forResponse){
                $fields['contactByConsultant']->setVisible('Yes');
                $fields['contactByConsultant']->setMandatory('Yes');
                $contactByConsultantFlag = true;
            }
        }
        ?>
        <li>
            <div <?=$registrationHelper->getBlockCustomAttributes('email');?>>
                <input type="text" name="email" id="email_<?php echo $regFormId; ?>" maxlength="125" placeholder="Email ID" class="universal-txt" data-enhance = "false" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)" <?=($disabled)?> />
                <div style="display:none;">
                    <div class="errorMsg error-msg" id="email_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
    <?php } ?>
    <?php
    if(isset($fields['isdCode'])){
        $mobile = 'ISD No.';
        $disabled = '';
        if(!empty($abroadShortRegistrationData['isdCode'])) {
            $mobile = $abroadShortRegistrationData['isdCode'];
            $disabled = 'disabled="disabled"';
        }
        ?>
        <li>
            <div class="flLt" style="width:48%">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
                    <?php
                    $ISDCodeValues = $fields['isdCode']->getValues(); ?>
                    <select class="universal-select" name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" regFieldId="isdCode" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"  style="color: #999; font-size: 12px;">
                        <?php foreach($ISDCodeValues as $key=>$value){ ?>
                            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="isdCodde_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </div>
            <div class="flRt" style="width:48%">
                <?php
                if(isset($fields['mobile'])){
                    $mobile = 'Mobile No.';
                    $disabled = '';
                    if(!empty($abroadShortRegistrationData['mobile'])) {
                        $mobile = $abroadShortRegistrationData['mobile'];
                        $disabled = 'disabled="disabled"';
                    }
                    ?>
                    <div <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
                        <input style="padding:7px;" name="mobile" id="mobile_<?php echo $regFormId; ?>" type="tel" placeholder="Mobile no." class="universal-txt" data-enhance = "false" maxlength="10" minlength="10" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> />
                        <div style="display:none;">
                            <div class="errorMsg error-msg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </li>
    <?php } ?>
    <li>
        <?php
        if(isset($fields['firstName'])){
            $firstName = 'First Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['firstName'])) {
                $firstName = $abroadShortRegistrationData['firstName'];
                $disabled = 'disabled="disabled"';
            }
            ?>
            <div class="flLt form-display-col" <?php echo $registrationHelper->getBlockCustomAttributes('firstName');?>>
                <input name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" type="text" placeholder="First Name" class="universal-txt" data-enhance = "false" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> default="First Name" />
                <div style="display:none;">
                    <div class="errorMsg error-msg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        <?php } ?>
        <?php
        if(isset($fields['lastName'])){
            $lastName = 'Last Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['lastName'])) {
                $lastName = $abroadShortRegistrationData['lastName'];
                $disabled = 'disabled="disabled"';
            }
            ?>
            <div class="flRt form-display-col" <?php echo $registrationHelper->getBlockCustomAttributes('lastName');?>>
                <input name="lastName" id="lastName_<?php echo $regFormId; ?>" type="text" placeholder="Last Name" class="universal-txt" data-enhance = "false" minlength="1" maxlength="50" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> default="Last Name" />
                <div>
                    <div class="errorMsg error-msg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        <?php } ?>
    </li>
    <?php
    if(isset($fields['whenPlanToGo'])){
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
        <li>
            <p class="form-label">When do you plan to start?</p>
            <div class="clearfix">
                <?php $planToGoCount = 1;
                foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                    <div class="flLt planning-col">
                        <input type="radio" id="year<?=($planToGoCount)?>" name="year-option" data-enhance = "false" onclick="changeSelectTo('<?=$plannedToGoValue?>',this);" <?php if($whenPlanToGo == $plannedToGoValue){echo 'checked';} ?> >
                        <label for="year<?=($planToGoCount++)?>">
                            <span class="sprite"></span>
                            <strong style="margin-left:5px;"><?=$plannedToGoText?></strong>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <div data-enhance="false" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" style="display:none;">
                    <option value="" selected="selected">When do you plan to start?</option>
                    <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                        <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
                    <?php } ?>
                </select>
                <div style="display:none;">
                    <div class="errorMsg error-msg clearfix" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>

        </li>
    <?php } ?>
    <?php
    if(isset($fields['residenceCity'])){
        ?>
        <li>
            <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
                <select name="residenceCity" class="universal-select" data-enhance = "false" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical')); ?>
                </select>
                <div style="display:none;">
                    <div class="errorMsg error-msg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
    <?php } ?>
    <?php
    if(isset($fields['destinationCountry'])){
        if($forResponse && !empty($destinationCountry)) {
            $ischecked = $destinationCountry;
            $countryNumSelected = ' (1 selected)'; // if a new user is creating response then the country of that course(on which response is being generated) will be prefilled, hence this will always be one.
        }
        $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
        ?>
        <li>
            <div style="position:relative" <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
                <div id="countryChooser" class="custom-dropdown" onclick="toggleCountryDropdown();">
                    <div class="drop-overlay"></div>
                    <select class="universal-select">
                        <option>Choose country to study<?=($countryNumSelected)?></option>
                    </select>
                </div>
                <div id="countryDropdownLayer" class="customDropLayer customInputs" style="display:none;" data-enhance="false">
                    <strong class="drop-title">You can select multiple countries here</strong>
                    <div class="drop-layer-cont">
                        <ul>
                            <?php foreach ($destinationCountry as $key => $country) {
                                if($country->getId() == 1){ continue; }?>
                                <li>
                                    <div>
                                        <input type="checkbox" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" onclick="toggleCheckbox(event,this);" <?php if($country->getId() == $ischecked){ echo 'checked = "checked" disabled = "disabled"';} ?>>
                                        <label data-enhance = "false" for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" style = "cursor:pointer;">
                                            <span class="sprite flLt"></span>
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
    <?php } ?>
    <li <?php echo ($forResponse?'style="display:none;"':''); ?>>
        <?php
        if(isset($fields['abroadDesiredCourse'])){
            if($forResponse)
            {  ?>
                <input type="hidden" name="desiredCourse" id="desiredCourseForResponse" value="<?php echo $desiredCourse; ?>" />
                <input type="hidden" id='isPaid' name='isPaid' value="<?php echo $isPaid; ?>" />
                <input type="hidden" name='abroadSpecialization' id='abroadSpecializationForResponse' value="<?php echo $specialization; ?>" />
                <?php
            }
            else{
                echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '".$currentLDBCourseId."' />";
            }
        }
        if(!$forResponse){
            ?>
            <div id='fieldOfInterest_block_parent_<?php echo $regFormId; ?>'>
                <?php       if(isset($fields['fieldOfInterest'])){
                    ?>

                    <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
                        <select class="universal-select signup-select" data-enhance = "false" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                            <option value="">Which course do you want to pursue?</option>
                            <optgroup label="Choose a popular course">
                                <?php
                                $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                                foreach($desiredCourses as $course)
                                {
                                    if($course['SpecializationId'] == $currentLDBCourseId)
                                    {
                                        $checked = 'checked';
                                    } else
                                    {
                                        $checked = '';
                                    }
                                    ?>
                                    <option value="<?=$course['SpecializationId']?>"><?=$course['CourseName']?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                            <optgroup label="Or Choose a stream">
                                <?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) { ?>
                                    <option value="<?php echo $categoryId; ?>"><?php echo $categoryName['name']; ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>
                    </div>
                <?php   }

                if(isset($fields['desiredGraduationLevel']))
                {
                    ?>
                    <div class="sub-filed grad-level" style="display: none;padding-bottom:23px;">
                        <div <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
                            <p style = "font-size:11px;font-weight:bold;margin-bottom: 5px;">Choose level of study</p>
                            <div class="form-sec" id="twoStepLevelOfStudy" style="margin-bottom: 0">
                                <div>
                                    <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                                        <div class="flLt " style = "margin-right:10px;">
                                            <input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
                                            <label style = "display:inline-block;margin-left: 10px;" for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
                                                <span class="sprite"></span>
                                                <strong style="font-size:12px !important;"><?=$desiredGraduationLevelText['CourseName']?></strong>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="clearFix"></div>

                            </div>
                        </div>
                        <div>
                            <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg error-msg"></div>
                        </div>
                    </div>
                    <?php
                }

                if(isset($fields['abroadSpecialization']))
                {
                    ?>
                    <div class = "sub-filed abroad-specialization" style="display: none;">
                        <div class="custom-dropdown " <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
                            <select name="abroadSpecialization" data-enhance = "false" class="universal-select signup-select" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                                <option>Select specialization (optional)</option>
                            </select>
                            <div>
                                <div class="errorMsg error-msg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                            </div>
                        </div>
                    </div>
                <?php   }
                ?>
                <div>
                    <div class="errorMsg error-msg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        <?php   }
        ?>
    </li>


    <?php if(isset($fields['graduationStream'])){ ?>
        <?php if(isset($fields['graduationStream'])){ ?>
            <li class="MastersEducation hidden">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
                    <select name="graduationStream" class="universal-select" data-enhance = "false" id="graduationStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value="">Graduation stream</option>
                        <?php foreach($fields['graduationStream']->getValues() as $gradStreamValue => $gradStreamText) { ?>
                            <option value="<?php echo $gradStreamValue; ?>"><?php echo $gradStreamText; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="graduationStream_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </li>
        <?php } ?>

        <?php if(isset($fields['graduationMarks'])){ ?>
            <li class="MastersEducation hidden">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
                    <select name="graduationMarks" class="universal-select" data-enhance = "false" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value="">Graduation Percentage</option>
                        <?php $valuetoshow = $fields['graduationMarks']->getValues(); ?>
                        <?php foreach($valuetoshow as $gradMarksValue => $gradMarksText) { ?>
                            <option value="<?php echo $gradMarksValue; ?>"><?php echo $gradMarksText; ?></option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php } ?>

        <?php if(isset($fields['workExperience'])){ ?>
            <li class="MastersEducation hidden" style="margin-bottom:10px;">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
                    <select name="workExperience" class="universal-select" data-enhance = "false" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value="">Work Experience</option>
                        <?php $valuetoshow = $fields['workExperience']->getSAValues(); ?>
                        <?php foreach($valuetoshow as $workExpValue => $workExpText) { ?>
                            <option value="<?php echo $workExpValue; ?>"><?php echo $workExpText; ?></option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php } ?>
    <?php } ?>

    <?php if(isset($fields['tenthBoard'])){ ?>
        <?php if(isset($fields['currentClass'])){ ?>
            <li class="BachelorsEducation hidden" style="margin-bottom:10px;">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
                    <select name="currentClass" class="universal-select " data-enhance = "false" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value=""><?php echo $fields['currentClass']->getCaption(); ?></option>
                        <?php foreach($fields['currentClass']->getValues() as $key => $value) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="currentClass_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php } ?>

        <?php if(isset($fields['tenthBoard'])){ ?>
            <li class="BachelorsEducation hidden">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
                    <select name="tenthBoard" class="universal-select " data-enhance = "false" id="tenthBoard_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthBoard'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].getTenthMarksAccToBoard(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value="">Class <?php echo $fields['tenthBoard']->getCaption(); ?></option>
                        <?php foreach($fields['tenthBoard']->getValues() as $tenthBoardValue => $tenthBoardText) { ?>
                            <option value="<?php echo $tenthBoardValue; ?>"><?php echo $tenthBoardText; ?></option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="tenthBoard_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </li>
        <?php } ?>

        <?php if(isset($fields['tenthmarks'])){ ?>
            <li class="BachelorsEducation hidden">
                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
                    <select name="tenthmarks" class="universal-select " data-enhance = "false" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <option value="">Select <?php echo $fields['tenthmarks']->getCaption(); ?></option>
                        <?php $valuetoshow = $fields['tenthmarks']->getValues(); ?>
                        <?php foreach($valuetoshow['CBSE'] as $tenthMarksValue => $tenthMarksText) { ?>
                            <option value="<?php echo $tenthMarksValue ; ?>" ><?php echo $tenthMarksText; ?></option>
                        <?php } ?>
                    </select>
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="tenthmarks_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php } ?>

        <?php if(isset($fields['currentSchool'])){ ?>
            <li class="BachelorsEducation hidden" style="margin-bottom:10px;">
                <div <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
                    <input type="text" name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" maxlength="100" placeholder="<?php echo $fields['currentSchool']->getLabel(); ?>" class="universal-txt" data-enhance = "false" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> />
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php } ?>
    <?php } ?>


    <?php
    if(isset($fields['examTaken'])){
        if(!empty($abroadShortRegistrationData['examsAbroad'])) {
            $examTaken = 'yes';
        }
        else if(!empty($abroadShortRegistrationData['passport'])&& $abroadShortRegistrationData['bookedExamDate']!=1) {
            $examTaken = 'no';
        }
        else if($abroadShortRegistrationData['bookedExamDate'] ==1)
        {
            $examTaken = 'bookedExamDate';
        }
        ?>
        <li>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
                <p class="form-label">Have you given any study abroad exam?</p>
                <div class="clearfix">
                    <div class="flLt planning-col2">
                        <input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" name="examTaken" class="examTaken_<?php echo $regFormId; ?>" value="yes" data-enhance = "false" <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);showExamList();" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked="checked"'; ?>>
                        <label for="examTaken_yes_<?php echo $regFormId; ?>">
                            <span class="sprite"></span>
                            <strong style="margin-left:5px;">Yes</strong>
                        </label>
                    </div>
                    <div class="flLt planning-col2">
                        <input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" name="examTaken" class="examTaken_<?php echo $regFormId; ?>" value="no" data-enhance = "false" <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);hideExamList();" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked="checked"'; ?>>
                        <label for="examTaken_no_<?php echo $regFormId; ?>">
                            <span class="sprite"></span>
                            <strong style="margin-left:5px;">No</strong>
                        </label>
                    </div>
                    <div class="flLt planning-col2">
                        <input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" name="examTaken" class="examTaken_<?php echo $regFormId; ?>" value="bookedExamDate" data-enhance = "false" <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);hideExamList();" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?>>
                        <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
                            <span class="sprite"></span>
                            <strong style="margin-left:5px;"><?php echo $fields['bookedExamDate']->getLabel(); ?></strong>
                        </label>
                    </div>
                    <input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>
                </div>
                <div style="display:none;">
                    <div class="errorMsg error-msg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
    <?php } ?>
    <?php
    if(isset($fields['examsAbroad'])){
        $visible = 'No';
        $style = 'display:none;';
        if(!empty($abroadShortRegistrationData['examsAbroad'])) {
            $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
            $visible = 'Yes';
            $style = 'display:block;';
        }
        ?>
        <li id="examScoreSection" style="<?=($style)?>">
            <p class="form-label">Select &amp; Enter your exam score</p>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
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

                    ?>
                    <div class="exam-list">
                        <div class="flLt form-display-col-1">
                            <input type="checkbox" name="exams[]" class="examsAbroad_<?php echo $regFormId; ?> hiddenExamCheckbox" <?php echo $display; ?> id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"  value="<?php echo $exam['name']; ?>" <?php echo $checked; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].enableAbroadExamScore(this);" onclick="toggleExamScore(this);" <?php echo ($value==='Score'?'':'checked="checked"'); ?> >
                            <label for="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"><span class="sprite flLt"></span><p><?php echo $exam['name']; ?></p></label>
                        </div>
                        <?php
                        // following variable returns true if input textbox is to be disabled
                        $disabledCheckVal = ($value==='Score' ||		// if corresponding checkbox is unchecked, it should be disabled
                            ($value!=='Score' && $exam['name']==='TOEFL' && $value === 0) || 	// TOEFL can have 0 as a valid score so no worry
                            ($value!=='Score' && !empty($value) && $value !== '0.0')		// if it's checked & score is invalid, enable; so that it can be validated
                        );
                        ?>
                        <div class="form-display-col-2" data-enhance = "false">
                            <input type="text" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" class="universal-txt universal-text" <?php echo ($disabledCheckVal?'disabled="disabled"':''); ?> maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score" default="Score" value="<?php echo $value; ?>" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
                            <input type="hidden" id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>" regfieldid="<?php echo $exam['name']; ?>_scoreType" name="<?php echo $exam['name']; ?>_scoreType" value="<?php echo $exam['scoreType']; ?>">
                            <div>
                                <div class="errorMsg error-msg" id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
                            </div>
                        </div>

                    </div>
                    <?php
                }
                ?>
                <div>
                    <div class="errorMsg error-msg" id="examsAbroad_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
    <?php }
    if(isset($fields['passport'])){
        $visible = 'No';
        $style = 'display:none;';
        if(!empty($abroadShortRegistrationData['passport']))
        {
            $passport = $abroadShortRegistrationData['passport'];
            if($examTaken == 'no') {
                $visible = 'Yes';
                $style = 'display:block;';
            }
        }
        ?>
        <li class="passportSection" style="<?=($style)?>" <?php echo $registrationHelper->getBlockCustomAttributes('passport', array('visible' => $visible, 'style' => $style)); ?>>
            <p class="form-label">Do you have a valid passport?</p>
            <div class="flLt planning-col">
                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                <label for="passport_yes_<?php echo $regFormId; ?>">
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">Yes</strong>
                </label>
            </div>
            <div class="flLt planning-col">
                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                <label for="passport_no_<?php echo $regFormId; ?>">
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">No</strong>
                </label>
            </div>
            <div>
                <div class="errorMsg error-msg" id="passport_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
    <?php }
    if(isset($fields['contactByConsultant']) && !($OTPforReturningUser == true)){
        if($contactByConsultant !== 'no'){
            $contactByConsultant = 'yes';
        }
        ?>
        <li <?php echo $registrationHelper->getBlockCustomAttributes('contactByConsultant'); ?>>
            <p class="form-label">Do you want to be contacted by consultants?</p>
            <div class="clearfix">
                <div class="columns flLt planning-col">
                    <input type="radio" id="contactByConsultant_yes_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="yes" <?php if(!empty($contactByConsultant) && $contactByConsultant == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                    <label for="contactByConsultant_yes_<?php echo $regFormId; ?>">
                        <span class="sprite"></span>
                        <strong style="margin-left:5px;">Yes</strong>
                    </label>
                </div>
                <div class="columns flLt planning-col">
                    <input type="radio" id="contactByConsultant_no_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="no" <?php if(!empty($contactByConsultant) && $contactByConsultant == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                    <label for="contactByConsultant_no_<?php echo $regFormId; ?>">
                        <span class="sprite"></span>
                        <strong style="margin-left:5px;">No</strong>
                    </label>
                </div>

                <div>
                    <div class="errorMsg error-msg" id="contactByConsultant_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </li>
    <?php } ?>
</ul>

<script type="text/javascript">
    var choices = ['<?php echo implode("','", $fields['currentSchool']->getValues());?>'];
</script>
<?php $this->load->view('registration/fields/LDB/variable/currentSchoolSuggestor');?>
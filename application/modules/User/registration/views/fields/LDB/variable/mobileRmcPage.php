<?php //var_dump( $abroadShortRegistrationData['examsAbroad'] ); ?>
<div class="clearfix" style="margin-bottom:10px;"><strong class="font-13">A. Study preferences</strong></div>
<ul class="form-display customInputs">
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
            <div class="flLt planning-col" style="margin-right:35px;">
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
            if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']]))
			{
				$examsAvailable = true;
                $display = 'style="visibility:hidden;"';
                $checked = 'filled = "1"';
                if(isset($examGrades[$exam['name']])) {
					//$disabled = 'disabled="disabled" checked="checked"';
                    $value = $examGrades[$exam['name']][(int)$examsAbroad[$exam['name']]];
                }
                else {
                    $value = $examsAbroad[$exam['name']];
                    if($examFloat[$exam['name']] !== TRUE) {
                        $value = (int)$value;
                    }
					//$disabled = '';
                }

                $labelFor = '';
            }

            ?>


            <div class="exam-list">
                <div class="flLt form-display-col-1">
                    <!--<a class="exam-btn" href="javascript:void(0);" onclick="toggleExamScore(this);"><?php echo $exam['name']; ?></a>-->
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
                <input type="radio" data-enhance="false" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                <label for="passport_yes_<?php echo $regFormId; ?>" >
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">Yes</strong>
                </label>
            </div>
            <div class="flLt planning-col">
                <input type="radio" data-enhance="false" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                <label for="passport_no_<?php echo $regFormId; ?>" >
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">No</strong>
                </label>
            </div>
            <div>
                <div class="errorMsg error-msg" id="passport_error_<?php echo $regFormId; ?>"></div>
            </div>
    </li>
    <?php } ?>


</ul>
	<section>
	<div class="clearfix" style="margin-bottom:10px;"><strong class="font-13">B. Education details</strong></div>
	<div id="saApplyArea">
	</div>


	</section>


	<section>
		<div class="clearfix" style="margin-bottom:10px;"><strong class="font-13">C. Personal info</strong></div>
	<ul class="form-display customInputs">
    <?php
    if(isset($fields['email'])){
        //$email = 'Email Id';
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
                    <input type="text" name="email" id="email_<?php echo $regFormId; ?>" maxlength="125" placeholder="Email ID" class="universal-txt" data-enhance = "false" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)" <?=($disabled)?> value="<?php echo $email; ?>" />
                    <div style="display:none;">
                        <div class="errorMsg error-msg" id="email_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            </li>
    <?php } ?>
    <?php
        if(isset($fields['isdCode'])){
            $disabled = '';
            if(!empty($abroadShortRegistrationData['isdCode'])) {
                $mobile = $abroadShortRegistrationData['isdCode'];
                $disabled = 'disabled="disabled"';
            }
            $ISDCodeValues = $fields['isdCode']->getValues();
            ?>
 <li>
                <div class"flLt" style="width:48%; float:left;"  <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>

                    <div class="custom-dropdown" style="width:100%;">
                        <select class="universal-select universal-select-2"  required="true" validate="validateSelect" caption="ISD Code" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                          <?php foreach($ISDCodeValues as $key=>$value){ ?>
                                <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                         <?php } ?>

                        </select>
                    </div>
                    <div>
                        <div class="errorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>

        <?php }
    if(isset($fields['mobile'])){
        //$mobile = 'Mobile No.';
        $disabled = '';
        if(!empty($abroadShortRegistrationData['mobile'])) {
            $mobile = $abroadShortRegistrationData['mobile'];
            $disabled = 'disabled="disabled"';
        }
    ?>

            <div style="float:right; width:48%" <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
                <input name="mobile" id="mobile_<?php echo $regFormId; ?>" type="tel" placeholder="Mobile no." class="universal-txt" data-enhance = "false" maxlength="10" minlength="10" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> value="<?php echo $mobile; ?>"/>
                <div style="display:none;">
                    <div class="errorMsg error-msg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div>
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
                <input name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" type="text" placeholder="First Name" class="universal-txt" data-enhance = "false" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> default="First Name" value="<?php echo htmlentities($firstName); ?>"/>
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
                <input name="lastName" id="lastName_<?php echo $regFormId; ?>" type="text" placeholder="Last Name" class="universal-txt" data-enhance = "false" minlength="1" maxlength="50" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?=($disabled)?> default="Last Name" value="<?php echo htmlentities($lastName); ?>"/>
                <div>
                    <div class="errorMsg error-msg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        <?php } ?>
    </li>

    <?php
        if(isset($fields['residenceCity'])){
            /* only a short user will have this field left out in which case this will be shown,
             * for any other logged in user this will be present along with rest of the form fields
             * due to which there will never be a need to prefill this field
             */
			if($userCity){
					$disabled = 'disabled="disabled"';
				}else{
					$disabled = '';
				}
    ?>
    <li>
        <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
            <select name="residenceCity" class="universal-select universal-select-2" <?=($disabled)?> data-enhance = "false" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
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
			$countriesSelected = array();
			if(is_null($userPreferredDestinations))
			{
				$userPreferredDestinations = array();
			}
			$userDestinations = array_merge($userPreferredDestinations,array($ischecked));
			foreach($destinationCountry as $countryObj)
			{
				if(in_array($countryObj->getId(),array_unique($userDestinations)))
				{
					array_push($countriesSelected,$countryObj->getName());
				}
			}
    ?>
	<li style="margin-bottom: 0px;">
		<div class="destination-content">
		<p style="padding-left:10px;">Preferred study destination : <?php echo implode(',',$countriesSelected); ?></p>
		<p style="padding-left:10px;">Are you also interested in other countries? </p>
		<a style="padding-left:10px;" href="Javascript:void(0);" onclick="toggleCountryDropdown();" style="font-size:11px;">+ add another study destination</a>
		<div style="position:relative" <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
			<div id="countryDropdownLayer" class="customDropLayer customInputs" style="display:none;top:5px;" data-enhance="false">
				<strong class="drop-title">You can select multiple countries here</strong>
				<div class="drop-layer-cont">
					<ul>
						<?php foreach ($destinationCountry as $key => $country) {
							if($country->getId() == 1){ continue; }?>
						<li>

							<div>
								<input type="checkbox" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" onclick="toggleCheckbox(event,this);" <?php if($country->getId() == $ischecked || in_array($country->getId(),$userPreferredDestinations)){ echo 'checked = "checked" disabled = "disabled"';} ?>>
								<label data-enhance = "false" for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" style = "cursor:pointer;"><!-- onclick="toggleCheckbox(event,this);"-->
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
	<li style="display:none;">
	<?php if(isset($fields['fieldOfInterest']) && $fieldOfInterest){ ?>
            <input type="hidden" name='fieldOfInterest' value="<?php echo $fieldOfInterest; ?>" />
	<?php } ?>
    <?php if(isset($fields['abroadDesiredCourse'])){ ?>
            <input type="hidden" name='desiredCourse' id='desiredCourseForResponseBottom' value="<?php echo $desiredCourse; ?>" />
	<?php } ?>
	<?php if(isset($fields['desiredGraduationLevel']) && $desiredGraduationLevel){ ?>
        <input type="hidden" name='desiredGraduationLevel' value="<?php echo $desiredGraduationLevel; ?>" />
    <?php } ?>
    <input type="hidden" id='isPaidBottom' name='isPaid' value="<?php echo $isPaid; ?>" />
	<?php if(isset($fields['abroadSpecialization'])){ ?>
        <input type="hidden" name='abroadSpecialization' id='abroadSpecializationForResponseBottom' value="<?php echo $specialization; ?>" />
	<?php } ?>
	</li>
	</ul>
</section>
<script>var rmcForm = true;
var engageDownloadBrochureWithLogin = 0;
</script>

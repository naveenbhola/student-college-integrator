<?php
//foreach($fields as $fieldId => $field) {
//        error_log($fieldId);
//    switch($fieldId) {
//
//        case 'email':
if(isset($fields['email'])){
            $email = 'Email Id';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['email'])) {
                $email = $abroadShortRegistrationData['email'];
                $disabled = 'disabled="disabled"';
                
                //in case of loggedinuser set contactByConsultant field visible
                $fields['contactByConsultant']->setVisible('Yes');
                $fields['contactByConsultant']->setMandatory('Yes');
                $contactByConsultantFlag = true;
            }
?>
        <div class="signup-fields clearwidth"><ul><li>
            <input type="hidden" id="signup_trackid_email" name="signup_trackid_email" value='<?php echo $trackingPageKeyId; ?>'>

            <div class="flLt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
		<input type="text" name="email" id="email_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" maxlength="125" default="Email Id" value="<?php echo $email; ?>" <?php echo $disabled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this)"/>
		<div>
		    <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
		</div>
            </div>

<?php   }
//            break;
//        case 'mobile':

    if(isset($fields['isdCode'])){
            $ISDCode = 'isdCode.';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['isdCode'])) {
                $mobile = $abroadShortRegistrationData['isdCode'];
                $disabled = 'disabled="disabled"';
            }
            $ISDCodeValues = $fields['isdCode']->getValues();
            ?>
            <div class="flRt" style="width:49%;">
            <div class="flLt signup-txtwidth" style="width:42%;" <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
                <div class="custom-dropdown" style="width:100%;">
                    <select class="universal-select signup-select" required="true" validate="validateSelect" caption="ISD Code" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">                                       
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
            $mobile = 'Mobile No.';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['mobile'])) {
                $mobile = $abroadShortRegistrationData['mobile'];
                $disabled = 'disabled="disabled"';
            }
?>
            <div style="width:56%;" class="flRt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
        <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile; ?>" <?php echo $disabled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
                <div>
                    <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div>
        </div>
            <div class="clearfix"></div>
        </div>

        </li>
        
<?php
    }
//            break;
//        case 'firstName':
    if(isset($fields['firstName'])){
            $firstName = 'First Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['firstName'])) {
                $firstName = $abroadShortRegistrationData['firstName'];
                $disabled = 'disabled="disabled"';
            }
?>
        <li>
            <div class="flLt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('firstName');?>>
                <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" minlength="1" maxlength="50" default="First Name" value="<?php echo $firstName; ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                <div>
                    <div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
            
<?php
    }
//            break;
//        case 'lastName':
    if(isset($fields['lastName'])){
            $lastName = 'Last Name';
            $disabled = '';
            if(!empty($abroadShortRegistrationData['lastName'])) {
                $lastName = $abroadShortRegistrationData['lastName'];
                $disabled = 'disabled="disabled"';
            }
?>
            <div  class="flRt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
                <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" class="universal-text signup-txtfield" minlength="1" maxlength="50" default="Last Name" value="<?php echo $lastName; ?>" <?php echo $disabled; ?> profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
                <div>
                    <div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </li></ul></div>
        
<?php        
    }
//            break;		
//        case 'whenPlanToGo':
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
            
        <div class="signup-fields clearwidth">
            <ul class="customInputs">
                <li>
                    <div class="flLt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                        <div class="custom-dropdown">
                            <select name="whenPlanToGo" class="universal-select signup-select" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                <option value="">When do you plan to start?</option>
                            <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                                <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
			    <?php } ?>
                            </select>
                        </div>
                        <div>
                            <div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                    
<?php
    }
//            break;
//        case 'residenceCity':
    if(isset($fields['residenceCity'])){
?>
            <div class="flRt signup-txtwidth" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
                <div class="custom-dropdown">
                    <select name="residenceCity" class="universal-select signup-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical')); ?>
                    </select>
                </div>
                <div>
            <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
        </div>
            </div>
            <div class="clearfix"></div>
        </li>   
<?php
    }
//            break;
//        case 'destinationCountry':
    if($context != 'downloadEbrochureSA'){
      if(isset($fields['destinationCountry'])){
?>
        <li>
            <div class="flLt signup-txtwidth">
                <div <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
                        <div id="twoStepChooseCourseCountryDropDown" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" style="position: relative; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0);">
                        
									<?php $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); //print_r($desiredCourses);exit;?>
									<div class="custom-dropdown">
												<div class="select-overlap" style="width:300px !important;"></div>
												<select class="universal-select signup-select" id="twoStepCountrySelect">
															<option id="twoStepCountrySelectOption">Which country do you want to study?</option>
												</select>
									</div>          
									<div class="select-opt-layer" style="display:none; width: 435px; z-index: 99;left:0 !important; right:auto; top: 30px !important;" id="twoStepCountryDropdownLayer" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();">
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
																					<p class="font-9"><strong>TOP COUNTRIES</strong></p>
																					<ol>
																								<li>
																											<?php
																											global $studyAbroadPopularCountries;
																											foreach($studyAbroadPopularCountries as $key => $popularCountry){
																											?>
																														<div class="country-flag-cont" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected(this); ">
																																	<div class="flag-main-box">
																																				<p class="clearfix">
																																							<span class="flags flLt <?php echo str_replace(' ', '', strtolower($popularCountry)); ?>">
																																							<!--img src="/public/images/abroadCountryFlags/<?php echo $popularCountry; ?>.gif"-->
																																							</span>
																																							<strong><?php echo $popularCountry; ?></strong>
																																				</p>
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
																														if($liCount%3 == 1) {echo '<li>';}
																								?>	
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
									<div>
												<div style="color: red; display: none;" class="errorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
									</div>
						</div>
            </div>
            <div class="clearfix"></div>
<?php
      }
    }
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
        <div class="flLt">
            <div <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
                <div class="flLt signUp-child-wrap" style="padding-left:0; font-size:12px !important;">
                    <p class="mb8">Have you given any study abroad exam?</p>
                    <div class="columns">
                        <input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="examTaken_yes_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p><strong style="font-size:12px !important;">Yes</strong></p>
                        </label>
                    </div>
                    <div class="columns">
                        <input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="examTaken_no_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p><strong style="font-size:12px !important;">No</strong></p>
                        </label>
                    </div>
					<div class="columns" style="width:135px;">
                        <input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p><strong style="font-size:12px !important;"><?php echo $fields['bookedExamDate']->getLabel(); ?></strong></p>
                        </label>
                    </div>
					<input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>
                    <div class="clearfix"></div>
                    <div>
                        <div class="errorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            
        
<?php
    }
//            break;
//        case 'examsAbroad':
    if(isset($fields['examsAbroad'])){
            $visible = 'No';
            $style = 'display:none;margin-bottom:0;';
            if(!empty($abroadShortRegistrationData['examsAbroad'])) {
                $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
                $visible = 'Yes';
                $style = 'display:block;margin-bottom:0;';
            }
?>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
                
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
                                            <input type="text" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" class="universal-text text-width disable-field" disabled="disabled" maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score" default="Score" value="<?php echo $value; ?>" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
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
                    <div class="clearfix"></div>
		    <div>
			<div id="examsAbroad_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
		    </div>
                
            </div>
            
<?php
    }
//            break;
//        case 'passport':
    if(isset($fields['passport'])){
            $visible = 'No';
            $style = 'display:none;padding-left:0;font-size:12px !important;';
            if(!empty($abroadShortRegistrationData['passport'])) {
                $passport = $abroadShortRegistrationData['passport'];
                if($examTaken == 'no') {
                    $visible = 'Yes';
                    $style = 'display:block;margin-bottom:0;';
                }
            }
?>
            
            <div class="signUp-child-wrap" style="display:none;padding-left:0;font-size:12px !important;" <?php echo $registrationHelper->getBlockCustomAttributes('passport', array('visible' => $visible, 'style' => $style)); ?>>
                    
                        
			    <p class="mb8">Do you have a valid passport?</p>
                            <div class="columns">
                                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                <label for="passport_yes_<?php echo $regFormId; ?>">
                                    <span class="common-sprite"></span>
                                    <p><strong style="font-size:12px !important;">Yes</strong></p>
                                </label>
                            </div>
                            <div class="columns">
                                <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                <label for="passport_no_<?php echo $regFormId; ?>">
                                    <span class="common-sprite"></span>
                                    <p><strong style="font-size:12px !important;">No</strong></p>
                                </label>
                            </div>
                        <div class="clearfix"></div>
			<div>
			    <div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
			</div>
                    
            </div>
            
        </div>
    </div>
            
<?php 
    }
    
    if(isset($fields['abroadDesiredCourse'])){
        echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '' />";
    }
    
//            break;
//        case 'fieldOfInterest':
    if($context != 'downloadEbrochureSA'){
      if(isset($fields['fieldOfInterest'])){
?>  
        <div id='fieldOfInterest_block_parent_<?php echo $regFormId; ?>' class="flRt signup-txtwidth">
            <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
                    <select class="universal-select signup-select" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                        <option value="">Which course do you want to pursue?</option>
                        <optgroup label="Choose a popular course">
						<?php $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                            foreach($desiredCourses as $course) {
                                    if($course['SpecializationId'] == $currentLDBCourseId) {
                                            $checked = 'checked';
                                    } else {
                                            $checked = '';
                                    } ?>
                            <option value="<?=$course['SpecializationId']?>"><?=$course['CourseName']?></option>
                    <?php } ?>
                         </optgroup>
                         <optgroup label="Or Choose a stream">
                        <?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) { ?>
                            <option value="<?php echo $categoryId; ?>"><?php echo $categoryName['name']; ?></option>
                        <?php } ?>
                         </optgroup>
                    </select>
            </div>
            <div>
                <div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
            </div>
            
                
<?php
      }
    }else{
        
    }

//            break;
//        case 'desiredGraduationLevel':
    if($context != 'downloadEbrochureSA'){
      if(isset($fields['desiredGraduationLevel'])){
?>  
            <div style="padding:0; margin-top:8px;" class="flRt signUp-child-wrap">
            <div <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
                <p class="mb4">Choose level of study</p>
                <div class="form-sec" id="twoStepLevelOfStudy" style="margin-bottom: 0">
                    <div class="field-child-wrap">
                    <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                        <div class="columns <?=$desiredGraduationLevelText['CourseName'].'-margin' ?>">
                            <input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
                            <label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
                                <span class="common-sprite"></span>
                                <p><strong style="font-size:12px !important;"><?=$desiredGraduationLevelText['CourseName']?></strong></p>
                            </label>
                        </div>
                    <?php } ?>
                    </div>
                    <div class="clearFix"></div>
                    <div>
                        <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                    </div>
                </div>               
            </div>
                </div>
        
        
<?php
      }
    }
//            break;
//        case 'abroadSpecialization':
    if($context != 'downloadEbrochureSA'){
      if(isset($fields['abroadSpecialization'])){
?>
        <div style="width:100%;" class="flLt">
            <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
                <select name="abroadSpecialization" class="universal-select signup-select" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                    <option>Select specialization (optional)</option>
                </select>
                <div>
                    <div class="errorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        </div>
        </li>
    
        
<?php
      }
    }

    if(isset($fields['contactByConsultant'])){
        if(!$contactByConsultantFlag){
            $contactByConsultant = 'yes'; 
        }
?>
        <li>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('contactByConsultant'); ?>>
                <div class="flLt signUp-child-wrap" style="padding-left:0; font-size:12px !important;">
                    <p class="mb8">Do you want to be contacted by consultants?</p>
                    <div class="columns">
                        <input type="radio" id="contactByConsultant_yes_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="yes" <?php if(!empty($contactByConsultant) && $contactByConsultant == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="contactByConsultant_yes_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p><strong style="font-size:12px !important;">Yes</strong></p>
                        </label>
                    </div>
                    <div class="columns">
                        <input type="radio" id="contactByConsultant_no_<?php echo $regFormId; ?>" class="contactByConsultant_<?php echo $regFormId; ?>" name="contactByConsultant" value="no" <?php if(!empty($contactByConsultant) && $contactByConsultant == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('contactByConsultant'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                        <label for="contactByConsultant_no_<?php echo $regFormId; ?>">
                            <span class="common-sprite"></span>
                            <p><strong style="font-size:12px !important;">No</strong></p>
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <div class="errorMsg" id="contactByConsultant_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </li>
    </ul>
    </div>

<?php } ?>

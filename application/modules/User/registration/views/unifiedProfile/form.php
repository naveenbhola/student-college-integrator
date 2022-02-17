<div class="container">
    <h1>Unified Profile </h1>
    <hr />
    <?php
        $CI = & get_instance();
        $CI->load->library('security');
        $CI->security->setCSRFToken();
    ?>
    <div>
        <form action="/registration/Registration/updateUser" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
            <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
            <h4>Email </h4>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?> >
                <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="register-fields form-control <?php if($isLoggedIn == true) echo 'disabled-field'; ?>" <?php if($isLoggedIn == true) echo 'disabled'; ?> maxlength="125" value="Email Id" default="Email Id" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" <?php if($context == 'registerResponseLPR' || $context == 'default' || $context == 'askQuestionBottom' || $context == 'inlineANA') { ?> onblur="registration_context = '<?php echo $context;?>';shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this) ? shikshaUserRegistration.checkExistingEmail(this) : false; shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } else { ?> onblur="if(typeof(registration_context) != 'undefined' && registration_context == 'MMP') { delete registration_context; }shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" <?php } ?> />
                <div>
                    <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>

            <h4>Mobile </h4>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
                <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="register-fields form-control" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                <div>
                    <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div> 
            </div>

            <h4>First Name </h4>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
                <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="register-fields form-control" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                <div>
                    <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                </div>
                <div class='clearFix'></div>
            </div>

            <h4>Last Name </h4>
            <div <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
                <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="register-fields form-control" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                <div>
                    <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                </div>
                <div class='clearFix'></div>
            </div>


            <?php if($courseGroup == 'domesticUnifiedProfile'){ ?>
                <h4>Field Of Interest </h4>
                <div <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
                    <div>
                        <select name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses();" class="form-control">
                            <option value="">Education Interest</option>
                            <?php foreach($fields['fieldOfInterest']->getValues() as $categoryId => $categoryName) { ?>
                                <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>

                <h4>Desired Course </h4>
                <div <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
                    <div>
                        <select name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" class="form-control">
                            <option value="">Desired Course</option>
                        </select>
                    </div>
                    <div>
                        <div class="regErrorMsg" id="desiredCourse_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
            <?php } ?>
                        <h4>Residence Locality </h4>
                        <div <?php echo $registrationHelper->getBlockCustomAttributes('residenceCityLocality'); ?>>
                            <div>
                                <div class="custom-dropdown">
                                    <select id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this); autoPopulateLocality(this, '<?php echo $formData['widget']; ?>','<?php echo $regFormId; ?>')" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" class="form-control">
                                        <?php $this->load->view('registration/common/dropdowns/residenceLocality'); ?>
                                    </select>
                                </div>
                                <div>
                                    <div class="regErrorMsg" id="residenceCityLocalivty_error_<?php echo $regFormId; ?>"></div>
                                </div>
                            </div>
                        </div>
                        <div <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?>>
                            <div>
                                <div class="custom-dropdown">
                                    <select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?> form-control" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                                        <option value="">Select Area</option>
                                    </select>
                                </div>
                                <div class="clearFix"></div>
                                <div>
                                    <div class="regErrorMsg" id="preferredStudyLocality1_error_<?php echo $regFormId; ?>"></div>
                                </div>
                                <div>
                                    <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
                                </div>
                            </div>
                        </div>


                        <?php  if($courseGroup == 'abroadUnifiedProfile'){
                        if(isset($fields['fieldOfInterest'])){?> 
                            <div class="account-setting-fields flLt" <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
                                <label>Primary course of interest</label>
                                <div class="custom-dropdown">
                                    <select class="form-control" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                                        <option value="">Which course do you want to pursue?</option>
                                        <optgroup label="Choose a popular course">
                                        <?php $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                                            foreach($desiredCourses as $course) {
                                                    if($course['SpecializationId'] == $formData['desiredCourse']) {
                                                            $selected = 'selected="selected"';
                                                    } else {
                                                            $selected = '';
                                                    } ?>
                                            <option value="<?=$course['SpecializationId']?>" <?= $selected;?>><?=$course['CourseName']?></option>
                                    <?php } ?>
                                         </optgroup>
                                         <optgroup label="Or Choose a stream">
                                        <?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) {
                                                    if($categoryId == $formData['fieldOfInterest']) {
                                                            $selected = 'selected="selected"';
                                                    } else {
                                                            $selected = '';
                                                    } 
                                                    ?>
                                            <option value="<?php echo $categoryId; ?>" <?= $selected;?>><?php echo $categoryName['name']; ?></option>
                                        <?php } ?>
                                         </optgroup>
                                    </select>
                               </div>
                                <div><div class="errorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div></div>
                            </div>
                            <?php } ?>
                            
                        <?php if(isset($fields['abroadDesiredCourse'])){
                            echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '' />";
                            }
                                if(isset($fields['desiredGraduationLevel'])){
                                        if($formData['desiredGraduationLevel']!=''){
                                            $visible = 'Yes';
                                           $style = 'display:block;margin-bottom:0;';
                                        }else{
                                            $visible = 'No';
                                            $style = 'display:none;margin-bottom:0;';
                                        }
                            ?>  
                            <div style="padding:0; margin-top:8px;" class="flRt signUp-child-wrap">
                            <div <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel',array('visible' => $visible,'style'=>$style)); ?>>
                                <p class="mb4">Choose level of study</p>
                                <div class="form-sec" id="twoStepLevelOfStudy" style="margin-bottom: 0">
                                    <div class="field-child-wrap">
                                    <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) {
                                        $checked = '';
                                        //_p($desiredGraduationLevelText['CourseName']);
                                        if(strtolower($desiredGraduationLevelText['CourseName']) == strtolower($formData['desiredGraduationLevel'])){
                                                $checked = 'checked="checked"'; 
                                        }
                                        
                                        ?>
                                        <!-- <div class="columns <?=$desiredGraduationLevelText['CourseName'].'-margin' ?>"> -->
                                            <input type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();">
                                            <label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
                                                <span class="common-sprite"></span>
                                                <p><strong style="font-size:12px !important;"><?=$desiredGraduationLevelText['CourseName']?></strong></p>
                                            </label>
                                        <!-- </div> -->
                                    <?php } ?>
                                    </div>
                                    <div class="clearFix"></div>
                                    <div>
                                        <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                                    </div>
                                </div>               
                            </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php } ?>

                            <?php if(isset($fields['abroadSpecialization'])){ ?>
                            <div class="account-setting-fields flRt" id='abroadSpecialization_block_<?php echo $regFormId; ?>' <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
                                <label>Course Specialization</label>
                                <div class="custom-dropdown">
                                    <select name="abroadSpecialization" class="form-control" id="abroadSpecialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                                        <option>Select specialization (optional)</option>
                                    </select>
                               </div>
                                <div>
                                        <div class="errorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                                </div>
                            </div>
                            <?php } ?>

                            <?php        
                                if(isset($fields['whenPlanToGo'])){
                                        if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
                                            //$creationDate = strtotime($abroadShortRegistrationData['creationDate']);
                                            //$creationDate = (int)date('Y', $creationDate);
                                            $creationDate = (int)date('Y', time());
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
                            <div class="account-setting-fields flRt">
                                <label>Planing to study abroad in</label>
                                <div class="custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
                                    <select name="whenPlanToGo" class="form-control" id="whenPlanToGo_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                    <option value="">When do you plan to start?</option>
                                        <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                                        <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
                                        <?php } ?>
                                </select>
                               </div>
                                <div><div class="errorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div></div>
                            </div>
                            <?php } ?>


                            <?php if(isset($fields['passport'])){
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
                                        <div class="account-setting-fields setting-child-wrap flLt" style="padding-left:0px;width: 100%;" <?php echo $registrationHelper->getBlockCustomAttributes('passport', array('visible' => $visible, 'style' => $style)); ?>>
                                        <label style="margin-bottom:8px;">Do you have a passport?</label>
                                           <!-- <div class="columns"> -->
                                            <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                            <label for="passport_yes_<?php echo $regFormId; ?>">
                                                <span class="common-sprite"></span>
                                                <p><strong style="font-size:12px !important;">Yes</strong></p>
                                            </label>
                                        <!-- </div> -->
                                        <!-- <div class="columns"> -->
                                            <input type="radio" name="passport" class="passport_<?php echo $regFormId; ?>" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?>>
                                            <label for="passport_no_<?php echo $regFormId; ?>">
                                                <span class="common-sprite"></span>
                                                <p><strong style="font-size:12px !important;">No</strong></p>
                                            </label>
                                        <!-- </div> -->
                                        <!-- <div class="clearfix"></div> -->
                                        <div>
                                                <div id="passport_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                                        </div>
                                       </div>
                                   
                            <?php  } ?>

                              <h4>budget </h4>
                                <div>
                                    <input type="text" name="budget" class="form-control" placeholder="budget" value="" profanity="1" />
                                </div>

                                 <h4>Please select Education level</h4>
                                 <div>
                                        <select class="form-control" name="sourceOfFunding">
                                            <option value="-1"> select Source</option>
                                            <option value="bank">Bank </option>
                                            <option value="own">Own </option>
                                            <option value="company">company </option>
                                        </select>
                                    </div>

                            <input type="hidden" name="destinationCountry[]" value="3" />
                            <input type="hidden" name="destinationCountry[]" value="8" />
                  <?php  }?>


                        <h4>Work Experience </h4>
                        <div <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
                            <select name="workExperience" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" class="form-control">
                                <option value="" title="Select">Work Experience</option>
                                <?php foreach($fields['workExperience']->getValues() as $workExperienceValue => $workExperienceText) { ?>
                                    <option value='<?php echo $workExperienceValue; ?>'><?php echo htmlentities($workExperienceText); ?></option>
                                <?php } ?>
                            </select>
                            <div>
                                <div class="regErrorMsg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
                            </div>
                        </div>


            <h4>About Me </h4>
            <div>
                <input type="text" name="aboutMe" class="form-control" />
            </div>

            <h4>Bio </h4>
            <div>
                <textarea name="bio" class="form-control" ></textarea>
            </div>

            <h4>Date of Birth </h4>
            <div>
                <input type="text" name="dob" id="dob" class="register-fields form-control" placeholder="Date of Birth(YYYY-MM-DD)" value=""profanity="1" />
            </div>
            
            <h4>Facebook ID </h4>
            <div>
                <input type="text" name="facebookId" id="facebookId" class="register-fields form-control" placeholder="Facebook Id" value=""profanity="1" />
            </div>

            <h4>Twitter ID </h4>
            <div>
                <input type="text" name="twitterId" id="twitterId" class="register-fields form-control" placeholder="Twitter Id" value="" profanity="1" />
            </div>

            <h4>LinkedIn ID </h4>
            <div>
                <input type="text" name="linkedinId" id="linkedinId" class="register-fields form-control" placeholder="Linkedin Id School" value="" profanity="1" />
            </div>

            <h4>Youtube Channel  </h4>
            <div>
                <input type="text" name="youtubeId" id="youtubeId" class="register-fields form-control" placeholder="youtubeId" value="" profanity="1" />
            </div>

            <h4>Personal URL </h4>
            <div>
                <input type="text" name="personalURL" id="personalURL" class="register-fields form-control" placeholder="Personal URL" value="" profanity="1" />
            </div>
            <div>
            <?php //$this->load->view('registration/fields/LDB/variable/unifiedProfile'); ?>
        </div>
            <div id="education-details-div">
                <h3>Education Details </h3>
                <hr />  
                <div id="education_block_0"> 
                    <h5>Please select Education level: </h5>
                    <select class="form-control" onChange="unifiedProfile.getEducationalFields(0, this.value);" name="EducationBackground[]">
                        <option value="-1"> select Education level </option>
                        <option value="xth">X th </option>
                        <option value="xiith">XII th </option>
                        <option value="bachelors">Graduation </option>
                        <option value="masters">Masters </option>
                        <option value="phd">PHD </option>
                    </select>
                    <div id="examFields_0">
                    </div>
            </div>
        </div>
        <div style="margin-top:10px;margin-bottom:15px;">
            <a href="javascript:void(0);" onclick="unifiedProfile.addExamBlock();" >Add More </a>
        </div>

            <div id="workExpDiv">

            <h3>Work Experience </h3>
            <h4>Employer </h4>
            <div>
                <input type="text" name="employer[]" class="register-fields form-control" placeholder="Employer" value="" profanity="1" />
            </div>

            <h4>Designation </h4>
            <div>
                <input type="text" name="designation[]" class="register-fields form-control" placeholder="Designation" value="" profanity="1" />
            </div>

            <h4>Department </h4>
            <div>
                <input type="text" name="department[]" class="register-fields form-control" placeholder="Department" value="" profanity="1" />
            </div>

            <h4>Start Date  </h4>
            <div>
                <input type="text" name="startDateMonth[]" class="register-fields" placeholder="Month(MM)" value="" profanity="1" />
                <input type="text" name="startDateYear[]" class="register-fields" placeholder="Year(YYYY)" value="" profanity="1" />
            </div>

            <h4>End Date </h4>
            <div>
                <input type="text" name="endDateMonth[]" class="register-fields" placeholder="Month(MM)" value="" profanity="1" />
                <input type="text" name="endDateYear[]" class="register-fields" placeholder="Year(YYYY)" value="" profanity="1" />
            </div>

            <h4>Current Company 
                <input type="checkbox" onclick="unifiedProfile.checktheBox(this)" placeholder="Current Job" value="YES" profanity="1" />
                <input type="hidden" name="currentJob[]" class="currentJob" value="NO" />
            </h4>
            <input type="hidden" name="workExp[]" value="1" />
        </div>

      <div style="margin-top:10px;margin-bottom:15px;">
            <a href="javascript:void(0);" onclick="unifiedProfile.addworkExpBlock();" >Add More </a>
        </div>

            <input type="hidden" name="CAT_score" value="100" />
            <input type="hidden" name="CAT_scoreType" value="Percentile" />
            <input type="hidden" name="CMAT_score" value="100" />
            <input type="hidden" name="CMAT_scoreType" value="Score" />
            <input type="hidden" name="exams[]" value="CAT" />
            <input type="hidden" name="exams[]" value="CMAT" />

            
            <div style="margin:10px;">
                <input type="submit" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?php echo $submitText ? $submitText : "Submit"; ?>" class="btn btn-default" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
            </div>
            <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
            <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
            <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'abroadUnifiedProfile' ? 'yes' : 'no'; ?>' />
            <input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
            <input type='hidden' id='referrer' name='referrer' value='<?php echo $customReferer ? $customReferer : "https://www.shiksha.com#homepageregisterbutton"; ?>' />
            <input type='hidden' id='fieldsView' name='fieldsView' value='default' />
            <input type='hidden' id='isMR' name='isMR' value='YES' />
            <input type='hidden' id='isCompareEmail' name='isCompareEmail' value='<?php echo $isCompareEmail; ?>' />
            <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
            <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
            <input type="hidden" id="replyContext_<?php echo $regFormId; ?>" name="replyMsg" value='<?php echo isset($replyContext) ? $replyContext : 'no'; ?>' />
            <input type="hidden" name="isUnifiedProfile" value="yes" />
            <input type="hidden" name="courseGroup" value="<?php echo $courseGroup; ?>" />
        </form>
    </div>
 </div>
<?php $this->load->view('registration/common/jsInitialization'); ?>

<style>
.regErrorMsg{
    color:red;
} 
.errorMsg{
     color:red;
}

</style>

<script type="text/javascript">
var UnifiedProfile = function(){
    var thisFormRegId = '<?php echo $regFormId; ?>';

    var eduBlockSequence = 1;

    var addExamBlock = function(){
        var html = '<hr />  <div id="education_block_'+eduBlockSequence+'"> <h5>Please select Education level: </h5><select class="form-control" onchange="unifiedProfile.getEducationalFields('+eduBlockSequence+', this.value);" name="EducationBackground[]"><option value="-1"> select Education level </option><option value="xth">X th </option><option value="xiith">XII th </option><option value="bachelors">Graduation </option><option value="masters">Masters </option><option value="phd">PHD </option></select><div id="examFields_'+eduBlockSequence+'"></div></div>';
        eduBlockSequence++;
        $j('#education-details-div').append(html);
    };

    var getEducationalFields = function(blockNum, level){
        $j.ajax({
            url:'/registration/Forms/getEducationalFields',
            type: 'POST',
            async:false,
            data:{
                'level':level
            },
            success:function(response){
                $j('#examFields_'+blockNum).html(response);
            }
        });
    };

    var addworkExpBlock = function(){ 
        var html = '<hr /><h4>Employer </h4><div><input type="text" name="employer[]" class="register-fields form-control" placeholder="Employer" value="" profanity="1" /></div><h4>Designation </h4><div><input type="text" name="designation[]" class="register-fields form-control" placeholder="Designation" value="" profanity="1" /></div><h4>Department </h4><div><input type="text" name="department[]" class="register-fields form-control" placeholder="Department" value="" profanity="1" /></div><h4>Start Date  </h4><div><input type="text" name="startDateMonth[]" class="register-fields" placeholder="Month(MM)" value="" profanity="1" /><input type="text" name="startDateYear[]" class="register-fields" placeholder="Year(YYYY)" value="" profanity="1" /></div><h4>End Date </h4><div><input type="text" name="endDateMonth[]" class="register-fields" placeholder="Month(MM)" value="" profanity="1" /><input type="text" name="endDateYear[]" class="register-fields" placeholder="Year(YYYY)" value="" profanity="1" /></div><h4>Current Company <input type="checkbox" onclick="unifiedProfile.checktheBox(this)" placeholder="Current Job" value="YES" profanity="1" /><input type="hidden" name="currentJob[]" class="currentJob" value="NO" /><input type="hidden" name="workExp[]" value="1" /></h4>';
        $j('#workExpDiv').append(html);
    };

    var checktheBox = function(obj){
        if($j(obj).is(":checked")){
           $j(obj).siblings('.currentJob').val('YES');
        }else{
           $j(obj).siblings('.currentJob').val('NO');
        }
    };

    return{
        addExamBlock:addExamBlock,
        getEducationalFields:getEducationalFields,
        addworkExpBlock:addworkExpBlock,
        checktheBox:checktheBox
    };

};


var unifiedProfile = new UnifiedProfile();
var registration_context = '';
</script>
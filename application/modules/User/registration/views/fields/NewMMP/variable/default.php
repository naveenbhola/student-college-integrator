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
                    <div class='frmoutBx'>
                        <select class="inptBx" name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?> onblur="registrationCustomLogic['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($values as $key => $value) { ?>
                                <option value="<?php echo $key; ?>">
                                    <?php echo $value; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'checkbox':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <div class='frmoutBx' style="padding-top:5px;">
                        <div>
                        <?php foreach($values as $value) { ?>
                            <input type="checkbox" name="<?php echo $id; ?>[]" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'multiple':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <div class='frmoutBx' style="margin-top: 7px;">
                        <div>
                        <?php foreach($values as $value) { ?>
                            <input type="radio" name="<?php echo $id; ?>" class="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>  value="<?php echo $value; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);"> <?php echo $value; ?> <br />
                        <?php } ?>
                        </div>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'paragraph':
        ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes($id); ?>>
                    <label><?php echo $label; ?><span><?php echo $registrationHelper->markMandatory($id); ?></span></label>
                    <div class='frmoutBx'>
                        <textarea class="register-fields2" style="width:225px; height:70px; color:#000;" name="<?php echo $id; ?>" id="<?php echo $id; ?>_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes($id); ?>></textarea>
                        <div>
                            <div class="errorMsg" id="<?php echo $id; ?>_error_<?php echo $regFormId; ?>"></div>
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
            case 'exams':
                if($fields['exams']->getValues()) {
        ?>
                    <li <?php echo $registrationHelper->getBlockCustomAttributes('exams'); ?>>
                        <label>Exams Taken/Plan to Take<span><?php echo $registrationHelper->markMandatory('exams'); ?></span></label>
                        <div class='frmoutBx frmCstmDrpp'>
                            <div class="selectStyleDiv" id="examLayerButton_<?php echo $regFormId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].showExamLayer(event);$j('#formScroll').tinyscrollbar_update(); $j('#exams_block_<?php echo $regFormId;?>').css({'max-height':'40px'});" >
                                <span class="selectStyleArrow slctBxDrpDwn"></span>
                                <div id="examContainer_<?php echo $regFormId; ?>" default="Select">Select</div>
                            </div>
                        
                            <?php $this->load->view('registration/common/layers/newMMPExams', array('formData'=>$formData, 'mmpData'=>$mmpData)); ?>
                            
                            <div>
                                <div id="exams_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                            </div>
                        </div>
                    </li>
        <?php
                }
                break;
            case 'graduationStatus':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationStatus'); ?>>
                    <label>Graduation Status<span><?php echo $registrationHelper->markMandatory('graduationStatus'); ?></span></label>
                    <div class="frmoutBx" style="margin-top: 7px;">
                        <div>
                            <?php foreach($fields['graduationStatus']->getValues() as $graduationStatusValue) { ?>
                                <input type="radio" name="graduationStatus" class="graduationStatus_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStatus'); ?> value="<?php echo $graduationStatusValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)"> <?php echo $graduationStatusValue; ?> &nbsp;
                            <?php } ?>
                        </div>
                        <div>
                            <div class="errorMsg" id="graduationStatus_error_<?php echo $regFormId; ?>"></div>
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
                    <div class="frmoutBx" style="width:150px;">
                        <select class="inptBx" style="width:150px;" name="graduationDetails" id="graduationDetails_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationDetails'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); " onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['graduationDetails']->getValues() as $graduationCourse) { ?>
                                <option value='<?php echo $graduationCourse; ?>'><?php echo $graduationCourse; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks','float:left; width:70px;'); ?>>
                    <div class="frmoutBx" style="width:100%;">
                        <select class="inptBx" style="width:100%;" name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Marks</option>
                            <?php foreach($fields['graduationMarks']->getValues() as $graduationMarks) { ?>
                                <option value='<?php echo $graduationMarks; ?>'><?php echo $graduationMarks; ?>%</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div style="margin-left: 190px;">
                    <div>
                        <div class="errorMsg" id="graduationDetails_error_<?php echo $regFormId; ?>"></div>
                    </div>
                    <div>
                        <div class="errorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </div>
                </li>
        <?php
                break;
            case 'graduationMarks123':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
                    <label>Graduation Marks<span><?php echo $registrationHelper->markMandatory('graduationMarks'); ?></span></label>
                    <div class="frmoutBx">
                        <select class="inptBx" name="graduationMarks" id="graduationMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks'); ?>>
                            <option value="">Select</option>
                            <?php foreach($fields['graduationMarks']->getValues() as $graduationMarks) { ?>
                                <option value='<?php echo $graduationMarks; ?>'><?php echo $graduationMarks; ?>%</option>
                            <?php } ?>
                        </select>
                        <div>
                            <div class="errorMsg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'graduationCompletionYear':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?>>
                    <label>Graduation Completion Year<span><?php echo $registrationHelper->markMandatory('graduationCompletionYear'); ?></span></label>
                    <div class="frmoutBx">
                        <select class="inptBx" style="width:150px; padding:5px 5px;" name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                            <option value="">Select</option>
                            <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
                                <option <?php if($formData['graduationCompletionYear'][0] == $graduationCompletionYear) { echo 'selected=selected'; } ?> value='<?php echo $graduationCompletionYear; ?>'><?php echo $graduationCompletionYear; ?></option>
                            <?php } ?>	
                        </select>
                        <div>
                            <div class="errorMsg" id="graduationCompletionYear_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div>
                            <div class="errorMsg" id="graduationCompletionDate_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'xiiStream':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
                    <label>Std. XII Stream<span><?php echo $registrationHelper->markMandatory('xiiStream'); ?></span></label>
                    <div class="frmoutBx" style="margin-top: 7px;">
                        <div>
                        <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
                            <input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" /> <?php echo $xiiStreamValue; ?> &nbsp;
                        <?php } ?>
                        </div>
                        <div>
                            <div id= "xiiStream_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'xiiYear':
        ?>                        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?>>
                
                    <label>XII Completion Year<span><?php echo $registrationHelper->markMandatory('xiiYear'); ?></span></label>
                    <div class="frmoutBx">
                        <select class="inptBx" style="width:100%; padding:5px 5px; " name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <option value="">Select</option>
                        <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
                            <option <?php if($formData['xiiYear'][0] == $xiiYear) { echo 'selected=selected'; } ?> value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
                        <?php } ?>	
                        </select>
                        <div>
                            <div id= "xiiYear_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
                        </div>
                    </div>
                </li>
        <?php
                break;
            case 'xiiMarks123':
        ?>        
                <li <?php echo $registrationHelper->getBlockCustomAttributes('xiiMarks'); ?>>
                    <label>XII Marks<span><?php echo $registrationHelper->markMandatory('xiiMarks'); ?></span></label>
                    <div class="frmoutBx">
                        <select class="inptBx" name="xiiMarks" id="xiiMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiMarks'); ?>>
                        <option value="">Select</option>
                        <?php foreach($fields['xiiMarks']->getValues() as $xiiMarks) { ?>
                            <option value='<?php echo $xiiMarks; ?>'><?php echo $xiiMarks; ?></option>
                        <?php } ?>	
                        </select>
                        <div>
                            <div id= "xiiMarks_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
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
            <div class="frmoutBx">
                <select style="padding:5px 5px;" class="inptBx" id="residenceCityLocality_<?php echo $regFormId; ?>" name="residenceCityLocality" prefNum='1' <?php echo $registrationHelper->getFieldCustomAttributes('residenceCityLocality'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateLocalitiesNew(this); autoPopulateLocality(this, '<?php echo $formData['widget']; ?>')" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('defaultOptionText' => 'Select','residenceCityLocality'=>$residenceCityLocality)); ?>
                </select>
                <div>
                    <div class="errorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
                </div>
            </div>
        </li>
        
        <li <?php echo $registrationHelper->getBlockCustomAttributes('residenceLocality','display:none;'); ?>>
            <label>Residence Locality<span><?php echo $registrationHelper->markMandatory('residenceLocality'); ?></span></label>
            <div class="frmoutBx">
                <select style="padding:5px 5px;" class="inptBx" id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" class="residenceLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceLocality'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <option value="">Select</option>
                </select>
                <div>
                    <div class="errorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
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
                    <div class="frmoutBx">
                        <select style="padding:5px 5px;" class="inptBx" name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
                        <?php $this->load->view('registration/common/dropdowns/residenceCity', array('isNational' => true,'formData'=>$formData,'residenceCity'=>$residenceCity)); ?>
                        </select>
                        <div>
                            <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </div>
                </li>
                <?php
                break;
            case 'desiredGraduationLevel':
        ?>  
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
                    <label>Desired Graduation Level<span><?php echo $registrationHelper->markMandatory('desiredGraduationLevel'); ?></span></label>
                    <div class="frmoutBx" style="margin-top: 7px;">
                        <div>
                        <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                            <input type="radio" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelValue; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadExams(this)"> <?php echo $desiredGraduationLevelText; ?> &nbsp;&nbsp;
                        <?php } ?>
                        </div>
                        <div>
                            <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="errorMsg"></div>
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
            <div class="frmoutBx">
                <select class="inptBx" style="width:100%; padding:5px 5px;" name="specialization" id="specialization_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('specialization'); ?>>
                    <option value="">Select</option>
                    <?php foreach($specializations as $specializationValue => $specializationText) { ?>
                    <option <?php if($specialization == $specializationValue) { echo 'selected="selected"'; } ?> value='<?php echo $specializationValue; ?>'><?php echo $specializationText; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <div class="errorMsg" id="specialization_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>
        <?php
                }

            break;

            case 'desiredCourse';

                $desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpFormId));
                $numDesiredCourses = 0;
                foreach($desiredCourses as $desiredCourseGroup => $coursesInGroup) {
                    $numDesiredCourses += count($coursesInGroup);
                }
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('desiredCourse'); ?>>
                        <label>Desired Course<span>*</span></label>
                        <?php
                                if($numDesiredCourses == 1) {
                                        foreach($desiredCourses as $groupName => $courses) {
                                                if(count($courses) > 0) {
                                                        foreach($courses as $courseId => $courseName) {
                                                                echo "<label style='padding:10px 5px;'>$courseName</label>";
                                                        }
                                                }
                                        }
                                    $selectedDesiredCourse = $formData['desiredCourse'][0];
                                } else {
                                    $selectedDesiredCourse = $desiredCourse;
                                }
                        ?>
                        <div class="frmoutBx">
                                <select class="inptBx" <?php if($numDesiredCourses == 1) { echo "style='display:none;'"; } else { echo "style='padding:5px 5px;'"; } ?> name="desiredCourse" id="desiredCourse_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('desiredCourse'); ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse(); updateTinyScrollBar(isRequiredTinyUpd);">
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

            case 'email';

            ?>

                <li <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
                    <label>Email<span><?php echo $registrationHelper->markMandatory('email'); ?></span></label>
                    <div class="frmoutBx">
                            <input type="text" class="inptBx" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" maxlength="125" tip="email_idM" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> <?php if($formData['userId']) echo "readonly='readonly'; style='background:#eee;'"; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                            <div>
                                    <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                            </div>
                    </div>
                </li>

            <?php
            break;

            case 'isdCode';
                $ISDCodeValues = $fields['isdCode']->getValues();  
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?> >
                        <label>Country Code<span><?php echo $registrationHelper->markMandatory('isdCode'); ?></span></label>
                        <div class="frmoutBx">
                                <select class="inptBx" name="isdCode" id="isdCode_<?php echo $regFormId; ?>"  <?php if($mmpData['page_type'] != 'abroadpage') { ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" <?php } else { ?> class="universal-select" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" <?php } ?> regFieldId="isdCode" class="register-fields" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
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
                    <div class="frmoutBx">
                            <input type="text" class="inptBx" name="mobile" id="mobile_<?php echo $regFormId; ?>" maxlength="10" minlength="10" tip="mobile_numM" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                            <?php  if(!isset($fields['isdCode'])) { ?>
                            <input type="hidden" id="isdCode_<?php echo $regFormId; ?>" name="isdCode" value="91-2" />
                            <?php } ?>
                            <div>
                                    <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                            </div>
                    </div>
                </li>
            <?php
            break;

            case 'firstName';
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('firstName'); ?>>
                    <label>First Name<span><?php echo $registrationHelper->markMandatory('firstName'); ?></span></label>
                    <div class="frmoutBx">
                            <input meta="first name" type="text" class="inptBx" name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                            <div>
                                    <div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                            </div>
                    </div>
                </li>
            <?php
            break;

            case 'lastName';
            ?>
                <li <?php echo $registrationHelper->getBlockCustomAttributes('lastName'); ?>>
                    <label>Last Name<span><?php echo $registrationHelper->markMandatory('lastName'); ?></span></label>
                    <div class="frmoutBx">
                            <input meta="last name" type="text" class="inptBx" name="lastName" id="lastName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" tip="displayname_id" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)" />
                            <div>
                                    <div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
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

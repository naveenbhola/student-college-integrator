<?php
                //_p($formData);

   $action = '/registration/Registration/updateUser';
?>
<form action="<?php echo $action; ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<div class="edit_data">
   <div class="pic_edit">
        <!--div class="pic_round"></div-->
        <?php 
        $label2 = '';
        if(empty($abroadShortRegistrationData['avtarimageurl'])){
          $label = 'Add Photo';
          $class = 'addPhoto';
        ?>
          <p class="pic" style="background-image: url('/public/images/studyAbroadCounsellorPage/profileDefaultNew1_m.jpg')"></p>
        <?php 
        }else{
          $label = 'Change';
          $label2 = 'Remove';
        ?>
          <p class="pic" style="background-image: url('<?php echo MEDIAHOSTURL.$abroadShortRegistrationData['avtarimageurl']; ?>')"></p>
        <?php 
        }
        if(isMobileRequest()){
        ?>
          <p class="change_pic"><a class="changeMyPhoto <?php echo $class;?>" data-rel="dialog" data-enhance="false" data-transition="slide" id="changeMyPhoto" href="#imgUploaderPage"><?php echo $label;?></a>
          <?php 
          if(!empty($label2)){
          ?>
          <a id="removeMyPhoto" class="removeMyPhoto" href="javascript:;"><?php echo $label2; ?></a>
          <?php 
          }
          ?>
          </p>
        <?php 
        }else{
        ?>
          <p class="change_pic"><a class="changeMyPhoto <?php echo $class;?>" id="changeMyPhoto" href="javascript:void(0);"><?php echo $label;?></a>
          <?php 
          if(!empty($label2)){
          ?>
          <a id="removeMyPhoto" class="removeMyPhoto" href="javascript:;"><?php echo $label2; ?></a>
          <?php 
          }
          ?>
          </p>
        <?php 
        }
        ?>
        <!-- <input type="file" accept="image/*" capture="camera"> -->
   </div>
   <div class="forms_data">
     <form class="edit-usrform"  method="post">
      <div class="general_info">
       <div class="list_forms auto_clear">
         <div class="left_data" <?php echo $registrationHelper->getBlockCustomAttributes('firstName');?>>
           <label>First Name</label>
            <?php
               $firstName = '';
               if(!empty($abroadShortRegistrationData['firstName'])) {
                  $firstName = $abroadShortRegistrationData['firstName'];
               }
            ?>
            <input type="text" profanity=1 name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" value="<?php echo htmlentities($firstName); ?>" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" placeholder="First Name">
            <div style="display:none;">
               <div class="errorMsg error-msg" id="firstName_error_<?php echo $regFormId; ?>"></div>
            </div>
         </div>
         <div class="right_data" <?php echo $registrationHelper->getBlockCustomAttributes('lastName');?>>
            <label>Last Name</label>
            <?php
               $lastName = '';
               if(!empty($abroadShortRegistrationData['lastName'])) {
                  $lastName = $abroadShortRegistrationData['lastName'];
               }
            ?>
            <input type="text" profanity=1 name="lastName" id="lastName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" value="<?php echo htmlentities($lastName); ?>" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" placeholder="Last Name">
            <div style="display:none;">
               <div class="errorMsg error-msg" id="lastName_error_<?php echo $regFormId; ?>"></div>
            </div>
         </div>
      </div>
      <div class="list_forms auto_clear">
         <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('email'); ?>>
            <?php
               if(!empty($abroadShortRegistrationData['email'])) {
                  $email = $abroadShortRegistrationData['email'];
               }
            ?>
           <label>Email ID <span class="onlyvis"> Only visible to you</span></label>
           <input type="text" profanity=1 name="email" id="email_<?php echo $regFormId; ?>" maxlength="125" placeholder="Email ID" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" value="<?php echo $email; ?>" disabled="disabled">
           <div style="display:none;">
               <div class="errorMsg error-msg" id="email_error_<?php echo $regFormId; ?>"></div>
           </div>
         </div>
      </div>
      <div class="list_forms auto_clear">
         <div class="fluid_label"  <?php echo $registrationHelper->getBlockCustomAttributes('password'); ?>>
            <label>Password<a class="change_p" href="javascript:void(0);">Change Password</a></label>
            <input type="password" name="password" id = "password_<?php echo $regFormId; ?>" value="" placeholder="*******" disabled="disabled" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" <?php echo $registrationHelper->getFieldCustomAttributes('password'); ?> maxlength="25">
            <a class="show_p" href="javascript:void(0);">Show</a>
            <div style="display:none;">
               <div class="errorMsg error-msg" id="password_error_<?php echo $regFormId; ?>"></div>
            </div>
         </div>
      </div>
      <div class="list_forms auto_clear">
         <?php 
            global $studyAbroadPopularCountries;
            $destinationCountries = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
            $courseCountryIds = $userPreferredDestinations;
            $userFirstCountry = $userPreferredDestinations[0];
            $courseCountryName = "Choose country to study";
            if(count($userPreferredDestinations) > 0){
                $courseCountryName .= ' ('.count($userPreferredDestinations).' selected)';
            }
            
         ?>
         <div class="fluid_label">
            <label>Countries aspiring for</label>
            <div id="countryChooser" style="position:relative;">
               <div class="drop-overlay" ></div>
               <select class="" name="">
                   <option value=""><?php echo $courseCountryName; ?></option>
               </select>
               <i class="drop_ico"></i>
            </div>
            <div style="position:relative" <?php echo $registrationHelper->getBlockCustomAttributes('destinationCountry'); ?>>
               <div id="countryDropdownLayer" class="customDropLayer customInputs" style="display:none;top:5px;" data-enhance="false">
                  <strong class="drop-title">You can select multiple countries here</strong>
                  <div class="drop-layer-cont">
                     <ul>
                        <?php foreach ($destinationCountries as $key => $country) {
                           if($country->getId() == 1){ continue; }?>
                        <li>
                           
                           <div>
                              <input type="checkbox" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" <?php if($country->getId() == $ischecked || in_array($country->getId(),$courseCountryIds)){ echo 'checked = "checked"';} ?>>
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
         </div>
      </div>
      <?php
         if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
             $creationDate = (int)date('Y', time());
             $whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
             $whenPlanToGo = (int)date('Y', $whenPlanToGo);
             if($creationDate == $whenPlanToGo) {
                 $whenPlanToGo = 'thisYear';
             }else if($creationDate + 1 == $whenPlanToGo) {
                 $whenPlanToGo = 'in1Year';
             }else {
                 $whenPlanToGo = 'later';
             }
         }
      ?>
      <div class="list_forms auto_clear">
        <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('whenPlanToGo'); ?>>
         <label>When do you plan to start?</label>
                        
          <div class="auto_clear">
         <?php $planToGoCount = 0;
               foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
            <div class="flLt planning-col">
               <input type="radio" id="year<?php echo ($planToGoCount); ?>" name="year-option" <?php if($whenPlanToGo == $plannedToGoValue){echo 'checked';} ?> value="<?php echo $plannedToGoValue; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?>>
               <label for="year<?php echo ($planToGoCount); ?>">
                  <span class="sprite"></span>
                  <strong><?php echo $plannedToGoText; ?></strong>
               </label>
            </div>
            <?php $planToGoCount++; } ?>
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
        </div>
       </div>
       <div class="list_forms auto_clear" <?php echo $registrationHelper->getBlockCustomAttributes('desiredGraduationLevel'); ?>>
         <div class="fluid_label" >
           <label>Level of study</label>
            <div class="auto_clear">
               <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
               <div class="flLt planning-col">
                  <input type="radio" class="desiredGraduationLevel_<?php echo $regFormId; ?>" id="<?php echo $desiredGraduationLevelText['CourseName'].$regFormId; ?>" name="desiredGraduationLevel" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" <?php if(!empty($currentLevel) && $currentLevel == $desiredGraduationLevelText['CourseName']) echo 'checked'; ?>>
                  <label for="<?php echo $desiredGraduationLevelText['CourseName'].$regFormId; ?>">
                     <span class="sprite"></span>
                     <strong><?php echo $desiredGraduationLevelText['CourseName']; ?></strong>
                  </label>
               </div>
               <?php } ?>
            </div>
            <div style="display:none;">
               <div class="errorMsg error-msg clearfix" id="desiredGraduationLevel_error_<?php echo $regFormId; ?>"></div>
            </div>
         </div>
       </div>
       <?php
         if($preferredCourse >0){
            $display = '';
         }else{
            $display ='style="display:none;"';
         }
       ?>
       <div class="list_forms auto_clear" <?php echo $display; ?> >
         <?php 
            $desiredCourses = $fields['abroadDesiredCourse']->getValues();
            $desiredCoursesKeys = array();
            foreach ($desiredCourses as $key => $value) {
                $desiredCoursesKeys[] = $value['SpecializationId'];
            }
            $fieldOfInterest = $fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
            $display = '';
            $vaildSAPreferredCourse = 0;
            if(!empty($preferredCourse)){
                $vaildSAPreferredCourse = 1;
                if(in_array($preferredCourse, $desiredCoursesKeys)){
                    $inputArray = array(
                        'type' => 'desiredCourse',
                        'ldbCourseId' => $preferredCourse
                    );
                    $preferredSpecializations = Modules::run("categoryList/AbroadCategoryList/getSubCatsForCourseCountryLayer",$inputArray);
                    $desiredCourseId = $preferredCourse;
               }else{
                    $inputArray = array(
                        'type'          => 'courseLevel',
                        'parentCatId'   => $formData['fieldOfInterest'],
                        'courseLevel'   => $currentLevel
                    );
                    $preferredSpecializations = Modules::run("categoryList/AbroadCategoryList/getSubCatsForSARegisteration",$inputArray);
               }
               unset($inputArray);
            }
         ?>
         <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
            <input type="hidden" id="abroadDesiredCourseHidden" name="desiredCourse" visible="Yes" value="<?php echo $desiredCourseId; ?>">
            <label>Course Interested in</label>
            <select class="universal-select" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> >
            <option value="">Select course</option>
            <?php
               global $studyAbroadPopularCourseToLevelMapping;
               foreach($desiredCourses as $course) {
                  $lvl = '';
                  if(!is_null($studyAbroadPopularCourseToLevelMapping[$course['SpecializationId']]))
                  {
                     $lvl = $studyAbroadPopularCourseToLevelMapping[$course['SpecializationId']];
                  }
                  if($currentLevel == 'PhD'){
                      continue;
                  }
            ?>
                <option value="<?php echo $course['SpecializationId']; ?>" <?php echo ($currentLevel != '' && $lvl != $currentLevel ? 'style="display:none"':''); ?> datalvl="<?php echo $lvl; ?>" <?php echo ($preferredCourse == $course['SpecializationId'])?'selected':''?>><?php echo $course['CourseName']; ?></option>
            <?php } 
               foreach($fieldOfInterest as $categoryId => $categoryName) { ?>
               <option value="<?php echo $categoryId; ?>" <?php echo ($formData['fieldOfInterest'] == $categoryId)?'selected':'';?> ><?php echo $categoryName['name']; ?></option>
            <?php } ?>
            </select>
            <i class="drop_ico"></i>
            <div style="display:none;">
               <div class="errorMsg error-msg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
            </div>
         </div>
       </div>
      <?php
         $specializations = array();
         foreach ($preferredSpecializations as $preferredSpec) {
             $specializations[$preferredSpec['sub_category_id']] = $preferredSpec['sub_category_name'];
         }
         unset($preferredSpecializations);
         if($preferredCourse >0){
            $display = '';
         }else{
            $display ='style="display:none;"';
         }
      ?>
      <div class="list_forms auto_clear" <?php echo $display; ?>>
         <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('abroadSpecialization'); ?>>
           <label>Specialization</label>
            <select id="abroadSpecialization_<?php echo $regFormId; ?>" name="abroadSpecialization" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
               <?php if(!empty($specializations) || $preferredSpecialization == 0){ ?>
                  <option value='' <?php echo ($preferredSpecialization == 0)?'selected':'';?>>All specializations</option>
               <?php }
               foreach ($specializations as $specializationId => $specializationName) {?>
                  <option value="<?php echo $specializationId?>" <?php echo ($specializationId == $preferredSpecialization)?'selected':'';?>><?php echo $specializationName?></option>
               <?php } ?>
            </select>
            <i class="drop_ico"></i>
         </div>
       </div>
       <div class="list_forms auto_clear">
         <?php
            $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
            $examsAbroadMasterList = $fields['examsAbroad']->getValues();
            $abroadExamNameList = array_map(function($a){ return $a['name'];},$examsAbroadMasterList);
            $examAbroadWithScoreGreterThanZero = array();
            foreach($examsAbroad as $examName => $examScore)
            {
                  if(!in_array($examName,$abroadExamNameList))
                  {
                     unset($examsAbroad[$examName]);
                    }
                  elseif($examScore !='' && $examScore >=0)
                  {
                     if($examName !='IELTS'){
                        $examAbroadWithScoreGreterThanZero[$examName]=(int)$examScore;
                     }else{
                        $examAbroadWithScoreGreterThanZero[$examName]= $examScore;			
                     }
                  }	
            }
            //because now we consider only exam which have score greater than zero
            $examsAbroad = $examAbroadWithScoreGreterThanZero;
            if(!empty($abroadShortRegistrationData['examsAbroad']) && count($examsAbroad)>0) {
               $examTaken = 'yes';
               $abroadExamFlag = 1;
            }
            else if(!empty($abroadShortRegistrationData['passport']) && $abroadShortRegistrationData['bookedExamDate'] !=1) {
               $examTaken = 'no';
               $abroadExamFlag = 0;
            }else if($abroadShortRegistrationData['bookedExamDate']==1){
               $examTaken = 'bookedExamDate';
               $abroadExamFlag = -1;
            }
         ?>
         <div class="fluid_label">
           <label>Have you given any study abroad exams?</label>
            <div class="auto_clear" <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
               <div class="flLt planning-col">
                  <input type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);showExamList();">
                  <label for="examTaken_yes_<?php echo $regFormId; ?>">
                      <span class="sprite"></span>
                      <strong>Yes</strong>
                  </label>
               </div>
               <div class="flLt planning-col">
                  <input type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);hideExamList();">
                  <label for="examTaken_no_<?php echo $regFormId; ?>">
                      <span class="sprite"></span>
                      <strong>No</strong>
                  </label>
               </div>
               <div class="flLt planning-col">
                  <input type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);hideExamList();">
                  <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
                      <span class="sprite"></span>
                      <strong>Booked Exam Date</strong>
                  </label>
                  <input name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo (!empty($examTaken) && $examTaken == 'bookedExamDate'? 1:''); ?>" type="hidden">
               </div>
            </div>
         </div>
      </div>
      <?php 
         if($examTaken == 'no'){
             $display = '';
         }else{
             $display = 'style="display:none;"';
         }
      ?>
       <div class="list_forms auto_clear passportSection" <?php echo $display; ?>>
         <div class="fluid_label">
           <label>Do you have a passport?</label>
            <div class="auto_clear" <?php echo $registrationHelper->getBlockCustomAttributes('examTaken'); ?>>
               <div class="flLt planning-col">
                  <input type="radio" id="passport_yes_<?php echo $regFormId; ?>" class="passport_<?php echo $regFormId; ?>" name="passport" value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?> >
                  <label for="passport_yes_<?php echo $regFormId; ?>">
                     <span class="sprite"></span>
                     <strong>Yes</strong>
                  </label>
               </div>
               <div class="flLt planning-col">
                  <input type="radio" type="radio" id="passport_no_<?php echo $regFormId; ?>" class="passport_<?php echo $regFormId; ?>" name="passport" value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?> >
                  <label for="passport_no_<?php echo $regFormId; ?>">
                     <span class="sprite"></span>
                     <strong>No</strong>
                  </label>
               </div>
            </div>
         </div>
       </div>
       <?php
         if(!empty($abroadShortRegistrationData['examsAbroad'])) {
            $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
            $visible = 'Yes';
            $style = 'style="display:block;"';
         }else{
            $visible = 'No';
            $style = 'style="display:none;"';            
         }
       ?>
       <div class="list_forms auto_clear" id="examScoreSection" <?php echo $style; ?>>
             <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('examsAbroad', '', array('visible' => $visible, 'style' => $style)); ?>>
               <label>Exams</label>
               <div class="auto_clear">
               <?php
                  global $examGrades;
                  global $examFloat;
                  $display = false;
                  
                  $examsAbroadMasterList = $fields['examsAbroad']->getValues();
                  $count = 0;
                  foreach($examsAbroadMasterList as $examId => $exam)
                  {
                     $count++;
                     $checked = '';
                     $value = 'Score';
                     $labelFor = 'exam_'.$exam['name'].'_'.$regFormId;
                     if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']]))
                     {
                        $examsAvailable = true;
                        $checked = 'checked = "checked"';
                        if(isset($examGrades[$exam['name']])) {
                             $value = $examGrades[$exam['name']][(int)$examsAbroad[$exam['name']]];
                        }else {
                           $value = $examsAbroad[$exam['name']];
                           if($examFloat[$exam['name']] !== TRUE) {
                              $value = (int)$value;
                           }
                        }
                        $labelFor = '';
                     }
               ?>
                 <div class="exams_split">
                   <div class="em_ck">
                     <input type="checkbox" name="exams[]" class="examsAbroad_<?php echo $regFormId; ?>"  id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"  value="<?php echo $exam['name']; ?>" <?php echo $checked; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].enableAbroadExamScore(this);" onclick="toggleExamScore(this);">
                     <label for="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>"><?php echo $exam['name']; ?></label>
                   </div>
                  <?php
                  // following variable returns true if input textbox is to be disabled
						$disabledCheckVal = ($value==='Score' ||		// if corresponding checkbox is unchecked, it should be disabled
                                    ($value!=='Score' && $exam['name']==='TOEFL' && $value === "" && $value != "0") || 	// TOEFL can have 0 as a valid score so no worry
                                    ($value!=='Score' && !empty($value) && $value === '0.0')		// if it's checked & score is invalid, enable; so that it can be validated
                                   );
                  ?>
                  <input type="text" profanity=1  id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" <?php echo ($disabledCheckVal?'disabled="disabled"':''); ?> maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score" default="Score" value="<?php echo $value; ?>" regfieldid="<?php echo $exam['name']; ?>_score" name="<?php echo $exam['name']; ?>_score" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);">
                  <div style="display:none;">
                     <div class="errorMsg error-msg" id="<?php echo $exam['name']; ?>_score_error_<?php echo $regFormId; ?>"></div>
                  </div>
                 </div>
                 <?php } ?>
                  <div style="display:none;">
                     <div class="errorMsg error-msg" id="examsAbroad_error_<?php echo $regFormId; ?>"></div>
                  </div>
               </div>
            </div>
         </div>
     </div>
       <?php
         if(is_null($currentLevel) || $currentLevel == '') { $display = 'style="display:none;"'; }
       ?>
       <div class="next_info" <?php echo $display; ?>>
         <script>
         var choices = ['<?php echo implode("','", $fields['currentSchool']->getValues());?>'];
         </script>
         <?php
               if($currentLevel == 'Bachelors')
               {
                  $MastersEduStyle = 'style="display:none;"';
                  $BachelorsEduStyle = '';
                  $bachMandatory = 'mandatory="1"';
               } else {
                  $MastersEduStyle = '';
                  $masterMandatory = 'mandatory="1"';
                  $BachelorsEduStyle = 'style="display:none;"';
               }
            ?>
         <p class="next_title">Educational Details</p>
         <div class="list_forms auto_clear edu_forms BachelorsEdu" <?php echo $BachelorsEduStyle; ?>>
            <?php
            if(!empty($educationDetails['currentSchoolName'])){
               $currentSchool = $educationDetails['currentSchoolName'];
            } ?>
            <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('currentSchool'); ?>>
               <label>Current School Name</label>
               <input type="text" profanity=1 name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" minlength="1" maxlength="100" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool').' '.$bachMandatory; ?> value="<?php echo $currentSchool;?>" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="currentSchool_error_<?php echo $regFormId; ?>"></div>
               </div>
            </div>
         </div>
         <div class="list_forms auto_clear edu_forms BachelorsEdu" <?php echo $BachelorsEduStyle; ?>>
            <?php
               $currentClassValues = $fields['currentClass']->getValues();
               if(!empty($educationDetails['currentClass'])){
                    $currentClass = $educationDetails['currentClass'];
               }
            ?>
           <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('currentClass'); ?>>
             <label>Current Class</label>
               <select name="currentClass" class="drpdwn" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass')." ".$bachMandatory; ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                  <option value="">Select current class</option>
                  <?php foreach($currentClassValues as $key => $val) { ?>
                      <option value="<?php echo $key; ?>" <?php echo ($key == $currentClass)?'selected':''?> ><?php echo $val; ?></option>
                  <?php } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="currentClass_error_<?php echo $regFormId; ?>"></div>
               </div>
           </div>
         </div>
         <div class="list_forms auto_clear edu_forms BachelorsEdu" <?php echo $BachelorsEduStyle; ?>>
         <?php
            $tenthBoardValues = $fields['tenthBoard']->getValues();
            $tenthBoard ='';
            if(!empty($educationDetails['tenthBoard'])){
                $tenthBoard = $educationDetails['tenthBoard'];
            }
         ?>
            <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('tenthBoard'); ?>>
               <label>Class Xth Board</label>
               <select  name="tenthBoard" class="drpdwn" id="tenthBoard_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthBoard')." ".$bachMandatory; ?> onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].getTenthMarksAccToBoard(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                  <option value="">Select Xth board</option>
                  <?php foreach($fields['tenthBoard']->getValues() as $key => $val) { ?>
                  <option value="<?php echo $key; ?>" <?php echo ($key == $tenthBoard)?'selected':''?> ><?php echo $val; ?></option>
                  <?php } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="tenthBoard_error_<?php echo $regFormId; ?>"></div>
               </div>
            </div>
         </div>
         <div class="list_forms auto_clear edu_forms BachelorsEdu" <?php echo $BachelorsEduStyle; ?>>
            <?php
               $marksValues = $fields['tenthmarks']->getValues();
               $realMarks = '';
               if(!empty($educationDetails['tenthMarks'])){
                   $tenthMarks = $educationDetails['tenthMarks'];
                   if($educationDetails['tenthBoard'] == 'CBSE'){
                       global $CBSE_Grade_Mapping;
                       $marks = array_flip($CBSE_Grade_Mapping);
                       $realMarks = $marks[$tenthMarks];
                   }elseif($educationDetails['tenthBoard'] == 'IGCSE'){
                       global $IGCSE_Grade_Mapping;
                       $marks = array_flip($IGCSE_Grade_Mapping);
                       $realMarks = $marks[$tenthMarks];
                   }else{
                       $realMarks = $educationDetails['tenthMarks'];
                   }
               }
            ?>
            <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('tenthmarks'); ?>>
               <label>Class Xth CGA</label>
               <select name="tenthmarks" class="drpdwn" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks')." ".$bachMandatory; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                  <option value="">Select marks</option>
                  <?php
                     foreach($marksValues as $boardName => $valueSet)
                     {
                        foreach($valueSet as $key => $val)
                        {
                            if(($tenthBoard != "" && $tenthBoard == $boardName)||
                            ($tenthBoard == "" && $boardName == "CBSE"))
                            {
                                $displayMarks = "";
                            }else{
                                $displayMarks = "display:none;";
                            }
                  ?>
                     <option value="<?php echo $key; ?>" databoard = "<?php echo $boardName; ?>" <?php echo (($boardName ==$educationDetails['tenthBoard'])&&($realMarks == $key))?'selected':''; ?> style="<?php echo $displayMarks; ?>"><?php echo $val; ?></option>
                 <?php  }
                     } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="tenthmarks_error_<?php echo $regFormId; ?>"></div>
               </div>
            </div>
         </div>
         <div class="list_forms auto_clear edu_forms MastersEdu" <?php echo $MastersEduStyle; ?>>
         <?php
            if(!empty($educationDetails['graduationStream'])){
               $graduationStream = $educationDetails['graduationStream'];
            }
         ?>
           <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('graduationStream'); ?>>
               <label>Graduation Stream</label>
               <select id="graduationStream_<?php echo $regFormId; ?>" name="graduationStream" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream')." ".$masterMandatory ; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                 <option value="">Select graduation stream</option>
                 <?php foreach($fields['graduationStream']->getValues() as $key => $val) { ?>
                     <option value="<?php echo $key; ?>" <?php echo ($key == $graduationStream)?'selected':''?> ><?php echo $val; ?></option>
                 <?php } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                    <div class="errorMsg error-msg" id="graduationStream_error_<?php echo $regFormId; ?>"></div>
               </div>
           </div>
         </div>
         <div class="list_forms auto_clear edu_forms MastersEdu" <?php echo $MastersEduStyle; ?>>
            <?php if(!empty($educationDetails['graduationPercentage'])){
							$graduationPercentage = $educationDetails['graduationPercentage'];
						}
            ?>
            <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('graduationMarks'); ?>>
               <label>Graduation Percentage</label>
               <select id="graduationMarks_<?php echo $regFormId; ?>" name="graduationMarks" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks')." ".$masterMandatory; ?>  onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                  <option value="">Select graduation percentage</option>
                  <?php foreach($fields['graduationMarks']->getValues() as $key => $val) { ?>
                     <option value="<?php echo $key; ?>" <?php echo ($val == $graduationPercentage)?'selected':'' ?> ><?php echo $val; ?></option>
                  <?php } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="graduationMarks_error_<?php echo $regFormId; ?>"></div>
               </div>
            </div>
         </div>
         <div class="list_forms auto_clear edu_forms MastersEdu" <?php echo $MastersEduStyle; ?>>
            <?php
               $workExperienceValuesForSA = $fields['workExperience']->getSAValues();
            ?>
            <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('workExperience'); ?>>
               <label>Total Work Experience</label>
               <select id="workExperience_<?php echo $regFormId; ?>" name="workExperience" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience')." ".$masterMandatory; ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                  <option value="">Select work experience</option>
                  <?php foreach($workExperienceValuesForSA as $key => $val) { ?>
                  <option value="<?php echo $key; ?>" <?php echo ($key === $userWorkExperience)?'selected="selected"':'';?> ><?php echo $val; ?></option>
                  <?php } ?>
               </select>
               <i class="drop_ico"></i>
               <div style="display:none;">
                  <div class="errorMsg error-msg" id="workExperience_error_<?php echo $regFormId; ?>"></div>
               </div>
            </div>
         </div>
     </div>
     <div class="Personal_info">
         <p class="next_title">Personal Info</p>
         <div class="list_forms auto_clear">
         <?php
            if(!empty($abroadShortRegistrationData['isdCode'])) {
                $isdCode = $abroadShortRegistrationData['isdCode'];
            }
            if($isdCode != 91){
                    $disabled = 'disabled="disabled"';
                    $selected = 'selected="selected"';
                    $userCity = null;
				}else{
					$disabled = '';
				}
         ?>
         <div class="fluid_label" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?>>
          
            <label>City</label>
            <select name="residenceCity" <?=($disabled)?> data-enhance = "false" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
              <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
              <option style="display: none;" <?php echo $selected;?> value="-1">City not required</option>
            </select>
            <i class="drop_ico"></i>
            <div style="display:none;">
                <div class="errorMsg error-msg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
          </div>
        </div>
        <div class="list_forms auto_clear">
          <div class="fluid_label auto_clear">
            <label>Mobile No <span class="onlyvis">Only visible to you</span></label>
            <?php
               if(!empty($abroadShortRegistrationData['isdCode'])) {
                   $isdCode = $abroadShortRegistrationData['isdCode'];
                   $mobile = $abroadShortRegistrationData['mobile'];
               }
               $ISDCodeValues = $fields['isdCode']->getValues();
            ?>
            <div class="cell" <?php echo $registrationHelper->getBlockCustomAttributes('isdCode'); ?>>
              <select id="isdCode_<?php echo $regFormId; ?>" name="isdCode" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeStudyAbroadForm(this.value);shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
               <?php foreach($ISDCodeValues as $key=>$value){ ?>
                  <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
               <?php } ?>
              </select>
              <i class="drop_ico"></i>
            </div>
            <div class="cell_num" <?php echo $registrationHelper->getBlockCustomAttributes('mobile'); ?>>
              <input type="text" profanity=1 name="mobile" id="mobile_<?php echo $regFormId; ?>"  maxlength="10" minlength="10" default="Mobile No." value="<?php echo $mobile; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);"/>
              <div style="display:none;"><div class="errorMsg error-msg" id="mobile_error_<?php echo $regFormId; ?>"></div></div>
            </div>
          </div>
        </div>
     </div>
   </div>
   <!--bottom sticky buttons-->
   <div class="fix_down auto_clear">
     <a href="Javascript:void(0);" class="cancelButton">Cancel</a>
      <a href="Javascript:void(0);" class="submitButton">Save</a>
   </div>
 </div>
   <input type="hidden" id="regFormId" name="regFormId" value="<?php echo $regFormId; ?>" />
   <input type="hidden" id="context_<?php echo $regFormId; ?>" name="context" value="<?php echo $context; ?>" />
   <input type="hidden" id="isStudyAbroad" name="isStudyAbroad" value="yes" />
   <input type="hidden" id="registrationSource" name="registrationSource" value="abroadTwoStep" />
   <input type="hidden" id="referrer" name="referrer" value="<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#editProfilePage")); ?>" />
   <input type="hidden" id="pagereferrer"  value="<?php echo htmlentities($this->security->xss_clean($_SERVER['HTTP_REFERER'])); ?>" />
   <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
   <input id="tracking_keyid_<?php echo $regFormId; ?>" value="<?php echo $trackingPageKeyId; ?>" name="tracking_keyid" type="hidden">
    <?php
        $CI = & get_instance();
        $CI->load->library('security');
        $CI->security->setCSRFToken();
    ?>
    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</form>
<script>
   var regFormId = '<?php echo $regFormId; ?>';
</script>

<section class="workexp-cont clearfix">
  <article class="workexp-box">
     <div class="dtls" style="padding-bottom:0">
        
        <ul class="wrkexp-ul">
          <li>
          
            <div class="text-show">
              <label>Your Desired Study Location</label>
              <div class="desire-cntry">
                 <input type="radio" class="radio-custom" name="radio-group1" name="yes" id="04"/> 
                 <label for="04" class="radio-custom-label1" id="nationalPreference"><i class="profile-sprite i_chck"></i>India</label>
          
                 <input type="radio" class="radio-custom" name="radio-group1" name="yes" id="05" checked="checked"/> 
                 <label for="05" class="radio-custom-label1 lft-space" id="abroadPreference"><i class="profile-sprite i_chck"></i>Abroad</label>
              </div>
            </div>
          </li> 
           
          <?php 
          if(isset($fields['destinationCountry'])){

            $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));

            global $studyAbroadPopularCountries;

            foreach ($destinationCountry as $key => $country) {

              $otherCountryId = '';
              $otherCountryId = $country->getId();
              if($country->getId() == 1) {
                continue;
              }
              $otherCountryName = '';
              $otherCountryName = $country->getName();

              if(!in_array($otherCountryName,$studyAbroadPopularCountries)) {
                $otherCountries[$otherCountryId] = $otherCountryName;
              }

            }

            if(!empty($locationPreferences['CountryId'])) {

              $prefilledCountries = array();
              $savedCountries = $locationPreferences['CountryId'];
              foreach($locationPreferences['CountryId'] as $countryId) {
                if(!empty($studyAbroadPopularCountries[$countryId])) {
                  $prefilledCountries[] = $studyAbroadPopularCountries[$countryId];
                }
                if(!empty($otherCountries[$countryId])) {
                  $prefilledCountries[] = $otherCountries[$countryId];
                }
              }

              $allprefilledCountries = implode(", ",$prefilledCountries);
              if(!empty($prefilledCountries)) {
                $prefilledClass = "filled1";
              }

            }
          
            ?>

            <li>
              <div id="destinationCountryBlock_<?php echo $regFormId; ?>" class="text-show <?=$prefilledClass?>">
                <label class="form-label">Country of Interest</label>
                <input type="text" name="destinationCountryNames" class="user-txt-flds" readonly="readonly" id="desiredCountry" value="<?php echo $allprefilledCountries;?>">
                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a> 

              </div>  

              <div style="display:none">
                  <div class="regErrorMsg" id="destinationCountry_error_<?php echo $regFormId; ?>"></div>
              </div>

              <div style="display:none" id="desiredCountrySelect">

                <div class="graylayer"></div>
                <div class="text-show fixedCountry">
                  <i class="close">×</i>
                  <div class="customSelectCountry multiselect editable" id="custom-slct2">                                   
                        
                    <div class="option-wrapper show">
                      <div class="loc-title">POPULAR LOCATIONS</div>
                        <ul class="drpdwn-ul">
                          <?php

                          foreach($studyAbroadPopularCountries as $key => $popularCountry){
                            $checked = '';
                            if(in_array($key,$savedCountries)) {
                              $checked = "checked='checked'";
                            }
                          ?>

                             <li>
                                 <div class="Customcheckbox">

                                     <input type="checkbox" class="destinationCountryCheckbox" <?=$checked?> name="destinationCountry[]" id="<?php echo str_replace(" ","",$popularCountry); ?>-flag" value="<?php echo $key; ?>">
                                     <label for="<?php echo str_replace(" ","",$popularCountry); ?>-flag" class="destinationCountryCheckboxLabel"><?php echo $popularCountry; ?></label>
                                 </div>
                             </li>

                             <?php } ?>

                      </ul>
                      
                      <div class="loc-title noMarginLoc">OTHER LOCATIONS</div>
                      
                      <ul class="drpdwn-ul">

                          <?php 
                            foreach ($otherCountries as $countryKey => $countryName) {
                              $checked = '';
                              if(in_array($countryKey,$savedCountries)) {
                                $checked = "checked='checked'";
                              }
                            ?>
                             <li>
                                 <div class="Customcheckbox">
                                     <input type="checkbox" class="destinationCountryCheckbox" <?=$checked?> name="destinationCountry[]" id="<?php echo str_replace(" ","",$countryKey); ?>-otherflag" value="<?php echo $countryKey; ?>">
                                     <label for="<?php echo str_replace(" ","",$countryKey); ?>-otherflag" class="destinationCountryCheckboxLabel"><?php echo $countryName;?></label>
                                 </div>
                             </li>
                            <?php                                         
                            } ?>

                        </ul>
                      </div>

                      <div class="done-btn"><input type="button" id="countrySave" class="green-btn" value="Done"></div>

                    </div>
                </div>

              </div>

            </li> 

            <?php } ?>
           
           <?php 
            $selectedDesiredCourseName = '';$popularCourses = array();
            foreach($fields['abroadDesiredCourse']->getValues() as $courseDetails) {
              $popularCourses[] = $courseDetails;
              if($courseDetails['SpecializationId'] == $desiredCourseDetails['desiredCourseId']) {
                $selectedDesiredCourseName = $courseDetails['CourseName'];
              }
            }

            $popularStreams = array();
            foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $category_id => $categoryNameDetails) {
              $popularStreams[$category_id] = $categoryNameDetails;
              if(($category_id == $desiredCourseDetails['categoryId']) && (empty($selectedDesiredCourseName))) {
                $selectedDesiredCourseName = $categoryNameDetails['name'];
              } 
            }

            $prefilledClass = "";
            if(!empty($selectedDesiredCourseName)) {
              $prefilledClass = "filled1";
            }
           ?>

            <li>
              <div class="text-show <?php echo $prefilledClass;?>">

                <label class="form-label">Education Interest</label>
                <input mandatory="1" type="text" readonly="readonly" name="fieldOfInterest_<?php echo $regFormId; ?>_input" value="<?php echo $selectedDesiredCourseName;?>" class="user-txt-flds ssLayerWOG" id="fieldOfInterest_<?php echo $regFormId; ?>_input" >

                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a> 
                <em class="pointerDown ssLayerWOG"></em>

              </div>   

              <div style="display:none">
                  <div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
              </div>                     

              <div class="select-Class">

                <select mandatory="1" regFieldId="fieldOfInterest" caption="desired education of interest" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" style="display:none;" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                  <optgroup label="Choose a popular course">
                    <option value="">Select</option>
                    <?php 
                    $selectedDesiredCourseId = '';
                    foreach($popularCourses as $course) {
                      if($course['SpecializationId'] == $desiredCourseDetails['desiredCourseId']) {
                          $selected = 'selected="selected"';
                          $selectedDesiredCourseId = $desiredCourseDetails['desiredCourseId'];
                      } else {
                          $selected = '';
                      } ?>
                    
                      <option value="<?=$course['SpecializationId']?>" <?php echo $selected;?>><?=$course['CourseName']?></option>
                    <?php } ?>
                  </optgroup>

                  <optgroup label="Or Choose a stream">
                    <?php 
                   foreach($popularStreams as $categoryId => $categoryName) {
                          if($categoryId == $desiredCourseDetails['categoryId']) {
                              $selected = 'selected="selected"';
                              $selectedDesiredCourseId = $desiredCourseDetails['categoryId'];
                          } else {
                              $selected = '';
                          } 
                          ?>
                      <option value="<?php echo $categoryId; ?>" <?php echo $selected;?>><?php echo $categoryName['name']; ?></option>
                    <?php } ?>
                  </optgroup> 
                </select>                     

                    
               </div>  
            </li> 

            <?php if(isset($fields['abroadDesiredCourse'])){ ?>
              <input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '' />
            <?php } ?>

            <?php if(isset($fields['desiredGraduationLevel'])){ ?>

            <li id="desiredGraduationLevel_block_<?php echo $regFormId;?>" <?php if(empty($selectedDesiredCourseId)){ echo 'style="display:none;"'; }?>>
              <div class="text-show">
                <label>Level of Study</label>
                <div class="desire-cntry" id="twoStepLevelOfStudy">

                  <?php 
                  foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) {  ?>

                    <input id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" type="radio" class="radio-custom desiredGraduationLevel_<?php echo $regFormId;?>"  class="desiredGraduationLevel_<?php echo $regFormId; ?>" value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();" caption="the desired course level" label="Desired Graduation Level" regfieldid="desiredGraduationLevel" mandatory="1"/> 
                    <label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" class="radio-custom-label1"><i class="profile-sprite i_chck"></i><?=$desiredGraduationLevelText['CourseName']?></label>

                  <?php } ?>         

                 
                </div>
              </div>

              <div style="display:none">
                  <div class="regErrorMsg" id="desiredGraduationLevel_error_<?php echo $regFormId; ?>"></div>
              </div>

            </li> 

            <?php } ?>

            <?php if(isset($fields['abroadSpecialization'])){   ?>

            <li id='abroadSpecialization_block_<?php echo $regFormId; ?>' style="display:none">
             <div class="text-show" id="abroadSpecialization_sub_block_<?php echo $regFormId; ?>">

                <label class="form-label">Course Specialization</label>
                <input type="text" readonly="readonly" class="user-txt-flds ssLayer" name="abroadSpecialization_<?php echo $regFormId; ?>_input" id="abroadSpecialization_<?php echo $regFormId; ?>_input">

                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide" style=""></a> 
                <em class="pointerDown"></em>
              </div>

              <div class="select-Class">
                  <select name="abroadSpecialization" id="abroadSpecialization_<?php echo $regFormId; ?>" style="display:none;" regfieldid="abroadSpecialization" caption="specialization">
                  </select>
              </div>
 
            </li> 

            <?php } ?>


            <?php        
            if(isset($fields['whenPlanToGo'])){
                if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
                   if(date('m',strtotime('now')) > 9) {
                    $creationDate = (int)date('Y', time());
                    $whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
                    $whenPlanToGo = (int)date('Y', $whenPlanToGo);
            
                    if($creationDate + 1 == $whenPlanToGo) {
                      $whenPlanToGo = 'in1Year';
                    }else if($creationDate + 2 == $whenPlanToGo) {
                      $whenPlanToGo = 'in2Years';
                    }
                    else {
                      $whenPlanToGo = 'later';
                    }
                   }else{

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
                }

                $prefilledClass = "";
                $whenPlanToGoValues = $fields['whenPlanToGo']->getValues();
                if(!empty($whenPlanToGo)) {
                  $prefilledwhenPlanToGoText = $whenPlanToGoValues[$whenPlanToGo];
                  $prefilledClass = "filled1";
                }
            ?>

    
            <li>
              <div class="text-show <?=$prefilledClass?>">
              <label class="form-label">Planning To Study Abroad In</label>
                <input mandatory="1" type="text" readonly="readonly" value="<?php echo $prefilledwhenPlanToGoText;?>" class="user-txt-flds ssLayer" id="whenPlanToGo_<?php echo $regFormId; ?>_input">
                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a> 
                <em class="pointerDown"></em>
              </div>  
              <div class="select-Class">
                <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $regFormId; ?>" caption="when you plan to start the course" label="When do you plan to go?" mandatory="1" regfieldid="whenPlanToGo" style="display:none">   
                    <option value="">Planning To Study Abroad In</option>                           
                    <?php foreach($whenPlanToGoValues as $plannedToGoValue => $plannedToGoText) { ?>
                    <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
                    <?php } ?>
                </select>
                  
              </div> 

              <div style="display:none">
                  <div class="regErrorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div>
              </div>
                   
           </li> 

           <?php } ?>
           
           <?php

              $prefilledClass = "";
              $budgetValues = $fields['budget']->getValues();
              if(!empty($userPreference['Budget'])) {
                $prefilledbudgetText = $budgetValues[$userPreference['Budget']];
                $prefilledClass = "filled1";
              }
           ?>
           
           
            <li>
              <div class="text-show <?=$prefilledClass?>">
               <label class="form-label">Budget Of Studies</label>
                <input type="text" id="budget_<?=$regFormId?>_input" value="<?php echo $prefilledbudgetText;?>" name="budget_<?=$regFormId?>_input" readonly="readonly" class="user-txt-flds ssLayer">
                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide" style=""></a> 
                <em class="pointerDown"></em>
               </div>  

              <div class="select-Class">
                <select name="budget" id="budget_<?=$regFormId?>" name="budget_<?=$regFormId?>" class="dfl-drp-dwn cursor" label="budget of studies" style="display:none">
                    <option value="">Budget Of Studies</option>
                    <?php foreach($budgetValues as $budgetValue => $budgetText) { ?>
                    <option value="<?php echo $budgetValue; ?>" <?php if(!empty($userPreference['Budget']) && ($budgetValue == $userPreference['Budget'])) echo 'selected'; ?>><?php echo $budgetText; ?></option>
                    <?php } ?>
                </select>
              </div>

            </li>
           

            <?php

              $prefilledClass = "";
              $fundValues = $fields['fund']->getValues();
              if(!empty($userPreference['sourceOfFunding'])) {
                $prefilledsourceOfFundingText = $fundValues[$userPreference['sourceOfFunding']];
                $prefilledClass = "filled1";
              }
          ?>

          <li>
              <div class="text-show <?=$prefilledClass?>">
               <label class="form-label">Source Of Funding</label>
                <input type="text" value="<?php echo $prefilledsourceOfFundingText;?>" id="sourceOfFunding_<?=$regFormId?>_input" readonly="readonly" class="user-txt-flds ssLayer">
                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide" style=""></a> 
                <em class="pointerDown"></em>
               </div>  

              <div class="select-Class">
                <select name="sourceOfFunding" id="sourceOfFunding_<?=$regFormId?>" class="dfl-drp-dwn cursor" label="budget of studies" style="display:none">
                  <option value="">Source Of Funding</option>
                  <?php foreach($fundValues as $fundValue => $fundText) { ?>
                    <option value="<?php echo $fundValue; ?>" <?php if(!empty($userPreference['sourceOfFunding']) && ($fundValue == $userPreference['sourceOfFunding'])) echo 'selected'; ?>><?php echo $fundText; ?></option>
                  <?php } ?>
                </select>
              </div>

           </li>


          <?php

              $prefilledClass = "";
              if(!empty($additionalInfo['Extracurricular'])) {
                $prefilledClass = "filled1";
              }
          ?>
          <li>
              <div class="text-show <?=$prefilledClass?>">
                   <label class="form-label">Extra Curricular Activities</label>
                    <textarea class="txt-area" maxlength="500" minlength="45" wrap="physical" name="extracurricular" id="extracurricular_<?php echo $regFormId; ?>"><?php echo $additionalInfo['Extracurricular']; ?></textarea>
              </div>  

              <div style="display:none">
                  <div class="regErrorMsg" id="extracurricular_error_<?php echo $regFormId; ?>"></div>
              </div>

          </li>

           <?php

              $prefilledClass = "";
              if(!empty($additionalInfo['SpecialConsiderations'])) {
                $prefilledClass = "filled1";
              }
          ?>

          <li>
            <div class="text-show <?=$prefilledClass?>">
                <label class="form-label">Special Considerations</label>
                <textarea class="txt-area" maxlength="500" minlength="45" wrap="physical" id="specialConsiderations_<?php echo $regFormId; ?>"  name="specialConsiderations"><?php echo $additionalInfo['SpecialConsiderations']; ?></textarea>
            </div>

            <div style="display:none">
              <div class="regErrorMsg" id="specialConsiderations_error_<?php echo $regFormId; ?>"></div>
            </div>

          </li>

          <?php

              $prefilledClass = "";
              if(!empty($additionalInfo['Preferences'])) {
                $prefilledClass = "filled1";
              }
          ?>

          <li>
             <div class="text-show <?=$prefilledClass?>">
                <label class="form-label">Preferences</label>
                <textarea class="txt-area" maxlength="500" minlength="45" wrap="physical" id="preferences_<?php echo $regFormId; ?>" name="preferences"><?php echo $additionalInfo['Preferences']; ?></textarea>
              </div>

              <div style="display:none">
                  <div class="regErrorMsg" id="preferences_error_<?php echo $regFormId; ?>"></div>
              </div>

          </li>

          <?php 

          if(isset($fields['examTaken'])){

            //this check is for if we delete any exam or No is selected for any exam given
            $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
            $examsAbroadMasterList = $fields['examsAbroad']->getValues();
            
            $abroadExamNameList = array_map(function($a){ return $a['name'];},$examsAbroadMasterList);
            $examAbroadWithScoreGreterThanZero = array();
            foreach($examsAbroad as $examName => $examScore) {
              if(!in_array($examName,$abroadExamNameList))  {
                unset($examsAbroad[$examName]);
              }elseif($examScore !='' && $examScore >=0) {
                if($examName !='IELTS'){
                  $examAbroadWithScoreGreterThanZero[$examName]=(int)$examScore;
                }else{
                  $examAbroadWithScoreGreterThanZero[$examName]= $examScore;     
                }
              } 
            }

            //because now we consider only exam which have score greater than zero
            $examsAbroad = $examAbroadWithScoreGreterThanZero;

            if(!empty($abroadShortRegistrationData['examsAbroad'])  && count($examsAbroad)>0) {
                $examTaken = 'yes';
                $abroadExamFlag = 1;
            }
            else if(!empty($abroadShortRegistrationData['passport'])&& $abroadShortRegistrationData['bookedExamDate']!=1) {
                $examTaken = 'no';
                $abroadExamFlag = 0;
            }
            else if($abroadShortRegistrationData['bookedExamDate'] ==1)
            {
                $examTaken = 'bookedExamDate';
                $abroadExamFlag = 0;
            }

            ?>

            <li id="examTaken_block_<?php echo $regFormId; ?>">
              <div class="text-show">
                <label>Have You Given Any Competitive Exam ?</label>
                <div class="desire-cntry" id="twoStepLevelOfStudy">

                  <input type="radio" class="radio-custom examTaken_yes examTaken_<?php echo $regFormId; ?>" id="examTaken_yes_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked="checked"'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken">
                  <label for="examTaken_yes_<?php echo $regFormId; ?>" class="radio-custom-label1 radio-custom-label2"> <i class="profile-sprite i_chck"></i>Yes</label>

                  <input type="radio" class="radio-custom examTaken_no  examTaken_<?php echo $regFormId; ?>" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked="checked"'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken">
                  <label for="examTaken_no_<?php echo $regFormId; ?>" class="radio-custom-label1 radio-custom-label2 lft-space2"> <i class="profile-sprite i_chck"></i>No</label>

                  <input type="radio" class="radio-custom examTaken_bookedExamDate  examTaken_<?php echo $regFormId; ?>" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked="checked"'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken">
                  <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="radio-custom-label1 radio-custom-label2 lft-space2"> <i class="profile-sprite i_chck"></i><?php echo $fields['bookedExamDate']->getLabel(); ?></label>     
                  <input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>
                </div>
              </div>

              <div style="display:none">
                <div class="regErrorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
              </div> 

            </li> 

          <?php } ?>

          <?php 
            if(isset($fields['passport'])){ 
              $style = 'display:none;';
              if(!empty($abroadShortRegistrationData['passport'])) {
                $passport = $abroadShortRegistrationData['passport'];
                if($examTaken == 'no') {
                  $style = 'display:block;';
                }
              }
            ?>
            
            <li id="passport_block_<?php echo $regFormId; ?>" style="<?=$style?>">
              <div class="text-show">
                <label>Do You Have A Valid Passport?</label>
                <div class="desire-cntry" id="twoStepLevelOfStudy">


                <input type="radio" class="radio-custom passport_<?php echo $regFormId; ?>" name="passport" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> caption="whether you have a vaild passport" label="Passport" <?php if(!empty($examTaken) && $examTaken == 'no') { echo 'mandatory="1"'; } ?> regfieldid="passport">
                  <label for="passport_yes_<?php echo $regFormId; ?>" class="radio-custom-label1"> <i class="profile-sprite i_chck"></i>Yes</label>


                  <input type="radio" class="radio-custom passport_<?php echo $regFormId; ?>" name="passport" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> caption="whether you have a vaild passport" label="Passport" <?php if(!empty($examTaken) && $examTaken == 'no') { echo 'mandatory="1"'; } ?> regfieldid="passport">
                    <label for="passport_no_<?php echo $regFormId; ?>" class="radio-custom-label1 lft-space"> <i class="profile-sprite i_chck"></i>No</label>
                 
                </div>
              </div>
              <input type="hidden" value="<?php echo $passport;?>" id="passportPrefilledValue" />
              <div style="display:none">
                <div class="regErrorMsg" id="passport_error_<?php echo $regFormId; ?>"></div>
              </div> 

            </li>

          <?php } ?>


         </ul>
     



    <?php 
    if(isset($fields['examsAbroad'])){ 

      global $examGrades;
      global $examFloat;
      $count = 0;
      $display = false;
      
      $examListByName = array();
      foreach($examsAbroadMasterList as $id=>$value){
        if($value['scoreType']=='grades'){
          $examListByName[$value['name']] = explode(',',$value['range']);   
        }
          
      }

      if($examsAbroad !='' && count($examsAbroad) >0){ ?>

        <div id="examsAbroad_block_<?php echo $regFormId; ?>">

          <input type="hidden" name="examTobeDeleted" value='<?php  echo json_encode(array_keys($examsAbroad))?>' />  

          <?php   
          foreach($examsAbroad as $examName => $examScore) {

            if(in_array($examName,array_keys($examListByName))){
                $keyOfrange = ((int)$examScore)-1;  
                $examScore = $examListByName[$examName][$keyOfrange];
            }
            $scoreType = '';      
            ?>


            <div class="examContainer" id="examDetail_<?= $count?>">
            
              <a href="#" class="cross-sec clearfix"><i class="up-cross removeExamButton" counter="<?= $count?>" id="removeExamButton_<?= $count?>">&times;</i></a>
              <ul class="wrkexp-ul">
               
                <li>
                  <div class="text-show filled1">
                    
                    <label class="form-label">Exam Name</label>

                    <input type="text" examid="<?= $count?>" name="examNameTextField" value="<?php echo $examName;?>" class="user-txt-flds ssLayer examNameTextField" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>_input" readonly="readonly"/>
                    <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a> 
                    <em class="pointerDown"></em>

                  </div>                    

                  <div style="display:none">
                    <div class="regErrorMsg examName" id="<?php echo $count; ?>_examName_error_<?php echo $regFormId; ?>"></div>
                  </div>

                  <div class="select-Class">                        
                      
                    <select style="display:none" examid="<?= $count?>" name="exams[]" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].manageExamScoreField('<?= $count; ?>');">
                      <option value="">Exam Name</option>
                      <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
                       <option value="<?= $examDetail['name'];?>"  <?php if(strtolower($examDetail['name'])==strtolower($examName)){ echo 'selected="selected"';$scoreType=$examDetail['scoreType']; }?> maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
                      <?php } ?>
                    </select>

                  </div>
                   
                </li> 
               
                <li>
            

                  <div class="text-show filled1">                  

                    <label class="form-label">Exam Score</label>
                    
                    <input type="text" class="user-txt-flds examScore" id="<?= $count;?>_score_<?php echo $regFormId; ?>" <?php if(empty($scoreType)) { echo 'disabled="disabled"';}?> exam="<?= $examName; ?>" maxlength="5" minscore="" maxscore="" range="" caption="<?= $examName; ?>" default="Exam Score" value="<?= $examScore; ?>" name="<?= $examName; ?>_score" onblur="" onfocus="">
                    <input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>" name="<?php echo $examName; ?>_scoreType" value="<?= $scoreType;?>">

                  </div>                        
                 

                  <div style="display:none">
                    <div class="regErrorMsg examNameScore" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
                  </div> 


                </li>
                 
              </ul>

            </div>

       <?php $count++;
          } ?>

      </div>

    <?php
      } else { ?>

        <div id="examsAbroad_block_<?php echo $regFormId; ?>" style="display:none">

          <div class="examContainer" id="examDetail_<?= $count?>">
            
            <a href="#" class="cross-sec clearfix"><i class="up-cross removeExamButton" counter="<?= $count?>" id="removeExamButton_<?= $count?>"></i></a>
            <ul class="wrkexp-ul">
             
              <li>
                <div class="text-show">              

                  <label class="form-label">Exam Name</label>
                   
                  <input type="text" examid="<?= $count?>" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>_input" readonly="readonly" name="examNameTextField" value="" class="user-txt-flds ssLayer examNameTextField" />
                  <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a> 
                  <em class="pointerDown"></em>

                </div>                    

                <div style="display:none">
                  <div class="regErrorMsg examName" id="<?php echo $count; ?>_examName_error_<?php echo $regFormId; ?>"></div>
                </div>

                <div class="select-Class">                        
                    
                  <select style="display:none" examid="<?= $count?>" name="exams[]" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].manageExamScoreField('<?= $count; ?>');">
                    <option value="">Exam Name</option>
                    <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
                     <option value="<?= $examDetail['name'];?>"  <?php if(strtolower($examDetail['name'])==strtolower($examName)){ echo 'selected="selected"';$scoreType=$examDetail['scoreType']; }?> maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
                    <?php } ?>
                  </select>

                </div>
              
              </li> 
             
              <li>
                <div class="text-show disable-input">                
                  <label class="form-label">Exam Score</label>

                  <input type="text" class="user-txt-flds examScore" id="<?= $count;?>_score_<?php echo $regFormId; ?>" <?php if(empty($scoreType)) { echo 'disabled="disabled"';}?> exam="" maxlength="5" minscore="" maxscore="" range="" caption=""  value="" name="" onblur="" onfocus="">
                  <input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>" name="" value="">

                </div>                                        
              
                <div style="display:none">
                  <div class="regErrorMsg examNameScore" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
                </div>

              </li>
             
            </ul>                

          </div>
        </div>       

    <?php
      }

    } ?>


    </div>

    <?php
    if(empty($abroadShortRegistrationData['examsAbroad']) || count($examsAbroad) == 0) {
      $anotherExamButtonStyle = 'display:none';
    } ?>

    <div class="add-card" style="<?=$anotherExamButtonStyle?>" id="anotherExamButton">
      <a style="<?=(count($examsAbroad)>=9)?'display:none;':""; ?>" href="#" class="add-sec add-workEx" id="addAnotherExamLink">
        <i class="profile-sprite addplus-icon flLt"></i>
        Add Exam Details
      </a>
    </div>

    <div style="display:none">
      <div id="examsAbroad_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
    </div> 



  </article>
     
</section>  

<input type="button" class="common-btn bottom-fix layerSaveButton" value="save" id="saveEducationPreference"/>
<input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="educationalPreferenceSection" />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='yes' />
<input type='hidden' name='regFormId' value='<?php echo $regFormId;?>' />

<?php 
$fields = array('whenPlanToGo', 'examTaken', 'examsAbroad', 'passport','abroadDesiredCourse', 'fieldOfInterest', 'desiredGraduationLevel', 'abroadSpecialization','extracurricular','specialConsiderations','preferences','destinationCountry');

$customValidationFields = array('extracurricular'=>'MinMaxLen','specialConsiderations'=>'MinMaxLen','preferences'=>'MinMaxLen', 'destinationCountry'=>'DestinationCountry');
?>

<script type="text/javascript">

  var registration_context = 'abroadUserSetting';
  var countExams = parseInt(<?php echo $count;?>);
  var abroadregFormId = '<?php echo $regFormId; ?>';
  var abroadExamFlag   = '<?php echo $abroadExamFlag;?>';
  var courseLevel = "<?php echo $desiredCourseDetails['desiredGraduationLevel'];?>";
  //var destinationCountry = '{<?php echo json_encode($locationPreferences['CountryId']);?>}';console.log(destinationCountry);
  var profilePage = true;
  var specializationId = "<?php echo $desiredCourseDetails['abroadSpecialization'];?>";
  var selectedDesiredCourseId = "<?php echo $selectedDesiredCourseId; ?>";
  var totalNationalExamCount = 0;
  var nationalExamBlock = 0;
  
  var shikshaUserProfileForm = {};
  var shikshaUserRegistrationForm = {};
  var examDivIdsExist = {};

  shikshaUserProfileForm[abroadregFormId] = new ShikshaUserProfileForm(abroadregFormId);
  shikshaUserRegistrationForm[abroadregFormId] = new ShikshaUserRegistrationForm(abroadregFormId);

  shikshaUserProfileForm[abroadregFormId].setCustomValidations(<?php echo json_encode($customValidationFields); ?>);
  shikshaUserProfileForm[abroadregFormId].setFormFieldList(<?php echo json_encode($fields); ?>);

  <?php
  if($examsAbroad !='' && count($examsAbroad) >0){ ?>
    for(var i = 0;i< countExams;i++) {
        shikshaUserProfileForm[abroadregFormId].manageExamScoreField(i,abroadregFormId);
    }
  <?php
  } ?>

  if(selectedDesiredCourseId != '') {
    $('#fieldOfInterest_'+abroadregFormId).change();

    if (courseLevel!='') {
      $('[name="desiredGraduationLevel"]').each(function(){
    
        if($(this).val().toUpperCase()==courseLevel.toUpperCase())
        {
          $(this).attr('checked',true);
          $(this).change();
        }
      })
    }
  } 
</script>
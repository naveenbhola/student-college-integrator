<?php 
$regFormId = random_string('alnum', 6);
?>

<section class="prf-box-grey" >
    <div class="prft-titl">
         <div class="caption">
            <p>EDUCATION PREFERENCES</p>
         </div>
    </div>

<!--profile-tab content-->
  <div class="frm-bdy">
     <form class="prf-form" id="registrationForm_<?=$regFormId?>">
        <div class="edu"> 
         <ul class="p-ul">
         <li class="p-1">
               <span>
                   <label class="cursor">Your Desired Study Location</label>
                     <span class="prf-r">
                      <input type="radio" class="prf-inputRadio">
                      <label class="prf-radio" onclick="editUserProfile('educationalPreferenceSection','EDUCATION PREFERENCES', 'national');"> <i class="icons ic_radiodisable1"></i>India</label>
                   </span>
                   <span class="prf-r" style="margin-left:30px;">
                      <input type="radio" class="prf-inputRadio" checked="checked">
                      <label class="prf-radio"> <i class="icons ic_radiodisable1"></i>Abroad</label>
                   </span>
                </span>
           </li>

          <?php 
          if(isset($fields['destinationCountry'])){

            $destinationCountry = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')); ?>

            <li class="p-l">
              <span class="p-s">

                <div class="account-setting-fields flLt signup-txtwidth">
                  <div id="destinationCountry_block_<?=$regFormId?>">
                    <div id="twoStepChooseCourseCountryDropDown" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" style="position: relative; left: 0; top: 0; width: 100%; background:rgba(0,0,0,0);">
                
                      <label class="cursor">Country Of Interest</label>
                      <div class="custom-drp">
                            <div id="countryNameDiv" class="select-overlap" style="width:300px !important;"></div>
                            <select class="dfl-drp-dwn" id="twoStepCountrySelect">
                                  <option id="twoStepCountrySelectOption">Select</option>
                            </select>
                      </div>          
                      <div class="select-opt-layer" style="display:none; width: 435px; z-index: 99;left:0 !important; right:auto; top: 55px" id="twoStepCountryDropdownLayer" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();">
                        <div class="scrollbar1" id="twoStepChooseCourseCountryLayerScrollbar">
                          <div class="scrollbar courseCountryScrollbarHeight disable" style="height: 145px;">
                            <div class="track courseCountryScrollbarHeight" style="height: 145px;">
                              <div class="thumb" style="top: 0px; height: 145px;">
                                <div class="end"></div>
                              </div>
                            </div>
                          </div>
                          
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
                                          </span>
                                          <strong><?php echo $popularCountry; ?></strong>
                                        </p>
                                      </div>
                                      <div class="flag-chkbox">
                                        <input type="checkbox" name="destinationCountry[]" class="nav-inputChk" id="<?php echo $popularCountry; ?>-flag" value="<?php echo $key; ?>">
                                        <label for="<?php echo $popularCountry; ?>-flag" class="nav-chck" onclick="return false;"><i class="icons ic_checkdisable2" style="margin-top:8px;"></i><span class="common-sprite"></span></label>
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
                                        <input type="checkbox" class="nav-inputChk" name="destinationCountry[]" id="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" value="<?php echo $country->getId(); ?>" >
                                        <label for="twoStepCountryDropdownCont2_<?php echo $country->getId(); ?>" class="nav-chck" onclick="return false;">  <i class="icons ic_checkdisable2"></i><span class="common-sprite"></span> <?php echo $country->getName();?></label>
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
                              <a href="JavaScript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].numberOfCountriesSelected();" class="button-style" style="padding: 6px 30px;margin-left:198px;">OK</a>
                            </p>
                          </div>
                        </div>

                        <div>
                          <div class="regErrorMsg" id="twoStepCourseCountryLayer_error<?php echo $regFormId ?>"></div>
                        </div>
                      
                      </div>
                    </div>
                    
                    <div class="clearfix"></div>
                  </div>
                </span>
           
              </li>
              <?php } ?>

              <li class="p-l">

                <span class="p-s">
                  <label class="cursor">Education Interest</label>

                  <div class="custom-drp">
                    <select class="dfl-drp-dwn cursor" name="fieldOfInterest" mandatory="1" regFieldId="fieldOfInterest" caption="desired education of interest" id="fieldOfInterest_<?php echo $regFormId; ?>" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].parentcatOnchangeActions();">
                      <option value="">Select</option>
                      <optgroup label="Choose a popular course">
                      <?php $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                        $selectedDesiredCourseId = '';
                        foreach($desiredCourses as $course) {
                            if($course['SpecializationId'] == $desiredCourseDetails['desiredCourseId']) {
                                $selected = 'selected="selected"';
                                $selectedDesiredCourseId = $desiredCourseDetails['desiredCourseId'];
                            } else {
                                $selected = '';
                            } ?>
                        <option value="<?=$course['SpecializationId']?>" <?= $selected;?>><?=$course['CourseName']?></option>
                    <?php } ?>
                       </optgroup>
                       <optgroup label="Or Choose a stream">
                      <?php foreach($fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad')) as $categoryId => $categoryName) {
                            if($categoryId == $desiredCourseDetails['categoryId']) {
                                $selected = 'selected="selected"';
                                $selectedDesiredCourseId = $desiredCourseDetails['categoryId'];
                            } else {
                                $selected = '';
                            } 
                            ?>
                        <option value="<?php echo $categoryId; ?>" <?= $selected;?>><?php echo $categoryName['name']; ?></option>
                      <?php } ?>
                       </optgroup>
                    </select>
                  </div>
                  <div class="clearFix"></div>
                  <div><div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div></div>
                
                <?php if(isset($fields['abroadDesiredCourse'])){
                  echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '' />";
                  } ?>


                </span>

                <?php if(isset($fields['abroadSpecialization'])){ ?>
                <span class="p-s">

                  <div id='abroadSpecialization_block_<?php echo $regFormId; ?>' blockfor="abroadSpecialization" style="display:none">
                    <label class="cursor">Course Specialization</label>

                    <div class="custom-drp">
                      <select name="abroadSpecialization"  class="dfl-drp-dwn cursor" id="abroadSpecialization_<?php echo $regFormId; ?>" caption="specialization" label="Specialization" regfieldid="abroadSpecialization">
                        <option>Select</option>
                      </select>
                    </div>
                    <div>
                      <div class="regErrorMsg" id="abroadSpecialization_error_<?php echo $regFormId; ?>"></div>
                    </div>
                  </div>
                 
                </span>
                <?php } ?>
                
              </li>

              <?php if(isset($fields['desiredGraduationLevel'])){ ?>
              <li class="p-l" id="desiredGraduationLevel_block_<?php echo $regFormId;?>" <?php if(empty($selectedDesiredCourseId)){ echo 'style="display:none;"'; }?>>
                <span class="p-s" id="twoStepLevelOfStudy">
                  <label class="cursor">Choose Level Of Study</label>

                  <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) {  ?>
                  <span class="prf-r">
                    <input  mandatory="1" type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" class="desiredGraduationLevel_<?php echo $regFormId; ?>" value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" onclick="this.blur();" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateSpecializedCourses();" caption="the desired course level" label="Desired Graduation Level" regfieldid="desiredGraduationLevel">
                    <label style="margin-right:10px !important " for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" class="prf-radio">
                      <i class="icons ic_radiodisable1"></i><?=$desiredGraduationLevelText['CourseName']?>
                    </label>
                  </span>
                  <?php } ?>                        
                  
                  <div class="clearFix"></div>
                  <div>
                    <div id="desiredGraduationLevel_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                  </div>                    

                </span>
              </li>
              <?php } ?>


              <li class="p-l">

              <?php        
              if(isset($fields['whenPlanToGo'])){
                  if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
                     if(date('m',strtotime('now')) > 9) {
                      $creationDate = (int)date('Y', time());
                      $whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
                      $whenPlanToGo = (int)date('Y', $whenPlanToGo);
              
                      // _p($whenPlanToGo); die;
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
              ?>
                  <span class="p-s">
                    <label class="cursor">Planning To Study Abroad In</label>
                     
                    <div class="custom-drp" id="whenPlanToGo_block_<?php echo $regFormId; ?>">
                      <select name="whenPlanToGo" class="dfl-drp-dwn cursor" id="whenPlanToGo_<?php echo $regFormId; ?>" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" caption="when you plan to start the course" label="When do you plan to go?" mandatory="1" regfieldid="whenPlanToGo">
                        <option value="">Select</option>
                        <?php foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                        <option value="<?php echo $plannedToGoValue; ?>" <?php if(!empty($whenPlanToGo) && ($plannedToGoValue == $whenPlanToGo)) echo 'selected'; ?>><?php echo $plannedToGoText; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="clearFix"></div>
                    <div><div class="regErrorMsg" id="whenPlanToGo_error_<?php echo $regFormId; ?>"></div></div>

                  </span>
                <?php } ?>

              </li>

              <li class="p-l">
                <span class="p-s">
                  <label class="cursor">Budget Of Studies</label>
                  <div class="custom-drp" id="budget_block_<?php echo $regFormId; ?>">
                    <select name="budget" class="dfl-drp-dwn cursor" label="budget of studies">
                      <option value="">Select</option>
                      <?php foreach($fields['budget']->getValues() as $budgetValue => $budgetText) { ?>
                      <option value="<?php echo $budgetValue; ?>" <?php if(!empty($userPreference['Budget']) && ($budgetValue == $userPreference['Budget'])) echo 'selected'; ?>><?php echo $budgetText; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </span>

                <span class="p-s">
                  <label class="cursor">Source Of Funding</label>
                  <div class="custom-drp" id="fund_block_<?php echo $regFormId; ?>">
                    <select name="sourceOfFunding" class="dfl-drp-dwn cursor" label="source of funding">
                      <option value="">Select</option>
                      <?php foreach($fields['fund']->getValues() as $fundValue => $fundText) { ?>
                      <option value="<?php echo $fundValue; ?>" <?php if(!empty($userPreference['sourceOfFunding']) && ($fundValue == $userPreference['sourceOfFunding'])) echo 'selected'; ?>><?php echo $fundText; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </span>

              </li>

              <li class="p-l">
                <span>
                  <label class="cursor">Extra Curricular Activities <span class="left-txt"> (500 characters)</span></label>

                  <textarea id="extracurricular_<?php echo $regFormId; ?>" rows="4" cols="50" name="extracurricular" maxlength="500" minlength="45" class="prf-textarea" wrap="physical" placeholder="Achievements other than your studies such as in sports, social work, acting, music etc." ><?php echo $additionalInfo['Extracurricular']; ?></textarea>
                  <div>
                    <div class="regErrorMsg" id="extracurricular_error_<?php echo $regFormId; ?>"></div>
                  </div>      
                </span>          
              </li>
              
              <li class="p-l">
                <span>
                  <label class="cursor">Special Considerations <span class="left-txt"> (500 characters)</span></label>

                  <textarea id="specialConsiderations_<?php echo $regFormId; ?>" rows="4" cols="50" name="specialConsiderations" maxlength="500" minlength="45" class="prf-textarea" wrap="physical" placeholder="Mention any special considerations while deciding your course or university such as wanting to study in AACSB accredited program etc." ><?php echo $additionalInfo['SpecialConsiderations']; ?></textarea>
                  <div>
                    <div class="regErrorMsg" id="specialConsiderations_error_<?php echo $regFormId; ?>"></div>
                  </div>    
                </span>            
              </li>

              <li class="p-l btm">
                <span>
                  <label class="cursor">Preferences <span class="left-txt"> (500 characters)</span></label>

                  <textarea id="preferences_<?php echo $regFormId; ?>" rows="4" cols="50" name="preferences" maxlength="500" minlength="45" class="prf-textarea" wrap="physical" placeholder="Mention your general preferences and constraints e.g. only want to study in London or New York (location preference) etc." ><?php echo $additionalInfo['Preferences']; ?></textarea>
                  <div>
                    <div class="regErrorMsg" id="preferences_error_<?php echo $regFormId; ?>"></div>
                  </div>          
                </span>    
              </li>

            <p class="clr"></p>
           </ul>
          </div>  
            
          
         
          <div class="edu-1">

            <ul class="p-ul">
            
              <li class="p-l no-btm">
                
                <?php if(isset($fields['examTaken'])){

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

                      if(!empty($abroadShortRegistrationData['examsAbroad']) && count($examsAbroad)>0) {
                        $examTaken = 'yes';
                        $abroadExamFlag = 1;
                      }
                      else if(!empty($abroadShortRegistrationData['passport'])  && $abroadShortRegistrationData['bookedExamDate'] !=1) {
                        $examTaken = 'no';
                        $abroadExamFlag = 0;
                      }else if($abroadShortRegistrationData['bookedExamDate'] ==1)
                      {
                        $examTaken = 'bookedExamDate';
                      }
                ?>
                <span class="p-s" id="examTaken_block_<?php echo $regFormId; ?>">
                  <div class="prf-div">
                       
                   <label class="cursor">Have You Given Any Competitive Exam ?</label>
                     <p class="prf-p">
                      <input type="radio" class="prf-inputRadio examTaken_<?php echo $regFormId; ?>" id="examTaken_yes_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); $j('#anotherExamButton').show(); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                      <label for="examTaken_yes_<?php echo $regFormId; ?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>Yes</label>
                     </p>
                     <p class="prf-p" style="margin-left:30px;">
                       <input type="radio" class="prf-inputRadio examTaken_<?php echo $regFormId; ?>" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); $j('#anotherExamButton').hide(); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                       <label for="examTaken_no_<?php echo $regFormId; ?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>No</label>
                    </p>
                    
                    <p class="prf-p" style="margin-left:30px;">
                     <input type="radio" class="prf-inputRadio examTaken_<?php echo $regFormId; ?>" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?>  caption="whether you have given any exam" label="Exam Taken" mandatory="1" regfieldid="examTaken" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeFieldsByExamTaken(this); $j('#anotherExamButton').hide(); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                       <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i><?php echo $fields['bookedExamDate']->getLabel(); ?></label>
                    </p>
                    <input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>

                    <div class="clearfix"></div>
                    <div>
                      <div class="regErrorMsg" id="examTaken_error_<?php echo $regFormId; ?>"></div>
                    </div>   
                  </div> 
                
                </span>
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

                <span class="p-s" id="passport_block_<?php echo $regFormId; ?>" style="<?=$style?>">
                  <div class="prf-div">
                    <label class="cursor">Do You Have A Valid Passport?</label>
                      <span class="prf-p">
                        <input type="radio" class="prf-inputRadio passport_<?php echo $regFormId; ?>" name="passport" id="passport_yes_<?php echo $regFormId; ?>"  value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> caption="whether you have a vaild passport" label="Passport" <?php if(!empty($examTaken) && $examTaken == 'no') { echo 'mandatory="1"'; } ?> regfieldid="passport">
                        <label for="passport_yes_<?php echo $regFormId; ?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>Yes</label>
                    </span>
                    <span class="prf-p" style="margin-left:30px;">
                      <input type="radio" class="prf-inputRadio passport_<?php echo $regFormId; ?>" name="passport" id="passport_no_<?php echo $regFormId; ?>"  value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> caption="whether you have a vaild passport" label="Passport" <?php if(!empty($examTaken) && $examTaken == 'no') { echo 'mandatory="1"'; } ?> regfieldid="passport">
                      <label for="passport_no_<?php echo $regFormId; ?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>No</label>
                    </span>

                    <div class="clearfix"></div>
                    <div>
                        <div id="passport_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                    </div>
                  </div>
                </span> 

                <?php } ?>

              </li>
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

              $count = 0;
              if($examsAbroad !='' && count($examsAbroad) >0){ ?>

                <ul class="p-ul" id="examsAbroad_block_<?php echo $regFormId; ?>">

                  <input type="hidden" name="examTobeDeleted" value='<?php  echo json_encode(array_keys($examsAbroad))?>' />  
          
          <?php   foreach($examsAbroad as $examName => $examScore) {

                    if(in_array($examName,array_keys($examListByName))){
                        $keyOfrange = ((int)$examScore)-1;  
                        $examScore = $examListByName[$examName][$keyOfrange];
                    }
                    $scoreType = '';      
                  ?>

              
                    <li class="p-l examContainer">

                        <div class="examdetailcontainer" id="examDetail_<?= $count?>">

                            <div class="p-s" style="width:50%; margin-right:5px;">
                               <select examid="<?= $count?>" name="exams[]" class="dfl-drp-dwn cursor" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" onchange="manageExamScoreField('<?= $count; ?>','<?php echo $regFormId; ?>');">
                                <option value="">Select Exam</option>
                                <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
                                 <option value="<?= $examDetail['name'];?>"  <?php if(strtolower($examDetail['name'])==strtolower($examName)){ echo 'selected="selected"';$scoreType=$examDetail['scoreType']; }?> maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
                                <?php } ?>
                              </select>

                              <div>
                                <div class="errorMsg examName" style="font-size:12px;" id="<?php echo $count; ?>_examName_error_<?php echo $regFormId; ?>"></div>
                              </div>

                            </div>

                            <div class="p-s" style="width:38%;">

                              <input type="text" class="prf-inpt examScore" style="width:240px;" id="<?= $count;?>_score_<?php echo $regFormId; ?>" <?php if(empty($scoreType)) { echo 'disabled="disabled"';}?> placeholder="Exam Score" exam="<?= $examName; ?>" maxlength="5" minscore="" maxscore="" range="" caption="<?= $examName; ?>" default="Exam Score" value="<?= $examScore; ?>" name="<?= $examName; ?>_score" onblur="" onfocus="">
                              <input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>" name="<?php echo $examName; ?>_scoreType" value="<?= $scoreType;?>">

                              <div>
                                <div class="errorMsg examNameScore" style="font-size:12px;" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
                              </div>

                            </div>

                            <div class="remove-lnk"><a onclick="removeExamBlock(this);" href="javascript:void(0);">Delete</a></div>

                        </div>

                    </li>

                    <p class="clr"></p>

              <?php $count++;
                  } ?>
              
                </ul>    

        <?php } else { ?>

                <ul class="p-ul" id="examsAbroad_block_<?php echo $regFormId; ?>" style="display:none">

                  <li class="p-l examContainer">

                    <div class="examdetailcontainer" id="examDetail_<?= $count?>">

                        <div class="p-s" style="width:50%; margin-right:5px;">

                          <select examid="<?= $count?>" name="exams[]" class="dfl-drp-dwn cursor" id="exam_<?= $count; ?>_<?php echo $regFormId; ?>" onchange="manageExamScoreField('<?= $count; ?>','<?php echo $regFormId; ?>');">
                            <option value="">Select Exam</option>
                            <?php foreach($examsAbroadMasterList as $key=>$examDetail){?>
                             <option value="<?= $examDetail['name'];?>"  <?php if(strtolower($examDetail['name'])==strtolower($examName)){ echo 'selected="selected"';$scoreType=$examDetail['scoreType']; }?> maxscore="<?= $examDetail['maxScore'];?>" minscore="<?= $examDetail['minScore'];?>" range="<?= $examDetail['range'];?>" scoretype="<?= $examDetail['scoreType'];?>"><?= $examDetail['name'];?></option>
                            <?php } ?>
                          </select>

                          <div>
                            <div class="errorMsg examName" style="font-size:12px;" id="<?php echo $count; ?>_examName_error_<?php echo $regFormId; ?>"></div>
                          </div>

                        </div>

                        <div class="p-s" style="width:38%;">

                          <input type="text" class="prf-inpt examScore" style="width:240px;" id="<?= $count;?>_score_<?php echo $regFormId; ?>" <?php if(empty($scoreType)) { echo 'disabled="disabled"';}?> placeholder="Exam Score" exam="" maxlength="5" minscore="" maxscore="" range="" caption="" default="Exam Score" value="" name="" onblur="" onfocus="">
                          <input type="hidden" id="<?php echo $count; ?>_scoreType_<?php echo $regFormId; ?>" name="" value="">

                          <div>
                               <div class="errorMsg examNameScore" style="font-size:12px;" id="<?php echo $count; ?>_score_error_<?php echo $regFormId; ?>"></div>
                          </div>

                        </div>

                        <div class="remove-lnk"><a onclick="removeExamBlock(this);" href="javascript:void(0);">Delete</a></div>

                    </div>                    

                  </li>

                </ul>

        <?php } ?>      
            
      <?php } ?>
            <p class="clr"></p>
          </div>
          
        <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="educationalPreferenceSection" />
        <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='yes' />
        <div>
          <div id="examsAbroad_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
        </div>
        <div class="prf-btns">

          <?php
          if(empty($abroadShortRegistrationData['examsAbroad']) || count($examsAbroad) == 0) {
            $anotherExamButtonStyle = 'display:none';
          } ?>
          <div class="lft-sid" style="<?=$anotherExamButtonStyle?>" id="anotherExamButton">
            <a style="<?=(count($examsAbroad)>=6)?'display:none;':""; ?>" href="javascript:void(0);" onclick="addAnotherExam('<?php echo $regFormId; ?>');" class="font-11 add-another-link"><i class="icons1 ic_addwrk"></i>Add Another Exam Details</a>
          </div>

          <section class="rght-sid">
            <a href="#educationalPreferenceSection" onclick="$j('#educationalPreferenceSection').html(educationalPrefSectionHTML);userActionTracking('educationalPreference', 'cancel', 'userProfile', false);" class="btn-grey">Cancel</a>
            <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('save'); trackEventByGA('UserProfileClick','LINK_SAVE_ABROAD_EDUCATION_PREFERENCE');" class="btn_orngT1">Save</a>
          </section>
          <p class="clr"></p>
        </div>
        <p class="clr"></p>
     </form>
  </div>
</section>

<?php
  $fields = array('whenPlanToGo', 'examTaken', 'examsAbroad', 'passport','abroadDesiredCourse', 'fieldOfInterest', 'desiredGraduationLevel', 'abroadSpecialization', 'destinationCountry','extracurricular','specialConsiderations','preferences');
  $customValidationFields = array('extracurricular'=>'MinMaxLen','specialConsiderations'=>'MinMaxLen','preferences'=>'MinMaxLen');
?>
<script type="text/javascript">

  var registration_context = 'abroadUserSetting';
  var countExams = parseInt(<?php echo $count;?>);
  var abroadregFormId = '<?php echo $regFormId; ?>';
  var abroadExamFlag   = '<?php echo $abroadExamFlag;?>';
  var courseLevel = "<?php echo $desiredCourseDetails['desiredGraduationLevel'];?>";
  var destinationCountry = <?php echo json_encode($locationPreferences['CountryId']);?>;
  var profilePage = true;
  var specializationId = "<?php echo $desiredCourseDetails['abroadSpecialization'];?>";
  var selectedDesiredCourseId = "<?php echo $selectedDesiredCourseId; ?>";

  shikshaUserProfileForm[abroadregFormId] = new ShikshaUserProfileForm(abroadregFormId);
  shikshaUserRegistrationForm[abroadregFormId] = new ShikshaUserRegistrationForm(abroadregFormId);
  shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidationFields); ?>);
  shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode($fields); ?>);
  
  <?php
  if($examsAbroad !='' && count($examsAbroad) >0){ ?>
    for(var i = 0;i< countExams;i++) {
      manageExamScoreField(i,abroadregFormId);
    }
  <?php
  } ?>

  if(selectedDesiredCourseId != '') {
    $j('#fieldOfInterest_'+abroadregFormId).change();

    if (courseLevel!='') {
      $j('[name="desiredGraduationLevel"]').each(function(){
    
        if($j(this).val().toUpperCase()==courseLevel.toUpperCase())
        {
          $j(this).attr('checked',true);
          $j(this).change();
        }
      })
    }
  }
  
  $j(destinationCountry).each(function(key,value){
    $j('[name="destinationCountry[]"]').each(function()  {
      if ($j(this).val()==value.toString()) {
        //THis click is for countries without flag image
        $j(this).parent().click();
        //THis click is for countries with flag image
        $j(this).parent().parent().click();
      }
    })
  
  }); 


</script>

<section class="prf-box-grey">
  <div class="prft-titl">
         <div class="caption">
            <p>Education Preferences</p>
         </div>
         <?php if($publicProfile != true){?>
         <div class="tools">
          <a href="javascript:void(0);" onclick="editUserProfile('educationalPreferenceSection','EDUCATION PREFERENCES');" class="change">Edit</a>
         </div>
         <?php } ?>
    </div>

  <!--profile-tab content-->
  <div class="frm-body">
    <ul>
      <li>
      <div class="prf-edu">
         <span class="edu-bck">
           <i class="icons1 ic_edu"></i><p><?php if($userPreference['ExtraFlag'] == 'studyabroad') { echo 'Study Abroad'; } else { echo 'India'; } ?></p>
           <?php if($publicProfile != true){
                    $x = "{0:'DesiredCourse'}";
                    $x = htmlspecialchars($x);
                    if($privacyDetails['DesiredCourse'] == 'public') {
                                    $priv = 'icons1 ic_view';
                                    $helptext = 'Visibility: public';
                                  }else { 
                                    $priv = 'icons1 ic_none';
                                    $helptext = 'Visibility: private';
                                  }
            ?>
                  <em><a href="javascript:void(0);"><i class="<?php echo $priv; ?>" title="<?php echo $helptext; ?>" onclick="togglePrivacy(this,'<?php echo $userData['userId']; ?>' ,<?php echo $x;?>);" style="float: right;"></i></a></em>
          <?php } ?>
        </span>

        <?php if($userPreference['ExtraFlag'] == 'studyabroad') {  ?>

              <div class="edu-dtls btm">

              <?php if(!empty($desiredCourseDetails['courseName'])) { ?>  
                <p>Interested in studying<span>
                  <?php 
                  if($desiredCourseDetails['categoryName'] == 'All') {
                    echo $desiredCourseDetails['courseName'];
                    if(!empty($desiredCourseDetails['subCatgoryName'])) { 
                      echo ' ('.$desiredCourseDetails['subCatgoryName'].')'; 
                    } else {
                      echo ' (All Specializations)'; 
                    }
                  } else {
                    echo $desiredCourseDetails['categoryName'];
                    if(!empty($desiredCourseDetails['subCatgoryName'])) { 
                      echo ' - '.$desiredCourseDetails['subCatgoryName']; 
                    } else {
                      echo ' - All Specializations'; 
                    }
                    echo ' ('.$desiredCourseDetails['courseName'].')';
                  }
                  ?> 

                  <?php  ?> <?php if(!empty($countryNames)) { ?>in <?php echo implode(", ",$countryNames); } ?></span></p>
              <?php } else if(!empty($countryNames)) { ?>
                <p>Interested in studying from<span><?php echo implode(", ",$countryNames); ?></span></p>
              <?php } ?>

                <p>Looking for admission in<span><?php echo $whenPlanToGo;?></span></p>
                <?php if($personalInfo['Passport'] ==  'yes') { ?>
                  <p>Has a<span>valid passport</span></p>
                <?php } else { ?>
                  <p>Don't have a valid passport</p>
                <?php } ?>

                <?php if((!empty($budget)) && (!empty($sourceOfFunding))) { ?>

                        <p>Has a<span>budget of <?php echo $budget;?> funded by <?php echo $sourceOfFunding;?></span></p>

                <?php } else { 

                        if(!empty($budget)) { ?>
                          
                          <p>Has a<span> budget of <?php echo $budget;?></span></p>

                      <?php } else if(!empty($sourceOfFunding)) { ?>

                          <p>Budget funded by<span><?php echo $sourceOfFunding;?></span></p>

                      <?php } ?>

                <?php } ?>

              </div>

              <?php if((!empty($additionalInfo['Extracurricular'])) || (!empty($additionalInfo['SpecialConsiderations'])) || (!empty($additionalInfo['Preferences']))) { ?>

                <div class="edu-dtls btm">
                  
                  <?php if(!empty($additionalInfo['Extracurricular'])) { ?>
                    <p>Extra Curricular Activities: <span><?php echo $additionalInfo['Extracurricular'];?></span></p>
                  <?php } ?>

                  <?php if(!empty($additionalInfo['SpecialConsiderations'])) { ?>
                    <p>Special Consideration: <span><?php echo $additionalInfo['SpecialConsiderations'];?></span></p>
                  <?php } ?>

                  <?php if(!empty($additionalInfo['Preferences'])) { ?>
                    <p>Preferences: <span><?php echo $additionalInfo['Preferences'];?></span></p>
                  <?php } ?>

                </div>
                
              <?php } ?>

        <?php } else { 

                if(!empty($domesticInterestDetails)) {
          ?>
                      <div class="edu-dtls btm" id="domesticInterestDetails">
                        <!-- <p>Interested in studying:</p> -->
                        <?php if(!empty($domesticInterestDetails)){ ?>
                          <p>Stream: <?php echo $domesticInterestDetails['stream']; ?></p>
                        <?php } ?>

                        <?php if(!empty($domesticInterestDetails['subStreamSpec'])){ ?>
                        <div>Specialization<?php if(count($domesticInterestDetails['subStreamSpec']) > 1){ echo 's'; } ?>: <div class="ml20 lh22">
                         <?php 

                          $unMappedSpecs = !empty($domesticInterestDetails['subStreamSpec']['ungrouped'])?$domesticInterestDetails['subStreamSpec']['ungrouped']:array();

                          $subStreamSpec = array();
                          $unMappedSubstreams = array();
                          unset($domesticInterestDetails['subStreamSpec']['ungrouped']);

                          foreach($domesticInterestDetails['subStreamSpec'] as $id=>$data){
                            $temp = '';
                            if(!empty($data['specializations'])){
                              echo $data['name'].' - '.implode(', ', $data['specializations']).'<br>';
                            }else{
                              $unMappedSubstreams[] = $data['name'];
                            }
                          } 

                          $unMappedSpecs = array_merge($unMappedSpecs, $unMappedSubstreams);                      

                          if(!empty($unMappedSpecs[0])){                        
                            asort($unMappedSpecs);
                            echo 'Others - '.implode(', ', $unMappedSpecs);
                          }
                         ?>
                         </div>
                        </div>
                    <?php } ?>

                    <?php if(!empty($domesticInterestDetails['baseCourses'])){ 
                        asort($domesticInterestDetails['baseCourses']);
                      ?>
                      <p>Course<?php if(count($domesticInterestDetails['baseCourses']) > 1){ echo 's'; } ?>: <?php echo implode(', ', $domesticInterestDetails['baseCourses']); ?></p>
                    <?php } ?>

                    <?php if(!empty($domesticInterestDetails['educationMode'])){
                        global $educationTypePriorities;
                     ?>

                      <div>Education Type<?php if(count($domesticInterestDetails['educationMode']) > 1){ echo 's'; } ?>: 
                          <?php 
                          $stringValue = '';
                          foreach ($domesticInterestDetails['educationMode'] as $key => $value) {
                            $tmpModeArr[$key] = $value['name'];
                            if(!empty($value['children'])){
                              foreach ($value['children'] as $id => $name) {
                                $tmpModeArr[$id] = $name;
                              }
                            }
                          }

                          $eduType = array_intersect(array_keys($educationTypePriorities), array_keys($tmpModeArr));

                          foreach($eduType as $key=>$modeId){
                              // asort($modeData['children']);
                              $stringValue .= $educationTypePriorities[$modeId].', ';
                          }
                          $stringValue = rtrim($stringValue,", ");
                          echo '<div class="ml20 lh22">'.$stringValue.'</div>';
                          ?>
                      </div>
                    <?php } ?>
                  </div>
        <?php   } 
              }
        ?>

      </div> 
    </li></ul>

      <?php if(!empty($competitiveExam['Name']) && 0) { ?>
<ul><li>
      <div class="prf-edu">
        <div class="edu-dtls">
          <p>Exam(s) Taken:<span>
            <?php  if($userPreference['ExtraFlag'] == 'studyabroad') {  ?>

                    <?php for($i=0; $i<count($competitiveExam['Name']); $i++) { ?>
                      <?php echo $competitiveExam['Name'][$i];?> (<?php if($competitiveExam['Name'][$i] == 'IELTS' || $competitiveExam['Name'][$i] == 'CAE'){ echo $competitiveExam['Marks'][$i]; } else { echo floor($competitiveExam['Marks'][$i]); } ?>)<?php if($i != (count($competitiveExam['Name'])-1)) echo ', '; ?> 
                    <?php } ?>

            <?php } else { ?>

                    <?php 
                    $examKeys = array_keys($examNames);
                    
                    for($i=0; $i<count($competitiveExam['Name']); $i++) { ?>
                    
                      <?php
                      if(in_array($competitiveExam['Name'][$i],$examKeys)) { 
                        echo $examNames[$competitiveExam['Name'][$i]];
                      } else {
                        echo $competitiveExam['Name'][$i];
                      }
                      ?>
                      <?php if($competitiveExam['MarksType'][$i] && $competitiveExam['Marks'][$i] != 0) { ?> (<?php echo $competitiveExam['Marks'][$i];?>) <?php } ?><?php if($i != (count($competitiveExam['Name'])-1)) echo ', '; ?> 
                    <?php } ?>

            <?php } ?>
          </span></p>
        </div>
      </div> 
</li></ul>
      <?php }  ?>
      <div class="prf-btns">
        <?php if($publicProfile != true){?>
          <div class="lft-sid">
             <a href="javascript:void(0);" onclick="editUserProfile('educationalPreferenceSection','EDUCATION PREFERENCES');"><i class="icons1 ic_addwrk"></i>Add Educational Preferences</a>
           </div>
          <?php } ?>
     </div>
      <p class="clr"></p>
  </div>
</section>

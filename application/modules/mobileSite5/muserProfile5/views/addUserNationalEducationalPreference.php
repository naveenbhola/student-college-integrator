<section class="workexp-cont clearfix">
  <article class="workexp-box">
     <div class="dtls">
        
        <ul class="wrkexp-ul">
          <li>
          
            <div class="text-show" style="display: none">
              <label>Your Desired Study Location</label>
              <div class="desire-cntry">
                 <input type="radio" class="radio-custom" name="radio-group1" name="yes" id="04" checked="checked"/> 
                 <label for="04" class="radio-custom-label1" id="nationalPreference"><i class="profile-sprite i_chck"></i>India</label>
          
                 <!--<input type="radio" class="radio-custom" name="radio-group1" name="yes" id="05"/> 
                 <label for="05" class="radio-custom-label1 lft-space" id="abroadPreference"><i class="profile-sprite i_chck"></i>Abroad</label>-->
              </div>
            </div>
          </li> 

          <?php echo Modules::run('registration/RegistrationForms/LDB','nationalPreference','userProfile', array('regFormId'=>$regFormId, 'showInterestPrefilled'=>'yes')); ?>

        </ul>

      </div>

    </article>
</section>
<input type="button" class="common-btn bottom-fix" value="save" id="saveEducationPreference"/>
<input type="hidden" name="isMobileProfile" id="isMobileProfile_<?=$regFormId?>" value="yes" />
<input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="educationalPreferenceSection" />
<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='no' />
<input type='hidden' name='regFormId' value='<?php echo $regFormId;?>' />
<input type="hidden" name="tracking_keyid" id="tracking_keyid_<?php echo $regFormId; ?>" value="696" />

<?php 
/* $desiredCoursesData = array('desiredCourse'=>$desiredCourseDetails['desiredCourseId']);
  $fieldOfInterest = array();
  foreach($fields['fieldOfInterest']->getValues() as $categoryId => $categoryName) { 
    $fieldOfInterest[$categoryId] = $categoryName;
  } 

  $desiredCourse = array();
  $categoryId = $desiredCourseDetails['categoryId']; */
?>

<!-- <input type="hidden" id="preSelectedDesiredCourse_<?php echo $regFormId; ?>" value='<?php echo $desiredCourseDetails["desiredCourseId"];?>' />
<input type="hidden" name="examTobeDeleted" value="<?php echo implode(",",$competitiveExam['Name']);?>"/>
<input type="hidden" name="PrefId" value="<?php echo $desiredCourseDetails['PrefId'];?>"/> -->
<?php
/*  $fields = array('fieldOfInterest', 'desiredCourse');
  $countCompetitiveExam = 1;
  if($competitiveExam['Name']) { 
    $countCompetitiveExam = count($competitiveExam['Name']);
  } 
    $fields = array('fieldOfInterest', 'desiredCourse','exams');

  $examDivIdsExist = array();
  for($i=1;$i<=$countCompetitiveExam;$i++) {
    $examDivIdsExist[] = $i;
  }
  $customValidationFields = array('exams'=>'AllExamScoresProfile'); */
?>

<script type="text/javascript">
  
  var nationalExamBlock = 0;
  var shikshaUserProfileForm = {};
  var shikshaUserRegistrationForm = {};
  var preSelectedCompetitiveExam = '';

  shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
  shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');

  <?php if(!empty($customFormData['customFields']['stream']['value'])){ ?>
    userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($('#stream_<?php echo $regFormId; ?>'));
  <?php } ?>


  <?php if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
    userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
  <?php } ?>

  
  /* var nationalExamBlock = 0;
  var shikshaUserProfileForm = {};
  var shikshaUserRegistrationForm = {};
  var totalNationalExamCount = '<?php echo (count($examNames)-1); ?>';

  shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
  shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
  shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidationFields); ?>);
  shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode($fields); ?>);
  // examDivIdsExist = <?php echo json_encode($examDivIdsExist);?>;

  var preSelectedCompetitiveExam = '';
  <?php if(!empty($competitiveExam)) { ?>
    preSelectedCompetitiveExam = '<?php echo json_encode($competitiveExam);?>';
  <?php } ?>
  
  <?php if(!empty($fieldOfInterest[$desiredCourseDetails['categoryId']])) { ?>
    shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateDesiredCourses(<?php echo json_encode($desiredCoursesData); ?>);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].addUserExams('<?php echo $desiredCourseDetails["desiredCourseId"];?>');
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].nationalExamBlock = '<?php echo $countCompetitiveExam;?>';
  <?php }else{ ?>
          $('#addNationalExam').hide();
    <?php } ?>
*/
</script>
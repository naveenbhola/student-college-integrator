<?php
$regFormId          = random_string('alnum', 6);
$desiredCoursesData = array('desiredCourse' =>
  $desiredCourseDetails['desiredCourseId']);
  ?>
  <section class="prf-box-grey">
    <div class="prft-titl">
      <div class="caption">
        <p>
          EDUCATION PREFERENCES
        </p>
      </div>
    </div>
    <!--profile-tab content-->
    <div class="frm-bdy">
    <form class="prf-form" id="registrationForm_<?=$regFormId?>" regFormId="<?php echo $regFormId; ?>"  method="post" action="/registration/Registration/updateUser">
        <?php
          $CI = & get_instance();
          $CI->load->library('security');
          $CI->security->setCSRFToken();
        ?>
        <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
        <div class="edu" style="display: none;"> 
          <ul class="p-ul">
            <li class="p-1">
              <span>
                <label class="cursor">Your Desired Study Location</label>
                <span class="prf-r">
                  <input type="radio" class="prf-inputRadio" checked="checked">
                  <label class="prf-radio"> <i class="icons ic_radiodisable1"></i>India</label>
                </span>
                <!--<span class="prf-r" style="margin-left:30px;">
                  <input type="radio" class="prf-inputRadio">
                  <label onclick="editUserProfile('educationalPreferenceSection','EDUCATION PREFERENCES', 'studyabroad');" class="prf-radio"> <i class="icons ic_radiodisable1"></i>Abroad</label>
                </span>-->
              </span>
            </li>

          </ul>
        </div>
        <div class="edu">
          <?php echo Modules::run('registration/RegistrationForms/LDB','nationalPreference','userProfile', array('regFormId'=>$regFormId, 'showInterestPrefilled'=>'yes')); ?>
        </div>

        <div class="prf-btns">
          <div class="lft-sid" id="addOtherExam" style="display:none">
            <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].addAnotherExam();">
              <i class="icons1 ic_addwrk">
              </i>
              Add Another Exam Details
            </a>
          </div>
          <section class="rght-sid">
            <a class="btn-grey" href="#educationalPreferenceSection" onclick="$j('#educationalPreferenceSection').html(educationalPrefSectionHTML);userActionTracking('educationalPreference', 'cancel', 'userProfile', false);">
              Cancel
            </a>
            <a class="btn_orngT1" href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('save'); trackEventByGA('UserProfileClick','LINK_SAVE_NATIONAL_EDUCATION_PREFERENCE');">
              Save
            </a>
          </section>
        </div>
        <p class="clr">
          <input type="hidden" name="sectionType" id="sectionType_<?php echo $regFormId; ?>" value="educationalPreferenceSection">
          <input type="hidden" name="siteType" value="national" id="siteType_<?php echo $regFormId; ?>">
          <input type="hidden" name="isProfilePage" value="yes" id="isProfilePage_<?php echo $regFormId; ?>">
          <input type="hidden" name="context" value="unifiedProfile" id="context_<?php echo $regFormId; ?>">
          <input type="hidden" name="tracking_keyid" id="tracking_keyid_<?php echo $regFormId; ?>" value="695" />
        </p>
      </form>
      <p class="clr">
      </p>
    </div>
  </section>
  <?php
  $fields               = array('fieldOfInterest', 'desiredCourse');
  $countCompetitiveExam = 1;
  if ($competitiveExam['Name']) {
    $countCompetitiveExam = count($competitiveExam['Name']);
    $fields               = array('fieldOfInterest', 'desiredCourse', 'exams');
  }

  $examDivIdsExist = array();
  for ($i = 1; $i <= $countCompetitiveExam; $i++) {
    $examDivIdsExist[] = $i;
  }
  $customValidationFields = array('exams' =>
    'AllExamScoresProfile');
    ?>
    <script type="text/javascript">
      shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
      shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
      shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidationFields); ?>);
      shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode($fields); ?>);

      examDivIdsExist = <?php echo json_encode($examDivIdsExist); ?>;

      var preSelectedCompetitiveExam = '';
    </script>

    <?php $this->load->view('registration/common/jsObjectInitialization', array('regFormId'=>$regFormId)); ?>
    <script>
      <?php if(!empty($customFormData['customFields']['stream']['value'])){ ?>
        userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($j('#stream_<?php echo $regFormId; ?>'));
        <?php } ?>
        initiateSimpleSlider($j);
        $j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });

        <?php if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
          userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
          <?php } ?>

        </script>

<?php 
$regFormId = random_string('alnum', 6);
$personalInfo['FirstName'] = str_replace('"' ,'&quot;',$personalInfo['FirstName']);
$personalInfo['LastName'] = str_replace('"' ,'&quot;',$personalInfo['LastName']);
?>
<section class="prf-box-grey" >
    <div class="prft-titl">
         <div class="caption">
            <p>PERSONAL INFORMATION</p>
         </div>
    </div>

 <!--profile-tab content-->
  <div class="frm-bdy">
     <form class="prf-form cursor" id="registrationForm_<?=$regFormId?>" action="/userProfile/UserProfileController/updateUserProfile">
        <div class="prf-d">
           <ul class="p-ul">
              <li class="p-l">
                <span class="p-s" id="firstName_block_<?php echo $regFormId; ?>" visible="Yes">
                   <label class="cursor">First Name <i>*</i></label>
                   <input type="text" name="firstName" caption="your first name" id="firstName_<?php echo $regFormId; ?>" label="first name" mandatory="1" regFieldId="firstName" class="prf-inpt" value="<?php if(!empty($personalInfo['FirstName'])){echo $personalInfo['FirstName']; }else{ echo 'First Name'; }?>" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" minlength="1" maxlength="50" default="First Name"/>
                   <div>
                        <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </span>

                 <span class="p-s" id="lastName_block_<?php echo $regFormId; ?>" visible="Yes">
                   <label class="cursor">Last Name <i>*</i></label>
                   <input type="text" class="prf-inpt" caption="your last name" id = "lastName_<?php echo $regFormId; ?>" label="last name" mandatory="1" name="lastName" regFieldId="lastName" minlength="1" maxlength="50" default="Last Name" profanity="1" value="<?php if(!empty($personalInfo['LastName'])){ echo $personalInfo['LastName']; }else{ echo 'Last Name'; }?>" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                   <div>
                      <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                  </div>
                </span>
              </li>

              <li class="p-l">
                <span class="p-s" >
                   <label class="cursor">Email ID <i>*</i></label>
						<p class="accnt-setting-mail-info"><?php echo $personalInfo['Email']; ?></p>
                           <input type="hidden" name="email" id="email_<?php echo $regFormId;?>" value="<?php echo $personalInfo['Email']; ?>" />
                      <!-- <input type="email" class="prf-inpt disable-field" disabled="disabled"/> -->
                </span>

                 <span class="p-s">
                   <label class="cursor">Student Email ID</label>
                   <input type="text" name="studentEmail"  id="studentEmail_<?php echo $regFormId; ?>" maxlength="125" class="prf-inpt" value="<?php if(!empty($additionalInfo['StudentEmail'])){ echo $additionalInfo['StudentEmail']; } ?>" placeholder="Email ID provided by your college" />
                   <div>
                      <div class="regErrorMsg" id="studentEmail_error_<?php echo $regFormId; ?>"></div>
                  </div>
                </span>
              </li>


              <li class="p-l">
                <span class="p-s" id="isdCode_block_<?php echo $regFormId; ?>" visible="Yes" >
                   <label class="cursor">Country</label>
                     <?php  
                          $ISDCodeValues = $fields['isdCode']->getValues(); 
                          $submittedValue = $personalInfo['ISDCode'].'-'.$personalInfo['Country'];
                          
                     ?>
                   <select class="dfl-drp-dwn cursor" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].changeFormOnCountryChange(this.value); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value)" name="isdCode" >
                          <!-- <i class="icons ic_dropdownsumo"></i>       -->
                          <?php foreach($ISDCodeValues as $key=>$value){ ?>
                                  <option value="<?php echo $key; ?>" <?php if($submittedValue == $key){echo 'selected'; } ?>> <?php echo $value; ?> </option>
                           <?php } ?>
                  </select>
                  <div>
                      <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                  </div>
                </span>

                <span class="p-s" id="mobile_block_<?php echo $regFormId; ?>" visible="Yes" >
                   <label class="cursor">Mobile No. <i>*</i></label>
                   <input type="text" class="prf-inpt" mandatory="1" id = "mobile_<?php echo $regFormId; ?>" caption="your mobile number"  name = "mobile" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" value="<?php if(!empty($personalInfo['Mobile'])) {echo $personalInfo['Mobile']; }else{ echo 'Mobile No.';} ?>" maxlength="10" regFieldId="mobile" default="Mobile No."/>
                   <div>
                      <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                  </div>  
                </span>
              </li>

              <li class="p-l" id="userCity_block_<?php echo $regFormId; ?>"> 
                <?php $this->load->view('userProfile/userCityBlock', array('regFormId'=>$regFormId)); ?>
              </li> 

            <li class="p-l">
              <span class="p-s">
                <?php
                    $DateOfBirth = get_object_vars($personalInfo['DateOfBirth']);
                    $DateOfBirth = explode(' ',$DateOfBirth['date']);
                    if($DateOfBirth[0] == '-0001-11-30'){
                      $DateOfBirth[0] = '';
                    }else{
                      if(is_object($personalInfo['DateOfBirth'])){
                          $DateOfBirth[0] = $personalInfo['DateOfBirth']->format('d-F-Y');    
                      }else{
                          $DateOfBirth[0] = '';  
                      }
                    }
                 ?>
                 <label class="cursor">Date Of Birth</label>
                 <input type="text" value="<?php if(!empty($DateOfBirth[0])){ echo $DateOfBirth[0]; } ?>" class="prf-inpt validateCls hasDatepicker" placeholder="DD-MM-YYYY" name="dob" id="dob_<?php echo $regFormId; ?>" style="width: 253px;" readonly/>
                  <a href="javascript:void(0);" title="Calendar" name="dob_img_<?php echo $regFormId; ?>" id="dob_img_<?php echo $regFormId; ?>" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('dob_<?php echo $regFormId; ?>'),'dob_img_<?php echo $regFormId; ?>','dd-mm-yyyy');return false;" >
                      <i class="icons1 ic_cal"></i>
                  </a>
                  <!-- <input type="text" class="prf-inpt validateCls hasDatepicker" value="< ?php if(!empty($DateOfBirth[0])){ echo $DateOfBirth[0]; } ?>" id="dob_< ?php echo $regFormId; ?>" readonly="readonly" name="dob" placeholder="yyyy-mm-dd" onclick="shikshaUserProfileForm['< ?php echo $regFormId; ?>'].selectDOB();" /><i class="icons1 ic_cal"></i> -->
                  <div>
                      <div class="regErrorMsg" id="dob_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>
            </li>

            <li class="p-l ">
              <span>
                 <label class="cursor">About Me <span class="left-txt">(50 characters)</span></label>
                 <input type="text" name="aboutMe" maxlength="50" minlength="5" class="prf-inp" placeholder="Your brief introduction" id="aboutMe_<?php echo $regFormId; ?>" regFieldId="aboutMe" default="Your brief introduction" value="<?php if(!empty($additionalInfo['AboutMe'])){echo $additionalInfo['AboutMe'];}else{ echo 'Your brief introduction'; } ?>" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);"/>
                 <div>
                      <div class="regErrorMsg" id="aboutMe_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>

            </li>

             <li class="p-l <?php if($userLevel > 10){ echo 'btm' ; }?>">
              <span>
                 <label class="cursor">Bio <span class="left-txt">(500 characters)</span></label>
                 <textarea id="bio_<?php echo $regFormId; ?>" rows="4" cols="50" name="bio" maxlength="500" minlength="45" class="prf-textarea" wrap="physical" placeholder="Detailed information about you. You may write about your achievements in education, work and other areas" default="Detailed information about you. You may write about your achievements in education, work and other areas" ><?php echo $additionalInfo['Bio']; ?></textarea>
                 <div>
                      <div class="regErrorMsg" id="bio_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>

            </li>
               <?php //} ?>
         </ul>
         <p class="clr"></p>
      </div>
      
          <?php if($userLevel > 10){ ?>

          <div class="social-col">
            <ul>
             <h2 class="social-titl">Social Profiles</h2>

            <li class="p-l">
              <span class="p-s">
                 <label class="cursor">Facebook </label>
                 <input type="text" class="prf-inpt" name="facebookId" value="<?php if(!empty($socialInfo['FacebookId'])){ echo $socialInfo['FacebookId'];} ?>" id="facebookId_<?php echo $regFormId; ?>" placeholder="Facebook URL"  />
                 <div>
                      <div class="regErrorMsg" id="facebookId_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>

               <span class="p-s">
                 <label class="cursor">Twitter </label>
                 <input type="text" class="prf-inpt" name="twitterId" value="<?php if(!empty($socialInfo['TwitterId'])){ echo $socialInfo['TwitterId'];} ?>" id="twitterID_<?php echo $regFormId; ?>" placeholder="Twitter URL" />
                 <div>
                      <div class="regErrorMsg" id="twitterID_error_<?php echo $regFormId; ?>"></div>
                  </div>

              </span>
              <p class="clr"></p>
            </li>

            <li class="p-l">
              <span class="p-s">
                 <label class="cursor">LinkedIn </label>
                 <input type="text" class="prf-inpt" name="linkedinId" value="<?php if(!empty($socialInfo['LinkedinId'])){ echo $socialInfo['LinkedinId'];} ?>" id="linkedinId_<?php echo $regFormId; ?>" placeholder="Linkedin URL" />
                 <div>
                      <div class="regErrorMsg" id="linkedinId_error_<?php echo $regFormId; ?>"></div>
                  </div>

              </span>

               <span class="p-s">
                 <label class="cursor">YouTube </label>
                 <input type="text" class="prf-inpt" name="youtubeId" value="<?php if(!empty($socialInfo['YoutubeId'])){ echo $socialInfo['YoutubeId'];} ?>" id="youtubeId_<?php echo $regFormId; ?>" placeholder="Youtube URL" />
                 <div>
                      <div class="regErrorMsg" id="youtubeId_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>
              <p class="clr"></p>
            </li>

            <li class="p-l">
              <span class="p-s">
                 <label class="cursor">Website </label>
                 <input type="text" class="prf-inpt" name="personalURL" value="<?php if(!empty($socialInfo['PersonalURL'])){ echo $socialInfo['PersonalURL'];} ?>" id="personalURL_<?php echo $regFormId; ?>" placeholder="Personal URL"  />
                  <div>
                      <div class="regErrorMsg" id="personalURL_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </span>
              <p class="clr"></p>
            </li>
            </ul>
        <p class="clr"></p>
      </div>
      <?php } ?>
      
        <div class="prf-btns">
            <section class="rght-sid">
              <input type="hidden" id="isStudyAbroad" value="no" />
              <input type="hidden" name="isStudyAbroad" value="<?php echo $isStudyAbroadFlag; ?>" />
              <input type="hidden" name="abroadSpecialization" value="<?php echo $abroadSpecializationFlag; ?>" />
              <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
              <input type="hidden" id="userVerification_<?php echo $regFormId; ?>" name="userVerification" value=no />
              <input type="hidden" name="action" value='save' />
              <input type="hidden" name="context" id="context_<?php echo $regFormId; ?>" value='unifiedProfile' />
              <input type="hidden" name="tracking_keyid" id="tracking_keyid_<?php echo $regFormId; ?>" value="695" />
              
             <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('cancel');" class="btn-grey">Cancel</a>
             <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?php echo $regFormId; ?>'].submitForm('save'); trackEventByGA('UserProfileClick','LINK_SAVE_PERSONAL_INFORMATION');" class="btn_orngT1">Save</a>
            </section>
             <p class="clr"></p>
       </div>
       <p class="clr"></p>
       <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="personalInformationSection" />
   </form>
</div>
</section>
<?php
 $MandatoryFields = array(
                       "mobile" => "Mobile",
                       "firstName" => "DisplayName",
                       "lastName" => "DisplayName",
                       'residenceCityLocality' => 'Mandatory'
                     );
?>
<script type="text/javascript">
    var customVars = {"email":"Email","mobile":"Mobile","firstName":"DisplayName","lastName":"DisplayName","residenceCityLocality":"Mandatory"};
    var shikshaUserProfileForm = {};
    var shikshaUserRegistrationForm = {};

   shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(customVars);


   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($MandatoryFields)); ?>);

    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($MandatoryFields); ?>);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($MandatoryFields)); ?>);
    var STUDY_ABROAD_NEW_REGISTRATION = 0;

    <?php if($submittedValue != INDIA_ISD_CODE){ ?>
        $j('#isdCode_<?php echo $regFormId; ?>').trigger('change');
      <?php }else{ ?>
          $j('#residenceCityLocality_<?php echo $regFormId; ?>').trigger('change');
        <?php } ?>
</script>

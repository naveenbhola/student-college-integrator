<?php 
    $personalInfo['FirstName'] = str_replace('"' ,'&quot;',$personalInfo['FirstName']);
    $personalInfo['LastName'] = str_replace('"' ,'&quot;',$personalInfo['LastName']);
    $UserResidentCity = (isset($personalInfo['Locality']) && !empty($personalInfo['Locality'])) ? $personalInfo['Locality']: 0;
?>

<form class="prf-form cursor" id="registrationForm_<?=$regFormId?>" action="/muserProfile5/UserProfile/updateUserProfile">
<div class="userpage-container">
    <div class="page-heading header-fix">
      <a href="#" class="p-title" id="p-title-id">Add/Edit Personal Information</a>
        <a href="#" class="cls-head flRt" data-rel="back">&times;</a>
    </div>

    <div class="workexp-dtls">
    <!--Profile Info-->
    <section class="workexp-cont clearfix">

        <article class="workexp-box">
            <div class="dtls">
                <ul class="wrkexp-ul">

                    <li id="firstName_block_<?php echo $regFormId; ?>" visible="Yes">
                        <div class="text-show <?php if(!empty($personalInfo['FirstName'])){ echo 'filled1'; }?>">
                            <label class="form-label" id="label">First Name</label>
                            <input type="text" id="firstName_<?php echo $regFormId; ?>" name="firstName" class="user-txt-flds blurEvent" value="<?php if(!empty($personalInfo['FirstName'])){echo $personalInfo['FirstName']; } ?>" minlength="1" maxlength="50" default="First Name" label="first name" mandatory="1" regFieldId="firstName" caption="your first name"/>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </li>
                    <li id="lastName_block_<?php echo $regFormId; ?>" visible="Yes">
                        <div class="text-show <?php if(!empty($personalInfo['LastName'])){ echo 'filled1'; }?>">
                            <label class="form-label" id="label">Last Name</label>
                             <input type="text" class="user-txt-flds blurEvent" caption="your last name" id = "lastName_<?php echo $regFormId; ?>" label="last name" mandatory="1" name="lastName" regFieldId="lastName" minlength="1" maxlength="50" default="Last Name" value="<?php if(!empty($personalInfo['LastName'])){ echo $personalInfo['LastName']; } ?>" />
                        </div>
                        <div>
                            <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </li>
                    <li>
                        <div class="text-show <?php if(!empty($personalInfo['Email'])){ echo 'filled1'; }?>">
                            <label class="form-label" id="label">Email ID</label>
                            <input type="text" id="email_<?php echo $regFormId;?>" name="email" class="user-txt-flds"  value="<?php echo $personalInfo['Email']; ?>" readonly style="color: rgb(158, 153, 153);"/>
                        </div>
                    </li>
                    <li>
                        <div class="text-show <?php if(!empty($additionalInfo['StudentEmail'])){ echo 'filled1'; } ?>">
                            <label class="form-label">Student Email ID</label>
                              <input type="text" name="studentEmail" id="studentEmail_<?php echo $regFormId; ?>" class="user-txt-flds" maxlength="125" value="<?php if(!empty($additionalInfo['StudentEmail'])){ echo $additionalInfo['StudentEmail']; } ?>" />
                        </div>
                        <div>
                            <div class="regErrorMsg" id="studentEmail_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </li>
                    <li >
                        <?php  
                          $ISDCodeValues = $fields['isdCode']->getValues(); 
                          $submittedValue = $personalInfo['ISDCode'].'-'.$personalInfo['Country'];
                          if(empty($personalInfo['Country']) || empty($personalInfo['ISDCode'])){
                            $submittedValue = '91-2';
                          }
                          
                        ?>
                        <div class="marks-sec" id="isdCode_block_<?php echo $regFormId; ?>" visible="Yes">
                            <div class="text-show <?php if(!empty($submittedValue)){ echo 'filled1'; } ?>">
                                <label class="form-label" id="label">Country Code</label>
                                <!-- <input type="text" id="ename" name="ename" class="user-txt-flds" /> -->
                                <input class="user-txt-flds ssLayer" id="isdCode_<?php echo $regFormId; ?>_input" readonly="readonly" type="text" value="<?php if(!empty($submittedValue)){ echo $ISDCodeValues[$submittedValue]; } ?>">
                                <a aria-expanded="false" aria-haspopup="true" aria-owns="myPopup" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup"></a>
                            </div>
                            <div class="select-Class">
                                <select class="select-hide" id="isdCode_<?php echo $regFormId; ?>" onchange=" shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeMobileFieldMaxlength(this.value); shikshaUserProfileForm['<?php echo $regFormId; ?>'].getLocationFields(this.value)" name="isdCode">
                                     <?php foreach($ISDCodeValues as $key=>$value){ ?>
                                      <option value="<?php echo $key; ?>" <?php if($submittedValue == $key){echo 'selected'; } ?>> <?php echo $value; ?> </option>
                                   <?php } ?>
                                </select>
                            </div>

                            <div>
                                <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                            </div>
                       </div>

                       <div class="marks-col" id="mobile_block_<?php echo $regFormId; ?>" visible="Yes">
                             <div class="text-show <?php if(!empty($personalInfo['Mobile'])) {echo 'filled1'; } ?>">
                                <label class="form-label" id="label">Mobile Number</label>
                                 <input type="integer" class="user-txt-flds blurEvent" mandatory="1" id = "mobile_<?php echo $regFormId; ?>" caption="your mobile number"  name="mobile" value="<?php if(!empty($personalInfo['Mobile'])) {echo $personalInfo['Mobile']; } ?>" maxlength="10" regFieldId="mobile" default="Mobile No."/>

                            </div>
                            <div>
                                <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                            </div>
                       </div>
                    </li>

                    <li id="userCity_block_<?php echo $regFormId; ?>">

                        <div class="text-show <?php if(!empty($personalInfo['City'])) {echo 'filled1'; } ?>">
                            <label class="form-label">Residence City</label>
                            <!-- <input type="text" name="ename" class="user-txt-flds" /> -->
                            <input class="user-txt-flds ssLayerWOG" id="residenceCityLocality_<?php echo $regFormId; ?>_input" readonly="readonly" type="text" value="<?php if(!empty($personalInfo['City'])){ echo $values[$personalInfo['City']]; } ?>">
                                <a aria-expanded="false" aria-haspopup="true" aria-owns="myPopup" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup"></a>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div class="select-Class" id="residenceCityLocality_block_<?php echo $regFormId; ?>" visible="Yes">
                            <select class="select-hide" caption="city of residence" label="Residence Location" mandatory="1" regfieldid="residenceCityLocality" name="residenceCityLocality" mandatory="1" id="residenceCityLocality_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].getUserLocalities(this.value, '<?php echo $UserResidentCity; ?>')" >
                                  <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('residenceCityLocality'=>$personalInfo['City'], 'isUnifiedProfile'=>'YES')); ?>
                              </select>
                        </div>
                    </li>

                    <li id="residenceLocality_block_<?php echo $regFormId; ?>">
                        <div class="text-show <?php if(!empty($personalInfo['Locality'])) {echo 'filled1'; } ?>" >
                            <label class="form-label">Resident Locality</label>
                            <input class="user-txt-flds ssLayerWOG" id="residenceLocality_<?php echo $regFormId; ?>_input" readonly="readonly" type="text">
                                <a aria-expanded="false" aria-haspopup="true" aria-owns="myPopup" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup"></a>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div class="select-Class" style="display:none;" visible="Yes">
                            <select class="select-hide" caption="locality of residence" name="residenceLocality" id="residenceLocality_<?php echo $regFormId; ?>">
                                  <option value="-1" >Locality </option>
                            </select>
                        </div>
                    </li>
                    <li>
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
                        <div class="text-show <?php if(!empty($DateOfBirth[0])){echo 'filled1'; } ?>">
                            <label class="form-label">Date Of Birth</label>
                            <i class="profile-sprite ic_calendar" ></a></i>
                            <input type="text" name="dob" id="dob_<?php echo $regFormId; ?>" class="user-txt-flds dobFocus" value="<?php if(!empty($DateOfBirth[0])){ echo $DateOfBirth[0]; } ?>" readonly='readonly' />
                            <div class="calendarToSelectBox" style=" position: relative;">
                                <div id="calendarToSelectDate"></div>
                            </div>
                        </div>
                        <div>
                            <div class="regErrorMsg" id="dob_error_<?php echo $regFormId; ?>"></div>
                        </div>
                    </li>
                    <li style="margin-bottom:0">
                        <div class="text-show <?php if(!empty($additionalInfo['AboutMe'])) {echo 'filled1'; } ?>">
                            <label class="form-label">About Me</label>
                            <input type="text" id="aboutMe_<?php echo $regFormId; ?>" name="aboutMe" class="user-txt-flds"  minlength="5" maxlength="50" default="Your brief introduction" value="<?php if(!empty($additionalInfo['AboutMe'])){echo $additionalInfo['AboutMe'];} ?>"/>
                           
                        </div>
                        <div style="display:none">
                          <div class="regErrorMsg" id="aboutMe_error_<?php echo $regFormId; ?>"></div>
                        </div>
                         <p class="char-txt" id='aboutmechar'><?php echo strlen($additionalInfo['AboutMe']).'/50 characters'; ?></p>
                    </li>
                    <li>
                        <div class="text-show <?php if(!empty($additionalInfo['Bio'])) {echo 'filled1'; } ?>">
                            <label class="form-labels">Bio</label>
                            <textarea class="txt-area" id="bio_<?php echo $regFormId; ?>" name="bio" maxlength="500" minlength="45" wrap="physical"><?php if(!empty($additionalInfo['Bio'])){echo $additionalInfo['Bio'];} ?></textarea>
                             
                        </div>
                        <div style="display:none">
                          <div class="regErrorMsg" id="bio_error_<?php echo $regFormId; ?>"></div>
                      </div>
                       <p class="char-txt" id='biochar'><?php echo strlen($additionalInfo['Bio']).'/500 characters'; ?></p>
                    </li>
                </ul>

                <?php if($userLevelDetails['userLevel'] > 10){ ?>

                    <h1 class="social-links-h1">Link your social profiles:</h1>
                    <ul class="scoial-link-ul">
                        <li>
                            <div class="text-show <?php if(!empty($socialInfo['FacebookId'])) {echo 'filled1'; } ?>">
                                <label class="form-label1">Facebook Link</label>
                                <i class="profile-sprite ic_fb"></i>
                                <input type="text" name="facebookId" class="sc-txt-flds" value="<?php if(!empty($socialInfo['FacebookId'])){ echo $socialInfo['FacebookId'];} ?>" id="facebookId_<?php echo $regFormId; ?>" />
                            </div>
                            <div>
                              <div class="regErrorMsg" id="facebookId_error_<?php echo $regFormId; ?>"></div>
                          </div>
                        </li>
                        <li>
                            <div class="text-show <?php if(!empty($socialInfo['TwitterId'])) {echo 'filled1'; } ?>">
                                <label class="form-label1">Twitter Link</label>
                                <i class="profile-sprite ic_twiter"></i>
                                <input type="text" name="twitterId" class="sc-txt-flds" value="<?php if(!empty($socialInfo['TwitterId'])){ echo $socialInfo['TwitterId'];} ?>" id="twitterID_<?php echo $regFormId; ?>" />
                            </div>
                            <div>
                              <div class="regErrorMsg" id="twitterID_error_<?php echo $regFormId; ?>"></div>
                          </div>
                        </li>
                        <li>
                            <div class="text-show <?php if(!empty($socialInfo['LinkedinId'])) {echo 'filled1'; } ?>">
                                <label class="form-label1">Linked In Link</label>
                                <i class="profile-sprite ic_lnkdn"></i>
                                <input type="text" name="linkedinId" class="sc-txt-flds" value="<?php if(!empty($socialInfo['LinkedinId'])){ echo $socialInfo['LinkedinId'];} ?>" id="linkedinId_<?php echo $regFormId; ?>" />
                            </div>
                            <div>
                              <div class="regErrorMsg" id="linkedinId_error_<?php echo $regFormId; ?>"></div>
                          </div>

                        </li>
                        <li>
                            <div class="text-show <?php if(!empty($socialInfo['YoutubeId'])) {echo 'filled1'; } ?>">
                                <label class="form-label1">Youtube Channel</label>
                                <i class="profile-sprite ic_youtube"></i>
                                <input type="text" name="youtubeId" class="sc-txt-flds" value="<?php if(!empty($socialInfo['YoutubeId'])){ echo $socialInfo['YoutubeId'];} ?>" id="youtubeId_<?php echo $regFormId; ?>" />
                            </div>
                            <div>
                              <div class="regErrorMsg" id="youtubeId_error_<?php echo $regFormId; ?>"></div>
                          </div>

                        </li>
                        <li>
                            <div class="text-show <?php if(!empty($socialInfo['PersonalURL'])) {echo 'filled1'; } ?>">
                                <label class="form-label1">Website Url</label>
                                <i class="profile-sprite ic_weburl"></i>
                                <input type="text" name="personalURL" class="sc-txt-flds" value="<?php if(!empty($socialInfo['PersonalURL'])){ echo $socialInfo['PersonalURL'];} ?>" id="personalURL_<?php echo $regFormId; ?>" />
                            </div>
                            <div>
                              <div class="regErrorMsg" id="personalURL_error_<?php echo $regFormId; ?>"></div>
                          </div>

                        </li>
                    </ul>

                <?php } ?>
            </div>
        </article>
    </section>
    <!---->  
    </div>
                      <input type="hidden" id="isStudyAbroad" value="no" />
                      <input type='hidden' name='isStudyAbroad' id="isStudyAbroadFlag_<?php echo $regFormId; ?>" value='<?php echo $isStudyAbroadFlag;?>' />
                      <input type='hidden' name='abroadSpecialization' id="abroadSpecialization_<?php echo $regFormId; ?>" value='<?php echo $abroadSpecializationFlag;?>' />
                      <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=true />
                      <input type="hidden" id="userVerification_<?php echo $regFormId; ?>" name="userVerification" value=no />
                      <input type="hidden" name="action" value='save' />
                      <input type="hidden" name="context" id="context_<?php echo $regFormId; ?>" value='unifiedProfile' />
                      <input type="hidden" name="tracking_keyid" id="tracking_keyid_<?php echo $regFormId; ?>" value="696" />
                        <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="personalInformationSection" />
                        <a id="popupAnchor" class="ui-btn ui-btn-inline ui-corner-all select-hide" href="#myPopup" data-rel="popup" aria-owns="myPopup" aria-haspopup="true" aria-expanded="false"></a>
    <input type="button" class="common-btn bottom-fix" value="save" onclick="shikshaUserProfileForm['<?php echo $regFormId; ?>'].submitForm('save');" />

</div>
    </form>

<?php
 $MandatoryFields = array(
                       "mobile" => "Mobile",
                       "firstName" => "DisplayName",
                       "lastName" => "DisplayName",
                       'residenceCityLocality' => 'Mandatory'
                     );
?>
<script type="text/javascript">
    var allEventDates = [];

    var customVars = {"email":"Email","mobile":"Mobile","firstName":"DisplayName","lastName":"DisplayName","residenceCityLocality":"Mandatory"};
    var shikshaUserProfileForm = {};
    var shikshaUserRegistrationForm = {};

   shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(customVars);


   shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($MandatoryFields)); ?>);

    // shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($MandatoryFields); ?>);
    // shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($MandatoryFields)); ?>);
    var STUDY_ABROAD_NEW_REGISTRATION = 0;

    <?php if($submittedValue != INDIA_ISD_CODE){ ?>
        $('#isdCode_<?php echo $regFormId; ?>').trigger('change');
    <?php }else{ ?>
        // $('#residenceCityLocality_<?php echo $regFormId; ?>_select').trigger('change');
        shikshaUserProfileForm['<?php echo $regFormId; ?>'].setUserCityValues("<?php echo $personalInfo['City']; ?>", "<?php echo $UserResidentCity; ?>");
    <?php } ?>

</script>

<div id="registration-box-layer">
  <form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
      <ul class="reg-ul" id="reg-personal-info" >

             <li <?php echo $registrationHelper->getBlockCustomAttributes('firstName','float:left !important'); ?>>
                <div class="two-column-fields" >
                    <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="reg-txt-fld" minlength="1" maxlength="50" value="First Name" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                    <div>
                        <div class="regErrorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
                    </div>
                    <div class='clearFix'></div>
                </div>
                <div <?php echo $registrationHelper->getBlockCustomAttributes('lastName', ''); ?>>
                    <div class="two-column-fields r1">
                        <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="reg-txt-fld" minlength="1" maxlength="50" value="Last Name" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                        <div>
                            <div class="regErrorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div class='clearFix'></div>
                    </div>
                </div>
                <div class="clearFix"></div>  
            </li>



            <li <?php echo $registrationHelper->getBlockCustomAttributes('email','float:left !important'); ?>>
              <div class="two-column-fields" style="width:48% !important;">
                  <input type="text" name="email" id="email_<?php echo $regFormId; ?>" regFieldId="email" class="reg-txt-fld" maxlength="125" value="Email Id" default="Email Id" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="registration_context = '<?php echo $context;?>';if(shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)){shikshaUserRegistration.setCallback(function(callbackData) {if(callbackData.status == 'SUCCESS') {window.location = '/mba/resources/iim-call-predictor-result';}});shikshaUserRegistration.checkExistingEmail(this);}else{ false;} shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
                  <div>
                      <div class="regErrorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                  </div>
              </div>

              <div <?php echo $registrationHelper->getBlockCustomAttributes('password', ''); ?>>
                    <div class="two-column-fields r1" style="position:relative;">
                         <input type="text" id="passwordText_<?php echo $regFormId; ?>" regFieldId="passwordText" class="reg-txt-fld" value="Password" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" />
                        <input type="password" name="password" id="password_<?php echo $regFormId; ?>" class="reg-txt-fld ph pas-clr" maxlength="25" <?php echo $registrationHelper->getFieldCustomAttributes('password'); ?> style="display: none;" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                        <p class="hideText" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleShowPass(this);">Show</p>
                        
                        <div>
                            <div class="regErrorMsg" id="password_error_<?php echo $regFormId; ?>"></div>
                        </div>
                        <div class='clearFix'></div>
                    </div>
                </div>


        </li>
       
        <p class="clr"></p>
      </ul>
      <ul class="city-ul">
        <li class="cat-flds" >
              <div class="reg-col reg-col-return0">
            <div class="country-col custom-dropdown" <?php echo $registrationHelper->getBlockCustomAttributes('isdCode','float:left !important;width:36%;'); ?>>
                <?php 
                    $ISDCodeValues = $fields['isdCode']->getValues(); ?>
                    <select name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].isdCodeOnChange(this.value);" regFieldId="isdCode" class="country-code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                    <?php foreach($ISDCodeValues as $key=>$value){ ?>
                        <option value="<?php echo $key; ?>"> <?php echo $value; ?> </option>
                 <?php } ?>
                </select>
                
                <div>
                    <div class="regErrorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
                </div>
                <div class='clearFix'></div>
            </div>

        <div <?php echo $registrationHelper->getBlockCustomAttributes('mobile',''); ?>>
            <div class="mbl-number"  style="width:59%">
                <input type="text" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="reg-txt-fld" maxlength="10" minlength="10" value="Mobile No" default="Mobile No" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" style="float:right;" />
                <div>
                    <div class="regErrorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
                </div>  
            </div>
          </div>
          </div>


            <div class="reg-col r1" id="registrationFormMiddle_<?php echo $regFormId; ?>" >
              <div class="custom-dropdown" style="width:100%" <?php echo $registrationHelper->getBlockCustomAttributes('residenceCity'); ?> >
                <select name="residenceCityLocality" class="country-code" id="residenceCityLocality_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Current City', 'isNational' => true)); ?>
                </select>
                </div>
           
            <div>
                <div class="regErrorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
            </div>
            </div>
        </li>
           <li <?php echo $registrationHelper->getBlockCustomAttributes('graduationCompletionYear'); ?>>
            <div class="custom-dropdown">
                <select name="graduationCompletionYear" id="graduationCompletionYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('graduationCompletionYear'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                    <option value="">Graduation Completion Year</option>
                    <?php foreach($fields['graduationCompletionYear']->getValues() as $graduationCompletionYear) { ?>
                        <option value='<?php echo $graduationCompletionYear; ?>' <?php if($graduationCompletionYear == $gradYear){ echo 'selected'; } ?> ><?php echo $graduationCompletionYear; ?></option>
                    <?php } ?>  
                </select>
            </div>
            <div>
                <div class="regErrorMsg" id="graduationCompletionYear_error_<?php echo $regFormId; ?>"></div>
            </div>
            <div>
                    <div class="regErrorMsg" id="graduationCompletionDate_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>

       <li>
        <input type="hidden" name="exams[]" value="CAT" />
        <input type="hidden" name="CAT_score" value="<?php echo $CAT_score; ?>" />
       </li>

        <p class="clr"></p>
         </ul>
         <ul>
           <li>
              <div class="cat-btn-col">
                 <input type="button" class="btn-bck nxt-btn" id="regLayerBack" value="Back" onclick="$j('#icpregistration').hide();$j('#interimOutputScreen').show();">
                
                 <input type="button" ga-attr="REGISTRATION_CONTINUE" id="registrationSubmit_<?php echo $regFormId; ?>" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" class="secondary nxt-btn" value="Continue">
                  <p class="clr"></p>
               </div> 
       	  </li>
        </ul>
            <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
            <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
            <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $courseGroup == 'studyAbroad' ? 'yes' : 'no'; ?>' />
            <input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : "MARKETING_FORM"; ?>' />
            <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($_SERVER['HTTP_REFERER']); ?>' />
            <input type='hidden' id='fieldsView' name='fieldsView' value='default' />
            <input type='hidden' id='isMR' name='isMR' value='YES' />
            <input type='hidden' id='isCompareEmail' name='isCompareEmail' value='<?php echo $isCompareEmail; ?>' />
            <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
            <input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value="true" />
            <input type="hidden" id="replyContext_<?php echo $regFormId; ?>" name="replyMsg" value='<?php echo isset($replyContext) ? $replyContext : 'no'; ?>' />            <input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
            <input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='627' />
            <?php if(isset($threadId) && $threadId != '0'){ ?> 
              <input type="hidden" id="replyThreadId_<?php echo $regFormId; ?>" name="replyThreadId" value='<?php echo $threadId; ?>' />
            <?php }
            global $managementStreamMR;
            global $mbaBaseCourse;
            global $fullTimeEdType;
            ?>
            <input type='hidden' id='stream' name='stream' value="<?php echo $managementStreamMR; ?>" />
            <input type='hidden' id='baseCourses' name='baseCourses[]' value="<?php echo $mbaBaseCourse; ?>" />
            <input type='hidden' id='educationType' name='educationType[]' value="<?php echo $fullTimeEdType; ?>" />
            <input type="hidden" name="flow" value="course">
            </form>
            <?php $this->load->view('registration/common/jsInitialization', array('skipPopulateForm'=>'yes')); ?>
            </div>
            <div style="clear:both;"></div>
 </div>
<?php

$action = '/registration/Registration/register';
if(!empty($abroadShortRegistrationData)) {
    $action = '/registration/Registration/updateUser';
}
$europeanCountries = array("EU","AD","AL","AT","BA","BE","BG","BY","CH","CS","CZ","DE","DK","EE","ES","FI","FO","FR","FX","GB","GI","GR","HR","HU","IE","IS","IT","LI","LT","LU","LV","MC","MD","MK","MT","NL","NO","PL","PT","RO","SE","SI","SJ","SK","SM","UA","VA");
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    var regFormId = '<?=$regFormId?>';
</script>
<form action="<?=$action?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
    <article class="content-inner clearfix">
        <!-- <div style="margin:5px 0 15px; font-weight:normal;<?=($formHeading !=''?'font-size:12px;':'')?>" class="wrap-title">
            <?=($formHeading !=''?htmlentities($formHeading):'Tell Us About Yourself')?>
        </div> -->
        <?php
        if($forResponse){
            $this->load->view('registration/fields/LDB/variable/abroadMobile');
        }else if($scholarshipResponse){
            $this->load->view('registration/fields/LDB/variable/abroadMobileScholarshipResponse');
        }else{
            $this->load->view('registration/fields/LDB/variable/abroadMobileRegistration');
        }?>
    </article>
    <?php
    if(!empty($consultantRelatedData['consultantData']))
    {
        $this->load->view('commonModule/consultantEnquiryResponseForm');
    }
    ?>
    <article class="content-inner captcha-box clear customInputs terms-conDiv">
        <ul class="form-display">
            <?php if(empty($formData['userId']) || $formData['userId'] == 0) { ?>
                <li>
                    <div>
                        <input type="checkbox" id="prv_plcy_<?=$regFormId?>" onchange="validateGDPRConsent('<?=$regFormId?>')" <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"checked":"")?>>
                        <label data-enhance="false" for="prv_plcy_<?=$regFormId?>">
                            <span class="sprite flLt"></span>
                            <p>Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the <a href="<?=SHIKSHA_STUDYABROAD_HOME."/privacy-policy.html";?>" target="_blank">Privacy Policy</a> and <a href="<?=SHIKSHA_STUDYABROAD_HOME."/terms-conditions.html";?>" target="_blank">Terms &amp; Conditions</a>.</p>
                        </label>
                    </div>
                    <div style="display: none">
                        <div class="errorMsg error-msg" id="prv_plcy_error_<?=$regFormId?>"></div>
                    </div>
                </li>
                <li>
                    <div class="promtnl-div">
                        <div class="lbl-div">
                            <input <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"checked":"")?> onchange="validateGDPRConsent('<?=$regFormId?>')" id="agr_purps_<?=$regFormId?>" type="checkbox"><label for="agr_purps_<?=$regFormId?>"><span class="sprite flLt"></span></label>
                        </div>
                        <div class="lbl-div <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"bold":"")?>">
                            <label data-enhance="false" for="agr_purps_<?=$regFormId?>" style="">
                            I agree to be contacted for service related information and promotional purposes.
                            </label>
                            <label style="">
                                <i class="sprite agree-cntIcn" id="agree-cntIcn" onclick="toggleToolTip(this);">
                                    <span class="input-helper1" style="display: none">
                                        <span class="up-arrow"></span>
                                        <span class="helper-text">I can edit my communication preferences at any time in "My profile" section and/or may withdraw and/or restrict my consent in full or in part</span>
                                    </span>
                                </i>
                            </label>
                        </div>
                    </div>
                    <div style="display: none;">
                        <div class="errorMsg error-msg" id="agr_purps_error_<?=$regFormId?>"></div>
                    </div>
                </li>
            <?php } ?>
            <?php if(!$formData['userId'] && SHOW_CAPTCHA_MOBILE_SA) { ?>
                <li class="captcha-text">
                    <?php $this->load->view('registration/fields/securityCode'); ?>
                </li>
            <?php }
            ?>
            <li style="margin:15px 0 15px">
                <!--<a href="javascript:void(0);" onclick="toggleCountryDropdown(true);return shikshaUserRegistrationForm[<?php /*echo "'$regFormId'"; */?>].submitForm();" class="btn btn-default btn-full"><?/*=($forResponse?'Submit': (!empty($abroadShortRegistrationData)?'Update your profile':'Register Now'))*/?></a>-->
                <a href="javascript:void(0);" onclick="return userRegistrationSubmitForm('<?=$regFormId?>');" class="btn btn-default btn-full"><?=($forResponse?'Submit': (!empty($abroadShortRegistrationData)?'Update your profile':'Register Now'))?></a>
            </li>
        </ul>
    </article>
    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo ($courseGroup == 'studyAbroad' || $courseGroup == 'studyAbroadRevamped') ? 'yes' : 'no'; ?>' />
    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#abroadSignUp")); ?>' />
    <input type='hidden' id='referrerTitle' name='referrerTitle' value='<?php echo htmlentities($this->security->xss_clean($refererTitle)); ?>' />
    <input type="hidden" id="conversionType" value="<?php echo $conversionType; ?>">
    <input type="hidden" id="keyName" value="<?php echo $keyName; ?>">
    <input type="hidden" id="contentId" value="<?php echo $contentId; ?>">
    <input type="hidden" id="contentType" value="<?php echo $contentType; ?>">
    <input type="hidden" id="compCourseId" value="<?php echo $compCourseId; ?>">
    <?php 
     if($forShortlist){ ?>
        <input type='hidden' id='listingTypeForShortlist' name='listingTypeForShortlist' value="<?php echo $listingTypeIdForBrochure; ?>" />
        <input type='hidden' id='shortlistpagetype' name='shortlistpagetype' value="<?php echo $shortlistSource; ?>" />
    <?php } ?>
    <?php if($scholarshipResponse){ ?>
        <input type='hidden' id='listingTypeForBrochure' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
        <input type='hidden' id='listingTypeIdForBrochure' name='listingTypeIdForBrochure' value='<?=$listingTypeIdForBrochure?>' />
        <input type='hidden' id='responseMobile' name='responseMobile' value='<?=$scholarshipResponse?>' />
        <input type='hidden' id='responseSource' name='responseSource' value='<?=$scholarshipResponseSource?>' />
        <input type='hidden' id='responseAction' value='<?=$responseAction?>' />
    <?php } ?>
    <?php if($forResponse){ ?>
        <input type='hidden' id='responseSource' name='responseSource' value='<?=$responseSourcePage?>' />
        <input type='hidden' id='listingTypeForBrochure' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
        <input type='hidden' id='listingTypeIdForBrochure' name='listingTypeIdForBrochure' value='<?php echo ($courseGroup == 'studyAbroadRevamped' && $context=='mobileRegistrationAbroad' && $pageReferer=='university'&& $universityId>0)?'':$clientCourseId?>' />
        <input type='hidden' id='widget' name='widget' value='<?=$widget?>' />
        <input type='hidden' id='responseMobile' name='responseMobile' value='<?=$forResponse?>' />
    <?php } ?>
    <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $trackingPageKeyId; ?>" name="tracking_keyid">
    <input id="consultant_tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?php echo $consultantTrackingPageKeyId; ?>" name="consultant_tracking_keyid">
    <input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
    <?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
    ?>
    <input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</form>
<?php $this->load->view('registration/common/jsInitialization'); ?>
<script>
    if(typeof(consultantInRegistration) !== 'undefined' && consultantInRegistration == true && !isNaN(loggedInUserCity)){
        showConsultantLayerForm('<?=$regFormId?>',loggedInUserCity);
    }
</script>
<?php if($OTPforReturningUser == true){ ?>
    <script type="text/javascript">
        $j('#joinShikshaLabel').hide();
        $j('#registrationForm_<?php echo $regFormId; ?>').hide();
        $j('.tabs').hide();
        $j('#user-Authentication-otp_<?php echo $regFormId; ?>').show();
    </script>
<?php }?>
<script type="text/javascript">
    if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined'){
        shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
    }
</script>

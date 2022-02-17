<?php 
    $action = '/registration/Registration/register';
    if(!empty($abroadShortRegistrationData)) {
        $action = '/registration/Registration/updateUser';
    }
    global $rmcTrackingCourseId;
    if(!isset($_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId])){
        $trackingPageKeyId = 646;
        setcookie("rmcTrackingPageKey".$rmcTrackingCourseId, $trackingPageKeyId, time()+1800, "/", COOKIEDOMAIN);                    
        $_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId]=$trackingPageKeyId;
    }
    $europeanCountries = array("EU","AD","AL","AT","BA","BE","BG","BY","CH","CS","CZ","DE","DK","EE","ES","FI","FO","FR","FX","GB","GI","GR","HR","HU","IE","IS","IT","LI","LT","LU","LV","MC","MD","MK","MT","NL","NO","PL","PT","RO","SE","SI","SJ","SK","SM","UA","VA");
?>
<script>
    var registration_context = '<?php echo $context; ?>';
    var regFormId = '<?=$regFormId?>';
</script>
<form action="<?=$action?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<article class="content-inner clearfix">
    
        <div style="margin:10px 0 15px; float: left; width:100%; color:#333; padding-bottom:5px; border-bottom:1px solid #ccc; font-size:12px;<?=($formHeading !=''?'font-size:12px;':'')?>" class="wrap-title">
            <?=($formHeading !=''?htmlentities($formHeading):'Tell Us About Yourself')?>
        </div>
        <?php $this->load->view('registration/fields/LDB/variable/mobileRmcPage'); ?>
</article>
<?php
if(!empty($consultantRelatedData['consultantData']))
{
   $this->load->view('commonModule/consultantEnquiryResponseForm'); 
}
?>
<article class="content-inner clear customInputs rmc-terms-conDiv">
        <ul class="form-display">
            <?php if(!$formData['userId'] && SHOW_CAPTCHA_MOBILE_SA) { ?>
                <li class="captcha-text">
                    <?php $this->load->view('registration/fields/securityCode'); ?>
                </li>
            <?php }
            if (empty($formData['userId']) || $formData['userId'] == 0){ ?>
                        <li>
                            <div>
                                <input type="checkbox" id="prv_plcy_<?=$regFormId?>" onchange="validateGDPRConsent('<?=$regFormId?>')" <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"checked":"")?>>
                                <label data-enhance="false" for="prv_plcy_<?=$regFormId?>">
                                    <span class="sprite flLt"></span>
                                    <p>Yes, I have read and provide my consent for my data to be processes for the purposed as mentioned in the <a href="<?=SHIKSHA_STUDYABROAD_HOME."/privacy-policy.html";?>" target="_blank">Privacy Policy</a> and <a href="<?=SHIKSHA_STUDYABROAD_HOME."/terms-conditions.html";?>" target="_blank">Terms &amp; Conditions</a>.</p>
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
                                            <span class="input-helper1" id="responsiveness0" style="display: none">
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
            <?php   }?>
            <li>
                <!--<a href="javascript:void(0);" onclick="toggleCountryDropdown(true);showHideTenthSubjectOverlay(true);return shikshaUserRegistrationForm[<?php /*echo "'$regFormId'"; */?>].submitForm();" class="rate-chances-btn"><?/*=('Rate My Chances')*/?></a>-->
                <a href="javascript:void(0);" onclick="return userRegistrationSubmitForm('<?=$regFormId?>', true);" class="rate-chances-btn"><?=('Rate My Chances')?></a>
            </li>
			<?php /*if(!$formData['userId']) { */?><!--
            <li class="tac" style="font-size:10px;">
                <p>I Agree To The <a href="<?php /*echo SHIKSHA_STUDYABROAD_HOME."/terms-conditions.html";*/?>" target="_blank">Terms Of Services</a> And <a href="<?php /*echo SHIKSHA_STUDYABROAD_HOME."/privacy-policy.html";*/?>" target="_blank">Privacy Policy</a>.</p>
            </li>
            --><?php /*} */?>
            
        </ul>
</article>
    <input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
    <input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
    <input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo (in_array($courseGroup, array('studyAbroad','studyAbroadRevamped','SAapply')) ? 'yes' : 'no'); ?>' />
    <input type='hidden' id='registrationSource' name='registrationSource' value='abroadTwoStep' />
    <input type='hidden' id='referrer' name='referrer' value='<?php echo htmlentities($this->security->xss_clean($customReferer ? $customReferer : $_SERVER['HTTP_REFERER']."#abroadSignUp")); ?>' />
    <input type='hidden' id='referrerTitle' name='referrerTitle' value='<?php echo htmlentities($this->security->xss_clean($refererTitle)); ?>' />
    <!-- fields required for response creation in case of download brochure -->
    <?php if($forResponse){ ?>
    <input type='hidden' id='responseSource' name='responseSource' value='<?=$responseSourcePage?>' />
    <input type='hidden' id='listingTypeForBrochure' name='listingTypeForBrochure' value='<?=$listingTypeForBrochure?>' />
    <input type='hidden' id='listingTypeIdForBrochure' name='listingTypeIdForBrochure' value='<?=$clientCourseId?>' />
    <input type='hidden' id='widget' name='widget' value='<?=$widget?>' />
    <input type='hidden' id='responseMobile' name='responseMobile' value='<?=$forResponse?>' />
	<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
    <input id="tracking_keyid_<?php echo $regFormId; ?>" type="hidden" value="<?=$_COOKIE['rmcTrackingPageKey'.$rmcTrackingCourseId]?>" name="tracking_keyid">
    <?php } ?>
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

<script type="text/javascript">
if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined' &&  $('isdCode_<?=$regFormId?>').value != '91-2'){ 
    $j('#isdCode_<?=$regFormId?>').trigger('change');
}

</script>

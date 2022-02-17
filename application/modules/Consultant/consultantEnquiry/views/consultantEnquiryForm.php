<?php
// prepare dropdown html
$dropdownOptionHtml = "";
foreach($dropdownRegions as $dropdownRegion){
    $selected = ($dropdownRegion['regionId'] == $regionId?' selected = "selected" ':'');
    $dropdownOptionHtml .= '<option value="'.$dropdownRegion['regionId'].'" '.$selected.' consultantLocationId = "'.$dropdownRegion['consultantLocationId'].'">'.$dropdownRegion['regionName'].'</option>';
}
//keep consultant location & shikshaPRI number in js, to update when user changes the region
$consultantLocationArr = array();
foreach($consultantLocations as $consultantLocationId => $consultantLocation)
{
    $consultantLocationArr[$consultantLocationId] = array('locationName'=>$consultantLocation->getLocalityName().($consultantLocation->getLocalityId()>0?", ".$consultantLocation->getCityName():""),
                                                          'shikshaPRI'=>$consultantLocation->getShikshaPRINumber(TRUE),
                                                          'displayPRI'=> $consultantLocation->getDisplayPRINumber());
}
// keep first one to keep it preselected
$selectedConsultantLocation = reset($consultantLocationArr);
// get email & mobile combination that was last verified via OTP
$cookieValOTP = explode('|',$cookieValOTP);
?>
<script>
    var consultantLocations = JSON.parse('<?=json_encode($consultantLocationArr)?>');
</script>
<div id = "consultantEnquiryFormContainer" style="display:none;">
    <form id="form_<?=$source?>">
    <input type = "hidden" name = "source" value = "<?=$source?>">
    <input type = "hidden" name = "consultantId_<?=$source?>" id = "consultantId_<?=$source?>" value = "<?=$consultantId?>">
    <input type = "hidden" name = "university_<?=$source?>" id = "university_<?=$source?>" value = "<?=base64_encode(json_encode($universityName))?>">
     <input type = "hidden" name = "consultantTrackingPageKeyId_<?=$source?>" id = "consultantTrackingPageKeyId_<?=$source?>" value = "<?=$consultantTrackingPageKeyId?>">
    <div style="padding:0px 10px 15px;" class="clearfix">
        <div style="padding:0; width:95%; background: none;margin-top:0px;" class="abroad-step-title flLt">
            <strong class="consultant-region-title">Please choose the region you are located in</strong>
            <div style="width:250px; margin-left:10px;" class="custom-dropdown">
                <select class="universal-select region-select regionSelect" name = "region_<?=$source?>" id = "region_<?=$source?>" onchange="changeConsultantInfo(this);" validate="validateSelect" onblur = "validateBrochureFormElement(this,'blur')" required="true">
                    <?php echo $dropdownOptionHtml; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="abroad-consult">
        <div class="abroad-consult-coll flLt"><img src="<?=($logoUrl)?>" alt="<?=(htmlentities($consultantName))?>" height="77" width="115"></div>
        <div class="abroad-consult-info clearfix" style="font-size:11px;">
            <h4><a href="<?=(htmlentities($consultantUrl))?>" target="_blank"><?=(htmlentities($consultantName))?></a></h4>
            <span class="locationSpan"><?=($selectedConsultantLocation['locationName'])?></span>
            <?php if($selectedConsultantLocation['displayPRI']) { $style ='style="display:none;"';}
                  else {$style ='';}
            ?>
            <div class = "shikshaPRIDiv" <?=($style)?>><i class="common-sprite contct"></i><span style="display:inline;"><?=($selectedConsultantLocation['displayPRI'])?></span></div>
        </div>
    </div>
    <div class="clearwidth abroad-consult-msg">
        <span class="abroad-consult-msg-hd">Write your message or query</span>
        <?php $placeholderText = 'Your query will be directly forwarded to this consultant. They will reply to you on your email or mobile phone';?>
        <textarea class="abroad-consult-txt enquiryField" id="message_<?=$source?>" onblur="textareaOnBlur(this);" onfocus="textareaOnFocus(this);" required="true" minlength="3" maxlength="500" caption="message" validate="validateConsultantTextarea" default="<?=$placeholderText?>" value="<?=$placeholderText?>" required="true"><?=$placeholderText?></textarea>
        <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="message_<?=$source?>_error" style="padding-left:3px; clear:both; display:block"></div>
    </div>
    <div class="clearwidth abroad-consult-msg">
        <div class="abroad-consult-msg-hd">Fill this form to get a response</div>
        <div class="abroad-consult-shre clearwidth">
            <ul>
                <li>
                    <div class="flLt signup-txtwidth">
                        <input class="universal-text signup-txtfield enquiryField" value="<?=($userFormData['email'] != ''?$userFormData['email']:'Email Id')?>" id="email_<?=$source?>" type="text" maxlength="150" caption="Email" default="Email Id" validate="validateEmail" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" required="true">
                        <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="email_<?=$source?>_error" style="padding-left:3px; clear:both; display:block"></div>
                    </div>
                    <div class="flRt signup-txtwidth">
                        <input class="universal-text signup-txtfield enquiryField" value="<?=($userFormData['mobile'] != ''?$userFormData['mobile']:'Mobile No.')?>" id="mobile_<?=$source?>" type="text" maxlength="10" minlength="10" caption="Mobile" default="Mobile No." validate="validateMobileInteger" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" required="true">
                        <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="mobile_<?=$source?>_error" style="padding-left:3px; clear:both; display:block"></div>
                    </div>
                </li>
                <li>
                    <div class="flLt signup-txtwidth">
                        <input class="universal-text signup-txtfield enquiryField" value="<?=($userFormData['firstName'] != ''?$userFormData['firstName']:'First Name')?>" id="firstName_<?=$source?>" type="text" maxlength="50" caption="First Name" default="First Name" validate="validateDisplayName" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" required="true">
                        <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="firstName_<?=$source?>_error" style="padding-left:3px; clear:both; display:block"></div>
                    </div>
                    <div class="flRt signup-txtwidth">
                        <input class="universal-text signup-txtfield enquiryField" value="<?=($userFormData['lastName'] != ''?$userFormData['lastName']:'Last Name')?>" id="lastName_<?=$source?>" type="text" maxlength="50" caption="Last Name" default="Last Name" validate="validateDisplayName" onblur = "validateBrochureFormElement(this,'blur')" onfocus = "validateBrochureFormElement(this,'focus',1)" required="true">
                        <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;" id="lastName_<?=$source?>_error" style="padding-left:3px; clear:both; display:block"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="abroad-consult-reg clearwidth" style="margin-top:0;">
        <div class="fllt signup-txtwidth"><a href="Javascript:void(0);" onclick = "saveConsultantEnquiry('<?=$source?>');" class="button-style big-button" style="font-size:18px; margin-top:15px;padding:13px 35px;">Send your message</a></div>
    </div>
    </form>
    <div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL;?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
</div>
<script>
    var consultantEnquirySaveDataObj;
    var otpVerifiedEmail = '<?=$cookieValOTP[0]?>';
    var otpVerifiedMobile = '<?=$cookieValOTP[1]?>';
    var enquirySaveInitiated = false;
    function textareaOnBlur(elem,formValidation)
    { var errormsg = '';
        if($j(elem).val() == '' || $j(elem).val() == $j(elem).attr('default'))
        {
            $j(elem).val('<?=$placeholderText?>');
            errormsg = "Please enter your message for the consultant.";
        }
        else{
            errormsg = validateStr(trim(elem.value), elem.getAttribute('caption'), elem.getAttribute('maxlength'), elem.getAttribute('minlength'));
        }
        if (errormsg !== true) {
            $j("#"+$j(elem).attr('id')+"_error").html(errormsg).show();
        }
        else{
            $j("#"+$j(elem).attr('id')+"_error").hide();
        }
        return errormsg;
    }
    function textareaOnFocus(elem)
    {
        if($j(elem).val() == '<?=$placeholderText?>')
        {
            setTimeout(function(){
				if (elem.setSelectionRange) {
				elem.setSelectionRange(0, 0);
				}
				else if (elem.createTextRange) {
					var range = elem.createTextRange();
					range.collapse(true);
					range.moveEnd('character', 0);
					range.moveStart('character', 0);
					range.select();
				}
			},20);
            
            if ($j(elem).val() == $j(elem).attr('default')) {
                $j(elem).bind("keydown", function () {
                    $j(this).val('');
                    $j(this).unbind("keydown");
                });
            }
            else {
                $j(elem).unbind("keydown");
            }
        }
    }
    $j('#region_<?=$source?>').trigger('change');
</script>

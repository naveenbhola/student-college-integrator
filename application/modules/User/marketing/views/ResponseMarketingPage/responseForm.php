<?php
if($validateuser != "false"){
	$firstname = $validateuser[0]['firstname'];
	$lastname = $validateuser[0]['lastname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
}
?>
<form id="form_<?php echo $widget; ?>" onsubmit="processResponseForm('<?=$widget?>','processResponseCallback'); return false;" novalidate>
<div class="req-form">
    <a href="#" class="close-items" uniqueattr="ResponseMarketingPageRequestBrochureHideButton" title="Close" onclick="hideResponseForm('<?php echo $institute->getId(); ?>'); return false;">&nbsp;</a>
    <h6>Request E-Brochure</h6>
	<?php if(!$firstname || !$mobile || !$cookiestr) { ?>
		<p class="font-14">Please provide following details about yourself:</p>
	<?php } ?>
    <ol>
        <li>
            <input type="text" class="universal-txt-field" value="<?php echo $firstname?$firstname:"Your First Name";?>" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your First Name" id="usr_first_name_<?=$widget?>" />
            <div class="clearFix"></div>
            <div style="display:none"><div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
        </li>
        
        <li>
            <input type="text" class="universal-txt-field" value="<?php echo $lastname?$lastname:"Your Last Name";?>" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Last Name" id="usr_last_name_<?=$widget?>" />
            <div class="clearFix"></div>
            <div style="display:none"><div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
        </li>
        
        <li>
            <input class="universal-txt-field flLt"  value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";} ?>" id="contact_email_<?=$widget?>" type="text" validate="validateEmail"  maxlength="100" minlength="10" caption="email"  blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Email" />
            <div class="clearFix"></div>
            <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_<?=$widget?>_error"></div></div>
        </li>
        
        <li>
            <input class="universal-txt-field"  value="<?php echo $mobile?$mobile:"Mobile";?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile" />
            <div class="clearFix"></div>
            <div class="clearFix" style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_<?=$widget?>_error"></div></div>
        </li>

        <li>
            <select class="universal-select" id="selected_course_<?=$widget?>" validation="false"  onchange="reloadWidget('<?=$widget?>')">
                <?php
					if($_REQUEST['city']){
						$request = new CategoryPageRequest();
						$request->setData(array(
											'cityId' => $_REQUEST['city'],
											'localityId' => $_REQUEST['locality']
											));
					}
				
					//$localityArray = array();
                    foreach($courses as $c){
                        if($course && ($course->getId() == $c->getId())){
                            $selected = "selected";
                        }else{
                            $selected = "";
                        }
                        echo '<option '.$selected.' value="'.$c->getId().'">'.html_escape($c->getName()).'</option>';
                        $c->setCurrentLocations($request,true);
                        //$localityArray[$c->getId()] = getLocationsCityWise($c->getCurrentLocations());
                    }
			?>
            </select>
        </li>
        
        <li id="locality-div_<?=$widget?>" class="localityDiv">
	
        </li>
        
		<?php if($validateuser == "false") { ?>
        <li>
			<div class="clearFix"></div>
            <p>Type in the characters you see in the picture below</p>
            <div class="clearFix spacer10"></div>
            <div>
                <img class="vam" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
                <input type="text"  style="width:100px" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                <div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>
            </div>
        </li>
        <?php } ?>
		
        <li><div class="clearFix"></div><input type="button" uniqueattr="ResponseMarketingPageBrochureSubmit" class="big-button" value="Submit" id="big-button_<?=$widget?>" /></li>
    </ol>
</div>
<input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>" />
<input type="hidden" value="<?=html_escape($institute->getId())?>" id="institute_id_<?=$widget?>" />
<input type="hidden" value="RESPONSE_MARKETING_PAGE" id="customDefinedRegistrationSource" />
</form>
<script>
    var localityArray = <?=json_encode($localityArray)?>;
    custom_localities = [];
    $j.each(localityArray,function(index,element){
        custom_localities[index] = element;
    });
    addOnBlurValidate($('form_<?=$widget?>'));
	reloadWidget('<?=$widget?>');
</script>


</div></div>

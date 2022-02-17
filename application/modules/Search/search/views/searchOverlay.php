<!--[if IE 6]> 
	<style>
	* html .ieAlignBtn{position:relative;top:0px}
	</style>
<![endif]-->
<!--[if lte IE 7]> 
	<style>
	.ie_leftAlignBtn{position:relative;left:-20px}
	</style>
<![endif]-->
<div id="sendSearchMail" style="display:none">
<div class="lineSpace_10">&nbsp;</div>
<form method="post" onsubmit="new Ajax.Request('/search/sendMail',{ onComplete: function(transport) { hideOverlay(); } , parameters:Form.serialize(this) }); return false;" action="/search/sendMail" id="sendSearchMailForm">
	<div  class="mar_full_10p">
		Enter Email Id: &nbsp;<input type="text" name="searchEmailAddr" id="searchEmailAddr" value="<?php echo $userEmailAddress;?>" size="30" />		
		<input type="hidden" name="listingTypeForMail" id="listingTypeForMail" value="" />		
		<input type="hidden" name="listingIdForMail" id="listingIdForMail" value="" />		
		<input type="hidden" name="listingUrlForMail" id="listingUrlForMail" value="" />		
        <input type="hidden" name="subject" id="subjectForThisEmail" value="<?php if(isset($subject)){ echo $subject; }else{ echo "Shiksha Info"; } ?>" />
        <input type="hidden" name="extraParams" id="extraParams" value="<?php if(isset($extraParams)){ echo $extraParams; } ?>" />
		<div class="errorMsg" id="emailError"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div align="center">
				<input type="Submit" name="Submit" onclick="return validateSearchEmail();" class="orange-button" style="padding:4px 8px !important; font-size:14px !important; height:auto !important; vertical-align:middle" value="Send Email"> &nbsp;
				<input type="button" onClick="hideOverlay();" class="gray-button" style="padding:3px 8px !important; font-size:14px !important; vertical-align:middle" value="Cancel" />
		</div>
		<div class="spacer10 clearFix"></div>
	</div>
</form>
</div>
<div id="sendSearchSms" style="display:none"> 
<div class="spacer10 clearFix"></div>
<form method="post" onsubmit="new Ajax.Request('/search/sendSms',{ onComplete: function(transport) { hideOverlay(); } , parameters:Form.serialize(this) }); return false;" action="/search/sendSms" id="sendSearchSmsForm">
	<div  class="mar_full_10p">
		Enter Mobile number:&nbsp;
		<input type="text" size="25" name="searchMobileNumber" id="searchMobileNumber" value="<?php echo is_array($validateuser) ? $validateuser[0]['mobile'] : '' ;?>"/>
		<input type="hidden" name="listingTypeForSms" id="listingTypeForSms" value="" />		
		<input type="hidden" name="listingIdForSms" id="listingIdForSms" value="" />		
		<input type="hidden" name="listingUrlForSms" id="listingUrlForSms" value="" />		
		<div class="errorMsg" id="mobileError"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div align="center">
				<button type="Submit" name="Submit" onclick="return validateSearchMobile();">
					<div class="shikshaEnabledBtn_sL" style="padding:0 0 0 3px;width:100px;cursor:pointer">
						<span class="shikshaEnabledBtn_sR" style="padding:0 5px">Send SMS</span>
					</div>
				</button>
				<button type="button" onClick="hideOverlay();" class="ieAlignBtn ie_leftAlignBtn">
					<div class="shikshaDisableBtn_sL" style="padding:0 0 0 3px;width:100px;cursor:pointer">
						<span class="shikshaDisableBtn_sR" style="padding:0 5px">Cancel</span>
					</div>
				</button>
		</div>	
		<div class="lineSpace_10">&nbsp;</div>
	</div>
</form>
</div>

<script>

function validateSearchEmail()
{
	email_field = $('searchEmailAddr').value;
	if (email_field.length == 0 )
	{
		$('emailError').innerHTML = "Enter email Address";
		return false;
	}
	if(validateEmail(email_field.toLowerCase(),"Email",500,1)!== true) {
			$('emailError').innerHTML  = validateEmail(email_field.toLowerCase(),"Email Address",500,1);
			return false;
	}
	return true;
}
function validateSearchMobile()
{
    mobile_field = $('searchMobileNumber').value;
    if (mobile_field.length == 0 )
    {
       $('mobileError').innerHTML = "Enter mobile Number.";
       return false;
    }
    if (validateInteger(mobile_field,"Mobile Number",14,10)!== true) {
           $('mobileError').innerHTML  = validateInteger(mobile_field,"Mobile number.",14,10);
           return false;
       }
    return true;
}

</script>

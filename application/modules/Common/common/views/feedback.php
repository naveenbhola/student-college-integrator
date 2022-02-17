<div id="feedback" style="display:none; z-index:100000001">
	<form class="fedbck_new" method="post" onsubmit=";new Ajax.Request('/shikshaHelp/ShikshaHelp/sendFeedBack',{onSuccess:function(request){javascript:hideFeedBackOverlay(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/shikshaHelp/ShikshaHelp/sendFeedBack">
	<div style="padding:10px">
		<div style="width:100%">
			<div class="float_L" style="width:100px">
				<div style="width:100%" class="txt_align_r"><b>From:&nbsp;</b></div>
			</div>
			<div class="float_L" style="width:300px">
				<div style="width:100%">
					<div><input id="userFeedbackEmail" type="text" size="35" style="height:18px;width:290px;" name="userFeedbackEmail" required="true" validate="validateEmail" maxlength="100" minlength="3" caption="email" /></div>
					<div>(Please enter your email-id)</div>
				</div>
				<div class="row errorPlace"><div id="userFeedbackEmail_error" class="errorMsg"></div></div>
			</div>
			<div class="clear_L">&nbsp;</div>			
		</div>
		<div class="spacer10 clearFix"></div>
		
		<div style="width:100%">
			<div class="float_L" style="width:100px">
				<div style="width:100%" class="txt_align_r"><b>Content:&nbsp;</b></div>
			</div>
			<div class="float_L" style="width:300px">
				<div style="width:100%">
					<div><textarea name="feedBackContent" id = "feedBackContent" validate="validateStr" caption="Content" maxlength="1000" minlength="10" required="true" style="width:290px;height:100px"></textarea></div>					
				</div>
				<div class="row errorPlace"><div id="feedBackContent_error" class="errorMsg"></div></div>
			</div>
			<div class="clear_L">&nbsp;</div>			
		</div>
		<div class="spacer10 clearFix"></div>
		
		<div align="center">Type in the characters you see in the picture below:</div>
		<div class="spacer10 clearFix"></div>
		<div align="center">
		<div><img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&secvariable=feedbackSecurityCode&randomkey=<?php echo rand(); ?>"  onabort="reloadCaptcha(this.id)" id="feedbackCaptcha" align="absmiddle"/>&nbsp;<input type="text" name="feedbackSecCode" id="feedbackSecCode" size="5" validate="validateSecurityCode" maxlength="5" minlength="5" required="true" caption="Security Code" style="padding:4px" /></div>
		<div class="row errorPlace"><div id="feedbackSecCode_error" class="errorMsg"></div></div>
		<div class="spacer10 clearFix"></div>
		<div>
			<input type="submit" value="Submit" onClick="return validateFields(this.form);" name = "submit" id = "submit" class="orange-button" style="padding:2px 8px !important; height:auto !important; font-size:15px !important" />&nbsp;&nbsp;<input type="button" onClick="javascript:hideOverlay();" class="gray-button" value="Cancel" style="padding:4px 8px !important; background-position:left 4px !important" />			
		</div>
		<div class="spacer10 clearFix"></div>
		<div><span id="feedback_thanksmsg"></span></div>
</div>
		</div>
</form>	
</div>

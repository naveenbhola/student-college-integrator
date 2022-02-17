<div id="customizedMMPCaptcha" style="padding:0 10px 0 10px; text-align:left;">
	<div>Type in the characters you see in the picture below</div>
	<div class="lineSpace_5">&nbsp;</div>
	<div>
		<div style="width:35%;line-height:18px;text-align:left;" class="float_L">
			<div class="txt_align_r" style="padding-right:5px;">
				<img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome" width="100" height="34"  id = "<?php echo $prefix; ?>secureCode"/>
			</div>
		</div>
		<div style="width:63%;float:right;padding-top:5px;">
			<input type="text" style="font-size:12px;width:65%;"
				   name = "homesecurityCode"
				   id = "homesecurityCode"
				   validate = "validateSecurityCode"
				   maxlength = "5"
				   minlength = "5"
				   required = "1"
				   caption = "Security Code"/>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div style="width:100%;" class="errorMsg" id= "<?php echo $prefix; ?>homesecurityCode_error"></div>
			</div>
		</div>
	</div>
	<div>
	</div>
	<div class="lineSpace_5" style="clear:both;">&nbsp;</div>
	<div id="customizedMMPTNC" style="margin-bottom:10px;text-align:left;">
		<div style="float:left;">
			<input type="checkbox" name="cAgree" id="cAgree" />
		</div>
		<div style="float:left;width:90%;margin-top:3px;">
			I agree to the <a href="javascript:" onclick="popitup('/shikshaHelp/ShikshaHelp/termCondition');">terms of services</a> and <a href="javascript:"
	onclick="popitup('/shikshaHelp/ShikshaHelp/privacyPolicy');">privacy policy</a>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<!--<div class="errorPlace" style="display:none;line-height:15px">
			<div style="float:right;width:99%;*padding-left:4px" class="errorMsg" id= "cAgree_error"></div>
		</div>-->
		<div style="clear:both;"></div>
		<div class="errorPlace" style="display:none;text-align:left;">
            <div class="errorMsg" id= "cAgree_error" style="padding-left:20px;"></div>
        </div>
	</div>
</div>

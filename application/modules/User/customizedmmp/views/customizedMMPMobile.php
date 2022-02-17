<?php if(($logged == "Yes" && strlen($userData[0]['mobile']) !=10)) { $userData[0]['mobile']  = ''; } ?>
<div>
	<div class="float_L" style="width:35%;line-height:18px">
		<div class="txt_align_r" style="padding-right:5px">Mobile<b class="redcolor">*</b>:</div>
	</div>
	<div style="float:right;width:63%;text-align:left;">
		<div>
			<input type="text" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number" maxlength="10" maxlength1="10" minlength = "10" style="width:65%;font-size:12px" value=""/>
		</div>
		<div class="clear_L withClear">&nbsp;</div>
		<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
			<div class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>
		</div>
	</div>
	<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>


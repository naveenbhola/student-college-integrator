<div id="study_abroad_email_block" style="display:none">
	<div class="float_L" style="width:35%;line-height:18px">
		<div class="txt_align_r" style="padding-right:5px">Email ID<b class="redcolor">*</b>:</div>
	</div>
	<div style="float:right;width:63%;text-align:left;">
		<div>
			<input type="text" id = "homeemail" name = "homeemail" value="" validate = "validateEmail" required = "true" caption = "email id" maxlength = "125" style = "width:65%;font-size:12px" />
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px;width:65%;">
				<div class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>
			</div>
		</div>
	</div>
	<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php
if($logged=="No") {
	// do nothing
}
else { ?>
	<!--<input type="hidden" id = "<?php //echo $prefix; ?>homeemail" value="<?php //$cookiesArr = array(); $cookieData = $userData[0]['cookiestr']; $cookieArr = split('\|',$cookieData); echo $cookieArr[0]; ?>"/>-->
	<!--<input type="hidden" id = "<?php //echo $prefix; ?>userId" value="<?php //echo $userData[0]['userid']; ?>"/>-->
<?php
}?>
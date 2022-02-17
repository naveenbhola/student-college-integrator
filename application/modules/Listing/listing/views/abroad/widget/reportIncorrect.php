<?php $widget = 'incorrectInfo'.rand();?>
<form name="<?= $widget;?>" action="">
<div id="reportIncorrectContainer" class="abroad-layer" style="width:589px;display: none;">
    <p class="err-pra">If you found any error in the information of this <?php echo $entityType ? $entityType : 'course' ?>, please write below</p>
    <div class="abroad-layer-content clearfix">
        <label class="ml-lbl">Your Email Id:</label>
	<input type="text" id="incorrect_email" name="incorrect_email" placeholder="(optional)" class="universal-text ml-fld" value="<?=$email?>"/>
	<input type="hidden" id="incorrect_session_id" name="incorrect_session_id" class="universal-text" value="<?=$session_id?>"/>
	
        <textarea class="universal-text report-textarea" id="incorrect_comments" placeholder="Write here" name="incorrect_comments" caption="text before proceeding" required="true"  maxlength="5000"></textarea>
	<span class="feedbackErrorMsg errorMsg" style="color: #FF0000; font-size: 11px; padding-left: 3px; clear: both;" id="incorrect_comments_error"></span>
	<p>Type in the characters you see in the picture below</p>
        
	<div style="margin-top:10px;">
	    <img class="vam flLt" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&secvariable=incorrectInfo_<?=$widget?>&randomkey=<?php echo rand(); ?>" align="absbottom" width="100" height="34" id = "incorrectInfoIMG_<?=$widget?>"/>
	    <input type="text" alt="secret code" class="register-fields universal-text" style="margin-left:9px;width:120px;"  name = "incorrectInfo_<?=$widget?>" id = "incorrectInfo_<?=$widget?>" required = "true" validate = "validateSecurityCode" maxlength = "5" minlength = "5"  caption = "security code"/>
	    <input type="hidden" value="incorrectInfo_<?=$widget?>" id="securityCodeVar_<?=$widget?>" name="securityCodeVar">
	    <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" id="incorrectInfo_<?=$widget?>_error"></div>
	    <div class="clearfix"></div>
	</div>
	
        <div class="clearwidth" style="margin:8px 0 0;">
            <a onclick="validateReportIncorrectInfoForm('<?= $widget;?>','<?= "incorrectInfo_".$widget;?>');" href="javaScript:void(0);" class="button-style report-sbmt-btn">Submit</a>
            <a href="javaScript:void(0);" onclick="hideAbroadOverlay();">Cancel</a>
        </div>
    </div>
</div>


<div id="reportIncorrectThankyou" class="abroad-layer" style="width:589px;display: none;">
    <div class="abroad-layer-content clearfix">
		<p>Thank you for the input. Our team will review these changes.</p>
        <div class="clearwidth" style="margin:8px 0 0;">
        	<a href="javaScript:void(0);" onclick="hideAbroadOverlay();" class="button-style report-sbmt-btn" style="padding:6px 40px !important; margin-top:20px">OK</a>
        </div>
    </div>
</div>
</form>
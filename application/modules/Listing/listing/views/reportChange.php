<div id="repChange" style="display:none; z-index:100000001">
	<div class="lineSpace_5" >&nbsp;</div>
	<form method="post" onsubmit=";new Ajax.Request('/listing/Listing/reportChange',{onSuccess:function(request){javascript:updateReportChangeResponse(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/listing/Listing/reportChange">
		<input type="hidden" name="listing_type" id="listing_type"  value="<?php echo $listing_type; ?>"/>
		<input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php echo $institute_id; ?>"/>

        <?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
        <?php }else{ ?>
	<div class="normaltxt_11p_blk_arial mar_left_10p fontSize_12p">
		<span class="bld">Your Email:&nbsp;</span> 
		<span>
            <input id="repChangeEmail" type="text" size="45" style="height:18px;" name="repChangeEmail" required="true" validate="validateEmail" maxlength="100" minlength="3" caption="email"  class = "normaltxt_11p_blk_arial fontSize_12p"/>
        </span>
        <br />
        <span class = "normaltxt_11p_blk_arial fontSize_12p" style="margin-left:80px;">(Please enter your email-id)</span>
        <div class="row errorPlace">
			<div id="repChangeEmail_error" class="errorMsg" style="margin-left:40px"></div>
        </div>	
	</div>
    <?php } ?>
	<div class="lineSpace_10" >&nbsp;</div>
	<div class="row">	
		<div class="float_L normaltxt_11p_blk_arial fontSize_12p" style = "width:78px; text-align:right"><span class="bld">Comment:&nbsp;</span></div>
		<div class="float_L">
			<div><textarea name="comment" id = "comment" rows="15" validate="validateStr" caption="Content" maxlength="1000" minlength="10" required="true" style="margin-left:3px;"></textarea></div>
			<div class="row errorPlace"><div id="comment_error" class="errorMsg"></div></div>
		</div>
		<div class="clear_L">&nbsp;</div>		
	</div> 
    <div class="lineSpace_10">&nbsp;</div>
	<div class="row">	
        <div class="normaltxt_11p_blk mar_left_10p">Type in the characters you see in the picture below:
            <span class="redcolor">*</span>
        </div>
    	<div class="lineSpace_2">&nbsp;</div>
        <div class="txt_align_c">
    	    <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=repchange" width="100" height="40"  id = "repChangeCaptacha"/>
        </div>
    </div>
    <div class="lineSpace_2">&nbsp;</div>
	<div class="row">	
        <div class="normaltxt_11p_blk txt_align_c">
            <input type="text" name = "repChange1" id = "repChange1" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
        </div>
	    <div class="row errorPlace">
	        <div class="errorMsg txt_align_c" id="repChange1_error"></div>
        </div>
    </div>

	<div class="row txt_align_c">
	    <button class="btn-submit13 w16" type="submit" onClick="return validateFields(this.form);">
	        <div class="btn-submit13"><p class="btn-submit14 whiteFont" name = "submit" id = "submit">Submit</p></div>
        </button>&nbsp;
        <button class="btn-submit5 w16" type="button" onClick="javascript:hideOverlay();">
            <div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
        </button>
	</div>	
	</form>	
	<div class="lineSpace_10" >&nbsp;</div>
    <script>
function updateReportChangeResponse(responseText)
{
    if(document.getElementById('repChangeCaptacha')){
        reloadCaptcha('repChangeCaptacha','repchange');	
        if(trim(responseText) == 'code')
        {

            var securityCodeErrorPlace = 'repChange1_error';
            document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
            document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
        }
        else
        {
            hideOverlay();
        }
    }else{
        hideOverlay();
    }
}



    </script>
</div>

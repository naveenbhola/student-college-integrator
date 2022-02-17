<?php if(is_array($validateuser)){
    if(is_array($validateuser[0])){
?>
<div style="display:none;" id="searchRequestInfoBox">
<?php
//echo "<pre>".print_r($validateuser)."</pre>";
	$url = site_url('listing/Listing/requestInfo/seccodecommonquery');
	echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateRequestInfoForSearchNew(request.responseText);','name' => 'reqInfo','id' => 'reqInfo'));
?> 
<div class="mar_full_10p">
		<div class="lineSpace_12">&nbsp;</div>
        <?php if(!isset($validateuser[0]['mobile']) || $validateuser[0]['mobile'] ==0 ) { ?>
			<div class="normaltxt_12p_blk fontSize_12p float_L"><?php echo $registerText['descText']; ?></div>
                <div class="lineSpace_10">&nbsp;</div>
		<?php  } ?>
		<br clear='left' />
		<input type="hidden" name="listing_type" id="common_listing_type"  value="<?php echo $listing_type; ?>"/>
		<input type="hidden" name="listing_type_id" id="common_listing_type_id"  value="<?php echo $type_id; ?>"/>
		<input type="hidden" name="listing_title" id="common_listing_title"  value="<?php echo $details['title']; ?>"/>
		<input type="hidden" name="listing_url" id="common_listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
		<input type="hidden" name="isPaid" id="common_isPaid"  value="yes"/>
		<input type="hidden" name="mailto" id="common_mailto"  value=""/>	
		
		<div class="row">
            <div class="normaltxt_11p_blk inline-l" style="width:100px">Your Name:<span class="redcolor">*</span></div>

			<div class="normaltxt_11p_blk">
				<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						<input type="text" name="reqInfoDispName" id="common_reqInfoDispName" value="<?php echo $validateuser[0]['firstname']; ?>" maxlength="25" minlength="3" validate="validateDisplayName"  required="true" size="30"  caption="Name"/>
				<?php } ?>
			</div>
			<div class="clear_L"></div>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="common_reqInfoDispName_error" style="margin-left:100px;"></div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_12">&nbsp;</div>
		<div class="row">
            <div class="normaltxt_11p_blk inline-l" style="width:100px">Your Email Id:<span class="redcolor">*</span></div>

			<div class="normaltxt_11p_blk">
				<input type="text" name="reqInfoEmail" id="common_reqInfoEmail" value="<?php $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0]; ?>"  maxlength = "125" size="30" validate = "validateEmail" required="true" caption="Email-Id" />
			</div>
			<div class="clear_L"></div>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="common_reqInfoEmail_error" style="margin-left:100px;"></div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_12">&nbsp;</div>
		
		<div class="row">
			<div class="normaltxt_11p_blk inline-l" style="width:100px">Your Mobile Number:<span class="redcolor">*</span></div>
			<div class="normaltxt_11p_blk">

            <?php if(isset($validateuser[0]['mobile'])) { ?>
                <input type="text" name="reqInfoPhNumber" id="common_reqInfoPhNumber"  value="<?php echo $validateuser[0]['mobile'] == 0 ?'':$validateuser[0]['mobile'] ; ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" size="30" required="true"  caption="Mobile Number" autocomplete="off"/>
                    <?php } ?>
			</div>
			<div class="clear_L"></div>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="common_reqInfoPhNumber_error" style="margin-left:100px;"></div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_12">&nbsp;</div>
		
		<div class="row">
			<div class="normaltxt_11p_blk inline-l" style="width:100px;">Your Question:</div>
			<div class="normaltxt_11p_blk">
			<textarea id="common_queryContent" name="queryContent"  validate="validateStr" maxlength="1000" minlength="10"  style="width:60%;" caption="Message"  profanity="true"  autocomplete="off" required="true" ></textarea><br/>
			</div>
		<div class="clear_L"></div>
	    </div>	
            <div class="row errorPlace">
                <div class="errorMsg" id="common_queryContent_error" style="margin-left:100px;"></div>
    			<div class="clear_L"></div>
            </div>

	<div class="lineSpace_12">&nbsp;</div>
	<div class="normaltxt_11p_blk txt_align_c row">Type in the characters you see in the picture below:<span class="redcolor">*</span></div>
	<div class="lineSpace_12">&nbsp;</div>
	<div class="row" align="center">
		<div> 	
		<img src="/public/images/blankImg.gif" align="absmiddle"  id = "common_reqinfoCaptacha"/>
		&nbsp;
	        	<input type="text" name = "securityCode1" id = "common_securityCode1" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "true" size="7" caption = "Security Code" autocomplete="off"/>
		</div>
	</div>
	<div class="row errorPlace">
		<div class="errorMsg" id="common_securityCode1_error" style="margin-left:100px;"></div>
		<div class="clear_L"></div>
	</div>
	
	<div class="lineSpace_13">&nbsp;</div>
	<div align="center">
				<button type="Submit" onClick="return sendReqInfoCommon(this.form);">
					<div class="shikshaEnabledBtn_sL" style="padding:0 0 0 3px;width:125px;cursor:pointer">
						<span class="shikshaEnabledBtn_sR" style="padding:0 5px">Send Question</span>
					</div>
				</button>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
</div>

</form>
</div>
<script>
function sendReqInfoCommon(objForm){
	var flag1 = validateFields(objForm);
	var flag2 = true;
	if(trim(document.getElementById("common_reqInfoPhNumber").value) == "")
	{
		document.getElementById("common_reqInfoPhNumber_error").innerHTML = "Please enter your correct mobile number";
		document.getElementById("common_reqInfoPhNumber_error").parentNode.style.display = "inline";
		flag2 = false;
	}
	if(flag1 == true && flag2 == true)
		return true;
	else
		return false;
}

function updateRequestInfoForSearchNew(responseText)
{
    if(document.getElementById('common_reqinfoCaptacha')){
        reloadCaptcha('common_reqinfoCaptacha','seccodecommonquery');	
        if(trim(responseText) == 'code')
        {
            var securityCodeErrorPlace = 'common_securityCode1_error';
            document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
            document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
        }
        else
        {
            hideOverlay();  
            if(document.getElementById('common_queryContent')){
                document.getElementById('common_queryContent').value="";
            }
            if(document.getElementById('common_securityCode1')){
                document.getElementById('common_securityCode1').value="";
            }
            Message = "You have successfully submitted the query to institute.";
            commonShowConfirmMessage(Message);
        }
    }
}
</script>
<?php
    }
}
?>

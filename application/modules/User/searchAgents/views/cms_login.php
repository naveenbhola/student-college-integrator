<div style="width:500px">
    <div style="background:#FFFFFF;border:1px solid #d6e6f6">
        <form id='cms_login_form' name="cms_login_form" method="post" onsubmit="return cmsUserLoginActionOnSubmit('<?php echo $str; ?>');" style="margin:0;padding:0" >
	<div style="float:left;width:100%;background:#FFF">
            <div class="titleBarLay">
                <div class="float_R">
                    <div style="padding-top:12px"><img onclick="messageObj.close();" src="/public/images/crossImg_14_12.gif" border="0" class="searchCareer" /></div>
                </div>
                <div class="fontSize_16p bld"></div>
                <div class="clear_B" style="font-size:1px;line-height:1px">&nbsp;</div>
            </div>
            <div style="padding:10px 15px">
                <div class="OrgangeFont fontSize_16p bld" style="width:100%">Currently this feature is under development & the access is restricted to limited users only</div>
                <div style="display:none;" class="OrgangeFont fontSize_16p bld" ></div>
                <div class="lineSpace_5">&nbsp;</div>
                <div class="infoIcon fontSize_14p bld" style="padding-bottom:10px">Please enter you Email id and password to sign in</div>
                <div class="lineSpace_10">&nbsp;</div>
                <div class="fontSize_14p float_L" style="width:100%"></div>
                <div class="lineSpace_20">&nbsp;</div>
                <div>
					<div style="padding-bottom:10px">
						<div class="float_L" style="width:150px;line-height:20px">
							<div class="txt_align_r">Login Email Id: &nbsp;</div>
						</div>
						<div style="float:left;width:200px">
							<div style="margin-left:0px;line-height:20px"><input profanity="true" required="true" tip="multipleapply_email" name="contact_email" value="" id="contact_email" type="text" validate="validateEmail"  maxlength="100" minlength="10" style="width:170px" caption="email" /></div>
							<div style="display:none"><div class="errorMsg" id="contact_email_error"></div>
							</div>
						</div>
						<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
					</div>
					<div style="padding-bottom:10px" >
						<div class="float_L" style="width:150px;line-height:20px">
							<div class="txt_align_r">Password: &nbsp;</div>
						</div>
						<div class="float_L OrgangeFont" style="width:300px">
						<div><input type="password" id = "quickpassword" name = "quickpassword" validate = "validateStr" tip="password_id" minlength = "5" maxlength = "20" required = "true" caption = "password"  style="width:170px" /></div>
		                                <div class="errorPlace" style="display:block;line-height:14px"><div id="quickpassword_error" class="errorMsg"></div></div>
						</div>
						<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
					</div>
					<div align="center">
					<input type="submit" class="submitGlobal" value="Submit" id="submitbtn" />&nbsp;
					<input type="button" onclick="return messageObj.close();" class="cancelGlobal" value="Cancel" /></div>

				</div>
			</div>
		</div>
        </form>
    </div>
</div>

<script>
try {
    addOnBlurValidate(document.getElementById('cms_login_form'));
    addOnFocusToopTip(document.getElementById('cms_login_form'));
} catch (ex){

}
</script>
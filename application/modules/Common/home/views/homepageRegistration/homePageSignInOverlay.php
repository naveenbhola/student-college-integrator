<input type="hidden" id="redirectFlag" value="refresh"/>
<input type="hidden" id="redirectUrl" value="refresh"/>
<input type="hidden" id="loginflag" value="refresh"/>
<input type="hidden" id="loginaction" value="refresh"/>
<div id="usershortLoginOverlay" style = "display:none">
<form autocomplete="off" action="/user/Login/submit" onsubmit="new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResult(request.responseText,'_marketing');}, onFailure:function(request){javascript:showLoginResult(request.responseText,'_marketing')}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post" >
    <input type="text" style="border:0px" onfocus= "document.getElementById('username_marketing').focus();" readonly/>
    <input type="hidden" value="" id="mpassword_marketing" name="mpassword"/>
<!-- Left Panel Starts -->
    <div style="">
		<div style="font-size: 16px;display:none" id="HeaderforOverlay" class="bld mar_left_10p">Sign in to Shiksha</div>
<!-- Email starts-->
        <div class="lineSpace_10">&nbsp; </div>
        <div class="row">
            <div style="font-size: 11px; width: 93px; line-height: 20px;" class="float_L txt_align_r">Login Email Id:&nbsp; &nbsp;</div>
            <div style="float:left">
				<div><input type="text" style="width: 157px;" name="username" id="username_marketing" /></div>
				<div style="margin-top: 2px; display: none;" class="errorPlace">
					<div id="username_marketing_error" class="errorMsg"></div>
				</div>
			</div>
			<div class="clear_L withClear">&nbsp;</div>
        </div>
<!-- Email Ends -->

<!-- Password-->
        <div class="row" id="pass" style="display: block;padding-top:10px">
            <div>
              <div style="width: 93px; font-size: 11px; line-height: 20px;" class="float_L txt_align_r">Password:&nbsp; &nbsp;</div>
              <div style="float:left">
				<div>
                    <input type="password" style="width: 157px;" name="password" id="password_marketing" />
                </div>
				<div style="margin-top: 2px; display: none;" class="errorPlace"> 
					<div id="password_marketing_error" class="errorMsg"></div>
				</div>				
             </div>
			  <div class="clear_L withClear">&nbsp;</div>
            </div>
        </div>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="rememberme_marketing" class ="row">
            <div style="width: 90px; line-height: 20px;" class="float_L txt_align_r">&nbsp;</div>
            <div style="margin-left: 71px; font-size: 11px;"><input type="checkbox" id="remember_marketing" name="remember" checked="" />Remember me on this computer</div> 
            <div class="clear_L withClear">&nbsp;</div>
        </div>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="sendRequest" class="row">
			<div style="width: 70px;" class="float_L txt_align_r">&nbsp;</div>
			<div style="margin-left: 91px;">
				<input id="signInSubmitM" type="submit" style="border: 0pt none ;" class="continueBtn" value="Login" onclick="return isBlank(this.form,'_marketing');"  />
				<span style="display:block; line-height: 26px;"><a onclick="return sendMailtoUser1(this.form)" href="javascript:void(0);" id="forgetPasswdM">Forgot Password</a></span>
			</div>
			<div class="clear_L withClear">&nbsp;</div>
		</div>
	</div>
    <input type="text" style="border:0px" onfocus= "document.getElementById('username_marketing').focus();" readonly/>
</form>
</div>

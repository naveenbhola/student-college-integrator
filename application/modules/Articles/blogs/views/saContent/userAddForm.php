<div class="abroad-layer" style="width:712px ;display: none;">
	<div class="abroad-layer-head clearfix">
		<div class="abroad-layer-logo flLt"><i class="layer-sprite logo-image"></i></div>
		<a href="#" class="common-sprite close-icon flRt"></a>
	</div>

        <div class="abroad-layer-content clearfix" id="comment_layer" style="display: none;">
        	<form>
            <ul class="customInputs-large login-layer">
        	<li>
            	<p class="form-label signUp-label" style="width:107px !important;padding-top:6px;">Name:</p>
                <div class="signup-wrap" style="width: 255px;margin-left:124px;">
                	<input type="text" class="universal-text" value="" id='username' name='username'  maxlength="50" minlength="1" validate="validateDisplayName" caption="Name" required = "true"  />
                	<div style="display:none;"><div class="errorMsg" id="username_error" style="*float:left"></div></div>
                </div>
            </li>
        
        	<li>
            	<p class="form-label signUp-label" style="width:107px !important;padding-top:6px;">Email:</p>
                <div class="signup-wrap" style="width: 255px;margin-left:124px;">
                	<input type="text" class="universal-text" value="" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125"    />
                	<div style="display:none;"><div class="errorMsg" id="quickemail_error" style="*float:left"></div></div>
                </div>
            </li>

            <li>	
            	<div style="margin-left:39px;">
                    <p style="margin-left:39px;">Type in the characters you see in the picture below</p>
                    <div class="sec-code-box signup-wrap" style="margin-left:87px;">
                      <img  id="captcha_img" style="vertical-align:middle" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForContentComment" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "saContentCaptcha" alt="" /> &nbsp;
                      <input type="text" style="width:100px;" class="universal-text" id = "securityCode_content" name = "securityCode_ForCA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" />
                      <div style="padding-left:19px;"><div class="errorMsg" id="securityCode_error"></div></div>
                    </div>
                 </div>
            </li>
            <li>
            	<div style="margin-left:150px;">
			<?php
				// incase of exam page ...
				if($examPageObj && $examPageObj instanceof AbroadExamPage)
				{
					$contentType = 'EXAM';
			?>
			<a id="addUserFormButton"  uniqueattr = "SA_SESSION_ABROAD_<?php echo $contentType?>_PAGE/submitCommentLayer" href="javascript:void(0);" onclick="if(validateUserForm())  {addUserValues(); }  " class="button-style" style="text-align:center; font-size:18px;">Submit</a>  				
			<?php	
				}
				else
				{
                    if(strtoupper($content['data']['type'])){
                        $contentType = strtoupper($content['data']['type']);    
                    }
			?>
			<a id="addUserFormButton" uniqueattr = "SA_SESSION_ABROAD_<?php echo $contentType?>_PAGE/submitCommentLayer" href="javascript:void(0);" onclick="if(validateUserForm())  { addUserValues();<?php if($contentType=="examContentPage"){echo "";}else{echo "postComment('','');" ;} ?> }  " class="button-style" style="text-align:center; font-size:18px;">Submit</a>  				
			<?php
				}
			?>
	            </div>
            </li>
            </ul>
            </form>
        </div>
</div>
 	 	

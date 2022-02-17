
<div class="newReg-container">
    <form novalidate="novalidate" accept-charset="utf-8" autocomplete="off" method="post" id="registrationForm_<?php echo $regFormId; ?>">

        <div class="rgstr-heading">
            <a href="#" class="rgstr-title">Log In on Shiksha</a>
            <a href="#" class="reg-rmv flRt">&times;</a>
        </div>
        <?php if(!empty($userEmailId)) { ?>
    	<div class="join-shiksha-sec">
            <strong class="title"><?=$userEmailId?> is already registered with us. Please Log In.</strong>
        </div>
        <?php } ?>
        <div id='forgotSuccess' class="join-shiksha-sec resetPwrd-sec" style="display:none">
            <strong class="title">Reset password instructions sent successfully to your current Email Id</strong>
        </div>

    	<div class="NewReg-form">
        	<ul>
            	<li id="email_block_<?php echo $regFormId; ?>">
                    <input type="email" caption="your email address" label="email" mandatory="1" default="Email" maxlength="125" class="NewReg-field loginLayerFields" value="<?php if(!empty($userEmailId)) { echo $userEmailId; }?>" placeholder="Email" regfieldid="email" id="email_<?php echo $regFormId; ?>" name="user_email" pattern="^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$">
                    <div style="display:none">
                        <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </li>
                <li id="password_block_<?php echo $regFormId; ?>" style="margin-bottom:4px;">
                    <input type="password" caption="your password" label="email" mandatory="1" default="Password" class="NewReg-field loginLayerFields" value="" placeholder="Password" regfieldid="password" id="password_<?php echo $regFormId; ?>" name="user_pass">
                    <div style="display:none">
                        <div class="errorMsg" id="password_error_<?php echo $regFormId; ?>"></div>
                    </div>
                </li>
            </ul>
            <div style="width:100%; float:left; margin-bottom:8px;">
                <div class="errorMsg" id="login_error"></div>
            </div>
            <input type="button" class="common-btn clearFix" id="loginButton" value="LOG IN" />
            <div class="forgot-sec">
            	
                <a class="frgtpswrd" aria-expanded="false" id="newDialogPage" aria-owns="dialogPage" aria-haspopup="true" href="#forgtPassDiv" data-rel="dialog" class="ui-btn ui-btn-inline ui-corner-all" data-transition='none'  style="">Forgot password?</a> <br>
    			<span>New User? <a href="#" id="registerFromLogin">Register here.</a></span>
            </div>
        </div>
    </form>
</div>


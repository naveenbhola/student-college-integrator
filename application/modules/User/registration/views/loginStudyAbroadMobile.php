<script>var userExists = 0;</script>
<article class="content-inner2 clearfix">
    <div style="margin:5px 0 15px; font-weight:normal" class="wrap-title tac login-layer-heading">Please Log In Into Your Account Here</div>
    <form method="post" id="userLogin" name="userLogin" onkeypress = "submitOnEnter(event);">
        <ul class="form-display">
            <li>
                <input type="text" id="loginOverlayEmail" name="username" placeholder="Email ID" class="universal-txt" data-enhance = "false" />
                <input type="hidden" id="forgotPasswordEmail" value="" />
                <div style="display:none"><div class="errorMsg error-msg" id="loginOverlayEmail_error"></div></div>
                <div style="display:none"><div class="errorMsg error-msg" id="forgotPasswordEmail_error"></div></div>
            </li>
            <li>
                <input id="loginOverlayPassword" name="password" type="password" autocomplete="off" placeholder="Password" class="universal-txt" data-enhance="false"/>
				<div style="display:none"><div class="errorMsg error-msg" id="loginOverlayPassword_error"></div></div>
            </li>
            <li style="margin:15px 0 15px">
                <a class="btn btn-default btn-full" href="javascript:void(0)" id = "submitLogin" onclick="submitLogin();" uniqueattr="SA_SESSION_ABROAD_PAGE/loginForm">Login</a>
            </li>
            <li class="tac">
                <a href="javascript:void(0)" onclick="submitForgotPassword();">Forgot Password?</a>
            </li>
        </ul>
        <input type="hidden" value="on" name="rem-pass" id="rem-pass"/>
    </form>
</article>
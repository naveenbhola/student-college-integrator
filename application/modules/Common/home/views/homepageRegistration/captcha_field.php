<div class="find-field-row">
	<?php
    	if($logged !== "Yes" ) {
    ?>
    <div id="home_study_abroad_securityCode_block">
    	<p>Type in the character you see in the picture below</p>
    	<div>
        	<img align = "absbottom" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome_sb" width="100" height="34" id = "secureCode_sb"/>
        	<input class="form-txt-field" style="width:145px;" type="text" blurMethod='trackEventByGA("EnterCaptcha","captcha entered by user");' name ="homesecurityCode_sb" id = "home_study_abroad_securityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
            <div class="errorPlace" style="display:none;">
                <div class="errorMsg" id= "home_study_abroad_securityCode_error"></div>
            </div>
     	</div>
	</div>
</div>
<div class="find-field-row">
	<div id="homepage_study_abroad_cagree">
    	<input type="checkbox" name="cAgree" id="cAgreeAbroad" /> I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
        <div class="clearFix"></div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
        	<div class="errorMsg" id= "cAgreeAbroad_error"></div>
        </div>
    </div>
    <?php
    	}
    ?>
</div>
<script>
//if(isUserLoggedIn) {
//	if($("homepage_study_abroad_cagree"))
  //  $("homepage_study_abroad_cagree").style.display = "none";
    //if($("home_study_abroad_securityCode_block"))
    //$("home_study_abroad_securityCode_block").style.display = "none";
//} else {
  //  $("homepage_study_abroad_cagree").style.display = "block";
    //$("home_study_abroad_securityCode_block").style.display = "block";
//}
</script>

<script>
    var userExists = 0;
</script>
<article class="content-inner2 clearfix" style="margin:10px; background:#fff;">
    <div style="margin:5px 0 15px; font-weight:normal; text-align: left; font-size:13px;" class="wrap-title tac">Login</div>
    <form method="post" id="userLogin2" name="userLogin2" onkeypress = "submitOnEnter(event);">
        <ul class="form-display">
            <li>
                <!--<input type="text" value="Email ID" class="universal-txt" data-enhance = "false" />-->
                <input type="text" id="loginOverlayEmail" name="username" placeholder="Email ID" class="universal-txt" data-enhance = "false" />
                <input type="hidden" id="forgotPasswordEmail" value="" />
                <div style="display:none"><div class="errorMsg error-msg" id="loginOverlayEmail_error"></div></div>
                <div style="display:none"><div class="errorMsg error-msg" id="forgotPasswordEmail_error"></div></div>
            </li>
            <li>
                <!--<input type="text" value="Mobile no." class="universal-txt" data-enhance = "false" /> -->
                <input id="loginOverlayPassword" name="password" type="password" autocomplete="off" placeholder="Password" class="universal-txt" data-enhance="false"/>
				<div style="display:none"><div class="errorMsg error-msg" id="loginOverlayPassword_error"></div></div>
            </li>
            <li style="margin:15px 0 15px">
                <a class="btn btn-default btn-full" href="javascript:void(0)" id = "submitLogin" onclick="submitLogin();" uniqueattr="SA_SESSION_ABROAD_PAGE/loginForm">Login</a>
            </li>
            <li class="tac">
                <a class="flLt" href="javascript:void(0)" onclick="submitForgotPassword();">Forgot Password?</a>
			<?php if(!$skipSignupLink){ ?>
                <span class="flRt" style="font-size:12px;">New member?<a href="javascript:void(0);" onclick="showSignUplayerWithoutLoginOption();"> Signup!</a></span>
            <?php } ?>
				<div class="clearfix"></div>
            </li>
        </ul>
        <input type="hidden" value="on" name="rem-pass" id="rem-pass"/>
        <?php
            if($trackingPageKeyId > 0){ ?>
            <input id="tracking_page_key" name="tracking_page_key" type="hidden" value="<?php echo $trackingPageKeyId; ?>">
            <input id="page_referrer" name="page_referrer" type="hidden" value="<?php echo $customReferer; ?>">
            <input id="refererTitle" name="refererTitle" type="hidden" value="<?php echo $refererTitle; ?>">
            <input id="saABTracking" name="saABTracking" value="yes" type="hidden">
            <input id="conversionType" type="hidden" value="<?php echo $MISTrackingDetails['conversionType']; ?>">
            <input type='hidden' id='fileUrl' name='fileUrl' value='<?php echo htmlentities($url !="" ? base64_encode($url) : ''); ?>' />
            <input type='hidden' id='examId' value='<?php echo $examId; ?>' />
            <input type='hidden' id='counselorId' value='<?php echo $counselorId; ?>' />
            <input id="keyName" type="hidden" value="<?php echo $MISTrackingDetails['keyName']; ?>">
            <input id="shortlistpagetype" type="hidden" value="<?php echo $MISTrackingDetails['page']; ?>">
            <?php if($MISTrackingDetails['conversionType']=='compare'){?>
                <input type='hidden' id='compCourseId' value='<?php echo $compCourseId; ?>' />
                <input type='hidden' id='compSource' value='<?php echo $compSource; ?>' />
            <?php } ?>
            <?php if($contentId > 0){ ?>
                <input id="contentId" type="hidden" value="<?php echo $contentId; ?>">
                <input id="contentType" type="hidden" value="<?php echo $contentType; ?>">
            <?php }else if($courseId >  0){ ?>
                <input id="courseId" type="hidden" value="<?php echo $courseId; ?>">
                <input id="sourcePage" type="hidden" value="<?php echo $sourcePage; ?>">
                <input id="widget" type="hidden" value="<?php echo $widget; ?>">
            <?php }else if($universityId > 0){ ?>
                <input id="universityId" type="hidden" value="<?php echo $universityId; ?>">
            <?php }else if($scholarshipId > 0 && $MISTrackingDetails['conversionType'] == "response"){ ?>
                <input id="listingTypeForResponse" name="listingTypeForBrochure" value="scholarship" type="hidden">
                <input id="listingTypeIdForResponse" name="listingTypeIdForBrochure" value="<?php echo $scholarshipId; ?>" type="hidden">
                <input id="sourcePage" type="hidden" value="<?php echo $sourcePage; ?>">
                <input id="widget" type="hidden" value="<?php echo $widget; ?>">
            <?php } ?>
        <?php } ?>
    </form>
</article>
<script type="text/javascript">
    var isLoginPage = true;
</script>
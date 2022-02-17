<?php
//load page header
$headerComponents = array(
	'css'               => array('studyAbroadCommon'),
	'canonicalURL'      => '',
	'title'             => 'Login Page',
	'metaDescription'   => '',
    'metaKeywords'      => '',
	'pgType'	        => 'abroadInLineLoginPage'
);

global $feedbackArray;
$feedbackArray = array('showFeedback' => false);
$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<div class="home-wrapper clearfix">

    <div class="clearfix" style="height:1px;"></div>
    <div class="abroad-layer-content clearfix" style="width:530px; border:1px solid #eee; margin:30px auto 0 auto; padding:0;">
        <div class="abroad-layer-title" style="font-weight:normal; font-size:18px; border-bottom:1px solid #eee; padding:10px;margin:0; text-align:center; color:#111">Please login to continue</div>
            <form onkeypress="submitOnEnter(event);" name="userLogin" id="userLogin" method="post">
				<ul class="customInputs-large login-layer" style="padding:10px;">
                    <li>
						<p class="form-label">Login Email Id:</p>
                        <div class="field-wrap">
                            <input type="text" class="universal-text" name="username" id="loginOverlayEmail">
							<input type="hidden" value="" id="forgotPasswordEmail">
							<div style="display:none"><div id="loginOverlayEmail_error" class="errorMsg"></div></div>
							<div style="display:none"><div id="forgotPasswordEmail_error" class="errorMsg"></div></div>
                        </div>
                    </li>
                    <li>
						<p class="form-label">Password:</p>
						<div class="field-wrap">
                            <input type="password" class="universal-text" autocomplete="off" name="password" id="loginOverlayPassword">
							<div style="display:none"><div id="loginOverlayPassword_error" class="errorMsg"></div></div>
							<div class="rem-box">
								<input type="checkbox" id="rem-pass" name="rem-pass" checked="checked">
								<label for="rem-pass">
									<span class="common-sprite"></span>Remember me on this computer
								</label>
							</div>
							<a class="button-style medium-button" uniqueattr="SA_SESSION_ABROAD_PAGE/loginForm" onclick="submitLogin();" id="submitLogin" href="javascript:void(0)"><i class="common-sprite login-icon2"></i> Login</a> &nbsp;
						</div>
					</li>
				</ul>
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
				<?php if($MISTrackingDetails['conversionType']=='compare'){?>
					<input type='hidden' id='compCourseId' value='<?php echo $compCourseId; ?>' />
					<input type='hidden' id='compSource' value='<?php echo $compSource; ?>' />
				<?php } ?>
				<input id="shortlistpagetype" type="hidden" value="<?php echo $MISTrackingDetails['page']; ?>">
					<?php if($contentId > 0){ ?>
						<input id="contentId" type="hidden" value="<?php echo $contentId; ?>">
					<?php }else if($courseId >  0){ ?>
						<input id="courseId" type="hidden" value="<?php echo $courseId; ?>">
						<input id="sourcePage" type="hidden" value="<?php echo $sourcePage; ?>">
						<input id="widget" type="hidden" value="<?php echo $widget; ?>">
					<?php }else if($universityId > 0){ ?>
						<input id="universityId" type="hidden" value="<?php echo $universityId; ?>">
					<?php }else if($scholarshipId > 0 && $MISTrackingDetails['conversionType'] == "response"){
                ?>
		            <input id="listingTypeForResponse" name="listingTypeForBrochure" value="scholarship" type="hidden">
		            <input id="listingTypeIdForResponse" name="listingTypeIdForBrochure" value="<?php echo $scholarshipId; ?>" type="hidden">
		            <input id="sourcePage" type="hidden" value="<?php echo $sourcePage; ?>">
					<input id="widget" type="hidden" value="<?php echo $widget; ?>">
                <?php
                } ?>
				<?php } ?>
			</form>
		</div>
		<div style="width:530px; margin:10px auto 20px auto; font-size:14px;">
		<a class="font-14 flLt" onclick="submitForgotPassword();" href="javascript:void(0)" style="margin-left:140px;">Forgot Password?</a>
	<?php if(!$skipSignupLink){ 
		if($trackingPageKeyId == 1979){ ?>
		<script>
		var singleSignUpFormObj = null;
        var singleSignUpFormObj =  {	            
	        'tkey': <?=$trackingPageKeyId;?>,
	        'rf' : document.referrer            
		};
		</script>
					<p class="flRt">New member? <a href="Javascript:void(0);" onclick="loadSignUpFromLoginPage('signUpViaLogin',<?php echo $trackingPageKeyId; ?>,singleSignUpFormObj);">Signup here!</a></p>
		<?php }else{?>
					<p class="flRt">New member? <a href="Javascript:void(0);" onclick="loadSignUpFromLoginPage('signUpViaLogin',702);">Signup here!</a></p>
		<?php }?>


	<?php } ?>
		<div class="clearfix"></div>
	</div>
	<div style="display:none;" id="resetPasswordPanel">
        <h3 style="margin:5px 0 0 10px;">Reset password instructions sent successfully to your current email id</h3>
        <div style="padding-top:15px;width:150px;">
	    <p style="padding:0;width:105px; margin-left:10px; text-align: left;" class="form-label">Login Email Id:</p>
                <div class="field-wrap"  style="margin-left:110px;">
			<p id="resetPasswordLayerEmailDiv"></p>
		</div>
        </div>
	<br>
	<div style="margin:5px 0 0 10px;padding-top:10px;">
			<a style="font-size:13px;width: 100px;text-align:center;" class="button-style medium-button" onclick="hideAbroadOverlay();" href="javaScript:void(0)">Ok</a>
	</div>
    </div>
</div>
<?php
	$footerComponents = array(
		'js'                => array('json2'),
        'nonAsyncJSBundle'=>'sa-login',
		'asyncJSBundle'=>'async-sa-login'
	);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script>
	var isLoginPage = true;
	function submitForgotPassword()
	{
		// copy login email to forgot password email
		var loginEmail = $j('#loginOverlayEmail').val();
		$j('#forgotPasswordEmail').val(loginEmail);
		//copy login email to reset password layer div as well
		$j('#resetPasswordLayerEmailDiv').html(loginEmail);
		// hide error  divs of loginoverlay
		$j('#loginOverlayEmail_error').hide();
		$j('#loginOverlayPassword_error').hide();
		$j('#forgotPasswordEmail_error').show();
		shikshaUserRegistration.sendForgotPasswordMail('');
		return false;
	}
	function submitLogin()
	{
		$j('#forgotPasswordEmail_error').hide();
		$j('#loginOverlayEmail_error').show();
		$j('#loginOverlayPassword_error').show();
		shikshaUserRegistration.doLogin();
	}
	// to submit on enter
	function submitOnEnter(event){
		if (event.charCode ==13 || event.keyCode == 13) {
			$j("#submitLogin").trigger("click");
			event.preventDefault();
		}
	}
	$j(document).ready(function(){
		$j('.home-wrapper').height($j(window).height()-($j('#header').height()));
		setCookie('applicationProcessSignUp', '',-1);
	});
</script>

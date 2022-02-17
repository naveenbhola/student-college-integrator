<div class="Reglayer-bg"></div>

<div class="Reg-layer">
	<?php 
	//LHS text
		if(!$showFormWithoutHelpText){ 
			$this->load->view('registration/registrationLHSText',array('customHelpText' => $customHelpText));
		}
	?> 	
	<div class="regRgt-col" >

		<div id="regSliders" style="width:100%; height:485px;">
			<div id='loginLayer' class="slide p0" tabindex="-1">
				<div class="regRgt-tabs" id="regRgtabs">
					<p class="Signup">Login</p>
					<p class="login">New to Shiksha? <a href="javascript:void(0);" class='openSignupForm' onclick="">&nbsp;Sign Up</a></p>
					<p class="clear"></p>
				</div>	
			
				<div class="regTab-cont logPad">
		            <form method="post" onsubmit="return false;" action="#" id="loginForm_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" tabindex="-1">
			            <div class="regtbs clearfix" id="regContTab2">
			                <div class="reg-form invalid" type="input" regfieldid="email" id="email_block_<?php echo $regFormId; ?>">
								<div class="ngPlaceholder">Email address</div>
									<input class="ngInput" id="email_<?php echo $regFormId; ?>" type="email" name="email" default="Email address" caption="Email address" label="email" mandatory="1" autocomplete="off" maxlength="125" regfieldid="email" >
									<div class="input-helper" style="display:block;">
										<div class="up-arrow"></div>
										<div class="helper-text">Please enter Email address.</div>
									</div>
							</div>
							<div class="reg-form invalid" type="input" regfieldid="password" id="password_block_<?php echo $regFormId; ?>">
								<div class="ngPlaceholder">Password</div>
								<input class="ngInput psd" type="password" name="password" id="password_<?php echo $regFormId; ?>" mandatory="1" caption="Password" label="password" autocomplete="off" regfieldid="password" maxlength="25" minlength = "6" >
								<span class="hideblk">show</span>
	   							<span class="hideblk ih">hide</span>
								<div class="input-helper">
									<div class="up-arrow"></div>
									<div class="helper-text">Please enter Password.</div>
								</div>
							</div>
							<div class="fgtpss-link">
								<a href="javascript:void(0);" class='fgtPswd' regFormId="<?php echo $regFormId; ?>" >Forgot Password?</a>
							</div>					
							<a class="reg-btn submitLoginButton" id = 'loginSubmit_<?php echo $regFormId; ?>' regformid="<?php echo $regFormId; ?>" type="submit" tabindex="-1">Login</a>
						</div>
					</form>
		        </div>
	        </div>

	        <div id="resetPasswordPanel" class="slide p0" tabindex="-1">
		        <div class="regRgt-tabs" id="regRgtabs">
					<a href="javascript:void(0);" regformid="<?php echo $regFormId; ?>" class="bck-icn backToLogin"> <i class="bck-arrw"></i> Back</a>
					<p class="clear"></p>
				</div>	
				<div class="regTab-cont logPad rstpnl" >
					<form id="resetPasswordForm_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" onsubmit="return false;" novalidate>
						<div class="regtbs clearfix" id="regContTab1">
							<div>
								<p class="field-title">Reset Password</p>
								<div class="rstPse-txt">
									<p>Enter your email address to reset your password.</p>
									<p>You may need to check your spam folder.</p>
								</div>
							</div>
							<div class="reg-form invalid" type="input" regfieldid="email" id="emailReset_block_<?php echo $regFormId; ?>">
								<div class="ngPlaceholder">Email address</div>
									<input class="ngInput fpe" id="emailReset_<?php echo $regFormId; ?>" type="email" name="email" default="Email Id" caption="Email address" label="email" mandatory="1" autocomplete="off" maxlength="125" regfieldid="emailReset">
									<div class="input-helper" style="display:block;">
										<div class="up-arrow"></div>
										<div class="helper-text">Please enter Email address.</div>
									</div>
							</div>
							<a href="javascript:void(0);" class="reg-btn mt50 rstpswlink" regformid="<?php echo $regFormId; ?>" type="submit" >Send Reset Link</a>
						</div>
					</form>						
				</div>

				<div class="msg-box rstsucs mt100 ih">	
					<i class="suc-icn"></i>
					<p class="mb10">A password reset link has been sent to your inbox.</p>
				</div>		
			</div>
		</div>

	</div>	
	<a href="javascript:void(0);" class="regClose">&times;</a>
</div>

<?php
	$customValidations = array('email'=>'Email', 'password'=>'Password', 'emailReset'=>'Email');
?>

<script>
	if(typeof(userRegistrationRequest['<?php echo $regFormId; ?>']) == 'undefined'){
		userRegistrationRequest['<?php echo $regFormId; ?>'] = new UserRegistrationRequest('<?php echo $regFormId; ?>');
	}
	userRegistrationRequest['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidations); ?>);

initiateSimpleSlider($j);
$j("#regSliders").simpleSlider({ speed: 500, regFormId:'<?php echo $regFormId; ?>' });	
</script>

<a href="javascript:void(0);" id="slideLeft_<?php echo $regFormId; ?>" class="ih"> Left </a>
<a href="javascript:void(0);" id="slideRight_<?php echo $regFormId; ?>" class="ih"> Right </a>

<script type="text/javascript">
	$j(document).keydown(function (e) 
	{
	    var keycode1 = (e.keyCode ? e.keyCode : e.which);
	    if (keycode1 == 0 || keycode1 == 9 && (e.target.className == 'fgtPswd' || e.target.className == 'ngInput fpe' || e.target.className == 'bck-icn backToLogin')) {
	        e.preventDefault();
	        e.stopPropagation();
	    }

	    if(keycode1 == 13){
	    	registrationForm.doLogin($j('#loginSubmit_<?php echo $regFormId; ?>'));
	    }
	});
</script>

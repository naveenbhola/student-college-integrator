<div class="SgUp-lyr">
	<div id="loginLayer">
		<div class="head">
			<p class="head-title">Login</p>
			<?php if($formCustomData['callbackFunctionParams']['AMP_FLAG'] != true){?>
			<a href="javascript:void(0);" data-rel="back" class="lyr-cls <?php if($directFlow == 'no') { echo 'bck2'; } ?>">&times;</a>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="Sgup-FrmSec mt10p" id="regContTab2">
			<form method="post" style="border:none; outline:none;" onsubmit="return false;" action="#" id="loginForm_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" tabindex="-1">
				<div class="reg-form invalid" data-enhance="false" type="input" regfieldid="email" id="email_block_<?php echo $regFormId; ?>"> <!--  invalid invalidFld -->
					<div class="ngPlaceholder">Email address</div>
					<input class="ngInput" id="email_<?php echo $regFormId; ?>" type="email" name="email" default="Email address" caption="Email address" label="email" mandatory="1" autocomplete="off" maxlength="125" regfieldid="email" >
					<div class="input-helper" style="display:block;">
						<div class="up-arrow"></div>
						<div class="helper-text">Please Enter Email Id.</div>
					</div>
				</div>
				<div class="reg-form invalid" data-enhance="false" type="input" regfieldid="password" id="password_block_<?php echo $regFormId; ?>">
					<div class="ngPlaceholder">Password</div>
					<input class="ngInput psd psw" type="password" name="password" id="password_<?php echo $regFormId; ?>" mandatory="1" caption="Password" label="password" autocomplete="off" regfieldid="password" maxlength="25" minlength = "6">
					<span class="hideblk">show</span>
					<span class="hideblk ih">hide</span>
					<div class="input-helper">
						<div class="up-arrow"></div>
						<div class="helper-text">Please Enter Password.</div>
					</div>
				</div>
				<div class="fgtpss-link">
					<a href="javascript:void(0);" class='fgtPswd' regFormId="<?php echo $regFormId; ?>">Forgot Password?</a>
				</div>
				<div class="btn-rltv rst-lgn btn-bttm">
					<a class="reg-btn submitLoginButton" id='loginSubmit_<?php echo $regFormId; ?>' regformid="<?php echo $regFormId; ?>" type="submit" tabindex="-1">Login</a>
					<div class="Sgup-box">
						<p class="newSks <?php if($directFlow == 'no') { echo 'gotoSignUp2'; } else { echo 'gotoSignUp'; } ?>">New to Shiksha? Sign Up here</p>
						<!-- <a href="javascript:void(0);" class="sgup-btn">Sign Up</a> -->
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="forgotPasswordLayerOld" style="display:none">
		<div class="head">
			<p class="head-title"><a href="javascript:void(0);" class="backToLogin"><i class="bck-arrw"></i> Back</a></p>
			<?php if(!$customFormData['callbackFunctionParams']['AMP_FLAG']){?>
			<a href="javascript:void(0);" class="lyr-cls resetLyrCls">&times;</a>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="Sgup-FrmSec rstpnl">
			<form id="resetPasswordFormOld_<?php echo $regFormId; ?>" regFormId="<?php echo $regFormId; ?>" onsubmit="return false;" novalidate>
				<div class="rst-pasTitle">Reset Password</div>
				<div><p class="field-title">Enter your email address to reset your password. You may need to check your spam folder.</p></div>
				<div class="reg-form invalid" data-enhance="false" type="input" regfieldid="email" id="emailReset_blockOld_<?php echo $regFormId; ?>">
					<div class="ngPlaceholder">Email address</div>
					<input class="ngInput fpe" id="emailResetOld_<?php echo $regFormId; ?>" type="email" name="email" default="Email address" caption="Email address" label="email" mandatory="1" autocomplete="off" maxlength="125" regfieldid="emailReset">
					<div class="input-helper">
						<div class="up-arrow"></div>
						<div class="helper-text">Please Enter Password.</div>
					</div>
				</div>
					
				<div class="btn-rltv clear rst-lgn btn-bttm">
					<a href="javascript:void(0);" class="reg-btn mt50 rstpswlink" regformid="<?php echo $regFormId; ?>" type="submit" >Send Reset Link</a>
					<div class="Sgup-box">
						<p class="newSks <?php if($directFlow == 'no') { echo 'gotoSignUp2'; } else { echo 'gotoSignUp'; } ?>">New to Shiksha?  Sign Up here.</p>
						<!-- <a href="javascript:void(0);" class="sgup-btn ">Sign Up</a> -->
					</div>
				</div>
			</form>			
		</div>

		<div class="msg-box rstsucs">	
			<i class="suc-icn top-pos"></i>
			<p class="mb10">A password reset link has been sent to your inbox.</p>
		</div>	
	</div>
</div>

<?php
	$customValidations = array('email'=>'Email', 'password'=>'Password', 'emailReset'=>'Email');
?>

<?php if(empty($isAmpPage) && $ampPageFlag != 'yes'){ ?>
	<script>
		if(typeof(userRegistrationRequest['<?php echo $regFormId; ?>']) == 'undefined'){
			userRegistrationRequest['<?php echo $regFormId; ?>'] = new UserRegistrationRequest('<?php echo $regFormId; ?>');
		}
		userRegistrationRequest['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidations); ?>);
	</script>
<?php } ?>
<?php $this->load->view('shortlistRegistrationHeader'); ?>

<script type="text/javascript">
base_url 						= "<?php echo SHIKSHA_HOME;?>";
is_user_logged_in 				= "<?php echo $user_logged_in;?>";
logged_in_userid 				= "<?php echo $logged_in_userid;?>";
</script>

<header id="page-header" class="clearfix">
    <div class="head-group">
        <a href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
        <h1><div class="left-align">Register on Shiksha</div></h1>
    </div>
</header>

<div id="popupBasicBack" data-enhance='false'></div>
<div class="content-wrap2">
	<?php if($user_logged_in == 'false') { ?>
		<section class="clearfix" id="alreadyRegistered">
			<div class="btn-section login-btn" data-enhance="false">
				<input type="button" class="l-btn" value="Already Registered? Login Here" onClick="if(shikshaUserRegistration.checkCallBackParamsInRegForm()){window.location='<?=$this->config->item("loginUrl")?>';}else{ shikshaUserRegistration.sendCallBackParamsToLoginPage();} " />
			</div>
			<hr class="h-rule" />
		</section>
	<?php } ?>
	
	<section class="content-child clearfix" style="padding-bottom: 0" id="joinShikshaLabel" >
		<p class="login-title" style="margin-bottom:0.2em" id="newUserRegister">Join Shiksha Now</p>
		<p class="login-sub-title" id="joinShiksha">Get free alerts on Exam updates, Admission deadline, College rankings and more </p>
	</section>

	<section class="content-child clearfix" style="padding: 0.5em 0.8em; display:none;">
		<div data-enhance="false"><strong>Study Preference: <br/></strong>
			<label><input id="prefindia" name="abroad" type="radio"  onClick="$('studyAbroadForm').style.display='none'; $('studyIndiaForm').style.display='';" checked > India</label> &nbsp;&nbsp;&nbsp;
			<label><input id="prefabroad" name="abroad" type="radio" onClick="$('studyIndiaForm').style.display='none'; $('studyAbroadForm').style.display='';"> Abroad</label>
		</div>
	</section>
	
	<section class="content-child clearfix" id="studyIndiaForm">
		<?php echo Modules::run('registration/Forms/LDB',NULL,'mobile',array('registrationSource' => 'MOBILE_LDB_REGISTRATION_FORM', 'tracking_keyid'=>$tracking_keyid)); ?>
	</section>
	<?php $this->load->view('registration/common/OTP/mobileOTPVerification'); ?> 
</div>
<div>
	<div id="responseCreationFormHolder">
	</div>
</div>

<?php $this->load->view('shortlistRegistrationFooter'); ?>
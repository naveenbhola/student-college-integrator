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
<div class="content-wrap2"style="margin-top:20px;">
	<?php if($user_logged_in == 'false' && $categoryPageData['count'] >= 5) { ?>
		<section style="margin:20px 0 0px 0;">
			<div class="reg-course-dt">
				<p class="reg-course-txt">Based on the details you provided, there are</p>
				<ul class="reg-course-dtls">
					<li>
						<div class="cours-dtls"><i class="reg-sprite cours-dtls-coll"></i></div>
						<strong class="cours-dtls-hd"><?php echo $categoryPageData['count'];?></strong>
						<p class="cours-dtls-txt">MBA Colleges In <?php echo $categoryPageData['cityName'];?></p>
					</li>
					<li class="even-box">
						<p class="cours-dtls-wth">With</p>
					</li>
					<!-- -->
					<li>
						<div class="cours-dtls"><i class="reg-sprite cours-dtls-place"></i></div>
						<strong class="cours-dtls-hd"><?php echo $falseAverageSalary;?></strong>
						<p class="cours-dtls-txt">Avg. Placement Salary</p>
					</li>
					<li class="even-box">
						<p class="cours-dtls-wth">With</p>
					</li>
					<li>
						<div class="cours-dtls"><i class="reg-sprite cours-dtls-rate"></i></div>
						<strong class="cours-dtls-hd"><?php echo $falseCourseRatingData;?></strong>
						<p class="cours-dtls-txt">Ratings</p>
					</li>
				</ul>
			</div>
		</section>
	<?php } ?>
	
	<section class="content-child clearfix" id="joinShikshaLabel" >
		<?php if($user_logged_in == 'false') { ?>
			<div class="clearfix" id="alreadyRegistered" style="display: block;margin-bottom: 10px;text-align: right;font-size:11px;">
				<span style="color:#999999;">Already Registered ?</span>&nbsp;<a href="<?=$this->config->item("loginUrl")?>"><strong>Login</strong></a>
			</div>
		<?php } ?>
	<?php ?>
		<p class="login-title" style="margin-bottom:0.2em" id="newUserRegister">
		<?php if($categoryPageData['count'] < 5) { ?>
			View details of colleges by filling the form below
		<?php } else { ?>
		View details for these <?php echo $categoryPageData['count'];?> colleges by filling the form below</p>
		<?php } ?>
	</section>

	<section class="content-child clearfix" style="padding: 0.5em 0.8em; display:none;">
		<div data-enhance="false"><strong>Study Preference: <br/></strong>
			<label><input id="prefindia" name="abroad" type="radio"  onClick="$('studyAbroadForm').style.display='none'; $('studyIndiaForm').style.display='';" checked > India</label> &nbsp;&nbsp;&nbsp;
			<label><input id="prefabroad" name="abroad" type="radio" onClick="$('studyIndiaForm').style.display='none'; $('studyAbroadForm').style.display='';"> Abroad</label>
		</div>
	</section>
	
	<section class="content-child clearfix" id="studyIndiaForm">
		<?php echo Modules::run('registration/Forms/LDB',NULL,'mobile',array('registrationSource' => 'MOBILE_REGISTRATION_SEARCH_BY_EXAM','trackingPageKeyId'=>$tracking_keyid)); ?>
	</section>
	<?php $this->load->view('registration/common/OTP/mobileOTPVerification'); ?> 
</div>

<?php $this->load->view('shortlistRegistrationFooter'); ?>
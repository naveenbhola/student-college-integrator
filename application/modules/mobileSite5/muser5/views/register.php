<!DOCTYPE html>
<html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
	<script type="text/javascript">
	var t_pagestart=new Date().getTime();
	</script>
        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>Education India - Search Colleges, Courses, Institutes, University, Schools, Degrees - Forums - Results - Admissions - Shiksha.com</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <base href="<?php echo base_url(); ?>" />

                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                <meta name = "format-detection" content = "telephone=no" />
                <meta name = "format-detection" content = "address=no" />

                <meta name="description" content="Search Colleges, Courses, Institutes, University,
                Schools,Degrees, Education options in India. Find colleges and universities in India & abroad.
                Search Shiksha.com Now! Find info on Foreign University, question and answer in education and career
                forum.Ask the education and career counselors."/>
                <meta name="keywords" content="Shiksha, education, colleges,universities, institutes,career, career options, career prospects,
                engineering, mba, medical, mbbs,study abroad, foreign education, college, university, institute,
                courses, coaching, technical education, higher education,forum, community, education career experts,
                ask experts, admissions,results, events,scholarships"/>

                <meta name="copyright" content="Shiksha.com" />
                <meta name="content-language" content="EN" />
                <meta name="author" content="www.Shiksha.com" />
                <meta name="resource-type" content="document" />
                <meta name="distribution" content="GLOBAL" />
 
                 <meta name="revisit-after" content="1 day" />
                <meta name="rating" content="general" />
                <meta name="pragma" content="no-cache" />
                <meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
                <meta name="robots" content="ALL" />

                <meta name="HandheldFriendly" content="True">
                <meta name="MobileOptimized" content="320"/>
                <meta http-equiv="cleartype" content="on">

                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

                <meta http-equiv="x-dns-prefetch-control" content="off">

                <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="/pwa/public/images/apple-touch-icon-v1.png">
	<link rel="publisher" href="https://plus.google.com/+shiksha"/> 

        <link rel="dns-prefetch" href="//ask.shiksha.com">

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion("mcommon",'nationalMobile'); ?>" >
        <link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion("mobile5",'nationalMobile'); ?>" >

	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion("jquery1.3.2",'nationalMobileVendor'); ?>" >

	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
	<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("userRegistrationFormMobile","nationalMobile"); ?>"></script>
	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>
            $(document).on('mobileinit', function () {
            $.mobile.ignoreContentEnabled = true;	//Disable the Auto styling by JQuery Mobile CSS
	    $.mobile.pushStateEnabled = false;	// Disable the AJAX Navigation across pages
	    $.mobile.ajaxEnabled = false;	// Disable the AJAX Navigation across pages
	    $.mobile.defaultHomeScroll = 0;
            });
        </script>	

	<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.mobile-1.3.2.min.full","nationalMobileVendor"); ?>"></script>
	<script>
		$j = $.noConflict();
		COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
	</script>

	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("userRegistration"); ?>"></script>
	<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("mobileUserRegistration","nationalMobile"); ?>"></script>

        <script type="text/javascript">
        var t_headend = new Date().getTime();
        </script>
</head>

<body>

<div id="wrapper" data-role="page">

<script type="text/javascript">
      var t_jsstart = new Date().getTime();
</script>
<!--[if lt IE 7]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<?php

if (is_array($userStatus) && ($userStatus != 'false'))
{
    $logged_in_user_array = $userStatus;
}
else
{
    $logged_in_user_array = $this->data['m_loggedin_userDetail'];
}

if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false')
{
	$logged_in_user_array = array();
}
else
{
	$logged_in_user_array = $logged_in_user_array[0];
}

global $user_logged_in;
global $logged_in_userid;

$logged_in_userid 			= (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
$user_logged_in 			= (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';
?>
<script type="text/javascript">
base_url 						= "<?php echo SHIKSHA_HOME;?>";
is_user_logged_in 				= "<?php echo $user_logged_in;?>";
logged_in_userid 				= "<?php echo $logged_in_userid;?>";
</script>

    <header id="page-header" class="clearfix">
        <div class="head-group">
            <a href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
            <?php
		if(isset($_COOKIE['onlineForm_StartApplication']) && $_COOKIE['onlineForm_StartApplication']!=''){
			$cookieStr = $_COOKIE['onlineForm_StartApplication'];
			$paramArray = explode('--',$cookieStr);
			$instituteName = base64_decode($paramArray[1]); ?>
			<h1><div class="left-align">Online Application for <?=$instituteName?></div></h1>
	    <?php
		}
		else{
		if(isset($requestEbrochure) && $requestEbrochure == 1)
		{
		?>
			<h1><div class="left-align">Request Free E-brochure</div></h1>
		<?php
		}else{
			?>
			<h1><div class="left-align">Register on Shiksha</div></h1>
			<?php
		}
		}
		?>
        </div>
    </header>
	<?php
	if(isset($requestEbrochure) && $requestEbrochure == 1)
	{
	?>
	<div class="request-msg" id="request-brochure-msg" style="background-color:#264971;padding:15px 10px;">
		<span style="color:#ffffff;">We need a few details to E-mail you the brochure for</span>
		<span style="color:#FEDA41;"><?=$institute_name?></span>
	</div>
	<?php
	}
	?>
	<div class="popup-layer" id="popupBasic" style="display: none;">
		<header class="layer-head">
		<p id="congratsUser">Congratulations Anil</p>
		<a href="#" class="close-box" onclick = "hideAndRedirect();">&times;</a>
		<div class="clearfix"></div>
		</header>
		<div class="layer-content" id="successLayer">
			<p style="margin-bottom: 10px">You are now a registered user on Shiksha.com</p>
			<strong>You can now explore:</strong>
			<ul>
				<li>Placement, Cut-off & Fee information for 1 lakh+ courses</li>
				<li>Education news, Articles & Study tips</li>
				<li>College Rankings, College Predictors & much more</li>
				
			</ul>
			 <a id="proceedButton" onclick="hideAndRedirect();" class="button yellow small" style="width:100%; -moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box; color:#000; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; margin-top:10px; padding: 8px"><span>Proceed</span></a>
		</div>
	</div>
	<div id="popupBasicBack" data-enhance='false'>	
	</div>
    <div class="content-wrap2">
    	<?php
	if(isset($requestEbrochure) && $requestEbrochure == 1)
	{
	?>
		<!--div class="flRt" style="padding: 5px;">
		<a href="javascript:void(0);" onClick="window.location='<?=$this->config->item("loginUrl")?>';">Already Registered? Login Here</a>
		</div-->
	<?php
	}
	else
	{
		$redirectionURL = $this->config->item("loginUrl");
		if($this->input->get('registrationSource') == 'ICP'){
			$redirectionURL = $redirectionURL.'?source=ICP';
		}
	?>
		<section class="clearfix" id="alreadyRegistered">
		    <div class="btn-section login-btn" data-enhance="false">
			<input type="button" class="l-btn" value="Already Registered? Login Here" onClick="if(shikshaUserRegistration.checkCallBackParamsInRegForm()){window.location='<?php echo $redirectionURL; ?>';}else{ shikshaUserRegistration.sendCallBackParamsToLoginPage();}" />
		    </div>
		    <hr class="h-rule" />
		</section>
	<?php
	}
	if(!isset($requestEbrochure))
	{
	?>

        <!--<section class="clearfix" style="padding: 0.7em 0.8em 0.2em 0.8em;">
        <h2>
        	<p class="login-title" style="margin-bottom:0.2em">New Users, Register Free!</p>
        </h2>
	</section>-->
	<?php if(!(isset($_COOKIE['onlineForm_StartApplication']) && $_COOKIE['onlineForm_StartApplication']!='')){ ?>
	<section class="content-child clearfix" style="padding-bottom: 0" id="joinShikshaLabel">
		<?php if(isset($_POST['fromRankPredictorPage']) && $_POST['fromRankPredictorPage'] == 'Yes') {?>
		
		<p class="login-title" style="margin-bottom:0.2em" id="newUserRegister">Register to see your predicted rank</p>
		
		<?php }else if(isset($_POST['fromMenteePage']) && $_POST['fromMenteePage'] == 'Yes'){?>
		
		<p class="login-title" style="margin-bottom:0.2em" id="newUserRegister">Enroll to get a mentor</p>
		
		<?php }else{ ?>
		
        	<p class="login-title" style="margin-bottom:0.2em" id="newUserRegister">Join Shiksha Now</p>
		<?php } ?>
		
		<?php if(isset($_POST['fromRankPredictorPage']) && $_POST['fromRankPredictorPage'] == 'Yes') {?>
		
		<p class="login-sub-title" id="joinShiksha">In addition, get free alerts on Exam updates, Admission deadline, College rankings and more </p>
		
		<?php }else if(isset($_POST['fromMenteePage']) && $_POST['fromMenteePage'] == 'Yes'){?>
		
		<p class="login-sub-title" id="joinShiksha">Enter your personal details to enroll into the mentorship program. </p>
		
		<?php }else{ ?>
		
        	<p class="login-sub-title" id="joinShiksha">Get free alerts on Exam updates, Admission deadline, College rankings and more </p>
		<?php } ?>
            
            
            
            <!-- Register Steps Starts -->
            <!--<section class="register-steps">
                <div class="step-1 active" id="stepone">
                    <i class="point"><span class="reg-sprite tick-mark"></span></i>
                    <p>Step 1</p>
                </div>
                
                <div class="step-2" id="steptwo">
                    <i class="point"><span class="reg-sprite tick-mark"></span></i>
                    <p>Step 2</p>
                </div>
                <i class="reg-sprite step-arrow"></i>
   			</section>-->
            <!-- Register Steps Ends -->
            
            
            
            
        </section>
	<?php } ?>
	<section class="content-child clearfix" style="padding: 0.5em 0.8em; display:none;">
		<div data-enhance="false"><strong>Study Preference: <br/></strong>

        	<label><input id="prefindia" name="abroad" type="radio"  onClick="$('studyAbroadForm').style.display='none'; $('studyIndiaForm').style.display='';" checked > India</label> &nbsp;&nbsp;&nbsp;
        	<label><input id="prefabroad" name="abroad" type="radio" onClick="$('studyIndiaForm').style.display='none'; $('studyAbroadForm').style.display='';"> Abroad</label>
		</div>
        </section>
	<script>$j('#prefindia').attr('checked', 'checked');</script>
	<?php
	}
	?>
        <section class="content-child clearfix" id="studyIndiaForm">
                <?php echo Modules::run('registration/Forms/LDB',NULL,'mobile',array('registrationSource' => 'MOBILE_LDB_REGISTRATION_FORM','referrer' => $referrer,'tracking_keyid'=>$tracking_keyid,'trackingPageKeyId'=>$trackingPageKeyId, 'formCallBack'=>$formCallBack)); ?>

		<?php //$preferredLocationVariable = $this->load->view('/muser5/preferredStudyLocation',array(),true); ?>
	</section>
	<?php $this->load->view('registration/common/OTP/otpVerificationMobile'); ?>
	<div>
		<div id="responseCreationFormHolder">
		</div>
	</div>
        <section class="content-child clearfix" id="studyAbroadForm" style="display: none;">
        <?php //echo Modules::run('registration/Forms/LDB','studyAbroad','mobile',array('registrationSource' => 'MOBILE_LDB_REGISTRATION_FORM_ABROAD','referrer' => 'http://shiksha.com/muser5/MobileUser/register')); ?>
		<?php //$destinationCountryVariable = $this->load->view('/muser5/destinationCountryRegister',array(),true); ?>
        </section>

    </div>
    <script>
      // ODB  courses list
      <?php if(isset($ODBCourses) && $ODBCourses !=''){?>
      var ODBCourses = JSON.parse('<?php echo $ODBCourses;?>');
      <?php }?>
    </script>
    
    <?php $this->load->view('/mcommon5/footerLinks');?>

</div>

<div data-role="page" id="preferredStudyLocations" data-enhance="false">
		<?php //$this->load->view('/muser5/preferredStudyLocation'); ?>
		<?php //echo $preferredLocationVariable; ?>
</div>
<!--
<div data-role="page" id="destinationCountryDiv" data-enhance="false">
		<?php //$this->load->view('/muser5/destinationCountry'); ?>
		<?php //echo $destinationCountryVariable; ?>
</div>-->
<!--<div id="examTakenDiv" data-role="page" data-enhance="false">
</div>-->

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>"></script>

<script>
var  total_selected_mobile=0;
var loginURLMobile = '<?=$this->config->item("loginUrl")?>';
</script>
	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>
    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    <input type='hidden' id='isMR' name='isMR' value='YES' />
    <input type='hidden' id='odbRedirectBaseUrl' name="odbRedirectBaseUrl" value='<?php echo base64_encode(base_url()); ?>' />
    <input type="hidden" value="<?php echo base64_encode(MOBILE_ODB_VERIFICATION);?>" id="odbMode" name="odbMode">
	<input type="hidden" name="actionPoint" id="actionPoint" value="<?php echo $actionPoint; ?>">
<?php
$footerComponent = array('doNotLoadImageLazyLoad'=>'true');
$this->load->view('/mcommon5/footer',$footerComponent);
?>


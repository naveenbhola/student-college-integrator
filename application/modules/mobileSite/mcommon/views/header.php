<!DOCTYPE html>
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
        
        <title><?php if ($m_meta_title) { echo $m_meta_title; } else { echo "Education India - Search Colleges, Courses, Institutes, University, Schools, Degrees - Forums - Results - Admissions -
		Shiksha.com"; } ?></title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
          
        <base href="<?php echo base_url(); ?>" />
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<meta name = "format-detection" content = "telephone=no" />
		<meta name = "format-detection" content = "address=no" />
		
		<meta name="description" content="<?php if ($m_meta_description) { echo $m_meta_description; } else
		{ echo "Search Colleges, Courses, Institutes, University,
		Schools,Degrees, Education options in India. Find colleges and universities in India & abroad.
		Search Shiksha.com Now! Find info on Foreign University, question and answer in education and career
		forum.Ask the education and career counselors."; } ?>"/>
		<meta name="keywords" content="<?php if ($m_meta_keywords) { echo $m_meta_keywords; } else { echo
		"Shiksha, education, colleges,universities, institutes,career, career options, career prospects,
		engineering, mba, medical, mbbs,study abroad, foreign education, college, university, institute,
		courses, coaching, technical education, higher education,forum, community, education career experts,
		ask experts, admissions,results, events,scholarships"; } ?>"/>

		<meta name="copyright" content="2012 Shiksha.com" />
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
	  <!-- Added for Canonical URL Start -->
	  <?php
	  if(!empty($m_canonical_url)){
			?>
			<link rel="canonical" href="<?php echo $m_canonical_url;?>" />
			<?php
	  }
	  ?>
	  <!-- Added for Canonical URL End -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="/public/mobile/images/apple-touch-icon.png">
        
        <link rel="dns-prefetch" href="//ask.shiksha.com">

        <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile/css/<?php echo getCSSWithVersion("mobile"); ?>" >

        <script src="//<?php echo JSURL; ?>/public/mobile/js/vendor/boomerang/<?php echo getJSWithVersion("boomerang"); ?>"></script>
        <script src="//<?php echo JSURL; ?>/public/mobile/js/vendor/<?php echo getJSWithVersion("require-jquery"); ?>"></script>
        <script src="//<?php echo JSURL; ?>/public/mobile/js/<?php echo getJSWithVersion("main"); ?>"></script>

        <script type="text/javascript">
        var t_headend = new Date().getTime();
        </script>
		<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
	<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
	<?php } ?>
</head>
 <?php //ob_flush(); ?>
<?php //flush(); ?>   
<!-- END HEADER -->
<body>
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>
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
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
global $logged_in_usermobile;
global $logged_in_user_name;
global $logged_in_first_name;
global $logged_in_last_name; 
global $logged_in_user_email;

$logged_in_userid 			= (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];

$user_logged_in 			= (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';

$logged_in_usermobile 		= (!isset($logged_in_user_array['mobile'])) ? '-1' : $logged_in_user_array['mobile'];

$logged_in_user_name 		= (!isset($logged_in_user_array['displayname'])) ? 'empty' : $logged_in_user_array['displayname'];

$logged_in_user_email 		= (!isset($logged_in_user_array['cookiestr'])) ? 'empty' : $logged_in_user_array['cookiestr'];
$values 					= explode("|",$logged_in_user_email);
$logged_in_user_email 		= $values[0];
$logged_in_first_name 		= (!isset($logged_in_user_array['firstname'])) ? 'empty' : $logged_in_user_array['firstname'];
$logged_in_last_name            = (!isset($logged_in_user_array['lastname'])) ? 'empty' : $logged_in_user_array['lastname']; 
$shiksha_site_current_url 	= current_url();

if($_SERVER['HTTP_REFERER'])
{
	$shiksha_site_current_refferal =  $_SERVER['HTTP_REFERER'];
	
}
else
{
	$shiksha_site_current_refferal = "www.shiksha.com";
	
}

$encoded_current_url 			= url_base64_encode($shiksha_site_current_url);
$encoded_current_refferal 		=  url_base64_encode($shiksha_site_current_refferal);

?>
<script type="text/javascript">

base_url 						= "<?php echo SHIKSHA_HOME;?>";

shiksha_site_current_url 		= "<?php echo $shiksha_site_current_url; ?>";

shiksha_site_current_refferal 	= "<?php echo $shiksha_site_current_refferal; ?>";

logged_in_user_first_name 		= "<?php echo $logged_in_first_name ;?>";

logged_in_user_last_name                  = "<?php echo $logged_in_last_name ;?>"; 

logged_in_user_name 			= "<?php echo $logged_in_user_name;?>";

is_user_logged_in 				= "<?php echo $user_logged_in;?>";

logged_in_userid 				= "<?php echo $logged_in_userid;?>";

logged_in_mobile 				= "<?php echo $logged_in_usermobile;?>";

logged_in_email 				= "<?php echo $logged_in_user_email;?>";

</script>

<div id="header">

    <div id="nav">
    	<ul>
		<?php 
		if ($logged_in_userid != '-1' && !empty($logged_in_user_email)) {
		?>
				<li> <?php echo "Hi " . displaySubStringWithStrip($logged_in_user_email,45); ?> | </li>
<li><a title="Sign Out" href="<?php echo SHIKSHA_HOME; ?>/muser/MobileUser/logout/">Sign Out</a></li>
		<?php
		} else {
		?>
		<?php $home_page = $activelink == "home" ? "active" : ''; ?>
		<?php $login_page = $activelink == "login" ? "active" : ''; ?>
		<?php $register_page = $activelink == "register" ? "active" : ''; ?>
                <?php if($flag_off_home == false):?>
        	<li><a class = "<?php echo $home_page;?>" title = "Home" href="<?php echo SHIKSHA_HOME;?>">Home</a>|</li>
		<li><a class = "<?php echo $login_page;?>" title = "Home" href="<?php echo SHIKSHA_HOME;?>/muser/MobileUser/login/">Login</a>|</li> 
        	<li><a class = "<?php echo $register_page;?>" title = "Home" href="<?php echo SHIKSHA_HOME;?>/muser/MobileUser/register/">Register</a></li>         <?php endif;?>
		<?php } ?>
        </ul>
    </div>
    <div id="logo"><a href="<? echo SHIKSHA_HOME; ?>"><img src = "<?php echo '/public/mobile/images/logo.jpg'; ?>"></a></div>
</div>
<?php
if (getTempUserData('flag_google_adservices'))
{ 
?>
<script type="text/javascript"> 
var img = document.createElement("img");
var goalId = 984794453;
var randomNum = new Date().getMilliseconds();
var value = 0;
var label = "m2i6CIvb_QQQ1YrL1QM";
var url = encodeURI(location.href);
var trackUrl = "http://www.googleadservices.com/pagead/conversion/"+goalId+"/?random="+randomNum+"&value="+value+"&label="+label+"&guid=ON&script=0&url="+url;
img.src = trackUrl;
document.body.appendChild(img);
</script>
<?php
 }
deleteTempUserData('flag_google_adservices');
?>

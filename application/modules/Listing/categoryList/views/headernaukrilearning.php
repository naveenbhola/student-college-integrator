<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ="�/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
	<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="robots" content="ALL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
	<title><?php echo $title?></title>
	<link rel="canonical" href="<?=$canonicalURL?>" />
<?php
echo getHeadTrackJs();
#global $product;
?>
<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("registration"); ?>" type="text/css" rel="stylesheet" />
<?php
if($product == 'home' && $_SERVER['REQUEST_URI'] == '/') {
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shiksha_common"); ?>" type="text/css" rel="stylesheet" />
<?php
}else{
	if($product == 'forums'){
		$css = array('common','ask');
	}elseif(($product == 'categoryHeader' || $product == 'testprep' || $product == 'foreign' || $product == 'MBA' || $product == 'gradHeader') && ($css[0] != 'listing')){
		$css = array('common','category-styles','recommend');
	}elseif($product == 'events'){
		$css = array('common','impDate');
	}
	elseif($product=='home' && in_array('shiksha_common',$css)){ ?>
	    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shiksha_common"); ?>" type="text/css" rel="stylesheet" />
    	<?php }
	elseif(isset($css) && is_array($css) && !in_array('mainStyle',$css)){ ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<?php	}else{ ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
<?php
}
}
?>
<?php
global $jsRepos;
$this->load->library('category_list_client');
$categoryClient = new Category_list_client();
$jsRepos = $categoryClient->getJSFromRepos();
    $cssExclude = array('footer','shiksha_common','mainStyle','header');
    if(isset($css) && is_array($css)) {
        foreach($css as $cssFile) {
            if(!in_array($cssFile,$cssExclude)) {
?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php
            }
        }
    }
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("userRegistration"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("facebook"); ?>"></script>
<script>
var urlforveri = 'http://<?php echo SHIKSHACLIENTIP;?>';
var currentPageName= null;

<?php
if(SHOW_AUTOSUGGESTOR){
?>
	var SHOW_AUTOSUGGESTOR_JS = true;
<?php
} else {
?>
	var SHOW_AUTOSUGGESTOR_JS = false;
<?php
}
?>
<?php
if(TRACK_AUTOSUGGESTOR_RESULTS){
?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = true;
<?php
} else {
?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = false;
<?php
}
if(TRACK_SEARCH_RESULTS){
  ?>
  var TRACK_SEARCH_RESULTS_JS = true;
  <?php
} else {
  ?>
	var TRACK_SEARCH_RESULTS_JS = false;
  <?php
}
?>

<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>
<?php addJSVariables(); ?>
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
// START GLOBAL variables for F-SHARE
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>';
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
//  START GLOBAL variables for F-SHARE
</script>

<script type="text/javascript">
  function loadJsFilesInParallel(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		if(SHOW_AUTOSUGGESTOR_JS){
		autosuggestorInstanceCheck = setInterval(function(){
			var fileLoaded = false;
			try{
				var aso = new AutoSuggestor();
				fileLoaded = true;
			} catch(e) {
				fileLoaded = false;
			}
			if(fileLoaded){
				clearInterval(autosuggestorInstanceCheck);
				if(typeof(initializeAutoSuggestorInstance) == 'function') {
                    initializeAutoSuggestorInstance();
                }
				if(typeof(initializeAutoSuggestorInstanceAlt) == 'function') {
                    initializeAutoSuggestorInstanceAlt();
                }
			}
		},1000);
	  }
	});
  }
</script>
<!-- LABJs utility loaded in parallel-->
<script type="text/javascript">
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallel();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>

</head>
<body id="wrapperMainForCompleteShiksha">
<div id="main-wrapper">
	<!--Start_TopBar-->
    <div id="top-bar">
    	<div class="top-bar-child">
        	<!--<ul>
                <li class="naukri-logo" title="A naukri.com group company"><span>A naukri.com group company</span></li>
                <li class="callForFree"><span class="callIcon">&nbsp;</span>Institutes outside India: <span>Call Toll Free #1800-717-1094</span></li>
            </ul>
        	<div class="clearFix"></div>-->
        	<div class="naukri-logo" title="A naukri.com group company"><span>A naukri.com group company</span></div>
        	<div class="top-signin-col">
                        <?php
                            if(is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])) {
                                $userEmail = substr($validateuser[0]['cookiestr'], 0, strpos($validateuser[0]['cookiestr'], '|'));
                        ?>
                            <div class="loginPannel">
                                <?php echo 'Hi '. $userEmail; ?>
                                &nbsp;<a href="<?php echo SHIKSHA_HOME; ?>/user/MyShiksha/index" tabindex="1">Account &amp; Settings</a>&nbsp;|&nbsp;
                                <a href="#" onClick = "SignOutUser()" tabindex="2">Sign out</a>
                            </div>
                        <?php } else { ?>
                            <?php
                                //$this->load->view('marketing/marketingSignInOverlay');
                            ?>
			<div id="fb-root"></div>
			
            <ul>
				<li>
    				<div class="login">
						<form autocomplete="off" action="/user/Login/submit" onsubmit="if(validateLoginForHeader(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResponseForHeader(request.responseText);}, onFailure:function(request){ return false; }, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post">
						<input id="mpassword_header" type="hidden" value="" name="mpassword"/>
                        <label>Registered User:</label>
                        
                        <input type="text" class="login-txt login-field" name="username" id="username_header" value="" default="" onfocus="checkTextElementOnTransition(this,'focus');this.className = this.className.replace('error_box','');" onblur="checkTextElementOnTransition(this,'blur')" tabindex="1" style="color:#ADA6Ad" />
                        <input type="password" name="password_header" id="password_header" class="passwordTxt login-field" value="" onfocus="checkTextElementOnTransition(this,'focus');this.className = this.className.replace('error_box','')" onblur="checkTextElementOnTransition(this,'blur')" tabindex="2" />
						<input type="Submit" class="login-btn" value=" " tabindex="3" />
						</form>
                      </div>
                      
                      <div class="register">
                      	<a href="#" onclick="try{return sendMailtoUser1(this.form);} catch(e){}" tabindex="4">Forgot Password</a>
                        <span>|</span>
                        <label>New User:</label>
						<input type="button" tabindex="5" value=" " class="register-btn" onclick="trackEventByGA('RegisterClick','HEADER_REGISTRATION_BTN');window.location='<?php echo SHIKSHA_HOME; ?>/user/Userregistration/index/'"/>
                            <?php //if(isset($_COOKIE['FBCookieCheck'])){ ?>
                            </div>
                            
                            <div class="fb-button"> 
                                    <script>tempFB='fConnect';</script>
                                    <fb:login-button scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="setCookieForFB('fHeader');callFConnectAndFShare('fConnect');">Connect</fb:login-button>
							</div>
                       
						<div class="clearFix"></div>
                        <div style="display:none;"><div class="errorMsg" id="username_header_error" style="margin-left:98px;">&nbsp;</div></div>
                     </li>
                  </ul>
				<?php } ?>
               </div>
			<div class="clearFix"></div>
         </div>
    </div>
    <!--End_TopBar-->
    

	<div id="content-wrapper">
      <div class="wrapperFxd" id="main-wrapper">
	<noscript> <div class="jser"> <img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
    

    <!--Start_Logo_And_TopBanner-->
    <div id="logo-section">
        <?php if(isset($partnerPage) && !empty($partnerPage)) { ?>

	<div class="float_R" style="height:65px;">
		<a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
		<span class="logoNewClass" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
		</a>
	</div>
	<div style="float:left; width:300px;">
    <a href="<?php echo constant(strtoupper($partnerPage) . '_URL'); ?>" title="<?php echo constant(strtoupper($partnerPage) . '_TITLE'); ?>"><img src="/public/images/<?php echo constant(strtoupper($partnerPage) . '_LOGO'); ?>" border="0" alt="<?php echo constant(strtoupper($partnerPage) . '_TITLE'); ?>" /></a>
    	<span style="position:relative;top:-20px;font-size:28px;font-weight:bold;color:#4d4948;" >Education</span>
	</div>
    <?php } else { ?>
<!--Start_Logo_Advertisement-->
    	
        <div style="clear:both;">
			<h1 style="width:290px" tabindex="7" class="shik-logo"><a href="<?php echo SHIKSHA_HOME; ?>" tabindex="6" title="Shiksha.com"><img src="/public/images/naukrilearning.jpg" alt="Shiksha.com" title="Shiksha.com" border="0" /></a></h1>
			<!--<div style="padding:0 0 0 104px;font-weight:700"><?php// if(isset($logoMsg)) echo $logoMsg; ?></div>-->
		
		<div style="float:right; width:480px;" tabindex="7">
          <?php
          $this->load->view('common/banner',$bannerProperties);
          ?>
          
          </div>
          <div class="clearFix"></div>
        </div>      
    		
        
	    <?php } ?>
        <div class="clearFix sopacer5"></div>
    </div>
    <!--End_Logo_And_TopBanner-->
<script>
overlayViewsArray.push(new Array('marketing/marketingSignInOverlay','marketingSignInOverlayId'));
overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay'));
overlayViewsArray.push(new Array('user/registerConfirmation','ConfirmRegistration'));
overlayViewsArray.push(new Array('common/changeOverlay','sendveriOverlay'));
</script>
<div id = "loginCommunication" class = "showMessages" style = "display:none; clear:both; width:945px; margin:0 0 10px 10px;">
	<div class="close-icon" onClick="hidelogindiv()">&nbsp;</div>
	<span id = "logindiv" style = "font-size:11px"></span>
    <div class="clearFix"></div>
</div>
<script>
if(getCookie('userresponse') != '') {
senduserResponse();
} else {
    <?php
        if(is_array($validateuser) && isset($validateuser[0])) { ?>
            var cookie = getCookie('user').split('|');var msg = cookie[2];
            var comm = '';
            if((msg == "hardbounce" || msg == "softbounce") &&  (getCookie('user').indexOf('hideVerification') < 0))
            {
                
                if(msg == "softbounce")
                    comm = "We experienced problem sending email to the address " + cookie[0] + " you provided. Please <a href = '#' onClick = 'showchangeEmailOverlay()'>click here</a> to change the email address or <a href = '#' onClick = 'showverificationMailOverlay()'>click here</a> to resend verification mail and continue using Shiksha.com and avail its benefits."
                        if(msg == "hardbounce")
                            comm = "The email address - " + cookie[0] + " you provided appears to be invalid. <a href = '#' onClick = 'showchangeEmailOverlay();'>Click here</a> to provide the correct email address to continue using Shiksha.com and avail its benefits.";
                //showMessagesInline1('logindiv',comm);
                //document.getElementById('loginCommunication').style.display = '';
            }
            <?php
        }
    ?>
}
</script>

<?php
$this->load->view('common/disablePageLayer.php');
$this->load->view('common/overlay.php');
//$this->load->view('common/categorySearchOverlay.php');
?>
<script>
if(document.body.offsetWidth < 1000 && document.getElementById('careername'))
{
document.getElementById('careername').innerHTML = 'Career Options';
}
setClientCookie();
</script>
<?php
global $jsToBeExcluded;
// $jsToBeExcluded[] = 'cityList';
$alreadyAddedJs = array();
if(!isset($js)){
    $js = array();
}
$jsOrig = $js;
$js = getJsToInclude(array_unique(array_merge($alreadyAddedJs, $js)));
    if(isset($js) && is_array($js)) {
        foreach($js as $jsFile) {
?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
<?php
        }
    }
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('cityList'); ?>"></script>

<?php
$timeDuration = $_COOKIE['FBCookieCheckTimeStampForLogin'];
$currentTime = time();
if(array_key_exists('FBCookieCheck',$_COOKIE) && array_key_exists('user',$_COOKIE) && $_COOKIE['FBCookieCheck']==1 && ( $currentTime > $timeDuration+24*60*60 && $currentTime<$timeDuration+15*24*60*60 )){
?>
           <script  type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
<?php try { ?>
<div id="fb-root"></div>
			<script> 
                            FB.init({
                            appId:<?php echo FACEBOOK_API_ID; ?>, cookie:true,
                            status:true, xfbml:true,oauth: true
                            });

                            FB.api('/me',function(response){
                            var email = response.email;
                            if(typeof(email)=='undefined'){
                                   return false;
                            }
                            var displayname = response.name;
                            var firstname = response.first_name;
                            var lastname = response.last_name;
                            //accessToken = FB.getSession().access_token;
                            //if(typeof(accessToken)=='undefined'){
                            FB.getLoginStatus(function(response) {accessToken = response.authResponse.accessToken;});
                            //}
                            facebookUserDetails(email,firstname,displayname,lastname,accessToken,'AnA');
                            
                            }

                            );
           
</script>
<?php }catch(Exception $e) {

	    }
   }
?>
<script> 
	// START $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session
	var unified_form_overlay1_cancel_clicked = false; 
	var unified_form_overlay2_cancel_clicked = false; 
	var unified_form_overlay3_cancel_clicked = false; 
	var arr_unified = new Array(); 
	var page_identifier_unified = ''; 
	var listingdetailpage_unified_thankslayer_identifier = ''; 
	var unified_widget_identifier = ''; 
	// END $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session 
</script> 
<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>
<div id="top-nav_naukri">
<?php
				$pageZone = str_replace(" ","_",$categoryPage->getSubCategory()->getName());
				$bannerProperties = array('pageId'=>'NAUKRI', 'pageZone'=>$pageZone.'_SCRAPPER', 'shikshaCriteria' => $criteriaArray,'width'=>973,'height'=>200);
		        $this->load->view('common/banner',$bannerProperties);
?>
</div>
<script>
	function showSideBannerForNaukrilearning() {
		var mainBanner = $('<?=$pageZone.'_SCRAPPER' ?>');
		mainBanner.src = bannerPool['<?=$pageZone.'_SCRAPPER'?>'];
	}
	showSideBannerForNaukrilearning();
</script>
<?php
global $institutesWithoutUnified;
?>
<script>
var institutesWithoutUnified = <?=json_encode($institutesWithoutUnified)?>;
</script>

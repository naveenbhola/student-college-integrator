<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
	<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
	<link rel="publisher" href="https://plus.google.com/+shiksha"/> 

<?php $requestUrl = (string)$_SERVER['REQUEST_URI'];
$check = (strrpos($requestUrl,"getTopicDetail")!='')?'found':(strrpos($requestUrl,"-qna-")!='')?'found':'notfound';
if(($check == 'found')&&($isMasterList == 'present')){
?>
<meta name="isMasterList" content="<?php echo $isMasterList?>"/>
<?php }?>

<!-- Added for External articles Start -->
<?php if(isset($noIndexMetaTag) && $noIndexMetaTag){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX">
<?php } else if(isset($noIndexNoFollow) && $noIndexNoFollow){ ?>
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<?php } else{ ?>
<meta name="robots" content="ALL" />
<?php } ?>

<!-- Added for adding Publish date meta tag on Question detail pages -->
<?php if(isset($publishDate) && $publishDate!=''){ ?>
<meta name="publishDate" content="<?=$publishDate?>" />
<?php } ?>

<!-- Added for Type Meta tag in ANA Start -->
<?php if(isset($typeOfEntity) && $typeOfEntity!=''){ ?>
<meta name="entityType" content="<?=$typeOfEntity?>" />
<?php } ?>
<!-- Added for Type Meta tag in ANA End -->

<!-- Added for FB Like button on Article detail pages -->
<?php if(isset($articleImage) && $articleImage!=''){ ?>
<meta property="og:title" content="<?php echo $title?>" />
<meta property="og:type" content="article" />
<meta property="og:image" content="<?=$articleImage?>" />
<meta property="og:site_name" content="http://www.shiksha.com/" />
<meta property="fb:app_id" content="<?php echo FACEBOOK_API_ID; ?>" />
<?php } ?>

<!-- Added for Canonical URL Start -->
<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
<!-- Added for Canonical URL End -->


<title><?php echo $title?></title>
<?php
echo getHeadTrackJs();
#global $product;
?>
<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<?php
//Amit Singhal : To Avoid loading of header.js twice
unset($js[array_search("header",$js)=="FALSE"?array_search("header",$js):-10]);
unset($jsFooter[array_search("header",$jsFooter)=="FALSE"?array_search("header",$jsFooter):-10]);
//Amit Singhal : To Avoid loading of header.js twice
?>
<?php
if($product == 'home' && $_SERVER['REQUEST_URI'] == '/') {
?>
<?php
}else{
	if($product == 'forums'){
		$css = array('common','ask');
	}elseif(($product == 'categoryHeader' || $product == 'testprep' || $product == 'MBA' || $product == 'gradHeader') && ($css[0] != 'listing')){
		$css = array('common','category-styles');
	}elseif($product == 'events'){
		$css = array('common','impDate');
	} else if(strtolower($product) == 'search'){
		$css = array('search','category-styles');
	} elseif($product == 'foreign'){
		$css = array('studyAbroad-styles');
	}elseif($product == 'ArticlesD'){
                $css = array('articles');
        }
	elseif($product=='home' && in_array('shiksha_common',$css)){ ?>
	    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shiksha_common"); ?>" type="text/css" rel="stylesheet" />
    	<?php }
	elseif(isset($css) && is_array($css) && !in_array('mainStyle',$css) && $page_is_listing !='YES'){ ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<?php	}else{ ?>
                <?php if($page_is_listing != 'YES'): ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
                <?php endif;?>
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
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('registration'); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script type="text/javascript">
var urlforveri = 'http://<?php echo SHIKSHACLIENTIP;?>';
var home_shiksha_url = '<?php echo SHIKSHA_HOME;?>';
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

// START $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session

var messageObj = null;
var unified_form_overlay1_cancel_clicked = false;
var unified_form_overlay2_cancel_clicked = false;
var unified_form_overlay3_cancel_clicked = false;
var arr_unified = new Array();
var page_identifier_unified = '';
var listingdetailpage_unified_thankslayer_identifier = '';
var unified_widget_identifier = '';
// END $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session

// START GLOBAL variables for F-SHARE
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>';
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
// START GLOBAL variables for F-SHARE
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
<body id="wrapperMainForCompleteShiksha" <?php if(isset($showGutterBanner) && $showGutterBanner=='1'){ echo 'onscroll="reposition()"'; } ?>>
    <div id="main-wrapper">
    
	<div id="content-wrapper">
 		<div class="wrapperFxd" id="main-wrapper">

<script>
overlayViewsArray.push(new Array('marketing/marketingSignInOverlay','marketingSignInOverlayId'));
overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay'));
overlayViewsArray.push(new Array('user/registerConfirmation','ConfirmRegistration'));
overlayViewsArray.push(new Array('common/changeOverlay','sendveriOverlay'));
</script>
<?php
//$this->load->view('common/changeOverlay');
$tmp = $product;
global $product;
$product = $tmp;
function getClass($str)
{
	global $product;
	if(strtolower($product) == 'event' && strtolower($str) == 'events') {
		$str = 'Event';
	}
	if (strtolower($str)==strtolower($product)) {
		return "tabSelected";
	} else {
		return "";
	}
}/*
 * function name
 * @param $arg
 */

function name($arg) {

}
if(isset($product) && ($product=='home')) {
	$product = 'all';
}
?>

<!--if($showBottomMargin){ ?>
    <div class="spacer10 clearFix"></div>
<?php// } ?>
-->

<?php
$this->load->view('common/disablePageLayer.php');
$this->load->view('common/overlay.php');
//$this->load->view('common/categorySearchOverlay.php');
?>
<script>
if(document.body.offsetWidth < 1000 && document.getElementById('careername'))
{
	document.getElementById('careername').innerHTML = 'All Courses';
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
			if(stripos($jsFile,"customCityList") > 0){
				$this->load->library('common/CustomCities');
				$this->customcities->addCustomCities();
			}
		}
    }
?>
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
   }?>
<script>

// added to check if unified name space and method exist
function checkForUnifiedObjectAndMethod () {
if(typeof(window.ShikshaUnifiedRegistarion) == 'object' && typeof(window.callUnifiedOverlay) == 'function') {
	return true;
} else {
	return false;
}
}
  </script>

<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('cityList'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("facebook"); ?>"></script>
<?php
global $institutesWithoutUnified;
?>
<script>
var institutesWithoutUnified = <?=json_encode($institutesWithoutUnified)?>;
</script>
<div id="fb-root"></div>

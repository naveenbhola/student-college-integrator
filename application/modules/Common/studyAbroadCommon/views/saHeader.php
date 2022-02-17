<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" lang="en">
<head>
<title><?php echo $title?></title>
<?php
if(is_null($loggedInUserData)){
    $userCriteria = Modules::run('commonModule/User/getUserData');
    GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;
	$validateuser     = $userCriteria['validateuser'];
	$loggedInUserData = $userCriteria['loggedInUserData'];
	$checkIfLDBUser   = $userCriteria['checkIfLDBUser'];
	if(isset($validateuser[0]['avtarurl'])){
		$loggedInUserData['avtarurl'] = $validateuser[0]['avtarurl'];
	}
}
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if($responsiveHtml === true){
?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
}
if($_COOKIE['user_force_cookie'] == 'YES'){
?>
	<meta name="viewport" content="width=1024" />
<?php
}
?>
<link rel="dns-prefetch" href="//<?php echo JSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo CSSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo IMGURL; ?>">
<link rel="dns-prefetch" href="<?php echo MEDIAHOSTURL; ?>">
<link rel="preconnect" href="//<?php echo JSURL; ?>" crossorigin>
<link rel="preconnect" href="//<?php echo CSSURL; ?>" crossorigin>
<link rel="preconnect" href="<?php echo MEDIAHOSTURL; ?>" crossorigin>
<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?htmlentities($metaDescription):''; ?>"/>
	<?php if($metaKeywords != "") {
	?>
	<META NAME="Keywords" CONTENT="<?php echo $metaKeywords; ?>"/>
	<?php } ?>
<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
<meta http-equiv="content-language" content="en" />
<meta name="author" content="www.Shiksha.com" />
<?php
$robotContent = ($robotsMetaTag=='')?"ALL":$robotsMetaTag;
?>
<meta name="robots" content="<?=$robotContent?>" />
<?php if(isset($articleImage) && $articleImage!=''){ ?>
<meta property="og:title" content="<?php echo $title?>" />
<meta property="og:type" content="article" />
<meta property="og:image" content="<?=$articleImage?>" />
<meta property="og:site_name" content="https://www.shiksha.com/" />
<meta property="fb:app_id" content="<?php echo FACEBOOK_API_ID; ?>" />
<?php } ?>
<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
<?php if(isset($previousURL) && $previousURL!=''){ ?>
<link rel="prev" href="<?php echo $previousURL; ?>" />
<?php } ?>
<?php if(isset($nextURL) && $nextURL!=''){ ?>
<link rel="next" href="<?php echo $nextURL; ?>" />
<?php } ?>
<link rel="icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />
<link rel="publisher" href="https://plus.google.com/+shiksha"/>
<?php
	global $useSingleSignUpForm;
	$useSingleSignUpForm = 1;
	$this->load->view('common/TrackingCodes/JSErrorTrackingCode');
	echo getHeadTrackJs();
?>
<script>
    var isStudyAbroadPage = 1;
    var isSAGlobalNavSticky = <?php echo isSAGlobalNavSticky($beaconTrackData['pageIdentifier'])?1:0; ?>;
<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>
<?php addJSVariables(); ?>
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
<?php $studyAbroadURL =  'https://'.$_SERVER['SERVER_NAME'];
if($studyAbroadURL == SHIKSHA_STUDYABROAD_HOME)
{ ?>
	var isStudyAbroadDomain = true;
<?php } else {?>
var isStudyAbroadDomain = false;
<?php }?>
</script>
<?php if($relPrev!=''){ ?>
<link rel="prev" href="<?php echo $relPrev; ?>" />
<?php } if($relNext!=''){ ?>
<link rel="next" href="<?php echo $relNext;?>" />
<?php } ?>
<?php
$this->load->view('studyAbroadCommon/css/commonFirstFoldCss');
if($firstFoldCssPath != ''){
	$this->load->view($firstFoldCssPath);
}
if($deferCSS === true){
?>
<noscript id="deferred-styles">
<?php
	if($cssBundleMobile!=""){ // because mobile js resides in a diff directory and with current implementation, it cant be bundled with desktop
		echo includeCSSFiles($cssBundleMobile,'abroadMobile',array('crossorigin'));
	}
	echo '<link rel="stylesheet" type="text/css" href="//'.CSSURL.'/public/responsiveAssets/css/'.getCSSWithVersion("saGNB","responsiveAssets").'" media="all">';
	echo includeCSSFiles($cssBundle,'shikshaDesktop',array('crossorigin'));
?>
</noscript>
<script>
  var loadDeferredStyles = function() {
    var addStylesNode = document.getElementById("deferred-styles");
    var replacement = document.createElement("div");
    replacement.innerHTML = addStylesNode.textContent;
    document.body.appendChild(replacement);
    addStylesNode.parentElement.removeChild(addStylesNode);
  };
  var raf = requestAnimationFrame || mozRequestAnimationFrame ||
      webkitRequestAnimationFrame || msRequestAnimationFrame;
  if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
  else window.addEventListener('load', loadDeferredStyles);
</script>
<?php
}else{
	echo '<link rel="stylesheet" type="text/css" href="//'.CSSURL.'/public/responsiveAssets/css/'.getCSSWithVersion("saGNB","responsiveAssets").'" media="all">';
	echo includeCSSFiles($cssBundle,'shikshaDesktop',array('crossorigin'));
}
?>
</head>
<body>
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1 && $pageType != "searchStarterPage") {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>
<div id="fb-root"></div>
	<?php $this->load->view('common/googleCommon');

		if($hideHeader !== true) {
			echo Modules::run('studyAbroadCommon/Navigation/getMainHeader',
            	array('trackingPageKeyId'=>$trackingPageKeyId, 'userData'=>$loggedInUserData));
		}
	?>
	<div id="main-wrapper">
	<div class="menu-overlay-sa"></div>

<?php
	$this->load->view('common/studyAbroadOverlay');
	$this->load->view('common/disablePageLayer');
if(!$skipCompareCode){
?>
<script>
	var compareCookiePageTitle = '<?php echo base64_encode($compareCookiePageTitle) ?>';
	var compareOverlayTrackingKeyId = '<?php echo $compareOverlayTrackingKeyId ?>';
	var compareButtonTrackingId = '<?php echo $compareButtonTrackingId ?>';
</script>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript">
var t_pagestart=new Date().getTime();
</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="preload" href="<?php echo IMGURL_SECURE?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff" as="font" type="font/woff" crossorigin="anonymous">
<link rel="preload" href="<?php echo IMGURL_SECURE?>/public/fonts/open-sans/OpenSans-SemiBold.woff" as="font" type="font/woff" crossorigin="anonymous">

<base href="<?php echo base_url(); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="dns-prefetch" href="//<?php echo JSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo CSSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo IMGURL; ?>">
<link rel="dns-prefetch" href="<?php echo MEDIAHOSTURL; ?>">
<link rel="preconnect" href="//<?php echo JSURL; ?>" crossorigin>
<link rel="preconnect" href="//<?php echo CSSURL; ?>" crossorigin>
<link rel="preconnect" href="<?php echo MEDIAHOSTURL; ?>" crossorigin>
<meta name = "format-detection" content = "telephone=no" />
<meta name = "format-detection" content = "address=no" />
<meta name="description" content="<?php echo htmlentities($metaDescription); ?>"/>
<?php if($metaKeywords != ""){ ?>
<meta name="keywords" content="<?php echo $metaKeywords; ?>"/>
<?php } ?>
<?php if(!is_null($customSEORobotsString) && $customSEORobotsString!=''){ ?>
<META NAME="ROBOTS" CONTENT="<?php echo $customSEORobotsString ?>">
<?php }else{ ?>
<meta name="robots" content="ALL" />
<?php } ?>
<meta name="copyright" content="<?php echo date("Y"); ?> Shiksha.com" />
<meta name="resource-type" content="document" />
<meta name="pragma" content="no-cache" />
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320"/>
<meta http-equiv="cleartype" content="on">
<meta http-equiv="x-dns-prefetch-control" content="off">
<link rel="canonical" href="<?php echo $canonicalURL;?>" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo IMGURL_SECURE?>/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo IMGURL_SECURE?>/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo IMGURL_SECURE?>/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo IMGURL_SECURE?>/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
<link rel="icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />    
<link rel="publisher" href="https://plus.google.com/+shiksha"/>
<?php
$userCriteria = Modules::run('commonModule/User/getUserData');
GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;
$validateuser = $userCriteria['validateuser'];
$loggedInUserData = $userCriteria['loggedInUserData'];
$checkIfLDBUser = $userCriteria['checkIfLDBUser'];
//JSB9 Tracking
echo getHeadTrackJs();
?>
<script type="text/javascript">
var t_headend = new Date().getTime();
</script>
<?php if($relPrev!=''){ ?>
<link rel="prev" href="<?php echo $relPrev; ?>" />
<?php } ?>
<?php if($relNext!=''){ ?>
<link rel="next" href="<?php echo $relNext;?>" />
<?php } ?>



<style type="text/css">
.hideHamburgerContainer{ display: none !important; }
</style>
<?php
if($firstFoldCssPath != ''){
        $this->load->view('commonModule/css/commonFirstFoldCss');
        $this->load->view($firstFoldCssPath);
}

// preloading assets css
echo includeCSSFiles('sa-com-mobile','abroadMobile',true);
if($cssBundleMobile != ''){
  echo includeCSSFiles($cssBundleMobile,'abroadMobile',true);
}else{
    foreach($css as $cssFile) {
        echo '<link rel="preload" as="style" href="//'.CSSURL.'/public/mobileSA/css/'.getCSSWithVersion($cssFile,"abroadMobile").'">';
    }
}
// preloading assets js
echo includeJSFiles('mSAComJQ', 'abroadMobile','',true);
if(!($dontLoadRegistrationJS == true)){
  echo includeJSFiles('asyncMSAComReg', 'abroadMobile','',true);
}else{
  echo includeJSFiles('asyncMSACom', 'abroadMobile','',true);
}



if($deferCSS === true){
?>
<noscript id="deferred-styles">
<?php
    echo includeCSSFiles('sa-com-mobile','abroadMobile',array('crossorigin'));
    if($cssBundleMobile != ''){
        echo includeCSSFiles($cssBundleMobile,'abroadMobile',array('crossorigin'));
    }
    else{
        foreach($css as $cssFile) {
            echo '<link rel="stylesheet" type="text/css" href="//'.CSSURL.'/public/mobileSA/css/'.getCSSWithVersion($cssFile,"abroadMobile").'" media="all">';
        }
    }
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
  var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
  if (raf)
    raf(function() { window.setTimeout(loadDeferredStyles, 0); });
  else
    window.addEventListener('load', loadDeferredStyles);
</script>
<?php
}
?>

</head>
<body>
<div id="allLoader" style="z-index: 100000;position: fixed; left: 0px; top: 0px; bottom: 0px; right: 0px;background: rgba(255, 255, 255, 1) none repeat scroll 0px 0px !important; display: none;"  data-role="none">
<div style=" background: black none repeat scroll 0% 0%; opacity: 0.5; width:100%; height:100%" data-role="none"></div>
<img style="opacity: 1;position:fixed;left:46%;top:40%;border-radius: 50%; width: 80px; height: 80px; box-shadow: 0 0 8px 2px #b5b5b5; margin-left: -24px;margin-top: -24px;" src="data:image/gif;base64,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">
</div>
<script>
var ratio = window.devicePixelRatio || 1;
var w = screen.width * ratio;
var h = screen.height * ratio;
document.getElementById('allLoader').style.height = h+'px';
document.getElementById('allLoader').style.width = w+'px';
var t_jsstart = new Date().getTime();
</script>


<?php if(count($jsRequiredInHeader)>0){ $this->load->view("commonModule/commonJsV2"); } ?>
<?php
if(!empty($jsRequiredInHeader) && count($jsRequiredInHeader)>0)
{
foreach($jsRequiredInHeader as $jsFile) {
?>
<script <?php if(in_array($jsFile,$asyncJsList)){ echo 'async';}?> src="//<?php echo JSURL; ?>/public/mobileSA/js/<?php echo getJSWithVersion($jsFile,'abroadMobile'); ?>"></script>
<?php
}
}
?>
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1 && $pageType != "searchStarterPage") {
loadBeaconTracker($beaconTrackData);
}
?>
</div>
<?php $this->load->view('common/googleCommon');?>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<?php
$this->load->view('common/TrackingCodes/JSErrorTrackingCode');
?>
<div data-role="page" id = "wrapper">
<div id="trnsprnt-ovrly"></div>
<?php
if($validateuser!=='false'){$this->load->view('commonModule/loggedinUser',$loggedInUserData);}?>
<?php
echo Modules::run('studyAbroadCommon/Navigation/getMainHeader',
    array('trackingPageKeyId'=>$trackingPageKeyId, 'userData'=>$validateuser));
?>
<div data-role = "content" style = "padding:0px !important;<?php echo ($hideHeader === true?"overflow-y:hidden;":""); ?>">

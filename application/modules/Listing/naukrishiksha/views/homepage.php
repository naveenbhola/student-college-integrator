<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<title>
	<?php
	$title = isset($title) ? $title : '';
	echo $title;
	$notShowSearch = isset($notShowSearch)?notShowSearch:false;
	?>
	</title>
	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
	<meta name="copyright" content=" 2009 Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="robots" content="ALL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("naukriShiksha"); ?>" type="text/css" rel="stylesheet" />
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
<?php

global $jsToBeExcluded;

$alreadyAddedJs = array('cityList');
?>

<?php
if(!isset($js)){
    $js = array();
}
$js = array_unique(array_merge($alreadyAddedJs, $js));
    if(isset($js) && is_array($js)) {
?>
	<?php foreach($js as $jsFile) { ?>
            <?php if(in_array($jsFile,$jsToBeExcluded)){?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo $jsFile; ?>.js"></script>
            <?php } else { ?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
            <?php } ?>
	<?php } ?>
<?php } ?>
</head>
<noscript> <div class="jser"> <img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>
var currentPageName= '<?php echo $currentPageName;?>';
<?php addJSVariables(); ?>
// START GLOBAL variables for F-SHARE
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>';
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
// START GLOBAL variables for F-SHARE
function showLoaderLayer(datamsg){
    var divX = screen.width/2 - 200;
    var   divY = screen.height/2 - 200;
    var  h = document.documentElement.scrollTop;
    divY = divY + h;
    document.getElementById('loaderOverlayContainer').style.left = divX +  'px';
    document.getElementById('loaderOverlayContainer').style.top = divY  +  'px';
    document.getElementById('dim_bg').style.height = document.body.offsetHeight +  'px';
    document.getElementById('dim_bg').style.display = 'inline';
	document.getElementById('loaderOverlayContainer').innerHTML = '<div style="width:100%;margin-Top:30px;margin-Bottom:10px;" align="center"><img src="/public/images/loader.gif" /></div><div align = "center" style = "font-Size:18px">'+datamsg+'</div>';
    document.getElementById('loaderOverlayContainer').style.display = '';
    overlayHackLayerForIE('loaderOverlayContainer', document.body);
    return;
}
function hideLoaderLayer() {
    dissolveOverlayHackForIE();
    document.getElementById('loaderOverlayContainer').style.display = 'none';
    document.getElementById('dim_bg').style.display = 'none';
    setNoScroll();
    return;
}


function obtainPostitionX(element) {
    var x=0;
    while(element)	{
        x += element.offsetLeft;
        element=element.offsetParent;
    }
    return x;
}

function obtainPostitionY(element) {
    var y=0;
    while(element) {
        y += element.offsetTop;
        element=element.offsetParent;
    }
    return y;
}
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
</script>
<body>
<div class="wrapperFxd">
<?php	$styleclause = '';
if(isset($_COOKIE['client']) && ($_COOKIE['client'] == 800)) {
$styleclause = "width:994px";
}?>
<div id="resolutionSet_800">
<script>
    if(document.body.offsetWidth<900){
        document.getElementById('resolutionSet_800').style.width='994px';
    }
</script>
<div id="enableCookieMsg" style="display:none;margin-left:10px;color:#ff0000" class="jser bld" align="center"></div>
<div id="loaderOverlayContainer" style="position:absolute;display:none;width:425px;height:138px;background-color:#FFFFFF;z-index:1000001;padding:10px;">

</div>
    <!--StartTopHeaderWithNavigation-->
<script>

function getCookie(c_name){
	if (document.cookie.length>0){
		c_start=document.cookie.indexOf(c_name + "=");
	    if (c_start!=-1){
		    c_start=c_start + c_name.length+1;
		    c_end=document.cookie.indexOf(";",c_start);
		    if (c_end==-1) { c_end=document.cookie.length ; }
		    return unescape(document.cookie.substring(c_start,c_end));
	    }
	  }
	return "";
}

function setCookie(c_name,value,expiredays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+";path=/;domain=<?php echo COOKIEDOMAIN; ?>"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
	if(document.cookie== '') {
		return false;
	}
	return true;
}

function setClientCookie(){
	try{
		if(getCookie('client') == "" || getCookie('client') ==  null || document.body.offsetWidth != getCookie('client')){
			if(getCookie('client') != "" && getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth > 1000 && getCookie('client') < 800) {
				setCookie('client',document.body.offsetWidth ,300);
			} else if(getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth < 1000 && getCookie('client') > 1000){
				setCookie('client',document.body.offsetWidth ,300);
			} else {
				setCookie('client',document.body.offsetWidth,300);
			}
            if(getCookie('client') == '') {
                    document.getElementById('enableCookieMsg').style.display = '';
					document.getElementById('enableCookieMsg').innerHTML = "Cookies are not getting set in your browser! Please Check.";
            }
		}
	}catch(e){}
}
setClientCookie();
</script>
<!--Start_Logo_Advertisement-->
	<!--Start_logoHeader-->
<div style="margin:0 10px">
	<div>
    	<div class="float_R" style="width:40.1%"><img src="/public/images/shik_ShikshaLogo.gif" alt="" align="right" onClick = "redirecttoShiksha('<?php echo SHIKSHA_HOME?>');" style="cursor:pointer" /></div>
        <div style="width:40.1%"><img src="/public/images/naukri_edu.gif" alt="" onClick = "window.location = '<?php echo NAUKRI_SHIKSHA_HOME?>'" style="cursor:pointer"/></div>
        <div class="clear_B withClear">&nbsp;</div>
    </div>
</div>
<!--End_Logo_Advertisement-->
<script>
function redirecttoShiksha(url)
{
showLoaderLayer('Redirecting you to Shiksha.com');
window.setTimeout(function(){ window.location = url; }, 1000);
}
            var bannerPool = new Array();
            function pushBannerToPool(bannerId, bannerUrl){
                if(bannerId != '') bannerPool[bannerId] = bannerUrl;
            }
</script>
<?php
$url=$_SERVER['REQUEST_URI'];

	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
	//$this->load->view('common/categorySearchOverlay.php');
    $this->load->view('user/registerConfirmation');
?>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>
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

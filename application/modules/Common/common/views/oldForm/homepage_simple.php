<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>
	<?php
	$title = isset($title) ? $title : '';
	echo $title;
	?>
	</title>
    <?php
        echo getHeadTrackJs();
    ?>
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

<?php if(isset($css) && is_array($css)) : ?>
	<?php foreach($css as $cssFile) : ?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
	<?php endforeach; ?>
<?php endif;

global $jsToBeExcluded;
$alreadyAddedJs = array('cityList');

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
<div id="enableCookieMsg" style="display:none;margin-left:10px;color:#ff0000" class="jser bld" align="center"></div>
<script>
<?php addJSVariables(); ?>
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

//Functions to close overlay without gray background end
var bannerPool = new Array();
function pushBannerToPool(bannerId, bannerUrl){
    if(bannerId != '') bannerPool[bannerId] = bannerUrl;
}
</script>
<body>
<div id="main-wrapper">
	<div id="top-bar" style="height:20px; overflow:hidden; background:#FFF !important;">
    		<div class="top-bar-child">
                <ul>
                    <li class="naukri-logo" title="A naukri.com group company"><span>A naukri.com group company</span></li>
                </ul>
      </div>
      </div>
      <div id="content-wrapper">
      <div class="wrapperFxd" id="main-wrapper">
    <input type="hidden" value="education india events courses colleges scholarships" id="google_keyword"/>
    <!--StartTopHeaderWithNavigation-->
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

var isQuickSignUpUser = <?php echo (is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['usergroup'] == 'quicksignupuser') ? "true" : "false"; ?>;

setLandingCookie();

function setLandingCookie()
{
if(getCookie('landingcookie') == '')
{
setCookie('landingcookie',location.href);
}
}

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
// START GLOBAL variables for F-SHARE
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>';
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
// START GLOBAL variables for F-SHARE
</script>



<!--Start_SignInBar-->
<div>
	<div>
    <?php
    if($naukriAssoc === "false"){ }else{?>
			<?php
				$url = base64_encode($taburl);
			?>
            

      <?php }?>
	</div>
</div>
<!--End_SignInBar-->
<?php if(isset($partnerPage) && !empty($partnerPage)) { ?>
<!--Start_Logo_Advertisement-->
<?php if(isset($headerHtml) && !empty($headerHtml)) { echo $headerHtml;?><?php }else {?>

<div>


<div id="logo-section">
	<h1 class="shik-logo"><a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com"><img src="<?php echo SHIKSHA_HOME."/public/images/nshik_ShikshaLogo1.gif";?>" alt="Shiksha.com" title="Shiksha.com" /></a></h1>
</div>

	<!--<div class="float_R" style="">
		<a href="<?php //echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
		<span class="logoNewClass" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
		</a>
	</div>-->
	<div>
        <a href="<?php echo constant(strtoupper($partnerPage) . '_URL'); ?>" title="<?php echo constant(strtoupper($partnerPage) . '_TITLE'); ?>"><img src="/public/images/<?php echo constant(strtoupper($partnerPage) . '_LOGO'); ?>" border="0" alt="<?php echo constant(strtoupper($partnerPage) . '_TITLE'); ?>" /></a>
        <?php if(isset($partnerPage) && !empty($partnerPage) && ($partnerPage != "shiksha")) { ?>
     	<span style="position:relative;top:-20px;font-size:28px;font-weight:bold;color:#4d4948;" >Education</span>
        <?php } ?>
	</div>
	<div class="clearFix"></div>
</div>
<?php }?>
<!--End_Logo_Advertisement-->
<?php } else { ?>
<!--Start_Logo_Advertisement-->
<div>
	<div id="logo-section">
	<h1 class="shik-logo"><a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com"><img src="<?php echo SHIKSHA_HOME."/public/images/nshik_ShikshaLogo1.gif";?>" alt="Shiksha.com" title="Shiksha.com" /></a></h1>
</div>
	<!--<a href="<?php //echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
	<span class="logoNewClass" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
	</a>-->
</div>
<!--End_Logo_Advertisement-->
<?php } ?>
<?php
$url=$_SERVER['REQUEST_URI'];
?>

<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
?>
<div class="lineSpace_5">&nbsp;</div>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>

<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>

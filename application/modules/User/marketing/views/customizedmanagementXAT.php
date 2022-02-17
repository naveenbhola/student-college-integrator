<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Shiksha.com - IBSAT result</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('marketing'); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('campaignXAT'); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
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
<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>

<script>
var messageObj;
var FLAG_LOCAL_COURSE_FORM_SELECTION = false;
function loadScript(url, callback){
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>', function(){
    //initialization code
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>', function(){
    //initialization code
    messageObj = new DHTML_modalMessage();
    messageObj.setShadowDivVisible(false);
    messageObj.setHardCodeHeight(0);
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>', function(){
    //initialization code
});
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("marketingpage"); ?>', function(){
    //initialization code
});
</script>
</head>

<body>
<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
?>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>
<?php $this->load->view('marketing/marketingSignInOverlay'); ?>


<!--Start_topHeader-->
<div id="Theader">
  <div class="W990">
    <div><a href="https://www.shiksha.com/" target="_blank" title="Shiksha.com"><img src="/public/images/campaignXAT/shikshaLogo.gif" width="276" height="84" border="0" /></a></div>
  </div>
</div>
<!--End_topHeader-->
<div class="mainWrapper">
  <div class="WrapperIMG"> 
    <!--Start_FormContainer-->
    <div id="lftColumn">
      <div>
        <h2>XAT 2013 <span>Results Declared</span></h2>
        <div class="bubble">See from which college you can get GD/ PI calls</div>
        <div><img src="/public/images/campaignXAT/tuppleShad.jpg" width="352" height="17" /></div>
        <div style="height:472px;"></div>
        <div class="ftrContWrap">
          <h3>Choose Shiksha.com to</h3>
          <div class="sepratorDiv"><span style="font-size:1px;"> </span></div>
          <ul>
            <li>
              <div class="Getcolz"></div>
              <p>Check your XAT 2013 Results.</p>
            </li>
            <li>
              <div class="Searchcolz"></div>
              <p>Search &amp; Apply to colleges with your XAT Score.</p>
            </li>
            <li>
              <div class="CnctMBA"></div>
              <p>Connect with other XAT Students.</p>
            </li>
            <li>
              <div class="ExprtAdv"></div>
              <p>Get Free Career Counseling from our Panel of Experts.</p>
            </li>
            <li>
              <div class="almReview"></div>
              <p>Get Alumni Review for XAT Colleges.</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div id="rhtColumn">
      <div id="formWrapper">
        <div class="OrgangeFont">Check your XAT 2013 result right here!</div>
        <div class="Txt12">We need a few details from you to create your free shiksha account.</div>
        <!--Start_Form_here-->
        <div><?php $this->load->view('marketing/new_customizedmanagement',array('hideFormHeading' => TRUE));?></div>
        <!--End_Form_here--> 
      </div>
      <!--End_FormContainer-->
      <div class="clr" style="height:16px;"></div>
      <div class="Txt13">Just fill in the small form on top to mention your educational preferences. Shiksha.com will help you select the most suitable course basis this information.</div>
    </div>
    <div class="clr"></div>
  </div>
  <div id="FooterWrap">
    <div class="sepratorDiv"><span style="font-size:1px;"> </span></div>
    <div class="ftrTxt">Copyright &copy; 2013 Info Edge India Ltd. All rights reserved.</div>
  </div>
</div>

<script>
fillProfaneWordsBag();
var isLogged = '<?php echo $logged; ?>';
var currentPageName = '<?php echo $pagename; ?>';
var pageTracker = null;
</script>
<div id="marketingLocationLayer_ajax"></div>
<div id="marketingusersign_ajax"></div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
<script id="galleryDiv_script_validate">
    <?php
    $userarray = json_decode($userDataToShow,true);
    if ((isset($userarray['Anydegree'])) && (!empty($userarray['Anydegree']))) {
    ?>
    check_degree_preference();
    <?php
    }
    ?>
    function RenderInit() {
        addOnBlurValidate(document.getElementById('frm1'));
        addOnFocusToopTip1(document.getElementById('frm1'));
    }
    window.onload = function () {
        try{
	    RenderInit();
            publishBanners();
        } catch (e) {
            // alert(e);
        }
        ajax_loadContent('marketingLocationLayer_ajax','/marketing/Marketing/ajaxform_mba/mr_page');
    }
</script>
<script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  loadScript(gaJsHost + 'google-analytics.com/ga.js', function(){
    try{
           pageTracker = _gat._getTracker("UA-4454182-1");
            pageTracker._setDomainName(".shiksha.com");
            pageTracker._initData();
            pageTracker._trackPageview();
            pageTracker._trackPageLoadTime();
        } catch(err) {}
  });
  function trackEventByGA(eventAction,eventLabel) {
	if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
	    pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
	}
	return true;
    }
</script>
<?php
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>
<!-- Begin comScore Tag -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "6035313" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
<img src="https://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>

<!--end_Footer-->
</body>
</html>

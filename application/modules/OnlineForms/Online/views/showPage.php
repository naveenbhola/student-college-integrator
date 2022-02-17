<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT=""/>
	<META NAME="Keywords" CONTENT=""/>
	<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
	<meta name="robots" content="ALL" />

	<title>Shiksha.com</title>

	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("online-styles"); ?>" type="text/css" rel="stylesheet" />
	<script>
	var urlforveri = 'http://<?php echo SHIKSHACLIENTIP;?>';
	var home_shiksha_url = '<?php echo SHIKSHA_HOME;?>';
	var currentPageName= null;
    <?php addJSVariables(); ?>
	var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
	</script>
</head>

<!-- Start: Display Shiksha Logo and Online Banner -->
<body id="wrapperMainForCompleteShiksha">
            <div id="top-bar">
                <div class="top-bar-child">
                    <div class="naukri-logo" title="A naukri.com group company"><span>A naukri.com group company</span></div>
                    <div class="clearFix"></div>
                </div>
            </div>
	<div style="background: url('/public/images/home-shade.gif') repeat-y scroll left top #FFFFFF;margin: 0 auto;padding: 0 4px; width: 988px;">	
	<div class="wrapperFxd">
		<noscript> <div class="jser"> <img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>

		<div id="logo-section" style="padding: 7px 6px 9px;">
    			<h1 class="shik-logo"><a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com"><img src="<?php echo SHIKSHA_HOME."/public/images/nshik_ShikshaLogo1.gif";?>" alt="Shiksha.com" title="Shiksha.com" /></a></h1>
			<div class="float_L" style="text-align:center;width:450px;margin-top:25px;"><button class="orange-button" onClick="window.location='<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'];?>';"><span class="bck-arr">&nbsp;</span> Back to Shiksha Application forms</button></div>
       			<div class="customerSupport">
			<div class="figure"></div>
                        <div class="details">
                            <p>
                                For online form Assistance<br />
                                <span>Call : 011-4046-9621</span>
                                (between 09:30 AM to 06:30 PM, Monday to Friday)
                            </p>
                        </div>
    			</div>
			</div>   
		<div class="clearFix"></div>

	</div>
	</div>
</body>
<!-- End: Display Shiksha Logo and Online Banner -->


<!-- Start: Display URL in iFrame -->
<iframe id="iframe1" width="100%" height="100%"  frameborder="0" src="<?php echo $url;?>" bordercolor="#000000" 
onload='sizeFrame(this);' vspace="0" hspace="0" marginheight="0" marginwidth="0"  ></iframe>



<script>

function getHeight(){
if(window.innerHeight){
var frameHeight = window.innerHeight - 147;
}
else{
        var B= document.body, 
        D= document.documentElement;
        var frameHeight = Math.max(D.clientHeight, B.clientHeight)-147;
}
return frameHeight;
}

function getWidth(){
if(window.innerWidth){
var frameWidth = window.innerWidth;
}
else{
        var B= document.body, 
        D= document.documentElement;
        var frameWidth = Math.max(D.clientWidth, B.clientWidth);
}
return frameWidth;
}

var obj = document.getElementById('iframe1');
obj.onload = function() { sizeFrame(obj) }



function sizeFrame(objId){
      objId.height = getHeight();
      objId.width = getWidth();
}

window.onresize = function(event) {
      document.getElementById('iframe1').height = getHeight();
      document.getElementById('iframe1').width = getWidth();
}

</script>
<!-- End: Display URL in iFrame -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="content-language" content="EN" />
<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ="�/>
<title><?php echo $title;?></title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("cmsLayout"); ?>" type="text/css" rel="stylesheet" />
<?php $this->load->view('common/TrackingCodes/JSErrorTrackingCode');?>
<?php
$cssExclude = array('footer','header','cal_style');
if(isset($css) && is_array($css)) : ?>
<?php foreach($css as $cssFile) :
if(!in_array($cssFile,$cssExclude)) {
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php }
endforeach;
endif;
global $jsToBeExcluded;
?>
<?php if(isset($js) && is_array($js)) :?>
<?php
        $alreadyAddedJs = array('header');
        if(!isset($js)){
           $js = array();
        }
        $js = array_unique(array_merge($alreadyAddedJs, $js));
    ?>
<?php foreach($js as $jsFile): ?>
<?php if(in_array($jsFile,$jsToBeExcluded)){?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo $jsFile; ?>.js"></script>
<?php } else { ?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
<noscript>
<div class="jser"><img style="vertical-align: middle;" src="https://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/enableJavascript.html">Click Here</a>, to know how to enable it.</div>
</noscript>
<script language = "javascript" src = "/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
<?php

if(!$isOldTinyMceNotRequired)
{
?>
    <script language = "javascript" src = "/public/js/tinymce3/jscripts/tiny_mce/tiny_mce.js"></script>
<?php
}
else
{
?>
<script type="text/javascript" src="//<?php echo $_SERVER['SERVER_NAME']; ?>/public/js/tinymce4/tinymce/js/tinymce/tinymce.min.js"></script>
<?php
}
?>

<script type="text/javascript"> 
  function loadJsFilesInParallel(){ 
        $LAB 
        .script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>") 
        .wait(function(){ 
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

                if(typeof(initializeAutoSuggestorInstanceSearchV2) == 'function') { // add new autoSuggestorV2
                    try{
                        
                        <?php 
                            if($product == 'campusConnectModeration') {
                                $autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('campusConnectModeration')); 
                                foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
                                    initShikshaSearch(); 
                                    autoSuggestorInstance = autoSuggestorInstanceArray['autoSuggestorInstance'];   // set object                    
                        <?php }
                    }?>


                    }catch(e){
                        console.log(e);
                    }
                }

        },1000); 
           
        }); 
  }

function initShikshaSearch() {
    return initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>')
} 
<?php 
    global $validDomains;
    global $invalidEmailDomains; 
?>
var validDomains = <?php echo json_encode($validDomains); ?>;
var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
</script> 
<!-- LABJs utility loaded in parallel--> 
<script type="text/javascript"> 
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout; 
  function f(){ loadJsFilesInParallel();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()}; 
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document); 
</script> 
 
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

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

function setExamPagePreviewCookie(c_name,value,expireTime,timeUnit) {
    if(typeof COOKIEDOMAIN == 'undefined') {
        COOKIEDOMAIN = '';
    }
    var today = new Date();
    today.setTime( today.getTime() );
    var cookieExpireValue = 0;
    expireTime = (typeof(expireTime) != 'undefined')?expireTime:0;
    timeUnit = (typeof(timeUnit) != 'undefined')?timeUnit:'days';
    if(expireTime != 0){
        if(timeUnit == 'seconds'){
            expireTime = expireTime * 1000;
        }else{
            expireTime = expireTime * 1000 * 60 * 60 * 24;
        }
        var exdate=new Date( today.getTime() + (expireTime) );
        var cookieExpireValue = exdate.toGMTString();
        document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN+""+((expireTime==null) ? "" : ";expires="+cookieExpireValue);
    }else{
        document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN;
    }
    if(document.cookie== '') {
        return false;
    }
    return true;
}

function setClientCookie(){
	try{
		if(getCookie('client') == "" || getCookie('client') ==  null || document.body.offsetWidth != getCookie('client')){
			if(getCookie('client') != "" && getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth > 1000 && getCookie('client') < 800) {
				if(setCookie('client',document.body.offsetWidth ,300)) {
					window.location.reload();
				} else {
					alert("Please enable cookies!!");
				}
			} else if(getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth < 1000 && getCookie('client') > 1000){
				if(setCookie('client',document.body.offsetWidth ,300)) {
					window.location.reload();
				} else {
					alert("Please enable cookies!!");
				}
			} else {
				if(!setCookie('client',1024 ,300)) {
					alert("Please enable cookies!!");
				}
			}
		}
	}catch(e){}
}
setClientCookie();

function obtainPostitionX(element) {
    var x=0;
    while(element)  {
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
<script type="text/javascript">
var DO_SEARCHPAGE_TRACKING = false;
</script>
<?php
$taburl=isset($taburl)?$taburl:"";
$data = array(
		'successurl'=> $taburl,
		'successfunction'=>'',
		'id'=>'',
		'redirect'=> 1
	     );
//$this->load->view('user/userlogin',$data);?>

<?php if($tabName == 'fb_lead_mapping'){?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link href="//<?php echo CSSURL; ?>/public/listingAdmin/bootstrap.min.css" rel="stylesheet">
        <script src="//<?php echo CSSURL; ?>/public/listingAdmin/bootstrap/dist/js/bootstrap.min.js"></script>
       <style>
        body {
            background-color:#f1f1f1;
        }
        </style> 
<?php }?>


<style>
body {
	font-size:12px
}
</style>
</head><body>

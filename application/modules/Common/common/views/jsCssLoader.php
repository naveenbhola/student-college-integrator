<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<?php $cssExclude = array('footer'); ?>
<?php if(isset($css) && is_array($css)) : ?>
	<?php foreach($css as $cssFile) : ?>
    <?php if(!in_array($cssFile,$cssExclude)) { ?>
        <?php if ($cssFile=="header" && ($_COOKIE['client']<=800)) : ?>
             <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile."800x600");?>" type="text/css" rel="stylesheet" />
        <?php else: ?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile);?>" type="text/css" rel="stylesheet" />
        <?php endif; ?>
    <?php } ?>    
	<?php endforeach; ?>
<?php endif;

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
</script>
<body>
<script>
<?php if ((isset($validateuser[0])) && !empty($validateuser[0])): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

var isQuickSignUpUser = <?php echo (is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['usergroup'] == 'quicksignupuser') ? "true" : "false"; ?>;

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
				if(!setCookie('client',document.body.offsetWidth,300)) {
					alert("Please enable cookies!!");
				}
			}
		}
	}catch(e){}
}
setClientCookie();
</script>
<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
	//$this->load->view('common/categorySearchOverlay.php');
//     $this->load->view('network/commonOverlay');
    $this->load->view('user/registerConfirmation');
?>
<div class="lineSpace_5">&nbsp;</div>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>

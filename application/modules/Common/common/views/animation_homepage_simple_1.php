<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<title><?php $title = isset($title) ? $title : ''; echo $title; ?></title>
		<?php echo getHeadTrackJs();?>
		<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
		<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
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
		<?php
		}
		?>
		<!--StartTopHeaderWithNavigation-->
<script type="text/javascript">
jQuery.noConflict();
	jQuery(document).ready(function(){	
		jQuery("#slider").easySlider({
			auto: true, 
			continuous: true,
			numeric: true
		});
	});	
</script>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery('#mycarousel').jcarousel();
jQuery('#mycarouse2').jcarousel();
});
</script>
<!--[if lt IE 7]>
<script language="javascript" type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("unitpngfix"); ?>"></script>
<![endif]-->
		<script>
		<?php if ((isset($displayname)) && !empty($displayname)): ?>
		isUserLoggedIn = true;
		<?php else: ?>
		isUserLoggedIn = false;
		<?php endif; ?>
		var isQuickSignUpUser = <?php echo (is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['usergroup'] == 'quicksignupuser') ? "true" : "false"; ?>;
		setLandingCookie();
		function setLandingCookie()	{
			if(getCookie('landingcookie') == '') {
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
		</script></head>
<body id="wrapperMainForCompleteShiksha" >
<?php
   $this->load->view('common/disablePageLayer.php');
$this->load->view('common/overlay.php');
?>
<input type="hidden" value="education india events courses colleges scholarships" id="google_keyword"/>
		<!--Start_SignInBar-->
		<div class="mar_full_10p">
			<div>
				<?php
				if($naukriAssoc === "false"){ }else{?>
					<div class="normaltxt_11p_blk float_R" align="right">
						<span id="naukriLogo">&nbsp;</span><span class="setPosTop_3">&nbsp;
						<?php $url = base64_encode($taburl);?>
						</span>
					</div>
				<?php }?>
			</div>
		</div>
                <noscript><div class="jser"><img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
	<div id="enableCookieMsg" style="display:none;margin-left:10px;color:#ff0000" class="jser bld" align="center"></div>
		<!--End_SignInBar-->
				<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>

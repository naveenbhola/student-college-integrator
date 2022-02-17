<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<title><?php $title = isset($title) ? $title : ''; echo $title; ?></title>
		<?php echo getHeadTrackJs();?>
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
		<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
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
	</head>
	<noscript><div class="jser"><img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
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

	<body id="wrapperMainForCompleteShiksha" style="background:#F1EFF0">
		<?php
				$this->load->view('common/disablePageLayer.php');
				$this->load->view('common/overlay.php');
		?>
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
		</script>
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
		<!--End_SignInBar-->
		<?php
		if(isset($partnerPage) && !empty($partnerPage))
		{
		?>
		<div class="wrapperFxd" style="background:url(/public/images/bkgAni.jpg) no-repeat">
			<!--Start_Margin-->
			<!--<div style="position:relative;top:0;left:0">
			<div style="position:absolute;left:730px;top:177px;z-index:1"><img src="/public/images/ani_char.gif"  /></div>
			</div>-->
			<div style="height:55px">&nbsp;</div>
			<div style="width:840px;margin:0 0 0 25px;position:relative">
				<div><img src="/public/images/a_tc.gif" /></div>
				<div style="width:840px;background:url(/public/images/a_tm.gif) repeat-y">
					<div class="mlr20">
						<div>
							<div class="float_L"><a href="http://www.shiksha.com/" title="Shiksha.com"><img src="/public/images/a_logo.gif" border="0" /></a></div>
							<div class="float_R"><a href="http://www.naukri.com/" title="Naukri.com"><img src="/public/images/a_nlogo.gif" border="0" /></a></div>
							<div class="clear_B">&nbsp;</div>
						</div>
						<div class="Fnt20 whiteFont pt40">Enhance your creative skills with a degree in Animation</div>
			<!--OLD_CODE-->
			<!--Start_Logo_Advertisement-->
			<?php //if(isset($headerHtml) && !empty($headerHtml)) { echo $headerHtml;?><?php //}else { ?>
				<!--<div class="mar_full_10p">
					<div class="float_R" style="">
						<a href="<?php //echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
						<span class="logoNewClass" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
						</a>
					</div>
					<div>
						<a href="<?php //echo constant(strtoupper($partnerPage) . '_URL'); ?>" title="<?php //echo constant(strtoupper($partnerPage) . '_TITLE'); ?>"><img src="/public/images/<?php //echo constant(strtoupper($partnerPage) . '_LOGO'); ?>" border="0" alt="<?php //echo constant(strtoupper($partnerPage) . '_TITLE'); ?>" /></a>
						<?php //if(isset($partnerPage) && !empty($partnerPage) && ($partnerPage != "shiksha")) { ?>
						<span style="position:relative;top:-20px;font-size:28px;font-weight:bold;color:#4d4948;" >Education</span>
						<?php //} ?>
					</div>
					<div class="clear_R"></div>
				</div>-->
			<?php //}?>
			<!--End_Logo_Advertisement-->
			<?php //} else { ?>
			<!--Start_Logo_Advertisement-->
			<!--<div class="mar_full_10p">
				<a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
				<span class="logoNewClass" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
				</a>
			</div>-->
			<!--End_Logo_Advertisement-->
			<?php } ?>
			<!--END_OLD_CODE-->

			<?php $url=$_SERVER['REQUEST_URI'];?>
			<div class="lineSpace_5">&nbsp;</div>
			<div id="dataLoaderPanel" style="position:absolute;display:none"><img src="/public/images/loader.gif"/></div>
<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>

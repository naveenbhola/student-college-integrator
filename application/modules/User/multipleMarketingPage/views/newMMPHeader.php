<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>
			<?php
				$title = isset($title) ? $title : "Shiksha.com - India's no. 1 college selection website: Register";
				echo $title;
			?>
		</title>
		<?php
			echo getHeadTrackJs();
		?>
		
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
		

		<?php $this->load->view('common/TrackingCodes/JSErrorTrackingCode');?>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<?php if($mmp_details['page_type'] == 'abroadpage') { ?>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet"  type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
		<?php } ?>
		<script type="text/javascript">
			$j = jQuery.noConflict();
		</script>
		
		<?php if(isset($css) && is_array($css)) : ?>
			<?php foreach($css as $cssFile) : ?>
				<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
			<?php endforeach; ?>
		<?php endif; ?>
		
		<?php
			global $jsToBeExcluded;
			$alreadyAddedJs = array();
			
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

	<noscript>
		<div class="jser">
			<img style="vertical-align: middle;" src="https://w10.naukri.com/jobsearch/images/jsoff.gif"/>
			Javascript is disabled in your browser due to this certain functionalities will not work.
			<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.
		</div>
	</noscript>
	
	<div id="enableCookieMsg" style="display:none;margin-left:10px;color:#ff0000" class="jser bld" align="center"></div>
	<script>
		<?php global $validDomains; global $invalidEmailDomains; ?>
		var validDomains = <?php echo json_encode($validDomains); ?>;
		var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
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

	<?php
		$this->load->view('common/googleCommon');
	?>

	<input type="hidden" value="education india events courses colleges scholarships" id="google_keyword"/>
	<!--StartTopHeaderWithNavigation-->
	<script>			
		setLandingCookie();
		
		function setLandingCookie() {
			if(getCookie('landingcookie') == '') {
				setCookie('landingcookie',location.href);
			}
		}
		
		function getCookie(c_name) {
			if (document.cookie.length>0) {
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
			} catch(e){}
		}
		setClientCookie();
		
	</script>

	<div class="lineSpace_5">&nbsp;</div>
	
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	


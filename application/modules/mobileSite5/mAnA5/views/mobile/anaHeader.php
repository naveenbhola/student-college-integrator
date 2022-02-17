<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
global $isWebViewCall;
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
	$displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
	$displayHamburger = true;
}
if($displayHamburger){
	echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
	echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>
<header id="page-header"  data-role="header" class="header" data-position="fixed">
  <?php if(!$isWebViewCall){echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger, '',"AnAMobile");}?>
  <?php $this->load->view('mobile/anaTabs');?>
</header>

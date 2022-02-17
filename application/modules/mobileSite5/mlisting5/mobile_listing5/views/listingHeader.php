<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
	$displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
	$displayHamburger = true;
}else if(strpos($_SERVER['HTTP_REFERER'],'muser5/MobileUser/login') > 0){
	$displayHamburger = true;
}


if($displayHamburger){
	echo Modules::run('mcommon5/MobileSiteHamburger/getHamburgerMenu','mypanel');
}
	echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>    
    <header id="page-header" data-role="header" class="header ui-header-fixed" data-tap-toggle="false" data-position="fixed">
		<?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger);?>
    </header>
	    
<!------this-is-used-for-opecity-layer----->
<div id="popupBasicBack" data-enhance='false'></div>

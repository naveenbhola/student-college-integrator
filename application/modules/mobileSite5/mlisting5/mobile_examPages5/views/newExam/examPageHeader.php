
<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER'] || ($_SERVER['HTTP_REFERER'] == $currentUrlWithParams)){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}else if(strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ 
//If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
}
//Put a check that if Hash value is added, we have to show the Hamburger
if(strpos($_SERVER["REQUEST_URI"], 'showHam') > 0){
	$displayHamburger = true;
}

if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'',$boomr_pageid,$isShowIcpBanner);?></div>
       <div id="fixed-card" class="nav-tabs color-w nav-bar pos-rl" style="display:none">
           <?php $this->load->view('mobile_examPages5/newExam/sectionNavTab');?>
      </div>
    </header>

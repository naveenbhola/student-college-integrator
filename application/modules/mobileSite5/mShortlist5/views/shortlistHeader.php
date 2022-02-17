<script language = javascript>
var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";
</script>

<?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
            $displayHamburger = true;
    }
    else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
            $displayHamburger = true;
    }
    else if($_SERVER['HTTP_REFERER']){
            setcookie('back-button-link-on-shortlist-page', $_SERVER['HTTP_REFERER'],time()+3600,'/',COOKIEDOMAIN);
    }
    if($displayHamburger){
	
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	
    }
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>

    <header id="page-header" class="header" data-role="header" data-tap-toggle="false" data-position="fixed">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,$isBackBtnCookei=$_COOKIE['back-button-link-on-shortlist-page'],$boomr_pageid);?>   
    </header>
    <!------this-is-used-for-opecity-layer----->
   <div id="popupBasicBack" data-enhance='false'></div>
    

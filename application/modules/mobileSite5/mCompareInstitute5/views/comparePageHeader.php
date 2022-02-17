    <!-- Show the page Header -->    
     <!--header id="page-header2" class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed" style="height:auto;">
     <div id="page-header-container" style="display:none;"></div>
	 <div id="page-header-inst-sticky" style="display:none;"></div>
    </header-->
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
	   <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,$isBackBtnCookei,$boomr_pageid);?></div>
	   <div id="page-header-inst-sticky" style="display:none;"></div>
    </header>
    <!-- End the Header page -->
   <!------this-is-used-for-opecity-layer------>

   <div id="popupBasicBack" data-enhance='false'></div>
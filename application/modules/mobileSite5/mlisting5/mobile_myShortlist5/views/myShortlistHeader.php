<!-- Show the page Header -->    
<header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false"  data-position="fixed">
    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
	<div class="page-sub-header" id="shortListSubHeader">
		<?php 
			$style = "visibility:hidden;";
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){ 
				$style = "";
			}
            $currentUrl = htmlspecialchars($this->input->server['SCRIPT_URL']);
		?>
		<a href="javascript:void(0);" data-rel="back" class="go-back" style="<?php echo $style;?>"><i class="msprite prv-icn"></i></a>
		<a id="myShortlistHeaderCount" href="<?php echo $homepageUrl ?>"><h1 style="display:inline">MY SHORTLIST</h1> <span id="shrtCnt">(<?php echo count($shortlistedCoursesIds); ?>)</span></a>
		<a href="#walkthroughHTML" data-rel="dialog" data-inline="true" data-transition="pop" onclick="showWalkthrough();" class="help"><span>?</span></a>
	</div>
</header>
<div id="walkthroughLoader"></div>
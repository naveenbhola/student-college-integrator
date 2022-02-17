<?php
$headerComponents = array(
    'searchPage'=>'true',
    'autoSuggestorPageName' => 'search_page_mobile',
    'mobilecss' => array('msearchResultPage','tuple'),
    'jsMobileFooter' => array('msearchResultPage', 'ana')
);

if($product == "MsearchV3"){
	$headerComponents['addNoFollow'] = TRUE;
}
if($product == "McategoryList" && empty($totalInstituteCount)){
	$headerComponents['addNoFollow'] = TRUE;
}

$this->load->view('mcommon5/header',$headerComponents); 

if($product == "MsearchV3") {
	echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_SEARCH_PAGE',1);
}
else if($product == "McategoryList") {
	echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_CATEGORY_PAGE',1);
} 
else if($product == "MAllCoursesPage") {
	echo jsb9recordServerTime('SHIKSHA_MOB_ALL_COURSES_PAGE',1);
} ?>

<div id="popupBasicBack" data-enhance="false"></div>
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 80px;">

<?php
    echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>

<header id="page-header" data-role="header" class="header ui-header-fixed" data-tap-toggle="false">
    <div id="page-header-container" style="">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true,'','',$isShowIcpBanner); ?>
    </div>
    <?php if ($totalInstituteCount || $totalCourseCount) { ?>
        <div id="search-sort-options" class="sort-filter-sec" data-enhance=false>
            <a id="searchPageFilterLink" class="nav_main_head" href="#searchFilters" data-transition="none">FILTER <i class="msprite filter-icon"></i></a>
        </div>
    <?php } ?>
</header>
<div data-role="content">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
?>
<div data-enhance="false">
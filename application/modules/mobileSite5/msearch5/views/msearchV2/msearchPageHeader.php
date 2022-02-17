<?php
$headerComponents = array(
'searchPage'=>'true',
'autoSuggestorPageName' => 'search_page_mobile',
'mobilecss' => array('msearchResultPage'),
'jsMobileFooter' => array('msearchResultPage'),
'addNoFollow' => 'true'
);

$this->load->view('mcommon5/header',$headerComponents); ?>

<div id="popupBasicBack" data-enhance="false"></div>
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 80px;">

<?php
echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>

<header id="page-header" data-role="header" class="header ui-header-fixed" data-tap-toggle="false"  data-position="fixed">
    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true); ?>
    <?php if ($totalInstituteCount) { ?>
        <div id="search-sort-options" class="sort-filter-sec" data-enhance=false>
            <a id="searchPageFilterLink" href="#searchFilters" data-transition="none">FILTER <i class="msprite filter-icon"></i></a>
        </div>
    <?php } ?>
</header>
<div data-role="content">
	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
	?>
<div data-enhance="false">
<?php
$title   = "";
$description = "";
$metaKeywords = "";
if(!empty($meta_details)){
  $title = $meta_details['title'];
  $description = $meta_details['description'];
}
$headerComponents = array(
  'm_meta_title'=>$title,
  'm_meta_description'=>$description,
  'm_meta_keywords'=>$metaKeywords,
  'canonicalURL' => $canonical,
  'jsMobile' => array(),
  'mobilePageName' => 'mRankingPage',
  'noJqueryMobile' => 1,
  'preloadCss' => array('tuple')
);
// $this->load->view('mcommon5/header',$headerComponents); 
$this->load->view('/mcommon5/headerV2',$headerComponents);
?>
<style type="text/css">
<?php $this->load->view('mranking5/ranking_page/ranking_page_mobile_css',$headerComponents); ?>
</style>
<div id="wrapper" data-role="page" style="min-height: 413px;">
<?php
    // Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
            $displayHamburger = true;
    }else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
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
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
        <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','ranking_page',$isShowIcpBanner);?></div>
        <div class="nav-tabs" id="fixed-card" style="display:none">
            <?php $this->load->view('mobile_listing5/course/widgets/courseTabSection'); ?>
        </div>
    </header>
    <div data-role="content" id="pageMainContainerId">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
        <div data-enhance="false">  
            <?php 

              $this->load->view('mranking5/ranking_page/ranking_page_seo_table'); 
              $this->load->view('mranking5/ranking_page/ranking_inner_page'); 
              $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array('ranking_page_mobile'),'cssFooter'=>array('websiteTour', 'mcommon') ));
              // $this->load->view('/mcommon5/footerLinks');
            ?>
        </div>
    </div>
</div>
<div id="popupBasicBack" data-enhance='false'></div> 

<?php 
// $this->load->view('mcommon5/footer');
$this->load->view('/mcommon5/footerV2');
    
?>
<?php $this->load->view('mranking5/ranking_page/ranking_filter_dialog'); ?>
<script>
    var pageName = 'ranking_page';
    var tupleType = '<?php echo $tupleType?>';
    var GA_currentPage = "RANKING_PAGE";
    var ga_user_level = "<?php echo $mobileRequest?"MOBILE":"DESKTOP";?>";
    var instituteDataMapping = JSON.parse(JSON.stringify(<?php echo json_encode($instituteDataMapping); ?>));
    var SHOW_RANKING_WEBSITE_TOUR = "<?php echo SHOW_RANKING_WEBSITE_TOUR; ?>"
    <?php 
      if(SHOW_RANKING_WEBSITE_TOUR){
        ?>
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        <?php
      }
    ?>
    var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile'); ?>'; 
    var layerClosed = false; 
    var isNoSticky = true;
</script>
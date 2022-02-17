
<?php ob_start('compress'); ?>
<?php
if($listing_type == 'institute'){
        $ampUrl = str_replace('/college/','/college/amp/',$m_canonical_url);
}else{
        $ampUrl = str_replace('/university/','/university/amp/',$m_canonical_url);
}
$headerComponents = array(
      'm_meta_title'=>$m_meta_title,
      'm_meta_description'=>$m_meta_description,
      'm_meta_keywords'=>$m_meta_keywords, 
      'canonicalURL' => $m_canonical_url,
      'product'         =>   'mListingDetailPage',
      'jsMobile' => array(),
      'mobilePageName'  =>   $mobilePageName,
      'ampUrl' => $ampUrl,
      'mobilecss' => array('mcommon'),
      'noJqueryMobile' => 1
);
  $this->load->view('/mcommon5/headerV2',$headerComponents);
?>
<?php 
  if($listing_type == 'institute')
    echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_COLLEGE_LISTINGS',1);
  elseif($listing_type == 'university')
    echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_UNIVERSITY_LISTINGS',1);
?>

<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
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
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP',$isShowIcpBanner);?></div>
       <div id="fixed-card" class="nav-tabs" style="display:none">
          <?php $this->load->view("institute/widgets/navTabs");?>
      </div>
    </header>

    <div data-role="content" id="pageMainContainerId">     
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
      <div data-enhance="false" >
        <?php if(!empty($instituteCourses) && count($instituteCourses)>0){?>
            <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP">
            <input type="hidden" id="instituteIdQP" name="instituteIdQP" >
            <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Listing_MOB">
            <input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">
          <?php } ?>

        <?php $this->load->view('institute/instituteDetailPageContent');  ?>
        <?php $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array("bootstrap-tour-standalone.min","websiteTour",'recoSlider'),'cssFooter'=>array('websiteTour','recoSlider') )); ?>

      </div>
  </div>
   <input type="hidden" id="listing_type" value="<?php echo $listing_type;?>">
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >

<?php $this->load->view('/mcommon5/footerV2',array('jsMobileFooter' => array('mobileSearchV2')));?>

<script type="text/javascript">
  var globalListingId = '<?=$instituteObj->getId()?>';
  var globalListingType = '<?=$instituteObj->getType()?>';
  var globalCourseListQP = '<?php echo addSlashes(json_encode($instituteCourses))?>';


  var contactKeyId            = '<?=$ampKeys['CONTACT_WIDGET']?>';
  var compareStickyKeyId      = '<?=$ampKeys['COMPARE_STICKY_WIDGET']?>';
  var brochureStickyKeyId     = '<?=$ampKeys['BROCHURE_STICKY_WIDGET']?>';
  var shortlistTopwidgetKeyId = '<?=$ampKeys['SHORTLIST_TOP_WIDGET']?>';
  
  var pos             = '<?=$pos;?>';
  var courseId = '<?=$courseId;?>';
  var uriActionType   = '<?=$actionType;?>';
  var uriFromWhere    = '<?=$fromwhere;?>';
  var replaceStateUrl = '<?=$replaceStateUrl;?>';
  //code to set viewed response data
  var viewedResponseCourseId = '<?php echo $course->getId();?>';
  var viewedResponseAction = '<?php echo $viewedResponseAction;?>';
  var viewedResponseTrackingKey = '<?=$instituteViewedTrackingPageKeyId;?>';


  var img = document.getElementById('beacon_img');
  var randNum = Math.floor(Math.random()*Math.pow(10,16));
  img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$listing_id?>+<?=$viewCountListingType?>';

  lazyLoadCss("//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile');?>");


  jQuery(document).ready(function($) {
    initializeInstituteDetailPage();
    <?php if($_REQUEST['showAllBranches'] == 1 && $isMultilocation){
          ?>
                    if($("#location-layer").length > 0)
                      showLocationLayer();
          <?php
          }?>
  });

  jQuery(window).load(function(){
    initLazyLoad();
  });

  contentMapping = JSON.parse('<?php echo json_encode(Modules::run('common/WebsiteTour/getContentMapping','cta','mobile')); ?>');
  //$(window).load(function(){
    if(uriActionType == 'compare' && $('#popupBasic-Short-Compare').css('display') != 'none')
    {
      setTimeout(function(){$(window).scrollTop($('#popupBasic-Short-Compare').offset().top - 50);},180);    
    }
  //});
</script>
<?php ob_end_flush(); ?>

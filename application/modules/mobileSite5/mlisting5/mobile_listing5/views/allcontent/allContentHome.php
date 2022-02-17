<?php ob_start('compress');?>

<?php
if($totalCount || $isAdmissionDetailsAvailable)
    $noIndexMetaTag = 0;
else
    $noIndexMetaTag = 1;

  
$scholarships = $instituteObj->getScholarships();
if($pageType == 'scholarships' && empty($scholarships)){
  $noIndexMetaTag = 1;   
}

$headerComponents = array(
      'noIndexMetaTag' => $noIndexMetaTag,
      'mobilecss' => array('allcontent','style','listingCommon', 'tuple','minstitute')
        );
$this->load->view('/mcommon5/header',$headerComponents);
global $isWebViewCall;
?>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<div id="wrapper" data-role="page" style="min-height: 413px;" >	

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
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

    
    <?php 
    if($pageType == "admission"){?>
      <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
         <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_AllContent_Page',$isShowIcpBanner);?></div>
         <div id="fixed-card" class="nav-tabs" style="display:none">
            <?php $this->load->view("allcontent/widgets/admissionNavTabs"); ?>
        </div>
      </header>
    <?php }else if(!$isWebViewCall){ ?>
      <header id="page-header"  data-role="header" class="header ui-header-fixed" data-position="fixed" data-tap-toggle="false">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','',$isShowIcpBanner);?>
      </header>
    <?php } ?>

    
      <?php 
		if($pageType == "admission" || $pageType == 'scholarships'){?>
    <div data-role="content" id="pageMainContainerId">     
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
      ?>
      <div data-enhance="false" >
			<?php $this->load->view('allcontent/admissionInnerPage');
		}
		else{ ?>
      <div data-role="content" data-enhance="false">
			<?php $this->load->view('allcontent/allContentInnerPage'); 
		}
	?>

       <?php if(!empty($instituteCourses) && count($instituteCourses)>0){?>
          <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP">
          <input type="hidden" id="instituteIdQP" name="instituteIdQP" value="<?=$listing_id?>">
          <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Institute_Questions_Page">
          <input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">
        <?php } ?>

      <?php $this->load->view('/mcommon5/footerLinks'); ?>

      <?php if($pageType == 'questions'){
		$this->load->view('/mAnA5/mobile/shareLayer'); 
      } ?>

<?php 
 switch ($pageType) {
        case 'questions':
                $GA_currentPage = 'ALL QUESTIONS PAGE';
                $viewedResponseAction = 'MOB_Institute_Viewed';
                $viewedResponseTrackingKey = 2059;
                $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLQUESTIONS_MOB';
                $GA_Tap_On_Action = '';
                $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
                $dBrochureStickyTrackingKeyId = 1157;
                $displayValue="Ask Question";
		            $firstCTAOnClick = "askQuestionCTA";
                $firstCTAOnClickParams = "";
                echo jsb9recordServerTime('SHIKSHA_NATIONAL_MOB_ALL_QUESTIONS_PAGE',1);
                echo includeJSFiles('shikshaMobileWebsiteTour');
                $hrefAttr = "#questionPostingLayerOneDiv";
            break;
        case 'reviews':
                $GA_currentPage = 'ALL REVIEWS PAGE';
                $viewedResponseAction = 'MOB_Institute_Viewed';
                $viewedResponseTrackingKey = 2063;
                $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLREVIEWS_MOB';
                $GA_Tap_On_Action = 'COMPARE_STICKY';
                $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
                $dBrochureStickyTrackingKeyId = 1152;
                $compareStickyTrackingKeyId = 1153;
                $displayValue="Compare";
		            $firstCTAOnClick = "showCourseCompareLayer";
                $firstCTAOnClickParams = $compareStickyTrackingKeyId;
                $hrefAttr = "javascript:void(0)";
                echo jsb9recordServerTime('SHIKSHA_NATIONAL_MOB_ALL_REVIEWS_PAGE',1);
                break;
        case 'articles':
                $GA_currentPage = 'ALL ARTICLES PAGE';
                $viewedResponseAction = 'MOB_Institute_Viewed';
                $viewedResponseTrackingKey = 2067;
                $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLARTICLES_MOB';
                $GA_Tap_On_Action = 'COMPARE_STICKY';
                $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
                $dBrochureStickyTrackingKeyId = 1147;
                $compareStickyTrackingKeyId = 1148;
                $displayValue="Compare";
                $firstCTAOnClick = "showCourseCompareLayer";
                $firstCTAOnClickParams = $compareStickyTrackingKeyId;
                echo jsb9recordServerTime('SHIKSHA_NATIONAL_MOB_ALL_ARTICLES_PAGE',1);
                $hrefAttr = "javascript:void(0)";
                break;
        case 'admission':
                $GA_currentPage = 'ADMISSION PAGE';
                $viewedResponseAction = 'MOB_Institute_Viewed';
                $viewedResponseTrackingKey = 2071;
                $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ADMISSION_MOB';
                $GA_Tap_On_Action = 'COMPARE_STICKY';
                $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
                $dBrochureStickyTrackingKeyId = 1145;
                $compareStickyTrackingKeyId = 1146;
                $displayValue="Compare";
                $firstCTAOnClick = "showCourseCompareLayer";
                $firstCTAOnClickParams = $compareStickyTrackingKeyId;
                echo jsb9recordServerTime('SHIKSHA_NATIONAL_MOB_ADMISSION_PAGE',1);
                $hrefAttr = "javascript:void(0)";
                break;
        case 'scholarships':
                $GA_currentPage = 'SCHOLARSHIP PAGE';
                $viewedResponseAction = 'MOB_Institute_Viewed';
                $viewedResponseTrackingKey = 2097;
                $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_SCHOLARSHIP_MOB';
                $GA_Tap_On_Action = 'COMPARE_STICKY';
                $GA_Tap_On_CTA = 'DBROCHURE_STICKY';
                $dBrochureStickyTrackingKeyId = 1557;
                $compareStickyTrackingKeyId = 1561;
                $displayValue="Compare";
                $firstCTAOnClick = "showCourseCompareLayer";
                $firstCTAOnClickParams = $compareStickyTrackingKeyId;
                echo jsb9recordServerTime('SHIKSHA_NATIONAL_MOB_SCHOLARSHIP_PAGE',1);
                $hrefAttr = "javascript:void(0)";
                break;
        default:
            # code...
            break;
    }
?>
      <div class="com-shrtlst-sec" id="stickyCTA" style="display:none;">
          <div class="lcard">
              <div class="com-col"><a class="btn-mob-blue" href='<?=$hrefAttr;?>' bindfunc="<?=$firstCTAOnClick;?>" bindfunc-params=<?=$firstCTAOnClickParams;?> ga-attr="<?=$GA_Tap_On_Action?>" data-rel="dialog" data-transition="fade"><?=$displayValue?></a></div>

              <div class="com-col"><a class="btn-mob" ga-attr="<?=$GA_Tap_On_CTA;?>" pageType= <?php echo $pageType;?> trackingId=<?php echo $dBrochureStickyTrackingKeyId;?>>Request Brochure</a></div>
          </div>
      </div>
    </div>
    <?php 
    if($pageType == "admission" || $pageType == "scholarships" ){?>
  </div>
    <?php } ?>
   
</div>

<?php
    $this->load->view("institute/widgets/courseCompareLayer"); 
?>

<?php $this->load->view('/mcommon5/footer', array("jsMobileFooter" => array('mAllContent','mCourse')));?>

<?php if($pageType == 'questions'){ ?>
<script async src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('ana','nationalMobile'); ?>"></script>
<script>
    //Load website tour
    $(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta', 'mobileQuestion', contentMapping);
    });
</script>
<?php }else if($pageType == 'admission' || $pageType == 'scholarships'){ ?>
<?php } ?>

<?php ob_end_flush(); ?>
<script>

var globalListingId = '<?=$instituteObj->getId()?>';
var globalListingType = '<?=$instituteObj->getType()?>';
<?php if(!empty($instituteCourses) && $pageType == 'questions'){?>
var globalListingId = '<?=$instituteObj->getId()?>';
var globalListingType = '<?=$instituteObj->getType()?>';
var globalCourseListQP = '<?php echo addSlashes(json_encode($instituteCourses))?>';
<?php }else if(!empty($courseId) && $pageType == 'admission'){?>
  var courseId = '<?=$courseId;?>';
<?php  }?>

initializeAllContentPage();

    var ga_page_name      = '<?=$GA_currentPage;?>';
    var viewedResponseAction = '<?=$viewedResponseAction;?>';
    var viewedResponseTrackingKey = '<?=$viewedResponseTrackingKey;?>';
    var viewedResponseCourseId = '<?=$flagshipCourseId;?>';
    var GA_currentPage      = '<?=$GA_currentPage;?>';
    var ga_commonCTA_name = '<?=$GA_commonCTA_Name;?>';
    var ga_user_level     = '<?=$GA_userLevel;?>';
    var GA_userLevel     = '<?=$GA_userLevel;?>';
    var multiLocationCourse = parseInt('<?= $multiLocationCourse;?>');
    var pageNumber = '<?=$pageNumber;?>';
    createAllContentViewedResponse();
    <?php if($pageType == 'admission'){?>
      lazyLoadCss("//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile');?>");
    <?php } ?>

    <?php if($showToastMsg && $validateuser!='false'){?>
          setTimeout(function(){
            handleToastMsg("<?=$SRM_DATA['ToastMsg']?>",5000);
          },3000);

    <?php } ?>

</script>
<?php if(!in_array($pageType, array('admission', 'scholarships'))){ ?>
  <script>
      generateFiltersHtml(<?php echo json_encode($filtersArray);?>,'<?php echo $base_url;?>',<?php echo json_encode($queryParameters);?>);
  </script>
<?php } ?>
<div id="popupBasicBack" data-enhance="false"></div>

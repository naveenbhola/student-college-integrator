<?php
ob_start('compressHTML');
if(!$isAjax)
{
    $scholarships = $instituteObj->getScholarships();
    $tempJsArray = array('allContent');
    if($totalElements > 0 || $isAdmissionDetailsAvailable){
        $noIndexMetaTag = 0;
    }
    else{
        $noIndexMetaTag = 1;
    }
    if($pageType == 'scholarships' && empty($scholarships)){
     $noIndexMetaTag = 1;   
    }
    $cssFiles = array('allContentPage');
    if($pageType == 'questions'){
       $cssFiles[] = 'ana';
       $cssFiles[] = 'quesDiscPosting';
    }
    $tempJsArray[] = 'listingCommon';
    if($pageType == 'admission'){
        $tempJsArray[] = 'courseDetailPage';
    }
    $headerComponents = array(
                    'css'   =>  $cssFiles,
                    'js' => array(),
                    'jsFooter'=>    $tempJsArray,
                    'subProduct' => $pageType,
                    'showBottomMargin' => false,
                    'title' => $seoTitle,
                    'metaDescription' => $seoDesc,
                    'canonicalURL' => $canonicalURL,
                    'previousURL' => $prevURL,
                    'nextURL' => $nextURL,
                    'product' => 'allContentPage',
                    'noIndexMetaTag' => $noIndexMetaTag,
                    "lazyLoadJsFiles" => true
    );
    $this->load->view('common/header', $headerComponents);    
}

?>
<script type="text/javascript">
var flag = '<?php echo $isAjax;?>';
    if(flag == 0){
        var isShowGlobalNav = false;
    }
isCompareEnable = true;
</script>
<?php if($pageType != 'questions') { ?>
    <div class="notify-bubble report-msg" id="noti" style="opacity: 1; display: none;">
        <div class="msg-toast">
        <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
        <p id="toastMsg"></p>
        </div>
    </div>
<?php } ?>
<?php
    if($pageType == 'questions'){
        $this->load->view('messageBoard/desktopNew/listOfUserLayer');
    }
    
    $this->load->view('AllContentPage/widgets/allContentCourseLayer');

    $this->load->view('AllContentPage/AllContentInnerSection');
    if(!$isAjax)
    {
        $this->load->view('common/footer');     
        if($pageType == 'questions'){
            $this->load->view('messageBoard/desktopNew/shareLayer');
            $this->load->view('messageBoard/desktopNew/toastLayer');
        }
    }

    switch ($pageType) {
        case 'questions':
            $GA_currentPage = 'ALL QUESTIONS PAGE';
            $viewedResponseAction = 'Institute_Viewed';
            $viewedResponseTrackingKey = 2057;
            $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLQUESTIONS_DESK';
            echo includeJSFiles('shikshaDesktopWebsiteTour');
            echo jsb9recordServerTime('SHIKSHA_NATIONAL_ALL_QUESTIONS_PAGE',1);
            break;
        case 'reviews':
            $GA_currentPage = 'ALL REVIEWS PAGE';
            $viewedResponseAction = 'Institute_Viewed';
            $viewedResponseTrackingKey = 2061;
            $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLREVIEWS_DESK';
            echo jsb9recordServerTime('SHIKSHA_NATIONAL_ALL_REVIEWS_PAGE',1);
            break;
        case 'articles':
            $GA_currentPage = 'ALL ARTICLES PAGE';
            $viewedResponseAction = 'Institute_Viewed';
            $viewedResponseTrackingKey = 2065;
            $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ALLARTICLES_DESK';
            echo jsb9recordServerTime('SHIKSHA_NATIONAL_ALL_ARTICLES_PAGE',1);
            break;
        case 'admission':
            $GA_currentPage = 'ADMISSION PAGE';
            $viewedResponseAction = 'Institute_Viewed';
            $viewedResponseTrackingKey = 2069;
            $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_ADMISSION_DESK';
            echo jsb9recordServerTime('SHIKSHA_NATIONAL_ADMISSION_PAGE',1);
            break;
        case 'scholarships':
            $GA_currentPage = 'SCHOLARSHIP PAGE';
            $viewedResponseAction = 'Institute_Viewed';
            $viewedResponseTrackingKey = 2095;
            $GA_commonCTA_Name = '_'.strtoupper($listing_type).'_SCHOLARSHIP_DESK';
            echo jsb9recordServerTime('SHIKSHA_NATIONAL_SCHOLARSHIP_PAGE',1);
            break;
        default:
            # code...
            break;
    }
    
    
?>
<?php if($pageType != 'questions') { 
            $displayData                   = array(); 
            $displayData['courseDetail']   = $instituteCourses;
            $displayData['courseViewFlag'] = true;
            $displayData['instituteId']    = $listing_id;
            $displayData['responseAction'] ='Asked_Question_On_AllContent_PC_'.$pageType;
            $displayData['GA_Tap_On_What_Question'] = 'TYPE_QUESTION_ALLCONTENT_PC_'.strtoupper($pageType);
    ?>
     <div id="tags-layer" class="tags-layer"></div>
     <div class="an-layer an-layer-inner" id="an-layer">
          <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);?>
     </div>
<?php } ?>
<input type="hidden" name="selectedInstituteId" id="selectedInstituteId" value="<?php echo $selectedInstituteId;?>">
<input type="hidden" name="selectedCourseId" id="selectedCourseId" value="<?php echo $selectedCourseId;?>">
<input type="hidden" name="listingId" id="listingId" value="<?php echo $listing_id;?>";>
<input type="hidden" name="listingType" id="listingType" value="<?php echo $listing_type;?>";>
<input type="hidden" name="pageType" id="pageType" value="<?php echo $pageType;?>";>
<input type="hidden" name="sort_option" id="sort_option" value="<?php echo strtolower($selectedSortOption);?>">
<input type="hidden" name="totalContentCount" id="totalContentCount" value="<?php echo $totalElements;?>" >
<input type="hidden" name="contentType" id="contentType" value="<?php echo $contentType?>" >
<input type="hidden" name="stream" id="stream" value="<?php echo $selectedStreamId?>" >
<input type="hidden" name="selectedFilterRating" id="selectedFilterRating" value="<?php echo $selectedFilterRating?>" >
<input type="hidden" name="selectedTagId" id="selectedTagId" value="<?php echo $selectedTagId;?>">

<script type="text/javascript">
var instituteCoursesMapping = JSON.parse('<?php echo $filtersArray["instituteCoursesMapping"];?>');
var baseCourseMapping = JSON.parse('<?php echo $filtersArray["baseCourseMapping"];?>');
var selectedInstituteId = '<?php echo $selectedInstituteId;?>';
var selectedCourseId = '<?php echo $selectedCourseId;?>';


</script>
<?php
ob_end_flush();
?>
<script type="text/javascript">
        $j(document).ready(function() {
            <?php if($pageType == 'questions'){ ?>
                window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
                initializeWebsiteTour('cta','anaDesktopV2',contentMapping);
            <?php } ?>
            
            <?php if($showToastMsg && $validateuser !='false'){?>
                    setTimeout(function(){
                      handleAnAToastMsg("<?=$SRM_DATA['ToastMsg']?>",5000);
                    },3000);
            <?php } ?> 
            
        });
        if(flag == 0){
            <?php if($pageType == 'admission'){ ?>
                var GA_currentPage='<?=$GA_currentPage?>',
                GA_userLevel='<?=$GA_userLevel?>',
                admissionCourseId = '<?php echo $coursesData['mostPopularCourse'];?>';
                $j("#admissionDetails a").attr("target","_blank");
            <?php } ?>
            initializeAllContentPage('<?php echo $base_url;?>','<?php echo $pageType;?>');
        }
        else
        {
            $j(document).ready(function() {
                showHideDefaultOptions(selectedInstituteId,selectedCourseId);
                if(typeof selectedInstituteId != 'undefined' && selectedInstituteId != '')
                    resetFilters(selectedInstituteId,true);
            });
        }
        searchCompareCTAEventAttach();
        var GA_currentPage     = '<?=$GA_currentPage;?>';
        var ga_page_name       = '<?=$GA_currentPage;?>';
        var viewedResponseAction = '<?=$viewedResponseAction;?>';
        var viewedResponseTrackingKey = '<?=$viewedResponseTrackingKey;?>';
        var viewedResponseCourseId = '<?=$flagshipCourseId;?>';
        var ga_commonCTA_name  = '<?=$GA_commonCTA_Name;?>';
        var ga_user_level      = '<?=$GA_userLevel;?>';
        var listingId          = '<?=$listing_id;?>';
        var listingType        = '<?=$listing_type;?>';
        var listingName        = '<?=addslashes(stripslashes(htmlentities($instituteName)));?>';
        var allContentPageName = '<?=$pageType?>';
        var pageNumber = '<?=$pageNumber;?>';
        createAllContentViewedResponse();
        function LazyLoadInstituteDetailsCallback(){
          lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
            $LAB
          .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
          .wait(function(){
        allContentLazyloadCallback();
          });
        }
</script>
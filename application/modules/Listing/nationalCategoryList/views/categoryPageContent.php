<?php ob_start('compressHTML'); global $criteriaArray;   ?>
<?php if($isAjax) {
    $this->benchmark->mark('Ajax_Breadcrumb_Selected_Filters_start');
    $this->load->view('nationalCategoryList/breadcrumb');
    //$this->load->view('nationalCategoryList/shoshkeleBanner');
    ?>
        <div id="shoshkeleBannerDiv"></div>
    <?php
    $this->load->view('nationalCategoryList/headingAndSelectedFilters');
    $this->benchmark->mark('Ajax_Breadcrumb_Selected_Filters_end');
    ?>
    <!-- search body starts -->
    <section class="tpl-data">
        <div id="pageContentSearch" class="container pLR0">
            <?php
                $this->benchmark->mark('Ajax_Filters_start'); 
                $this->load->view('nationalCategoryList/filters');
                $this->benchmark->mark('Ajax_Filters_end');
            ?>
            <?php 
                $this->benchmark->mark('Ajax_Tuples_start');
                if($product == "AllCoursesPage"){
                     $this->load->view('nationalCategoryList/tupleListAllCourses');
                }else{
                     $this->load->view('nationalCategoryList/tupleList');
                }
                $this->benchmark->mark('Ajax_Tuples_end');
            ?>
            <?php $this->load->view('nationalCategoryList/pagination'); ?>

        </div>
    </section>    
    <script>
        publishBanners();
        initlazyLoad();

        jsonFiltersList = JSON.parse('<?php echo $jsonFiltersList;?>');
        jsonFieldAlias = JSON.parse('<?php echo json_encode($fieldAlias);?>');
        appliedFilters = '<?php echo json_encode($appliedFilters);?>';
        customAppliedFilters = '<?php echo json_encode($customAppliedFilters);?>';
        cityFromRequest = '<?php echo $cityFromRequest;?>';
        stateFromRequest = '<?php echo $stateFromRequest;?>';
        topInstitute = '<?php echo $topInstitute;?>';

        <?php if($product == "Category" && !empty($topInstitute)){ 
            $this->benchmark->mark('Ajax_Load_Shoshkele_start'); ?>
            //load top and right gutter banners
            var topPageId = 'CATEGORY';
            var topPageZone = 'HEADER';
            var rightPageId = 'CATEGORY';
            var rightPageZone = 'RIGHT_GUTTER';
            var topRightShikshaCriteria = '<?php echo json_encode($criteriaArray);?>';
            loadGutterBannersFromAjax();

            //load shoshkele
            topInstitute = '<?php echo $topInstitute;?>';
            loadShoskeleFromAjax();
            <?php
            $this->benchmark->mark('Ajax_Load_Shoshkele_end');
        }?>
    </script>
<?php
}else{
    $this->benchmark->mark('Common_Header_start');
 $this->load->view('nationalCategoryList/categoryPageHeader'); 
$this->benchmark->mark('Common_Header_end');
 ?>
 <div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
    <div class="msg-toast">
    <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
    <p id="toastMsg"></p>
    </div>
 </div>

   <a href="javascript:void(0);" class="scrollToTop"></a>

 <script>
isCompareEnable = true; 
</script>
    <body>
        <div class="srpOverlay"><div class="three-quarters-loader-regForm">Loading…</div></div>
        <div class="localitiy-over-layer" style="display:none"></div>
        <?php 
        $this->benchmark->mark('Locality_Filter_start');
        foreach ($filters['location']['city'] as $key => $city) {
            $cityId = $city['id'];
            $populateLocLayer = false;
            if(!empty($filters['location']['cityWiseLocality'][$cityId])) {
                $locIds = $filters['location']['cityWiseLocality'][$cityId];
                foreach ($appliedFilters['locality'] as $key => $appliedLocId) {
                    if(in_array($appliedLocId, $locIds)) {
                        $populateLocLayer = true;
                        break;
                    }
                }
                if($populateLocLayer) {
                    $data = array();
                    $data['localityFilterValues'] = $filters['location']['cityWiseLocality'][$cityId];
                    $data['appliedLocality'] = $appliedFilters['locality'];
                    $data['cityId'] = $cityId; ?>
                    <div id='localityLayerContDiv_<?=$cityId?>' class="localityLayerContDiv" style='display:none'><?php $this->load->view('nationalCategoryList/filters/defaultLocalities', $data); ?></div>
                <?php } else { ?>
                    <div id='localityLayerContDiv_<?=$cityId?>' class="localityLayerContDiv" style='display:none'></div>
                <?php }
            }
        } 
        $this->benchmark->mark('Locality_Filter_end'); ?>

        <div class="wrapper" id="search-container-wrapper">
                <?php
                if($totalInstituteCount || $totalCourseCount) {
                    if($product == "Category"){
                        $this->benchmark->mark('Table_start');
                        $this->load->view('nationalCategoryList/categoryPageSeoTable');
                        $this->benchmark->mark('Table_end');
                    }
                    $this->benchmark->mark('Breadcrumb_start');
                    $this->load->view('nationalCategoryList/breadcrumb');
                    $this->benchmark->mark('Breadcrumb_end');
                    ?>
                    <div id="shoshkeleBannerDiv"></div>
                    <?php
                    $this->benchmark->mark('Heading_Selected_Filters_start');
                    $this->load->view('nationalCategoryList/headingAndSelectedFilters');
                    $this->benchmark->mark('Heading_Selected_Filters_end');
                    ?>
                    <!-- search body starts -->
                    <section class="tpl-data">
                        <div id="pageContentSearch" class="container pLR0">
                            <?php 
                                $this->benchmark->mark('Filters_start');
                                $this->load->view('nationalCategoryList/filters');
                                $this->benchmark->mark('Filters_end');
                            ?>
                            <?php
                                $this->benchmark->mark('Tuples_start');
                                if($product == "AllCoursesPage"){
                                     $this->load->view('nationalCategoryList/tupleListAllCourses');
                                }else{
                                     $this->load->view('nationalCategoryList/tupleList');
                                }
                                $this->benchmark->mark('Tuples_end');
                             ?>
                            <?php 
                            $this->benchmark->mark('Pagination_start');
                            $this->load->view('nationalCategoryList/pagination'); 
                            $this->benchmark->mark('Pagination_end');
                            ?>
                        </div>
                        <?php 
                        if($product == 'Category'){
                            ?>
                            <a href="javascript:void(0);" class="shwDat-link" id="ctpTbl-btn">Show Data in Table</a>
                            <?php
                        }
                        ?>
                    </section>
                    <section id ='bottom-sec'>
                        <div class='new-rvw-sec'>
                        <?php if($product == "AllCoursesPage" && !empty($reviewWidget['html'])){
                            echo $reviewWidget['html'];
                        } ?>
                        </div>
                    </section>

                    <?php
                    if($product == 'Category' && !empty($categoryPageInterlinkgUrls)) {
                        $this->benchmark->mark('Interlinking_start');
                        $this->load->view('nationalCategoryList/interlinkingWidget'); 
                        $this->benchmark->mark('Interlinking_end');
                    }
                }
                else {
                    ?>
                    <div class="container no-result-cont">
                        <?php $this->load->view('nationalCategoryList/categoryZrp'); ?>
                    </div>
                    <?php 
                }
            ?>
            <!-- search body ends -->
        </div>
        <?php
        $this->benchmark->mark('Footer_start');
            $this->load->view('nationalCategoryList/categoryPageFooter');  
            $this->benchmark->mark('Footer_end');
}

$this->benchmark->mark('Footer_Remaining_start');
?>

<script>
    var jsonFiltersList = JSON.parse('<?php echo $jsonFiltersList;?>');
    var jsonFieldAlias = JSON.parse('<?php echo json_encode($fieldAlias);?>');
    var appliedFilters = '<?php echo json_encode($appliedFilters);?>';
    var customAppliedFilters = '<?php echo json_encode($customAppliedFilters);?>';
    var cityFromRequest = '<?php echo $cityFromRequest;?>';
    var stateFromRequest = '<?php echo $stateFromRequest;?>';
    var topInstitute = '<?php echo $topInstitute;?>';
    var showLocLayerOnLoad = '<?php echo $showLocLayerOnLoad;?>';
    var viewedResponseAction = 'Institute_Viewed';
    var viewedResponseTrackingKey = 2053;
    var viewedResponseCourseId = '<?=$flagshipCourseId;?>';
    <?php
        if(!empty($urlPrefix)){
            ?>
            var urlPrefix  = '<?php echo $urlPrefix;?>';
            <?php
        }
    ?>

    myCompareObj.setRemoveAllCallBack('addRemoveCompareSearchCallback');

    <?php if($product == "AllCoursesPage"){ ?>
    initializeScrollToTop();
    createAllCoursesViewedResponse();

     $j(document).ready(function() {
            <?php if($showToastMsg && $validateuser !='false'){?>
                setTimeout(function(){
                  handleAnAToastMsg("<?=$SRM_DATA['ToastMsg']?>",5000);
                },3000);

            <?php } ?> 

        });


    <?php }?>

</script>
<?php
$this->benchmark->mark('Footer_Remaining_end');

$this->benchmark->mark('Footer_Flush_start');
ob_end_flush();
$this->benchmark->mark('Footer_Flush_end');
?>

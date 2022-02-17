<?php ob_start('compress'); 
$requestUrl = clone $request;
$requestUrl->setData(array('naukrilearning'=>0));		
$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
$totalResults = $categoryPage->getTotalNumberOfInstitutes();
$currentPage = $requestUrl->getPageNumberForPagination();
$totalPages = ceil($totalResults/$mobile_website_pagination_count);
$lastPage = $totalResults%$mobile_website_pagination_count; //results in last page.
if($currentPage < $totalPages){
    $nextURL = $requestUrl->getURL($currentPage+1);
}
if($currentPage > 1){
    $prevURL = $requestUrl->getURL($currentPage-1);
}

$headerComponent = array(
        'canonicalURL' => $canonicalurl,
        'previousURL' => $prevURL,
        'nextURL' => $nextURL
);
?>
<?php $this->load->view('/mcommon5/header',$headerComponent);
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_CATEGORY_PAGE_FULLTIME_MBA',1);

global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();

global $filtersApplied;
global $filterDisplayString;

$this->load->view('appliedFiltersFullTimeMBA');
$instituteCount = 0;
if(!empty($institutes)) {
	$instituteCount = count($institutes);
}
?>

<script>
	if(typeof($categorypage) == 'undefined'){
		$categorypage = [];
	}
	var mobileInstituteCountOnPage = "<?=$instituteCount;?>";
</script>


<div id="popupBasic" style="display:none">      
<header class="recommen-head" style="border-radius:0.6em 0.6em 0 0;">
       <p style="width:210px;" class="flLt">Students who showed interest in this institute also looked at</p>
       <a href="#" class="flRt popup-close" onclick = "$('#popupBasic').hide();$('#popupBasicBack').hide();">&times;</a>
       <div class="clearfix"></div>
       </header>
                <div id="recomendation_layer_listing" style="margin-top:20px;margin-bottom:20px;"></div>
        </div>
<div id="popupBasicBack" data-enhance='false'>  
</div>
<div id="wrapper" data-role="page" class="of-hide"  style="min-height: 413px;padding-top: 40px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    
        <?php   $this->load->view('mobileCategoryHeader'); ?>
        
	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>        
        
    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    
	    
        <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
		<!----subheader--->
	       <?php $this->load->view('mobileCategorySubHeader');?>
		<!--end-subheader-->
	       
            <div data-enhance="false">
                <!--	Refine Options Header -->
                <?php if($filtersApplied)	{ ?>
                <section id="showSelectedFilters" class="filter-applied" onClick="$('#refineOverlayOpen').click();" style="cursor:pointer;">
                    <div class="filter-child">
                        <strong>Applied Filters : Tap to Edit</strong>
                        <p>
                                <?php echo $filterDisplayString; ?>
                        </p>
                        <div class="filter-arr"></div>
                    </div>
                </section>
                <?php } ?>     
<?php
if (getTempUserData('confirmation_message')){?>
<section class="top-msg-row">
        <div class="thnx-msg">
            <i class="icon-tick"></i>
                <p>
                <?php echo getTempUserData('confirmation_message'); ?>
                </p>
        </div>
	<div style="clear:both"></div>
</section>
<?php } ?>
<?php
   deleteTempUserData('confirmation_message');
   deleteTempUserData('confirmation_message_ins_page');
?>
            <?php
            //Check for No Results on Category page
            if(!$institutes){
                if($request->getPageNumberForPagination() > 1){
                    $urlRequest = clone $request;
                    $urlRequest->setData(array('pageNumber'=>1));
                    $url = $urlRequest->getURL();
                    header("location:".$url);
                }
            ?>
                    <nav class="clearfix fixed-wrap" id="no-result">
                        <p>Sorry, no results were found. <br/>
                        <a onclick="setCookie('scroll_page','yes') ;location.href='<?php echo SHIKSHA_HOME;?>'" href='javascript:void(0);' >Explore again</a>
                        <?php if($filtersApplied){ ?>
                         / <a href="javascript:void(0);" onClick="$('#refineOverlayOpen').click();">Modify Filters</a>
                        <?php }?>
                        .</p>
                    </nav>
            <?php
            }else{
                if( isset($tracking_keyid) ) { // Pass on the tracking key to the view ahead if it was set in the controller
                    $this->load->view('mobileCategoryShowInstitutes', array('tracking_keyid'=> $tracking_keyid));
                } else {
                    $this->load->view('mobileCategoryShowInstitutes');
                }
            }
            //End Check for No Results on Category page
            
            //Set the Cookie for current category page so that user can be redirected to this page directly next time
            setcookie('current_cat_page',urlencode($this->shiksha_site_current_url),time() + 2592000 ,'/',COOKIEDOMAIN);
            ?>
            <?php $this->load->view('/mcommon5/footerLinks'); ?>
            </div>
        </div>
</div>

<?php
//$categoryPageKeyString = $request->getPageKeyString();
if($categoryPageTypeFlag == CP_NEW_RNR_URL_TYPE && in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
	$categoryPageKeyString = $request->getPageKeyString();
	$reorderFilterLocationListURL = '/mobile_category5/CategoryMobile/getCategoryPageLocation/'.$categoryPageKeyString."/RNRURL";
} else {
	$categoryPageKeyString = $request->getPageKeyString();
	$reorderFilterLocationListURL = '/mobile_category5/CategoryMobile/getCategoryPageLocation/'. $categoryPageKeyString . "/0";
}
//$reorderFilterLocationListURL = '/mobile_category5/CategoryMobile/getCategoryPageLocation/'.$categoryPageKeyString;
echo "<script>var reorderFilterLocationListURL = '$reorderFilterLocationListURL';</script>";
?>
<div data-role="page" id="refineDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine'); ?>
</div>

<div data-role="page" id="courseDiv" data-enhance="false"><!-- dialog--> 
 <?php $this->load->view('refine_course'); ?>
</div>

<div data-role="page" id="feesDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_fees'); ?>
</div>

<div data-role="page" id="examDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_exam'); ?>
</div>

<div data-role="page" id="locationDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_location'); ?>
</div>

<div data-role="page" id="categoryLocationDiv" data-enhance="false"><!-- dialog-->
</div>
<input type="hidden" id="subcategoryId" value="<?php echo $request->getSubCategoryId(); ?>"/>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>
<script>
// Function called to bind the events to the Mobile College Review Widget 
    


$( document ).ready(function() {
     var urlString = "<?=$urlString?>";
     getFiltersOnLoad(urlString);
     getCategoryLocationsOnLoad('categoryLocationDiv',urlString);
     new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","CATEGORYPAGE_MOBILE").loadWidget();
});

</script>

<!-- Google Code for registration Conversion Page -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en_GB";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "O3WQCOaXRRCS3Lz2Aw";
var google_conversion_value = 0;
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=0&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->

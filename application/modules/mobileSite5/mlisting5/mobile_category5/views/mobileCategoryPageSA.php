<?php ob_start('compress'); 
$requestUrl = clone $request;
$requestUrl->setData(array('naukrilearning'=>0));		
$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
$headerComponent = array(
        'canonicalURL' => $canonicalurl
);
?>
<?php $this->load->view('/mcommon5/header',$headerComponent);
global $filters;
global $appliedFilters;
$filters = $categoryPage->getFilters();
$appliedFilters = $request->getAppliedFilters();
global $filtersApplied;
global $filterDisplayString;

$this->load->view('getAppliedFilters');
?>

<div id="wrapper" data-role="page"  class="of-hide">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');?>
    
        <?php   $this->load->view('mobileCategoryHeaderSA'); ?>

        <div data-role="content">
            <?php 
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
            ?>
            <div data-enhance="false">
            
                <!--	Refine Options Header -->
                <?php if($filtersApplied)	{ ?>
                <section id="showSelectedFilters" class="filter-applied" onClick="$('#refineOverlayOpen').click(); trackEventByGAMobile('HTML5_CATEGORY_PAGE_FILTER_GREY');" style="cursor:pointer;">
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
                        <nav class="clearfix fixed-wrap" id="no-result"><p>Sorry, no results were found. <br/>
                        <a onclick="setCookie('scroll_page','yes') ;location.href='<?php echo SHIKSHA_HOME;?>'" href='javascript:void(0);' >Explore again</a>
                        <?php if($filtersApplied){ ?>
                         / <a href="javascript:void(0);" onClick="$('#refineOverlayOpen').click();">Modify Filters</a>
                        <?php }?>
                        .</p></nav>
                <?php
                }else{
                    $this->load->view('mobileCategoryShowInstitutes');
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
$reorderFilterLocationListURL = '/mobile_category5/CategoryMobile/reorderFilterLocationList/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage()."/0";
echo "<script>var reorderFilterLocationListURL = '$reorderFilterLocationListURL';</script>";
?>
<div data-role="page" id="refineDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refineSA'); ?>
</div>

<div data-role="page" id="courseDiv" data-enhance="false"><!-- dialog--> 
 <?php $this->load->view('refine_course'); ?>
</div>

<div data-role="page" id="examDiv" data-enhance="false"><!-- dialog--> 
 <?php //$this->load->view('refine_exam'); ?>
</div>

<div data-role="page" id="locationDiv" data-enhance="false"><!-- dialog--> 
 <?php $this->load->view('refine_locationSA'); ?>
</div>
<div data-role="page" id="categoryAbroadLocationDiv" data-enhance="false"><!-- dialog-->
</div>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>
<script>
$( document ).ready(function() {
      var urlString = "<?=$urlString?>";
      getFiltersOnLoadForSA(urlString);
     getCategoryLocationsOnLoad('categoryAbroadLocationDiv',urlString);
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

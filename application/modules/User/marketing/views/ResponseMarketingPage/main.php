<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Full Time MBA Institutes</title>
<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
<link href="/public/css/<?php echo getCSSWithVersion("responseMarketingPage"); ?>" type="text/css" rel="stylesheet" />
<link href="/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="/public/css/<?php echo getCSSWithVersion("registration"); ?>" type="text/css" rel="stylesheet" />

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("category"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>" type="text/javascript"></script>
<script>
    <?php global $institutesWithoutUnified; global $COOKIEDOMAIN; ?>
    var institutesWithoutUnified = <?=json_encode($institutesWithoutUnified)?>;
	var COOKIEDOMAIN = '<?php echo $COOKIEDOMAIN; ?>';
	var studyAbroad = 0;
	var listings_with_localities = <?php echo $listings_with_localities; ?>;
	
	<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
		var isUserLoggedIn = true;
	<?php else: ?>
		var isUserLoggedIn = false;
	<?php endif; ?>
	var disableGlobalUnified = 1;
	
	var messageObj;
	messageObj = new DHTML_modalMessage();
    messageObj.setShadowDivVisible(false);
    messageObj.setHardCodeHeight(0);
	
	var theResponseMarketingPage = 1;
</script>
</head>

<body>
<div id="center-box">
<div id="mainWrapper">

	<div id="contentWrapper">
		<h1 class="logo-box"><a href="https://www.shiksha.com"><img src="/public/images/responseMarketingPage/logo.gif" /></a></h1>
		<div class="page-title">Full Time MBA Institutes</div>
		<h2 class="brochure-title">Download Free Brochures of colleges of your choice</h2>
		
        <div class="filter-section">
			<?php if(is_array($passedFilters) && count($passedFilters) > 0) { ?>
            <h4>Your Selection:</h4>
            <div class="selected-items">
				<?php
				foreach($passedFilters as $passedFilterKey => $passedFilterValues) {
					if(is_array($passedFilterValues) && count($passedFilterValues) > 0) {
				?>
						<p>
							<strong><?php echo html_escape(ucfirst($passedFilterKey)); ?>:</strong>
							<?php echo implode(" <span>|</span> ",array_slice($passedFilterValues,0,2)); ?>
							
							<span id="filter_<?php echo $passedFilterKey; ?>" style="color:#000; display:none">
								<?php echo "<span>|</span> ".implode(" <span>|</span> ",array_slice($passedFilterValues,2)); ?>
							</span>
							
							<?php if(count($passedFilterValues) > 2) { $moreItems = count($passedFilterValues) - 2; ?>
							<span id="filter_more_<?php echo $passedFilterKey; ?>" style="color:#000;">
								<span>[</span><a href="#" onclick="showMoreFilterValues('<?php echo $passedFilterKey; ?>'); return false;"><?php echo $moreItems; ?> more</a><span>]</span>
							</span>	
							<?php } ?>
						</p>
				<?php
					}
				}
				?>		
            </div>
            
			<div id="modifySelectionButton">
				<div class="clearFix"></div>
				<div class="open-refine" onclick="$('modifyFilter').style.display = ''; $('modifySelectionButton').style.display = 'none';" uniqueattr="ResponseMarketingPageModifySelection">Modify your selection</div>
			</div>
			
			<?php } ?>
			
			<div id="modifyFilter" style="<?php if(is_array($passedFilters) && count($passedFilters) > 0) { echo "display:none"; } ?>">
			<?php
			global $filters;
			global $appliedFilters;
			$filters = $categoryPage->getFilters();
			$appliedFilters = $request->getAppliedFilters();
			?>
			
            <div class="refine-options">
                <h5>Refine institutes by</h5>
            
                <div class="refine-box" id="refineDetailsBox">
                    <div class="refine-cols">
						<?php $this->load->view('marketing/ResponseMarketingPage/filterLeft');?>
                    </div>
                    
                    <div class="refine-cols">
						<?php $this->load->view('marketing/ResponseMarketingPage/filterMiddle');?>
                    </div>
                    
                    <div class="refine-last-cols" id="refineCols3">
						<?php $this->load->view('marketing/ResponseMarketingPage/filterRight');?>
                    </div>
                    
                    <div class="clearFix"></div>
                </div>
            
                <div class="refine-btn-cont">
                    <input type="button" class="refine-btn" value="Refine" uniqueattr="ResponseMarketingPageFilterApplyButton" onclick="applyFiltersOnResponseMarketingPage();" />&nbsp; <a href="#" onclick="clearFiltersOnResponseMarketingPage();return false;" uniqueattr="ResponseMarketingPageFilterClearButton">Clear all</a>
                </div>
            </div>
            <div class="clearFix"></div>
			<?php if(is_array($passedFilters) && count($passedFilters) > 0) { ?>
            <div class="close-refine" onclick="$('modifyFilter').style.display = 'none'; $('modifySelectionButton').style.display = ''; ">Close</div>
			<?php } ?>
			</div>
			<div class="clearFix"></div>
        </div>

		<div id="categoryPageListingsLoader" style="display:none">
			<img src="/public/images/loader.gif" align="absmiddle">&nbsp;&nbsp;Loading...
		</div>
		
        <div class="course-display-cont" id="responseMarketingListings">
			<?php $this->load->view('marketing/ResponseMarketingPage/snippets'); ?>
        </div>
		<div class="clearFix"></div>
		<div class="go-back"><span>&laquo;</span> <a href="<?php echo SHIKSHA_HOME; ?>" title="Go to shiksha.com homepage">Go to shiksha.com homepage</a></div>
	</div>
	<div class="page-footer">
		Trade Marks belong to the respective owners.<br />
		Copyright &copy; 2013 Info Edge India Ltd. All rights reserved.
	</div>
	<div class="clearFix"></div>
</div>
</div>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlinetooltip"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("multipleapply"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("customCityList"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("responseMarketingPage"); ?>" type="text/javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = $.noConflict();
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("userRegistration"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("processForm"); ?>" type="text/javascript"></script>

<script>
if(typeof($categorypage) == 'undefined'){
	$categorypage = [];
}
$categorypage.categoryId = "<?=$request->getCategoryId()?>";
$categorypage.subCategoryId = "<?=$request->getSubCategoryId()?>";
$categorypage.LDBCourseId = "<?=$request->getLDBCourseId()?>";
$categorypage.cityId = "<?=$request->getCityId()?>";
$categorypage.key  = "<?=$request->getPageKey()?>";
$categorypage.ajaxurl  = "<?=$url?>";
$categorypage.filterTrackingURL  = "<?=$filterTrackingURL?>";
$categorypage.currentUrl = "<?=$currentUrl?>";
$categorypage.NaukrilearningTrack = "<?=$request->isNaukrilearningPage()?"/nl":""?>";
$categorypage.localityId = "<?=$request->getLocalityId()?>";
localityArray = new Array();
studyAbroad = 0;
var STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";

function showListingDetailOverview(url,instituteId) {
	messageObj.setShadowDivVisible(false);
	messageObj.setFlagShowLoaderAjax(false);
	messageObj.setSource('/marketing/ResponseMarketingPage/listingDetail');
	messageObj.setCssClassMessageBox(false);
	messageObj.setSize(1000,600);
	messageObj.display({"url" : url,"instituteId" : instituteId});
}
</script>
<script type="text/javascript">
function loadScript(url, callback){
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
</script>
<script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  loadScript(gaJsHost + 'google-analytics.com/ga.js', function(){
    //initialization code
    try{
       pageTracker = _gat._getTracker("UA-4454182-1");
        pageTracker._setDomainName(".shiksha.com");
        pageTracker._initData();
        pageTracker._trackPageview();
        pageTracker._trackPageLoadTime();
    } catch(err) {}
  });
  function trackEventByGA(eventAction,eventLabel) {
	if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
	    pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
	}
	return true;
    }
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
var TRACKING_CUSTOM_VAR_MARKETING_FORM = "multiplemarketingpage";
if(typeof(setCustomizedVariableForTheWidget) == "function") {
if (window.addEventListener){
	window.addEventListener('click', setCustomizedVariableForTheWidget, false);
} else if (window.attachEvent){
	document.attachEvent('onclick', setCustomizedVariableForTheWidget);
}
}


$j(document).ready(function(){
	if (window.screen.width <= 800 && window.screen.height <= 600) {
    	$j('#center-box').addClass('width-800');
	}
	
	else if (window.screen.width <= 1024 && window.screen.height <= 768) {
    	$j('#center-box').addClass('width-1024');
	}
	
	else if (window.screen.width <= 1280 && window.screen.height <= 800) {
    	$j('#center-box').addClass('width-1200');
	}
	})

</script>
</body>
</html>

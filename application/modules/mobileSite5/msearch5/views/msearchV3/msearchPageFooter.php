<p class='clr'></p>
<?php 
	$this->load->view('/mcommon5/footerLinks'); 
?>
</div>
</div><!-- for closing content div -->
<div data-role="popup" data-transition="none" id="coachMarkPop" data-enhance="false"><!-- dialog--> 
</div>
</div><!-- for closing wrapper div -->

<div id="collegeReviewViewDetails" data-enhance="false" data-role="dialog" data-dialog="true" class="clearfix content-wrap">
	<?php //$this->load->view('mCompareInstitute5/collegeReviewLayer', array('fromPage'=>'search')); ?>
</div>

<div data-role="dialog" data-transition="none" id="searchFilters" data-enhance="false"><!-- dialog--> 
</div>

<?php $this->load->view('/mcommon5/footer'); ?>

<?php
  /*  $currentPageNum = $request->getCurrentPageNum();
    $request->setPageNumber($currentPageNum+1);
    $urlFetch = $request->getUrl();
    $urlArray = explode('pn=',$urlFetch);
    $paginationURLPrev = $urlArray[0];
    $urlArray = explode('&',$urlArray[1]);
    for($i=1;$i<(count($urlArray));$i++) {
        $paginationURLNext .= ($paginationURLNext=='')?$urlArray[$i]:'&'.$urlArray[$i];
    }*/
?>

<script type="text/javascript">
	$(document).ready(function(){
		if(window.location.hash.length > 0){
			var loc = window.location.href,
		    index = loc.indexOf('#');

			if (index > 0) {
			  window.location = loc.substring(0, index);
			}
		}
		bindEventsOnSearchResultPage();
	contentMapping = JSON.parse('<?php echo json_encode(Modules::run('common/WebsiteTour/getContentMapping','cta','mobile')); ?>');
	});
	var currentPageNum = '<?php echo $currentPage; ?>';
	<?php 
		if($product != "MAllCoursesPage") {
			if($totalInstituteCount == $institutes['instituteCountInCurrentPage'] || $totalInstituteCount == ((($currentPage - 1) * $pageLimit) + $institutes['instituteCountInCurrentPage'])) { ?>
				var doNotMakeAnotherCall = true;
				$('#searchPagination').show();
			<?php } else { ?>
				var doNotMakeAnotherCall = false;
			<?php }
		}else{
			if($totalCourseCount == $courseCountInCurrentPage || $totalCourseCount == ((($currentPage - 1) * $pageLimit) + $courseCountInCurrentPage)) { ?>
				var doNotMakeAnotherCall = true;
				$('#searchPagination').show();
			<?php } else { ?>
				var doNotMakeAnotherCall = false;
			<?php }
		}
	?>
	// var paginationURL = '<?php echo trim($paginationURLPrev.$paginationURLNext, "&"); ?>';
	var pageNumber = parseInt(currentPageNum) + 1;
	var searchProduct = '<?php echo $product; ?>';
	var filterBucketName		= eval('(' + '<?php echo $filterBucketName;?>' + ')');
	// var jsonFiltersList		= eval('(' + '<?php echo $jsonFiltersList;?>' + ')');
	var filtersPossible		= eval('(' + '<?php echo $filtersPossible;?>' + ')');
	var fieldAlias = eval('(' + '<?php echo json_encode($fieldAlias);?>' + ')');
	// var countReviewsTotal = '<?php echo json_encode($courseReviews);?>';
	//var filters = eval("(" + "<?php echo json_encode($filters); ?>" + ")");
	var filters = JSON.parse(JSON.stringify(<?php echo json_encode($filters);?>));
	//var selectedFilters = eval('(' + '<?php echo json_encode($selectedFilters); ?>' + ')');
	var selectedFilters = JSON.parse(JSON.stringify(<?php echo json_encode($selectedFilters);?>));
	var urlPassingAttribute		= eval('(' + '<?php echo $urlPassingAttribute;?>' + ')');
	var originalUrlAttribute = $.extend({}, urlPassingAttribute);
	<?php if($product == "MsearchV3"){
		?>
		var searchFilterData		= eval('(' + '<?php echo $searchFilterData;?>' + ')');	
		<?php
	}
	if($product == 'McategoryList' || $product == "MsearchV3"){
		?>
		var defaultFiltersApplied = eval('(' + '<?php echo json_encode($defaultFiltersApplied);?>' + ')');
		<?php
	}
	if($product == 'McategoryList')
	{ ?>
		var showLocLayerOnLoad = '<?php echo $showLocLayerOnLoad;?>';
	
	<?php }

	    if(!empty($urlPrefix)){
	        ?>
	        var urlPrefix  = '<?php echo $urlPrefix;?>';
	        <?php
	    }
	?>
	
</script>
<?php 
	
$finalUpdatedCount = $totalInstituteCount;
if(empty($finalUpdatedCount)){
	$finalUpdatedCount = $totalCourseCount;
}
if(empty($finalUpdatedCount)){
	$finalUpdatedCount = 0;
}
if(DO_SEARCHPAGE_TRACKING) {
	if(!empty($trackingSearchId)){
		echo "<input type='hidden' id='trackingSearchId' value={$trackingSearchId}>";
		if(!empty($trackingFilterId)){
		    echo "<input type='hidden' id='trackingFilterId' value={$trackingFilterId}>";
		}
		if(!empty($updateResultCountForTracking)){
			?>
			<script type="text/javascript">
				// var img = new Image();
				var url = SEARCH_PAGE_URL_PREFIX+"/trackSearchQuery?ts=<?php echo urlencode(base64_encode($trackingSearchId)); ?>&count=<?php echo urlencode(base64_encode($finalUpdatedCount)); ?>";
				<?php 
					if(!empty($singleStreamClosedSearch)){
						?>
						url += "&pageType=<?php echo urlencode(base64_encode('close')); ?>";
						url += "&entityType=<?php echo urlencode(base64_encode('stream')); ?>";
						url += "&entityId=<?php echo urlencode(base64_encode($singleStreamClosedSearchStream)); ?>";
						<?php
					}
					if(!empty($relevantResults)){
						if($relevantResults != 'relax'){
							?>
							url += "&newKeyword=<?php echo urlencode(base64_encode($searchKeyword)); ?>"; 
							<?php
						}
						?>
						url += "&criteriaApplied=<?php echo urlencode(base64_encode($relevantResults)); ?>";
						<?php
					}
				?>
				makeCustomAjaxCall(url,{});
			</script>
			<?php
		}
	}
}
?>

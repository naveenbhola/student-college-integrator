<p class='clr'></p>
<?php $this->load->view('/mcommon5/footerLinks'); ?>
</div>
</div><!-- for closing content div -->
<div data-role="popup" data-transition="none" id="coachMarkPop" data-enhance="false"><!-- dialog--> 
</div>
</div><!-- for closing wrapper div -->

<div id="collegeReviewViewDetails" data-enhance="false" data-role="dialog" data-dialog="true" class="clearfix content-wrap">
	<?php $this->load->view('mCompareInstitute5/collegeReviewLayer', array('fromPage'=>'search')); ?>
</div>

<div data-role="dialog" data-transition="none" id="searchFilters" data-enhance="false"><!-- dialog--> 
</div>

<?php $this->load->view('/mcommon5/footer'); ?>

<?php
    $currentPageNum = $request->getCurrentPageNum();
    // $request->setPageNumber($currentPageNum+1);
    // $urlFetch = $request->getUrl();
    // $urlArray = explode('pn=',$urlFetch);
    // $paginationURLPrev = $urlArray[0];
    // $urlArray = explode('&',$urlArray[1]);
    // for($i=1;$i<(count($urlArray));$i++) {
    //     $paginationURLNext .= ($paginationURLNext=='')?$urlArray[$i]:'&'.$urlArray[$i];
    // }
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
	});

	var countReviewsTotal = '<?php echo json_encode($courseReviews);?>';
	var filters = eval('(' + '<?=$filters;?>' + ')');
	var selectedFilters = eval('(' + '<?php echo json_encode(array_diff_key($selectedFilters,array("subCategory"=>'',"catId"=>''))); ?>' + ')');
	var urlPassingAttribute		= eval('(' + '<?php echo $urlPassingAttribute;?>' + ')');
	var originalUrlAttribute = $.extend({}, urlPassingAttribute);
	var filterBucketName		= eval('(' + '<?php echo $filterBucketName;?>' + ')');
	var currentPageNum = '<?php echo $currentPageNum; ?>';
	// var paginationURL = '<?php echo $paginationURLPrev.$paginationURLNext; ?>';
	var pageNumber = parseInt(currentPageNum) + 1;
	<?php if($totalInstituteCount == $institutes['instituteCountInCurrentPage'] || $totalInstituteCount == ((($currentPageNum - 1) * SEARCH_PAGE_LIMIT_MOBILE) + $institutes['instituteCountInCurrentPage'])) { ?>
		var doNotMakeAnotherCall = true;
		$('#searchPagination').show();
	<?php } else { ?>
		var doNotMakeAnotherCall = false;
	<?php } ?>
	var searchFilterData		= eval('(' + '<?php echo $searchFilterData;?>' + ')');
</script>

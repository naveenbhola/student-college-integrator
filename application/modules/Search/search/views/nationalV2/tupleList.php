<div class="content col-lg-9 pR0">
    <?php 
    $tuplenumber = 1;
    foreach ($institutes['instituteData'] as $instituteId => $instituteObj) {
    	$courseObj = reset($instituteObj->getCourses());
		$data = array();
    	$data['course']    = $courseObj;
		$data['institute'] = $instituteObj;
		//$data['subCatId']  = $request->getSubCategoryId();
		$data['tuplenumber'] = $tuplenumber;
		$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
		
		// $data['courseReviews'] = $courseReviews;
		// $data['courseQuestions'] = $courseQuestions;
		// $data['shortlistedCoursesOfUser'] = $shortlistedCoursesOfUser;
		// $data['categoryRepository'] = $categoryRepository;

    	if(is_object($instituteObj) && is_object($courseObj)) {
	    	// error_log('institute id for the tuple: '.$instituteId);
	    	// error_log('course id for the tuple: '.$courseObj->getId());
	    	echo "<input type='hidden' id='tuplenum_".$instituteId."' value=".$tuplenumber." />";
		 	$this->load->view('common/gridTuple/tupleContent', $data);
		 	$tuplenumber++;
		} 
		else {
	      	error_log('CORRUPT institute id for the tuple: '.$instituteId);
	    }
	}
	if(DO_SEARCHPAGE_TRACKING) {
		$trackingSearchId = $request->getTrackingSearchId();
		$trackingFilterId = $request->getTrackingFilterId();
		if(!empty($trackingSearchId)){
			echo "<input type='hidden' id='trackingSearchId' value={$trackingSearchId}>";
		}
		if(!empty($trackingFilterId)){
			echo "<input type='hidden' id='trackingFilterId' value={$trackingFilterId}>";
		}
	}
	?>

	<?php 
	$trackingSearchId = $request->getTrackingSearchId();
	$relevantResults  = $request->getRelevantResults();
	$newKeyword = '';
	if(!empty($relevantResults) && $relevantResults != 'relax'){
	    $newKeyword       = $request->getSearchKeyword();
	}
	// if(empty($relevantResults)){
	// 	$relevantResults = '';
	// }
	// if(empty($oldKeyword)){
	// 	$oldKeyword = '';
	// }
	if(DO_SEARCHPAGE_TRACKING && !empty($trackingSearchId) && $updateResultCountForTracking){
		?>
		<script type="text/javascript">
			var img = new Image();
			var url = SEARCH_PAGE_URL_PREFIX+"/trackSearchQuery?ts="+<?php echo $trackingSearchId; ?>+'&count='+<?php echo $totalInstituteCount; ?>;
			<?php 
			if(!empty($newKeyword)){
				?>
				url += "&newKeyword=<?php echo htmlentities($newKeyword); ?>";
				<?php
			}
			if(!empty($relevantResults)){
				?>
				url += "&criteriaApplied=<?php echo $relevantResults; ?>";
				<?php
			}
			?>
			img.src = url;
		</script>
		<?php
	}
	?>
  
  
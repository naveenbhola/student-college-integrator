<?php
$searchType = $search_type; //controller variable
$sponsoredInstitutes = $sponsored_institutes; //controller variable
$normalInstitutes = $normal_institutes; //controller variable
$sponsoredInstituteIds = $sponsored_institute_ids; //controller variable
$sponsoredResultCount = count($sponsoredInstitutes);

$normalResultCount = count($normalInstitutes);
$totalResultCount = $sponsoredResultCount +  $normalResultCount;
/*Finding Sponsored slots in the result list, so that every sponsored slot has equal distance b/w them */
$sponsoredSlots = array();
if($sponsoredResultCount > 0){
	$sponsoredSlots[] = 1;
	if($sponsoredResultCount - 1 > 0){
		if($sponsoredResultCount > 2){
			$steps = floor($totalResultCount / ($sponsoredResultCount - 1));
		} else {
			$steps = floor($totalResultCount / $sponsoredResultCount);
		}
		
		for($init = 0; $init < $sponsoredResultCount - 1; $init++){
			$lastEntry = $sponsoredSlots[count($sponsoredSlots) - 1];
			$newEntry = $lastEntry + $steps;
			if($newEntry > $totalResultCount){
				$sponsoredSlots[] = $totalResultCount;	
			} else {
				$sponsoredSlots[] = $newEntry;	
			}
		}
	}
}
?>
<div class="instituteLists">
	<?php
	if($solr_institute_data['numfound_institute_groups'] <= 0){
		if($search_type == "institute"){
			/*
		?>
			<h5 class="search-result-title">No institute or courses found for <span>&ldquo;<?php echo $solr_institute_data['raw_keyword'];?>&rdquo;</span></h5>
		<?php
			*/
		}
	} else {
		if($search_type == "content"){
			/*
			?>
			<h5 class="search-result-title"><?php echo getPlural($solr_institute_data['total_institute_groups'], 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span>&ldquo;<?php echo $solr_institute_data['raw_keyword'];?>&rdquo;</span></h5>
			<div class="spacer10" style="border-top:1px solid #EEE;"></div>
			<div class="spacer10"></div>
		<?php
			*/
		}
		?>
		<ul>
			<?php
			$normalResultKeys = array_keys($normalInstitutes);
			$sponsoredResultKeys = array_keys($sponsoredInstitutes);
			$originalSponsoredKeys = $sponsoredResultKeys;
			
			$currentPagestart 					= $solr_institute_data['start'];
			$resultPerPage 						= $general['rows_count']['institute_rows'];
			$totalResultForThisSearch 			= $solr_institute_data['total_institute_groups'];
			$pageNo  							= floor($currentPagestart / $resultPerPage)+1;
			$totalResultOnCurrentPage 			= count(array_keys($normalInstitutes));
			$totalNormalResultsOnCurrentPage 	= count(array_keys($normalInstitutes));
			$totalSponsoredResultsOnCurrentPage = count(array_keys($sponsoredInstitutes));
			$nextPageStart = $currentPagestart + $totalNormalResultsOnCurrentPage;
			for($displayRowcount = 1; $displayRowcount <= $totalResultCount; $displayRowcount++){
				if(in_array($displayRowcount, $sponsoredSlots)){
					$instituteId = array_shift($sponsoredResultKeys);
					$isSponsored = true;
					$institute = $sponsoredInstitutes[$instituteId];
				} else {
					$instituteId = array_shift($normalResultKeys);
					$isSponsored = false;
					$institute = $normalInstitutes[$instituteId];
				}
				$showMoreSimilarCourses = true;
				$viewData = array(
								'count' 			 => $displayRowcount,
								'recommendationPage' => false,
								'sponsored'			 => $isSponsored,
								'institute'			 => $institute,
								'showSimilarCourses' => $showMoreSimilarCourses,
								'hideCompareBlock'   => true,
								'searchType'		 => $searchType,
								'requestObject'		 => $requestObject,
								'pageNo'			 => $pageNo,
								'rowNo'              => $displayRowcount
								);
				
				$this->load->view('search/search_institute_snippet', $viewData);
			}
			?>
		</ul>
		<div class="clearFix"></div>
		<ul id="institute_more_search_results_<?php echo $nextPageStart;?>" style="display:none;"></ul>
		<div class="clearFix spacer20"></div>
		<?php
		//Result left are total results for this search - nextpage start - sponsored institutes on this page
		$resultLeft = ( $totalResultForThisSearch - $totalSponsoredResultsOnCurrentPage ) - $nextPageStart;
		if($resultLeft >= $resultPerPage){
			$resultLeft = $resultPerPage;
		}
		
		if(($totalNormalResultsOnCurrentPage + $totalSponsoredResultsOnCurrentPage) > 0 && $resultLeft > 0 && $totalResultForThisSearch > $nextPageStart){
			if($search_type == "institute"){
			?>
				<div class="btn-box" id="institute-pagination-block">
					<div id="pagination-loder" style="float:left;padding-top:2px;display:none;">
						<img src='/public/images/loader_hpg.gif'></img>
					</div>
					<input type="button" onclick="displayMoreInstituteResults('<?php echo urlencode($urlparams['keyword']);?>', '<?php echo $nextPageStart;?>', '<?php echo $resultPerPage;?>', 'institute_more_search_results_<?php echo $nextPageStart;?>','','<?php echo $totalResultCount;?>','<?php echo $uid;?>');" class="see-more-res" value="See <?php echo $resultLeft;?> more <?php echo getPlural($resultLeft, 'result', false)?>" />
				</div>
			<?php
			} else {
				?>
				<div class="btn-box" id="content-pagination-block">
					<input type="button" onclick="postFormWithSearchType('institute');" class="see-more-res" value="See more results"/>
				</div>
				<?php
			}
		}
		?>
		<div class="clearFix spacer10"></div>
		<div class="clearFix spacer10"></div>
	<?php
	}
	?>
</div>

<script type="text/javascript">
<?php
foreach($originalSponsoredKeys as $val){
?>
	//variable defined in search_page_hidden.php
	searchSponsoredIds.push('<?php echo $val;?>');
<?php
}
?>
function logSearchQuery(){
	<?php
	if(TRACK_SEARCH_RESULTS){
		$instituteTypeResults = array();
		if(isset($search_listing_ids['institute_ids'])){
			$instituteTypeResults['institute'] = $search_listing_ids['institute_ids'];
		}
		
		if(isset($search_listing_ids['course_ids'])){
			$instituteTypeResults['course'] = $search_listing_ids['course_ids'];
		}
		
		$contentTypeResults = array();
		if(isset($search_listing_ids['question_ids'])){
			$contentTypeResults['question'] = $search_listing_ids['question_ids'];
		}
		
		if(isset($search_listing_ids['article_ids'])){
			$contentTypeResults['article'] = $search_listing_ids['article_ids'];
		}
		
		if(isset($search_listing_ids['discussion_ids'])){
			$contentTypeResults['discussion'] = $search_listing_ids['discussion_ids'];
		}
		
		$articleCount = 0;
		if(isset($solr_content_data['numfound_article'])){
			$articleCount = $solr_content_data['numfound_article'];
		}
		$questionCount = 0;
		if(isset($solr_content_data['numfound_question'])){
			$questionCount = $solr_content_data['numfound_question'];
		}
		$courseCount = 0;
		if(isset($solr_institute_data['numfound_course_documents'])){
			$courseCount = $solr_institute_data['numfound_course_documents'];
		}
		$instituteCount = 0;
		if(isset($solr_institute_data['total_institute_groups'])){
			$instituteCount = $solr_institute_data['total_institute_groups'];
		}
		$discussionCount = 0;
		if(isset($solr_content_data['numfound_discussion'])){
			$discussionCount = $solr_content_data['numfound_discussion'];
		}
		$questionCount = $questionCount + $discussionCount;
		?>
		var params = {};
		params['institute_count'] 			= "<?php echo $instituteCount; ?>";
		params['course_count'] 				= "<?php echo $courseCount; ?>";
		params['question_count'] 			= "<?php echo $questionCount; ?>";
		params['article_count'] 			= "<?php echo $articleCount; ?>";
		params['institute_type_result_ids'] = encodeURIComponent('<?php echo serialize($instituteTypeResults); ?>');
		params['content_type_result_ids']  	= encodeURIComponent('<?php echo serialize($contentTypeResults) ;?>');
		params['search_type'] 				= "<?php echo $searchType;?>";
		params['result_step'] 				= "<?php echo $solr_institute_data['result_step'];?>";
		params['initial_qer'] 				= encodeURIComponent('<?php echo $solr_institute_data['initial_qer_query'];?>');
		params['final_qer'] 				= encodeURIComponent('<?php echo $solr_institute_data['final_qer_query'];?>');
		params['page_id'] 					= 1;
		initiateSearchTracking(params);
	<?php
	}
	?>
}

$searchPage.instituteWithMultipleCourseLocations = <?=json_encode($GLOBALS['instituteWithMultipleCourseLocations']);?>;
$searchPage.studyAbroadIds = <?=json_encode($GLOBALS['studyAbroadIds']);?>;
localityArray = <?=json_encode($GLOBALS['localityArray'])?>;
for(var key in localityArray){
	custom_localities[key] = localityArray[key];
}
/*
$j.each(localityArray,function(index,element){
	custom_localities[index] = element;
});
*/
</script>
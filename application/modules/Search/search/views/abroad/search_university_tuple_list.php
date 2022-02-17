<ul class="tuple-cont" id="university_result_container">
	<?php
		
		$keyword = $this->input->get('keyword');
		if(!isset($keyword) || $keyword==''){
			$keyword = $this->input->post('keyword');
		}
		$keyword  = urlencode($keyword);

		$totalUniversityResultCount 			= $university_count;
		$universityResultsStartOffset 			= $university_result_offset;//This should come from SOLR data
		$totalUniversityResultsOnCurrentPage 	= count($universities);
		$nextPageStart 							= $universityResultsStartOffset + $totalUniversityResultsOnCurrentPage;
		$tuplePostion = $university_result_offset+1;
		if($totalUniversityResultCount > 0) {
            for($count=0; $count < $totalUniversityResultsOnCurrentPage; $count++) {
					$universityData['university'] = $universities[$count];
					$universityData['tuplePostion'] = $tuplePostion++;
					$this->load->view('abroad/search_university_tuple', $universityData);
			}
        }
        //Result left are total results for this search - nextpage start - sponsored institutes on this page
		$resultLeft = $totalUniversityResultCount - $nextPageStart;
		if($resultLeft >= $totalUniversityResultsOnCurrentPage) {
			$resultLeft = $totalUniversityResultsOnCurrentPage;
		}
        ?>
</ul>
<?php
if($totalUniversityResultsOnCurrentPage > 0 && $resultLeft > 0 && $totalUniversityResultCount > $nextPageStart) {
?>
<div id="uni_pagination_results">
    <div class="load-more clearwidth" style="text-align:center;" id="universities_loadmore_cont">
            <a href="javascript:void(0);" onclick="loadMoreTracking(<?=$nextPageStart?>,'university');loadUniversitySearchResults('<?php echo $keyword;?>', <?php echo $nextPageStart;?>);" class="load-more-btn">Load <?php echo $resultLeft;?> more Universities</a>
			<span id='uni-pagination-loder' style='float:left;padding-top:2px;display:none;width:100%;'><img src='/public/images/loader_hpg.gif'></img></span>
    </div>
	
</div>
<?php
}




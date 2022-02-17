<ul class="tuple-cont" id="course_result_container">
	<?php
		$keyword = $this->input->get('keyword');
		if(!isset($keyword) ||  $keyword==''){
			$keyword = $this->input->post('keyword');
		}
		$keyword  = urlencode($keyword);
                $totalCourseResultGroupCount = $sa_course_group_count;
		$totalCourseResultCount 			= $sa_course_count;
		$courseResultsStartOffset 			= $course_result_offset;//This should come from SOLR data
		$totalCourseResultsOnCurrentPage 	= count($courseList);
		$nextPageStart 							= $courseResultsStartOffset + $totalCourseResultsOnCurrentPage;
		$tuplePostion = $course_result_offset+1;
		if($totalCourseResultCount > 0) {
            for($count=0; $count < $totalCourseResultsOnCurrentPage; $count++) {
				$courseData['course'] = $courseList[$count][0];
				$courseData['tuplePostion'] = $tuplePostion++;
				$fees = $courseData['course']['sa_course_fees_value']+$courseData['course']['sa_course_room_board']+$courseData['course']['sa_course_insurance']+$courseData['course']['sa_course_transportation']+$courseData['course']['sa_course_custom_fees'];
				$fees = $abroadListingCommonLib->convertCurrency($courseData['course']['sa_course_fees_currency'], 1, $fees);
				$courseData['fees'] = $abroadListingCommonLib->getIndianDisplableAmount($fees, 1);
				$courseData['count'] = count($courseList[$count]) - 1;
				$courseData['publicclass'] = $courseData['course']['sa_course_university_type'] === "1"? '&#10004':'&times';
				$courseData['scholarshipclass'] = $courseData['course']['sa_course_scholarship'] === "1" ? '&#10004':'&times';
				$courseData['accomodationclass'] = $courseData['course']['sa_course_university_accomodation'] == "1" ? '&#10004':'&times';
				$courseData['similarCourses'] = array(); 
				for($iter=1; $iter < count($courseList[$count]); $iter++){
						$subCourseData['course'] = $courseList[$count][$iter];
						$subCourseData['publicclass'] = $subCourseData['course']['sa_course_university_type'] == 1? '&#10004':'&times';
						$subCourseData['scholarshipclass'] = $subCourseData['course']['sa_course_scholarship'] == 1 ? '&#10004':'&times';
						$subCourseData['accomodationclass'] = $subCourseData['course']['sa_course_university_accomodation'] == 1 ? '&#10004':'&times';
						$fees = $subCourseData['course']['sa_course_fees_value']+$subCourseData['course']['sa_course_room_board']+$subCourseData['course']['sa_course_insurance']+$subCourseData['course']['sa_course_transportation']+$subCourseData['course']['sa_course_custom_fees'];
						$fees = $abroadListingCommonLib->convertCurrency($subCourseData['course']['sa_course_fees_currency'], 1, $fees);
						$subCourseData['subfees'] = $abroadListingCommonLib->getIndianDisplableAmount($fees, 1);
						$courseData['similarCourses'][] = $subCourseData;  
						
				}
				$this->load->view('abroad/search_course_tuple', $courseData);
			}
        }
        //Result left are total results for this search - nextpage start - sponsored institutes on this page
		$resultLeft = $totalCourseResultGroupCount - $nextPageStart;
		if($resultLeft >= $totalCourseResultsOnCurrentPage) {
			$resultLeft = $totalCourseResultsOnCurrentPage;
		}
	?>
</ul>
<?php
	if($totalCourseResultsOnCurrentPage > 0 && $resultLeft > 0 && $totalCourseResultGroupCount > $nextPageStart) { 
?>
<div id="uni_pagination_results">
    <div class="load-more clearwidth" style="text-align:center;" id="courses_loadmore_cont">
            <a href="javascript:void(0);" onclick="loadMoreTracking(<?=$nextPageStart?>,'course');loadCourseSearchResults('<?php echo $keyword;?>', <?php echo $nextPageStart;?>);" class="load-more-btn">Load <?php echo $resultLeft;?> more Courses</a>
			<span id='course-pagination-loder' style='float:left;padding-top:2px;display:none;width:100%;'><img src='/public/images/loader_hpg.gif'></img></span>
    </div>
	
</div>
<?php
}
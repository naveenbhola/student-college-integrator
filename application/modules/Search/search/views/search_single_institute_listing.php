<?php
$searchType = $search_type; //controller variable
$sponsoredInstitutes = $sponsored_institutes;
$normalInstitutes = $normal_institutes;
$sponsoredInstituteIds = $sponsored_institute_ids;

$allCourseDocuments = array();
foreach($normalInstitutes as $categoryId => $courseDocumentList){
	foreach($courseDocumentList as $course){
		if(!in_array($course->getId(), $allCourseDocuments)){
			array_push($allCourseDocuments, $course);
		}
	}
}

	
function getFlagShipCourse($normalInstitutes) {
	$courseOrderList = array();
	$order = 1000000000;
	$document = null;
	foreach($normalInstitutes as $categoryId => $courseDocuments){
		foreach($courseDocuments as $courseId => $courseDocument){
			if($order > $courseDocument->getOrder()){
				$order = $courseDocument->getOrder();
				$document = $courseDocument;
			}
		}
	}
	$returnDoc = array();
	if(!empty($document)){
		$returnDoc[$document->getId()] = $document;
	}
	return $returnDoc;
}

function getFirstCourseDocument($normalInstitutes){
	$document = null;
	$breakFlag = false;
	foreach($normalInstitutes as $categoryId => $courseDocuments){
		if(!$breakFlag){
			foreach($courseDocuments as $courseId => $courseDocument){
				$document = $courseDocument;
				$breakFlag = true;
				break;
			}	
		} else {
			break;
		}
	}
	$returnDoc = array();
	if(!empty($document)){
		$returnDoc[$document->getId()] = $document;
	}
	return $returnDoc;
}

if($solr_institute_data['single_result_type'] == "institute"){
	$flagShipCourseDocumet = getFlagShipCourse($normalInstitutes);
	if(empty($flagShipCourseDocumet)){
		$flagShipCourseDocumet = getFirstCourseDocument($normalInstitutes);
	}
} else {
	$flagShipCourseDocumet = getFirstCourseDocument($normalInstitutes);
}

$courseKey = array_keys($flagShipCourseDocumet);
$displayCourseId =  $courseKey[0];
$viewData = array(
	'count' 			 => 1,
	'recommendationPage' => false,
	'sponsored'			 => $isSponsored,
	'institute'			 => $flagShipCourseDocumet,
	'showSimilarCourses' => false,
	'hideCompareBlock'   => true,
	'singleResultCase'   => true,
	'allCourseDocuments' => $allCourseDocuments,
	'pageNo' => 1,
	 'rowNo' => 1
);
?>

<div>
	<div class="instituteLists">
		<ul>
			<?php $this->load->view('search/search_institute_snippet', $viewData); ?>
		</ul>
	</div>
	
	<div class="course-detail-section">
			<?php
			$count = 1;
			$result_type = "normal";
			if($isSponsored){
				$result_type = "sponsored";
			}
			foreach($normalInstitutes as $categoryId => $courseDocumentList){
				if(!empty($courseDocumentList)){
					$courseKeys = array_keys($courseDocumentList);
					$courseDoc = $courseDocumentList[$courseKeys[0]];
					$instituteId = $courseDoc->getInstitute()->getId();
					$courseContainerOpen = false;
					if($count == 1){
						$courseContainerOpen = true;	
					}
					
					$courseKeys = array_keys($courseDocumentList);
					$updatedCourseDocumentList = array();
					foreach($courseDocumentList as $key => $courseDoc){
						if($courseDoc->getId() != $displayCourseId){
							$updatedCourseDocumentList[$key] = $courseDoc;
						}
					}
					$courseDocumentList = $updatedCourseDocumentList;
					$viewData = array(
									'documents'   			=> $courseDocumentList,
									'categoryId' 			=> $categoryId,
									'headOfficeDetails'	 	=> true,
									'courseContainerOpen'	=> $courseContainerOpen,
									'instituteId'			=> $instituteId,
									'hideCompareBlock'   	=> true,
									'headingType'			=> 'category',
									'rowCount'				=> 1,
									'result_type'			=> $result_type,
									);
					$this->load->view('search/search_more_courses_snippet', $viewData);
					$count++;
				}
			}
			?>
	</div>
</div>

<script type="text/javascript">

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
		?>
		var search_unique_insert_id = -1;
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

<?php
$normalInstitutes = $normal_institutes;
$normalResultCount = count($normalInstitutes);
$totalResultCount = $normalResultCount;

$normalResultKeys = array_keys($normalInstitutes);
$currentPagestart = $solr_institute_data['start'];
$resultPerPage = $general['rows_count']['institute_rows'];

$pageNo  = floor($currentPagestart / $resultPerPage)+1;

$nextPageStart = $currentPagestart + $resultPerPage;
$totalResultForThisSearch = $solr_institute_data['total_institute_groups'];
$totalResultOnCurrentPage = count(array_keys($normalInstitutes));

for($displayRowcount = 1; $displayRowcount <= $totalResultCount; $displayRowcount++){
	$instituteId = array_shift($normalResultKeys);
	$isSponsored = false;
	$institute = $normalInstitutes[$instituteId];
	$showMoreSimilarCourses = true;
	$rowNo = $previousRowCount + $displayRowcount;
	$viewData = array(
					'count' 			 => $displayRowcount,
					'recommendationPage' => false,
					'sponsored'			 => $isSponsored,
					'institute'			 => $institute,
					'showSimilarCourses' => $showMoreSimilarCourses,
					'hideCompareBlock'   => true,
					'pagination'		 => $pagination,
					'pageNo'			 => $pageNo,
					'rowNo'              => $rowNo
					);
	$this->load->view('search/search_institute_snippet', $viewData);
	
	global $partial_localityArray;
	global $partial_studyAbroadIds;
	global $partial_instituteWithMultipleCourseLocations;
	$courseKeys = array();
	$courseKeys = array_keys($institute);
	$courseDocuments = array();
	$totalMultiLocationCourses = 0;
	$totalPaidCourses = 0;
	for($counter = 0; $counter < count($courseKeys); $counter++){
		$courseDocuments[$courseKeys[$counter]] = $institute[$courseKeys[$counter]];
	}
	foreach($courseDocuments as $applyCourse) {
		if($applyCourse->isPaid()){
			$totalPaidCourses++;
			if($applyCourse->isStudyAbroadCourse() == true){
				array_push($partial_studyAbroadIds, $applyCourse->getId());
			}
			$otherLocations = $applyCourse->getOtherLocations();
			if(!empty($otherLocations)){
				$totalMultiLocationCourses++;
			}
			$otherLocations[$applyCourse->getLocation()->getLocationId()] = $applyCourse->getLocation();
			$documentCourseLocation = $otherLocations;
			$partial_localityArray[$applyCourse->getId()] = getLocationsCityWise($documentCourseLocation);
		}
	}
	if($totalMultiLocationCourses > 0 || $totalPaidCourses > 1){ //This institute has more than one course to display
		if(!in_array($instituteId, $partial_instituteWithMultipleCourseLocations)){
			array_push($partial_instituteWithMultipleCourseLocations, (string)$instituteId);
		}
	}
}
?>

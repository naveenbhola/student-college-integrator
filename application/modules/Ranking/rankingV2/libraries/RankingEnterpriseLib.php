<?php

class RankingEnterpriseLib {
	
	private $_ci;
	private $categoryRepo;
	private $rankingCommonLib;
	private $rankingURLManager;
	private $ranking_model;
	private $alertClient;
	
	
	public function __construct($_ci, $rankingPageCommonLib, $rankingURLManager){
		if(!empty($rankingPageCommonLib)){
			$this->rankingCommonLib = $rankingPageCommonLib;
			$this->rankingURLManager = $rankingURLManager;
			$this->_ci = $_ci;
			$_ci->load->model('ranking_model');
		}else{
			$_ci = &get_instance();
			$_ci->load->model('ranking_model');
			$this->_ci = $_ci;
		}
		$this->_ci->load->library('alerts_client');
		$this->alertClient = new Alerts_client();
	}
	
	public function createRankingPage($params = array()){
		$return = array("success" => "false", "error_type" => "");
		if(!empty($params)){
			$paramsValid = $this->validateNewRankingPageParams($params);
			if($paramsValid === false){
				$return['error_type'][] = "INVALID_INPUT_PARAMS";
			} else {
				$categoryId 		= $params['category_id'];
				$subCategoryId 		= $params['subcategory_id'];
				$specializationId 	= $params['specialization_id'];
				$rankingPageText 	= strip_tags($params['ranking_page_text']);
				$rankingModel = new ranking_model();
				$return = $rankingModel->createRankingPage(true, $categoryId, $subCategoryId, $specializationId, $rankingPageText);
			}
		}
		return $return;
	}
	
	public function getRankingPagesWithPageData($params = array()) {
		$rankingModel = new ranking_model();
		$pages = $rankingModel->getRankingPages($params);
		
		if(empty($pages)){
			return array();
		}
		
		$rankingPages = $pages['results'];
		$rankingPageList = array();
        $rankingPageIds = array();
		foreach($rankingPages as $rankingPage) {
			$pageId 	= $rankingPage['id'];
			$pageName 	= $rankingPage['ranking_page_text'];
			$url = $this->rankingURLManager->getRankingPageURLByRankingPageId($pageId, $pageName);
			$rankingPage['url'] = $url;
			$rankingPageList[] = $rankingPage;
            $rankingPageIds[] = $rankingPage['id'];
		}

		$rankingPageDataCount 	= array();
		$status = array("live", "disable", "draft", "delete");
        if(count($rankingPageIds) > 0) {
            $rankingPageDataCount = $rankingModel->getRankingPageDataCount($rankingPageIds);
        }

		$pages['page_data_count'] = $rankingPageDataCount;
		$pages['results'] 		  = $rankingPageList;
		return $pages;
	}
	
	public function changeRankingPageStatus($id = NULL, $status = NULL){
		if(empty($id) || empty($status)){
			return false;
		}
		$rankingModel = new ranking_model();
		$flag = true;
		$errorMsg = "";
		$params = array();
		$params['ranking_page_id'] = $id;
		$rankingPageData = $rankingModel->getRankingPageData($params);
		if($status == "live"){
			if(count($rankingPageData) <= 0){
				$flag = false;
				$errorMsg = "Ranking page with 0 courses cannot go live.";
			}

			$params = array();
			$params['id'] 		= $id;
			$rankingPage = $rankingModel->getRankingPages($params);
			if(empty($rankingPage[0]['default_publisher'])) {
				$flag = false;
				$errorMsg = "Ranking page with no default publisher cannot go live.";
			}
		}
		$operationSuccessful = false;
		if($flag){
			$rankPageCourses = array();
			foreach ($rankingPageData as $value) {
				$rankPageCourses[] = $value['course_id'];
			}
			if(!empty($rankPageCourses)){
				$this->rankingCommonLib->indexCourseForRanking($rankPageCourses);
			}
			$operationSuccessful = $rankingModel->changeRankingPageStatus($id, $status);
		}
		$returnData = array();
		$returnData['success'] 		= $operationSuccessful;
		$returnData['error_msg']   	= $errorMsg;
		return $returnData;
	}
	
	public function deleteCourseFromRankingPageData($rankingPageId = NULL, $courseId = NULL, $checkForAtLeastOneCourseAvailable = true){
		if(empty($rankingPageId) || empty($courseId)){
			return false;
		}
		
		$rankingModel = new ranking_model();
		$params['ranking_page_id'] = $rankingPageId;
		$rankingPageData = $rankingModel->getRankingPageData($params);
		
		$params = array();
		$params['id'] = $rankingPageId;
		$rankingPage = $rankingModel->getRankingPages($params);
		$status = $rankingPage[0]['status'];
		
		$operationFlag = true;
		$errorMsg = "";
		if(!empty($rankingPageData) && $checkForAtLeastOneCourseAvailable && $status == "live" && count($rankingPageData) <= 1){
			$operationFlag = false;
			$operationSuccessful = false;
			$errorMsg = "Live ranking pages should have at least one course.";
		}
		if($operationFlag){
			$operationSuccessful = $rankingModel->deleteCourseFromRankingPageData($rankingPageId, $courseId);	
		}
		$returnData = array();
		$returnData['success'] = $operationSuccessful;
		$returnData['error_msg'] = $errorMsg;
		return $returnData;
	}
	
	public function removeCourseFromSourceData($rankingPageId = NULL, $courseId = NULL, $sourceId = NULL){
		if(empty($rankingPageId) || empty($courseId) || empty($sourceId)){
			return false;
		}
		
		$rankingModel = new ranking_model();
		$operationSuccessful = $rankingModel->removeCourseFromSourceData($rankingPageId, $courseId, $sourceId);
		
		$returnData = array();
		$returnData['success'] = $operationSuccessful;
		$returnData['error_msg'] = $errorMsg;
		return $returnData;
	}
	
	public function getRankingPageData($rankingPageId = NULL){
		$rankingPageData = array();
		if(empty($rankingPageId)){
			return $rankingPageData;
		}
		$rankingModel = new ranking_model();
		$params = array();
		$params['ranking_page_id'] = $rankingPageId;
		$rankingPageData = $rankingModel->getRankingPageData($params);
		if(!empty($rankingPageData)){
			foreach($rankingPageData as $key => $pageData){
				$courseIds[] = $pageData['course_id'];
				$rankingPageCourseIds[] = $pageData['id'];
			}
			
			//get course param details
			$courseParamData = $rankingModel->getRankingPageCourseParamData($rankingPageCourseIds);
			foreach($courseParamData as $key => $data) {
				$courseCustomParamData[$data['ranking_page_course_id']][$data['source_id']][$data['parameter_id']]['score'] = $data['score'];
				$courseCustomParamData[$data['ranking_page_course_id']][$data['source_id']][$data['parameter_id']]['rank'] = $data['rank'];
			}
			
			//get course data
			$courseDetails = $this->rankingCommonLib->getCourseRankingDetails($courseIds);
			foreach($rankingPageData as $key => $pageData){
				$tempCourseId = $pageData['course_id'];
				if(array_key_exists($tempCourseId, $courseDetails)){
					$rankingPageData[$key]['course_details'] = $courseDetails[$tempCourseId];
				}
				
				$tempPageCourseId = $pageData['id'];
				$rankingPageData[$key]['course_param_details'] = array();
				if(array_key_exists($tempPageCourseId, $courseCustomParamData)){
					$rankingPageData[$key]['course_param_details'] = $courseCustomParamData[$tempPageCourseId];
				}
			}
		}
		return $rankingPageData;
	}
	
	public function saveRankingCourseDetails($rankPageId = NULL, $instituteId = NULL, $courseId = NULL, $rankScoreDetails = NULL, $courseAltText = NULL, $sourceId = NULL){

		$return = array("success" => "false", "error_msg" => array());
		$this->_ci->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$rankingModel = new ranking_model();
		$rankingPage = $rankingModel->getRankingPages(array('id' => $rankPageId));
		$sourceIds = $rankingModel->getLatestSourceForRankingPage($rankPageId);
		$courseIds = array();
		$courseIds = $rankingModel->getDistinctCoursesBySource($rankPageId, $sourceIds);
		$maxCoursesInRankingPageAllowed = $this->_ci->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		if(!in_array($courseId, $courseIds)){ //new course addition case
			if(count($courseIds) >= $maxCoursesInRankingPageAllowed){
				$return['error_msg'][] = "You cannot add more courses to this ranking page. Maximum limit($maxCoursesInRankingPageAllowed) reached.";
				return $return;
			}
		}
		//if($overallRank > $maxCoursesInRankingPageAllowed){
			//$return['error_msg'][] = "Course rank should not be more than maximum limit (" . $maxCoursesInRankingPageAllowed . ").";
			//return $return;
		//}
		
		$returnData = $rankingModel->saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rankScoreDetails, $courseAltText,$sourceId);
		if(isset($rankingPage[0]['status']) && $rankingPage[0]['status'] == 'live' && !empty($courseIds)){
			$this->rankingCommonLib->indexCourseForRanking($courseIds);
		}
		return $returnData;
	}
	
	/*public function updateRankingPageDetails($postParams, $categoryParams, $rankingPageDisclaimer, $sourceSelectedValues){
		$sourceSelectedValuesArray = explode(",", $sourceSelectedValues);
		$categoryData = explode("!$!", $categoryParams);
		$parentCategoryId = $categoryData[0];
		$subCategoryId 	  = $categoryData[1];
		$specializationId = $categoryData[2];
		if($specializationId == "select"){
			$specializationId = 0;
		}
		$data = explode("$$", $postParams);
		$dataPageDetails = $data[0];
		$dataPageData 	 = $data[1];
		$tempD  = explode("!-!", $dataPageDetails);
		
		$rankingPageId = $tempD[0];
		$rankingPageName = $tempD[1];
		$dataPageData = trim($dataPageData);
		$dataPageData = trim($dataPageData, "!-!");
		$tempD  = array();
		if(trim($dataPageData) != ""){
			$tempD  = explode("!-!", $dataPageData);	
		}
		$courses = array();
		foreach($tempD as $data){
			$temp = explode("!$!", $data);
			$course = array();
			$course['course_id'] = $temp[0];
			$course['institute_id'] = $temp[1];
			$course['rank'] = $temp[2];
			$course['course_alt_text'] = $temp[3];
			
			$courses[] = $course;
		}
		$data = array();
		$data['ranking_page_id'] = $rankingPageId;
		$data['ranking_page_name'] = $rankingPageName;
		$data['categoryId'] 		= $parentCategoryId;
		$data['subcategoryId'] 	= $subCategoryId;
		$data['specializationId'] 	= $specializationId;
		$data['courses'] = $courses;
		$data['sourceSelectedValuesArray'] = $sourceSelectedValuesArray;
		$data['rankingPageDisclaimer'] = $rankingPageDisclaimer;
		$rankingModel = new ranking_model();
		$return = $rankingModel->updateRankingPageDetails($data);
		$this->rankingCommonLib->invalidateRankingPagesCache(array($rankingPageId));
		return $return;
	}*/
	
	public function updateSourceRankingPage($rankingPageId, $rankingPageStatus, $sourceSelectedValues) {
		$sourceSelectedValuesArray = explode(",", $sourceSelectedValues);
		
		$data['rankingPageId'] = $rankingPageId;
		$data['rankingPageStatus'] = $rankingPageStatus;
		$data['sourceSelectedValuesArray'] = $sourceSelectedValuesArray;
		$rankingModel = new ranking_model();
		$return = $rankingModel->updateSourceRankingPage($data);
		$courseArr = $rankingModel->getAllCoursesOnRankingPage($rankingPageId);
		if(!empty($courseArr)){
			$this->rankingCommonLib->indexCourseForRanking($courseArr);
		}
		$this->rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
		return $return;
	}
	
	public function loadInstituteSuggestions($categoryId = NULL, $subCategoryId = NULL, $specializationId = NULL) {
		$results = array();
		if(!empty($categoryId) && $categoryId != "select" && !empty($subCategoryId) && $subCategoryId != "select"){
			if($specializationId == "select"){
				$specializationId = 0;
			}
			$dataSet = $this->getCourseSuggestionsForRankingPage($categoryId, $subCategoryId, $specializationId);
			if($specializationId != 0){
				//Fetch the results for specialization from subcategory pages, apply check for specializations
				$specializationDataSet = $this->getCourseSuggestionsForRankingPage($categoryId, $subCategoryId, 0);
				foreach($specializationDataSet as $specializationSet){
					$specializationIds = $specializationSet['course_details']['specialization_ids'];
					if(in_array($specializationId, $specializationIds)){
						$dataSet[] = $specializationSet;
					}
				}
			}
			$courseIdsEncountered = array();
			foreach($dataSet as $data){
				if(!in_array($data['course_id'], $courseIdsEncountered)){
					$courseIdsEncountered[] = $data['course_id'];
					$results[] = $data;
				}
			}
		}
		return $results;
	}
	
	public function getCourseSuggestionsForRankingPage($categoryId = NULL, $subcategoryId = NULL, $specializationId = NULL){
		$returnData = array();
		$params = array();
		$params['category_id'] 		= $categoryId;
		$params['subcategory_id'] 	= $subcategoryId;
		$params['specialization_id']= $specializationId;
		$params['status'] = array('live', 'disable', 'draft');
		
		$rankingModel = new ranking_model();
		$rankingPages = $rankingModel->getRankingPages($params);
		$rankingPageIds = array();
		foreach($rankingPages as $rankingPage){
			$rankingPageIds[] = $rankingPage['id'];
		}
		$courses = array();
		if(count($rankingPageIds) > 0){
			foreach($rankingPageIds as $rankingPageId){
				$data = $this->getRankingPage($rankingPageId);
				$courses = array_merge($courses, $data);
			}
		}
		return $courses;
	}
	
	public function getRankingPage($rankingPageId = NULL){
		$courses = array();
		if(!empty($rankingPageId)){
			$params = array();
			$params['ranking_page_id'] 	= $rankingPageId;
			$rankingModel = new ranking_model();
			$rankingPageData = $rankingModel->getRankingPageData($params);
			foreach($rankingPageData as $data){
				$courseId = $data['course_id'];
				$tempCourseArr = array();
				$tempCourseArr = $data;
				$courseDetails = $this->rankingCommonLib->getCourseRankingDetails(array($courseId));
				$tempCourseArr['course_details'] = $courseDetails[$courseId];
				$courses[] = $tempCourseArr;
			}
		}
		return $courses;
	}
	
	private function validateNewRankingPageParams($params = array()){
		$valid = true;
		if(!empty($params)){
			if(empty($params['category_id']) && $params['category_id'] > 0){
				$valid = false;
			}
			if(empty($params['subcategory_id']) && $params['subcategory_id'] > 0){
				$valid = false;
			}
			if(empty($params['ranking_page_text'])){
				$valid = false;
			}
		}
		return $valid;
	}
	
	public function checkProductCategory($productId = NULL){
		$productCategory = false;
		if(empty($productId)){
			return $productCategory;
		}
		$tierMaxCount = 3;
		for($count = 1; $count <= $tierMaxCount; $count++){
			$cityKey  = "RBP_TIER_". $count ."_CITY_SUBCAT";
			$stateKey = "RBP_TIER_". $count ."_STATE_SUBCAT";
			$cityProductIds 	= $this->_ci->config->item($cityKey);
			$stateProductIds 	= $this->_ci->config->item($stateKey);
			
			if(in_array($productId, $cityProductIds)){
				$productCategory = "city";
				break;
			}
			if(in_array($productId, $stateProductIds)){
				$productCategory = "state";
				break;
			}
		}
		
		$allCitiesKey 		= "RBP_ALL_CITY_SUBCAT";
		$allCityProductIds 	= $this->_ci->config->item($allCitiesKey);
		if(in_array($productId, $allCityProductIds)){
			$productCategory = "city";
		}
		return $productCategory;
	}
	
	public function checkIfMaxLimitReachedForBanner($rankingPageId = NULL, $locationType = NULL, $locationId = NULL) {
		if(empty($rankingPageId) || empty($locationType) || empty($locationId)) {
			return false;
		}
		$rankingModel = new ranking_model();
		$banners = $rankingModel->getBannersByLocationAndRankingPage($rankingPageId, $locationType, $locationId);
		$totalBanners = count($banners);
		$maxBannersAllowedInLocation = 1;
		switch($locationType){
			case 'city':
				$maxBannersAllowedInLocation = $this->_ci->config->item('MAXIMUM_ALLOWED_BANNERS_FOR_CITY');
				break;
			case 'state':
				$maxBannersAllowedInLocation = $this->_ci->config->item('MAXIMUM_ALLOWED_BANNERS_FOR_STATE');
				break;
		}
		$maximumLimitReached = false;
		if($totalBanners >= $maxBannersAllowedInLocation){
			$maximumLimitReached = true;
		}
		return $maximumLimitReached;
	}
	
	public function changeRankingPageMetaDetails($data = NULL){

		if(empty($data['rankingPageId'])){
			return false;
		}

		$location = explode('-',$data['rankingPageLocation']);
		$data['rankingPageLocationId'] = $location[0];
		$data['rankingPageLocationType'] = $location[1];
		$rankingModel = new ranking_model();
		$status = $rankingModel->changeRankingPageMetaDetails($data);
		return $status;
	}
	
	public function getRankingPageMetaDetails($rankingPageId = NULL){
		$rankingModel = new ranking_model();
		$metaDetailsFromDB = $rankingModel->getRankingPageMetaDetails($rankingPageId);
		if(!empty($metaDetailsFromDB)){
			return $metaDetailsFromDB;
		}
		return false;
	}
	
	public function getRankingPageSourceData($rankingPageId = NULL, $rankingPageStatus = NULL) {
		$rankingModel = new ranking_model();
		$rankingPageSources = $rankingModel->getRankingPageSourceData($rankingPageId, $rankingPageStatus);
		if(!empty($rankingPageSources)){
			return $rankingPageSources;
		}
		return false;
	}

	public function getRankingPagePublishersWithData($rankingPageId = NULL, $rankingPageStatus = NULL) {
		$rankingModel = new ranking_model();
		$rankingPagePublishers = $rankingModel->getRankingPagePublishersWithData($rankingPageId, $rankingPageStatus);
		if(!empty($rankingPagePublishers)){
			return $rankingPagePublishers;
		}
		return false;
	}

	public function addRankingPage($mainTableData,$sources,$metaDetails = array()){
		$rankingModel = new ranking_model();
		$metaDetails = $this->formatMetaDetails($metaDetails);
		return $rankingModel->addRankingPage($mainTableData,$sources,$metaDetails);
	}

	public function editRankingPage($rankingPageId,$mainTableData, $metaDetails = array()){
		$rankingModel = new ranking_model();
		$metaDetails = $this->formatMetaDetails($metaDetails);
		return $rankingModel->editRankingPage($rankingPageId,$mainTableData,$metaDetails);
	}

	public function formatMetaDetails($metaDetails){
		$this->_ci->load->config('rankingV2/rankingConfig');
	   	$metaDetailConfig = $this->_ci->config->item('defaultMetaDetails');
	   	$metaDetails['ranking_page_title'] = ($metaDetails['ranking_page_title'])? $metaDetails['ranking_page_title']: $metaDetailConfig['location']['title'];
		$metaDetails['ranking_page_description'] = ($metaDetails['ranking_page_description']) ? ($metaDetails['ranking_page_description']): $metaDetailConfig['location']['description'];
	   	$metaDetails['ranking_page_title_exam'] = $metaDetailConfig['exam']['title'];
	   	$metaDetails['ranking_page_description_exam'] = $metaDetailConfig['exam']['description'];
	   	return $metaDetails;	

	}

	public function uploadInstituteRankingData($data){
		$rankingModel = new ranking_model();

	  	return $rankingModel->uploadRankingPageInstituteData($data);
	}
	public function getUnprocessedRankingPages(){
		$this->ranking_model = new ranking_model();
	  	$data =  $this->ranking_model->getUnprocessedRankingPages();
	  	return $data;
	}

	public function markDataAsProcessed($rankingPageId,$source){
		$this->ranking_model->markDataAsProcessed($rankingPageId,$source);
	}

	public function updateDataforRankingPageIDandSource($rankingPageId,$source,$data){
		
		
		$rankingCommonLib = $this->_ci->load->library(RANKING_PAGE_MODULE.'/rankingCommonLib');
		$returnData = array();
		$rankingPageData = $this->ranking_model->getRankingPageData(array('ranking_page_id' => $rankingPageId));
			$allInstitutesforSource = array_keys($data['sourceWiseData'][$source]);
			$coursesData  = $this->fetchCourseForInstitutes($rankingPageId,$allInstitutesforSource);
			
			$this->ranking_model->cleanDataforInstituteAndCourse($rankingPageId,$coursesData['instituteWiseCourse']);

			$rankPageData= $this->ranking_model->getDetailsforMappedInstitutes($rankingPageId, $allInstitutesforSource);
			$existingInstitutes = array();
			foreach ($rankPageData as $key => $value) {
   				$existingInstitutes[]=$value["institute_id"];
   	 		}
			$remainingInstitutes=array_diff($allInstitutesforSource,$existingInstitutes);
			$remainingInstitutes = array_unique($remainingInstitutes);
			$allCourseAdded = array();
			
			if(!empty($remainingInstitutes)){

				foreach ($existingInstitutes as $value) {
					unset($coursesData['instituteWiseCourse'][$value]);
				}
				$allCourseAdded = $this->updateCourseForInstitutes($rankingPageId,$remainingInstitutes,$coursesData);	
			}
			$this->updateRankDetails($rankingPageId,$source,$data);
			foreach($rankingPageData as $pageData)
			{
				$allCourseAdded[]= $pageData['course_id'];
			}
		return $allCourseAdded;  	
	}

	public function updateCourseForInstitutes($rankingPageId,$instituteIds,$coursesData){
		
		$returnData =array();
		$coursesData['allCourseIds'] = array();
		if(!empty($coursesData)){
			$data =array();
			foreach ($coursesData['instituteWiseCourse'] as $key => $value) {

				$inputData = array();
				$inputData['ranking_page_id']=$rankingPageId;
				$inputData['institute_id'] = $key;
				$inputData['course_id'] = $value['course_id'];
				$coursesData['allCourseIds'][] = $value['course_id'];
				$inputData['course_alt_text'] = $value['course_alt_text'];
				$data[]=$inputData;

			}
			if(!empty($data)){
				$this->ranking_model->saveCourseDetailsForInstitutes($data);
			}
			$returnData = $coursesData['allCourseIds'];
		}
		return $returnData;
	}

	public function fetchCourseForInstitutes($rankingPageId,$instituteIds){

		$courseCriteria = $this->ranking_model->getCourseCriteriaforRankingPage($rankingPageId);
		$rankingCommonLib = $this->_ci->load->library(RANKING_PAGE_MODULE.'/rankingCommonLibv2');
		$instituteDetailLib = $this->_ci->load->library('nationalInstitute/InstituteDetailLib');
		$returnData = array();
		$allCourseIds = array();	
		$mailContent = "";
		$mailSubject = "Ranking CMS Institute Upload Cron Alert";
		foreach ($instituteIds as $instituteId) {
			$resultSet = $rankingCommonLib->getInstituteCourses($instituteId);
			$validCourses = array();
			$validCourseName = array();
			foreach ($resultSet as $id => $courseData) {
				$isValidCourse =true;
				$streamId = $courseData['stream'];
				$substreamId = $courseData['substream'];
				$specializationId = $courseData['specialization'];
				$baseCourse = $courseData['baseCourse'];
				if($courseCriteria['stream_id'] > 0 ){
					if($streamId != $courseCriteria['stream_id']){
						$isValidCourse = false;
					}
					else if($courseCriteria['substream_id'] > 0 ){
						if($substreamId != $courseCriteria['substream_id']){
							$isValidCourse = false;
						}
						else if($courseCriteria['specialization_id'] > 0 ){
							if($specializationId != $courseCriteria['specialization_id']){
								$isValidCourse = false;
							}
						}
					}
				}
				if($courseCriteria['base_course_id'] > 0 ){
					if($baseCourse != $courseCriteria['base_course_id']){
						$isValidCourse = false;
					}
				}

				if($isValidCourse){
					$validCourses[]=$id;
					$validCourseName[$id] = $courseData['name'];
				}
			}
			if(!empty($validCourses)){

				$courseViewCount = array_keys($instituteDetailLib->getCourseViewCount($validCourses));
				$mostViewedCourse = reset($courseViewCount);
				$courseInfo['course_id'] = $mostViewedCourse;
				$courseInfo['course_alt_text'] = $validCourseName[$mostViewedCourse];
				$returnData['instituteWiseCourse'][$instituteId] = $courseInfo;	
			}else {
				if($mailContent ==""){
					$mailContent = "For Ranking Page Id : ".$rankingPageId." following Institutes has no Valid Courses : <br>";
				}
				$mailContent =$mailContent.$instituteId."<br>";
			}
		}
		if($mailContent != ""){
		    $emailIdsArray = array("listingstech@shiksha.com", "sourabh.raj@shiksha.com", "jakhodia.nikita@shiksha.com");
			foreach ($emailIdsArray as $emailId) {
				$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $mailSubject, $mailContent, "html", '', 'n');
			}
		}
		
		return $returnData;
	}
	
	public function updateRankDetails($rankingPageId,$source,$data){
		$instituteRankMap  = $data['sourceWiseData'][$source];
		$allInstitutesforSource = array_keys($instituteRankMap);
		$rankPageData= $this->ranking_model->getDetailsforMappedInstitutes($rankingPageId, $allInstitutesforSource);
		$data = array();
		foreach ($rankPageData as $key => $value) {
			$inputData = array();
			$inputData['ranking_page_course_id'] = $value['id'];
			$inputData['source_id'] = $source;
			$inputData['rank'] = $instituteRankMap[ $value['institute_id'] ];
			$data[] = $inputData;
		}
		if(!empty($data)){
		 $this->ranking_model->deleteExistingRankDataforSource($rankingPageId,$source);
		 $this->ranking_model->updateRankDetails($rankingPageId,$data);
		}
	}

}

	
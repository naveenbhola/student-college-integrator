<?php

class RankingEnterpriseLib {
	
	private $_ci;
	private $categoryRepo;
	private $rankingCommonLib;
	private $rankingURLManager;
	
	public function __construct($_ci, $categoryRepo, $rankingPageCommonLib, $rankingURLManager){
		if(!empty($categoryRepo) && !empty($rankingPageCommonLib)){
			$this->rankingCommonLib = $rankingPageCommonLib;
			$this->categoryRepo 	= $categoryRepo;
			$this->rankingURLManager = $rankingURLManager;
			$this->_ci = $_ci;
		}
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
				$return = $rankingModel->createRankingPage($categoryId, $subCategoryId, $specializationId, $rankingPageText);
			}
		}
		return $return;
	}
	
	public function getRankingPagesWithPageData($params = array()){
		$rankingModel = new ranking_model();
		$pages = $rankingModel->getRankingPages($params);
		if(empty($pages)){
			return array();
		}
		
		$rankingPages = $pages['results'];
		$rankingPageList = array();
		foreach($rankingPages as $rankingPage){
			$pageId 	= $rankingPage['id'];
			$pageName 	= $rankingPage['ranking_page_text'];
			$url = $this->rankingURLManager->getRankingPageURLByRankingPageId($pageId, $pageName);
			$rankingPage['url'] = $url;
			$rankingPageList[] = $rankingPage;
		}
		$this->_ci->load->builder('RankingPageBuilder', 'ranking');
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$rankingPageDataCount 	= array();
		$status = array("live", "disable", "draft", "delete");
		foreach($rankingPages as $rankingPage){
			$rankingPageObject = $rankingPageRepository->find($rankingPage['id'], $status);
			if($rankingPageObject){
				$rankingPageDataCount[$rankingPage['id']] = count($rankingPageObject->getRankingPageData());
			}
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
		if($status == "live"){
			$params = array();
			$params['ranking_page_id'] 		= $id;
			$rankingPageData = $rankingModel->getRankingPageData($params);
			if(count($rankingPageData) <= 0){
				$flag = false;
				$errorMsg = "Ranking page with 0 courses cannot go live.";
			}
		}
		$operationSuccessful = false;
		if($flag){
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
		$this->_ci->load->builder('RankingPageBuilder', 'ranking');
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$rankingPage = $rankingPageRepository->find($rankingPageId, array('live', 'disable', 'delete', 'draft'));
		$operationFlag = true;
		$errorMsg = "";
		if(!empty($rankingPage) && $checkForAtLeastOneCourseAvailable){
			$status = $rankingPage->getStatus();
			$rankingPageData = $rankingPage->getRankingPageData();
			if($status == "live" && count($rankingPageData) <= 1){
				$operationFlag = false;
				$operationSuccessful = false;
				$errorMsg = "Live ranking pages should have at least one course.";
			}
		}
		if($operationFlag){
			$rankingModel = new ranking_model();
			$operationSuccessful = $rankingModel->deleteCourseFromRankingPageData($rankingPageId, $courseId);	
		}
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
			}
			$courseDetails = $this->rankingCommonLib->getCourseRankingDetails($courseIds);
			foreach($rankingPageData as $key => $pageData){
				$tempCourseId = $pageData['course_id'];
				if(array_key_exists($tempCourseId, $courseDetails)){
					$rankingPageData[$key]['course_details'] = $courseDetails[$tempCourseId];
				}
			}
		}
		return $rankingPageData;
	}
	
	public function saveRankingCourseDetails($rankPageId = NULL, $instituteId = NULL, $courseId = NULL, $rank = NULL, $courseAltText = NULL){
		$return = array("success" => "false", "error_msg" => array());
		$this->_ci->load->builder('RankingPageBuilder', 'ranking');
		$this->_ci->config->load('ranking_config');
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$rankingPage = $rankingPageRepository->find($rankPageId, array('live', 'disable', 'draft', 'delete'));
		$rankingPageData = array();
		if(!empty($rankingPage)){
			$rankingPageData = $rankingPage->getRankingPageData();
		}
		
		$courseIds = array();
		foreach($rankingPageData as $pageData){
			$tempCourseId = $pageData->getCourseId();
			$courseIds[] = $tempCourseId;
		}
		
		$maxCoursesInRankingPageAllowed = $this->_ci->config->item('MAXIMUM_COURSES_IN_RANKING_PAGE');
		if(!in_array($courseId, $courseIds)){ //new course addition case
			if(count($courseIds) >= $maxCoursesInRankingPageAllowed){
				$return['error_msg'][] = "You cannot add more courses to this ranking page. Maximum limit($maxCoursesInRankingPageAllowed) reached.";
				return $return;
			}
		}
		if($rank > $maxCoursesInRankingPageAllowed){
			$return['error_msg'][] = "Course rank should not be more than maximum limit (" . $maxCoursesInRankingPageAllowed . ").";
			return $return;
		}
		$rankingModel = new ranking_model();
		$returnData = $rankingModel->saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rank, $courseAltText);
		return $returnData;
	}
	
	/*public function updateRankingPageDetails($postParams, $categoryParams){
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
		$rankingModel = new ranking_model();
		$return = $rankingModel->updateRankingPageDetails($data);
		return $return;
	}*/
	
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
	
	public function changeRankingPageMetaDetails($rankingPageId = NULL, $rankingPageTitle = NULL, $rankingPageDescription = NULL, $rankingPageTitleExam = NULL, $rankingPageDescriptionExam = NULL, $multiplePassesAllowed = false){
		if(empty($rankingPageId) || empty($rankingPageTitle) || empty($rankingPageDescription)){
			return false;
		}
		$rankingModel = new ranking_model();
		$status = $rankingModel->changeRankingPageMetaDetails($rankingPageId, $rankingPageTitle, $rankingPageDescription, $rankingPageTitleExam, $rankingPageDescriptionExam, $multiplePassesAllowed);
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
}

	
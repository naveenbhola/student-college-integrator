<?php

class RankingPageFilterManager {
	
	private $rankingURLManager;
	private $locationRepo;
	public $rankingModel;
	private $skipStatusCheck = "false";
	private $_ci;
	public function __construct($_ci, $locationRepo, $rankingURLManager, $rankingModel){
		if(!empty($locationRepo) && !empty($rankingURLManager) && !empty($rankingModel)){
			$this->locationRepo			= $locationRepo;
			$this->rankingURLManager  	= $rankingURLManager;
			$this->rankingModel			= $rankingModel;
			$this->_ci = $_ci;
		}
	}
	private function _getAllBaseCoursesForRankingPage(){
            $this->_ci->load->builder('ListingBaseBuilder','listingBase');
            $builder = new ListingBaseBuilder;
            $repo = $builder->getBaseCourseRepository();
            $allBaseCourses = $repo->getAllBaseCourses('object');
            return $allBaseCourses;
        }

    function _getFilterTypes($rankingPageObject) {
    	if($rankingPageObject->getTupleType() == 'institute') {
    		return array('location', 'specialization', 'publisher', 'resetAll');
    	}
    	else {
    		return array('location', 'exam', 'specialization', 'publisher', 'resetAll');
    	}
    }

	public function getFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$filters = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $filters;
		}
		
		$rankingPageId  = $rankingPageObject->getId();
		$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
        $rankingPageIdentifier = $rankingPageRequestObject->getCountryId().
                                 $rankingPageRequestObject->getStateId().
                                 $rankingPageRequestObject->getCityId().
                                 $rankingPageRequestObject->getExamId();
        $this->_ci->benchmark->mark('filters_from_cache_start');
		$filters = $this->rankingCache->getRankingPageFilters($rankingPageRequestObject->getPageId(), $rankingPageIdentifier);
		if(!empty($filters)){
        	$this->_ci->benchmark->mark('filters_from_cache_end');
			return $filters;
		}
		
		$pageCriteria = $this->prepareRankingPageCriteria($rankingPageRequestObject, $rankingPageObject);
		
		$filters = array();
		$baseCourses = array();
		$baseCourses = $this->_getAllBaseCoursesForRankingPage();
		$filterTypes = $this->_getFilterTypes($rankingPageObject);
		$this->_ci->benchmark->mark('filters_from_db_start');
		foreach($filterTypes as $filterType){
			switch($filterType){
				case 'location':
					$locationFilters 		= $this->getLocationFilters($rankingPageObject, $rankingPageRequestObject,$baseCourses, $pageCriteria);
					$filters['city'] 	= $locationFilters['cityFilter'];
					$filters['state'] 	= $locationFilters['stateFilter'];
					$filters['selectedLocationFilter'] 	= $locationFilters['selectedLocationFilter'];
					break;
				case 'exam':
					$examFilters 		= $this->_getExamFilters($rankingPageObject, $rankingPageRequestObject,$baseCourses, $pageCriteria);
					$filters['exam'] 				= $examFilters['examFilterList'];
					$filters['selectedExamFilter'] 	= $examFilters['selectedExamFilter'];
					break;
				case 'specialization':
					$specializationFilters 		= $this->_getSpecializationFilters($rankingPageObject, $rankingPageRequestObject,$baseCourses, $pageCriteria);
					 $filters['specialization'] = $specializationFilters['specializationFilterList'];
					 $filters['selectedSpecializationFilter'] = $specializationFilters['selectedSpecializationFilter'];
					break;
				case 'publisher': 
					$publisherFilters 		= $this->_getPublisherFilters($rankingPageObject, $rankingPageRequestObject,$baseCourses, $pageCriteria);
					$filters['publisher'] = $publisherFilters['publisherFilterList'];
					$filters['selectedPublisherFilter'] = $publisherFilters['selectedPublisherFilter'];
					break;
				case 'resetAll':
					$filters['resetAllFilter'] = $this->_getResetAllFilter($filters, $rankingPageRequestObject, $baseCourses);
					break;
			}
		}
		$this->_ci->benchmark->mark('filters_from_db_end');
		$filters['currentPageUrl'] = getCurrentPageURLWithoutQueryParams();
		$this->rankingCache->storeRankingPageFilters($rankingPageRequestObject->getPageId(), $rankingPageIdentifier,$filters);
		return $filters;
	}
	
	/**
	 *@method: check whether particular ranking page data row i;e course details satisfies the user selected filter or not.
	 *@return: If satisfies than returns true else returns false
	*/
	private function satisfiesExamFilter(RankingPageData $rankingPageData, RankingPageRequest $rankingPageRequestObject = NULL) {
		$satisfiesExamFilter = false;
		if(empty($rankingPageRequestObject) || empty($rankingPageData)){
			return $satisfiesExamFilter;
		}
		$requestExamId	= $rankingPageRequestObject->getExamId();
		if(empty($requestExamId)){
			$satisfiesExamFilter = true;
		} else {
			$exams = $rankingPageData->getExams();
			if(!empty($exams)){
				foreach($exams as $exam){
					$examId = $exam['id'];
					if($examId == $requestExamId){
						$satisfiesExamFilter = true;
						break;
					}
				}
			}
		}
		
		return $satisfiesExamFilter;
	}
	
	/*
	 *@method : Generated specialization filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter data array()
	 *@example output: 
		array
		   (
			   [id] => 12
			   [name] => MBA22
			   [url] => 
			   [selectedName] => 1
		   )
	 */
	private function _getSpecializationFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL,$baseCourses, $criteria){
		$rankingCommonLib = $this->_ci->load->library("rankingV2/RankingCommonLib");

		$currentPageTupleType = $rankingPageObject->getTupleType();
		$currentPageExamId = $rankingPageRequestObject->getExamId();
		$currentPageCityId = $rankingPageRequestObject->getCityId();
		$currentPageStateId = $rankingPageRequestObject->getStateId();
		$specializationFilterList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $specializationFilterList;
		}
		$this->ListingBaseBuilder    = new ListingBaseBuilder();
		$this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
		$specializationFilters = $this->rankingModel->getFilters($criteria, 'specialization');
		
		$parentRankingPageData = $this->rankingModel->getParentRankingPage($rankingPageObject->getId(), $criteria);
		$parentRankingPageId = $parentRankingPageData['rankingPageId'];
		$isLocationMapped = $parentRankingPageData['isLocationMapped'];
		$rankingPageIds = array_keys($specializationFilters);
		$defaultFilter 				= NULL;
		$currentSelectedFilter 		= NULL;
		$isCurrentSelectedSpecializationValid = false;
		$params = array();
		if(empty($specializationFilters[$parentRankingPageId])) {
			array_unshift($specializationFilters, array('ranking_page_id' => $parentRankingPageId, 'isParent' => 1));
		} else {
			$specializationFilters[$parentRankingPageId]['isParent'] = 1;
		}
		
		foreach ($specializationFilters as  $val) {
			$rankingPageId = $val['ranking_page_id'];
			$rankingPageInfoArray = $rankingCommonLib->getRankingPagesUsingCache(array('id' => $rankingPageId, 'status' => 'live'));
			$params['cityId'] = $currentPageCityId;
			$params['stateId'] = $currentPageStateId;
			$params['examId'] = $currentPageExamId;
			$rankingPageInfo = reset($rankingPageInfoArray);
			$params['rankingPageId'] = $rankingPageId;
			
			//check if tuple type is different
			if($currentPageTupleType != $rankingPageInfo['tuple_type'] && $val['isParent']) {
				unset($params['examId']);
				if(!$isLocationMapped) {
					unset($params['cityId']);
					unset($params['stateId']);
				}
			}
			
			$isCurrentSelectedSpecializationValid = false;
			if($rankingPageId == $criteria['ranking_page_id']){
				$isCurrentSelectedSpecializationValid = true;
			}
			
			$obj = $this->rankingURLManager->getRankingPageRequestFromDataArray($params);
			$url = $this->rankingURLManager->buildURL($obj,'url',$baseCourses);
			
			if($isCurrentSelectedSpecializationValid){
				$filterObject 			= new RankingPageFilter($rankingPageId, $obj->getPageName(), $url, true);
				$currentSelectedFilter 	= $filterObject;
				$specializationFilterList[] 	= $filterObject;
			} else {
				$filterObject 		= new RankingPageFilter($rankingPageId, $obj->getPageName(), $url);
				$specializationFilterList[] 	= $filterObject;
			}
			$filterObject 		= NULL;
			$obj = NULL;
		}

		if(empty($currentSelectedFilter)){
			$currentSelectedFilter = reset($specializationFilterList);
		}
        return array('specializationFilterList' =>  $specializationFilterList, 'selectedSpecializationFilter' => $currentSelectedFilter);
	}
	
	/**
	 *@method : Generates default 'All' filter
	 *@return : filter object
	*/
	private function getDefaultFilter(RankingPageRequest $defaultRequestObject, $filterType = NULL, $defaultSelected = false){
		$filterObject = NULL;
		if(!empty($filterType)){
			switch($filterType){
				case 'city':
				case 'state':
					$defaultRequestObject->setCountryId(2);
					$defaultRequestObject->setCountryName('India');
					$url = $this->rankingURLManager->buildURL($defaultRequestObject);
					$filterObject 	= new RankingPageFilter(2, 'All Location', $url, $defaultSelected);
					break;
				case 'exam':
					$defaultRequestObject->setExamId(0);
					$defaultRequestObject->setExamName('All');
					$url = $this->rankingURLManager->buildURL($defaultRequestObject);
					$filterObject 	= new RankingPageFilter(0, 'All Exam', $url, $defaultSelected);
					break;
			}
			$defaultRequestObject = NULL;
		}
		return $filterObject;
	}

	/**
	 *@method : Generated City filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter objects
	 *@example output:
	 *Array (
		[0] => RankingPageFilter Object (
            [id:RankingPageFilter:private] => 2
            [name:RankingPageFilter:private] => All
            [url:RankingPageFilter:private] => /top-mba-colleges-in-india-ranking-12-2-0-0-0
            [selected:RankingPageFilter:private] => 1
        )
		[1] => RankingPageFilter Object
        (
            [id:RankingPageFilter:private] => 30
            [name:RankingPageFilter:private] => Ahmedabad
            [url:RankingPageFilter:private] => /top-mba22-colleges-in-ahmedabad-ranking-12-0-0-30-0
            [selected:RankingPageFilter:private] => 
        )
	*/
	private function getLocationFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL,$baseCourses, $criteria){
		$cityFiltersList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $cityFiltersList;
		}
		$rankingPageData  = $rankingPageObject->getRankingPageData();
		if(empty($rankingPageData) || !is_array($rankingPageData)){
			return $cityFiltersList;
		}
		
		$cityFilters = $this->rankingModel->getFilters($criteria, 'city');
		
		$defaultFilter 						= NULL;
		$currentlySelectedFilter 			= NULL;
		$currentlySelectedCityIsValid 		= false;

		$requestObject 	= new RankingPageRequest();
		$requestObject->setPageId($rankingPageRequestObject->getPageId());
		$requestObject->setPageName($rankingPageRequestObject->getPageName());
		$requestObject->setExamId($rankingPageRequestObject->getExamId());
		$requestObject->setExamName($rankingPageRequestObject->getExamName());
		$requestObject->setSpecializationId($rankingPageRequestObject->getSpecializationId());
		$requestObject->setStreamId($rankingPageRequestObject->getStreamId());
		$requestObject->setSubstreamId($rankingPageRequestObject->getSubstreamId());
		$requestObject->setSpecializationId($rankingPageRequestObject->getSpecializationId());
		$requestObject->setBaseCourseId($rankingPageRequestObject->getBaseCourseId());

		//getting city Filters
		$cityFilterData = $this->_prepareCityFilters(array_keys($cityFilters), $criteria, $requestObject, $baseCourses);
		$stateIds = $cityFilterData['stateIds'];
		$requestObject 	= new RankingPageRequest();
		$requestObject->setPageId($rankingPageRequestObject->getPageId());
		$requestObject->setPageName($rankingPageRequestObject->getPageName());
		$requestObject->setExamId($rankingPageRequestObject->getExamId());
		$requestObject->setExamName($rankingPageRequestObject->getExamName());
		$requestObject->setStreamId($rankingPageRequestObject->getStreamId());
		$requestObject->setSubstreamId($rankingPageRequestObject->getSubstreamId());
		$requestObject->setSpecializationId($rankingPageRequestObject->getBaseCourseId());
		$requestObject->setBaseCourseId($rankingPageRequestObject->getBaseCourseId());
		$stateFilterData = $this->_prepareStateFilters($stateIds, $criteria, $requestObject, $baseCourses);
		
		$selectedLocationFilter = $stateFilterData['selectedStateFilter'];
		if(!empty($cityFilterData['selectedCityFilter'])) {
			$selectedLocationFilter = $cityFilterData['selectedCityFilter'];
		}

		return array('cityFilter' => $cityFilterData['cityFilters'],
					 'stateFilter' => $stateFilterData['stateFilters'],
					 'selectedLocationFilter' => $selectedLocationFilter);
	}

	function prepareRankingPageCriteria(RankingPageRequest $rankingPageRequestObject, RankingPage $rankingPageObject) {
		$requestPageId 				= $rankingPageRequestObject->getPageId();
		$requestExamId 				= $rankingPageRequestObject->getExamId();
		$requestExamName 			= $rankingPageRequestObject->getExamName();
		$requestCityId				= $rankingPageRequestObject->getCityId();
		$requestStateId				= $rankingPageRequestObject->getStateId();
		$requestCityName			= $rankingPageRequestObject->getCityName();
		$requestStreamId			= $rankingPageRequestObject->getStreamId();
		$requestSubstreamId 		= $rankingPageRequestObject->getSubstreamId();
		$requestSpecializationId 	= $rankingPageRequestObject->getSpecializationId();
		$requestBaseCourseId		= $rankingPageRequestObject->getBaseCourseId();
		if(is_object($rankingPageObject)) {
			$publisherId 				= $rankingPageObject->getPublisherId();
		}

		$criteria['city_id']           = $requestCityId;
		$criteria['state_id']          = $requestStateId;
		$criteria['exam_id']           = $requestExamId;
		$criteria['specialization_id'] = $requestSpecializationId;
		$criteria['ranking_page_id']   = $requestPageId;
		$criteria['publisher_id']      = $publisherId;
		$criteria['stream_id']         = $requestStreamId;
		$criteria['substream_id']      = $requestSubstreamId;
		$criteria['base_course_id']    = $requestBaseCourseId;
		return $criteria;
	}

	private function _prepareCityFilters($cityIds, $criteria, $requestObject, $baseCourses) {

		$cityObjs = $this->locationRepo->findMultipleCities($cityIds);
		$cityFiltersList = array();
		foreach($cityObjs as $cityIdKey => $obj) {
			$cityId = $cityIdKey;
			if($obj->isVirtualCity() || $obj->getStateId() == -1) {
				continue;
			}
			$cityName = $obj->getName();
			if($obj->getVirtualCityId() > 0) {
				$virtualCityObj = $this->locationRepo->findCity($obj->getVirtualCityId());
				$cityId = $virtualCityObj->getId();
				$cityName = $virtualCityObj->getName();
			}

			$currentlySelectedCityIsValid 	=  false;
			if($cityId == $criteria['city_id']){
				$currentlySelectedCityIsValid = true;
			}
			$requestObject->setCityId($cityId);
			$requestObject->setCityName($cityName);
			$url = $this->rankingURLManager->buildURL($requestObject,'url',$baseCourses);
			if(!$currentlySelectedCityIsValid){ //If this city is not currently selected by user, than this should not be highlighted
				$filterObject 	= new RankingPageFilter($cityId, $cityName, $url, false);
				$cityFiltersList[] = $filterObject;
			} else {
				$filterObject 	= new RankingPageFilter($cityId, $cityName, $url, $currentlySelectedCityIsValid);
				$currentlySelectedFilter = $filterObject;
				$cityFiltersList[] = $filterObject;
			}

			//getting state ids
			$stateIds[] = $obj->getStateId();
		}
		
		$stateIds = array_unique($stateIds);
		$cityFiltersList = array_unique($cityFiltersList, SORT_REGULAR);
		// _p($stateIds); die;
		//Generate default 'All' filter
		$defaultFilterIsSelected = false;
		if(empty($currentlySelectedFilter)){
			$defaultFilterIsSelected = true;
		}
		
		uasort($cityFiltersList, 'stringCompareFN'); //Sort alphabatically all the city filters
		
		if(!empty($defaultFilter)){
			array_unshift($cityFiltersList, $defaultFilter); //Push 'all' default filter on top of list(Requirement: Default 'All' filter should be at the top).
		}
		return array('cityFilters' => $cityFiltersList, 'stateIds' => $stateIds, 'selectedCityFilter' => $currentlySelectedFilter);
	}

	private function _prepareStateFilters($stateIds, $criteria, $requestObject, $baseCourses) {
		$stateObjs = $this->locationRepo->findMultipleStates($stateIds);
		$stateFiltersList = array();
		foreach($stateObjs as $stateId => $obj) {
			$isCurrentSelectedStateValid = false;
			$stateName = $obj->getName();
			if($stateId == $criteria['state_id']){
				$isCurrentSelectedStateValid = true;
			}
			$requestObject->setStateId($stateId);
			$requestObject->setStateName($stateName);
			
			$url = $this->rankingURLManager->buildURL($requestObject,'url',$baseCourses);
			if($isCurrentSelectedStateValid){
				$filterObject 			 = new RankingPageFilter($stateId, $stateName, $url, true);
				$currentSelectedFilter 	 = $filterObject;
				$stateFiltersList[] = $filterObject;
			} else {
				$filterObject 		= new RankingPageFilter($stateId, $stateName, $url, false);
				$stateFiltersList[] = $filterObject;
			}

			$filterObject 		= NULL;
			$encounteredIds[] 	= $stateId;
		}

		uasort($stateFiltersList, 'stringCompareFN'); //Sort alphabatically all the state filters

		$defaultRequestObject 	= new RankingPageRequest();
		$defaultRequestObject->setPageId($requestObject->getPageId());
		$defaultRequestObject->setPageName($requestObject->getPageName());
		$defaultRequestObject->setExamId($requestObject->getExamId());
		$defaultRequestObject->setExamName($requestObject->getExamName());
		$defaultRequestObject->setStreamId($requestObject->getStreamId());
		$defaultRequestObject->setSubstreamId($requestObject->getSubstreamId());
		$defaultRequestObject->setSpecializationId($requestObject->getSpecializationId());
		$defaultRequestObject->setBaseCourseId($requestObject->getBaseCourseId());
		$defaultFilter = $this->getDefaultFilter($defaultRequestObject, 'city', $defaultFilterIsSelected);
		
		array_unshift($stateFiltersList, $defaultFilter); //Push currently selected filter on top of list(Requirement: Currently selected filter should be second in the filters list).
		if(empty($currentSelectedFilter)){
			$currentSelectedFilter = reset($stateFiltersList);
			$defaultFilter->setSelected(true);
		}

		return array('stateFilters' => $stateFiltersList, 'selectedStateFilter' => $currentSelectedFilter);
	}

	private function _getExamFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL,$baseCourses, $criteria){
		$examFilterList = array();
		$this->_ci->benchmark->mark('exam_start');
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $examFilterList;
		}
		$this->examLib = $this->_ci->load->library('examPages/ExamMainLib');
		$examFilters = $this->rankingModel->getFilters($criteria, 'exam');
		$examData = $this->examLib->getExamDataByExamIds(array_keys($examFilters));
		
		
		$defaultFilter 				= NULL;
		$currentSelectedFilter 		= NULL;
		$isCurrentSelectedExamValid = false;

		$requestObject 	= new RankingPageRequest();
		$requestObject->setPageId($rankingPageRequestObject->getPageId());
		$requestObject->setPageName($rankingPageRequestObject->getPageName());
		$requestObject->setExamId($rankingPageRequestObject->getExamId());
		$requestObject->setExamName($rankingPageRequestObject->getExamName());
		$requestObject->setSpecializationId($rankingPageRequestObject->getSpecializationId());
		$requestObject->setBaseCourseId($rankingPageRequestObject->getBaseCourseId());
		$requestObject->setCityId($rankingPageRequestObject->getCityId());
		$requestObject->setCityName($rankingPageRequestObject->getCityName());
		$requestObject->setStateId($rankingPageRequestObject->getStateId());
		$requestObject->setStateName($rankingPageRequestObject->getStateName());
		$requestObject->setStreamId($rankingPageRequestObject->getStreamId());
		$requestObject->setSubstreamId($rankingPageRequestObject->getSubstreamId());
		// _p($examData); die;

		foreach ($examData as $examId => $exam) {
			$isCurrentSelectedExamValid = false;
			$examName = $exam['name'];
			if(empty($examName)) {
				continue;
			}
			if($examId == $criteria['exam_id']){
				$isCurrentSelectedExamValid = true;
			}
			$requestObject->setExamId($examId);
			$requestObject->setExamName($examName);
			$url = $this->rankingURLManager->buildURL($requestObject,'url',$baseCourses);
			
			if($isCurrentSelectedExamValid){
				$filterObject 			= new RankingPageFilter($examId, $examName, $url, true);
				$currentSelectedFilter 	= $filterObject;
				$examFilterList[] 	= $filterObject;
			} else {
				$filterObject 		= new RankingPageFilter($examId, $examName, $url);
				$examFilterList[] 	= $filterObject;
			}
			// $requestObject 		= NULL;
			$filterObject 		= NULL;
		}

		$defaultIsSelectedParam = false;
		if(empty($currentSelectedFilter)){
			$defaultIsSelectedParam = true;
		}
		// Default filter
		$defaultRequestObject 	= new RankingPageRequest();
		$defaultRequestObject->setPageId($rankingPageRequestObject->getPageId());
		$defaultRequestObject->setPageName($rankingPageRequestObject->getPageName());
		$defaultRequestObject->setSpecializationId($rankingPageRequestObject->getSpecializationId());
		$defaultRequestObject->setBaseCourseId($rankingPageRequestObject->getBaseCourseId());
		$defaultRequestObject->setCityId($rankingPageRequestObject->getCityId());
		$defaultRequestObject->setCityName($rankingPageRequestObject->getCityName());
		$defaultRequestObject->setStateId($rankingPageRequestObject->getStateId());
		$defaultRequestObject->setStateName($rankingPageRequestObject->getStateName());
		$defaultRequestObject->setStreamId($rankingPageRequestObject->getStreamId());
		$defaultRequestObject->setSubstreamId($rankingPageRequestObject->getSubstreamId());
		$defaultFilter = $this->getDefaultFilter($defaultRequestObject, 'exam', $defaultIsSelectedParam);
		
		uasort($examFilterList, 'stringCompareFN');
		
		array_unshift($examFilterList, $defaultFilter);
		
		if(empty($currentSelectedFilter)){
			$currentSelectedFilter = reset($examFilterList);
		}
		$this->_ci->benchmark->mark('exam_end');
		return array('examFilterList' => $examFilterList, 'selectedExamFilter' => $currentSelectedFilter);
	}

	function _getPublisherFilters($rankingPageObject, $rankingPageRequestObject,$baseCourses, $criteria) {
		$publisherFilterList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $publisherFilterList;
		}

		$isCurrentSelectedPublisherValid = false;
		
		$rankingPagePublishersFilters = $this->rankingModel->getFilters($criteria, 'publisher');
		$publisherIds = array_keys($rankingPagePublishersFilters);
		$publisherDataFromDb = $this->rankingModel->getPublisherData($publisherIds);
		$publisherDetails = array();
		foreach ($publisherDataFromDb as $key => $publisher) {
			$publisherDetails[$publisher['publisher_id']] = $publisher;
		}
		$publisherFilters = array();
		foreach ($rankingPagePublishersFilters as  $filterData) {
			$publisherId = $filterData['publisher_id'];
			$isCurrentSelectedPublisherValid = false;
			$publisherName = $publisherDetails[$publisherId]['name'];
			if($publisherId == $criteria['publisher_id']){
				$isCurrentSelectedPublisherValid = true;
			}
			
			if($isCurrentSelectedPublisherValid){
				$filterObject 			= new RankingPageFilter($publisherId, $publisherName, '', true, $filterData['result_count']);
				$currentSelectedFilter = $filterObject;
				$publisherFilters[] 	= $filterObject;
			} else {
				$filterObject 		= new RankingPageFilter($publisherId, $publisherName, '', false, $filterData['result_count']);
				$publisherFilters[] 	= $filterObject;
			}
			
			$filterObject 		= NULL;
		}
		if(empty($currentSelectedFilter)) {
			$currentSelectedFilter = reset($publisherFilters);
		}
		return array('publisherFilterList' => $publisherFilters, 'selectedPublisherFilter' => $currentSelectedFilter);
	}

	function _getResetAllFilter($filters, RankingPageRequest $rankingPageRequestObject, $baseCourses) {
		$defaultSpecialization = reset($filters['specialization']);
		if(!is_object($defaultSpecialization)) {
			$pageId = $rankingPageRequestObject->getPageId();
			$pageName = $rankingPageRequestObject->getPageName();
		}
		else {
			$pageId = $defaultSpecialization->getId();
			$pageName = $defaultSpecialization->getName();
		}
		$requestObject 	= new RankingPageRequest();
		$requestObject->setStreamId($rankingPageRequestObject->getStreamId());
		$requestObject->setSubstreamId($rankingPageRequestObject->getSubstreamId());
		$requestObject->setPageId($pageId);
		$requestObject->setPageName($pageName);
		$requestObject->setBaseCourseId($rankingPageRequestObject->getBaseCourseId());
		$url = $this->rankingURLManager->buildURL($requestObject,'url',$baseCourses);
		$resetAllFilter = new RankingPageFilter($pageId, $pageName, $url, true);
		return $resetAllFilter;
	}
}
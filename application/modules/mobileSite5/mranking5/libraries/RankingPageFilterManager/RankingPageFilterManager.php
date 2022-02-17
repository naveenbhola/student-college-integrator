<?php

class RankingPageFilterManager {
	
	private $rankingURLManager;
	private $locationRepo;
	private $rankingModel;
	private $skipStatusCheck = "false";
	private $_ci;
	
	public function __construct($_ci, $locationRepo, $rankingURLManager, $rankingModel){
		if(!empty($locationRepo) && !empty($rankingURLManager) && !empty($rankingModel)){
			$this->locationRepo			= $locationRepo;
			$this->rankingURLManager  	= $rankingURLManager;
			$this->rankingModel			= $rankingModel;
			$this->_ci = $_ci;
		}
		if(!empty($_REQUEST['skipstatuscheck']) && $_REQUEST['skipstatuscheck'] == "true"){
			$this->skipStatusCheck = $_REQUEST['skipstatuscheck'];
		}
	}
	
	public function getFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL, $filterTypes = array('city', 'state', 'exam', 'specialization', 'category')){
		$filters = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $filters;
		}
		
		foreach($filterTypes as $filterType){
			switch($filterType){
				case 'city':
					$cityFilters 		= $this->getCityFilters($rankingPageObject, $rankingPageRequestObject);
					$filters['city'] 	= $cityFilters;
					break;
				case 'state':
					$stateFilters 		= $this->getStateFilters($rankingPageObject, $rankingPageRequestObject);
					$filters['state'] 	= $stateFilters;
					break;
				case 'exam':
					$examFilters 		= $this->getExamFilters($rankingPageObject, $rankingPageRequestObject);
					$filters['exam'] 	= $examFilters;
					break;
				case 'specialization':
					$specializationFilters 		= $this->getSpecializationFilters($rankingPageObject, $rankingPageRequestObject);
					$filters['specialization'] 	= $specializationFilters;
					break;
				case 'category':
					$categoryFilters 		= $this->getCategoryFilters($rankingPageObject, $rankingPageRequestObject);
					$filters['category'] 	= $categoryFilters;
					break;
			}
		}
		return $filters;
	}
	
	/**
	 *@method : Applies filter values on the ranking page data and discard data that doesn't satisfies user selected filter.
	*/
	public function applyFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL) {
		if(empty($rankingPageObject)){
			return;
		}
		$rankingPageData = $rankingPageObject->getRankingPageData();
		if(empty($rankingPageData) || !is_array($rankingPageData)){
			return;
		}
			
		$validRankingPageData 	= array();
		foreach($rankingPageData as $pageData){
			$satisfiesExamFilter 	 = $this->satisfiesExamFilter($pageData, $rankingPageRequestObject);
			$satisfiesLocationFilter = $this->satisfiesLocationFilter($pageData, $rankingPageRequestObject);
			if($satisfiesExamFilter && $satisfiesLocationFilter){
				$validRankingPageData[] = $pageData;
			}
		}
		$rankingPageObject->setRankingPageData($validRankingPageData);
	}
	
	/**
	 *@method: check whether particular ranking page data row i;e course details satisfies the user selected filter or not.
	 *@return: If satisfies than returns true else returns false
	 */
	private function satisfiesLocationFilter(RankingPageData $rankingPageData, RankingPageRequest $rankingPageRequestObject = NULL) {
		$satisfiesLocationFilter = false;
		if(empty($rankingPageRequestObject) || empty($rankingPageData)){
			return $satisfiesLocationFilter;
		}
		$requestCityId 		= $rankingPageRequestObject->getCityId();
		$requestStateId 	= $rankingPageRequestObject->getStateId();
		$requestCountryId 	= $rankingPageRequestObject->getCountryId();
		
		$filterLocationType 	= "";
		$filterLocationId 		= "";
		if(!empty($requestCityId)){
			$filterLocationType 	= "city";
			$filterLocationId 		= $requestCityId;
		} else if(!empty($requestStateId)){
			$filterLocationType 	= "state";
			$filterLocationId 		= $requestStateId;
		} else if(!empty($requestCountryId)){
			$filterLocationType 	= "country";
			$filterLocationId 		= $requestCountryId;
		} else {
			$filterLocationType 	= "country";
			$filterLocationId 		= 2;
		}
		
		$locationId = "";
		switch($filterLocationType){
			case 'city':
				$locationId = $rankingPageData->getCityId();
				break;
			case 'state':
				$locationId = $rankingPageData->getStateId();
				break;
			case 'country':
				$locationId = $rankingPageData->getCountryId();
				break;
		}
		if($locationId == $filterLocationId){
			$satisfiesLocationFilter = true;
		}
		
		return $satisfiesLocationFilter;
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
	private function getCityFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$cityFiltersList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $cityFiltersList;
		}
		
		$rankingPageData  = $rankingPageObject->getRankingPageData();
		if(empty($rankingPageData) || !is_array($rankingPageData)){
			return $cityFiltersList;
		}
		
		$requestPageId 		= $rankingPageRequestObject->getPageId();
		$requestPageName 	= $rankingPageObject->getName();
		$requestExamId 		= $rankingPageRequestObject->getExamId();
		$requestExamName 	= $rankingPageRequestObject->getExamName();
		$requestCityId		= $rankingPageRequestObject->getCityId();
		$requestCityName	= $rankingPageRequestObject->getCityName();
		
		$defaultFilter 						= NULL;
		$currentlySelectedFilter 			= NULL;
		$encounteredIds	  					= array();
		$currentlySelectedCityIsValid 		= false;
		foreach($rankingPageData as $pageData){
			$currentlySelectedCityIsValid 	=  false;
			$cityId 		= $pageData->getCityId();
			$cityName 		= $pageData->getCityName();
			if(in_array($cityId, $encounteredIds) || empty($cityId) || empty($cityName)){
				continue;
			}
			if($cityId == $requestCityId){
				$currentlySelectedCityIsValid = true;
			}
			
			$requestObject 	= new RankingPageRequest();
			$requestObject->setPageId($requestPageId);
			$requestObject->setPageName($requestPageName);
			$requestObject->setExamId($requestExamId);
			$requestObject->setExamName($requestExamName);
			$requestObject->setCityId($cityId);
			$requestObject->setCityName($cityName);
			
			$url = $this->rankingURLManager->buildURL($requestObject);
			if($currentlySelectedCityIsValid){ //If this city is currently selected by user, than this should be highlighted
				$filterObject 	= new RankingPageFilter($cityId, $cityName, $url, $currentlySelectedCityIsValid);
				$currentlySelectedFilter = $filterObject;
			} else {
				$filterObject 	= new RankingPageFilter($cityId, $cityName, $url, false);
				$cityFiltersList[] = $filterObject;
			}
			$requestObject = NULL;
			$filterObject  = NULL;
			$encounteredIds[] = $cityId;
		}
			
		//Generate default 'All' filter
		$defaultFilterIsSelected = false;
		if(empty($currentlySelectedFilter)){
			$defaultFilterIsSelected = true;
		}
		$defaultRequestObject 	= new RankingPageRequest();
		$defaultRequestObject->setPageId($requestPageId);
		$defaultRequestObject->setPageName($requestPageName);
		$defaultRequestObject->setExamId($requestExamId);
		$defaultRequestObject->setExamName($requestExamName);
		$defaultFilter = $this->getDefaultFilter($defaultRequestObject, 'city', $defaultFilterIsSelected);
		
		uasort($cityFiltersList, 'stringCompareFN'); //Sort alphabatically all the city filters
		if(!empty($currentlySelectedFilter)){
			array_unshift($cityFiltersList, $currentlySelectedFilter); //Push currently selected filter on top of list(Requirement: Currently selected filter should be second in the filters list).
		}
		if(!empty($defaultFilter)){
			array_unshift($cityFiltersList, $defaultFilter); //Push 'all' default filter on top of list(Requirement: Default 'All' filter should be at the top).
		}
		return $cityFiltersList;
	}
	
	/**
	 *@method : Generated State filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter objects
	 *@example output:
	 *Array (
			[1] => RankingPageFilter Object
			(
				[id:RankingPageFilter:private] => 100
				[name:RankingPageFilter:private] => Andhra Pradesh
				[url:RankingPageFilter:private] => /top-mba-colleges-in-andhra-pradesh-ranking-12-0-100-0-0
				[selected:RankingPageFilter:private] => 1
			)
			[6] => RankingPageFilter Object
				(
					[id:RankingPageFilter:private] => 128
					[name:RankingPageFilter:private] => Delhi
					[url:RankingPageFilter:private] => /top-mba-colleges-in-delhi-ranking-12-0-128-0-0
					[selected:RankingPageFilter:private] => 
				)
		)
	*/
	private function getStateFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$stateFiltersList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $stateFiltersList;
		}
		
		$rankingPageData  = $rankingPageObject->getRankingPageData();
		if(empty($rankingPageData) || !is_array($rankingPageData)){
			return $stateFiltersList;
		}
		
		$requestPageId 		= $rankingPageRequestObject->getPageId();
		$requestPageName 	= $rankingPageObject->getName();
		$requestExamId 		= $rankingPageRequestObject->getExamId();
		$requestExamName 	= $rankingPageRequestObject->getExamName();
		$requestStateId		= $rankingPageRequestObject->getStateId();
		$requestStateName	= $rankingPageRequestObject->getStateName();
		
		$encounteredIds	  = array();
		$isCurrentSelectedStateValid = false;
		$currentSelectedFilter 		 = NULL;
		
		foreach($rankingPageData as $pageData){
			$isCurrentSelectedStateValid = false;
			$stateId 		= $pageData->getStateId();
			$stateName 		= $pageData->getStateName();
			if(in_array($stateId, $encounteredIds) || empty($stateId) || empty($stateName)){
				continue;
			}
			if($stateId == $requestStateId){
				$isCurrentSelectedStateValid = true;
			}
			$requestObject 	= new RankingPageRequest();
			$requestObject->setPageId($requestPageId);
			$requestObject->setPageName($requestPageName);
			$requestObject->setExamId($requestExamId);
			$requestObject->setExamName($requestExamName);
			$requestObject->setStateId($stateId);
			$requestObject->setStateName($stateName);
			
			$url = $this->rankingURLManager->buildURL($requestObject);
			if($isCurrentSelectedStateValid){
				$filterObject 			 = new RankingPageFilter($stateId, $stateName, $url, true);
				$currentSelectedFilter 	 = $filterObject;
			} else {
				$filterObject 		= new RankingPageFilter($stateId, $stateName, $url, false);
				$stateFiltersList[] = $filterObject;
			}
			$requestObject 		= NULL;
			$filterObject 		= NULL;
			$encounteredIds[] 	= $stateId;
		}
		
		uasort($stateFiltersList, 'stringCompareFN'); //Sort alphabatically all the state filters
		if(!empty($currentSelectedFilter)){
			array_unshift($stateFiltersList, $currentSelectedFilter); //Push currently selected filter on top of list(Requirement: Currently selected filter should be second in the filters list).
		}
		return $stateFiltersList;
	}
	
	/**
	 *@method : Generated Exam filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter objects
	 *@example output:
	 *Array (
			 [0] => RankingPageFilter Object
			(
				[id:RankingPageFilter:private] => 0
				[name:RankingPageFilter:private] => All
				[url:RankingPageFilter:private] => /top-mba22-colleges-in-india-ranking-12-2-0-0-0
				[selected:RankingPageFilter:private] => 
			)
			[1] => RankingPageFilter Object
				(
					[id:RankingPageFilter:private] => 305
					[name:RankingPageFilter:private] => CAT
					[url:RankingPageFilter:private] => /top-mba22-colleges-in-india-accepting-cat-ranking-12-2-0-0-305
					[selected:RankingPageFilter:private] => 1
				)
	)
	*/
	private function getExamFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$examFilterList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $examFilterList;
		}
		
		$rankingPageData  = $rankingPageObject->getRankingPageData();
		if(empty($rankingPageData) || !is_array($rankingPageData)){
			return $examFilterList;
		}
		
		$requestPageId 		= $rankingPageRequestObject->getPageId();
		$requestPageName 	= $rankingPageObject->getName();
		$requestCityId 		= $rankingPageRequestObject->getCityId();
		$requestCityName 	= $rankingPageRequestObject->getCityName();
		$requestStateId 	= $rankingPageRequestObject->getStateId();
		$requestStateName 	= $rankingPageRequestObject->getStateName();
		$requestCountryId 	= $rankingPageRequestObject->getCountryId();
		$requestExamId      = $rankingPageRequestObject->getExamId();
		$requestExamName    = $rankingPageRequestObject->getExamName();
		
		$encounteredIds	  			= array();
		$defaultFilter 				= NULL;
		$currentSelectedFilter 		= NULL;
		$isCurrentSelectedExamValid = false;
		foreach($rankingPageData as $pageData){
			$exams = $pageData->getExams();
			if(empty($exams) || !is_array($exams)){
				continue;
			}
			foreach($exams as $exam){
				$isCurrentSelectedExamValid = false;
				$examId 	= $exam['id'];
				$examName 	= $exam['name'];
				if(in_array($examId, $encounteredIds) || empty($examId) || empty($examName)){
					continue;
				}
				if($examId == $requestExamId){
					$isCurrentSelectedExamValid = true;
				}
				$requestObject 	= new RankingPageRequest();
				$requestObject->setPageId($requestPageId);
				$requestObject->setPageName($requestPageName);
				$requestObject->setCityId($requestCityId);
				$requestObject->setCityName($requestCityName);
				$requestObject->setStateId($requestStateId);
				$requestObject->setStateName($requestStateName);
				$requestObject->setCountryId($requestCountryId);
				$requestObject->setCountryName($requestCountryName);
				$requestObject->setExamId($examId);
				$requestObject->setExamName($examName);
				
				$url = $this->rankingURLManager->buildURL($requestObject); 
				if($isCurrentSelectedExamValid){
					$filterObject 			= new RankingPageFilter($examId, $examName, $url, true);
					$currentSelectedFilter 	= $filterObject;
				} else {
					$filterObject 		= new RankingPageFilter($examId, $examName, $url);
					$examFilterList[] 	= $filterObject;
				}
				$requestObject 		= NULL;
				$filterObject 		= NULL;
				$encounteredIds[] 	= $examId;
			}
		}
			
		$defaultIsSelectedParam = false;
		if(empty($currentSelectedFilter)){
			$defaultIsSelectedParam = true;
		}
		// Default filter
		$defaultRequestObject 	= new RankingPageRequest();
		$defaultRequestObject->setPageId($requestPageId);
		$defaultRequestObject->setPageName($requestPageName);
		$defaultRequestObject->setCityId($requestCityId);
		$defaultRequestObject->setCityName($requestCityName);
		$defaultRequestObject->setStateId($requestStateId);
		$defaultRequestObject->setStateName($requestStateName);
		$defaultRequestObject->setCountryId($requestCountryId);
		$defaultRequestObject->setCountryName($requestCountryName);
		$defaultFilter = $this->getDefaultFilter($defaultRequestObject, 'exam', $defaultIsSelectedParam);
		
		uasort($examFilterList, 'stringCompareFN');
		if(!empty($currentSelectedFilter)){
			array_unshift($examFilterList, $currentSelectedFilter);
		}
		if(!empty($defaultFilter)){
			array_unshift($examFilterList, $defaultFilter);
		}
		return $examFilterList;
	}
	
	/*
	 *@method : Generated specialization filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter objects
	 *@example output: 
	 [0] => RankingPageFilter Object
        (
            [id:RankingPageFilter:private] => 12
            [name:RankingPageFilter:private] => MBA22
            [url:RankingPageFilter:private] => /top-mba22-colleges-in-india-ranking-12-2-0-0-0
            [selected:RankingPageFilter:private] => 1
        )

    [1] => RankingPageFilter Object
        (
            [id:RankingPageFilter:private] => 18
            [name:RankingPageFilter:private] => Full Time MBA/PGDM IT
            [url:RankingPageFilter:private] => /top-full-time-mba-pgdm-it-colleges-in-india-ranking-18-2-0-0-0
            [selected:RankingPageFilter:private] => 
        )
	*/
	private function getSpecializationFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$specializationFilterList = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $specializationFilterList;
		}
		
		$requestRankingPageId 				= $rankingPageObject->getId();
		$requestRankingPageSubCategoryId 	= $rankingPageObject->getSubCategoryId();
		$requestRankingPageCategoryId 		= $rankingPageObject->getCategoryId();
		
		$filterWithSameSubcat 		= array();
		$filterWithSpecialization 	= array();
		$params = array();
		$params['status'] =	array('live');
		if($this->skipStatusCheck == "true"){
			$params['status'] =	array('live', 'disable', 'draft');
		}
		$params['subcategory_id'] 	= 	$requestRankingPageSubCategoryId;
		$params['category_id']		=	$requestRankingPageCategoryId;
		$rankingPages = $this->rankingModel->getRankingPages($params); //Get ranking pages with currently rank page's subcat and category id.
		
		/*
		 More than two rank pages can have same subcat and no specialization, so separate out ranking pages with same subcategory and
		 ranking page with subcat and non empty specializations
		*/
		$this->_ci->load->builder('RankingPageBuilder', 'ranking');
		$rankingPageRepo  	= RankingPageBuilder::getRankingPageRepository();
		
		$rankingPageWithSameSubCat 		= array();
		$rankingPageWithSpecialization 	= array();
		foreach($rankingPages as $rankingPage){
			$tempArr = array();
			$tempArr['id'] 	 = $rankingPage['id'];
			$tempArr['name'] = $rankingPage['ranking_page_text'];
			if($rankingPage['status'] == "live"){
				if(empty($rankingPage['specialization_id'])){
					$rankingPageWithSameSubCat[] = $tempArr;
				} else {
					$rankingPageWithSpecialization[] = $tempArr;
				}	
			}
		}
			
		$pageList = array();
		$pageList['subcat'] 		= $rankingPageWithSameSubCat;
		$pageList['specialization'] = $rankingPageWithSpecialization;
		
		/*
		 Requirement:
		 Generate Filter objects for both type of ranking pages i;e subcat pages and specialization pages filters and don't maintain the
		 currently selected city/state/exam
		*/
		$selectedPageFilter 	= NULL;
		$selectedPageFilterType = "";
		foreach($pageList as $key => $data){
			foreach($data as $rankingPage){
				$currentSelectedFilter 	= false;
				$rankingPageId 			= $rankingPage['id'];
				$rankingPageName 		= $rankingPage['name'];
				
				if($requestRankingPageId == $rankingPageId){
					$currentSelectedFilter = true;
				}
				
				$requestObject = new RankingPageRequest();
				$requestObject->setPageId($rankingPageId);
				$requestObject->setPageName($rankingPageName);
				$requestObject->setCountryId(2);
				$requestObject->setCountryName("India");
				$url = $this->rankingURLManager->buildURL($requestObject);
				
				/*
				  Generate filter and push it into its bucket, spare the currently selected filter as we are going to push it later
				  in the bucket.
				*/
				if($currentSelectedFilter){
					$filterObject = new RankingPageFilter($rankingPageId, $rankingPageName, $url, true);
					$selectedPageFilter 	= $filterObject;
					$selectedPageFilterType = $key;
				} else {
					$filterObject = new RankingPageFilter($rankingPageId, $rankingPageName, $url, false);
				}
				
				if(!$currentSelectedFilter){
					if($key == "subcat"){
						$filterWithSameSubcat[] = $filterObject;
					} else if($key == "specialization"){
						$filterWithSpecialization[] = $filterObject;
					}
				}
			}
		}
		
		/*
		 Requirement: Show all the subcat filters first than the currently selected one and than specialization filters in alphabatecal order
		*/
		uasort($filterWithSameSubcat, 'stringCompareFN');
		uasort($filterWithSpecialization, 'stringCompareFN');
		if(!empty($selectedPageFilterType) && !empty($selectedPageFilter)){
			if($selectedPageFilterType == "subcat"){
				array_unshift($filterWithSameSubcat, $selectedPageFilter);
			} else if($selectedPageFilterType == "specialization"){
				array_unshift($filterWithSpecialization, $selectedPageFilter);
			}
		}
		$specializationFilterList = array_merge($filterWithSameSubcat, $filterWithSpecialization);
		return $specializationFilterList;
	}
	
	/*
	 *@method : Generated category filters based on the ranking page data and current request page object(url params)
	 *@return : List of RankingPageFilter objects
	*/
	private function getCategoryFilters(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL) {
		$categoryFilters = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $categoryFilters;
		}
		
		$requestRankingPageId 				= $rankingPageObject->getId();
		$requestRankingPageSubCategoryId 	= $rankingPageObject->getSubCategoryId();
		$requestRankingPageCategoryId 		= $rankingPageObject->getCategoryId();
		
		/*
		 Get ranking pages with same category but not with specializations
		*/
		$params = array();
		$params['specialization_id'] = 0;
		$params['category_id'] 		 = $requestRankingPageCategoryId;
		$params['status']	   		 = array('live');
		if($this->skipStatusCheck == "true"){
			$params['status'] =	array('live', 'disable', 'draft');
		}
		
		$rankingPages = array();
		$data = $this->rankingModel->getRankingPages($params);
		foreach($data as $page){
			//Skip pages which belongs to same subcategory as current ranking page.
			if($page['specialization_id'] == 0 && $page['subcategory_id'] != $requestRankingPageSubCategoryId && $page['id'] != $requestRankingPageId && $page['status'] == "live"){
				$rankingPages[] = $page;
			}
		}
			
		foreach($rankingPages as $page) {
			$rankingPageId 		= $page['id'];
			$rankingPageName 	= $page['ranking_page_text'];
			$clonedRequestObject = clone $rankingPageRequestObject;
			$clonedRequestObject->setPageId($rankingPageId);
			$clonedRequestObject->setPageName($rankingPageName);
			$url 			= $this->rankingURLManager->buildURL($clonedRequestObject);
			$filterObject 	= new RankingPageFilter($rankingPageId, $rankingPageName, $url, false);
			$categoryFilters[] = $filterObject;
			$filterObject 			= NULL;
			$clonedRequestObject 	= NULL;
		}
		uasort($categoryFilters, 'stringCompareFN');
		return $categoryFilters;
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
					$filterObject 	= new RankingPageFilter(2, 'All', $url, $defaultSelected);
					break;
				case 'exam':
					$defaultRequestObject->setExamId(0);
					$defaultRequestObject->setExamName('All');
					$url = $this->rankingURLManager->buildURL($defaultRequestObject);
					$filterObject 	= new RankingPageFilter(0, 'All', $url, $defaultSelected);
					break;
			}
			$defaultRequestObject = NULL;
		}
		return $filterObject;
	}
}

<?php

class RankingURLManager {
	
	private $rankingModel;
	private $locationRepo;
	public function __construct($locationBuilder, $rankingModel){
		if(!empty($locationBuilder) && !empty($rankingModel)){
			$this->locationRepo	= $locationBuilder->getLocationRepository();
			$this->rankingModel = $rankingModel;
		}
	}
	
	/**
	 *@method: Take the URL identifier e.g 12-2-0-0-0 and extract ranking page id, country id, state id, city id, exam id from identifier and
	 *Create a request object with the URL param values
	 */
	public function getRankingPageRequest($urlIdentifierParam = NULL) {
		$rankingPageRequest = false;
		$urlIdentifier = $urlIdentifierParam;
		if(empty($urlIdentifier)){
			return $rankingPageRequest;
		}
		$urlIdentifier = trim($urlIdentifier, "-");
		$urlParams 	   = explode("-", $urlIdentifier);
		$params 	   = array();
		if(count($urlParams) >= 5){
			if(
			   is_numeric($urlParams[0]) &&
			   is_numeric($urlParams[1]) &&
			   is_numeric($urlParams[2]) &&
			   is_numeric($urlParams[3]) &&
			   is_numeric($urlParams[4]) 
			){
				$params['rankingPageId'] 	= (int)$urlParams[0];
				$params['countryId'] 	   	= (int)$urlParams[1];
				$params['stateId'] 	   		= (int)$urlParams[2];
				$params['cityId'] 	   		= (int)$urlParams[3];
				$params['examId'] 	   	   	= (int)$urlParams[4];
				$rankingPageRequest = $this->getRankingPageRequestFromDataArray($params);	
			}
		}
		return $rankingPageRequest;
	}
	
	/**
	 * @method: This function takes a data array with keys like cityId, stateId, countryId, rankingPageId etc.
	 * If the values like statename, cityname etc are empty but their associated ids are non empty than this method will
	 * try to fetch their values and make a request object.
	 */
	public function getRankingPageRequestFromDataArray($paramArray = array()){
		$rankingPageId 		= $paramArray['rankingPageId'];
		$rankingPageRequest = false;
		if(empty($rankingPageId)){ //Only RankingPageId is must for a page to display, other params can be empty or default
			return $rankingPageRequest;
		}
		
		$cityId 			= $paramArray['cityId'];
		$stateId 			= $paramArray['stateId'];
		$countryId 			= $paramArray['countryId'];
		$examId 			= $paramArray['examId'];
		$cityName   		= $paramArray['cityName'];
		$stateName  		= $paramArray['stateName'];
		$countryName   		= $paramArray['countryName'];
		$examName   		= $paramArray['examName'];
		$rankingPageName    = $paramArray['rankingPageName'];
		
		$rankingPageRequest = new RankingPageRequest();
		$rankingPageRequest->setPageId($rankingPageId);
		$rankingPageRequest->setCountryId($countryId);
		$rankingPageRequest->setStateId($stateId);
		$rankingPageRequest->setCityId($cityId);
		$rankingPageRequest->setExamId($examId);
		
		if(!empty($rankingPageId)){
			if(empty($rankingPageName)){
				$params = array();
				$params['id'] 			 	= $rankingPageId;
				$params['status']		 	= array('live');
				if(!empty($_REQUEST['skipstatuscheck']) && $_REQUEST['skipstatuscheck'] == "true"){
					$params['status']		 	= array('live', 'disable', 'draft');
				}
				$rankingPages 				= $this->rankingModel->getRankingPages($params);
				if(!empty($rankingPages)){
					$rankingPageName 	= $rankingPages[0]['ranking_page_text'];
				}
			}
			$rankingPageRequest->setPageName($rankingPageName);
		}
		
		if(!empty($cityId)){
			if(empty($cityName)){
				$cityObject = $this->locationRepo->findCity($cityId);
				$cityName   = $cityObject->getName();
			}
			$rankingPageRequest->setCityName($cityName);
		}
		
		if(!empty($stateId)){
			if(empty($stateName)){
				$stateObject = $this->locationRepo->findState($stateId);
				$stateName   = $stateObject->getName();
			}
			$rankingPageRequest->setStateName($stateName);
		}
		
		if(!empty($countryId)){
			if(empty($countryName)){
				$countryObject = $this->locationRepo->findCountry($countryId);
				$countryName   = $countryObject->getName();
			}
			$rankingPageRequest->setCountryName($countryName);
		}
		
		if(!empty($examId)){
			if(empty($examName)){
				$examDetails = $this->rankingModel->getExamById($examId);
				if(!empty($examDetails)){
					$examName 	 = $examDetails['acronym'];
				}
			}
			$rankingPageRequest->setExamName($examName);
		}
		
		return $rankingPageRequest;
	}
	
	/**
	 * @method: This function corrects the ranking page request with filter values. It handles the wrongly typed URL identifier value.
	 * This function corrects the current ranking page request object if there is any anomaly in the request object
	*/
	public function correctRankingPageRequestUsingFilterValues(RankingPageRequest $rankingPageRequest, $filters = array()){
		if(empty($rankingPageRequest) || empty($filters)){
			return;
		}
		$requestCityId  	= $rankingPageRequest->getCityId();
		$requestStateId 	= $rankingPageRequest->getStateId();
		$requestExamId  	= $rankingPageRequest->getExamId();
		$requestCountryId 	= $rankingPageRequest->getCountryId();
		
		$cityFilters 	= $filters['city'];
		$stateFilters 	= $filters['state'];
		$examFilters 	= $filters['exam'];
		
		$citySelectedFilter = $this->getSelectedFilterFromFiltersList($cityFilters);
		if(!empty($citySelectedFilter)){
			$selectedCityName 	= $citySelectedFilter->getName();
			$selectedCityId 	= $citySelectedFilter->getId();
			if($selectedCityId != $requestCityId){
				if(strtolower($selectedCityName) == 'all'){
					$rankingPageRequest->setCityId(0);
					$rankingPageRequest->setCityName("");
				} else {
					$cityObject = $this->locationRepo->findCity($selectedCityId);
					$rankingPageRequest->setCityName($cityObject->getName());
					$rankingPageRequest->setCityId($selectedCityId);
				}
			}	
		} else {
			$rankingPageRequest->setCityId(0);
			$rankingPageRequest->setCityName("");
		}
		
		$stateFilters = $filters['state'];
		$stateSelectedFilter = $this->getSelectedFilterFromFiltersList($stateFilters);
		if(!empty($stateSelectedFilter)){
			$selectedStateName 	= $stateSelectedFilter->getName();
			$selectedStateId 	= $stateSelectedFilter->getId();
			if($selectedStateId != $requestStateId){
				if(strtolower($selectedStateName) == 'all'){
					$rankingPageRequest->setStateId(0);
					$rankingPageRequest->setStateName("");
				} else {
					$stateObject = $this->locationRepo->findState($selectedStateId);
					$rankingPageRequest->setStateName($stateObject->getName());
					$rankingPageRequest->setStateId($selectedStateId);
				}
			}	
		} else {
			$rankingPageRequest->setStateId(0);
			$rankingPageRequest->setStateName("");
		}
		
		if(!empty($requestCountryId)){
			if($requestCountryId != 2){
				$rankingPageRequest->setCountryId(2);
				$rankingPageRequest->setCountryName('India');
			}
		}
		
		$tempRequestCityId 		= $rankingPageRequest->getCityId();
		$tempRequestStateId 	= $rankingPageRequest->getStateId();
		$tempRequestCountryId 	= $rankingPageRequest->getCountryId();
		
		if(empty($tempRequestCityId) && empty($tempRequestStateId) && empty($tempRequestCountryId)){ //Fail-Safe
			$rankingPageRequest->setCountryId(2);
			$rankingPageRequest->setCountryName('India');
		}
		
		$examFilters = $filters['exam'];
		$examSelectedFilter = $this->getSelectedFilterFromFiltersList($examFilters);
		if(!empty($examSelectedFilter)){
			$selectedExamName 	= $examSelectedFilter->getName();
			$selectedExamId 	= $examSelectedFilter->getId();
			if($selectedExamId != $requestExamId){
				if(strtolower($selectedExamName) == 'all'){
					$rankingPageRequest->setExamId(0);
					$rankingPageRequest->setExamName("");
				} else {
					$examDetails = $this->rankingModel->getExamById($selectedExamId);
					if(!empty($examDetails)){
						$rankingPageRequest->setExamName($examDetails['acronym']);
						$rankingPageRequest->setExamId($selectedExamId);
					} else {
						$rankingPageRequest->setExamId(0);
						$rankingPageRequest->setExamName("");
					}
				}
			}	
		} else {
			$rankingPageRequest->setExamId(0);
			$rankingPageRequest->setExamName("");
		}
	}
	
	private function getSelectedFilterFromFiltersList($filters = array()){
		$selectedFilter = NULL;
		if(!empty($filters)){
			foreach($filters as $filter){
				$selected = $filter->isSelected();
				if($selected == true){
					$selectedFilter = $filter;
					break;
				}
			}	
		}
		return $selectedFilter;
	}
	
	/**
	 * @method: This function builds URL based on the ranking page request
	 * @param $returnType : It takes two values "url" and "urltitle". default is url
	 * @return:
	 * if returnType is url it will only return url
	 * if returnType is urltitle, it will return SEO friendly URL + SEO friendly Title
	*/
	public function buildURL(RankingPageRequest $rankingPageRequest, $returnType = "url"){
		$url 		 = "";
		$seoUrlTitle = "";
		if(empty($rankingPageRequest)){
			if($returnType == "urltitle"){
				return array('url' => $url, 'title' => $seoUrlTitle);
			} else {
				return $url;
			}
		}
		$rankingPageId 		= $rankingPageRequest->getPageId();
		$rankingPageName 	= $rankingPageRequest->getPageName();
		$cityId				= $rankingPageRequest->getCityId();
		$cityName			= $rankingPageRequest->getCityName();
		$stateId			= $rankingPageRequest->getStateId();
		$stateName			= $rankingPageRequest->getStateName();
		$countryId			= $rankingPageRequest->getCountryId();
		$countryName		= $rankingPageRequest->getCountryName();
		$examId				= $rankingPageRequest->getExamId();
		$examName			= $rankingPageRequest->getExamName();
		$urlString = "";
		$urlParamsIdentifierString = "";
		$replaceCharacters = array(" ", "/");
		$seoURLString = "";
		if(!empty($rankingPageId) && !empty($rankingPageName)){
			$rawRankingPageName = $rankingPageName;
			$rankingPageName = handleMBASpecialCase($rankingPageName);
			$rankingPageName = sanitizeTextForURL($rankingPageName);
			
			$seoURLString = "Top ". $rawRankingPageName . " colleges";
			$urlString = "top-". trim($rankingPageName, "-") . "-colleges";
			$urlParamsIdentifierString = "rankingpage-" . $rankingPageId;
			if(!empty($cityId) && !empty($cityName)){
				$rawCityName = $cityName;
				$cityName = sanitizeTextForURL($cityName);
				
				$seoURLString .= " in " . $rawCityName;
				$urlString .= "-in-".ltrim($cityName, "-");
				$urlParamsIdentifierString .= "-0-0-" .$cityId;
			} else if(!empty($stateId) && !empty($stateName)){
				$rawStateName = $stateName;
				$stateName = sanitizeTextForURL($stateName);
				
				$seoURLString .= " in " . $rawStateName;
				$urlString .= "-in-".ltrim($stateName, "-");
				$urlParamsIdentifierString .= "-0-" . $stateId . "-0";
			} else if(!empty($countryId) && !empty($countryName)){
				$rawCountryName = $countryName;
				$countryName = sanitizeTextForURL($countryName);
				
				$seoURLString .= " in " . $rawCountryName;
				$urlString .= "-in-".ltrim($countryName, "-");
				$urlParamsIdentifierString .= "-" . $countryId . "-0-0";
			} else {
				$seoURLString .= " in India";
				$urlString .= "-in-India";
				$urlParamsIdentifierString .= "-2-0-0";
			}
			
			if(!empty($examId) && !empty($examName)){
				$rawExamName = $examName;
				$examName 	 = sanitizeTextForURL($examName);
				
				$seoURLString .= " accepting ". $rawExamName;
				$urlString .= "-accepting-".ltrim($examName, "-")."-score";
				$urlParamsIdentifierString .= "-" . $examId;
			} else {
				$urlParamsIdentifierString .= "-0";
			}
		}
		
		$seoUrlTitle = trim($seoURLString);
		if(!empty($urlString) && !empty($urlParamsIdentifierString)){
			$url = $urlString . "-" . $urlParamsIdentifierString;
			$url = "/".strtolower($url);
			$url = trim($url);
		}
		if($returnType == "urltitle"){
			return array('url' => $url, 'title' => $seoUrlTitle);
		} else {
			return $url;
		}
	}
	
	public function getRankingPageMetaData(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$metaDetails = array("title" => "", "description" => "");
		if(empty($rankingPageObject)){
			return $metaDetails;
		}
		$rankingPageId = $rankingPageObject->getId();
		$metaDetailsFromDB = $this->rankingModel->getRankingPageMetaDetails($rankingPageId); //Check if the meta details exists in DB or not. If yes than return it.
		$metaDetailsFromDB = $this->parseLocationAndExamFromMetaDetails($metaDetailsFromDB, $rankingPageRequestObject);
		if(!empty($metaDetailsFromDB)){
			return $metaDetailsFromDB;
		}
		$rankingPageName 	= $rankingPageObject->getName();
		$cityName			= $rankingPageRequestObject->getCityName();
		$stateName 			= $rankingPageRequestObject->getStateName();
		$countryName 		= $rankingPageRequestObject->getCountryName();
		$examName 			= $rankingPageRequestObject->getExamName();
		
		$rankingPageSubCategoryId = $rankingPageObject->getSubCategoryId();
		$rankingPageSpecializationId = $rankingPageObject->getSpecializationId();
		
		$locationName = "";
		if(!empty($cityName)){
			$locationName = $cityName;
		} else if(!empty($stateName)){
			$locationName 	= $stateName;
		} else if(!empty($countryName)){
			$locationName 	= $countryName;
		}
		
		$pageType = "subcategory_page";
		if(!empty($rankingPageSpecializationId)){
			$pageType = "specialization_page";
		}
		$rankingPageTextToBeUsed = handleMBASpecialCase($rankingPageName);
		$title = "Top " . $rankingPageTextToBeUsed . " colleges/courses";
		if($pageType == "subcategory_page"){
			$title = "Top " . $rankingPageTextToBeUsed . " colleges";
		}
		
		$description = "Find Top " . $rankingPageTextToBeUsed . " colleges";
		if($pageType == "subcategory_page"){
			$description = "Search for Top " . $rankingPageTextToBeUsed . " colleges";
		}
		
		if(!empty($locationName)){
			$title .= " in " . $locationName;
			$description .= " in " . $locationName;
			if(empty($examName)){
				$description .= ".";
				$title .= ".";
			}
		}
		if(!empty($examName)){
			$title .= " accepting " . $examName . " score.";
			$description .= " accepting " . $examName . " score.";
		}
		
		if($pageType == "subcategory_page"){
			if(!empty($locationName)){
				$description .= " Get a list of Top 10, 20 " . $rankingPageTextToBeUsed . " Colleges in " . $locationName . " categorized by Rank, location, specializations, course fees and cutoff.";
			} else {
				$description .= " Get a list of Top 10, 20 " . $rankingPageTextToBeUsed . " Colleges categorized by Rank, location, specializations, course fees and cutoff.";
			}
		} else {
			$description .= " Get a list of Best " . $rankingPageTextToBeUsed . " Institutes with its curriculum, course fees, cutoff and ranks.";
		}
		$metaDetails["title"] 		= $title;
		$metaDetails["description"] = $description;
		
		return $metaDetails;
	}
	
	public function getCurrentPageURL(RankingPageRequest $rankingPageRequest = NULL){
		if(empty($rankingPageRequest)){
			return '';
		}
		$url = $this->buildURL($rankingPageRequest);
		return SHIKSHA_HOME . "/". ltrim($url, "/");
	}
	
	public function getRankingPageURLByRankingPageId($rankingPageId = NULL, $rankingPageName = NULL){
		if(empty($rankingPageId) || empty($rankingPageName)){
			return "";
		}
		$param = array();
		$param['rankingPageId'] 	= $rankingPageId;
		$param['rankingPageName'] 	= $rankingPageName;
		$rankingPageRequest = $this->getRankingPageRequestFromDataArray($param);
		$url = $this->buildURL($rankingPageRequest);
		return SHIKSHA_HOME . "/". ltrim($url, "/");
	}
	
	private function parseLocationAndExamFromMetaDetails($metaDetailsFromDB = NULL, $rankingPageRequestObject = NULL) {
		if(empty($rankingPageRequestObject) || empty($rankingPageRequestObject)){
			return false;
		}
		
		$cityName			= $rankingPageRequestObject->getCityName();
		$stateName 			= $rankingPageRequestObject->getStateName();
		$countryName 		= $rankingPageRequestObject->getCountryName();
		$examName 			= $rankingPageRequestObject->getExamName();
		
		$locationName = "";
		if(!empty($cityName)){
			$locationName = $cityName;
		} else if(!empty($stateName)){
			$locationName 	= $stateName;
		} else if(!empty($countryName)){
			$locationName 	= $countryName;
		}
		
		$updatedMetaDetails = false;
		if(!empty($examName)){
			$title 		 = $metaDetailsFromDB['title_exam'];
			$description = $metaDetailsFromDB['description_exam'];
		} else {
			$title 		 = $metaDetailsFromDB['title'];
			$description = $metaDetailsFromDB['description'];
		}
		if(!empty($title) && !empty($description)){
			$title = str_ireplace("<location>", $locationName, $title);
			$title = str_ireplace("<examname>", $examName, $title);
			
			$description = str_ireplace("<location>", $locationName, $description);
			$description = str_ireplace("<examname>", $examName, $description);
			
			$updatedMetaDetails['title'] = $title;
			$updatedMetaDetails['description'] = $description;
		}
		return $updatedMetaDetails;
	}
	
}
<?php

define("ENT_MAX_LINKS_LIMIT"		     , 4);
define("RANKING_MAX_LINKS_COURSE_LIMIT"  , 2);

define("INDIA_ONLY"			             , 0);
define("INDIA_EXAM_ONLY"		         , 1);
define("INDIA_SPECIALIZATION_ONLY"		 , 2);
define("INDIA_EXAM_SPECIALIZATION_ONLY"	 , 3);
define("LOCATION_ONLY"			         , 4);
define("EXAM_ONLY"			             , 5);
define("SPECIALIZATION_ONLY"		     , 6);
define("EXAM_SPECIALIZATION_ONLY"	     , 7);

class RankingPageRelatedLib {
	
	private $rankingURLManager;
	private $locationRepo;
	private $rankingModel;
	private $_ci;
	
	public function __construct($_ci, $locationRepo, $rankingURLManager, $rankingModel){
		if(!empty($locationRepo) &&
		   !empty($rankingURLManager) &&
		   !empty($rankingModel)){
			$this->locationRepo		 = $locationRepo;
			$this->rankingURLManager = $rankingURLManager;
			$this->rankingModel		 = $rankingModel;
			$this->_ci = $_ci;
		}
	}
	
	/*public function getSEOLinksForRankingPage(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$moreSEOLinks = array();
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $moreSEOLinks;
		}
		
		$rankingPageData 				= $rankingPageObject->getRankingPageData();
		$rankingPageId 					= $rankingPageObject->getId();
		$rankingPageName 				= $rankingPageObject->getName();
		$rankingPageSubcategoryId 		= $rankingPageObject->getSubcategoryId();
		$rankingPageSpecializationId 	= $rankingPageObject->getSpecializationId();
		if($rankingPageSpecializationId == 0){ //current ranking page is a subcategory page
			$specializationPagesExistForSubCat 	= true;
			$specializationPages 				= $this->getSpecializationPagesBySubcategory($rankingPageSubcategoryId);
			if(empty($specializationPages)) { //no specialization pages available for this sub category.
				$specializationPagesExistForSubCat = false;
				$rankingPageDetail = array();
				$rankingPageDetail['id'] 				= $rankingPageId;
				$rankingPageDetail['ranking_page_text'] = $rankingPageName;
				$rankingPageDetail['specialization_id'] = $rankingPageSpecializationId;
				$rankingPageDetail['subcategory_id'] 	= $rankingPageSubcategoryId;
				$specializationPages[] = $rankingPageDetail;
			}
			$moreSEOLinks = $this->getSEOLinksBasedOnSubcategoryPage($rankingPageObject, $rankingPageRequestObject, $specializationPages, $specializationPagesExistForSubCat);
		} else {
			$moreSEOLinks = $this->getSEOLinksBasedOnSpecializationPage($rankingPageObject, $rankingPageRequestObject);
		}
		
		return $moreSEOLinks;
	}*/
	
	/*public function getSpecializationPagesBySubcategory($subcategoryId = NULL){
		$specializationPageDetails = array();
		if(empty($subcategoryId)){
			return $specializationPageDetails;
		}
		$tempSpecializationPageDetails = $this->rankingModel->getSpecializationPagesBySubcategory(array($subcategoryId));
		foreach($tempSpecializationPageDetails as $specializationPage){
			if($specializationPage['status'] == "live"){
				$specializationPageDetails[] = $specializationPage;
			}
		}
		return $specializationPageDetails;
	}*/
	
	/**
	 *@method: getSEOLinksBasedOnSpecializationPage: returns seo links for the given specialization ranking page
	 *@param: $rankingPage: current ranking page
	 *@param: $rankingPageRequest: current ranking page request
	 *returns:
	 *  array of fliter objects
	 */
	public function getSEOLinksBasedOnSpecializationPage(RankingPage $rankingPage, RankingPageRequest $rankingPageRequest){
		$seoFiltersList = array();
		$requestCountryId 	= $rankingPageRequest->getCountryId();
		$requestStateId 	= $rankingPageRequest->getStateId();
		$requestCityId 		= $rankingPageRequest->getCityId();
		$examId 			= $rankingPageRequest->getExamId();
		$examName 			= $rankingPageRequest->getExamName();
		
		$locationTypeDetails = $this->getLocationTypeDetailsFromRequestObject($rankingPageRequest);
		if(!empty($locationTypeDetails)){
			$locationType = $locationTypeDetails['location_type'];
			switch($locationType){
				case 'city':
					$cityId   			= $locationTypeDetails['location_type_id'];
					$cityName 			= $locationTypeDetails['location_type_name'];
					if(empty($examId)){
						$cityFiltersList 	= $this->getSpecializationSEOLinksByAllTestsInCity($rankingPage, $cityId, $cityName);
						$seoFiltersList 	= array_merge($seoFiltersList, $cityFiltersList);	
					}
					break;
				case 'state':
					$stateId   			= $locationTypeDetails['location_type_id'];
					$stateName 			= $locationTypeDetails['location_type_name'];
					$stateFiltersList 	= $this->getSpecializationSEOLinksByAllCitiesInState($rankingPage, $stateId, $examId, $examName);
					$seoFiltersList 	= array_merge($seoFiltersList, $stateFiltersList);
					break;
				
				case 'country':
					if(!empty($examId)){
						$countryId   			= $locationTypeDetails['location_type_id'];
						$countryName 			= $locationTypeDetails['location_type_name'];
						$countryFiltersList 	= $this->getSpecializationSEOLinksByAllCities($rankingPage, $countryId, $countryName, $examId, $examName);
						$seoFiltersList = array_merge($seoFiltersList, $countryFiltersList);	
					} else {
						$countryId   			= $locationTypeDetails['location_type_id'];
						$countryName 			= $locationTypeDetails['location_type_name'];
						$countryFiltersList 	= $this->getSpecializationSEOLinksByAllStates($rankingPage, $countryId);
						$seoFiltersList = array_merge($seoFiltersList, $countryFiltersList);	
					}
					break;
			}
		}
		return $seoFiltersList;
	}
	
	/**
	 *@method: getSEOLinksBasedOnSubcategoryPage: returns seo links for the given subcategory ranking page
	 *@param: $rankingPage: current ranking page
	 *@param: $rankingPageRequest: current ranking page request
	 *@param: $specializationPageDetails: specialization pages with same subcategory as current ranking page.
	 *@param: $pageWithSpecializations: whether or not this ranking page is specialization page or not.
	 *returns:
	 *  array of fliter objects
	 */
	public function getSEOLinksBasedOnSubcategoryPage(RankingPage $rankingPage, RankingPageRequest $rankingPageRequest, $specializationPageDetails = array(), $pageWithSpecializations = true){
		$seoFiltersList = array();
		$locationTypeDetails = $this->getLocationTypeDetailsFromRequestObject($rankingPageRequest); //Identify whether request object has city/state or country
		if(empty($locationTypeDetails)){
			return $seoFiltersList;
		}
		
		$requestCountryId 	= $rankingPageRequest->getCountryId();
		$requestStateId 	= $rankingPageRequest->getStateId();
		$requestCityId 		= $rankingPageRequest->getCityId();
		$examId 			= $rankingPageRequest->getExamId();
		$examName 			= $rankingPageRequest->getExamName();
		$locationType 		= $locationTypeDetails['location_type'];
		switch($locationType){
			case 'city':
				$cityId   = $locationTypeDetails['location_type_id'];
				$cityName = $locationTypeDetails['location_type_name'];
				if($pageWithSpecializations){
					//requirement: if current ranking page has specializations than only show specialization links with location
					//user has selected subcategory page with specializations with city, with or without exam
					$cityFilterList = $this->getSubcategorySEOLinksByCity($specializationPageDetails, $cityId, $cityName, $examId, $examName);
				} else {
					//user has selected subcategory page without specializations with city, with or without exam
					if(empty($examId)){
						//if no exam is selected than show all current ranking + city + exams combinations
						$cityFilterList = $this->getSubcategorySEOLinksByCityAllExams($rankingPage, $specializationPageDetails, $cityId, $cityName);
					}
				}
				$seoFiltersList = array_merge($seoFiltersList, $cityFilterList);
				break;
			case 'state':
				//if user has selected state than links will have current ranking page's specializations with cities in selected state and exam if selected
				$stateId   			= $locationTypeDetails['location_type_id'];
				$stateName 			= $locationTypeDetails['location_type_name'];
				$stateFilterList	= $this->getSubcategorySEOLinksByState($rankingPage, $specializationPageDetails, $stateId, $stateName, $examId, $examName);
				$seoFiltersList 	= array_merge($seoFiltersList, $stateFilterList);
				break;
			case 'country':
				$countryId   		= $locationTypeDetails['location_type_id'];
				$countryName 		= $locationTypeDetails['location_type_name'];
				if($pageWithSpecializations){
					//ranking page has specializations.
					if(!empty($examId)){
						//if user has selected exam but no state or city than links will have current ranking page's specializations with all cities
						$countryTestFilterList 	= $this->getSubcategorySEOLinksByAllCities($rankingPage, $specializationPageDetails, $countryId, $countryName, $examId, $examName);
						$seoFiltersList 		= array_merge($seoFiltersList, $countryTestFilterList);
					} else {
						//if user has not selected exam and state or city than links will have current ranking page's specializations with all states
						$stateFilterList	= $this->getSubcategorySEOLinksByCountry($rankingPage, $specializationPageDetails, $pageWithSpecializations, $countryId, $countryName);
						$seoFiltersList 	= array_merge($seoFiltersList, $stateFilterList);
					}
				} else {
					//ranking page has no specializations.
					//if user has selected exam and no state or city than links will have current ranking page with all cities and all exams.
					if(!empty($examId)){
						$countryTestFilterList 	= $this->getSubcategorySEOLinksByAllCitiesAllTest($rankingPage, $specializationPageDetails, $countryId, $countryName);
						$seoFiltersList 		= array_merge($seoFiltersList, $countryTestFilterList);
					} else {
						//if user has not selected exam and no state or city than links will have current ranking page with all states in it.
						$stateFilterList	= $this->getSubcategorySEOLinksByCountry($rankingPage, $specializationPageDetails, $pageWithSpecializations, $countryId, $countryName);
						$seoFiltersList 	= array_merge($seoFiltersList, $stateFilterList);
					}
				}
				break;
		}
		return $seoFiltersList;
	}
	
	public function getSubcategorySEOLinksByCity($specializationPages = array(), $cityId = NULL, $cityName = NULL, $examId = NULL, $examName = NULL){
		$seoFilterObjectList = array();
		if(empty($specializationPages) || empty($cityId) || empty($cityName)){
			return $seoFilterObjectList;
		}
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPages);
		if(!empty($orderedSpecializations)){
			foreach($orderedSpecializations as $specialization){
				$params = array();
				$params['rankingPageId'] 		= $specialization['id'];
				$params['rankingPageName']   	= $specialization['ranking_page_text'];
				$params['cityId']   			= $cityId;
				$params['cityName']   			= $cityName;
				$params['examId']   			= $examId;
				$params['examName']   			= $examName;
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSubcategorySEOLinksByState(RankingPage $rankingPage = NULL, $specializationPageDetails = array(), $stateId = NULL, $stateName = NULL, $examId = NULL, $examName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($specializationPageDetails) || empty($stateId) || empty($stateName)){
			return $seoFilterObjectList;
		}
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPageDetails);
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$cities 				= $this->getRankingPageCitiesTierWiseByState($rankingPageData, $stateId);
		foreach($orderedSpecializations as $specialization){
			$rankingPageName = $specialization['ranking_page_text'];
			$rankingPageId 	 = $specialization['id'];
			foreach($cities as $cityTier => $cityValues){
				foreach($cityValues as $city){
					$cityId 	= $city['city_id'];
					$cityName 	= $city['city_name'];
					
					$params = array();
					$params['rankingPageId'] 		= $rankingPageId;
					$params['rankingPageName']   	= $rankingPageName;
					$params['cityId']   			= $cityId;
					$params['cityName']   			= $cityName;
					$params['examId']   			= $examId;
					$params['examName']   			= $examName;
					$seoFilterInstance 				= $this->getSeoURL($params);
					$seoFilterObjectList[] 			= $seoFilterInstance;
				}
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSubcategorySEOLinksByCountry(RankingPage $rankingPage = NULL, $specializationPageDetails = array(), $pageWithSpecializations = true, $countryId = NULL, $countryName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($specializationPageDetails) || empty($countryId) || empty($countryName)){
			return $seoFilterObjectList;
		}
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPageDetails);
		if($pageWithSpecializations){ //If current ranking page has specializations than create links for specialization and country
			foreach($orderedSpecializations as $specialization){
				$rankingPageName = $specialization['ranking_page_text'];
				$rankingPageId 	 = $specialization['id'];
				
				$params = array();
				$params['rankingPageId'] 		= $rankingPageId;
				$params['rankingPageName']   	= $rankingPageName;
				$params['countryId']   			= $countryId;
				$params['countryName']   		= $countryName;
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;
			}
		} else {
			//If current ranking page has no specializations than create links for current ranking page and states in the selected country
			$rankingPageData = $rankingPage->getRankingPageData();
			$states 		 = $this->getRankingPageStatesTierWiseByCountry($rankingPageData, $countryId);
			foreach($orderedSpecializations as $specialization){
				$rankingPageName = $specialization['ranking_page_text'];
				$rankingPageId 	 = $specialization['id'];
				foreach($states as $stateTier => $stateValues){
					foreach($stateValues as $state){
						$stateId 	= $state['state_id'];
						$stateName 	= $state['state_name'];
						
						$params = array();
						$params['rankingPageId'] 		= $rankingPageId;
						$params['rankingPageName']   	= $rankingPageName;
						$params['stateId']   			= $stateId;
						$params['stateName']   			= $stateName;
						$seoFilterInstance 				= $this->getSeoURL($params);
						$seoFilterObjectList[] 			= $seoFilterInstance;
					}
				}
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSubcategorySEOLinksByAllCities(RankingPage $rankingPage = NULL, $specializationPageDetails = array(), $countryId = NULL, $countryName = NULL, $examId = NULL, $examName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($specializationPageDetails) || empty($countryId) || empty($countryName)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPageDetails);
		$cities 				= $this->getRankingPageCitiesTierWiseByCountry($rankingPageData, $countryId);
		foreach($orderedSpecializations as $specialization){
			$rankingPageName = $specialization['ranking_page_text'];
			$rankingPageId 	 = $specialization['id'];
			foreach($cities as $cityTier => $cityDetails){
				foreach($cityDetails as $city){
					$cityId 	= $city['city_id'];
					$cityName 	= $city['city_name'];
					$params = array();
					$params['rankingPageId'] 		= $rankingPageId;
					$params['rankingPageName']   	= $rankingPageName;
					$params['cityId']   			= $cityId;
					$params['cityName']   			= $cityName;
					$params['examId']   			= $examId;
					$params['examName']   			= $examName;
					
					$seoFilterInstance 				= $this->getSeoURL($params);
					$seoFilterObjectList[] 			= $seoFilterInstance;	
				}
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSpecializationSEOLinksByAllCities(RankingPage $rankingPage = NULL, $countryId = NULL, $countryName = NULL, $examId = NULL, $examName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($countryId) || empty($countryName)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$cities 				= $this->getRankingPageCitiesTierWiseByCountry($rankingPageData, $countryId);
		$rankingPageName = $rankingPage->getName();
		$rankingPageId 	 = $rankingPage->getId();
		foreach($cities as $cityTier => $cityDetails){
			foreach($cityDetails as $city){
				$cityId 	= $city['city_id'];
				$cityName 	= $city['city_name'];
				$params = array();
				$params['rankingPageId'] 		= $rankingPageId;
				$params['rankingPageName']   	= $rankingPageName;
				$params['cityId']   			= $cityId;
				$params['cityName']   			= $cityName;
				$params['examId']   			= $examId;
				$params['examName']   			= $examName;
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;		
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSubcategorySEOLinksByAllCitiesAllTest(RankingPage $rankingPage = NULL, $specializationPageDetails = array(), $countryId = NULL, $countryName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($specializationPageDetails) || empty($countryId) || empty($countryName)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPageDetails);
		$cities 				= $this->getRankingPageCitiesTierWiseByCountry($rankingPageData, $countryId);
		$exams 					= $this->getRankingPageExamsTierWise($rankingPageData);
		
		foreach($orderedSpecializations as $specialization){
			$rankingPageName = $specialization['ranking_page_text'];
			$rankingPageId 	 = $specialization['id'];
			foreach($cities as $cityTier => $cityDetails){
				foreach($cityDetails as $city){
					$cityId 	= $city['city_id'];
					$cityName 	= $city['city_name'];
					foreach($exams as $exam){
						$examId 						= $exam['exam_id'];
						$examName 						= $exam['exam_name'];
						
						$params = array();
						$params['rankingPageId'] 		= $rankingPageId;
						$params['rankingPageName']   	= $rankingPageName;
						$params['cityId']   			= $cityId;
						$params['cityName']   			= $cityName;
						$params['examId']   			= $examId;
						$params['examName']   			= $examName;
						
						$seoFilterInstance 				= $this->getSeoURL($params);
						$seoFilterObjectList[] 			= $seoFilterInstance;		
					}
				}
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSubcategorySEOLinksByCityAllExams(RankingPage $rankingPage = NULL, $specializationPageDetails = array(), $cityId = NULL, $cityName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($specializationPageDetails) || empty($cityId) || empty($cityName)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$orderedSpecializations = $this->getOrderdSpecializationsForSEOLinks($specializationPageDetails);
		$exams 					= $this->getRankingPageExamsTierWise($rankingPageData);
		
		foreach($orderedSpecializations as $specialization){
			$rankingPageName = $specialization['ranking_page_text'];
			$rankingPageId 	 = $specialization['id'];
			foreach($exams as $exam){
				$examId 						= $exam['exam_id'];
				$examName 						= $exam['exam_name'];
				$params = array();
				$params['rankingPageId'] 		= $rankingPageId;
				$params['rankingPageName']   	= $rankingPageName;
				$params['cityId']   			= $cityId;
				$params['cityName']   			= $cityName;
				$params['examId']   			= $examId;
				$params['examName']   			= $examName;
				
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;		
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSeoURL($params = array()){
		$rankingPageRequest = $this->rankingURLManager->getRankingPageRequestFromDataArray($params);
		if(empty($rankingPageRequest)){
			return false;
		}
		$pageId 			= $rankingPageRequest->getPageId();
		$urlData 			= $this->rankingURLManager->buildURL($rankingPageRequest, 'urltitle');
		$url 				= $urlData['url'];
		$urlTitle 			= $urlData['title']; 
		$filterObject 		= new RankingPageFilter($pageId, $urlTitle, $url); 
		return $filterObject;
	}
	
	public function getSpecializationSEOLinksByAllStates(RankingPage $rankingPage, $countryId = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($countryId)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$states 				= $this->getRankingPageStatesTierWiseByCountry($rankingPageData, $countryId);
		foreach($states as $stateTierWise){
			$rankingPageName = $rankingPage->getName();
			$rankingPageId 	 = $rankingPage->getId();
			foreach($stateTierWise as $stateDetails){
				$stateId 	= $stateDetails['state_id'];
				$stateName 	= $stateDetails['state_name'];
				
				$params = array();
				$params['rankingPageId'] 		= $rankingPageId;
				$params['rankingPageName']   	= $rankingPageName;
				$params['stateId']   			= $stateId;
				$params['stateName']   			= $stateName;
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;		
				
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSpecializationSEOLinksByAllCitiesInState(RankingPage $rankingPage, $stateId = NULL, $examId = NULL, $examName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($stateId)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$cities 				= $this->getRankingPageCitiesTierWiseByState($rankingPageData, $stateId);
		foreach($cities as $cityTierWise){
			$rankingPageName = $rankingPage->getName();
			$rankingPageId 	 = $rankingPage->getId();
			foreach($cityTierWise as $cityDetails){
				$cityId 	= $cityDetails['city_id'];
				$cityName 	= $cityDetails['city_name'];
				
				$params = array();
				$params['rankingPageId'] 		= $rankingPageId;
				$params['rankingPageName']   	= $rankingPageName;
				$params['cityId']   			= $cityId;
				$params['cityName']   			= $cityName;
				$params['examId']   			= $examId;
				$params['examName']   			= $examName;
				$seoFilterInstance 				= $this->getSeoURL($params);
				$seoFilterObjectList[] 			= $seoFilterInstance;		
			}
		}
		return $seoFilterObjectList;
	}
	
	public function getSpecializationSEOLinksByAllTestsInCity(RankingPage $rankingPage, $cityId = NULL, $cityName = NULL){
		$seoFilterObjectList = array();
		if(empty($rankingPage) || empty($cityId) || empty($cityName)){
			return $seoFilterObjectList;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$exams 					= $this->getRankingPageExamsTierWise($rankingPageData);
		$rankingPageName = $rankingPage->getName();
		$rankingPageId 	 = $rankingPage->getId();
		foreach($exams as $exam){
			$examId 	= $exam['exam_id'];
			$examName 	= $exam['exam_name'];
			
			$params = array();
			$params['rankingPageId'] 		= $rankingPageId;
			$params['rankingPageName']   	= $rankingPageName;
			$params['cityId']   			= $cityId;
			$params['cityName']   			= $cityName;
			$params['examId']   			= $examId;
			$params['examName']   			= $examName;
			$seoFilterInstance 				= $this->getSeoURL($params);
			$seoFilterObjectList[] 			= $seoFilterInstance;		
		}
		return $seoFilterObjectList;
	}
	
	private function getOrderdSpecializationsForSEOLinks($specializations = array()){
		$orderedSpecializations = array();
		if(empty($specializations)){
			return $orderedSpecializations;
		}
		$specialSpecializationsOrder = $this->_ci->config->item('SPECIALIZATION_ORDER');
		$specialSpecializations = array();
		$normalSpecializations  = array();
		foreach($specializations as $specialization){
			if(in_array($specialization['specialization_id'], $specialSpecializationsOrder)){
				$specialSpecializations[] = $specialization;
			} else {
				$normalSpecializations[]  = $specialization;
			}
		}
		if(!empty($specialSpecializations)){
			$specialSpecializations = $this->sortSpecialSpecializations($specialSpecializations);
		}
		if(!empty($normalSpecializations)){
			usort($normalSpecializations, 'specializationCompareFN');
		}
		$orderedSpecializations = array_merge($specialSpecializations, $normalSpecializations);
		return $orderedSpecializations;
	}
	
	private function sortSpecialSpecializations($specializations = array()){
		if(empty($specializations)) {
			return $specializations;
		}
		$specialSpecializationsOrder = $this->_ci->config->item('SPECIALIZATION_ORDER');
		$orderedSpecializations = array();
		foreach($specialSpecializationsOrder as $key){
			foreach($specializations as $specialization){
				if($key == $specialization['specialization_id']){
					$orderedSpecializations[] = $specialization;
				}
			}
		}
		if(!empty($orderedSpecializations)){
			$specializations = $orderedSpecializations;
		}
		return $specializations;
	}
	
	private function sortSpecalExams($exams = array()){
		if(empty($exams)) {
			return $exams;
		}
		$specialExamsOrder = $this->_ci->config->item('EXAMS_ORDER');
		$orderedExams = array();
		foreach($specialExamsOrder as $key){
			foreach($exams as $exam){
				if($key == $exam['exam_id']){
					$orderedExams[] = $exam;
				}
			}
		}
		if(!empty($orderedExams)){
			$exams = $orderedExams;
		}
		return $exams;
	}
	
	
	private function getRankingPageCitiesTierWiseByCountry($rankingPageData = array(), $paramCountryId = NULL){
		$tierWiseCities = array();
		if(empty($rankingPageData) || empty($paramCountryId)){
			return $tierWiseCities;
		}
		$cities = array();
		$encounteredCityIds = array();
		foreach($rankingPageData as $data){
			$cityObject  	= $data->getLocation()->getCity();
			$virtualCityId 	= $cityObject->getVirtualCityId();
			if(!empty($virtualCityId)){
				if(!in_array($virtualCityId, $encounteredCityIds)){ //Normally virtual cities are tier 1 cities, this check can be assumed.
					$virtualCityObject = $this->locationRepo->findCity($virtualCityId);
					$virtualCityTier   = $virtualCityObject->getTier();
					if(!array_key_exists($virtualCityTier, $cities)){
						$cities[$virtualCityTier] = array();
					}
					$cityDetails = array();
					$cityDetails['city_id'] 	= $virtualCityId;
					$cityDetails['city_name'] 	= $virtualCityObject->getName();
					$cities[$virtualCityTier][] = $cityDetails;
					$encounteredCityIds[] 		= $virtualCityId;
				}
			} else {
				$cityId = $data->getCityId();
				if(!in_array($cityId, $encounteredCityIds)){
					$cityTier = $cityObject->getTier();
					if(!array_key_exists($cityTier, $cities)){
						$cities[$cityTier] = array();
					}
					$cityDetails = array();
					$cityDetails['city_id'] 	= $cityId;
					$cityDetails['city_name'] 	= $data->getCityName();
					$cities[$cityTier][]  = $cityDetails;
					$encounteredCityIds[] = $cityId;
				}
			}
		}
		ksort($cities); //Sort cities array based on tier.
		$tempCities = $cities;
		if(!empty($tempCities)){
			foreach($tempCities as $cityTier => $cityDetail){
				if(count($cityDetail) > 1){
					usort($cityDetail, 'cityNameCompareFN'); //sort cities of same tier alphabatically.
					$cities[$cityTier] = $cityDetail;
				}
			}
		}
		return $cities;
	}
	
	private function getRankingPageCitiesTierWiseByState($rankingPageData = array(), $paramStateId = NULL){
		$tierWiseCities = array();
		if(empty($rankingPageData) || empty($paramStateId)){
			return $tierWiseCities;
		}
		$cities = array();
		$encounteredCityIds = array();
		foreach($rankingPageData as $data){
			$stateId = $data->getStateId();
			if($stateId == $paramStateId){
				$cityObject  	= $data->getLocation()->getCity();
				$virtualCityId 	= $cityObject->getVirtualCityId();
				if(!empty($virtualCityId)){
					if(!in_array($virtualCityId, $encounteredCityIds)){ //Normally virtual cities are tier 1 cities, this check can be assumed.
						$virtualCityObject = $this->locationRepo->findCity($virtualCityId);
						$virtualCityTier   = $virtualCityObject->getTier();
						if(!array_key_exists($virtualCityTier, $cities)){
							$cities[$virtualCityTier] = array();
						}
						$cityDetails = array();
						$cityDetails['city_id'] 	= $virtualCityId;
						$cityDetails['city_name'] 	= $virtualCityObject->getName();
						$cities[$virtualCityTier][] = $cityDetails;
						$encounteredCityIds[] 		= $virtualCityId;
					}
				} else {
					$cityId = $data->getCityId();
					if(!in_array($cityId, $encounteredCityIds)){
						$cityTier = $cityObject->getTier();
						if(!array_key_exists($cityTier, $cities)){
							$cities[$cityTier] = array();
						}
						$cityDetails = array();
						$cityDetails['city_id'] 	= $cityId;
						$cityDetails['city_name'] 	= $data->getCityName();
						$cities[$cityTier][]  = $cityDetails;
						$encounteredCityIds[] = $cityId;
					}
				}
			}
		}
		
		ksort($cities); //Sort cities array based on tier.
		$tempCities = $cities;
		if(!empty($tempCities)){
			foreach($tempCities as $cityTier => $cityDetail){
				if(count($cityDetail) > 1){
					usort($cityDetail, 'cityNameCompareFN'); //sort cities of same tier alphabatically.
					$cities[$cityTier] = $cityDetail;
				}
			}
		}
		return $cities;
	}
	
	private function getRankingPageStatesTierWiseByCountry($rankingPageData = array(), $paramCountryId = NULL){
		$tierWiseStates = array();
		if(empty($rankingPageData) || empty($paramCountryId)){
			return $tierWiseStates;
		}
		$states = array();
		$encounteredStateIds = array();
		
		foreach($rankingPageData as $data){
			$countryId = $data->getCountryId();
			if($countryId == $paramCountryId){
				$stateId        = $data->getStateId(); 
				$stateName      = $data->getStateName();
				if(!in_array($stateId, $encounteredStateIds)){
					$stateObject  	= $data->getLocation()->getState(); 
					$stateTier  	= $stateObject->getTier(); 
					if(!array_key_exists($stateTier, $states)){
						$states[$stateTier] = array();
					}
					$stateDetails = array();
					$stateDetails['state_id'] 	= $stateId;
					$stateDetails['state_name'] = $stateName;
					$states[$stateTier][]  		= $stateDetails;
					$encounteredStateIds[] 		= $stateId;
				}
			}
		}
		ksort($states); //Sort states array based on tier.
		$tempStates = $states;
		if(!empty($tempStates)){
			foreach($tempStates as $stateTier => $stateDetail){
				if(count($stateDetail) > 1){
					usort($stateDetail, 'stateNameCompareFN'); //sort states of same tier alphabatically.
					$states[$stateTier] = $stateDetail;
				}
			}
		}
		return $states;
	}
	
	public function getRankingPageExamsTierWise($rankingPageData = array()){
		$exams = array();
		if(empty($rankingPageData)){
			return $exams;
		}
		$specialExamOrders 	= $this->_ci->config->item('EXAMS_ORDER');
		$encounteredExamIds = array();
		$normalExams 	= array();
		$specialExams 	= array();
		foreach($rankingPageData as $pageData) {
			$examDetails = $pageData->getExams();
			foreach($examDetails as $exam){
				if(!in_array($exam['id'], $encounteredExamIds)){
					$tempExams = array();
					$tempExams['exam_id'] 	= $exam['id'];
					$tempExams['exam_name'] = $exam['name'];
					if(in_array($exam['id'], $specialExamOrders)){
						$specialExams[] = $tempExams;
					} else {
						$normalExams[] 	= $tempExams;
					}
					$encounteredExamIds[] = $exam['id'];
				}
			}
		}
		if(!empty($specialExams)){
			$specialExams = $this->sortSpecalExams($specialExams);
		}
		
		if(!empty($normalExams)){
			usort($normalExams, 'examNameCompareFN');
		}
		
		$exams = array_merge($specialExams, $normalExams);
		return $exams;
	}
	
	/**
	 *@mthod: getLocationTypeDetailsFromRequestObject: get location type from request object. Current requestObject can have state or
	 *city value, so identify whether the request object has valid city value or state value
	 *@returns: array
	 *@example
	 *	Array
		(
			[location_type] => city
			[location_type_id] => 278
			[location_type_name] => Bangalore
		)
	*/
	public function getLocationTypeDetailsFromRequestObject(RankingPageRequest $rankingPageRequest){
		$locationTypeDetails 	= array();
		$requestCountryId 		= $rankingPageRequest->getCountryId();
		$requestStateId 		= $rankingPageRequest->getStateId();
		$requestCityId 			= $rankingPageRequest->getCityId();
		$examId 				= $rankingPageRequest->getExamId();
		
		$locationType 		= "";
		$locationTypeId 	= "";
		$locationTypeName 	= "";
		if(!empty($requestCityId)){
			$locationType		= "city";
			$locationTypeId 	= $requestCityId;
			$locationTypeName	= $rankingPageRequest->getCityName();
			if(empty($locationTypeName)){
				$cityObject 		= $this->locationRepo->getCity($locationTypeId);
				$locationTypeName 	= $cityObject->getName();
			}
		} else if(!empty($requestStateId)){
			$locationType		= "state";
			$locationTypeId 	= $requestStateId;
			$locationTypeName	= $rankingPageRequest->getStateName();
			if(empty($locationTypeName)){
				$stateObject 		= $this->locationRepo->getState($locationTypeId);
				$locationTypeName 	= $stateObject->getName();
			}
		} else if(!empty($requestCountryId)){
			$locationType		= "country";
			$locationTypeId 	= $requestCountryId;
			$locationTypeName	= $rankingPageRequest->getCountryName();
			if(empty($locationTypeName)){
				$countryObject 		= $this->locationRepo->getCountry($locationTypeId);
				$locationTypeName 	= $countryObject->getName();
			}
		}
		
		$locationTypeDetails['location_type'] 		= $locationType;
		$locationTypeDetails['location_type_id'] 	= $locationTypeId;
		$locationTypeDetails['location_type_name'] 	= $locationTypeName;
		
		return $locationTypeDetails;
	}
	
	/*public function getWidgetHTML($subcategoryIds = array(), $specializationIds = array(), $pageType = "listingpage"){
		$widgetHTML = "";
		if(empty($subcategoryIds) && empty($specializationIds)){
			return $widgetHTML;
		}
		$rankingModel = new ranking_model();
		if(!empty($specializationIds)){
			$rankingPages = $rankingModel->getRankingPagesBySpecializations($specializationIds);
		} else if(!empty($subcategoryIds)){
			$rankingPages = $rankingModel->getRankingPagesBySubcategory($subcategoryIds);
		}
		
		$rankingPageWithMaxRanks = $this->getRankingPageWithMaxRanks($rankingPages);
		if(!empty($rankingPageWithMaxRanks)){
			$params = array();
			$params['rankingPageId'] 		= $rankingPageWithMaxRanks['id'];
			$params['rankingPageName']   	= $rankingPageWithMaxRanks['ranking_page_text'];
			$params['countryId']   			= 2;
			$params['countryName']   		= "India";
			$seoFilterInstance 				= $this->getSeoURL($params);
			$data = array();
			$data['filter'] = $seoFilterInstance;
			$data['page_type'] = $pageType;
			$widgetHTML = $this->_ci->load->view("ranking/ranking_widget", $data, true);
		}
		return $widgetHTML;
	}*/
	
	private function getRankingPageWithMaxRanks($rankingPages = array()){
		if(empty($rankingPages)){
			return false;
		}
		$maxRanksEncountered 		= 0;
		$rankingPageWithMaxRanks 	= false;
		$rankingPageDataCountList = $this->rankingModel->getRankingPageRowsCount();
		if(!empty($rankingPageDataCountList)){
			foreach($rankingPages as $rankingPage){
				if(!empty($rankingPage) && !empty($rankingPage['id']) && $rankingPage['status'] == "live") {
					$rankingPageId = $rankingPage['id'];
					if(array_key_exists($rankingPageId, $rankingPageDataCountList)){
						$rankingPageDataCount = $rankingPageDataCountList[$rankingPageId];
						if(!empty($rankingPageDataCount)){
							if($rankingPageDataCount > $maxRanksEncountered){
								$rankingPageWithMaxRanks 	= $rankingPage;
								$maxRanksEncountered 		= $rankingPageDataCount;
							}
						}
					}
				}
			}	
		}
		return $rankingPageWithMaxRanks;
	}

	/**
	 * To prepare category page related links from ranking page
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-18
	 * @param  Object     $rankingPageRequest Ranking page request to get current url identifier
	 * @return Array                          List of category page links
	 */
	public function getCategoryRelatedLinks($rankingPageRequest){

		// load category page model and cache libraries
		$rankingPageCache  = $this->_ci->load->library(RANKING_PAGE_MODULE."/cache/RankingPageCache");
		
		//get ranking page identifier from request
		$categoryId        = $rankingPageRequest->getCategoryId();
		$subCategoryId     = $rankingPageRequest->getSubCategoryId();
		$specializationId  = $rankingPageRequest->getSpecializationId();
		$cityId            = $rankingPageRequest->getCityId();
		$stateId           = $rankingPageRequest->getStateId();
		$countryId         = $rankingPageRequest->getCountryId();
		$examId            = $rankingPageRequest->getExamId();
		$examName          = $rankingPageRequest->getExamName();


		//logic to get pageType
		if(!empty($countryId) && !empty($examId) && !empty($specializationId))
			$pageType = INDIA_EXAM_SPECIALIZATION_ONLY;
		elseif(!empty($countryId) && empty($examId) && !empty($specializationId))
			$pageType = INDIA_SPECIALIZATION_ONLY;
		elseif(!empty($countryId) && !empty($examId) && empty($specializationId))
			$pageType = INDIA_EXAM_ONLY;
		elseif(!empty($countryId) && empty($examId) && empty($specializationId))
			$pageType = INDIA_ONLY;
		elseif(empty($countryId) && !empty($examId) && !empty($specializationId))
			$pageType = EXAM_SPECIALIZATION_ONLY;
		elseif(empty($countryId) && empty($examId) && !empty($specializationId))
			$pageType = SPECIALIZATION_ONLY;
		elseif(empty($countryId) && !empty($examId) && empty($specializationId))
			$pageType = EXAM_ONLY;
		elseif(empty($countryId) && empty($examId) && empty($specializationId))
			$pageType = LOCATION_ONLY;




		//check array of category links  from cache
		$cacheData = $rankingPageCache->getCategoryRelatedLinks($rankingPageRequest);
		
		if(!$cacheData){
			$data['categoryId']        = $categoryId;
			$data['subCategoryId']     = $subCategoryId;
			$data['specializationId']  = $specializationId;
			$data['cityId']            = $cityId;
			$data['stateId']           = $stateId;
			$data['countryId']         = $countryId;
			$data['examId']            = $examId;
			$data['examName']          = $examName;
			$data['rankingPageRequest']= $rankingPageRequest;
			$data['pageType']          = $pageType;

			//Method to prepare category page links logic according to pagetype
    		$catlinksArr = $this->getRelatedCategoryLinks($data);
    		$catlinksArr = array_slice($catlinksArr, 0 ,ENT_MAX_LINKS_LIMIT);
    		
			// get the static ranking link to be shown same on each subcategory page
		    $catlinksArrWithStatic = $this->getStaticLink($data,$catlinksArr);
    	}else{
    		return $cacheData;
    	}

		// array to store category related links
		$urls = array();

		foreach($catlinksArrWithStatic as $row){
	    	// get url and url title 
			$urls[] = $this->getCategoryPageUrl($row);
	    }

		// to remove duplicate title urls	 
	    $urls = array_map("unserialize", array_unique(array_map("serialize", $urls)));
	 	
	 	// to get array last key
	    end($urls);
	    $lastKey =  key($urls);

		if($subCategoryId == ENGINEERING_SUBCAT_ID){
			$urls[$lastKey]['urlTitle'] = "Engineering Colleges in India";
		}elseif($subCategoryId == MBA_SUBCAT_ID){
			$urls[$lastKey]['urlTitle'] = "MBA Colleges in India";
		}

		//to store category page links in cache
		$rankingPageCache->storeCategoryRelatedLinks($rankingPageRequest, $urls);	

		return $urls;
	}

	/**
	 * To prepare category page links logic according to pagetype
	 * Stage 1:
	 * INDIA_ONLY                       : cities
	 * INDIA_EXAM_ONLY                  : exam and cities (cities needed)
	 * INDIA_SPECIALIZATION_ONLY        : specialization and cities (cities needed)
	 * INDIA_EXAM_SPECIALIZATION_ONLY   : exam, specialization and cities (cities needed)
	 * LOCATION_ONLY                    : location
	 * EXAM_ONLY                        : location and exam
	 * SPECIALIZATION_ONLY              : location, specialization and exam (exam needed)
	 * EXAM_SPECIALIZATION_ONLY         : location, specialization and exam
	 *
	 * Stage 2:
	 * INDIA_SPECIALIZATION_ONLY        : specialization, exam and cities (cities and exam needed)
	 * LOCATION_ONLY                    : location and specialization (specialization needed)
	 * EXAM_ONLY                        : location, fee and exam (fee needed)
	 * SPECIALIZATION_ONLY              : location, specialization and fee (fee needed)
	 * EXAM_SPECIALIZATION_ONLY         : location
	 *
	 * Stage 3:
	 * LOCATION_ONLY                    : location and exam (exam needed)
	 * EXAM_ONLY                        : location, specialization and exam (specialization needed)
	 * EXAM_SPECIALIZATION_ONLY         : location and exam
	 *
	 * Stage 4:
	 * LOCATION_ONLY                    : location and fee (fee needed)
	 * 
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-18
	 * @param  [type]     $rankingPageRequest [description]
	 * @return [type]                         [description]
	 */
	public function getRelatedCategoryLinks($data){

		$uniqueLinksArr    = array();	

		$categoryId        = $data['categoryId'];
		$subCategoryId     = $data['subCategoryId'];
		$specializationId  = $data['specializationId'];
		$cityId            = $data['cityId'];
		$stateId           = $data['stateId'];
		$countryId         = $data['countryId']; 
		$examId            = $data['examId'];
		$examName          = $data['examName'];
		$pageType          = $data['pageType'];



		///////////////
		// Stage 1 //
		///////////////
		
		$filters                     = array();
		$filters['categoryId']       = $categoryId;
		$filters['subCategoryId']    = $subCategoryId;
		$filters['countryId']        = 2;
		$filters['stateId']          = $stateId;
		$filters['cityId']           = (!empty($stateId))?1:$cityId;
		$filters['ldbCourseId']      = (!empty($specializationId))?$specializationId : 1;
		$filters['examName']         = ($examName)?$examName:'none';
		$filters['fees']             = 'none';
		$filters['limit']            = ENT_MAX_LINKS_LIMIT;

		if($pageType == SPECIALIZATION_ONLY){
			$filters['examNeeded']  = 1;
			unset($filters['examName']);
		}else{
			// check for ranking india pages
			if(!empty($countryId)){
				$filters['cityNeeded']  = 1;
				unset($filters['cityId']);
			}			
		}
		
		$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
    	if($pageType == INDIA_ONLY || $pageType == INDIA_EXAM_ONLY || $pageType == INDIA_EXAM_SPECIALIZATION_ONLY){
    		return $uniqueLinksArr;	
    	}else{
    		if(count($uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return $uniqueLinksArr;	
    	}
    	


    	///////////////
		// Stage 2 //
		///////////////

    	$filters                     = array();
		$filters['categoryId']       = $categoryId;
		$filters['subCategoryId']    = $subCategoryId;
		$filters['countryId']        = 2;
		$filters['stateId']          = $stateId;
		$filters['cityId']           = $cityId;
		$filters['cityId']           = (!empty($stateId))?1:$cityId;
		$filters['ldbCourseId']      = (!empty($specializationId))?$specializationId : 1;
		$filters['examName']         = ($examName)?$examName:'none';
		$filters['fees']             = 'none';
		$filters['limit']            = ENT_MAX_LINKS_LIMIT;

    	if($pageType == INDIA_SPECIALIZATION_ONLY){
    		$filters['examNeeded']  = 1;
    		$filters['cityNeeded']  = 1;
			unset($filters['examName']);
			
			$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
    		return $uniqueLinksArr;
    	}elseif ($pageType == LOCATION_ONLY) {
    		$filters['specializationNeeded'] = 1;
    		unset($filters['ldbCourseId']);

	    	$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
	    	if(count($uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return $uniqueLinksArr;
    	}elseif ($pageType == EXAM_ONLY) {
    		$filters['feesNeeded']  = 1;
    		unset($filters['fees']);

    		$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
    		if(count($uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return $uniqueLinksArr;
    	}elseif ($pageType == SPECIALIZATION_ONLY) {
    		$filters['feesNeeded']  = 1;
    		unset($filters['fees']);

    		$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
    		return $uniqueLinksArr;
    	}elseif ($pageType == EXAM_SPECIALIZATION_ONLY) {
			$filters['examName']    = 'none';
			$filters['ldbCourseId'] = 1;

			$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
    		if(count($uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return $uniqueLinksArr;
    	}


		///////////////
		// Stage 3 //
		///////////////

    	$filters                     = array();
		$filters['categoryId']       = $categoryId;
		$filters['subCategoryId']    = $subCategoryId;
		$filters['countryId']        = 2;
		$filters['stateId']          = $stateId;
		$filters['cityId']           = (!empty($stateId))?1:$cityId;
		$filters['ldbCourseId']      = (!empty($specializationId))?$specializationId : 1;
		$filters['examName']         = ($examName)?$examName:'none';
		$filters['fees']             = 'none';
		$filters['limit']            = ENT_MAX_LINKS_LIMIT;

    	if ($pageType == LOCATION_ONLY) {
    		$filters['examNeeded'] = 1;
    		unset($filters['examName']);

	    	$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
	    	if(count($uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return $uniqueLinksArr;
    	}elseif ($pageType == EXAM_ONLY) {
    		$filters['specializationNeeded']  = 1;
    		unset($filters['ldbCourseId']);

	    	$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
	    	return $uniqueLinksArr;
    	}elseif ($pageType == EXAM_SPECIALIZATION_ONLY) {
			$filters['ldbCourseId'] = 1;

	    	$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
	    	return $uniqueLinksArr;
    	}


    	///////////////
		// Stage 4 //
		///////////////

    	$filters                     = array();
		$filters['categoryId']       = $categoryId;
		$filters['subCategoryId']    = $subCategoryId;
		$filters['countryId']        = 2;
		$filters['stateId']          = $stateId;
		$filters['cityId']           = (!empty($stateId))?1:$cityId;
		$filters['ldbCourseId']      = (!empty($specializationId))?$specializationId : 1;
		$filters['examName']         = ($examName)?$examName:'none';
		$filters['fees']             = 'none';
		$filters['limit']            = ENT_MAX_LINKS_LIMIT;

    	if ($pageType == LOCATION_ONLY) {
    		$filters['feesNeeded'] = 1;
    		unset($filters['fees']);

	    	$uniqueLinksArr = $this->getNonZeroCategoryPages($filters,$uniqueLinksArr);
	    	return $uniqueLinksArr;
    	}
	}

	public function getNonZeroCategoryPages($filters,$uniqueLinksArr){
		$categorypagemodel = $this->_ci->load->model("categoryList/categorypagemodel");
		
		$data = array();
		$data = $categorypagemodel->getNonZeroCategoryPages($filters);
		return array_merge($uniqueLinksArr, $data);

	}


	public function getCategoryPageUrl($key){
  
	     $this->_ci->load->library('categoryList/CategoryPageRequest');
   		 $this->_ci->load->helper('categoryList/category_page');


		 $request = new CategoryPageRequest();
		 $request->setNewURLFlag(1);
         $request->setData(array(
									'categoryId'    =>	$key['category_id'],
									'subCategoryId' =>	$key['sub_category_id'],
									'LDBCourseId'   =>	$key['ldbCourseId'],
									'cityId'        =>	$key['city_id'],
									'stateId'       =>	$key['state_id'],
									'countryId'     =>	$key['country_id'],
									'feesValue'     =>	$key['fees'],
									'examName'      =>	$key['exam']
         						 ));


		// need to ask
		$pageTitle        = computeFinalCrumb($request);
		$data['url']      = $request->getURL();
		$data['urlTitle'] = getPageHeadingTextForRNR('',$pageTitle,'',$request);
	
		return $data;	
	}

	function getStaticLink($data,$catlinksArr){

		$filters                  = array();
		$filters['categoryId']    = $data['categoryId'];
		$filters['subCategoryId'] = $data['subCategoryId'];
		$filters['ldbCourseId']   = 1;
		$filters['cityId']        = 1;
		$filters['stateId']       = 1;
		$filters['countryId']     = 2;
		$filters['examName']      = 'none';
		$filters['fees']          = 'none';
		$filters['limit']         = 1;
	    
	   return $this->getNonZeroCategoryPages($filters,$catlinksArr);
	    
	}


	function getRankingSeoLinkOnCoursePage($rankingFiltersData){
		if(empty($rankingFiltersData)){
			return false;
		}

		$this->_ci               = &get_instance();
		$this->categorypagemodel = $this->_ci->load->model("categoryList/categorypagemodel");
		$this->_ci->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->rankingURLManager = RankingPageBuilder::getURLManager();
		
		$data        = array();
		$locationUrl = array();
		$examUrl     = array();

		// city condition
		$data      = $this->prepareFiltersRankingData('cityId',$rankingFiltersData['city']);
		if(count($data) >=1){
			foreach($data as $row){
				$locationUrl[] = $this->getRankingPageUrl($row);
		    }
		};

		// state condition	
		if(empty($locationUrl)){
			$data 	= $this->prepareFiltersRankingData('stateId',$rankingFiltersData['state']);
			if(count($data) >=1){
				foreach($data as $row){
					$locationUrl[] = $this->getRankingPageUrl($row);
			    }
			};
		}
		
		
		// exam condition
		if(!empty($rankingFiltersData['exams']) && is_array($rankingFiltersData['exams'])){
			$randExamKey        = array_rand($rankingFiltersData['exams'],1);
			$examName           = $rankingFiltersData['exams'][$randExamKey]->getAcronym();
			$data           	= $this->prepareFiltersRankingData('examName',$examName);
			if(count($data) >=1){
				foreach($data as $row){
					$examUrl[]          = $this->getRankingPageUrl($row);
				}
			};
		}

		return array_merge($locationUrl,$examUrl);
	}

	private function prepareFiltersRankingData($key,$value){
		$filters                  = array();
		$filters['subcategoryId'] = ENGINEERING_SUBCAT_ID;
		$filters[$key]            = $value;
		$filters['countryId']     = 0;

		$uniqueRankingLinks = $this->getNonZeroRankingPagesLinks($filters);
		return $uniqueRankingLinks;
	}

	function getNonZeroRankingPagesLinks($filters){
		
		$rankingData 		= array();
		$limit 				= 1;
		$rankingData        = $this->categorypagemodel->getNonZeroRankingPages($filters,$limit);
		return $rankingData;
	}

	function getRankingPageUrl($row){
		
	    $pageIdentifier = $row['ranking_page_id']."-".$row['country_id']."-".$row['state_id']."-".$row['city_id']."-".$row['exam_id'];
	    $request = $this->rankingURLManager->getRankingPageRequest($pageIdentifier);

	    return $this->rankingURLManager->buildURL($request, 'urltitle', 1);
	}

	public function rankingSpecializationsLinkCoursePage($subcategoryIds = array(), $specializationIds = array(), $pageType = "listingpage"){
		if(empty($subcategoryIds) && empty($specializationIds)){
			return false;
		}
		$rankingModel = new ranking_model();
		if(!empty($specializationIds)){
			$rankingPages = $rankingModel->getRankingPagesBySpecializations($specializationIds);
		} else if(!empty($subcategoryIds)){
			$rankingPages = $rankingModel->getRankingPagesBySubcategory($subcategoryIds);
		}
		
		$rankingPageWithMaxRanks = $this->getRankingPageWithMaxRanks($rankingPages);
		if(!empty($rankingPageWithMaxRanks)){
			$params = array();
			$params['rankingPageId'] 		= $rankingPageWithMaxRanks['id'];
			$params['rankingPageName']   	= $rankingPageWithMaxRanks['ranking_page_text'];
			$params['countryId']   			= 2;
			$params['countryName']   		= "India";
			$seoFilterInstance 				= $this->getSeoURL($params);
			$data = array();
			return  $seoFilterInstance;
		}
	}

	
}

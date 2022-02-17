<?php

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
	
}

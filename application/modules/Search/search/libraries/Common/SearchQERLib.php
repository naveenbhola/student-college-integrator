<?php

class SearchQERLib {
	
	private $_ci;
	private $locationRepo;
	private $listingRepo;
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->builder("LocationBuilder", "location");
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->_ci->load->model('search/SearchModel', '', true);
		$this->_ci->load->model('search/search_qer_model', '', true);
		$this->_ci->config->load('search_config');
		$this->config = $this->_ci->config;
		$locationBuilder = new LocationBuilder();
		$this->locationRepo = $locationBuilder->getLocationRepository();
		
		$listingBuilder = new ListingBuilder();
		$this->listingRepo = $listingBuilder->getInstituteRepository();
		
	}
	
	public function getCountryList(){
		$coutries = $this->locationRepo->getCountries();
		$countryList = array();
		foreach($coutries as $countryObject){
			$countryList[$countryObject->getId()] = array();
			$countryList[$countryObject->getId()]['id'] = $countryObject->getId();
			$countryList[$countryObject->getId()]['name'] = $countryObject->getName();
			$countryList[$countryObject->getId()]['tier'] = $countryObject->getTier();
		}
		return $countryList;
	}
	
	public function getStatesListForCountry($countryIdList = array()){
		$statesList = array();
		$validStates = $this->getStatesMappedToShikshaListings();
		if(!empty($countryIdList)){
			foreach($countryIdList as $countryId){
				$states = NULL;
				$states = $this->locationRepo->getStatesByCountry($countryId);

				if(!empty($states)){
					foreach($states as $stateObject){
						if(in_array($stateObject->getId(), $validStates)) {
							$statesList[$stateObject->getId()] = array();
							$statesList[$stateObject->getId()]['id'] = $stateObject->getId();
							$statesList[$stateObject->getId()]['name'] = $stateObject->getName();
							$statesList[$stateObject->getId()]['country_id'] = $stateObject->getCountryId();
						}
					}
				}
			}	
		}
		
		return $statesList;
	}
	
	public function getCitiesListForCountry($countryIdList){
		$cityList = array();
		$validCities = $this->getCitiesMappedToShikshaListings();
		if(!empty($countryIdList)){
			foreach($countryIdList as $countryId){
				$cities = NULL;
				$cities = $this->locationRepo->getCities($countryId, 1);
				if(!empty($cities)){
					foreach($cities as $cityObject){
						if($cityObject->getStateId() == 1 || in_array($cityObject->getId(), $validCities)) {
							$cityList[$cityObject->getId()] = array();
							$cityList[$cityObject->getId()][0]['id'] = $cityObject->getId();
							$cityList[$cityObject->getId()][0]['name'] = $cityObject->getName();
							$cityList[$cityObject->getId()][0]['country_id'] = $cityObject->getCountryId();
							$cityList[$cityObject->getId()][0]['tier'] = $cityObject->getTier();
						}
					}
				}
			}	
		}
		
		return $cityList;
	}

	public function getCitiesMappedToShikshaListings() {
		$searchQERModel = new search_qer_model();
		$result = $searchQERModel->getCitiesMappedToShikshaListings();

		foreach ($result as $key => $value) {
			$cities[] = $value['city_id'];
		}

		return $cities;
	}

	public function getStatesMappedToShikshaListings() {
		$searchQERModel = new search_qer_model();
		$result = $searchQERModel->getStatesMappedToShikshaListings();

		foreach ($result as $key => $value) {
			$states[] = $value['state_id'];
		}

		return $states;
	}
	
	public function getVirtualCityList($virtualCityIds = array()){
		$virtualCityList = array();
		if(!empty($virtualCityIds)){
			$searchQERModel = new search_qer_model();
			$virtualCityList = $searchQERModel->getVirtualCityInformationForCities($virtualCityIds);
			return $virtualCityList;
		}
	}
	
	public function getInstituteData($instituteId = NULL){
		$instituteData = array();
		$searchmodel = new searchmodel();
		if(!empty($instituteId)){
			$this->listingRepo->disableCaching();
			$instituteObject = $this->listingRepo->find($instituteId);
			$extras = $searchmodel->getInstituteSynonyms($instituteId);
			$website = "";
			if($instituteObject != false){
				$locations = $instituteObject->getLocations();
				foreach($locations as $location) {
					$contact_obj = $location->getContactDetail();
					$website = $contact_obj->getContactWebsite();
					if(!empty($website)) {
						break;
					}
				}
				$instituteData['id'] = $instituteObject->getId();
				$instituteData['name'] = $instituteObject->getName();
				// $instituteData['abbreviation'] = $instituteObject->getAbbreviation();
				$instituteData['website'] = $website;
				$instituteData['relatedWords'] = '';
				if(!empty($extras)){
					$instituteData['relatedWords'] = implode(',', array_unique(explode(',', trim(trim($extras['synonyms'],' ,').','.trim($extras['acronyms'],' ,'),' ,'))));
				}
			}
		}
		return $instituteData;
	}
	
	public function getLdbCourseDetailsForQER($offset = 0, $limit = 1000, $getTestPrep = 0){
		$searchQERModel = new search_qer_model();
		$ldbCourseDetails = $searchQERModel->getQERLdbCourseDetails($offset, $limit, $getTestPrep);
		return $ldbCourseDetails;
	}
	
	public function getLDBCourseDetailsAlreadyAddedToQER(){
		$searchQERModel = new search_qer_model();
		$ldbCourseDetails = $searchQERModel->getLDBCourseDetailsAlreadyAddedToQER();
		return $ldbCourseDetails;
	}

	/*
	 * One time script
	 */
	public function removeTestPrepCoursesAndInstitutes(){
		$searchQERModel = new search_qer_model();
		
		//get test prep ldb	courses
		$data = $searchQERModel->getQERLdbCourseDetails(0, 10000, 1);
		foreach ($data as $value) {
			$testPrepCourseIds[] = $value['id'];
		}
		_p("Total test prep ldb courses: ".count($testPrepCourseIds));

		//get test prep institutes
		$testPrepInstituteIds = $searchQERModel->getTestPrepInstitutes();
		_p("Total test prep institutes: ".count($testPrepInstituteIds));

		//remove test prep courses and institutes from QER db
		$searchQERModel->removeTestPrepCoursesAndInstitutes($testPrepCourseIds, $testPrepInstituteIds);
	}

	function testData($showData) {
		$searchmodel = new searchmodel();

		$this->_ci->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;

		$data = $searchmodel->testData();
		if($showData) {
			_p($data); die;
		}

		$c = 0;
		foreach ($data['expIds'] as $key => $value) {
			$c += count($value);
		}
		
		$checkedInst = 0; $exactMatchSuccess = 0;
		foreach ($data['syns'] as $key => $value) {
			_p("*********************  Sno.".$key."  *********************");
			_p("Search keyword - ".$value);
			$qerFilters = $solrSearcher->getQERFiltersForSearch($value);
			$checkedQueries++; $individualScore = 0;
			foreach ($qerFilters['institute'] as $key1 => $instId) {
				if(in_array($instId, $data['expIds'][$value])) {
					//_p($instId." ========= TRUE ==========");
					$qerFilters['institute'][$key1] = $instId." (TRUE; Expected: Exact match should provide only this)";
					$successInst++; $individualScore++;
				} else {
					$qerFilters['institute'][$key1] = $instId." (FALSE)";
					//_p($instId." ========= FALSE ==========");
				}
			}
			_p("QER result - ");
			_p($qerFilters);
			if($individualScore == ($key1-1)) {
				$exactMatchSuccess++;
			}
			if(empty($qerFilters['institute'])) {
				_p("========= FALSE ==========");
			}
		}

		_p("*********************  CONCLUSION  *********************");
		_p("Number of queries(synonyms with city name in it) - ".$checkedQueries);
		_p("Partial match success - ".$successInst. " //giving institutes we are expecting, as well as other institutes");
		_p("Partial match success Rate - ".round(($successInst/$checkedQueries) * 100, 2)."%");
		_p("Exact match success - ".$exactMatchSuccess);
		_p("Exact match success Rate - ".round(($exactMatchSuccess/$checkedQueries) * 100, 2)."%");
		_p("Rest didn't match institute at all.");
	}
}
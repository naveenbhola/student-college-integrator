<?php

class SearchQER extends MX_Controller {
	private $searchQERLib;
	private $debug = false;
	public function __construct(){
		$this->load->builder('SearchBuilder');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('ListingBuilder','listing');
		$this->load->builder("LocationBuilder", "location");
		$this->load->model('search/SearchModel', '', true);
		$this->load->model('search/search_qer_model', '', true);
		$this->searchQERLib 	   = SearchBuilder::getSearchQERLib();
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
		$this->config->load('search_config');
	}
	
	public function index(){
		_p("Not a valid call");
		die();
	}
	
	public function getLocationData(){
		$coutryList = $this->searchQERLib->getCountryList();
		// $countryIds = array();
		// foreach($coutryList as $country){
		// 	if(!in_array($country['id'], $countryIds)){
		// 		$countryIds[] = $country['id'];	
		// 	}
		// }
		$countryIds = array();
		$countryIds[] = 2;
		
		$stateList = $this->searchQERLib->getStatesListForCountry($countryIds);
		$cityList = $this->searchQERLib->getCitiesListForCountry($countryIds);
		foreach ($cityList as $key1 => $iArray) {
			foreach ($iArray as $key2 => $value) {
				if((strpos($value['name'], "- Other") !== false) || (strpos($value['name'], "-Other") !== false)){
					unset($cityList[$key1][$key2]);
				}
			}
		}
		$cityList = array_filter($cityList);
		
		// $virtualCityMapping = $this->searchCommonLib->getVirtualCityMappingForSearch();
		
		// $virtualCities = array();
		// foreach($virtualCityMapping as $virtualCityId => $cities){
		// 	$virtualCities = array_merge($virtualCities, $cities);
		// }
		// $virtualCityList = $this->searchQERLib->getVirtualCityList($virtualCities);
		
		// foreach($cityList as $cityId => $cityData){
		// 	if(array_key_exists($cityId, $virtualCityList)){
		// 		$tempArr = array();
		// 		$tempArr['id'] = $virtualCityList[$cityId]['id'];
		// 		$tempArr['name'] = $virtualCityList[$cityId]['name'];
		// 		$cityList[$cityId][] = $tempArr;
		// 	}
		// }
		
		$data = array();
		$data['country'] = $coutryList;
		$data['state'] = $stateList;
		$data['city'] = $cityList;
		return $data;
	}
	
	public function getInstituteData($instituteId = NULL){
		$instituteData = array();
		if(!empty($instituteId)){
			$instituteData = $this->searchQERLib->getInstituteData($instituteId);
		}
		return $instituteData;
	}
	
	public function syncQERInstitutes() {
		return;
		ini_set('memory_limit','2048M');
		set_time_limit(864000); //Max execution time limit set for 1 complete day
		$this->setDebugStatus();
		$maxListings = -1;
		$batchSize = 100;
		$flag = true;
		$start = 0;
		$searchModel = new SearchModel();
		$searchQERModel = new search_qer_model();
		$log = array();
		while($flag){
			$ids = $searchModel->getDataForIndexing('institute', $start, $batchSize, 'ASC', 0, 1);
			if(is_array($ids) && !empty($ids) && count($ids) > 0){
				foreach($ids as $id){
					// if($id == 3240){
					// 	continue;
					// }
					$this->log("Fetching data for institute id: $id");
					$instituteData = $this->getInstituteData($id);
					$returnVal = $searchQERModel->syncInstitute($instituteData);
					$this->log("Sync status for institute id: $id ==> $returnVal");
				}
				$start = $start + $batchSize;
			} else {
				$flag = false;
			}
			if($start >= $maxListings && $maxListings != -1){
				$flag = false;
			}
		}
	}
	
	public function syncQERLdbCourses(){
		ini_set('memory_limit','2048M');
		set_time_limit(864000); //Max execution time limit set for 1 complete day
		$this->setDebugStatus();
		$this->log("Sync operation for LDB courses starts");
		$maxListings = -1;
		$batchSize = 100;
		$flag = true;
		$start = 0;
		$searchModel = new SearchModel();
		$searchQERModel = new search_qer_model();
		$log = array();
		$ldbCoursesAlreadydAdded = $this->searchQERLib->getLDBCourseDetailsAlreadyAddedToQER();
		$restrictedCourseIds = array(1);
		$totalNewRecordsAdded = 0;
		while($flag){
			$data = $this->searchQERLib->getLdbCourseDetailsForQER($start, $batchSize);
			if(is_array($data) && !empty($data) && count($data) > 0){
				foreach($data as $data){
					$tempId = $data['id'];
					$tempCourseLdbName = $data['ldb_full_name'];
					if(!in_array($tempId, $ldbCoursesAlreadydAdded) && !in_array($tempId, $restrictedCourseIds)){
						$this->log("Start sync for ldb course id: $tempId ");
						$this->log("ldb course:  $tempCourseLdbName");
						$logMessage = $searchQERModel->syncQERLdbCourses($data);
						$this->log("Sync status for ldb course id: $tempId ==> $logMessage");
						$totalNewRecordsAdded++;
					}
				}
				$start = $start + $batchSize;
			} else {
				$flag = false;
			}
			if($start >= $maxListings && $maxListings != -1){
				$flag = false;
			}
		}
		$this->log("Total new records added: " . $totalNewRecordsAdded);
		$this->log("Sync operation for LDB courses ends");
	}
	
	public function syncQERDeletedInstituteById($instituteId = NULL){
		$this->setDebugStatus();
		$this->log("Sync operation for deleted institute id: $instituteId starts");
		if(!empty($instituteId)){
			$searchQERModel = new search_qer_model();
			$returnVal = $searchQERModel->syncDeletedInstitute($instituteId);
			$this->log("Sync status for deleted institute id: $id ==> $returnVal");
		} else {
			$this->log("Institute id empty");	
		}
	}
	
	public function syncQERInstituteById($instituteId = NULL){
		$this->setDebugStatus();
		$this->log("Fetching data for institute id: $instituteId");
		if(!empty($instituteId)){
			$instituteData = $this->getInstituteData($instituteId);
			$searchQERModel = new search_qer_model();
			$returnVal = $searchQERModel->syncInstitute($instituteData);
			$this->log("Sync status for institute id: $instituteId ==> $returnVal");	
		} else {
			$this->log("Institute id empty");	
		}
	}

	public function syncQERExams() {
		$this->setDebugStatus();
		$this->log("Stream sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();

		$examsData = $searchModel->getDataForIndexingNew('exams');
		
		if(!empty($examsData)){
			$searchQERModel->syncExams($examsData);
		}
		$this->log("Exam sync process ends");
		//_p('Done');
	}

	public function syncQERCareers() {
		$this->setDebugStatus();
		$this->log("Careers sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();

		$careersData = $searchModel->getDataForIndexingNew('careers');
		
		print_r($careersData);
		if(!empty($careersData)){
			$searchQERModel->syncCareers($careersData);
		}
		$this->log("Career sync process ends");
		//_p('Done');
	}

	
	public function syncQERStreams(){
		
		$this->setDebugStatus();
		$this->log("Stream sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$streamsData = $searchModel->getDataForIndexingNew('streams');
			
		if(!empty($streamsData)){
			$searchQERModel->syncStreams($streamsData);
		}
		$this->log("Stream sync process ends");
	}

	public function syncQERSubStreams(){
		$this->setDebugStatus();
		$this->log("Sub-Stream sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$subStreamsData = $searchModel->getDataForIndexingNew('substreams');
			
		if(!empty($subStreamsData)){
			$searchQERModel->syncSubStreams($subStreamsData);
		}
		$this->log("Sub-Stream sync process ends");	
	}

	public function syncQERSpecailizations(){
		$this->setDebugStatus();
		$this->log("Specialization sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$specializationData = $searchModel->getDataForIndexingNew('specializations');
			
		if(!empty($specializationData)){
			$searchQERModel->syncSpecializations($specializationData);
		}
		$this->log("Specialization sync process ends");		
	}

	public function syncQERBaseCourses(){
		$this->setDebugStatus();
		$this->log("Base Courses sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$coursesData = $searchModel->getDataForIndexingNew('base_courses');
			
		if(!empty($coursesData)){
			$searchQERModel->syncQERBaseCourses($coursesData);
		}
		$this->log("Base Courses sync process ends");			
	}

	public function syncQERInstitutesNew(){
		$this->setDebugStatus();
		$this->log('Institutes sync Process Starts');
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$start = 0;
		$batchSize = 500;
		$flag = true;
		while($flag){
			$instituteData = $searchModel->getDataForIndexingNew('institutes',$start,$batchSize);	
			
			if(!empty($instituteData) && count($instituteData) > 0 && is_array($instituteData)){
				$searchQERModel->syncInstituteNew($instituteData);
			}else{
				$flag = false;
			}
			if(!$flag) break;

			$start += $batchSize;
		}
		$this->log('Institutes sync process ends');
	}

	public function syncQERUniversties(){
		$this->setDebugStatus();
		$this->log('Universities sync process starts');
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$start = 0;
		$batchSize = 500;
		$flag = true;
		while($flag){
			$universityData = $searchModel->getDataForIndexingNew('university',$start,$batchSize);
		
			if(!empty($universityData) && count($universityData) > 0 && is_array($universityData)){
				$searchQERModel->syncUniversityNew($universityData);
			}else{
				$flag = false;
			}
			if(!$flag) break;

			$start += $batchSize;
		}
		$this->log('Universities sync process ends');
	}

	public function syncDeletedInstituteNew(){
		$this->setDebugStatus();
		$this->log('Deleted Institutes sync Process Starts');
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$start = 0;
		$batchSize = 2;
		$flag = true;
		while($flag){
			$instituteData = $searchModel->getDeletedInstitutesNew($start,$batchSize);		
			if(!empty($instituteData) && count($instituteData) > 0 && is_array($instituteData)){
				$searchQERModel->syncDeletedInstituteNew($instituteData);
			}else{
				$flag = false;
			}
			if(!$flag) break;	
			$start += $batchSize;
		}	
		$this->log('Deleted Institutes sync process ends');
	}

	public function syncPopularGroups(){
		$this->setDebugStatus();
		$this->log("Popular Groups sync process starts");
		
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$groupsData = $searchModel->getDataForIndexingNew('popular_groups');
		
		if(!empty($groupsData)){
			$searchQERModel->syncPopularGroups($groupsData);
		}
		$this->log("Popular Groups sync process starts");
	}


	public function syncCertificateProvider(){
		$this->setDebugStatus();
		$this->log('Certificate Providers Sync Starts');
		$searchQERModel = new search_qer_model();
		$searchModel = new SearchModel();
		$certiData = $searchModel->getDataForIndexingNew('certificate_providers');
		if(!empty($certiData)){
			$searchQERModel->syncCertificateProvider($certiData);
		}
		$this->log('Certificate Providers Sync Ends');
	}

	public function syncQERLocation(){
		ini_set('memory_limit','2048M');
		set_time_limit(864000); //Max execution time limit set for 1 complete day
		$this->setDebugStatus();
		$this->log("Location sync process starts");
		$locationData = $this->getLocationData();
		$searchQERModel = new search_qer_model();
		
		$countries = $locationData['country'];
		$states = $locationData['state'];
		$cities = $locationData['city'];
		$log = array();
		if(!empty($countries)){
			$this->log("Countries sync starts");
			$log['country'] = $searchQERModel->syncCountries($countries);
			$this->log("Countries sync status", $log['country']);
		}
		if(!empty($states)){
			$this->log("States sync starts");
			$log['state'] = $searchQERModel->syncStates($states);
			$this->log("States sync status", $log['state']);
		}
		if(!empty($cities)){
			$this->log("Cities sync starts");
			$log['city'] = $searchQERModel->syncCities($cities);
			$this->log("Cities sync status", $log['city']);
		}
		$this->log("Location sync process ends");
	}
	
	public function syncQERDBWithShiksha(){
		$this->validateCron();
		ini_set('memory_limit','2048M');
		set_time_limit(864000); //Max execution time limit set for 1 complete day
		$this->setDebugStatus();
		$this->debug =  true;

		$this->log("QER DB Sync starts");
		$this->log("=================================================================");
		
		$this->log("QER Location sync starts");
		$this->syncQERLocation();
		$this->log("QER Location sync ends");

		$this->log("QER Exam sync starts");
		$this->syncQERExams();
		$this->log("QER Exam sync ends");

		$this->log("QER Stream sync starts");
		$this->syncQERStreams();
		$this->log("QER Stream sync ends");
		
		$this->log("QER Sub-Stream sync starts");
		$this->syncQERSubStreams();
		$this->log("QER Sub-Stream sync ends");

		$this->log("QER Specialization sync starts");
		$this->syncQERSpecailizations();
		$this->log("QER Specialization sync ends");

		$this->log("QER Base Courses sync starts");
		$this->syncQERBaseCourses();
		$this->log("QER Base Courses sync ends");

		$this->log("QER Institute sync starts");
		$this->syncQERInstitutesNew();
		$this->log("QER Institute sync ends");

		$this->log("QER Institutes Deleted sync starts");
		$this->syncDeletedInstituteNew();
		$this->log("QER Institutes Deleted sync ends");
		
		$this->log("QER Popular Groups sync starts");
		$this->syncPopularGroups();
		$this->log("QER  Popular Groups sync ends");

		$this->log("QER Certificate Providers sync starts");
		$this->syncCertificateProvider();
		$this->log("QER  Certificate Providers sync ends");

		$this->log("=================================================================");
		$this->log("QER DB Sync ends");
	}
	
	public function syncMissingInstitutesWithQER(){
		ini_set('memory_limit','2048M');
		set_time_limit(864000);
		$this->setDebugStatus();
		$this->debug =  true;
		$searchQERModel = new search_qer_model();
		$instituteIds 	= $searchQERModel->getMissingInstitutesFromShikshaDBForQER();
		foreach($instituteIds as $instituteId){
			$this->syncQERInstituteById($instituteId);
		}
	}
	
	private function setDebugStatus(){
		if($this->security->xss_clean($_REQUEST['debug']) == "true"){
			$this->debug = true;	
		}
	}
	
	public function log($logMessage = "", $data = array()){
		if($this->debug){
			if(!empty($data)){
				_p("QERLOG:: $logMessage " . print_r($data, true));
				error_log("QERLOG:: $logMessage " . print_r($data, true));	
			} else {
				_p("QERLOG:: $logMessage ");
				error_log("QERLOG:: $logMessage ");	
			}
		}
	}

	/*
	 * Nikita Jain
	 * -----------
	 * One time cron to remove existing test prep entries in QER DB (LF-4001)
	 */
	public function removeTestPrepCoursesAndInstitutesFromQER() {
		$this->searchQERLib->removeTestPrepCoursesAndInstitutes();
		_p('DONE');
	}

	public function testData($showData=0) {
		$this->searchQERLib->testData($showData);
	}
}

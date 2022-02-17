<?php

class Demo extends MX_Controller{
	function __construct(){

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();

	    $this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	    $this->load->library('listingCommon/ListingCommonLib');
	    $this->lib = new ListingCommonLib;

     	$this->load->library('nationalCourse/CourseDetailLib');
	    $this->lib1 = new CourseDetailLib;

		$this->load->builder("listingBase/ListingBaseBuilder");
		$baseCourseBuilder = new ListingBaseBuilder();
		$this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();

		$this->load->library('nationalInstitute/InstitutePostingLib');
	    $this->lib2 = new InstitutePostingLib;
		
		$this->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();

		$this->load->builder('location/LocationBuilder');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();

	}

	public function a(){
		// $a = $this->locationRepository->findCity(87);
		// _p($a);
		// _p("==".$a->getVirtualCityId());die;
		// insert into tuserReputationPoint(userId, points, previouspoints) VALUES(6313823, 0 ,0);
		// 6313823
		/*$a = $this->locationRepository->findCity(1);
		_p($a);
		_p($a->getVirtualCityId());
		
		// $a = $this->courseRepo->viewCountLastOneYear(array(2364,1653));
		// _p($a);
		// die;
		// // _p($a);die;
		// $a = $this->courseRepo->findMultiple(array(2364,250251));
		// _P($a);
		// die;
		*/
		// $this->courseRepo->disableCaching();
		$a = $this->courseRepo->find(198720,'full');
		// _p($a);
		die;
		// _p($a->getPlacements()->getSalary());die;
		_P($a->getMainLocation()->getContactDetail());die;
		// _p($a->getEligibility());die;
		_p($a->getDeliveryMethod()->getName());die;
		// var_dump($a->isDisabled());die;
		var_dump($a->isExecutive());die;
		_p($a->isTwinning());die;
		_p($a->getPlacements()->getSalary());die;
		// $a = $this->courseRepo->find(2364,'full');
		$b = $a->getCourseTypeInformation();
		$c = $b['entry_course'];
		$d = $a->getRecognition();
		foreach ($d as $key => $value) {
			_p($value->getName());
			_p($value->getId());
		
		}
		die;
		$inputArr[0]['streamId'] = 2;
		$inputArr[0]['substreamId'] = "1";
		$inputArr[0]['specializationId'] = "20";

/*		$inputArr[1]['streamId'] = 3;
		$inputArr[1]['substreamId'] = "any";
		$inputArr[1]['specializationId'] = "none";*/
		$a = $this->lib1->getClientCoursesByBaseEntities(2,1,20);
		_p($a);
		
		/*$b = $a->getLocations();
		$c = $b[176]->getContactDetail();*/
		_p($c);
		// _p($a);
		// var_dump($a->getPlacements());
		die;
		// $b = $this->courseRepo->findMultiple(array(250112,250144),array('location'));
		_p($b);die;
		// _p($a->getPrimaryHierarchy());
		// _p($a->getDuration());
	}

	public function b(){
		$a = $this->instituteRepo->find(486,'full');
		_p($a);
	}

	public function c(){
		$a = $this->lib->getCustomizedBrochureData('course',array(2364,250251));
		// $a = $this->lib->getCustomizedBrochureData('institute',array(486,48896));
		_P($a);
		$a = $this->lib->getCustomizedBrochureData('course',2364);
		_P($a);
		$a = $this->lib->getCustomizedBrochureData('course',250251);
		_P($a);die;
		/*$a = $this->lib->listingViewCount('institute',array(772,486,307));
		_p($a);
		$a = $this->lib->getNationalListingsLastModifyDate('institute',array(772,486,307));
		_p($a);
		$a = $this->lib->getCustomizedBrochureData('course',2364);
		_P($a);*/

		$a = $this->lib1->getCourseForInstituteLocationWise(486);
			// $a = $this->lib1->getClientCoursesByBaseEntities(2,2,24);
			_p($a);die;
		_p($a);

	}

	function d($a){
		// $a = $this->input->post('courseId');
		// $a = $this->baseCourseRepo->getLevelCredentailOfBaseCourses(array(1,4));
		// _p($a);
		// die;
		// $a = $this->baseCourseRepo->findMultiple(array(1,4));
		// _p($a);die;
	/*	$a = $this->baseCourseRepo->getBaseCoursesByCredential(10);
		_p($a);die("@222222");*/
		// $courses = array($a);
		$courses = explode(",", $a);
		_p($courses);
		$courseStatus = $this->lib1->getCourseStatus($courses);
		_P($courseStatus);
	}


	function e(){
		$hierarcyData = $this->nationalIndexingModel->fetchHierachiesData();
		$processedData = $this->_processA($hierarcyData);
		unset($hierarcyData);

		$streamData = $processedData['streamData'];
		$substreamData = $processedData['substreamData'];
		$specData = $processedData['specData'];
		$baseCourseData = $processedData['baseCourseData'];
		$credentailData = $processedData['credentailData'];
		unset($processedData);

		$basicData = $this->nationalIndexingModel->fetchBasicData();
		$processedData = $this->_processB($basicData);
		unset($basicData);

		$educationTypeData = $processedData['educationType'];
		$deliveryMethodData = $processedData['deliveryMethod'];
		$parentMappingData = $processedData['parentMapping'];
		unset($processedData);

		$examsData = $this->nationalIndexingModel->fetchExamsData();
		$processedData = $this->_processC($examsData);
		unset($examsData);
		$examsData = $processedData['examsData'];
		unset($processedData);

		$locationsData = $this->nationalIndexingModel->fetchLocationsData();

		$processedData = $this->_processD($locationsData);
		unset($locationsData);

		$locationsData = $processedData['locationsData'];

		unset($processedData);

		$dataToProcess = $this->nationalIndexingModel->findCategoryPageData(0,2);

		foreach ($dataToProcess as $key => $value) {
			_p($value);
			$streamId = $value['stream_id'];
			$substreamId = $value['substream_id'];
			$specializationId = $value['specialization_id'];
			$baseCourse = $value['base_course_id'];
			$educationType = $value['education_type'];
			$deliveryMethod = $value['delivery_method'];
			$credential = $value['credential'];
			$examId = $value['exam_id'];
			$cityId = $value['city_id'];
			$stateId = $value['state_id'];
			$matchA = array();

			if(!empty($examId)){
				$matchExams = $examsData[$examId];
			}
			if(!empty($streamId)){
				$matchStreams = $streamData[$streamId];
			}
			if(!empty($substreamId)){
				$matchsubStreams = $substreamData[$substreamId];
			}
			if(!empty($specializationId)){
				$matchSpecialization = $specData[$specializationId];
			}
			if(!empty($baseCourse)){
				$matchBaseCourse = $baseCourseData[$baseCourse];
			}
			if(!empty($educationType)){
				$matchEducationType = $educationTypeData[$educationType];
			}
			if(!empty($deliveryMethod)){
				$matchDeliveryMethod = $deliveryMethodData[$deliveryMethod];
			}
			if(!empty($credential)){
				$matchCredential = $credentailData[$credential];
			}

			// _p($locationsData);die;
			if(!empty($stateId)){
				// Virtual City Case
				if($stateId == -1){

					$matchedStateData = array();
					$mainCityFromVirtualCity = $this->locationRepository->getCitiesByVirtualCity($cityId);
					foreach ($mainCityFromVirtualCity as $cityObject) {
						
						$cityId = $cityObject->getId();
						$stateId = $cityObject->getStateId();					
						if(!empty($stateId) && !empty($cityId)){
							$tempData = $locationsData[$stateId][$cityId];
							$matchedStateData = array_merge($matchedStateData, $tempData);
						}
					}
					$matchedStateData = array_values(array_unique($matchedStateData));
				}else{ // Non Virtual City Case
					if($cityId == 1){
						$tempData = array();
						$tempData = $locationsData[$stateId];
						$matchedStateData = array();
						foreach ($tempData as $cityId => $courseIdsList) {
							$matchedStateData = array_merge($matchedStateData, $courseIdsList);
						}

						$matchedStateData = array_values(array_unique($matchedStateData));
					}else{
						$matchedStateData = $locationsData[$stateId][$cityId];
					}
				}
			}

			$tempArray = [];

			if (count($matchExams) >0) $tempArray[] = $matchExams;
			if (count($matchStreams) >0) $tempArray[] = $matchStreams;
			if (count($matchsubStreams) >0) $tempArray[] = $matchsubStreams;
			if (count($matchSpecialization) >0) $tempArray[] = $matchSpecialization;
			if (count($matchBaseCourse) >0) $tempArray[] = $matchBaseCourse;
			if (count($matchEducationType) >0) $tempArray[] = $matchEducationType;
			if (count($matchDeliveryMethod) >0) $tempArray[] = $matchDeliveryMethod;
			if (count($matchCredential) >0) $tempArray[] = $matchCredential;
			if (count($matchedStateData) >0) $tempArray[] = $matchedStateData;


			if(count($tempArray) == 0){
				_p("===CASE 1");
				$intersect = array();
			}else if(count($tempArray) == 1){
				_p("===CASE 2");
				$intersect = $tempArray[0];
			}else{
				_p("===CASE 3");
				$intersect = call_user_func_array('array_intersect', $tempArray);	
			}
			$finalIntersect = array();
			// _p($parentMappingData);
			// _p($intersect);die;

			foreach ($intersect as $courseId) {
				$x = $parentMappingData[$courseId];
				$finalIntersect[$x] = 1;
			}
			// _p($finalIntersect);die;
			_p("=== coumt for =  ".$value['id']."== is ".count($finalIntersect));
			/*if(!empty($cityId)){
				$matchBaseCourse = $baseCourseData[$baseCourse];
			}
			if(!empty($stateId)){
				$matchBaseCourse = $baseCourseData[$baseCourse];
			}*/

		}



		echo memory_get_peak_usage();
		
		
	}

	function _processA($hierarcyData){
		$streamSubStreamSpecData = array();
		
		foreach ($hierarcyData as $key => $value) {
			$streamData[$value['stream_id']][] = $value['course_id'];
			$substreamData[$value['substream_id']][] = $value['course_id'];
			$specData[$value['specialization_id']][] = $value['course_id'];
			$baseCourseData[$value['base_course']][] = $value['course_id'];
			$credentailData[$value['credential']][] = $value['course_id'];
		}
		$finalResult['streamData'] = $streamData;
		$finalResult['substreamData'] = $substreamData;
		$finalResult['specData'] = $specData;
		$finalResult['baseCourseData'] = $baseCourseData;
		$finalResult['credentailData'] = $credentailData;
		return $finalResult;
	}

	function _processB($basicData){
		
		// _p($basicData);die;
		foreach ($basicData as $key => $value) {
			if(empty($value['delivery_method'])){
				$value['delivery_method'] = 33;
			}
			$educationType[$value['education_type']][] = $value['course_id'];
			$deliveryMethod[$value['delivery_method']][] = $value['course_id'];
			$parentMapping[$value['course_id']] = $value['primary_id'];
		}

		$finalResult['educationType'] = $educationType;
		$finalResult['deliveryMethod'] = $deliveryMethod;
		$finalResult['parentMapping'] = $parentMapping;
		return $finalResult;
	}

	function _processC($examsData){

		$processedExamsData = array();
		foreach ($examsData as $key => $value) {
			if(empty($value['exam_id'])) continue;
			$processedExamsData[$value['exam_id']][] = $value['course_id'];
		}
		$finalResult['examsData'] = $processedExamsData;
		return $finalResult;
	}

	function _processD($locationsData){
		$processedLocationsData = array();
		foreach ($locationsData as $key => $value) {
			$processedLocationsData[$value['state_id']][$value['city_id']][] = $value['course_id'];
		}
		$finalResult['locationsData'] = $processedLocationsData;
		return $finalResult;
	}

	function gg(){

	ini_set("max_execution_time", "-1");
	ini_set('memory_limit', "2000M");
	// $courseIdsArray = array(30967);
	
	$courseIdsArray = $this->nationalIndexingModel->gggg1();
	$this->load->library('nationalInstitute/InstitutePostingLib');
    $this->institutePostingLib = new InstitutePostingLib;
    $count = 0;
    
	foreach ($courseIdsArray as $courseId) {
		error_log($courseId."===================");

		$count++;
		if($count % 1000  == 0){
			_P("Count is ".$count);
			error_log("Count is ".$count);
			
		}
		$courseObject = $this->courseRepo->find($courseId);
		$primaryId = $courseObject->getInstituteId();
		$primaryName = $courseObject->getInstituteName();
		
		$primaryType = $courseObject->getInstituteType();
		if(empty($primaryId) || empty($primaryType)){
			continue;
		}

		$courseHierarchy = $this->institutePostingLib->getParentHierarchyById($primaryId,$primaryType);
		_p($courseHierarchy);die;
		
		if(empty($courseHierarchy)){
			continue;
		}
		$dataToInsert = array();
		foreach ($courseHierarchy as $key => $value) {
			
			$temp = array();
			$temp['course_id'] = $courseId;
			$temp['primary_parent_id'] = $primaryId;
			$temp['primary_parent_type'] = $primaryType;
			$temp['primary_parent_name'] = $primaryName;

			$temp['hierarchy_parent_id'] = $value['listing_id'];
			$temp['hierarchy_parent_name'] = $value['name'];
			if($value['type'] == "university"){
				$temp['hierarchy_parent_type'] = "university";
			}else{
				$temp['hierarchy_parent_type'] = "institute";
			}
			
			$temp['disabled_url'] = $value['disabled_url'];
			$temp['id_dummy'] = $value['is_dummy'];
			$temp['is_satellite'] = $value['is_satellite'];
			$temp['is_autonomous'] = $value['is_autonomous'];
			$temp['is_aiu_membership'] = $value['is_aiu_membership'];
			$temp['is_open_university'] = $value['is_open_university'];
			$temp['is_ugc_approved'] = $value['is_ugc_approved'];
			$temp['university_specification_type'] = $value['university_specification_type'];
			if($value['type'] != "university"){
				$temp['institute_specification_type'] = $value['type'];
			}else{
				$temp['institute_specification_type'] = NULL;
			}
			
			$temp['ownership'] = $value['ownership'];
			$temp['accreditation'] = $value['accreditation'];
		
		$dataToInsert[] = $temp;

		}
		
		$this->nationalIndexingModel->gggg($dataToInsert);

	}

 }


 function gg1(){

	ini_set("max_execution_time", "-1");
	ini_set('memory_limit', "2000M");
	
	$comleteResult = $this->nationalIndexingModel->gggg1();

	$instituteCourseData = $comleteResult[0];
	$instituteNames = $comletehierarchy_Result[1];
	$instituteSat = $comleteResult[2];
	
	$this->load->library('nationalInstitute/InstitutePostingLib');
    $this->institutePostingLib = new InstitutePostingLib;
    $count = 0;
    $dataToInsert = array();
    foreach ($instituteCourseData as $institute => $courseIdsArray) {
    	
    	$count++;
		if($count % 1000  == 0){
			_P("Count is ".$count);
			error_log("Count is ".$count);
			
		}
    	$dataToInsert = array();
    	$isEmptyCourses = true;
    	list($instituteId, $instituteType) = explode("_", $institute);
    	if(empty($instituteId) || empty($instituteType)) continue;

    	$parentHierarchy = $this->institutePostingLib->getParentHierarchyById($instituteId,$instituteType);
    	
    	$anotherUnivFlag = 0;
    	$anotherUnivData = 0;
    	foreach ($parentHierarchy as $key => $value) {
    		if($value['type'] == "university"){
    			$anotherUnivData = $value['listing_id'];
    		}
    	}
    	if(empty($parentHierarchy)) continue;
    	$anotherSatFlag = 0;
    	foreach ($parentHierarchy as $value) {
    		if(empty($anotherSatFlag) && !empty($value['is_satellite'])){
    			$anotherSatFlag = $value['is_satellite'];
    		}
    		$courseIdsArray = array_filter($courseIdsArray);
    		foreach ($courseIdsArray as $courseId) {
    			$isEmptyCourses = false;

    			$temp = array();
				$temp['course_id'] = $courseId;
				$temp['primary_parent_id'] = $instituteId;
				$temp['primary_parent_type'] = $instituteType;
				$temp['hierarchy_parent_id'] = $value['listing_id'];
				if($value['type'] == "university"){
					$temp['hierarchy_parent_type'] = "university";
				}else{
					$temp['hierarchy_parent_type'] = "institute";
				}
			
				$temp['primary_is_satellite'] = $instituteSat[$instituteId];
				$dataToInsert[] = $temp;
    		}


    		if($isEmptyCourses){
    			$temp['course_id'] = 0;
				$temp['primary_parent_id'] = $instituteId;
				$temp['primary_parent_type'] = $instituteType;
				$temp['hierarchy_parent_id'] = $value['listing_id'];
				if($value['type'] == "university"){
					$temp['hierarchy_parent_type'] = "university";
				}else{
					$temp['hierarchy_parent_type'] = "institute";
				}
				
				$temp['primary_is_satellite'] = $instituteSat[$instituteId];
				$dataToInsert[] = $temp;
    		}
    	}
    	$this->nationalIndexingModel->gggg($dataToInsert);

    }

 }


    function getAllCoursesForInstitutesOLD($institutesIds,$directOrAllCourses="all",$excludeInstituteTypes = array(), $excludeSatellite = true){

    	$instituteIds = array(1);
    	if(empty($instituteIds)) return;
    	if(!is_array($instituteIds)) return;

    	$result = $this->nationalIndexingModel->getAllCoursesForInstitutesFromFlatTable($instituteIds,$directOrAllCourses, $excludeInstituteTypes, $excludeSatellite);
    	
    	$finalResult = array();
    	$excludeInstituteTypesList = array();
    	$excludeSatelliteTypesList = array();
    	$universityCount = array();
    	foreach ($result as $key => $value) {

 			// Main University Count FOr Satellite Logic Condtion for University
			if($value['listing_type'] == "university"){
				$universityCount[$value['hierarchy_parent_id']]++;
				$universityCountList[$value['hierarchy_parent_id']][] = $value['primary_parent_id'];
				// _p($universityCountList);
			}

			if($value['is_satellite'] && $excludeSatellite){
				$excludeSatelliteTypesList[] = $value['hierarchy_parent_id'];
			}

			// EXCLUDE excludeInstituteTypes CONDITION
			if(in_array($value['institute_specification_type'], $excludeInstituteTypes)) {
				$excludeInstituteTypesList[] = $value['hierarchy_parent_id'];
				continue;	
			}
			if(in_array($value['hierarchy_parent_id'], $excludeInstituteTypesList)) continue;

			// DIRECT/ALL Courses
   			if($directOrAllCourses == "direct" && $value['hierarchy_parent_id']!=$value['primary_parent_id']) continue;

   			

			// EXCLUDE SATELLITE CONDITION
			/*if($excludeSatellite && !empty($universityCount[$value['hierarchy_parent_id']]) && $universityCount[$value['hierarchy_parent_id']] > 1) continue;
			if($excludeSatellite && in_array($value['hierarchy_parent_id'], $excludeSatelliteTypesList)) continue;*/


			// POPULATE THE DATA 
			$finalResult[$value['hierarchy_parent_id']]['courseIds'][] = $value['course_id'];	
			$finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']][] = $value['course_id'];
			$finalResult[$value['hierarchy_parent_id']]['type'][$value['primary_parent_id']] = $value['listing_type'];


			// REMOVE BLANK VALUES
    		$finalResult[$value['hierarchy_parent_id']]['courseIds'] = array_filter(array_unique($finalResult[$value['hierarchy_parent_id']]['courseIds']));
    		$finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']] = array_filter(array_unique($finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']]));

    		$finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'] = array_filter($finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses']);
/*
    		$finalResult[$value['hierarchy_parent_id']]['courseIds'] = array_filter($finalResult[$value['hierarchy_parent_id']]['courseIds']);*/

    	}
    	_p($finalResult);
    }

    
    // ANKIT GARG API
    function ddd(){
    	$inputArr = array(486 => 'institute',307=>'institute');
    	$this->load->library('nationalInstitute/InstitutePostingLib');
        $this->institutePostingLib = new InstitutePostingLib;
        foreach ($inputArr as $primaryId=>$primaryType) {

        	$courseHierarchy[$primaryId] = $this->institutePostingLib->getParentHierarchyById($primaryId,$primaryType);
        }
        _p($courseHierarchy);die;
    	
    }

    function fff(){
    	$this->load->library('nationalInstitute/InstitutePostingLib');
        $this->institutePostingLib = new InstitutePostingLib;
        $a = $this->institutePostingLib->getInstituteParentHierarchyFromFlatTale(array(486,307,1));
        _P($a);
    }

    // ROMIL API == ACTUAL
    function eee($id,$type){
    	$inputArr = array($id => $type);
    	$this->load->library('nationalInstitute/InstituteDetailLib');
        $this->institutePostingLib = new InstituteDetailLib;
        foreach ($inputArr as $primaryId=>$primaryType) {
        	$courseHierarchy[$primaryId] = $this->institutePostingLib->getInstituteCourseIds($primaryId,$primaryType);
        }
        // ksort($courseHierarchy[1]['type']);
        _p($courseHierarchy);die;
    }

    // ROMIL API  === CHANGED === OPTIMIZED
    function getAllCoursesForInstitutes($institutesId,$directOrAllCourses="all"){

    	$instituteIds = array(50159);
    	$this->load->library('nationalInstitute/InstituteDetailLib');
        $this->instituteDetailLib = new InstituteDetailLib;
        $result = $this->instituteDetailLib->getAllCoursesForInstitutes($institutesId);
        // sort($result[1]['courseIds']);
        // _p($result);die;
        echo json_encode($result);
    	
    }

    function addCourse($courseId){
    	$this->lib1111 = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    	$this->lib1111->addCourse($courseId);
    	// var_dump($result);
    }

    function addInst($instId,$type){
    	$this->lib1111 = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    	$this->lib1111->addInstitute($instId,$type);
    	// var_dump($result);
    }

    function aa(){
    	$this->lib1111 = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    	$this->lib1111->aa(345);
    }


	function bbb(){
		$this->graph = $this->load->library('Neo4j');
		$client = $this->graph->get_db();
		$client->run("CREATE (n:demo)");
	}

	function func1(){
		$this->lib1111 = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    	$this->lib1111->populateFlatTable();
	}

	function getLocations() {
		$data = $this->locationRepository->findMultipleCities(array(30, 138, 171));
		_p($data);
		
		$data = $this->locationRepository->getCitiesByMultipleStates(array(109, 126, 103));
		_p($data);

		//$this->locationRepository->getCitiesByVirtualCity();

		$data = $this->locationRepository->getCitiesHavingZones();
		_p($data);
		
		$data = $this->locationRepository->getCities(2, 1);
		_p($data);
		
		$cities = $this->locationRepository->getCitiesByMultipleTiers(array(1, 2, 3), 2);
		_p($cities); die;
	}

	function clearLocationCache() {
		$this->locationRepository->clearLocationCache();
	}
}

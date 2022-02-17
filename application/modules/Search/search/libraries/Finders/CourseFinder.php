<?php

class CourseFinder {
	
	private $_ci;
	private $listingBuilder;
	private $courseObject;
	private $caDiscussionHelper;
	
	public function __construct(){
		$this->_ci = & get_instance();
		
		$this->_ci->load->model("listing/coursemodel", "", true);
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->library("listing/listing_client", "", true);
		$this->_ci->load->helper("search/SearchUtility");
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
		
		$this->_ci->load->service('CurrencyConverterService','categoryList');
    	$this->currencyConverterService = new CurrencyConverterService;
		
		$this->_ci->load->library('Category_list_client');
		$this->categoryClient = new Category_list_client();
		
		$this->_ci->load->library('listing/cache/ListingCache');
		$this->listingCache = new ListingCache;
		
		$this->courseObject = null;

		$this->_ci->load->library('CA/CADiscussionHelper');
        $this->caDiscussionHelper =  new CADiscussionHelper();
		
		$this->_ci->load->builder("LocationBuilder", "location");
		$locationBuilder = new LocationBuilder();
		$this->locationRepo = $locationBuilder->getLocationRepository();

		$this->_ci->load->library('ShikshaPopularity/ShikshaPopularityDataLib');
		$this->shikshaPopularityDataLib = new ShikshaPopularityDataLib();

	}
	
	public function getData($id = null) {
		if($id == null){
			return array();
		}
		$courseRepos = $this->listingBuilder->getCourseRepository();
		$courseRepos->disableCaching();
		$courseDataObject = $courseRepos->find($id);
		if($courseDataObject instanceOf AbroadCourse)
		{ // this code doesn't work for abroad course, it has a different finder itself: AbroadCourseFinder
			return array();
		}
		$courseId = $courseDataObject->getId();
		$courseIndexData = false;
		if(!empty($courseDataObject) && !empty($courseId)){
			$this->setCourseObject($courseDataObject);
			$courseIndexData = $this->preprocessRawData($courseDataObject);
		}
		return $courseIndexData;
	}
	
	public function preprocessRawData($courseDataObject = null) {

		/*
		All the course documents are location based. As course details can vary depends on its location.
		count($courseData['locations']) > 1 ==> Its a multilocation course
		*/
			$courses = array();
			$course = array();
			
			$course['course_id'] 					= $this->getCourseId($courseDataObject);
			$course['course_title'] 				= $this->getCourseTitle($courseDataObject);
			$course['course_type'] 					= $this->getCourseType($courseDataObject);
			$course['course_level'] 				= $this->getCourseLevel($courseDataObject);
			$course['course_level_1'] 				= $this->getCourseLevel1($courseDataObject);
			$course['course_level_2'] 				= $this->getCourseLevel2($courseDataObject);
			$course['course_level_cp'] 				= $this->getCourseLevelForCategoryPage($courseDataObject);
			$course['course_order']     			= $this->getCourseOrder($courseDataObject);
			
			$course['course_duration_unit'] 		= $this->getCourseDurationUnit($courseDataObject);
			$course['course_duration_value']	 	= $this->getCourseDurationValue($courseDataObject);
			$course['course_duration_in_hours']	 	= $this->getCourseDurationValueInHours($courseDataObject);
			$course_attributes 						= $this->getCourseAttributeList($courseDataObject);
			$course['course_attributes']			= $course_attributes['attributes'];
			$course['course_affiliations']			= $course_attributes['affiliations'];
			$course['course_eligibility_required'] 	= $this->getCourseEligibility($courseDataObject, 'required');
			$course['course_eligibility_testprep'] 	= $this->getCourseEligibility($courseDataObject, 'testprep');
			$course['course_salient_feature'] 		= $this->getCourseSalientFeatures($courseDataObject);
			$course['course_seo_url'] 				= $this->getCourseSeoUrl($courseDataObject);
			$course['course_pack_type'] 			= $this->getCoursePackType($courseDataObject);
			
			$course['course_degree_pref']			= $this->getCourseDegreePref($courseDataObject);
			$course['course_class_timings']			= $this->getCourseClassTimings($courseDataObject);
			
			$course_valid_exams						= $this->getCourseValidExams($courseDataObject);
			$course['course_RnR_valid_exams']		= $course_valid_exams['name'];
			$this->_populateCourseValidExamScore($course, $course_valid_exams);
			
			$course['course_last_modified_date']	= solrDateFormater($this->getCourseLastUpdatedDate($courseDataObject));
			
			// course ldb ids
			$course['course_ldb_id'] 		= $this->getLdbCourseList($course['course_id']);
			$course['course_view_count'] 	= $this->getCourseViewCount($course['course_id']);
			$course['course_view_count_year'] 	= $this->getCourseViewCountPastYear($course['course_id']);
			
			//just to get institute id and course view count as well while making facet for course id
			$course['insttId_courseId_yearViewCount'] = $this->getCourseIdWithInsttAndViewCount($courseDataObject, $course['course_view_count_year']);

			$course['date_form_submission'] 	= $this->getCourseFormSubmissionDate($courseDataObject);
			// course ldb information: category name, specialization name, ldb course name etc
			$courseLdbDetails 				= $this->getMultipleLdbDetailsForCourse($course['course_ldb_id']);
			//online application form details
			$course_aof_details 			= $this->getOAFormDetailsForCourse($course['course_id']);
			//sponsor details
			$courseSponsoredDetails 		= $this->getCourseSponsorTypeInformation($course['course_id']);
			//category information
			$courseCategoryIds 				= $this->getCourseCategoryIds($course['course_id']);
			//parent categories information
			$parentCategoriesStr 			= $this->getParentCategoryId($courseCategoryIds['course_category_ids']);
			$course['course_parent_categories'] = $parentCategoriesStr;
			// normalized course duration
			$normalizedCourseDuration 		= $this->getCourseNormalizedDuration($course['course_duration_unit'], $course['course_duration_value']);
			// general fields
			$courseReviewCount 				= $this->getCollegeReviewsForCourse($course['course_id']);
			$course['course_review_count']  = $courseReviewCount;
		
			$courseExist 					= $this->isCRExistForCourse($course['course_id']);
			$course['course_cr_exist']  	= $courseExist;
			
			//Category Information
			$categoryIds 					= $courseCategoryIds['course_all_category_ids'];
			$explodedValued = array();
			if(!empty($categoryIds)){
				$explodedValued = explode(",", $categoryIds);
			}
			$course['course_category_id_list'] = $explodedValued;
			
			$course['facetype'] = 'course';
			
			
			// $course['course_ranking']       = $this->getCourseRanking($course['course_id']);
			
			//Get all locations of course.
			$courseLocationArray = array();
			foreach($courseDataObject->getLocations() as $locObj){  
				$courseAllLocationsArray[$locObj->getLocationId()]	= $this->getCourseLocationInformation($locObj);
				
			}
			
			
			foreach($courseDataObject->getLocations() as $locationObject){
				
				
				$course['course_fees_value'] 			= $this->getCourseLocationAttribute($locationObject, "Course Fee Value", $courseDataObject);
				$course['course_head_office'] 			= $this->getCourseLocationAttribute($locationObject, "Head Office", $courseDataObject);
				$course['course_fees_unit'] 			= $this->getCourseLocationAttribute($locationObject, "Course Fee Unit", $courseDataObject);

				//course location information
				//$courseLocation 				= $this->getCourseLocationInformation($locationObject);
				$courseLocation                 = $courseAllLocationsArray[$locationObject->getLocationId()];
				
				$courseLocationClusterInfo 		= $this->getLocationInfoForCluster($courseLocation);
				$locationClusterInfo 			= $courseLocationClusterInfo;
				$course['course_location_cluster'] = $locationClusterInfo;
                 
				$course['unique_id'] = 'course_' . $course['course_id'] . '_' . $courseLocation['course_institute_location_id'];
				
				//other course location details
				$otherCourseLocationArray =  $courseAllLocationsArray;
				unset($otherCourseLocationArray[$locationObject->getLocationId()]);
				$returnArr['course_other_locations'] = json_encode(array_slice($otherCourseLocationArray,0,count($otherCourseLocationArray)));
				$otherCourseLocationDetails 	= $returnArr;
				$course['course_normalised_fees']               = $this->getCourseNormalisedFees($course['course_fees_value'], $course['course_fees_unit']);				
				$course = array_merge($course,
								  $courseLdbDetails,
								  $courseLocation,
								  $otherCourseLocationDetails,
								  $course_aof_details,
								  $courseCategoryIds,
								  $normalizedCourseDuration,
								  $courseSponsoredDetails);
			
				array_push($courses, $course);
				}
		return $courses;
	}
	
	private function setCourseObject($courseDataObject){
		$this->courseObject = $courseDataObject;
	}
	
	public function getCourseObject(){
		return $this->courseObject;
	}
	
	public function getCourseInstituteId(){
		return $this->courseObject->getInstId();
	}
	
	private function getCourseId($courseDataObject){
		$course_id = "";
		$course_id = $courseDataObject->getId();
		return trim($course_id);
	}

	private function getCourseIdWithInsttAndViewCount($courseDataObject, $viewCount) {
		$course_id = "";
		$course_id = $courseDataObject->getId();

		$institute_id = "";
		$institute_id = $courseDataObject->getInstId();

		if(empty($viewCount)) {
			$viewCount = '0';
		}
		return $institute_id.':'.$course_id.':'.$viewCount;
	}
	
	private function getCourseTitle($courseDataObject){
		$course_title = "";
		$course_title = $courseDataObject->getName();
		return trim($course_title);
	}
	
	private function getCourseOrder($courseDataObject){
		$course_order = "";
		$course_order = $courseDataObject->getOrder();
		return trim($course_order);
	}
	
	
	private function getCourseType($courseDataObject){
		$course_type = "";
		$course_type = $courseDataObject->getCourseType();
		return trim($course_type);
	}
	
	private function getCourseLevel($courseDataObject){
		$course_level = "";
		$course_level = $courseDataObject->getCourseLevelValue();
		$course_level1 = $courseDataObject->getCourseLevel1Value();
		$course_level_display = getCourseLevelDisplay($course_level, $course_level1);
		return trim($course_level_display);
	}
	
	private function getCourseLevel1($courseDataObject){
		$course_level_1 = "";
		$course_level_1 = $courseDataObject->getCourseLevel1Value();
		return trim($course_level_1);
	}
	
	private function getCourseLevel2($courseDataObject){
		$course_level_2 = "";
		$course_level_2 = $courseDataObject->getCourseLevel2Value();
		return trim($course_level_2);
	}
	
	private function getCourseLevelForCategoryPage($courseDataObject) {
		$course_level_cp = "";
		$course_level_cp = $courseDataObject->getCourseLevel();
		return trim($course_level_cp);
	}
	
	private function getCourseDurationUnit($courseDataObject){
		$course_duration_unit = "";
		$course_duration_unit = $courseDataObject->getDuration()->getDurationUnit();
		return trim($course_duration_unit);
	}
	
	private function getCourseDurationValue($courseDataObject){
		$course_duration_value = "";
		$course_duration_value = $courseDataObject->getDuration()->getDurationValue();
		return trim($course_duration_value);
	}
	
	private function getCourseDurationValueInHours($courseDataObject){
		$course_duration_in_hours = "";
		$course_duration_in_hours = $courseDataObject->getDuration()->getValueInHours();
		return intval(trim($course_duration_in_hours));
	}
	
	private function getCourseLocationAttribute($locationObject, $attributeType, $courseDataObject = NULL){
		$attributeValue = "";
		switch($attributeType){
			case 'Course Fee Value':
				$attributeValue = "";
				foreach($locationObject->getAttributes() as $attribute){
					if($attribute->getName() == 'Course Fee Value'){
						$attributeValue = $attribute->getValue();
					}
				}
				if(empty($attributeValue)){
					$feesObject = $courseDataObject->getFees();
					$attributeValue = $feesObject->getValue();
				}
				break;
			
			case 'Course Fee Unit':
				$attributeValue = "";
				foreach($locationObject->getAttributes() as $attribute){
					if($attribute->getName() == 'Course Fee Unit'){
						$attributeValue = $attribute->getValue();
					}
				}
				if(empty($attributeValue)){
					$feesObject = $courseDataObject->getFees();
					$attributeValue = $feesObject->getCurrency();
				}
				break;
			
			case 'Head Office';
				$attributeValue = "";
				foreach($locationObject->getAttributes() as $attribute){
					if($attribute->getName() == 'Head Office'){
						$attributeValue = $attribute->getValue();
					}
				}
				if(strtolower($attributeValue) == "true"){
					$attributeValue = 1;	
				} else {
					$attributeValue = 0;
				}
				break;
		}
		return $attributeValue;
	}
	
	private function getCourseAttributeList($courseDataObject){
		$attributes = array(); $affiliations = array();
		$attributesList = $courseDataObject->getAttributes();
		foreach($attributesList as $attribute){
			array_push($attributes, trim($attribute->getName()) . ":" . trim($attribute->getValue()));
			switch (trim($attribute->getName())) {
				case 'AffiliatedToIndianUni':
					if(trim($attribute->getValue()) == 'yes') {
						array_push($affiliations, 'Indian');
					}
					break;
					
				case 'AffiliatedToDeemedUni':
					if(trim($attribute->getValue()) == 'yes') {
						array_push($affiliations, 'Deemed');
					}
					break;

				case 'AffiliatedToForeignUni':
					if(trim($attribute->getValue()) == 'yes') {
						array_push($affiliations, 'Foreign');
					}
					break;

				case 'AffiliatedToAutonomous':
					if(trim($attribute->getValue()) == 'yes') {
						array_push($affiliations, 'Autonomous');
					}
					break;
			}
		}
		$result['attributes'] = $attributes;
		$result['affiliations'] = $affiliations;
		return $result;
	}
	
	private function getCourseEligibility($courseDataObject, $examMapType = "required"){
		$courseEligibility = array();
		foreach($courseDataObject->getEligibilityExams() as $exam){
			if($exam->getTypeOfMap() == $examMapType){
				array_push($courseEligibility, $exam->getAcronym());
			}
		}
		return $courseEligibility;
	}
	
	private function getCourseSalientFeatures($courseDataObject){
		$salientFeatures = array();
		foreach($courseDataObject->getSalientFeatures() as $feature) {
			array_push($salientFeatures, trim($feature->getName()) . ":" . trim($feature->getValue()));
		}
		return $salientFeatures;
	}
	
	private function getCourseSeoUrl($courseDataObject){
		$seoUrl = "";
		$seoUrl = $courseDataObject->getURL();
		return trim($seoUrl);
	}
	
	private function getCourseNormalizedDuration($courseDurationUnit, $courseDurationValue){
		$durationArr = getNormalizedDuration($courseDurationValue." ".$courseDurationUnit);
		$courseArr['course_duration_normalized'] = $durationArr[0];
		return $courseArr;
	}
	
	private function getCoursePackType($courseDataObject){
		$course_pack_type = "";
		$course_pack_type = $courseDataObject->getCoursePackType();
		return $course_pack_type;
	}
	
	private function getCourseDegreePref($courseDataObject) {
		$course_degree_pref = array();
		$course_degree_pref = $courseDataObject->getApprovals();
		return $course_degree_pref;
	}
	
	private function getCourseNormalisedFees($course_fees_value, $course_fee_currency) {
		$course_normalised_fees = "";
		if($course_fee_currency != "INR" && !empty($course_fee_currency)){
			$this->currencyConverterService->setBaseCurrency('INR');
			$course_normalised_fees = $this->currencyConverterService->convert($course_fees_value, $course_fee_currency);
		} else {
			$course_normalised_fees = $course_fees_value;
		}
		return intval($course_normalised_fees);
	}
	
	private function getCourseClassTimings($courseDataObject) {
		$course_class_timings = array();
		$course_class_timings = $courseDataObject->getClassTimings();
		return $course_class_timings;
	}
	
	private function getCourseValidExams($courseDataObject) {
		$course_valid_exam = array();
		$course_valid_exam_name = array();
		$course_valid_exam_obj = array();
		$course_exam_object = $courseDataObject->getEligibilityExams();
		foreach($course_exam_object as $examObj) {
			if($this->_checkForExamValidation($examObj)) {
				array_push($course_valid_exam_name, $examObj->getAcronym());
				array_push($course_valid_exam_obj, $examObj);
			}
		}
		$course_valid_exam['name'] = $course_valid_exam_name;
		$course_valid_exam['obj'] = $course_valid_exam_obj;
		
		return $course_valid_exam;
	}
	
	private function _checkForExamValidation($examObj) {
		$listOfAllExams			= $this->getAllExams();
	//	$listOfAllExams['MBA'][] 	= "GMAT";
		$examName				= $examObj->getAcronym();
		$MarksTypFromCourseExam = $examObj->getMarksType();
		
		//For MBA Exams
		if(in_array($examName, $listOfAllExams['MBA'])) {
			$MBAExmasNotReqPrcntl = $GLOBALS['MBA_EXAMS_REQUIRED_SCORES'];
			$MBANoOptionExam = $GLOBALS['MBA_NO_OPTION_EXAMS'];
			if(in_array($examName, $MBAExmasNotReqPrcntl)) {   //Exceptional Marks Type check
				if(!empty($MarksTypFromCourseExam) && $MarksTypFromCourseExam == 'total_marks') {
					$validMarksTypeFlag = TRUE;
				}
			}
			elseif(!empty($MarksTypFromCourseExam) && $MarksTypFromCourseExam == 'percentile') { //check for mba exams other than MBA_EXAMS_REQUIRED_SCORES
				$validMarksTypeFlag = TRUE;
			}
			elseif(empty($MarksTypFromCourseExam) && !empty($MBANoOptionExam) && in_array($examName,$MBANoOptionExam)) {
				$validMarksTypeFlag = TRUE;
			}
		}
		
		//For BE/Betch Exams
		if(in_array($examName, $listOfAllExams['Engineering'])) {
			$EngExamsNotReqRank = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
			if(in_array($examName,$EngExamsNotReqRank)) { //Exceptional Marks Type check
				if($MarksTypFromCourseExam == 'total_marks')
				{
					$validMarksTypeFlag = TRUE;
				}
			}
			elseif($MarksTypFromCourseExam == 'rank') { //Default Check
				$validMarksTypeFlag = TRUE;
			}
		}
		return $validMarksTypeFlag;
	}
	
	private function getAllExams() {
		$exam_list = $this->listingCache->getExamsList();
		if(empty($exam_list)){
			$exam_list = $this->categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($exam_list);
		}
		$exam_list = $this->_prepareExamList($exam_list);
		array_push($exam_list['MBA'], 'GMAT');
		return $exam_list;
	}
	
	private function _prepareExamList($exam_list = array()) {
		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}
	
	private function _populateCourseValidExamScore(& $course, $course_valid_exams) {
		foreach($course_valid_exams['obj'] as $validExamObj) {
			$exam_name = $validExamObj->getAcronym();
			$exam_name = removeSpacesFromString($exam_name);
			$course['course_exam_'.$exam_name] = floatval($validExamObj->getMarks());
		}
	}
	
	private function getCourseLastUpdatedDate($courseDataObject) {
		$course_last_modified_date = "";
		$course_last_modified_date = $courseDataObject->getLastUpdatedDate();
		return $course_last_modified_date;
	}
	
	private function getCourseLocationInformation($locationObject){
		$locationInfo['course_locality_id'] = $locationObject->getLocality()->getId() != null ? $locationObject->getLocality()->getId() : "";
		$locationInfo['course_locality_name'] = $locationObject->getLocality()->getName() != null ? $locationObject->getLocality()->getName() : "";
		$locationInfo['course_custom_locality_name'] = $locationObject->getCustomLocalityName() != null ? $locationObject->getCustomLocalityName() : "";
		
		$locationInfo['course_zone_id'] = $locationObject->getZone()->getId() != null ? $locationObject->getZone()->getId() : "";
		$locationInfo['course_zone_name'] = $locationObject->getZone()->getName() != null ? $locationObject->getZone()->getName() : "";
		
		$locationInfo['course_virtual_city_id'] = $locationObject->getCity()->getVirtualCityId() != null ? $locationObject->getCity()->getVirtualCityId() : "";

		if(!empty($locationInfo['course_virtual_city_id'])){
			$virtualCityObj = $this->locationRepo->findCity($locationInfo['course_virtual_city_id']);
			if(!empty($virtualCityObj)){
				$locationInfo['course_virtual_city_name'] = $virtualCityObj->getName();
			}
		}
		if(!empty($locationInfo['course_virtual_city_id'])) {
			$locationInfo['course_virtual_city_name_id'] = $locationInfo['course_virtual_city_name'].":".$locationInfo['course_virtual_city_id'];
		}
		
		$locationInfo['course_city_id'] = $locationObject->getCity()->getId() != null ? $locationObject->getCity()->getId() : "";
		$locationInfo['course_city_name'] = $locationObject->getCity()->getName() != null ? $locationObject->getCity()->getName() : "";
		if(!empty($locationInfo['course_city_id'])) {
			$locationInfo['course_city_name_id'] = $locationInfo['course_city_name'].":".$locationInfo['course_city_id'];
		}
		
		//to get city id along with locality id when facet is created on locality
		if($locationInfo['course_locality_id'] != '' && $locationInfo['course_city_id'] != '') {
			$locationInfo['course_city_id_locality_id'] = $locationInfo['course_city_id'].':'.$locationInfo['course_locality_id'];
		}

		$locationInfo['course_state_id'] = $locationObject->getState()->getId() != null ? $locationObject->getState()->getId() : "";
		$locationInfo['course_state_name'] = $locationObject->getState()->getName() != null ? $locationObject->getState()->getName() : "";
		if(!empty($locationInfo['course_state_id'])) {
			$locationInfo['course_state_name_id'] = $locationInfo['course_state_name'].":".$locationInfo['course_state_id'];
		}
		
		$locationInfo['course_country_id'] = $locationObject->getCountry()->getId() != null ? $locationObject->getCountry()->getId() : "";
		$locationInfo['course_country_name'] = $locationObject->getCountry()->getName() != null ? $locationObject->getCountry()->getName() : "";
		
		$locationInfo['course_institute_location_id'] = $locationObject->getLocationId() != null ? $locationObject->getLocationId() : "";
		
		$locationAttributes = array();
		$courseLocationAttributes = $locationObject->getAttributes();
		foreach($courseLocationAttributes as $attribute){
			$locationAttributes[$attribute->getName()] = $attribute->getValue() != null ? $attribute->getValue() : "";
		}
		$locationInfo['course_location_attributes'] = json_encode($locationAttributes);
		
		$searchModel = new SearchModel();
		$continentInfo = $searchModel->getContinentForCountry($locationInfo['course_country_id']);
		$locationInfo = array_merge($locationInfo, $continentInfo);
		return $locationInfo;
	}
	
	private function getOtherCourseLocationDetails($locations, $currentLocation){
		$currentLocationId = $currentLocation->getLocationId();
		$locationDetails = array();
		foreach($locations as $location){
			if($location->getLocationId() != $currentLocationId){
				$tempLocationArr = $this->getCourseLocationInformation($location);
				array_push($locationDetails, $tempLocationArr);
			}
		}
		$returnArr = array();
		$returnArr['course_other_locations'] = json_encode($locationDetails);
		return $returnArr;
	}
	
	
	private function getOAFormDetailsForCourse($courseId){
		$searchModel = new SearchModel();
		$OAFDetails = $searchModel->getOAFormDetailsForCourse($courseId);
		return $OAFDetails;
	}
	
	private function getLdbCourseList($courseId){
		$listingClientObj = new Listing_client();
		$ldbCourseIdList = $listingClientObj->getLDBIdForCourseId($courseId);
		return $ldbCourseIdList;
	}
	
	private function getLdbCourseDetailList($ldb_id, $multiple = true){
		$returnArray = array();
		$ListingClientObj = new Listing_client();
		$ldbDetails = $ListingClientObj->getLdbCourseDetailsForLdbId($ldb_id, $multiple);
		return $ldbDetails;
	}
	
	private function getLdbDetailsForCourse($ldb_id, $multiple){
		$returnArray = array();
		$ldbDetails = $this->getLdbCourseDetailList($ldb_id, $multiple);
		$returnArray['course_ldb_specialization_name'] = $ldbDetails['ldb_specialization_name'];
		$returnArray['course_ldb_category_name'] = $ldbDetails['ldb_category_name'];
		$returnArray['course_ldb_course_name'] = $ldbDetails['ldb_course_name'];	
		return $returnArray;
	}
	
	private function getMultipleLdbDetailsForCourse($ldbIds){
		$returnArray = array();
		$returnArray['course_ldb_specialization_name'] = array();
		$returnArray['course_ldb_category_name'] = array();
		$returnArray['course_ldb_course_name'] = array();
		$returnArray['sp_name_id_map'] = array();
		$returnArray['course_ldb_course_name_id_map'] = array();
		$specializationMapFacet = array();
		foreach($ldbIds as $ldbid){
			$ldbDetails = $this->getLdbCourseDetailList($ldbid, true);

			foreach($ldbDetails['ldb_specialization_name'] as $specializationId => $specializationName){
				array_push($returnArray['course_ldb_specialization_name'], $specializationName);
				if(strtolower($specializationName) != "all"){
					$str = $specializationName . ":" . $specializationId;
					if(!empty($specializationId) && !empty($specializationName)) {
						array_push($returnArray['sp_name_id_map'], $str);
					}
				}
			}
			foreach($ldbDetails['ldb_category_name'] as $categoryName){
				array_push($returnArray['course_ldb_category_name'], $categoryName);
			}
			foreach($ldbDetails['ldb_course_name'] as $ldbCourseId => $courseName){
				array_push($returnArray['course_ldb_course_name'], $courseName);
				if(strtolower($ldbDetails['ldb_specialization_name'][$ldbCourseId]) == 'all' && $courseName != $ldbDetails['ldb_category_name'][$ldbCourseId]) {
					$str = $courseName . ":" . $ldbCourseId;
					if(!empty($ldbCourseId) && !empty($courseName)) {
						array_push($returnArray['course_ldb_course_name_id_map'], $str);
					}
				}
			}
		}
		return $returnArray;
	}
	
	
	private function getCategoryIds($id, $type){
		$returnArray = array('category_ids' => '', 'category_ids_trail' => '', 'category_names_trail' => '');
		$searchModel = new searchmodel();
		$categories = $searchModel->getListingCategory($id, $type);
		if(count($categories) > 0){
			$categoryIdsStr = implode($categories, ",");
			$returnArray['category_ids'] = $categoryIdsStr;
			//$categoryTrail = $searchModel->getCategoryTrail($categoryIdsStr);
			$returnArray['category'] = json_encode($categoryTrail['category']);
			$returnArray['category_ids_trail'] = implode($categoryTrail['category_ids'], ',');
			$returnArray['category_names_trail'] = implode($categoryTrail['category_names'], ',');
		}
		return $returnArray;
	}
	
	private function getParentCategoryId($ids){
		$searchModel = new searchmodel();
		$categoryArray = explode(',', $ids);
		$parentCategories = array();
		foreach($categoryArray as $catId){
			$categories = $searchModel->getParentCategoryId($catId);
			if($categories['boardId'] != 1 && !empty($categories['boardId'])){
				$parentCategories[] = $categories['boardId'];
			}
			
		}
		$parentCategories = array_unique($parentCategories);
		$parentCategoriesStr = "";
		if(count($parentCategories) > 0){
			$parentCategoriesStr = implode(",", $parentCategories);
		}
		return $parentCategoriesStr;
	}
	
	private function getCourseCategoryIds($id){
		$returnArray = array();
		$categoryInfo = $this->getCategoryIds($id, 'course');
		$returnArray['course_category_ids'] = $categoryInfo['category_ids'];
		$returnArray['course_category_info'] = json_encode($categoryInfo['category']);
		$returnArray['course_all_category_ids'] = $categoryInfo['category_ids_trail'];

		$categories = json_decode($categoryInfo['category']);
		foreach ($categories as $key => $value) {
			if($key > 1) {
				$returnArray['course_category_name'][] = $value; //both category and subcategory
			}
		}
		
		//$returnArray['course_category_ids_trail'] = $categoryInfo['category_ids_trail'];
		//$returnArray['course_category_names_trail'] = $categoryInfo['category_names_trail'];
		return $returnArray;
	}
	
	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['course_id'])){
				$returnFlag = true;
			}	
		}
		return $returnFlag;
	}
	
	private function getLocationInfoForCluster($locationInfo){
		$fields = array('course_country_id', 'course_state_id', 'course_city_id', 'course_zone_id', 'course_locality_id');
		$courseClusterInfo = '';
		foreach($fields as $value){
			if(isset($locationInfo[$value]) && !empty($locationInfo[$value])){
				$courseClusterInfo .= $locationInfo[$value] . '/';
			} else {
				break;
			}
		}
		$courseClusterInfo = trim(trim($courseClusterInfo, '/'));
		return $courseClusterInfo;
	}
	
	public function getFlagShipCourseFromCourses($courses){
		$courseOrders = array();
		$flagShipCourseId = -1;
		foreach($courses as $course){
			$courseOrders[$course['course_id']] = $course['course_order'];
		}
		if(count($courseOrders) > 0){
			asort($courseOrders);
			$flagShipCourseId = key($courseOrders);
		}
		return $flagShipCourseId;
	}
	
	private function getCourseViewCount($courseId) {
		$searchModel = new searchmodel();
		$viewCount = $searchModel->getCourseViewCount($courseId);
		return $viewCount;
	}

	private function getCourseViewCountPastYear($courseId) {
		return $this->shikshaPopularityDataLib->getTotalViewsOnCourse(array($courseId));
	}
	
	private function getCourseSponsorTypeInformation($courseId = NULL){
		$sponsorInformation = array();
		$bmsKeysInformation = array();
		if(!empty($courseId)){
			$searchModel = new searchmodel();
			$params = array(
						'listing_id' 	=> $courseId,
						'listing_type' 	=> 'course'
						);
			$sponsorTypeDetails = $searchModel->getLiveSponsoredResults($params);
			$sponsorTypes = array();
			foreach($sponsorTypeDetails as $detail){
				if($detail['listing_type'] == "course" && $detail['search_type'] == "course"){
					if(!in_array($detail['sponsor_type'], $sponsorTypes)){
						array_push($sponsorTypes, $detail['sponsor_type']);
					}
					if($detail['sponsor_type'] == "featured"){
						$bmsKeysInformation['course_featured_bmskey'][] = $detail['bmskey'];
					}
					if($detail['sponsor_type'] == "banner"){
						$bmsKeysInformation['course_banner_bmskey'][] = $detail['bmskey'];
					}
				}
			}
		}
		$sponsorTypeStr =  implode(",", $sponsorTypes);
		$sponsorTypeStr = trim($sponsorTypeStr, ",");
		$sponsorInformation = $bmsKeysInformation; 
		$sponsorInformation['course_sponsor_types'] = $sponsorTypeStr;
		return $sponsorInformation;
	}
        
	function getCourseFormSubmissionDate($courseDataObject){
		$displayLocation = ($courseDataObject->getCurrentMainLocation() != null) ? $courseDataObject->getCurrentMainLocation() : $courseDataObject->getMainLocation() ;
		$date = $courseDataObject->getDateOfFormSubmission($displayLocation->getLocationId());
		if($date == '0000-00-00 00:00:00') {
			return '';
		} else {
			return strtotime($courseDataObject->getDateOfFormSubmission($displayLocation->getLocationId()));
		}
	}
	
	private function getCollegeReviewsForCourse($courseId = NULL){
		$searchModel = new searchmodel();
		$count = $searchModel->getCourseReviewCount($courseId);
		return $count;
	}

	private function isCRExistForCourse($courseId = NULL){
		if(empty($courseId)){
			return 0;
		}
		$count = $this->caDiscussionHelper->checkIfCampusRepExistsForCourse(array($courseId));
		if(strtolower($count[$courseId]) == 'true') {
			return 1;
		} else {
			return 0;
		}
	}
}

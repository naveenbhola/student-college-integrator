<?php 

class Recommendation_Server extends MX_Controller
{	
	private $_recommendation_log = array();

	/*
	 * Users for whom no recommendations or less than required are generated
	 */
	private $_recommendation_defaulters = array();

	function init()
	{
		$this->load->library(array(
										'recommendation/recommendation_lib',
										'recommendation/exclusionlist',
										'recommendation/logger',
										'xmlrpc',
										'xmlrpcs',
										'listing/NationalCourseLib'
								   )
							);

		$this->load->helper('recommendation/recommendation');

		$this->load->model('user/userinfomodel');

		$this->load->model('recommendation/recommendation_model');
		$this->recommendation_model->init(NULL);
		
		//registerShutdown('recommendation');
		
		set_time_limit (0);
		//ini_set('memory_limit','2048M');
		ini_set('display_errors','Off');
	}
	
	function index()
	{
		$this->init();
		
		$config['functions']['getRecommendations'] = array('function' => 'Recommendation_Server.getRecommendations');
		
		$config['functions']['registerClickOnRecommendation'] = array('function' => 'Recommendation_Server.registerClickOnRecommendation');

		$config['functions']['processRecommendationMailer'] = array('function' => 'Recommendation_Server.processRecommendationMailer');
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	
	/** 
     * Get recommendations for a set of users  
     */
	function getRecommendations($request)
	{
		$parameters = $request->output_parameters();
		$users = $parameters[0];
		$numResultsPerUser = $parameters[1];
		$disableExclusionList = $parameters[2];
		
		$this->logger->logToFile("Recommendations started with ".count($users)." users",2);
	
		if(count($users)) {
			
			$seed_data = $this->recommendation_lib->getSeedDataForUsers($users);
			
			if($disableExclusionList == 'yes') {
				foreach($users as $userId) {
					$exclusion_list[$userId] = array();
				}
			}
			else {
				$exclusion_list = $this->exclusionlist->getExclusionList($users, array(EXCLUSION_SOURCE_RECOMMENDATIONS));
			}
			
			$recommendations = array();
			
			foreach($users as $user) {
				$recommendations_for_user = $this->getRecommendationsForUser($user, $seed_data[$user], $exclusion_list[$user], $numResultsPerUser);
				
				if(is_array($recommendations_for_user) && count($recommendations_for_user)) {
					$recommendations[$user] = $recommendations_for_user;
				}	
			}	
			
			$this->logger->logToFile("Recommendations successfully retrieved for ".count($recommendations)." users");
			
			$recommendationData = array();
			
			if(count($recommendations)) {
				$recommendationData = rh_buildRecommendationData($recommendations);
			}	
			
			$responseData = array(
									'recommendations' => $recommendationData,
									'defaulters' => $this->_recommendation_defaulters,
									'log' => $this->_recommendation_log
								);
			
			$responseString = base64_encode(json_encode($responseData));
		
			$response = array($responseString,'string');
			return $this->xmlrpc->send_response($response);
		}
		else 
		{
			return $this->xmlrpc->send_error_message('123', 'Users not available');
		}
	}
	
	/** 
	* Get recommendations for a particular user 
	*/
	function getRecommendationsForUser($user_id, $seed_data, $exclusion_list, $numResultsPerUser)
	{	
		$this->logger->reset();
		$this->logger->log('User Info',$user_profile_info,'userinfo');
		$this->logger->log("Exclusion List",$exclusion_list,'array');
		$this->logger->log("No. of Seed data categories",count($seed_data));
		$this->logger->log("Algo Scheme",$algo_scheme,'array');
		
		$recommendations = array();
    
		/**
		 * No. of recommendations to be generated for each user
		 */ 
		$numResultsPerUser = intval($numResultsPerUser);
		if(!$numResultsPerUser) {
			$numResultsPerUser = RECO_NUM_RESULTS;
		}
		
		/*
		 * Run recommendation algos for each category in seed data 
		 */
		if(is_array($seed_data) && count($seed_data) > 0) {
			$this->logger->log('CATEGORY # '.$category,$category_seed_data,'seed_data');
			
			$recommendations = array();
			$num_results = $numResultsPerUser;
			
			/*
			 * Also Viewed
			 */
			$also_viewed_results = $this->recommendation_lib->getAlsoViewedResults($seed_data, $num_results, $exclusion_list);
			
			if(is_array($also_viewed_results) && count($also_viewed_results)) {
				$recommendations = $also_viewed_results;
				
				foreach($also_viewed_results as $also_viewed_result) {
					$exclusion_list[] = (int) $also_viewed_result['institute_id'];
				}
			}
			
			/*
			 * Similar Institutes
			 */
			$num_results = $numResultsPerUser - count($recommendations);
			$similar_institutes = $this->recommendation_lib->getSimilarInstitutes($seed_data, $exclusion_list, $num_results); 
			
			if(is_array($similar_institutes) && count($similar_institutes)) {
				$recommendations = array_merge($recommendations, $similar_institutes);
			}
		
			$this->logger->addSeparatorRow('medium');
		}
		else {
			$recommendations =  $this->recommendation_lib->getProfileBasedResults($user_id, $exclusion_list, $numResultsPerUser);
		}
		
		$recommendation_log = base64_encode(gzcompress($this->logger->getLog()));
		$this->_recommendation_log[$user_id] = $recommendation_log;
		
		if(count($recommendations) == 0) {
			$this->_recommendation_defaulters[] = $user_id;
		}
		
		return $recommendations;
	}

	function registerClickOnRecommendation($request)
	{
		$parameters = $request->output_parameters();
		
		$recommendation_id = (int) $parameters[0];
		$listing_id = (int) $parameters[1];
		$listing_type = $parameters[2];
		
		/*
		 * Register click for recommendation
		 */
		$this->recommendation_model->registerClickOnRecommendation($recommendation_id,$listing_id,$listing_type);
		
		$response = array('success','string');
		return $this->xmlrpc->send_response($response);
	}
		
	function getRecommendedCategoryPageURLsForCourse($courseId) {
		
		if($courseId <=0) {
			return array();
		}
		
		$this->load->library('CacheLib');
		$cache_lib = new CacheLib();
		
		$data = $cache_lib->get('CategoryLinksWidget'.$courseId);
		if($data != 'ERROR_READING_CACHE') {
			return unserialize($data);
		}
		
		$this->config->load('categoryPageConfig');
		$this->load->library('Category_list_client');
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->builder('ListingBuilder','listing');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('CategoryBuilder','categoryList');
		
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		    
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		
		$category_list_client = new Category_list_client();
		
		$newURLSubCategoryIds = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$newURLFeeValues = $this->config->item('CP_FEES_RANGE');
		$newURLFeeValues = array_keys($newURLFeeValues['RS_RANGE_IN_LACS']);
		$newURLAffiliationList = $this->config->item('CP_AFFILIATION_LIST');
		$newURLFlag = 1;
		
		$URLWidgetHeading = '';
		$categoryPageURLs = array();
		
		global $parentToLDBCourseMapping;
		
		$course = $courseRepository->find($courseId);
		
		//change for institute to college story
		$collegeOrInstituteRNR = 'Institutes';
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    $categoryIds = $national_course_lib->getCourseInstituteCategoryId($courseId,'course');
		if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
			$collegeOrInstituteRNR = 'Colleges';
		}
		//institute to college story ends here

		/*
		 *Get LDB Course Data
		*/
		$LDBCourses = $LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
		$LDBCourseIds = array();
		$LDBCourseData = array();
		$specializationIds = array();
		$parentIds = array();
		
		foreach($LDBCourses as $LDBCourse) {
			$subCategoryId = $LDBCourse->getSubCategoryId();
			$parentId = $LDBCourse->getParentId();
			
			if(!in_array($subCategoryId, $newURLSubCategoryIds)) {
				continue;
			}
			
			if(array_key_exists($parentId, $parentToLDBCourseMapping)) {
				$parentId = $parentToLDBCourseMapping[$parentId];
			}
			
			if(!in_array($parentId, $parentIds)) {
				$parentIds[] = $parentId;
			}
		}
		
		if(!count($parentIds)) {
			$data = array();
			$data['subCategoryForRecommendations'] = 0;
			return $data;
		}
		
		$randomParentIndex = mt_rand(0, count($parentIds) - 1);
		
		foreach($LDBCourses as $LDBCourse) {
			$LDBId = $LDBCourse->getId();
			$subCategoryId = $LDBCourse->getSubCategoryId();
			$categoryId = $LDBCourse->getCategoryId();
			$parentId = $LDBCourse->getParentId();
			$isLDB = false;
			
			if(!array_key_exists($parentId, $parentToLDBCourseMapping)) {
				$isLDB = true;
			}
			
			if(array_key_exists($parentId, $parentToLDBCourseMapping)) {
				$parentId = $parentToLDBCourseMapping[$parentId];
			}
			
			if($parentId != $parentIds[$randomParentIndex]) {
				continue;
			}
			
			if($isLDB) {
				$LDBCourseIds[] = $LDBId;
			}
			
			$specializations = $LDBCourseRepository->getSpecializations($parentId);
			foreach($specializations as $specialization) {
				$specializationId = $specialization->getId();
				if(!in_array($specializationId, $specializationIds)) {
					$specializationIds[] = $specializationId;
					$specializationCourse = $LDBCourseRepository->find($specializationId);
					$LDBCourseData[$specializationId]['subCategoryId'] = $specializationCourse->getSubCategoryId();
					$LDBCourseData[$specializationId]['categoryId'] = $specializationCourse->getCategoryId();
					
					$categoryObject  = $categoryRepository->find($LDBCourseData[$specializationId]['subCategoryId']);
					if(!empty($categoryObject)){
						$courseName 	 = $categoryObject->getShortName();
					}
					$LDBCourseData[$specializationId]['name'] = $courseName.' in '.$specializationCourse->getSpecialization();
					   
					$LDBCourseData[$specializationId]['tracking'] = 'specialization_'.$specializationCourse->getSpecialization();
				}
			}
		}
		
		$specializationIds = array_values(array_diff($specializationIds, $LDBCourseIds));
		
		
		/*
		 *Get Location Data
		*/
		$isMultiLocation = $course->isCourseMultilocation();
		
		if($isMultiLocation == 'true') {
			$mainLocation = $course->getMainLocation();
		}
		else if($isMultiLocation == 'false') {
			$mainLocation = $course->getMainLocation();
		}
		
		$city = $mainLocation->getCity();
		$virtualCityId = $city->getVirtualCityId();
		$hasVirtualCity = isset($virtualCityId) ? true : false;
		
		if($hasVirtualCity) {
			$actualCityId = $city->getId();
			$city = $locationRepository->findCity($virtualCityId);
		}
		
		$isTier1 = $city->getTier() == 1 ? true : false;
		
		$cityId = $city->getId();
		$stateId = $city->getStateId();
		$countryId = $mainLocation->getCountry()->getId();
		$regionId = $mainLocation->getRegion();
		$regionId = empty($regionId) ? 0 : $regionId;
		$filterRegionName = $isTier1 == true ? 'cityId' : NULL;
		$filterRegionId = $isTier1 == true ? $cityId : NULL;
		
		
		/*
		 *Get View by Specialization URLs
		*/
		if(!empty($LDBCourseIds)) {
			$randomLDBIndex = mt_rand(0, count($LDBCourseIds) - 1);
			$request = new CategoryPageRequest();
			$request->setNewURLFlag($newURLFlag);
			$URLdata = array($filterRegionName => $filterRegionId,
					 'stateId' => $stateId,
					 'countryId' => $countryId,
					 'regionId' => $regionId,
					 'LDBCourseId' => $LDBCourseIds[$randomLDBIndex],
					 'subCategoryId' => $LDBCourseData[$LDBCourseIds[$randomLDBIndex]]['subCategoryId'],
					 'categoryId' => $LDBCourseData[$LDBCourseIds[$randomLDBIndex]]['categoryId']);
			$request->setData($URLdata);
			$URL = $request->getURL();
			$hasInstitutes = $category_list_client->isCategoryPageEmpty($request);
			if($hasInstitutes) {
				$categoryPageURLs['View By Specialization']['URL'][] = $URL;
				$categoryPageURLs['View By Specialization']['text'][] = $LDBCourseData[$LDBCourseIds[$randomLDBIndex]]['name'];
				$categoryPageURLs['View By Specialization']['tracking'][] = strtolower(preg_replace('/\s+/', '', $LDBCourseData[$LDBCourseIds[$randomLDBIndex]]['tracking']));
			}
		}
		
		$randomSpecializationIndexArray = array();
		for($index = 0; $index < 5; $index++) {
			$randomSpecializationIndex = mt_rand(0, count($specializationIds) - 1);
			if(in_array($randomSpecializationIndex, $randomSpecializationIndexArray)) {
				continue;
			}
			$randomSpecializationIndexArray[] = $randomSpecializationIndex;
			
			$request = new CategoryPageRequest();
			$request->setNewURLFlag($newURLFlag);
			$URLdata = array($filterRegionName => $filterRegionId,
					 'stateId' => $stateId,
					 'countryId' => $countryId,
					 'regionId' => $regionId,
					 'LDBCourseId' => $specializationIds[$randomSpecializationIndex],
					 'subCategoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'],
					 'categoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['categoryId']);
			$request->setData($URLdata);
			$URL = $request->getURL();
			$hasInstitutes = $category_list_client->isCategoryPageEmpty($request);
			if($hasInstitutes) {
				$categoryPageURLs['View By Specialization']['URL'][] = $URL;
				$categoryPageURLs['View By Specialization']['text'][] = $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['name'];
				$categoryPageURLs['View By Specialization']['tracking'][] = strtolower(preg_replace('/\s+/', '', $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['tracking']));
			}
		}
		
		
		/*
		 *Get View by Fees URLs
		*/
		$feesURLText = array("$collegeOrInstituteRNR with fees upto 1 lakh",
				     "$collegeOrInstituteRNR with fees upto 2 lacs",
				     "$collegeOrInstituteRNR with fees upto 5 lacs",
				     "$collegeOrInstituteRNR with fees upto 7 lacs",
				     "$collegeOrInstituteRNR with fees upto 10 lacs");
		
		if($isMultiLocation == 'true') {
			$fees = $course->getFees()->getValue();
		}
		else if($isMultiLocation == 'false') {
			$fees = $course->getFees()->getValue();
		}
		
		$filterFeesValues = array();
		if(empty($fees)) {
			$randomFeeIndex = mt_rand(0, count($newURLFeeValues) - 1);
			$filterFeesValues[$randomFeeIndex] = $newURLFeeValues[$randomFeeIndex];
			$randomFeeIndex = mt_rand(0, count($newURLFeeValues) - 1);
			$filterFeesValues[$randomFeeIndex] = $newURLFeeValues[$randomFeeIndex];
			ksort($filterFeesValues);
		}
		else if($fees <= $newURLFeeValues[1]) {
			$filterFeesValues[0] = $newURLFeeValues[0];
			$filterFeesValues[1] = $newURLFeeValues[1];
		}
		else if($fees <= $newURLFeeValues[2]) {
			$filterFeesValues[1] = $newURLFeeValues[1];
			$filterFeesValues[2] = $newURLFeeValues[2];
		}
		else if($fees <= $newURLFeeValues[3]) {
			$filterFeesValues[2] = $newURLFeeValues[2];
			$filterFeesValues[3] = $newURLFeeValues[3];
		}
		else {
			$filterFeesValues[3] = $newURLFeeValues[3];
			$filterFeesValues[4] = $newURLFeeValues[4];
		}
		
		foreach($filterFeesValues as $index => $fee) {
			$request = new CategoryPageRequest();
			$request->setNewURLFlag($newURLFlag);
			$URLdata = array($filterRegionName => $filterRegionId,
					 'stateId' => $stateId,
					 'countryId' => $countryId,
					 'regionId' => $regionId,
					 'subCategoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'],
					 'categoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['categoryId'],
					 'feesValue' => $fee);
			$request->setData($URLdata);
			$URL = $request->getURL();
			$hasInstitutes = $category_list_client->isCategoryPageEmpty($request);
			if($hasInstitutes) {
				$categoryPageURLs['View By Fees']['URL'][] = $URL;
				$categoryPageURLs['View By Fees']['text'][] = $feesURLText[$index];
				$categoryPageURLs['View By Fees']['tracking'][] = strtolower(preg_replace('/\s+/', '', 'fee_'.$fee));
			}
		}
		
		
		/*
		 *Get View by Location URLs
		*/
		$locationIds = array();
		$locationData = array();
		$hasLocalities = false;
		$hasCities = false;
		
		if($isTier1) {
			if($hasVirtualCity) {
				$localities = $locationRepository->getLocalitiesByCity($actualCityId);
				
				if(empty($localities)) {
					$cities = $locationRepository->getCitiesByVirtualCity($cityId);
					
					if(!empty($cities)) {
						$hasCities = true;
					}
				}
				else {
					$hasLocalities = true;
				}
			}
			else {
				$localities = $locationRepository->getLocalitiesByCity($cityId);
				
				if(!empty($localities)) {
					$hasLocalities = true;
				}
			}
		}
		else {
			$cities = $locationRepository->getCitiesByState($stateId);
			
			if(!empty($cities)) {
				$hasCities = true;
			}
		}
		
		if($hasCities) {
			foreach($cities as $cityObj) {
				$locationId = $cityObj->getId();
				$locationIds[] = $locationId;
				$locationData[$locationId]['state'] = $cityObj->getStateId();
				$locationData[$locationId]['text'] = "$collegeOrInstituteRNR in ".$cityObj->getName();
				$locationData[$locationId]['tracking'] = 'location_city_'.$cityObj->getName();
			}
		}
		else if($hasLocalities) {
			foreach($localities as $locality) {
				$locationId = $locality->getId();
				$locationIds[] = $locationId;
				$locationData[$locationId]['zone'] = $locality->getZoneId();
				$locationData[$locationId]['city'] = $locality->getCityId();
				$locationData[$locationId]['state'] = $locality->getStateId();
				$locationData[$locationId]['text'] = "$collegeOrInstituteRNR in ".$locality->getName();
				$locationData[$locationId]['tracking'] = 'location_locality_'.$locality->getName();
			}
		}
		
		$randomLocalityIndexArray = array();
		for($index = 0; $index < 10; $index++) {
			$randomLocalityIndex = mt_rand(0, count($locationIds) - 1);
			if(in_array($randomLocalityIndex, $randomLocalityIndexArray)) {
				continue;
			}
			$randomLocalityIndexArray[] = $randomLocalityIndex;
			
			$request = new CategoryPageRequest();
			$request->setNewURLFlag($newURLFlag);
			
			if($hasLocalities) {
				$URLdata = array('localityId' => $locationIds[$randomLocalityIndex],
						 'zoneId' => $locationData[$locationIds[$randomLocalityIndex]]['zone'],
						 'cityId' => $locationData[$locationIds[$randomLocalityIndex]]['city'],
						 'stateId' => $locationData[$locationIds[$randomLocalityIndex]]['state'],
						 'countryId' => $countryId,
						 'regionId' => $regionId,
						 'subCategoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'],
						 'categoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['categoryId']);
			}
			else if($hasCities) {
				$URLdata = array('cityId' => $locationIds[$randomLocalityIndex],
						 'stateId' => $locationData[$locationIds[$randomLocalityIndex]]['state'],
						 'countryId' => $countryId,
						 'regionId' => $regionId,
						 'subCategoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'],
						 'categoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['categoryId']);
			}
			$request->setData($URLdata);
			$URL = $request->getURL();
			$hasInstitutes = $category_list_client->isCategoryPageEmpty($request);
			if($hasInstitutes) {
				$categoryPageURLs['View By Location']['URL'][] = $URL;
				$categoryPageURLs['View By Location']['text'][] = $locationData[$locationIds[$randomLocalityIndex]]['text'];
				$categoryPageURLs['View By Location']['tracking'][] = strtolower(preg_replace('/\s+/', '', $locationData[$locationIds[$randomLocalityIndex]]['tracking']));
			}
		}
		
		
		/*
		 *Get View by Exam URLs
		*/
		$exams = $course->getEligibilityExams();
		
		if(!empty($exams)) {
			$randomExamIndexArray = array();
			for($index = 0; $index < 5; $index++) {
				$randomExamIndex = mt_rand(0, count($exams) - 1);
				if(in_array($randomExamIndex, $randomExamIndexArray)) {
					continue;
				}
				$randomExamIndexArray[] = $randomExamIndex;
				
				$examId = $exams[$randomExamIndex]->getAcronym();
				
				$request = new CategoryPageRequest();
				$request->setNewURLFlag($newURLFlag);
				$URLdata = array($filterRegionName => $filterRegionId,
						 'stateId' => $stateId,
						 'countryId' => $countryId,
						 'regionId' => $regionId,
						 'subCategoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'],
						 'categoryId' => $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['categoryId'],
						 'examName' => $examId);
				$request->setData($URLdata);
				$URL = $request->getURL();
				$hasInstitutes = $category_list_client->isCategoryPageEmpty($request);
				if($hasInstitutes) {
					$categoryPageURLs['View By Exam']['URL'][] = $URL;
					$categoryPageURLs['View By Exam']['text'][] = "$collegeOrInstituteRNR accepting ".strtoupper($examId);
					$categoryPageURLs['View By Exam']['tracking'][] = strtolower(preg_replace('/\s+/', '', 'exam_'.strtoupper($examId)));
				}
			}
		}
		
		
		/*
		 *Get URL Widget Heading
		*/
		$courseHeading = $LDBCourseRepository->find($parentIds[$randomParentIndex])->getCourseName();
		$categoryObject  = $categoryRepository->find($LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId']);
		if(!empty($categoryObject)){
				$courseHeading = $categoryObject->getShortName();
		}
		
		if($isTier1) {
			$locationHeading = $city->getName();
		}
		else {
			$state = $locationRepository->findState($stateId);
			$locationHeading = $state->getName();
		}
		
		$URLWidgetHeading = 'More choices of '.$courseHeading." $collegeOrInstituteRNR in ".$locationHeading;
		
		$data = array();
		$data['subCategoryForRecommendations'] = $LDBCourseData[$specializationIds[$randomSpecializationIndex]]['subCategoryId'];
		$data['URLWidgetHeading'] = $URLWidgetHeading;
		$data['categoryPageURLs'] = $categoryPageURLs;
		
		if(!empty($categoryPageURLs)) {
			$cache_lib->store('CategoryLinksWidget'.$courseId, serialize($data), 1209600, 'CategoryLinksWidget');
		}
		
		return $data;
	}

	/*******************************************
	 * RESPONSE PROCESSING FUNCTIONS
	 ******************************************/
	
	function processRecommendationMailer($request)
	{
		$parameters = $request->output_parameters();
		
		$listing_type = $parameters[0];
		$recommendation_id = (int) $parameters[1];
		
		if($recommendation_id)
		{
			$recommendation_details = $this->recommendation_model->getRecommendationDetails($recommendation_id);
		
			if($recommendation_details)
			{                      
				$response = array(
                                    'listing_id'  => $recommendation_details->courseID,
                                    'listing_url' => $recommendation_details->course_url,
                                    'coursePackType' => $recommendation_details->coursePackType,
							     );
													
				$response_string = base64_encode(gzcompress(json_encode($response)));
				$response = array($response_string,'string');
				return $this->xmlrpc->send_response($response);				 	
			}
			else 
			{
				return $this->xmlrpc->send_error_message('900', 'Invalid recommendation ID');
			}
		}
		else 
		{
			return $this->xmlrpc->send_error_message('900', 'Recommendation ID not available');
		}
	}
	
}

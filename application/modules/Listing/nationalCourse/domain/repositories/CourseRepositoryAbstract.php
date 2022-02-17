<?php
class CourseRepositoryAbstract extends EntityRepository {
	function __construct($dao = NULL,$cache = NULL) {
        parent::__construct($dao,$cache);
        
        $this->dao         = $dao;
        $this->cache       = $cache;

        $this->CI->load->library('CA/CADiscussionHelper');
        $this->caDiscussionHelper =  new CADiscussionHelper();
    }

    protected function getCourseFromCache($courseId, $sections) {
    	
		$dataFromCache = $this->cache->getCourseSections($courseId, $sections);

		$courseLocations = array();
		if(in_array('location', $sections)) {
			$courseLocations = $this->cache->getCourseLocations($courseId);
		}

		$dataFromCache['location'] = $courseLocations;

		if(empty($dataFromCache['basic']))
			return false;

		$courseObj = $this->_populateCourseObjFromData($courseId, $dataFromCache, $sections);

		return $courseObj;
	}
    protected function getMultipleCourseFromCache($courseIds, $sections) {

		$courseObjects = array();
		$dataFromCache = $this->cache->getMultipleCourseSections($courseIds, $sections);

		// get locations from cache
		$courseLocations = array();
		if(in_array('location', $sections)){
			$courseLocations = $this->cache->getMultipleCourseLocationsCache($courseIds);
		}

		global $courseSectionNotCached;
		global $courseSections;
		foreach ($courseSections as $section) {
			if(in_array($section, $courseSectionNotCached) && in_array($section, $sections)){
				$sectionWiseData[$section] = $this->dao->getDataForMultipleCourses($courseIds, $section);
			}
		}
		
		foreach ($dataFromCache as $courseData) {
			$basicSection = reset($courseData['basic']);
			if(empty($courseData['basic']) || !$basicSection['course_id'])
				continue;

			$courseId = $basicSection['course_id'];

			foreach ($courseSections as $section) {
				if(in_array($section, $courseSectionNotCached) && in_array($section, $sections)){
					// $sectionWiseData = $this->dao->getData($courseId, $section);
					$courseData[$section] = $sectionWiseData[$section][$courseId][$section];
				}

			}

			// fill location data
			$courseData['location'] = $courseLocations[$courseId];
			if($courseObj)
				$course= $courseObj;
			else
				$course= $this->_createCourse();

			$this->_loadChildren($courseId, $course,$courseData, false, $sections);
			$courseObjects[$courseId] = $course;			
		}
		
		return $courseObjects;
	}

	protected function populateAndStoreCourseObject($results, $courseObj, $storeInCache = true, $sectionKey = array()) {
		$courses = $this->fillAndStoreCourse($results, $courseObj, $storeInCache, $sectionKey);
		return current($courses);
	}

	protected function fillMultipleCourseObjects($results, $courseObj, $storeInCache = true, $sectionKey = array()) {
		$courses = array();
		foreach ($results as $courseId=>$courseData) {
			$courses += $this->fillAndStoreCourse(array($courseId=>$courseData), $courseObj, $storeInCache, $sectionKey);	
		}
		
		return $courses;
	}

	private function fillAndStoreCourse($results, $courseObj, $storeInCache, $sectionKey) {
		// _p($results); die;
		$courses = array();
		if(is_array($results) && count($results)) {
			foreach($results as $courseId => $result) {
				if($courseObj)
					$course= $courseObj;
				else
					$course= $this->_createCourse();
				$this->_loadChildren($courseId, $course,$result, $storeInCache, $sectionKey);
				$courses[$courseId] = $course;
			}
		}
		return $courses;
	}
	
	// create Course object
	protected function _createCourse() {
		$course= new Course;
		return $course;
	}
	
	// loads all the children of Course
	private function _loadChildren($courseId, $course,$result, $storeInCache = true, $sectionKey) {
		if($sectionKey)
			$children = $sectionKey;
		else {
			global $courseSections;
			$children = $courseSections;	
		}

		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
					$this->_loadChild($courseId, $child,$course,$result[$child], $storeInCache);
			}
			else {
				$this->_loadChild($courseId, $child,$course, null, $storeInCache);
			}
		}
	}

	// load individual child of a Course
	private function _loadChild($courseId,$child,$course,$childResult = NULL, $storeInCache) {
		// _p($courseId);
		// _p($child);
		// _p($childResult); 
		switch($child) {
			case 'basic':
				$this->_loadBasic($course,$childResult, $storeInCache);
				if($storeInCache) {
					//fetching contact details from institute only when caching is disabled
					if(is_object($course->getMainLocation())) {
						$childResult[0]['main_location']['contact_details'] = $this->object_to_array($course->getMainLocation()->getContactDetail());
					}
					$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				}
				break;
			case 'eligibility':
				$eligibility = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $eligibility, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'course_type_information':
				$courseTypeInformation = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $courseTypeInformation, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'location':
				$locations = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $locations, $child);
				if($storeInCache) {
					//fetching contact details from institute only when caching is disabled
					$this->_setContactDetailsForEmptyLocations($course, array($child));
					$locationData = $this->object_to_array($course->getLocations());
					$this->_storeSectionInCache($courseId, $child, $locationData, $storeInCache);
				}
				break;
			case 'placements_internships':
				$placementsInterships = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course,$placementsInterships,$child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'usp':
				$uspDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $uspDetails, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'medium_of_instruction':
				$mediumOfInstructions = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $mediumOfInstructions, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'partner':
				$partnerData = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $partnerData, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;
			case 'media':
				$mediaDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($course, $mediaDetails, $child);
				$this->_storeSectionInCache($courseId, $child, $childResult, $storeInCache);
				break;

		}
	}

	function _addDataToObject(&$courseObj, $sectionData, $section){
		switch($section) {
			case 'course_type_information':
				$courseObj->addCourseTypeInformation($sectionData);
				break;
			case 'entry_course':
				// $courseObj->addEntryCourse($sectionData);
				break;
			case 'exit_course':
				$courseObj->addExitCourse($sectionData);
				break;
			case 'location':
				$courseObj->addLocations($sectionData);
				break;
			case 'eligibility':
				$courseObj->addEligibility($sectionData);
				break;
			case 'fees':
				$courseObj->addFees($sectionData);
				break;
			case 'contact_details':
				$courseObj->addContactDetails($sectionData);
				break;
			case 'main_location':
				$courseObj->addMainLocation($sectionData);
				break;
			case 'placements_internships':
				$courseObj->addPlacementsInternships($sectionData);
				break;
			case 'recognition' :
				$courseObj->addRecognition($sectionData);
				break;
			case 'education_type' :
				$courseObj->addEducationType($sectionData);
				break;
			case 'delivery_method' :
				$courseObj->addDeliveryMethod($sectionData);
				break;
			case 'time_of_learning' :
				$courseObj->addTimeOfLearning($sectionData);
				break;
			case 'difficulty_level' :
				$courseObj->addDifficultyLevel($sectionData);
				break;
			case 'medium_of_instruction' :
				$courseObj->addMediumOfInstruction($sectionData);
				break;
			case 'partner' :
				$courseObj->addPartner($sectionData);
				break;
			case 'usp':
				$courseObj->addUSPList($sectionData);
				break;
			case 'media':
				$courseObj->addMediaList($sectionData);
				break;
		}
	}

	function _addDataToSubObject(&$obj, $sectionData, $section){

		switch($section) {
			case 'fees':
				$obj->addFees($sectionData);
				break;
			case 'contact_details' :
				$obj->addContactDetails($sectionData);
				break;
			case 'credential':
				$obj->addCredential($sectionData);
				break;
			case 'course_level':
				$obj->addCourseLevel($sectionData);
				break;
		}
	}


	private function _loadBasic($course,$result = NULL, $storeInCache = false){
		if(!empty($result)) {
			global $courseBasicSubSections;
			
			$result = current($result);
			$this->fillObjectWithData($course,$result);
			
			foreach ($courseBasicSubSections as $entity) {
				if($result[$entity]) {
					switch ($entity) {
						case 'fees':
							foreach ($result[$entity] as $feesEntityData) {
								$sectionData[] = $this->_createSectionEntity($feesEntityData, $entity);	
							}
							$this->_addDataToObject($course, $sectionData, $entity);
							break;
						case 'main_location':
							$locationnData = $this->_createSectionEntity($result[$entity], $entity);
							foreach ($result[$entity]['fees'] as $feesEntityData) {
								$feesData[] = $this->_createSectionEntity($feesEntityData, 'fees');
							}
							$this->_addDataToObject($locationnData, $feesData, 'fees');
							$contactData = $this->_createSectionEntity($result[$entity]['contact_details'], 'contact_details');
							$this->_addDataToObject($locationnData, $contactData, 'contact_details');
							$this->_addDataToObject($course, $locationnData, $entity);
							if($storeInCache) {
								$this->_setContactDetailsForEmptyLocations($course, array(), true);
							}
							break;
						case 'recognition':
							foreach ($result[$entity] as $recogitionEntity) {
								$recognitionData[] = $this->_createSectionEntity($recogitionEntity,$entity);
							}
							$this->_addDataToObject($course, $recognitionData, $entity);
							break;
						default:
							$sectionData = $this->_createSectionEntity($result[$entity], $entity);
							$this->_addDataToObject($course, $sectionData, $entity);
							break;
					}
					
				}
			}
		}
	}

	private function _loadSectionDetails($result, $sectionName){
		
		$sectionData = array();
		foreach ($result as $value) {
			switch ($sectionName) {
				case 'location':
					$sectionData[$value['listing_location_id']] = $this->_createSectionEntity($value, $sectionName);
					global $courseLocationSubSections;
					foreach ($courseLocationSubSections as $entity) {
						if($value[$entity]) {
							switch ($entity) {
								case 'fees':
									foreach ($value[$entity] as $feesEntityData) {
										$feesData[] = $this->_createSectionEntity($feesEntityData, 'fees');
									}
									$this->_addDataToSubObject($sectionData[$value['listing_location_id']], $feesData, 'fees');
									$feesData = array();
									break;								
								default:
									$subsectionData = $this->_createSectionEntity($value[$entity], $entity);
									$this->_addDataToSubObject($sectionData[$value['listing_location_id']], $subsectionData, $entity);
									break;
							}
						}
					}
					break;
				case 'course_type_information':
				$sectionData[$value['type'].'_course'] = $this->_createSectionEntity($value, $sectionName);
				global $courseTypeSubSections;
				foreach ($courseTypeSubSections as $entity) {
					if($value[$entity]){
						$subsectionData = $this->_createSectionEntity($value[$entity], $entity);
							$this->_addDataToSubObject($sectionData[$value['type'].'_course'], $subsectionData, $entity);
					}
				}
						
					break;

				case 'placements_internships' : 
						$sectionData[$value['type']] = $this->_createSectionEntity($value,$sectionName);
					break;
				default:
					$sectionData[] = $this->_createSectionEntity($value, $sectionName);	
					break;
			}

		}
		return $sectionData;
	}

	private function _loadSingleSectionDetail($result, $sectionName){
		$sectionData = array();
		
		$sectionData = $this->_createSectionEntity($result, $sectionName);
		
		return $sectionData;
	}

	private function _createSectionEntity($result, $sectionName){

		switch ($sectionName) {
			case 'course_type_information':
				$sectionEntity = new CourseTypeInformation;
				break;
			case 'entry_course':
				// $sectionEntity = new CourseTypeInformation;
				break;
			case 'exit_course':
				$sectionEntity = new CourseTypeInformation;
				break;
			case 'eligibility':
				
				$sectionEntity = new Eligibility;
				break;
			case 'main_location':
				$sectionEntity = new NationalLocation;
				break;
			case 'location':
				$sectionEntity = new NationalLocation;
				break;
			case 'fees' :
				$sectionEntity = new Fees;
				break;
			case 'contact_details':
				$sectionEntity = new NationalContact;
				break;
			case 'placements_internships':
				$sectionEntity = new PlacementsInterships;
				break;
			case 'course_level':
			case 'credential':
			case 'recognition':
			case 'education_type':
			case 'delivery_method':
			case 'time_of_learning':
			case 'difficulty_level':
			case 'medium_of_instruction':
				$sectionEntity = new BaseAttribute;
				break;
			// case 'academic':
			// 	$sectionEntity = new Academic;
			// 	break;
			// case 'research_project':
			// 	$sectionEntity = new ResearchProject;
			// 	break;
			case 'usp':
				$sectionEntity = new Usp;
				break;
			case 'partner':
				$sectionEntity = new Partner;
				break;
			// case 'scholarship':
			// 	$sectionEntity = new Scholarship;
			// 	break;
			// case 'company' : 
			// 	$sectionEntity = new Company;
			// 	break;
			case 'media' : 
				$sectionEntity = new NationalMedia;
				break;
			// case 'facility' : 
			// 	$sectionEntity = new Facility;
			// 	break;
			// case 'contact' : 
			// 	$sectionEntity = new Contact;
			// 	break;
		}

		$this->fillObjectWithData($sectionEntity,$result);
		// _p($sectionEntity);
		return $sectionEntity;
	}

	private function _storeSectionInCache($courseId, $sectionName, $sectionData, $storeInCache){

		if(!$storeInCache) {
			return;
		}

		global $courseSectionNotCached;

		if($sectionName == 'location'){
			foreach ($sectionData as &$value) {
				$value = json_encode($value);
			}
			$this->cache->storeCourseLocations($courseId, $sectionData);
		}
		else if(!in_array($sectionName, $courseSectionNotCached)){
			$this->cache->storeCourseSection($courseId, $sectionName, $sectionData);
		}
	}

	function _populateCourseObjFromData($courseId,$dataFromCache, $sectionsNeeded) {

		if($dataFromCache['basic']){
			$courseObj = $dataFromCache['basic'];
			// unset($dataFromCache['basic']);
		}

		if(!isset($courseObj) || get_class($courseObj) != 'Course'){
			$courseObj = new Course;
		}

		global $courseSectionNotCached;
		global $courseSections;
		foreach ($courseSections as $section) {
			if(in_array($section, $courseSectionNotCached) && in_array($section, $sectionsNeeded)){
				$dataFromDB = $this->dao->getData($courseId, $section);
				$courseObj = $this->populateAndStoreCourseObject($dataFromDB, $courseObj, false, array($section));
			}
			else{
				$courseObj = $this->populateAndStoreCourseObject($dataFromDB, $courseObj, false, array($section));
				// $this->_addDataToObject($courseObj, $dataFromCache[$section], $section);
			}

		}

		return $courseObj;
	}

	protected function _validateSections(&$sections){

		global $courseSections;
		
		if($sections == 'full'){
			$sections = $courseSections;
		}
		else if($sections == ''){
			$sections = array('basic','course_type_information');	
		}

		if(!in_array('basic', $sections)){
			$sections[] = 'basic';
		}
		if(!in_array('course_type_information', $sections)){
			$sections[] = 'course_type_information';
		}
	}

	public function isCRExistForCourse($courseId = NULL){

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

	public function fetchCoursesReviewCount($courseId = null){
		if(empty($courseId) || $courseId == null)
			return 0;
		$courseReviewCount = $this->dao->getCourseReviewCount($courseId);
		return $courseReviewCount;
	}


	public function getCourseOrder($courseIds = array()){
		
		if($courseIds == null || empty($courseIds)){
			return null;
		}
		$data = $this->dao->getCourseOrder($courseIds);

		$result = array();

		foreach ($data as $key => $value) {
			$result[$key] = $value;
		}
		
		return $result;
	}

	public function getCourseListingHierarchy($courseId){
		$data = $this->dao->getCourseListingHierarchy($courseId);
		return $data;
	}

	public function _setContactDetailsForEmptyLocations($course,$sections, $fillMainLocation = false){
		
		$courseId = $course->getId();
		if(empty($courseId)) return;
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
        $instRepo = $instituteBuilder->getInstituteRepository();

        if(in_array('location', $sections)){
        	$locations_array = $course->getLocations();
			$location_ids_array = array();
			$empty_location_object_array = array();
			$result = array();
			// check for empty contact id for the locations
			foreach ($locations_array as $locations_object) {
				$contact_id = $locations_object->getContactDetail();
				if(!isset($contact_id) && empty($contact_id)){
					$location_ids_array[] = $locations_object->getLocationId();
					$empty_location_object_array[] = $locations_object;
				}
			}	
        }

        $mainLocationObject = $course->getMainLocation();
        if(empty($mainLocationObject))
			return;
		$course_main_location_contact_details = $course->getMainLocation()->getContactDetail();
		$emptyMainLocationDetails = false;
		if($this->_checkIfEmptyContactDetails($course_main_location_contact_details)){
			$emptyMainLocationDetails = true;
		}
		// if empty is not found return 
		if(count($location_ids_array) == 0 || empty($location_ids_array[0])) {
			if(!$emptyMainLocationDetails)
				return;
			else if($fillMainLocation){
				$empty_location_object_array[] = $mainLocationObject;
			}
		}
		
		if(empty($mainLocationObject)){
			return;
		}
		
		
		$instituteObject = $instRepo->find($course->getInstituteId());		
		$instituteMainLocation = $instituteObject->getMainLocation();
		if(empty($instituteMainLocation)){ 
			return; 
		}
		$instituteLocations = reset($instRepo->getInstituteLocations(array($course->getInstituteId()), $location_ids_array));
		
		foreach ($empty_location_object_array as $locationObjectWithEmptyContact) {
			$locationId = $locationObjectWithEmptyContact->getLocationId();
			$instituteParticularLocation = $instituteLocations[$locationId];
			if(empty($instituteParticularLocation)){
				continue;
			}
			$instituteContactDetails = $instituteLocations[$locationId]->getContactDetail();		
			
			if($this->_checkIfEmptyContactDetails($instituteContactDetails)){
				if($this->_checkIfEmptyContactDetails($course_main_location_contact_details)){
					$finalContactDetails = clone $instituteMainLocation->getContactDetail();
				}else{					
					$finalContactDetails = clone $course_main_location_contact_details;
				}
			}else{				
				$finalContactDetails = clone $instituteContactDetails;
			}
			if(!empty($finalContactDetails))
				$actualLocationId = $finalContactDetails->getListingLocationId();
			$finalContactDetails->setListingLocationId($locationId);
			//set listing_location_id of address information on object
			$finalContactDetails->setActualListingLocationId($actualLocationId);
			// Update Contact Details in Location Object(in Locations Sections)
			$this->_addDataToSubObject($locationObjectWithEmptyContact, $finalContactDetails, 'contact_details');
			// Update Contact Details in Main Location Object(in Basic Section)
			if($locationObjectWithEmptyContact->isMainLocation()){
				$this->_addDataToSubObject($mainLocationObject, $finalContactDetails, 'contact_details');
			}
		}		
	}

	function json_encode_private($object) {
        $public = array();
        $reflection = new ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $public[$property->getName()] = $property->getValue($object);
        }
        return json_encode($public);
    }

    function _checkIfEmptyContactDetails($contactObject){
    	if(empty($contactObject)) return true;
    	$listing_location_id = $contactObject->getListingLocationId();
    	if(empty($listing_location_id)) return true;
    	$website_url = $contactObject->getWebsiteUrl();
    	$address = $contactObject->getAddress();
    	$latitute = $contactObject->getLatitude();
    	$longitude = $contactObject->getLongitude();
    	$admission_contact_number = $contactObject->getAdmissionContactNumber();
    	$admission_email = $contactObject->getAdmissionEmail();
    	$generic_contact_number = $contactObject->getGenericContactNumber();
    	$generic_email = $contactObject->getGenericEmail();

    	if(empty($website_url) && empty($address) && empty($latitute) && empty($longitude) && empty($admission_contact_number) && empty($admission_email) && empty($generic_email) && empty($generic_contact_number)){
    		return true;
    	}
    	return false;
    }

    function object_to_array($obj) {
		if(is_object($obj)) $obj = (array) $this->dismount($obj);
		if(is_array($obj)) {
			$new = array();
			foreach($obj as $key => $val) {
				$new[$key] = $this->object_to_array($val);
			}
		}
		else $new = $obj;
		return $new;
	}

	function dismount($object) {
		$reflectionClass = new ReflectionClass(get_class($object));
		$array = array();
		foreach ($reflectionClass->getProperties() as $property) {
			$property->setAccessible(true);
			$array[$property->getName()] = $property->getValue($object);
			$property->setAccessible(false);
		}
		return $array;
	}

}
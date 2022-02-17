<?php

class CourseRepository extends EntityRepository
{
	private $courseFinderDao;
	private $abroadCourseRepo;

	function __construct($dao,$cache,$courseFinderDao,$model)
	{
		parent::__construct($dao,$cache,$model);
		$this->courseFinderDao = $courseFinderDao;
               
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('Course','CourseAttribute','SalientFeature','RecruitingCompany','CourseFees','CourseDuration','Ranking'
		,'CourseDescriptionAttribute','ContactDetail','InstituteLocation','CourseLocationAttribute','ListingViewCount'),'listing');
		$this->CI->load->entities(array('AbroadCourse','ClassProfile','JobProfile', 'Currency'),'listing');
		$this->CI->load->entities(array('AbroadExam'),'common');
		$this->CI->load->entities(array('Event'),'events');
		$this->CI->load->entities(array('Exam'),'common');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
		$this->CI->load->entities(array('Category'),'categoryList');
	}
	/**
	 * return course object with required value objects
	 *
	 * @param integer $courseId course id
	 * @param array $value_objects_array
	 * @return Object
	 */
	public function findCourseWithValueObjects($courseId,$value_objects_array = array(), $base_course_object = NULL) {
		// check if institute id is valid or not
		Contract::mustBeNumericValueGreaterThanZero($courseId,'Institute ID');
		// get base institute object first
		if(!isset($base_course_object)) {
		$base_course_object = $this->find($courseId);
	    }
	    
		foreach ($value_objects_array as $value_object_name) {
			$this->_populateValueObject($courseId,$value_object_name,$base_course_object);
		}
		return $base_course_object;
	}
	/**
	 * return base course object
	 *
	 * @param integer $courseId course id
	 * @return Object
	 */
	function find($courseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');
		// check for abroad course
        if($this->model->checkIfCourseIdBelongsToAbroad($courseId)) {
            $this->_loadAbroadCourseRepo(); // Load Abraod Course Repo if not loaded already.
            $courseObj = $this->abroadCourseRepo->find($courseId);
//            _p($courseObj);die;
            return $courseObj;
        }

		if($this->caching && $cachedCourse = $this->cache->getCourse($courseId)) {
			// set contact details for the location having base contact details null
		
       if($cachedCourse instanceof AbroadCourse) { /** Preventing abroad course object to pass through contact detail funtion **/
            return $cachedCourse;
        }
	
			$this->_setContactDetailsForEmptyLocations($cachedCourse);
			return $cachedCourse;
		}
		
//		if($this->model->checkIfCourseIdBelongsToAbroad($courseId)) {
//			$this->_loadAbroadCourseRepo(); // Load Abraod Course Repo if not loaded already.
//           return $this->abroadCourseRepo->find($courseId);
//        }

		$courseDataResults = $this->dao->getDataForCourse($courseId);
		
		//get dominant subcat id
		$courseDataResults['general']['dominantSubcatObj'] = '';
		$national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
        $courseDominantSubCategory = $national_course_lib->getCourseDominantSubCategoryDB(array($courseId));
		$dominantSubcatId = $courseDominantSubCategory['subCategoryInfo'][$courseId]['dominant'];
		if(!empty($dominantSubcatId)) {
			$dominantSubcatObj = $this->_loadSubcategoryObjs(array($dominantSubcatId));
			$courseDataResults['general']['dominantSubcatObj'] = $dominantSubcatObj[$dominantSubcatId];
		}
		
		$course = $this->_loadOne($courseDataResults);
		
		// set contact details for the location having base contact details null
		$this->_setContactDetailsForEmptyLocations($course);

		$this->cache->storeCourse($course);
		
		return $course;
	}
	/**
	 * return course objects indexed with course id
	 *
	 * @param array $courseIds course ids
	 * @return Object
	 */
	public function findMultiple($courseIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');

		$orderOfCourseIds = $courseIds;
        /*** Check and load if Abroad Courses ***/
        $coursesFromAbroadRepo = array();
        $abroadCourseIds = $this->model->fetchDiffOfValidAbroadCourseIds($courseIds);
        if(count($abroadCourseIds) > 0) {
            $this->_loadAbroadCourseRepo(); //Load Abroad course repo if not loaded already
            $coursesFromAbroadRepo = $this->abroadCourseRepo->findMultiple($abroadCourseIds);
            $courseIds = array_diff($courseIds,$abroadCourseIds); // national course ids
        }

		$coursesFromCache = array();
		if($this->caching) { //national
			$coursesFromCache = $this->cache->getMultipleCourses($courseIds);
			$foundInCache = array_keys($coursesFromCache);
			$courseIds = array_diff($courseIds,$foundInCache); // national course ids not in cache
		}
		
		// get nat objects
		if(count($courseIds) > 0) {
			$courseDataResults = $this->dao->getDataForMultipleCourses($courseIds);
			
			//get dominant subcat id
			$national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
	        $courseDominantSubCategory = $national_course_lib->getCourseDominantSubCategoryDB($courseIds);
	        $dominantSubcatIds = array();
	        foreach ($courseDominantSubCategory['subCategoryInfo'] as $courseId => $subcatInfo) {
	        	$dominantSubcatIds[$courseId] = $subcatInfo['dominant'];
	        }
	        $dominantSubcatObjs = $this->_loadSubcategoryObjs($dominantSubcatIds);
	        foreach ($courseDataResults as $courseId => $courseData) {
	        	$courseDataResults[$courseId]['general']['dominantSubcatObj'] = $dominantSubcatObjs[$dominantSubcatIds[$courseId]];
	        }
	        
			$coursesFromDB = $this->_load($courseDataResults);
			
			foreach($coursesFromDB as $courseId=>$course) {
				// set contact details for the location having empty contact details
				// loop through every course and set this === not required now == don't remove below commented code
				$this->_setContactDetailsForEmptyLOcations($course);
				
				//store course obj in cache
				$this->cache->storeCourse($course);
			}
		}
        // consolidate nat & abroad
		$courses = array();
		foreach($orderOfCourseIds as $courseId) {
			if(isset($coursesFromCache[$courseId])) {
				$courses[$courseId] = $coursesFromCache[$courseId];
			}
			else if(isset($coursesFromDB[$courseId])) {
				$courses[$courseId] = $coursesFromDB[$courseId];
			}
			else if(isset($coursesFromAbroadRepo[$courseId])) {
				$courses[$courseId] = $coursesFromAbroadRepo[$courseId];
			}
		}

		return $courses;
	}
	
	/***** LOAD Abroad Course Repo If Required ******/
	
	private function _loadAbroadCourseRepo(){
		if(empty($this->abroadCourseRepo)) {
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder 			= new ListingBuilder;
			$this->abroadCourseRepo = $listingBuilder->getAbroadCourseRepository();
		}
	}
	
	// returns latest modified courses based on passesd criteria
	public function getModifiedCourses($criteria)
	{
		return $this->courseFinderDao->getModifiedCourses($criteria);
	}
	// returns courses for multiple institutes
	public function getCoursesByMultipleInstitutes($instituteIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');
		return $this->courseFinderDao->getCoursesByMultipleInstitutes($instituteIds);
	}
	// retruns live courses
	public function getLiveCourses()
	{
		return $this->courseFinderDao->getLiveCourses();
	}
	// returns single course object
	private function _loadOne($results)
	{
		$courses = $this->_load(array($results));
		return $courses[0];
	}
	// filter out result for multiple courses
	private function _load($results)
	{
		$courses = array();

		if(is_array($results) && count($results))
		{
			foreach($results as $courseId => $result)
			{
				$course = $this->_createCourse($result);
				$this->_loadChildren($course,$result);
				$courses[$courseId] = $course;
			}
		}

		return $courses;
	}
    
    // load course object by given data
    public function getCourseByData($data){
        return $this->_loadOne($data);
    }
    
	// create course object and populate it
	private function _createCourse($result)
	{
		$course = new Course;
		$courseData = (array) $result['general'] + (array) $result['ease_of_immigration']+(array) $result['basic_info'];
		$this->fillObjectWithData($course,$courseData);
		$this->_loadFees($course,$courseData);
		$this->_loadDuration($course,$courseData);
		$this->_loadDominantSubcat($course,$courseData);

		return $course;
	}

	private function _loadSubcategoryObjs($subcatIds) {
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		$uniqueSubcatIds = array_unique($subcatIds);
		if(!empty($uniqueSubcatIds)) {
			$subcatObjs = $this->categoryRepository->findMultiple($uniqueSubcatIds);
		}
		
		return $subcatObjs;
	}

	// populate course fees object
	private function _loadFees($course,$result)
	{
		$fees = new CourseFees;
		$this->fillObjectWithData($fees,$result);
		$course->setFees($fees);
	}
        
	// populate course duration object
	private function _loadDuration($course,$result)
	{
		$duration = new CourseDuration;
		$this->fillObjectWithData($duration,$result);
		$course->setDuration($duration);
	}

	// populate course duration object
	private function _loadDominantSubcat($course,$result) {
		if(empty($result['dominantSubcatObj'])) {
			$result['dominantSubcatObj'] = new Category;
		}
		$course->setDominantSubcategory($result['dominantSubcatObj']);
	}
        
	// load all the childern
	private function _loadChildren($course,$result)
	{
		$children = array('attributes','salient_features','exams','recruiting_companies','events','ranking','locations','ldb_courses');
		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
				foreach($result[$child] as $childResult) {
					$this->_loadChild($child,$course,$childResult);
				}
			}
			else {
				/*
				 * Load empty child
				 */
				$this->_loadChild($child,$course);
			}
		}
	}
	// based on the child of course it call appropriate method
	// which in turn populate child
	private function _loadChild($child,$course,$childResult = NULL)
	{
		switch($child) {
			case 'attributes':
				$this->_loadAttribute($course,$childResult);
				break;
			case 'salient_features':
				$this->_loadSalientFeature($course,$childResult);
				break;
			case 'exams':
				$this->_loadExam($course,$childResult);
				break;
			case 'recruiting_companies':
				$this->_loadRecruitingCompany($course,$childResult);
				break;
			case 'events':
				$this->_loadEvent($course,$childResult);
				break;
			case 'ranking':
				$this->_loadRanking($course,$childResult);
				break;
			case 'locations':
				$this->_loadLocation($course,$childResult);
				break;
			case 'ldb_courses':
				$this->_loadLdbCourses($course,$childResult);
				break;
		}
	}

	//populate ldb courses
	private function _loadLdbCourses($course,$result = NULL) {
		$course->addLdbCourse($result['ldbCourseId']);
	}

	// populate course location
	private function _loadLocation($course,$result = NULL)
	{
		$location = $this->_createLocation($result);
		$course->addLocation($location);
	}
	// populate course attribute object
	private function _loadAttribute($course,$result)
	{
		$attribute = new CourseAttribute;
		$this->fillObjectWithData($attribute,$result);
		$course->addAttribute($attribute);
	}
	// populate course salientfeature object
	private function _loadSalientFeature($course,$result)
	{
		$salientFeature = new SalientFeature;
		$this->fillObjectWithData($salientFeature,$result);
		$course->addSalientFeature($salientFeature);
	}
	// populate course exam object
	private function _loadExam($course,$result)
	{
		$exam = new Exam;
		$this->fillObjectWithData($exam,$result);
		$course->addExam($exam);
	}
	// populate course recruiting company object
	private function _loadRecruitingCompany($course,$result)
	{
		$company = new RecruitingCompany;
		$this->fillObjectWithData($company,$result);
		$course->addRecruitingCompany($company);
	}
	// populate course event object
	private function _loadEvent($course,$result)
	{
		$event = new Event;
		$this->fillObjectWithData($event,$result);
		$course->addEvent($event);
	}
	// populate course ranking object
	private function _loadRanking($course,$result)
	{
		$ranking = new Ranking;
		$this->fillObjectWithData($ranking,$result);
		$course->setRanking($ranking);
	}
	// Helps in populating value objects for a course
	private function _populateValueObject($courseId,$value_object_name,$base_course_object) {
		// populate required object
		switch($value_object_name) {
			case 'description':
				$result = $this->model->getDataForCourseDescriptionAttributes($courseId);
				$this->_loadDescription($base_course_object,$result);
				break;
			case 'viewcount':
				$result = $this->model->getCourseViewCount($courseId);
				$this->_loadViewCount($base_course_object,$result[$courseId]);
				break;
		}
	}
	// populate course description object
	private function _loadDescription($base_course_object,$result) {
		foreach ($result as $row) {
			$description = new CourseDescriptionAttribute();
			$this->fillObjectWithData($description, $row);
			$base_course_object->setDescriptionAttribute($description);
		}
	}
	// populates location object and it's children
	private function _createLocation($result)
	{
		$result['entities'] = array();

		$country = new Country;
		$this->fillObjectWithData($country,$result);
		$result['entities']['country'] = $country;

		$state = new State;
		$this->fillObjectWithData($state,$result);
		$result['entities']['state'] = $state;

		$city = new City;
		$this->fillObjectWithData($city,$result);
		$result['entities']['city'] = $city;

		$zone = new Zone;
		$this->fillObjectWithData($zone,$result);
		$result['entities']['zone'] = $zone;

		$locality = new Locality;
		$this->fillObjectWithData($locality,$result);
		$result['entities']['locality'] = $locality;

		$contact_detail = new ContactDetail();
		$this->fillObjectWithData($contact_detail,$result);
		$result['entities']['contact_detail'] = $contact_detail;
			
		$location = new InstituteLocation;
		$this->fillObjectWithData($location,$result);

		$this->_loadCourseLocationAttributes($location,$result['locationattribute'][0]);

		return $location;
	}
	// this method is used for populating contact details for empty locations
	// if for a particular location course is not having any contact details then
	// it populates contact details from main course or institute
	private function _setContactDetailsForEmptyLOcations($course) {
		//  get all the locations
		$locations_array = $course->getLocations();
		$location_ids_array = array();
		$empty_location_object_array = array();
		$result = array();
		// check for empty contact id for the locations
		foreach ($locations_array as $locations_object) {
			$contact_id = $locations_object->getContactDetail()->getContactId();
			if(is_null($contact_id) || empty($contact_id)) {
				$location_ids_array[] = $locations_object->getLocationId();
				$empty_location_object_array[] = $locations_object;
			}
		}
		// if empty is not found return
		if(count($location_ids_array) == 0 || empty($location_ids_array[0])) {
			return;
		}
		// get main course contact detail
		$main_course_contact_details = $this->model->getMainCourseContactDetails($course->getId());
		if(count($main_course_contact_details)>0) {
                        foreach($location_ids_array as $id) {
				$result[$id] = $main_course_contact_details;
                        }
		} else {
			// get contact details from institute
			$result = $this->model->getCourseContactDetailsFromInstitute($location_ids_array,$course->getInstId());
		}
		$this->_loadContactDetail($empty_location_object_array,$result);
	}
	private function _loadContactDetail($empty_location_object_array,$result) {
		// set contact detail for empty case
		foreach ($empty_location_object_array as $object) {
			$child_data = $result[$object->getLocationId()];
			$child_data['institute_location_id'] =  $object->getLocationId();
			$contact_detail = $object->getContactDetail();
			$this->fillObjectWithData($contact_detail,$child_data);
		}

	}
	// populate course location attributes
	private function _loadCourseLocationAttributes($location,$result) {
		foreach ($result as $attribute) {
			$course_location_attribute = new CourseLocationAttribute();
			$this->fillObjectWithData($course_location_attribute,$attribute);
			$location->addAttribute($course_location_attribute);
		}
	}
	// populates view count object
	private function _loadViewCount($base_course_object,$result) {
		// instantiate and populate object
		$viewCount = new ListingViewCount();
		$this->fillObjectWithData($viewCount,$result);
		$base_course_object->addViewCount($viewCount);
	}

     /**
	 * return base course ids array
	 * Return all the courses' ids which are expiring on $expiryDate date
	 * @param date("Y-m-d") $expiryDate paid expiry date
     * @param string $courseType course pack type (can be 'ALL', 'FREE', 'PAID')
	 * @return array
	 */
	function findExpiringCourses($expiryDate, $courseType = 'ALL')
	{
                if($expiryDate == "") {
                    return '';
                }

                $courseFinderModel = $this->CI->load->model('CourseFinderModel');

		return $courseFinderModel->getExpiringCourses($expiryDate, $courseType);
	}

	function findExpiringCoursesForAnInterval($dateToCheckFrom,$dateToCheckUpto,$courseType = 'ALL')
	{
                if($dateToCheckFrom == "" || $dateToCheckUpto == "" ) {
                    return '';
                }

                $courseFinderModel = $this->CI->load->model('CourseFinderModel');

		return $courseFinderModel->getExpiringCoursesForAnInterval($dateToCheckFrom,$dateToCheckUpto,$courseType);
	}

	function getCoursesHavingZeroExpiryDate()
	{
        $courseFinderModel = $this->CI->load->model('CourseFinderModel');

		return $courseFinderModel->getCoursesHavingZeroExpiryDate();
	}




	function getCourseInfoToExpire($courseId,$dateToCheckFrom) {
			if($dateToCheckFrom == ""){
				return '';
			}
		$courseFinderModel = $this->CI->load->model('CourseFinderModel');
		return $courseFinderModel->getCourseInfoToExpire($courseId,$dateToCheckFrom); 	
	}
	
	function getCutOffRanksForCourse($courseId) {
		return $this->model->getCutOffRanksForCourse($courseId);
	}
	
	public function getRedirectionIdForDeletedCourse($listing_id,$listing_type) {
		if(empty($listing_id) || !($listing_id > 0)) {
			return 0;
		}
		
		$instituteModel = $this->CI->load->model('InstituteModel');
		$redirection_id = $instituteModel->getRedirectionIdForDeletedInstitute($listing_id,$listing_type);
		return $redirection_id;
	}
	
	/* Get consolidated info for a set of courses at once
	* Returned data is indexed by 'course ids -> Filters'
		 */
	public function getDataForMultipleCourses($courseIds,$filters) {
		$courseDataResults = $this->dao->getDataForMultipleCourses($courseIds,$filters,TRUE);

		//get dominant subcat id
		$national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
        $courseDominantSubCategory = $national_course_lib->getCourseDominantSubCategoryDB($courseIds);
        $dominantSubcatIds = array();
        foreach ($courseDominantSubCategory['subCategoryInfo'] as $courseId => $subcatInfo) {
        	$dominantSubcatIds[$courseId] = $subcatInfo['dominant'];
        }
        $dominantSubcatObjs = $this->_loadSubcategoryObjs($dominantSubcatIds);
        foreach ($courseDataResults as $courseId => $courseData) {
        	$courseDataResults[$courseId]['general']['dominantSubcatObj'] = $dominantSubcatObjs[$dominantSubcatIds[$courseId]];
        }
		//_p($courseDataResults); //die;
		$coursesFromDB = $this->_loadBasicInfo($courseDataResults,$filters);
	
		return $coursesFromDB;
	}
	
	private function _loadBasicInfo($results,$filters)
	{
		$courses = array();
	    $appliedFilters= explode('|', $filters);
		if(is_array($results) && count($results))
		{
			foreach($results as $courseId => $result)
			{
				$course = $this->_createCourse($result);
				foreach($appliedFilters as $key=>$filterKey){
					switch($filterKey){
						case "head_location":
							$this->_loadLocation($course,$result['head_location'][0]);
						break;

						case "attributes":
							$this->loadAllAttributes($course,$result['attributes']);
						break;

						case "recruiting_companies":
							$this->loadAllRecuritmentCompany($course,$result['recruiting_companies']);
						break;

						case "salient_features":
							$this->loadAllSalientFeatures($course,$result['salient_features']);
						break;

						case "events":
							$this->loadAllEvent($course,$result['events']);
						break;

						case "exams":
							$this->loadAllExams($course,$result['exams']);
						break;
					}
				}
				
				$courses[$courseId] = $course;
			}
		}
	   
		return $courses;
	}
	
	private function loadAllAttributes($course, $result ){
		foreach($result as $row=>$col){
			$this->_loadAttribute($course,$col);
		}
	}

	private function loadAllRecuritmentCompany($course, $result ){
		foreach($result as $row=>$col){
			$this->_loadRecruitingCompany($course,$col);
		}
	}

	private function loadAllSalientFeatures($course, $result ){
		foreach($result as $row=>$col){
			$this->_loadSalientFeature($course,$col);
		}
	}

	private function loadAllEvent($course, $result ){
		foreach($result as $row=>$col){
			$this->_loadEvent($course,$col);
		}
	}

	private function loadAllExams($course, $result ){
		foreach($result as $row=>$col){
			$this->_loadExam($course,$col);
		}
	}

}

<?php

class AbroadCourseRepository extends EntityRepository
{
	private $courseFinderDao;
	private $nationalCourseRepo;
	function __construct($dao,$cache,$courseFinderDao)
	{
		parent::__construct($dao,$cache);
		$this->courseFinderDao = $courseFinderDao;
		
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('AbroadCourse','CourseAttribute','RecruitingCompany','CourseFees','CourseDuration','ClassProfile','JobProfile','InstituteLocation', 'Currency'),'listing');
		$this->CI->load->entities(array('AbroadExam'),'common');
		$this->CI->load->entities(array('Event'),'events');
		$this->CI->load->entities(array('Exam'),'common');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
		// $this->CI->load->entities(array('Course','SalientFeature','Ranking'	,'CourseDescriptionAttribute','ContactDetail','CourseLocationAttribute','ListingViewCount'),'listing');
		
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
		//if cached
		if($this->caching && $cachedCourse = $this->cache->getCourse($courseId)) {
			if($cachedCourse instanceof AbroadCourseOld){
				return $cachedCourse;
			}
			return false;
		}
		//check if its a national course
		$resultantCourse = $this->dao->checkIfCourseIdBelongsToAbroad($courseId);
		if($resultantCourse['course_id'] >0)
		{
			if(in_array($resultantCourse['institute_type'],array('Department','Department_Virtual')) === false)
			{
				return false;
				// $this->_loadNationalCourseRepo(); ** LOAD NATIONAL COURSE REPO **
				// return $this->nationalCourseRepo->find($courseId);
			}
			else{
				//fetch data for abroad course
				$courseDataResults = $this->dao->getData($courseId);
				$course = $this->_loadOne($courseDataResults);

				if(!is_object($course) || $course->getId() == "") {
						$isInvalidCourse= 1;			
				} else {
						$isInvalidCourse= 0;	
				}
				if($isInvalidCourse) {
					 $this->cache->storeCourse($course, $courseId);
				}else{
					 $this->cache->storeCourse($course);
				}
				
				return $course;
			}
		}
		else{
			return false;
		}
	}
	/**
	 * return course objects indexed with course id
	 *
	 * @param array $courseIds course ids
	 * @return Object
	 */
	public function findMultiple($courseIds,$categoryPageFlag = false)
	{
		$courseIds = array_filter($courseIds);
		if(count($courseIds)==0)
			return;
		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');

		$orderOfCourseIds = $courseIds;
		$coursesFromCache = array();
			//if cached
		if($this->caching) {
			$coursesFromCache 	= $this->cache->getMultipleCourses($courseIds,$categoryPageFlag);
			$coursesFromCache 	= array_filter($coursesFromCache,function($ele){if($ele instanceof AbroadCourseOld) return true; return false;});
			$foundInCache 		= array_keys($coursesFromCache);
			$courseIds 			= array_diff($courseIds,$foundInCache);
		}
       
		$abroadCourseIds = array();
		$nationalCourseIds = array();
		$coursesFromNationalRepo = array();
		if(!empty($courseIds)){
			$abroadCourseIds = $this->dao->fetchDiffOfValidAbroadCourseIds($courseIds);
			// $nationalCourseIds = array_diff($courseIds, $abroadCourseIds);
			
			// if(count($nationalCourseIds) > 0) {
			// $this->_loadNationalCourseRepo(); /*** LOAD NATIONAL COURSE REPO ***/
			// $coursesFromNationalRepo = $this->nationalCourseRepo->findMultiple($nationalCourseIds);
			// }
			$courseIds = $abroadCourseIds;
		}
		
		if(count($courseIds) > 0) {
			$courseDataResults = $this->dao->getDataForMultipleCourses($courseIds);
			$coursesFromDB = $this->_load($courseDataResults);
			foreach($coursesFromDB as $course) {
				if($course->getId() == ""){
                    			$this->cache->storeCourse($course, $courseId);
                		}else{
                    			$this->cache->storeCourse($course);
                		}
			}
		}

		$courses = array();
		foreach($orderOfCourseIds as $courseId) {
			if(isset($coursesFromCache[$courseId])) {
				$courses[$courseId] = $coursesFromCache[$courseId];
			}
			else if(isset($coursesFromDB[$courseId])) {
				$courses[$courseId] = $coursesFromDB[$courseId];
			}
			// else if(isset($coursesFromNationalRepo[$courseId])) {
			// 	$courses[$courseId] = $coursesFromNationalRepo[$courseId];
			// }
		}
		return $courses;
	}
	
	/***** LOAD National Course Repo If Required ******/
	
	private function _loadNationalCourseRepo(){
		if(empty($this->nationalCourseRepo)) {
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder 			= new ListingBuilder;
			$this->nationalCourseRepo = $listingBuilder->getCourseRepository();
		}
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
				if((integer)$result['general']['course_id'] <=0){
					continue;
				}
				$course = $this->_createCourse($result);
				$this->_loadChildren($course,$result);
				$courses[$courseId] = $course;
			}
		}

		return $courses;
	}
    
    // load course object by given data
    public function getCourseByData($data)
	{
        return $this->_loadOne($data);
    }
    
	// create course object and populate it
	private function _createCourse($result)
	{
		$course = new AbroadCourseOld;
		$courseData = (array) $result['general'];
		$this->fillObjectWithData($course,$courseData);
		$this->_loadFees($course,$courseData);
		$this->_loadDuration($course,$courseData);
		return $course;
	}
	
	// populate course fees object
	private function _loadFees($course,$result)
	{
		$fees = new CourseFees;
		$this->fillObjectWithData($fees,$result);
		$currency = new Currency;
		$this->fillObjectWithData($currency, $result);
		$fees->setCurrencyEntity($currency);
		$course->setFees($fees);
	}
        
	// populate course duration object
	private function _loadDuration($course,$result)
	{
		$duration = new CourseDuration;
		$this->fillObjectWithData($duration,$result);
		$course->setDuration($duration);
	}
        
	// load all the childern
	private function _loadChildren($course,$result)
	{
		$children = array('attributes','recruiting_companies','class_profile','job_profile','exams','locations','application_details','rmccounsellor_details','scholarship_details','customScholarship_details');
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
			case 'exams':
				$this->_loadExam($course,$childResult);
				break;
			case 'recruiting_companies':
				$this->_loadRecruitingCompany($course,$childResult);
				break;
			case 'class_profile':
				$this->_loadClassProfile($course,$childResult);
				break;
			case 'job_profile':
				$this->_loadJobProfile($course,$childResult);
				break;
			case 'locations':
				$this->_loadLocation($course,$childResult);
				break;
			case 'application_details':
				$this->_loadApplicationDetails($course,$childResult);
				break;
			case 'rmccounsellor_details':
				$this->_loadRmcCounsellorDetails($course,$childResult);
				break;
			case 'scholarship_details':
				$this->_loadCourseScholarshipDetails($course,$childResult);
				break;
			case 'customScholarship_details':
				$this->_loadCourseCustomScholarshipDetails($course,$childResult);
				break;
		}
	}
	
	// adds course application details
	private function _loadApplicationDetails($course,$result = NULL)
	{
		$courseApplicationDetailId = $this->_createCourseApplicationDetail($result);
		$course->setCourseApplicationDetail($courseApplicationDetailId);
	}
	// populates institute location and it's children
	private function _createCourseApplicationDetail($result)
	{
		return $result['id'];
	}
	// creates location object
	private function _loadLocation($course,$result = NULL)
	{
		$location = $this->_createLocation($result);
		$course->addLocation($location);
	}
	
	// populates institute location and it's children
	private function _createLocation($result)
	{
		$result['entities'] = array();

		$region = new Region;
		$this->fillObjectWithData($region,$result);
		$result['entities']['region'] = $region;

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

		$location = new InstituteLocation;
		$this->fillObjectWithData($location,$result);

		return $location;
	}
	
	// populate course attribute object
	private function _loadAttribute($course,$result)
	{
		$attribute = new CourseAttribute;
		$this->fillObjectWithData($attribute,$result);
		$course->addAttribute($attribute);
	}
	
	// populate course exam object
	private function _loadExam($course,$result)
	{
		$exam = new AbroadExam;
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
	
	// populate course class profile object
	private function _loadClassProfile($course,$result)
	{
		$classProfile = new ClassProfile;
		$this->fillObjectWithData($classProfile,$result);
		$course->setClassProfile($classProfile);
	}
	
	// populate course job profile object
	private function _loadJobProfile($course,$result)
	{
		$jobProfile = new JobProfile;
		$this->fillObjectWithData($jobProfile,$result);
		$currency = new Currency;
		$this->fillObjectWithData($currency, $result);
		$jobProfile->setCurrencyEntity($currency);
		$course->setJobProfile($jobProfile);
	}
	
	// adds rmc counsellor details
	private function _loadRmcCounsellorDetails($course,$result = NULL)
	{
		$rmcCounsellorEnabled = $this->_createRmcCounsellorDetails($result);
		$course->setRmcEnabledDetail($rmcCounsellorEnabled);
	}
	// populates institute location and it's children
	private function _createRmcCounsellorDetails($result)
	{
		if($result['status']=='live')
		{
			 return $result['counsellorCount'];
		}
		else
		{
			return '0';	
		}
		
	}
	
	// add scholarship details
	private function _loadCourseScholarshipDetails($course,$result = NULL)
	{
		$course->setScholarshipDescription($result['description']);
		$course->setScholarshipEligibility($result['eligibility']);
		$course->setScholarshipDeadLine($result['deadline']);
		$course->setScholarshipAmount($result['amount']);
		$course->setScholarshipCurrency($result['currency']);
		$course->setScholarshipCurrencyCode($result['currency_code']);
		$course->setScholarshipLink($result['link']);		
	}
	
	// add scholarship details
	private function _loadCourseCustomScholarshipDetails($course,$result = NULL)
	{
		$course->setCustomScholarship($result);		
	}
	
	// get all live abroad courses ids
	public function getLiveAbroadCourses()
	{
		return $this->dao->getLiveAbroadCourses();
	}
}

<?php

class CourseRepository extends CourseRepositoryAbstract
{
	private $courseFinderDao;
	private $courseRepository;
	private $locationRepository;
	private $abroadInstRepo;
	function __construct($dao,$cache)
	{
		parent::__construct($dao,$cache);
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('nationalCourse/Course', 
										'nationalCourse/course/CourseTypeInformation', 
										'nationalCourse/course/Eligibility', 
										'nationalInstitute/institute/NationalLocation', 
										'nationalCourse/course/Fees',
										'nationalCourse/course/PlacementsInterships',
										'nationalCourse/course/Usp',
										'nationalInstitute/institute/NationalContact',
										'nationalInstitute/institute/NationalMedia',
										'nationalCourse/course/BaseAttribute',
										'nationalCourse/course/Partner',
										));
		// $this->caching = false;
	}

	/**
	 * Get Course object section wise
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2016-09-16
	 * @param  [type]     $courseId [Course Id]
	 * @param  string     $sections    [1. If full object is needed pass 'full']
	 *                                 [2. For Section-wise give section names in an array]
	 * Section Names : 'basic', 'eligibility', 'course_type_information','location', 'academic','facility','research_project','usp','event','scholarship','company','placements_internships'
	 * @return [type]                  [Course Object]
	 * @notes  default sections that will be loaded are: ['basic','course_type_information']
	 */
	public function find($courseId, $sections = '')
	{
		
		$courses = $this->findMultiple(array($courseId), $sections);
		if($courses[$courseId]) {
			return $courses[$courseId];
		}
		else {
			return $this->_createCourse();
		}
		/*Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');
		
		// validate and correct the section filters
		$this->_validateSections($sections);

		if($this->caching && $cachedCourse = $this->getCourseFromCache($courseId, $sections)) {
			$this->CI->benchmark->mark('course_object_from_cache_start');
			$this->_setContactDetailsForEmptyLocations($cachedCourse, $sections);
			$this->CI->benchmark->mark('course_object_from_cache_end');
			return $cachedCourse;
		}
		$this->CI->benchmark->mark('course_object_from_db_start');
		$courseDataResults = $this->dao->getData($courseId);
		_p(findMultiple); 
		$course = $this->populateAndStoreCourseObject($courseDataResults, '', true);
		$this->CI->benchmark->mark('course_object_from_db_end');
	
		return $course;*/
	}

	/**
	 * Get multiple Course object section wise
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2016-09-16
	 * @param  [type]     $courseId [Course Id]
	 * @param  string     $sections    [1. If full object is needed pass 'full']
	 *                                 [2. For Section-wise give section names in an array]
	 * Section Names : 'basic', 'eligibility', 'course_type_information','location', 'academic','facility','research_project','usp','event','scholarship','company','placements_internships'
	 * @return [type]                  [Course Object]
	 * @notes  default sections that will be loaded are: ['basic','course_type_information']
	 */
	public function findMultiple($courseIds, $sections = '', $maintainOrder = false, $donotSetContactDetails = true)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');
		$res = array();

		// validate and correct the section filters
		$this->_validateSections($sections);

		// Prepare course objects from Cache
		$cachedCourse = array();
		if($this->caching) {
			static $courseObjCallStack = 1;
			$time = $courseObjCallStack++;
			$this->CI->benchmark->mark('course_object_from_cache_for_'.$time.'_time_start');
			$cachedCourse = $this->getMultipleCourseFromCache($courseIds, $sections);
			$this->CI->benchmark->mark('course_object_from_cache_for_'.$time.'_time_end');
		}

		// Determine what all courses need to be fetched from DB
		$coursesFromCache = array_keys($cachedCourse);
		$coursesToBeFetchedFromDB = array_diff($courseIds, $coursesFromCache);

		// Prepare course objects from DB
		if($coursesToBeFetchedFromDB){
			$this->CI->benchmark->mark('course_object_from_db_start');
			$courseDataResults = $this->dao->getDataForMultipleCourses($coursesToBeFetchedFromDB);
			// _p($courseDataResults); die;
			// _p(json_encode*($courseDataResults[1653]['basic'])); die;
			$courses = $this->fillMultipleCourseObjects($courseDataResults, '', true);
			// _p($courses); die;
			// _p($this->object_to_array($courses[1653])); die;
			$this->CI->benchmark->mark('course_object_from_db_end');
		}

		$courseObjects = (array)$cachedCourse + (array)$courses;

		if($maintainOrder) {
			$courseObjectsTemp = $courseObjects;
			$courseObjects = array();
			foreach ($courseIds as $courseId) {
				if($courseObjectsTemp[$courseId]) {
					$courseObjects[$courseId] = $courseObjectsTemp[$courseId];
				}
			}
		}
		
		return $courseObjects;
	}
	
	/**
	 * return base course ids array
	 * Return all the courses' ids which are expiring on $expiryDate date
	 * @param date("Y-m-d") $expiryDate paid expiry date
     * @param string $courseType course pack type (can be 'ALL', 'FREE', 'PAID')
	 * @return array
	 */
	function findExpiringCourses($expiryDate, $courseType = 'ALL'){
        if($expiryDate == "") {
            return '';
        }

		return $this->dao->getExpiringCourses($expiryDate, $courseType);
	}
        
    function findExpiringCoursesForAnInterval($dateToCheckFrom,$dateToCheckUpto,$courseType = 'ALL'){
        if($dateToCheckFrom == "" || $dateToCheckUpto == "" ) {
            return '';
        }

		return $this->dao->getExpiringCoursesForAnInterval($dateToCheckFrom,$dateToCheckUpto,$courseType);
	}

	function getCoursesHavingZeroExpiryDate(){
		return $this->dao->getCoursesHavingZeroExpiryDate();
	}

	function getCoursesWithoutBrochure(){
		return $this->dao->getCoursesWithoutBrochure();
	}

	function getCourseInfoToExpire($courseId,$dateToCheckFrom) {
		if($dateToCheckFrom == ""){
			return '';
		}
		return $this->dao->getCourseInfoToExpire($courseId,$dateToCheckFrom); 	
	}
	function deleteCoursesCache($courseIds) {
		$this->cache->deleteCoursesCache($courseIds);
	}
}

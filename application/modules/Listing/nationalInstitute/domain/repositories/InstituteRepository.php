<?php

class InstituteRepository extends NationalInstituteRepositoryAbstract
{

	function __construct($dao,$cache)
	{
		parent::__construct($dao,$cache);
       
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('Institute', 'institute/NationalLocation','institute/NationalAcademic','institute/NationalResearchProject', 'institute/NationalUSP', 'institute/NationalEvent', 'institute/NationalScholarship', 'institute/NationalCompany', 'institute/NationalMedia', 'institute/NationalFacility', 'institute/NationalContact', 'institute/InstitutePageSeoInfo'),'nationalInstitute');
		$this->CI->load->config("nationalInstitute/instituteSectionConfig");

		$this->CI->load->library("common/apiservices/APICallerLib");
	}

	/**
	 * Get institute object section wise
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-09-16
	 * @param  [type]     $instituteId [Institute Id]
	 * @param  string     $sections    [1. If full object is needed pass 'full']
	 *                                 [2. For Section-wise give section names]
	 * Section Names : 'basic', 'location', 'academic', 'facility', 'research_project', 'usp', 'event', 'media', 'scholarship', 'company'
	 * @return [type]                  [Institute Object]
	 */
	public function find($instituteId, $sections = '')
	{
		$institutes = $this->findMultiple(array($instituteId), $sections);
		if($institutes[$instituteId]) {
			return $institutes[$instituteId];
		}
		else {
			return $this->_createInstitute();
		}
		
		/*Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');

		// validate and correct the section filters
		$this->_validateSections($sections);

		$this->CI->benchmark->mark('institute_object_from_cache_start');
		// get the data from cache if present
		if($this->caching && $cachedInstitute = $this->getInstituteFromCache($instituteId, $sections)) {
			$this->CI->benchmark->mark('institute_object_from_cache_end');
			return $cachedInstitute;
		}

		$this->CI->benchmark->mark('institute_object_from_db_start');

		// get data from DB
		$instituteDataResults = $this->dao->getData($instituteId);
		
		// populate institute object and store it in cache
		$institute = $this->populateAndStoreInstituteObject($instituteDataResults, '', true);
		$this->CI->benchmark->mark('institute_object_from_db_end');
		
		return $institute;*/
	}

	/**
	 * Get multiple institutes objects
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-09-16
	 * @param  [type]     $instituteIds [Institute Ids]
	 * @param  string     $sections    [1. If full object is needed pass 'full']
	 *                                 [2. For Section-wise give section names]
	 * Section Names : 'basic', 'location', 'academic', 'facility', 'research_project', 'usp', 'event', 'media', 'scholarship', 'company'
	 * @return [type]                  [Institute Objects]
	 */
	public function findMultiple($instituteIds, $sections = array('basic'),$maintainOrder = false)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		// validate and correct the section filters
		$this->_validateSections($sections);

		// Prepare institute objects from Cache
		$cachedInstitute = array();
		if($this->caching) {
			static $instituteObjCallStack = 1;
			$time = $instituteObjCallStack++;
			$this->CI->benchmark->mark('institute_object_from_cache_for_'.$time.'_time_start');
			$cachedInstitute = $this->getMultipleInstituteFromCache($instituteIds, $sections);
			$this->CI->benchmark->mark('institute_object_from_cache_for_'.$time.'_time_end');
		}

		// Determine what all institutes need to be fetched from DB
		$institutesFromCache         = array_keys($cachedInstitute);
		$institutesToBeFetchedFromDB = array_diff($instituteIds, $institutesFromCache);

		// Prepare institute objects from DB
		if($institutesToBeFetchedFromDB){
			$this->CI->benchmark->mark('institute_object_from_db_start');
			
			/*
             * Get object from Java Service
             */
			// $output = $this->CI->apicallerlib->makeAPICall("LISTING","/listing/api/v1/info/getInstitutesObject","POST", "", array("instituteIds[]" => implode(",", $institutesToBeFetchedFromDB), "filters[]" => implode(",", $sections), "disableCache" => true), array(), "", false);
   //          $output = json_decode($output['output']);
   //          $institutes = $output->data;

   //          // _p($institutes); die;

  	// 		// define('__ROOT__', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))); 
			// // require_once(__ROOT__.'/third_party/JsonMapper.php'); 
  	// 		// require_once("/var/www/html/application/third_party/JsonMapper.php");
   //          // $mapper = new JsonMapper();
   //          foreach ($institutes as $instituteId => $stdClassObj) {
  	// 			$this->copyObject($stdClassObj, new Institute());
   //          	// $instituteObj = new Institute();
  	// 			// $instituteObj = clone $obj;
   //          	//$instituteObject[$instituteId] = $mapper->map($obj, new Institute());
   //          	// $instituteObject[$instituteId] = $instituteObj;
   //          }
   //          die;
   //          _p($instituteObject); die;

            /*
             * Get object from Php
             */
			$instituteDataResults = $this->dao->getDataForMultipleInstitutes($institutesToBeFetchedFromDB);

			$institutes = $this->populateAndStoreMultipleInstituteObjects($instituteDataResults, '', true);
			$this->CI->benchmark->mark('institute_object_from_db_end');
		}
		$instituteObjects = (array)$cachedInstitute + (array)$institutes;

		if($maintainOrder) {
			$instituteObjectsTemp = $instituteObjects;
			$instituteObjects = array();
			foreach ($instituteIds as $instituteId) {
				if($instituteObjectsTemp[$instituteId]) {
					$instituteObjects[$instituteId] = $instituteObjectsTemp[$instituteId];
				}
			}
		}
		
		return $instituteObjects;
	}

	public function copyObject($fromObject, $toObject) {
		foreach ($fromObject as $key => $value) {
			if(gettype($value) == object) {
				$obj = $toObject->getPropertyObject($key);
				if(gettype($obj) == "array") { //Maps in Java
					foreach ($value as $key2 => $fromInnerObj) {
						$toInnerObject = clone $obj[0];
						$toInnerObject = $this->copyObject($fromInnerObj, $toInnerObject);
						$tempObj[$key2] = $toInnerObject;
					}
					$obj = $tempObj;
				} else {
					$obj = $this->copyObject($value, $obj);
				}
				$value = $obj;
			}
			elseif(gettype($value) == "array") {

			}
			$toObject->__set($key, $value);
		}

		_p($toObject);
		return $toObject;
	}

	/**
	 * Get institute Locations
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-09-16
	 * @param  [type]     $instituteIds 		      [Institute Ids]
	 * @param  string     $instituteLocationIds    	  [Optional : Institute Location Id for which data is needed]
	 * @param  string     $stateCityLocalityFilter    [Optional : State Ids, city Ids, locality Ids for which data is needed]
	 * Eg. getInstituteLocations(array(34593,44766), 
	 * 							 array(142173, 142516,142608,142670), 
	 * 							 array('states'=>array(128), 'cities'=>array(74), 'localities'=>array(734))
	 * 							);
	 * @return [type]                  [Institute Object]
	 */
	function getInstituteLocations($instituteIds, $instituteLocationIds = array(), $stateCityLocalityFilter = array()){

		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');	
		
		$instituteLocationIds = array_filter($instituteLocationIds);

		$locations = array();
		// get locations from cache
		if($this->caching) {
			$locations = $this->cache->getMultipleInstituteLocations($instituteIds, $instituteLocationIds);
		}
		foreach ($locations as $id => $value) {
			$locations[$id] = $this->_loadSectionDetails($locations[$id], 'location');
		}
		// check which all institute-location needs to be picked from DB
		$institutesToBeFetchedFromDB = array_diff($instituteIds, array_keys($locations));
		foreach ($locations as $instituteId=>$instituteLocation) {
			if(empty($instituteLocation)){
				$institutesToBeFetchedFromDB[] = $instituteId;
			}
		}

		if(!empty($institutesToBeFetchedFromDB)){
			$locationsFromDB = $this->dao->getDataForMultipleInstitutes($institutesToBeFetchedFromDB, 'location');
		}
		//_p($locationsFromDB);die;

		foreach ($locationsFromDB as $instituteId => $locationData) {
			
			// unset unnecessary location-ids from location list
			foreach ($locationData['location'] as $key => $value) {
				if(!in_array($value['listing_location_id'], $instituteLocationIds) && !empty($instituteLocationIds))
					unset($locationData['location'][$key]);
			}

			// populate location objects
			$locations[$instituteId] = $this->_loadSectionDetails($locationData['location'], 'location');
			// _p($locations);die;
		}
		$this->_filterLocationsByStateCityLocality($locations, $stateCityLocalityFilter);

		$locations = array_filter($locations);

		return $locations;
	}

	/*
	 * Find institutes with given courses
	 * Accepts array <Institute Id> => <Array of course ids>
	 * e.g.
	 * [12 => array(45,876)]
	 * [56 => array(89,65)]
	 */
	public function findWithCourses($institutesWithCourses, $instituteSections = '', $courseSections = '', $options = array('setContactDetails' => true))
	{
		/*
		 * Extract institute ids and courses ids
		 * So that these can be used in findMultiple()
		 */
		$instituteIds = array();
		$courseIds    = array();
		foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
			$instituteIds[]     = $instituteId;
			$instituteCourseIds = (array) $instituteCourseIds;
			$courseIds          = array_merge($courseIds,$instituteCourseIds);
		}

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$courseRepository    = $builder->getCourseRepository();

		if($instituteIds)
			$institutes = $this->findMultiple($instituteIds, $instituteSections);
			
		if($courseIds)
			$courses = $courseRepository->findMultiple($courseIds, $courseSections, false, $options['setContactDetails']);
		// foreach ($courseIds as $courseId) {
		// 	error_log("course Ids".$courseId);
		// 	$courses[$courseId] = $courseRepository->find($courseId, $courseSections);
		// }


		$result = array();
		foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
			$instituteCourseIds = (array) $instituteCourseIds;
			$courseCount = 0;   // to check if at least one course exists for an institute or not 
			foreach($instituteCourseIds as $instituteCourseId) {
				if(isset($institutes[$instituteId]) && !empty($courses[$instituteCourseId])) {
					$courseCount++;
					$institutes[$instituteId]->addCourse($courses[$instituteCourseId]);
				}
			}
            if($courseCount != 0) {
            	// unset($institutes[$instituteId]);
            	$result[$instituteId] = $institutes[$instituteId];
            	unset($institutes[$instituteId]);
            }
		}

		return $result;
	}

	function getCoursesOfInstitutes($instituteIds, $instituteSections = '', $courseSections = ''){

		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		$instituteCoursesMapping = $this->dao->getCoursesOfInstitutes($instituteIds);

		$institutes = array();
		if(!empty($instituteCoursesMapping))
			$institutes = $this->findWithCourses($instituteCoursesMapping, $instituteSections, $courseSections);

		return $institutes;
	}


	function getCoursesListForInstitutes($instituteIds,$dbHandle='writeHandle'){
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');
		$instituteCoursesMapping = $this->dao->getCoursesOfInstitutes($instituteIds,'',$dbHandle);
		return $instituteCoursesMapping;
	}

}

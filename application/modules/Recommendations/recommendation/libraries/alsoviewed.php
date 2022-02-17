 <?php

class AlsoViewed
{
	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library('recommendation/alsoviewed/AlsoViewedFactory');
		$this->CI->load->model('nationalCourse/nationalcoursemodel');
        $this->cache = PredisLibrary::getInstance();

        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
	}

	/*
	 * @array - seed courses
	 * @int - num results
	 * @array - institutes to be excluded from result
	 */
	function getAlsoViewedCourses($courseIds, $numResults = 10, $exclusionList = array())
	{
        if(!is_array($courseIds) || count($courseIds) == 0) {
			return array();
		}

		/**
		 * All the institutes corresponding to given course ids
		 * should be included in exclusion list
		 */
		// if contains only one courseId use cache to save db query
		if(count($courseIds) == 1){
			$courseId = reset($courseIds);
			$courseObj = $this->courseRepo->find($courseId, array('location'));
			if(!empty($courseObj) && $courseObj->getId() != ''){
				$courseInstituteIds[$courseId] = $courseObj->getInstituteId();
			}
		}
		else{
			$courseInstituteIds = $this->CI->nationalcoursemodel->getInstituteIdsForCourses($courseIds);
		}
		$courseInstituteIds = array_unique(array_values($courseInstituteIds)); 
		if(!is_array($exclusionList)) {
			$exclusionList = array();
		}
		$exclusionList = array_merge($exclusionList, $courseInstituteIds);
		
		$numResults = intval($numResults);
		if(!$numResults) {
			$numResults = 10;
		}

		$alsoViewedGenerator = AlsoViewedFactory::getAlsoViewedGenerator(ALSO_VIEWED_ALGO);
		return $alsoViewedGenerator->getAlsoViewedCourses($courseIds, $numResults, $exclusionList);
	}
	
	function getAlsoViewedInstitutes($instituteIds, $numResults = 10, $exclusionList = array(), $prefetchedCourseIds = array())
	{
		return $this->getAlsoViewedInstituteResults($instituteIds, 'institute', $numResults, $exclusionList, $prefetchedCourseIds);
	}
	
	function getAlsoViewedUniversities($universityIds, $numResults = 10, $exclusionList = array(), $prefetchedCourseIds = array())
	{
        return $this->getAlsoViewedInstituteResults($universityIds, 'university', $numResults, $exclusionList, $prefetchedCourseIds);
	}

	/**
	 * Also viewed results for both INSTITUTES and UNIVERSITIES (specified by TYPE)
	 */ 
	function getAlsoViewedInstituteResults($instituteIds, $type, $numResults = 10, $exclusionList = array(), $prefetchedCourseIds = array())
	{
        $cacheKey = "INS_REC#".implode(':', $instituteIds)."#".$type."#".implode(':', $exclusionList);
        
        /**
         * First check in cache
         * Return if required no. of results found in cache
         */
        $resultsFromCache = $this->cache->getMemberOfString($cacheKey);  
        if(!empty($resultsFromCache)) {
            $resultsFromCache = json_decode($resultsFromCache, TRUE);
            if(is_array($resultsFromCache) && count($resultsFromCache) >= $numResults) {
                return array_slice($resultsFromCache, 0, $numResults);
            }    
        }
        
        
        /**
         * Fetch from source
         */ 
		$this->CI->load->library('nationalInstitute/InstituteDetailLib');
		$courseIds = array();
		$includedInstituteIds = array();
		
		foreach($instituteIds as $instituteId) {

			if(!empty($prefetchedCourseIds) && !empty($prefetchedCourseIds[$instituteId]))
				$courses = $prefetchedCourseIds[$instituteId];
			else
				$courses = $this->CI->institutedetaillib->getInstituteCourseIds($instituteId, $type);

			foreach($courses['instituteWiseCourses'] as $instId => $instCourseIds) {
				$includedInstituteIds[] = $instId;
				$courseIds = array_merge($courseIds, $instCourseIds);
			}
		}
		
		if(count($courseIds) == 0) {
			return array();
		}
		
		$exclusionList = array_merge($exclusionList, $includedInstituteIds);
		
        /**
         * Max no. of results which could be requested from this API
         * Used in caching e.g. store MAX no. in cache
         * and then return whatever no. N (<= MAX) is requested
         * If more than MAX is requested, update MAX to that number
         */ 
        $MAXRESULTS = 20;
        if($numResults > $MAXRESULTS) {
            $MAXRESULTS = $numResults;
        }
        
		$alsoViewedResults = $this->getAlsoViewedCourses($courseIds, $MAXRESULTS, $exclusionList);
		$alsoViewedInstitutes = array();
		foreach($alsoViewedResults as $result) {
			$alsoViewedInstitutes[] = $result['instituteId'];
		}
		
        /**
         * Store in cache
         */
        $expireInSeconds = 86400;
        $cacheResult =  json_encode($alsoViewedInstitutes);
        $this->cache->addMemberToString($cacheKey, $cacheResult, $expireInSeconds);  
        
		return array_slice($alsoViewedInstitutes, 0, $numResults);	
	}
	
	function getAlsoViewedListings()
	{
		return array();
	}
}

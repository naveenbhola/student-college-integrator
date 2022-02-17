<?php

class ListingCache extends Cache
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Institute
	 */ 
	public function getInstitute($instituteId)
    {
		$data = unserialize(gzuncompress($this->get('Institute',$instituteId)));
		return $data;
	}
	
	public function getMultipleInstitutes($instituteIds)
    {
		$institutes =  $this->multiGet('Institute',$instituteIds);
		$institutes = array_map('unserialize', array_map('gzuncompress',$institutes));
		return $institutes;
    }
	
	public function storeInstitute($institute)
	{
		$data = gzcompress(serialize($institute), 9);
		$this->store('Institute', $institute->getId(), $data,-1, NULL, 1);
	}
	
	public function deleteInstitute($instituteId)
	{
		$this->delete('Institute',$instituteId);
	}
	
	public function storeInstituteCacheKeys($instituteIds)
	{
		if(is_array($instituteIds)) {
			$cacheKeys = array();
			foreach($instituteIds as $instituteId) {
				$cacheKeys[] = $this->getCacheKey('Institute',$instituteId);
			}
			$this->cacheLib->store(CACHEPRODUCT_INSTITUTE,serialize($cacheKeys),-1);
		}
	}
	
	/*
	 * Course
	 */
	function getCourse($courseId)
    {
		$data = unserialize(gzuncompress($this->get('Course',$courseId)));
		return $data;
	}
	
	function getMultipleCourses($courseIds,$categoryPageFlag = false)
    {
		$courses =  $this->multiGet('Course',$courseIds);
		if($categoryPageFlag){
			foreach($courses as $key=>$course){
				$course = unserialize(gzuncompress($course));
				if($course instanceOf AbroadCourse){
					$course->cleanForCategorypage();
					$courses[$course->getId()] = $course;
				}else{
					unset($courses[$key]);
				}
			}
		}else{
			$courses = array_map('unserialize', array_map('gzuncompress', $courses));
		}

		return $courses;
    }
	
/*	function storeCourse($course)
	{		
		$data = gzcompress(serialize($course), 9);
		$this->store('Course',$course->getId(), $data,-1, NULL, 1);
	}
*/
    /* introduced deletedCourseId because for deleted Course we receive blank Object which do not have courseId
     * to save data against any object we need its ID.
    */
    function storeCourse($course, $deletedCourseId = 0)
    {
        $data = gzcompress(serialize($course), 9);

        if($deletedCourseId != 0) {
                $courseId = $deletedCourseId;
        } else {
                $courseId = $course->getId();
        }
        $this->store('Course',$courseId, $data,-1, NULL, 1);
    }
	function storeLastYearViewResponseCountForInstitute($info,$institute_id)
	{   
		$data = serialize($info);
		$this->store('LastYearViewResponseCount',$institute_id,$data,43200,NULL, 0);
	}
	
	function getCachedLastYearViewResponseCountForInstitute($institute_id)
	{  
		$data = unserialize($this->get('LastYearViewResponseCount',$institute_id));
		return $data;
	}
	
	function deleteCourse($courseId)
	{
		$this->delete('Course',$courseId);
	}
	
	public function storeCourseCacheKeys($courseIds)
	{
		if(is_array($courseIds)) {
			$cacheKeys = array();
			foreach($courseIds as $courseId) {
				$cacheKeys[] = $this->getCacheKey('Course',$courseId);
			}
			$this->cacheLib->store(CACHEPRODUCT_COURSE,serialize($cacheKeys),-1);
		}
	}
	
	public function getExamsList()
    {
		$examList = $this->get('categorypage','examlist');
		if(empty($examList)){
			return array();
		}
		return $examList;
	}
	
	function storeExamsList($examsList = array())
	{		
		if(!empty($examsList)){
			$this->store('categorypage','examlist', $examsList, 86400, NULL, 1);
		}
	}

	public function getRedirectExamsList(){
		$redirectExamsList = $this->get('categorypage','redirectExamsList');
		if(empty($redirectExamsList)){
			return array();
		}
		return $redirectExamsList;
	}

	function storeRedirectExamsList($redirectExamsList){
		if(!empty($redirectExamsList)){
			$this->store('categorypage','redirectExamsList',$redirectExamsList, 86400, NULL, 1);
		}
	}
	
	function getSimilarCoursesForCourse($courseId)
	{
		$data = unserialize(gzuncompress($this->get('SimilarCourses',$courseId)));
		if(empty($data)){
			return array();
		}
		return $data;
	}
		
	function storeSimilarCoursesForCourse($courseId, $data)
	{
		$data = gzcompress(serialize($data), 9);
		$this->store('SimilarCourses',$courseId, $data,86400, NULL, 1);
	}

	function getCampusRepInstituteData($instituteId = NULL)
	{
		$data = array();
		if(!empty($instituteId)){
			$data = $this->get('instituteCampusRep', $instituteId);
			$data = unserialize(gzuncompress($this->get('instituteCampusRep', $instituteId)));
		}
		return $data;
	}
	
	function storeCampusRepInstituteData($instituteId, $data = array())
	{
		if(!empty($data)){
			$data = gzcompress(serialize($data), 9);
			$this->store('instituteCampusRep', $instituteId, $data, 7200, NULL, 1);
		}
	}
	
	function getCampusRepCourseData($courseId = NULL)
	{
		$data = array();
		if(!empty($courseId)){
			$data = $this->get('courseCampusRep', $courseId);
			$data = unserialize(gzuncompress($this->get('courseCampusRep', $courseId)));
		}
		return $data;
	}
	
	function storeCampusRepCourseData($courseId, $data = array())
	{
		if(!empty($data)){
			$data = gzcompress(serialize($data), 9);
			$this->store('courseCampusRep', $courseId, $data, 7200, NULL, 1);
		}
	}
	
	function deleteCampusRepCourseData($courseId) {
		$this->delete('courseCampusRep', $courseId);
	}
	
	function deleteCampusRepInstituteData($instituteId) {
		$this->delete('instituteCampusRep', $instituteId);
	}
	
	function getCampusRepInstituteCommentCount($instituteId = NULL)
	{
		$data = array();
		if(!empty($instituteId)){
			$data = $this->get('instituteCampusRepCommentCount', $instituteId);
		}
		return $data;
	}
	
	function storeCampusRepInstituteCommentCount($instituteId, $data)
	{
		if(!empty($data)){
			$this->store('instituteCampusRepCommentCount', $instituteId, $data, 1800, NULL, 1);
		}
	}
	

	function getFeaturedInstitutesForRankingPage($RankingPageKey)
	{
		$data = array();
		if(!empty($RankingPageKey)){
			$data = unserialize(gzuncompress($this->get('RankingPageFeaturedInstitute', $RankingPageKey)));
		}
		return $data;
	}
	
	function storeFeaturedInstitutesForRankingPage($RankingPageKey, $data = array())
	{
		if(!empty($data)){
			$data = gzcompress(serialize($data), 9);
			$this->store('RankingPageFeaturedInstitute', $RankingPageKey, $data, 43200, NULL, 1);
		}
	}
	
	/*
	 * University
	 */ 
	public function getUniversity($universityId)
    {
		$data = unserialize($this->get('University',$universityId));
		return $data;
    }
	
	public function getMultipleUniversities($universityIds,$categoryPageFlag = false)
    {
		$universities =  $this->multiGet('University',$universityIds);
		if($categoryPageFlag){
			foreach($universities as $university){
				$university = unserialize($university);
				$university->cleanForCategoryPage();
				$universities[$university->getId()] = $university;
			}
		}else{
			$universities = array_map('unserialize',$universities);		 
		}
		
		return $universities;
    }
	
	public function storeUniversity($university)
	{
		$data = serialize($university);
		$this->store('University',$university->getId(), $data,-1, NULL, 1);
	}
	
	public function deleteUniversity($universityId) {
		$this->delete('University',$universityId);
	}
	
	public function storeVirtualCityMappingForSearch($data) {
		if(!empty($data)) {
			$data = serialize($data);
			$this->store('SearchVirtualCityMapping', 'map', $data, 86400, NULL, 1);
		}
	}
	
	public function getVirtualCityMappingForSearch() {
		$data = unserialize($this->get('SearchVirtualCityMapping', 'map'));
		return $data;
    }

	function storeViewCountOfInstitutes($viewCountForInstitues)
	{   //Cache file set to 24 Hours
		$data = serialize($viewCountForInstitues);
		$this->store('ViewCount','Institutes',$data,86400,NULL, 0);
	}
	
	function getViewCountOfInstitutes()
	{
		 $data = $this->get('ViewCount','Institutes');
		 return unserialize($data);
	}
	
	function storeIndexingInProgressIds($data) {
		$data = serialize($data);
		$this->store('indexingInProgress',1,$data,43200,NULL, 0);
	}
	
	function getIndexingInProgressIds() {
		$data = unserialize($this->get('indexingInProgress',1));
		return $data;
	}
	
	function increment($key) {
		return $this->inc($key);
	}
        
    function storeCourseReviews($courseReviews,$courseId) {		
        $data = gzcompress(serialize($courseReviews), 9);
        $this->store('CourseReviews',$courseId, $data,10800, NULL, 1);
	}

    function getMultipleCoursesReviews($courseIds) {
        $coursesReviews =  $this->multiGet('CourseReviews',$courseIds);
        return array_map('unserialize', array_map('gzuncompress', $coursesReviews));
    }

    function getMultipleCoursesReviewsForRankingPage($courseIds){
    	$coursesReviews =  $this->multiGet('CourseReviewsForRankingPage',$courseIds);
    	return array_map('unserialize', array_map('gzuncompress', $coursesReviews));
    }

    function storeCourseReviewsForRankingPage($courseReview,$courseId){
    	$data = gzcompress(serialize($courseReview), 9);
    	$this->store('CourseReviewsForRankingPage',$courseId, $data,10800, NULL, 1);
    }
    
	function storeQuestionCountForCourse($questionCount,$courseId) {		
        $data = gzcompress(serialize($questionCount), 9);
        $this->store('CourseQuestionCount',$courseId, $data, 10800, NULL, 1); //3 hrs cache
	}

    function getMultipleCoursesQuestionCount($courseIds) {		
        $questionCount = $this->multiGet('CourseQuestionCount',$courseIds);
        return array_map('unserialize', array_map('gzuncompress', $questionCount));
    }

    function deleteCourseQuestionCount($courseId) {
		$this->delete('CourseQuestionCount',$courseId);
	}

	function storeReviewCountForCourse($questionCount,$courseId) {		
        $data = gzcompress(serialize($questionCount), 9);
        $this->store('CourseReviewCount',$courseId, $data, 10800, NULL, 1); //3 hrs cache
	}

    function getMultipleCoursesReviewCount($courseIds) {		
        $questionCount = $this->multiGet('CourseReviewCount',$courseIds);
        return array_map('unserialize', array_map('gzuncompress', $questionCount));
    }

    function deleteCourseReviewCount($courseId) {
		$this->delete('CourseReviewCount',$courseId);
	}

    function storeNonMbaAlumniReviewsOfInstitute($instituteId,$reviews) {   
        if(!empty($instituteId)) {
            $data = gzcompress(serialize($reviews), 9);
            $this->store('NonMbaAlumniReviews',$instituteId, $data,3600,NULL, 1);
            return;
        }
	}
	
	function getNonMbaAlumniReviewsOfInstitute($instituteId) {
            if(!empty($instituteId)) {
                $data = $this->get('NonMbaAlumniReviews',$instituteId);
                return unserialize(gzuncompress($data));
            }
            else {
                return array();
            }
            
	}

	function storeShareCouponsUser($userId){
		 if(!empty($userId)) {
		 		$data = date("Y-m-d");
                $this->store('ShareCouponCodeUser',$userId, $data,-1,NULL, 1);
                return;
            }
	}

	function getShareCouponsUser($userId){

		if(!empty($userId)) {
                $data = $this->get('ShareCouponCodeUser',$userId);
                return $data;
            }
            else {
                return array();
            }
		
	}
	function getSpecialCourseLocData($id){
		$data = unserialize(gzuncompress($this->get('SpecialCourseLoc',$id)));
		return $data;
	}

	public function storeSpecialCourseLocData($data, $id){
		$data = gzcompress(serialize($data), 9);
		$this->store('SpecialCourseLoc', $id, $data,86400, NULL, 1);
	}

	public function getLocationwiseCourseListForInstitute($instituteId){
		error_log("ROMILGOEL : FROM CACHE");
		$data = unserialize(gzuncompress($this->get('LocationwiseCourseListForInstitute',$instituteId)));
		return $data;
	}

	public function storeLocationwiseCourseListForInstitute($data, $instituteId){
		$data = gzcompress(serialize($data), 9);
		$this->store('LocationwiseCourseListForInstitute', $instituteId, $data,86400, NULL, 1);
	}
}

<?php 

class NationalCourseCache{

	private $courseCacheKeyPrefix = "V1Course:";
	private $courseLocationCacheKeyPrefix = "V1CourseLoc:";
	private $courseInterLinkingPrefix = "V1CourseInterLinking:";
	private $courseEligibilityPrefix = "V1CourseEligibility:";

	function __construct()
	{
		$this->CI =& get_instance();
		
		$this->CI->load->library('cacheLib');
		$this->cacheLib = $this->CI->cachelib;

		$this->_redis_client = PredisLibrary::getInstance();
	}

	public function canOpenCourseInEdit($courseId,$userId){
		$stringKey 	= "Lock_Course:".$courseId;
		$isKeyExist = $this->_redis_client->getMemberOfString($stringKey);

		if(!empty($isKeyExist) && $isKeyExist != $userId){
			return array('userId' => $isKeyExist,'open'=>false);
		}
		return array('open'=>true);
	}

	public function lockCourseForEdit($courseId,$userId){
		$stringKey 	= "Lock_Course:".$courseId;
		$expireInSeconds = 20 * 60;// minutes * seconds
		$data = $this->_redis_client->addMemberToString($stringKey,$userId,$expireInSeconds);
		return $data;
	}

	public function removeLockOnCourseForEdit($courseId){
		$stringKey 	= "Lock_Course:".$courseId;
		return $this->_redis_client->deleteKey(array($stringKey));
	}

	public function getMultipleCourseSections($courseIds, $sections) {
    	$data = array();

        $this->_redis_client->setPipeLine();
		foreach ($courseIds as $courseId) {
                $hashKey    = $this->courseCacheKeyPrefix.$courseId;
                $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$sections, TRUE);
        }
		$data = $this->_redis_client->executePipeLine();

		foreach ($data as $key=>$courseRow) {
			foreach ($courseRow as $key1=>$courseSection) {

				$data[$key][$sections[$key1]] = json_decode($courseSection, true);
				unset($data[$key][$key1]);
			}
		}

		return $data;
	}

	public function getCourseSections($courseId, $sections)
    {
    	$hashKey    = $this->courseCacheKeyPrefix.$courseId;
    	$dataFromCache = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$sections);

    	foreach ($dataFromCache as &$value) {
			$value = json_decode($value, true);
		}
		return $dataFromCache;
	}
	
	public function storeCourseSection($courseId, $sectionName, $sectionData)
	{
		$data       = json_encode($sectionData);
		$hashKey    = $this->courseCacheKeyPrefix.$courseId;
		$hashMember = array($sectionName => $data);

		$this->_redis_client->addMembersToHash($hashKey,$hashMember,FALSE);
	}

	public function storeCourseLocations($courseId, $sectionData)
	{
		if(empty($sectionData))
			return;
		$hashKey    = $this->courseLocationCacheKeyPrefix.$courseId;

		$this->_redis_client->addMembersToHash($hashKey,$sectionData,FALSE);
	}

	public function getCourseLocations($courseId, $locationIds = array())
    {
    	$hashKey    = $this->courseLocationCacheKeyPrefix.$courseId;

    	if(empty($locationIds))
    		$locations = $this->_redis_client->getAllMembersOfHashWithValue($hashKey);
    	else
    		$locations = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey, $locationIds);
  
    	foreach ($locations as &$value) {
    		$value = json_decode($value, true);
    	}

		return $locations;
	}

	public function getMultipleCourseLocations($courseIds, $locationIds = array()){

		$data = array();
		foreach ($courseIds as $courseId) {
			$data[$courseId] = $this->getCourseLocations($courseId, $locationIds);

			// unset empty arrays
			foreach ($data[$courseId] as $key => $value) {
				if(empty($value))
					unset($data[$courseId][$key]);
			}
		}

		$data = array_filter($data);

		return $data;
	}

	public function getMultipleCourseLocationsCache($courseIds){

		$data = array();

		// get data from redis
		$this->_redis_client->setPipeLine();
		$reverseCourseMapping = array();
		foreach ($courseIds as $courseId) {
			$hashKey   = $this->courseLocationCacheKeyPrefix.$courseId;
			$locations = $this->_redis_client->getAllMembersOfHashWithValue($hashKey, TRUE);
			$reverseCourseMapping[] = $courseId;

			foreach ($locations as &$value) {
	    		$value = json_decode($value, true);
	    	}
	    	$data[$courseId] = $locations;
		}
		$data = $this->_redis_client->executePipeLine();

		// reverse mapping of locations
		$finalData = array();
		foreach ($data as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$value[$key1] = json_decode($value1, true);
			}
			$finalData[$reverseCourseMapping[$key]] = $value;
		}
		unset($data);

		return $finalData;
	}

	public function isUserAllowedInEditMode($listingId,$listingType,$userId)
	{
		$stringKey 	= "Lock_".substr($listingType,0,4).":".$listingId;
		$isKeyExist = $this->_redis_client->getMemberOfString($stringKey);

		if( !empty($isKeyExist) && (int) $isKeyExist != $userId)
		{
			return false;
		}
		$expireInSeconds = 2 * 60 * 60;//hours * minutes * seconds
		$data = $this->_redis_client->addMemberToString($stringKey,$userId,$expireInSeconds);
		return $data;
	}
	public function isAllowUserAction($listingId,$listingType,$userId)
	{
		$stringKey 	= "Lock_".substr($listingType,0,4).":".$listingId;
		$isKeyExist = $this->_redis_client->getMemberOfString($stringKey);

		if( !empty($isKeyExist) && (int) $isKeyExist != $userId)
		{
			return false;
		}
		return true;
	}
	public function removeLockListingForm($listingId,$listingType)
	{
		$stringKey 	= "Lock_".substr($listingType,0,4).":".$listingId;
		return $this->_redis_client->deleteKey(array($stringKey));
	}

	function removeCoursesCache($courseIds){

		if(empty($courseIds))
			return;

		$courseKeys = array();
		// $this->_redis_client->setPipeLine();
		// delete course cache and course location cache
		foreach ($courseIds as $courseId) {
			$courseKeys[] = $this->courseCacheKeyPrefix.$courseId;
			$courseKeys[] = $this->courseLocationCacheKeyPrefix.$courseId;
			$courseKeys[] = $this->courseInterLinkingPrefix.$courseId;
			$courseKeys[] = $this->courseEligibilityPrefix.$courseId;
		}
		$redisResponse = $this->_redis_client->deleteKey($courseKeys);
		// $this->_redis_client->executePipeLine();

		$this->CI->load->builder("nationalCourse/CourseBuilder");
    	$courseBuilder = new CourseBuilder();
    	$this->courseRepo = $courseBuilder->getCourseRepository();
		$this->courseRepo->disableCaching();
    	// use write handle to make course object because of lag between master and slave
    	global $forceListingWriteHandle;
    	$forceListingWriteHandle = true;

    	$courseObjs = $this->courseRepo->findMultiple($courseIds,array('basic'));
    	foreach ($courseIds as $courseId) {
    		$courseObj = $courseObjs[$courseId];
    		if(!empty($courseObj) && $courseObj->getId() != ''){
    			$courseUrl = $courseObj->getAmpURL();
    			updateGoogleCDNcacheForAMP($courseUrl);
    		}
    	}
    	$this->courseRepo->enableCaching();
	}

	public function storeCourseEligibility($courseId, $data){
		if(empty($courseId) || empty($data)){
			return;
		}
		$stringKey = $this->courseEligibilityPrefix.$courseId;
		$this->_redis_client->addMemberToString($stringKey,json_encode($data));
	}

	public function getCourseEligibility($courseId){
		if(empty($courseId)){
			return;
		}
		$stringKey = $this->courseEligibilityPrefix.$courseId;
		$data = $this->_redis_client->getMemberOfString($stringKey);
		return json_decode($data, true);
	}

	public function removeCourseEligibility($courseId){
		if(empty($courseId)){
			return;
		}
		$stringKey = $this->courseEligibilityPrefix.$courseId;
		return $this->_redis_client->deleteKey(array($stringKey));
	}

	public function storeCourseInterLinking($courseId,$sectionName,$data,$extraData = array()){
		if(empty($sectionName) || empty($courseId)){
			return;
		}
		$hashKey    = $this->courseInterLinkingPrefix.$courseId;
		$sectionName = (!empty($extraData['cityId'])) ? $sectionName.':'.$extraData['cityId'] : $sectionName;

		$finalData['data'] = $data;

		$hashMember = array($sectionName => json_encode($finalData));
		$expireInSeconds = 30 * 24 * 60 * 60; // 1 month

		$this->_redis_client->addMembersToHash($hashKey,$hashMember,FALSE);
		$this->_redis_client->expireKey($hashKey,$expireInSeconds);
	}

	public function getCourseInterLinking($courseId,$sectionName,$extraData = array()){
		if(empty($sectionName) || empty($courseId)){
			return;
		}
    	$hashKey    = $this->courseInterLinkingPrefix.$courseId;
    	$sectionName = (!empty($extraData['cityId'])) ? $sectionName.':'.$extraData['cityId'] : $sectionName;
    	$dataFromCache = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,array($sectionName));
    	foreach ($dataFromCache as &$value) {
			$value = json_decode($value,true);
		}
		return $dataFromCache;
	}

	public function removeCourseInterLinking($courseIds){
		$courseKeys = array();
		if(is_array($courseIds)){
			$courseKeys = array_map(function($a){return $this->courseInterLinkingPrefix.$a;},$courseIds);
		}
		else{
			$courseKeys[] = $this->courseInterLinkingPrefix.$courseIds;
		}
		$redisResponse = $this->_redis_client->deleteKey($courseKeys);
	}

	public function removeCourseInterLinkingBySection($courseId,$sectionName){
		$hashKey    = $this->courseInterLinkingPrefix.$courseId;
		$this->_redis_client->deleteMembersOfHash($hashKey,array($sectionName));
	}

	public function setExamCourseMapping($examCourseMapping) {
		if(empty($examCourseMapping)) {
			return;
		}

		$hashKey = "courses_mapped_to_exam";
		$expireInSeconds = 24 * 60 * 60; // 1 day

		foreach ($examCourseMapping as $examId => $value) {
            $examCourseMapping[$examId] = json_encode($value);
        }

        $this->cacheLib->addMembersToHash($hashKey, $examCourseMapping, $expireInSeconds);
	}

	public function getExamCourseMapping($examIds) {
		if(empty($examIds)) {
			return;
		}

		$hashKey = "courses_mapped_to_exam";

        $examCourseMappingFromCache = $this->cacheLib->getMembersOfHashByFieldNameWithValue($hashKey, $examIds);

        foreach ($examCourseMappingFromCache as $examId => $value) {
            $examCourseMappingFromCache[$examId] = json_decode($value, true);
        }

        return array_filter($examCourseMappingFromCache);
	}

	public function setBaseCourseToCourseMapping($baseCourseToCourseMapping) {
		if(empty($baseCourseToCourseMapping)) {
			return;
		}

		$hashKey = "courses_mapped_to_base_course";
		$expireInSeconds = 24 * 60 * 60; // 1 day

		foreach ($baseCourseToCourseMapping as $examId => $value) {
            $baseCourseToCourseMapping[$examId] = json_encode($value);
        }

        $this->cacheLib->addMembersToHash($hashKey, $baseCourseToCourseMapping, $expireInSeconds);
	}

	public function getBaseCourseToCourseMapping($baseCourseIds) {
		if(empty($baseCourseIds)) {
			return;
		}

		$hashKey = "courses_mapped_to_base_course";

        $baseCourseMappingFromCache = $this->cacheLib->getMembersOfHashByFieldNameWithValue($hashKey, $baseCourseIds);

        foreach ($baseCourseMappingFromCache as $examId => $value) {
            $baseCourseMappingFromCache[$examId] = json_decode($value, true);
        }

        return array_filter($baseCourseMappingFromCache);
	}

	function deleteCoursesCache($courseIds) {
		if(empty($courseIds))
			return;

		$courseKeys = array();
		// delete course cache and course location cache
		foreach ($courseIds as $courseId) {
			$courseKeys[] = $this->courseCacheKeyPrefix.$courseId;
			$courseKeys[] = $this->courseLocationCacheKeyPrefix.$courseId;
			$courseKeys[] = $this->courseInterLinkingPrefix.$courseId;
		}
		$redisResponse = $this->_redis_client->deleteKey($courseKeys);
		return $redisResponse;
	}
}

?>

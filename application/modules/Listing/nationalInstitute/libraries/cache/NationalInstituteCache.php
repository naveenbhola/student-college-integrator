<?php

class NationalInstituteCache
{
	
	private $instituteCacheKeyPrefix = "V2Inst:";
	private $instituteLocationCacheKeyPrefix = "V2InstLoc:";
	private $instituteCoursesCacheKeyPrefix = "InstCourses:";
	private $instituteTopCardCacheKeyPrefix ="InstTopCard:";
	private $instituteCoursesCacheTtl = 43200; //12 hrs

	function __construct()
	{
		$this->CI =& get_instance();
		$this->_redis_client = PredisLibrary::getInstance();
	}
	
	/*
	 * Institute
	 */ 
	public function getMultipleInstituteSections($instituteIds, $sections)
    {
    	$data = array();
    	$this->_redis_client->setPipeLine();
    	foreach ($instituteIds as $instituteId) {
    		$hashKey    = $this->instituteCacheKeyPrefix.$instituteId;
    		$this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$sections, TRUE);	
    	}
    	$data = $this->_redis_client->executePipeLine();

    	// $data = $this->_redis_client->executePipeLine();
    	$finalData = array();
    	foreach ($data as $key=>$instituteRow) {
			foreach ($instituteRow as $key1=>$instituteSection) {
				$finalData[$instituteIds[$key]][$sections[$key1]] =  json_decode($instituteSection, true);
			}
		}
		unset($data);

		return $finalData;
	}

	public function getInstituteSections($instituteId, $sections)
    {
    	$hashKey    = $this->instituteCacheKeyPrefix.$instituteId;
    	$dataFromCache = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$sections);

    	foreach ($dataFromCache as &$value) {
			$value = json_decode($value, true);
		}
		return $dataFromCache;
	}
	
	public function storeInstituteSection($instituteId, $sectionName, $sectionData)
	{
		$data       = json_encode($sectionData);
		$hashKey    = $this->instituteCacheKeyPrefix.$instituteId;
		$hashMember = array($sectionName => $data);

		$this->_redis_client->addMembersToHash($hashKey,$hashMember,FALSE);
	}

	public function getInstituteCourseWidget($instituteId){
		$stringKey 	= "courseWidgetData:".$instituteId;
		$courseWidgetData = $this->_redis_client->getMemberOfString($stringKey);
		return $courseWidgetData;
	}

	public function storeInstituteCourseWidget($instituteId, $courseWidgetData){
		if(empty($courseWidgetData))
			return;
		$stringKey 	= "courseWidgetData:".$instituteId;
		$this->_redis_client->addMemberToString($stringKey,json_encode($courseWidgetData));		
	}
	public function storeInstituteCourseWidgetNew($instituteId, $courseWidgetData){
		if(empty($courseWidgetData))
			return;
		$stringKey 	= "courseWidgetDataV2:".$instituteId;
		$this->_redis_client->addMemberToString($stringKey,json_encode($courseWidgetData));		
	}
	public function getInstituteCourseWidgetNew($instituteId){
		$stringKey 	= "courseWidgetDataV2:".$instituteId;
		$courseWidgetData = $this->_redis_client->getMemberOfString($stringKey);
		return $courseWidgetData;
	}
	public function invalidateCourseWidgetCache($instituteId){
		$stringKey 	= "courseWidgetDataV2:".$instituteId;
		$keysArray = array($stringKey);
		$this->_redis_client->deleteKey($keysArray,false);
	}
	public function storeInstituteBIPResponseCourse($instituteId,$data){
		if(empty($data))
			return;
		$stringKey 	= "InstBIPResponseCourse:".$instituteId;
		$this->_redis_client->addMemberToString($stringKey,json_encode($data));		
	}

	public function storeInstCourseCount($instituteId,$count){
		$stringKey 	= "InstCourseCountTotal";
		$this->_redis_client->addMembersToHash($stringKey,array($instituteId => $count));		
	}
	public function getInstCourseCount($listingId){
		
		if(empty($listingId)){
			return;
		}
		if(!is_array($listingId)) {
        $listingId = array($listingId);
        }
		$stringKey 	= "InstCourseCountTotal";
		$courseCount = $this->_redis_client->getMembersOfHashByFieldNameWithValue($stringKey, $listingId);
		return $courseCount;
	}

	public function invalidateInstituteBIPResponseCache($instituteId){
		
		$stringKey = "InstBIPResponseCourse:".$instituteId;
		$keysArray = array($stringKey);
		$this->_redis_client->deleteKey($keysArray,false);	
	}
	public function storeSanitizedBaseEntities($sanitizedBaseEntitiesData){
           if(empty($sanitizedBaseEntitiesData)){
                   return;
           }
           $stringKey = "sanitizedBaseEntities";
           $this->_redis_client->addMemberToString($stringKey,json_encode($sanitizedBaseEntitiesData));
   }
   public function getSanitizedBaseEntities(){
           $stringKey = "sanitizedBaseEntities";
           $sanitizedBaseEntitiesData = $this->_redis_client->getMemberOfString($stringKey);
           return $sanitizedBaseEntitiesData;
   }
   public function invalidateSanitizedBaseEntitiesCache(){
   		$stringKey = "sanitizedBaseEntities";
   		$keysArray = array($stringKey);
		$this->_redis_client->deleteKey($keysArray,false);
   }

   public function storeAdmissionCoursesData($instituteId,$admissionCoursesData){
   		if(empty($admissionCoursesData)){
                   return;
           }
           $stringKey = "admissionCoursesData:".$instituteId;
           
           $this->_redis_client->addMemberToString($stringKey,json_encode($admissionCoursesData));
   }
   public function invalidateAdmissionCoursesCache($instituteId){
		$stringKey 	= "admissionCoursesData:".$instituteId;
		$keysArray = array($stringKey);
		$this->_redis_client->deleteKey($keysArray,false);
	}
   
	public function storeInstituteLocations($instituteId, $sectionData)
	{
		if(empty($sectionData))
			return;
		$hashKey    = $this->instituteLocationCacheKeyPrefix.$instituteId;

		$this->_redis_client->addMembersToHash($hashKey,$sectionData,FALSE);
	}

	public function getInstituteLocations($instituteId, $locationIds = array())
    {
    	$hashKey    = $this->instituteLocationCacheKeyPrefix.$instituteId;

    	if(empty($locationIds))
    		$locations = $this->_redis_client->getAllMembersOfHashWithValue($hashKey);
    	else
    		$locations = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey, $locationIds);
  
    	foreach ($locations as &$value) {
    		$value = json_decode($value, true);
    	}

		return $locations;
	}

	public function getMultipleInstituteLocations($instituteIds, $locationIds = array()){

		$data = array();
		foreach ($instituteIds as $instituteId) {
			$data[$instituteId] = $this->getInstituteLocations($instituteId, $locationIds);

			// unset empty arrays
			foreach ($data[$instituteId] as $key => $value) {
				if(empty($value))
					unset($data[$instituteId][$key]);
			}
		}

		$data = array_filter($data);

		return $data;
	}

	public function getMultipleInstituteLocationsCache($instituteIds){

		$data = array();

		// get data from redis
		$this->_redis_client->setPipeLine();
		$reverseInstMapping = array();
		foreach ($instituteIds as $instId) {
			$hashKey   = $this->instituteLocationCacheKeyPrefix.$instId;
			$locations = $this->_redis_client->getAllMembersOfHashWithValue($hashKey, TRUE);
			$reverseInstMapping[] = $instId;

			foreach ($locations as &$value) {
	    		$value = json_decode($value, true);
	    	}
	    	$data[$instId] = $locations;
		}
		$data = $this->_redis_client->executePipeLine();

		// reverse mapping of locations
		$finalData = array();
		foreach ($data as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$value[$key1] = json_decode($value1, true);
			}
			$finalData[$reverseInstMapping[$key]] = $value;
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

	function removeInstitutesCache($instituteIds){

		if(empty($instituteIds))
			return;

		// delete institute cache and institute location cache
		foreach ($instituteIds as $instituteId) {
			$instituteKeys = array($this->instituteCacheKeyPrefix.$instituteId, $this->instituteLocationCacheKeyPrefix.$instituteId,$this->instituteTopCardCacheKeyPrefix.$instituteId);
			$this->_redis_client->deleteKey($instituteKeys);	
		}
        
        global $forceListingWriteHandle;
	    if($forceListingWriteHandle) {
			$instituteBuilder = new InstituteBuilder();
			$this->instituteRepo = $instituteBuilder->getInstituteRepository();
			$this->instituteRepo->disableCaching();

			
		    $this->instituteRepo->findMultiple($instituteIds);
		    $this->instituteRepo->enableCaching();
	    }
		
	}

	function fetchKeysBasedOnPattern($pattern){

		$keys = $this->_redis_client->fetchKeysBasedOnPattern($pattern);
		return $keys;
	}

	function deleteKeys($keys){

		if(empty($keys))
			return;

		return $this->_redis_client->deleteKey($keys);	
	}

	function checkIfAdmissionDetailsExists($listingId){

		$flag = $this->_redis_client->checkIfMemberOfSet("UnivWithAdmissionInfo",$listingId);
		return $flag;
	}
	/*store category links in redis for institute and university listings*/
	public function storeCategoryLinksForListings($listingId,$cLinks)
	{
		$stringKey 	= "catListingWid:".$listingId;
		$expireInSeconds = 30 * 24 * 60 * 60;//days * hours * minutes * seconds
		$data = $this->_redis_client->addMemberToString($stringKey,$cLinks,$expireInSeconds);
		return $data;
	}
	public function getCategoryLinksForListings($listingId)
	{
		$stringKey 	= "catListingWid:".$listingId;
		$cLinks = $this->_redis_client->getMemberOfString($stringKey);
		return $cLinks;
	}

	public function storeRankingLinksForListings($listingId,$data){
		if(empty($data)){
			return;
		}
		$stringKey 	= "V1rankingListingWid:".$listingId;

		$obj = array();
		
		$obj['data'] = $data;
		$obj['count'] = count($data);
		
		$expireInSeconds = 30 * 24 * 60 * 60; // 1 week
		
		$this->_redis_client->addMemberToString($stringKey,json_encode($obj),$expireInSeconds);
	}

	public function getRankingLinksForListings($listingId)
	{
		$stringKey 	= "V1rankingListingWid:".$listingId;
		$cLinks = $this->_redis_client->getMemberOfString($stringKey);

		$data = json_decode($cLinks,true);
		if(!empty($data)){
			$obj = new stdClass;
			$obj->data = $data['data'];
			$obj->count = $data['count'];
			return $obj;	
		}
		return null;

		
	}

	public function removeInterLinkingCache($type,$instituteIds){
		$keys = array();
		if($type == 'ranking'){
			if(is_array($instituteIds)){
				$keys = array_map(function($a){return "rankingListingWid:".$a;},$instituteIds);
			}
			else{
				$keys[] = "rankingListingWid:".$instituteIds;
			}
		}
		else if($type == 'category'){
			if(is_array($instituteIds)){
				$keys = array_map(function($a){return "catListingWid:".$a;},$instituteIds);
			}
			else{
				$keys[] = "catListingWid:".$instituteIds;
			}
		}
		$this->deleteKeys($keys);
	}

	public function getInstituteCourses($instituteIds)
    {
    	$keysArray = array();
    	foreach ($instituteIds as $id) {
    		$keysArray[] = $this->instituteCoursesCacheKeyPrefix.$id;
    	}
    	$data = $this->_redis_client->getMemberOfMultipleString($keysArray);

    	$returnData = array();
    	foreach ($instituteIds as $key => $id) {
    		if(!empty($data[$key])) {
    			$returnData[$id] = json_decode($data[$key], true);
    		}
    	}
    	return $returnData;
	}
	
	public function storeInstituteCourses($instituteCourses , $listingIdsToStore = array())
	{
		$hashMember = array();
		foreach ($instituteCourses as $instituteId => $data) {
			if(!empty($listingIdsToStore[$instituteId])){
				$key = $this->instituteCoursesCacheKeyPrefix.$instituteId;
				$this->_redis_client->addMemberToString($key,json_encode($data), $this->instituteCoursesCacheTtl);
			}
		}
		return true;
	}

	function removeInstituteCourses($institutesToBeUpdated) {
		$keys = array();
		foreach ($institutesToBeUpdated as $instituteId) {
			$keys[] = $this->instituteCoursesCacheKeyPrefix.$instituteId;
		}
		$this->deleteKeys($keys);
	}

	function getUniversityStreamBaseCourse($listingId){
		$stringKey = 'universityHierarchies#'.$listingId;
		$data = $this->_redis_client->getMemberOfString($stringKey);
		return json_decode($data,true);
	}
	function setUniversityStreamBaseCourse($listingId,$data){
		$stringKey = 'universityHierarchies#'.$listingId;
		$expireInSeconds = 60*60*6;
		$data = json_encode($data);
		$this->_redis_client->addMemberToString($stringKey,$data,$expireInSeconds);		
	}

	function setInstitutePaidStatus($data){
		$stringKey = 'institutePaidStatus';
		$this->_redis_client->addMembersToHash($stringKey, $data, FALSE);		
		$this->_redis_client->expireKey($stringKey, 24*60*60);
	}

	public function getInstitutePaidStatus($instituteIds)
    {
    	if(empty($instituteIds)){
    		return array();
    	}
    	if(!is_array($instituteIds)){
    		$instituteIds = array($instituteIds);
    	}
    	$hashKey   = 'institutePaidStatus';
    	$dataFromCache = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey, $instituteIds);
    	foreach ($dataFromCache as &$value) {
			$value = json_decode($value, true);
		}
		return $dataFromCache;
	}

}


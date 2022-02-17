<?php

class ListingBaseCache{
    
    private $predisLibrary;
    private $entity;

    function __construct() {
		$this->predisLibrary = PredisLibrary::getInstance();
	}

	function setEntity($entity){
		$this->entity = $entity;
	}

	function setData($data){
		$cacheData = array();
		foreach ($data as $key => $value) {
			$cacheData[$key] = json_encode($value);
		}
		// _p($this->entity);die;
		if(!empty($cacheData)){
			$this->predisLibrary->addMembersToHash($this->entity,$cacheData,true);
		}
	}

	function getMultipleData($ids = array()) {
		$data = array();
		if(BASE_ENTITIES_CACHE){
			$data = $this->predisLibrary->getMembersOfHashByFieldNameWithValue($this->entity,$ids);
		}
		$cacheData = array();
		foreach ($data as $key => $value) {
			$cacheData[$key] = json_decode($value,true);
		}
		return $cacheData;
	}

	function getAllBaseCourse($includeDummy)
	{
		$data = array();
		$key = 'allBaseCourse#'.$includeDummy;
		$data = $this->predisLibrary->getMemberOfString($key);
		if(!empty($data)){
			$data = json_decode($data,true);
		}
		return $data; 
	}

	function setAllBaseCourse($includeDummy,$data){
		$data = json_encode($data);
        $expireInSeconds = 30*24*60*60;
        $key = 'allBaseCourse#'.$includeDummy;
        $this->predisLibrary->addMemberToString($key,$data,$expireInSeconds,FALSE,FALSE);
	}

	function getSubstreamSpecializationByStreamId($ids = array()) {
		$data = array();
		$data = $this->predisLibrary->getMembersOfHashByFieldNameWithValue('substreamSpecializationByStreamId',$ids);

		$cacheData = array();
		foreach ($data as $key => $value) {
			$cacheData[$key] = json_decode($value,true);

		}
		return $cacheData;
	}

	function setSubstreamSpecializationByStreamId($data){
		$cacheData = array();
		foreach ($data as $key => $value) {
			$cacheData[$key] = json_encode($value);
		}
		if(!empty($cacheData)){
			$this->predisLibrary->addMembersToHash('substreamSpecializationByStreamId',$cacheData,false);
		}
	}


	function removeCacheForBaseEntities(){
		$this->predisLibrary->deleteKey(array('Hierarchies','Streams','Substreams','Specializations','BaseCourses' , 'allBaseCourse#0','allBaseCourse#1','substreamSpecializationByStreamId'));
	}
}
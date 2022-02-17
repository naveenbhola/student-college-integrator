<?php

class NationalCategoryListCache
{
	private $catPageInterLinkingCacheKeyPrefix = 'catRelatedLink:';
	private $catNonZeroCachePrefix = 'catNonZero:';
	private $clientCourseCachePrefix = 'clientCourses:';
	function __construct(){
		$this->_redis_client = PredisLibrary::getInstance();
	}
	
	public function storeClientCoursesCache($cacheKey, $courseIds) {
		$stringKey 	= $this->clientCourseCachePrefix.$cacheKey;
		$data = $this->_redis_client->addMemberToString($stringKey, $courseIds, 86400);
		return $data;
	}

	public function getClientCoursesCache($cacheKeys) {
		foreach ($cacheKeys as $key => $value) {
			$stringKeys[$key] = $this->clientCourseCachePrefix.$value;
		}
		$data = $this->_redis_client->getMemberOfMultipleString($stringKeys);
		
		foreach ($stringKeys as $key => $value) {
			$data[$key] = $data[$key]; //to have all string keys present in $data
		}
		return $data;
	}
	
	public function storeCategoryPageRelatedLinks($categoryPageId,$catRelatedLinks)
	{
		$stringKey 	= $this->catPageInterLinkingCacheKeyPrefix.$categoryPageId;
		$data = $this->_redis_client->addMemberToString($stringKey,$catRelatedLinks);
		return $data;
	}

	public function getCategoryPageRelatedLinks($categoryPageId)
	{
		$stringKey 	= $this->catPageInterLinkingCacheKeyPrefix.$categoryPageId;
		$catLinks = $this->_redis_client->getMemberOfString($stringKey);
		return $catLinks;
	}

	public function removeCategoryPageRelatedLinksCache($categoryPageIds){
		if(empty($categoryPageIds))
			return;

		$catPageInterLinkingKeys = array();
		foreach ($categoryPageIds as $categoryPageId) {
			$catPageInterLinkingKeys[] = $this->catPageInterLinkingCacheKeyPrefix.$categoryPageId;
		}
		$this->_redis_client->deleteKey($catPageInterLinkingKeys);
	}

	public function getNonZeroPageLinksCache($data){
		if(empty($data)){
			return;
		}
		$keys = array();
		foreach ($data as $value) {
			$stringKey = $this->catNonZeroCachePrefix.$value;
			$keys[] = $stringKey;
		}
		$cacheData = $this->_redis_client->getMemberOfMultipleString($keys);
		$returnData = array();
		foreach ($data as $idx => $key) {
			$returnData[$key] = $cacheData[$idx];
		}
		return $returnData;
	}

	public function storeNonZeroPageLinksCache($mappings){
		if(empty($mappings)){
			return;
		}
		$this->_redis_client->setPipeLine();
		foreach ($mappings as $key => $value) {
			$stringKey = $this->catNonZeroCachePrefix.$key;
			$this->_redis_client->addMemberToString($stringKey,$value,0,FALSE, TRUE);
		}
		$this->_redis_client->executePipeLine();
	}

	public function removeNonZeroPageLinksCache($data){
		if(empty($data)){
			return;
		}
		$keys = array();
		foreach ($data as $value) {
			$keys[] = $this->catNonZeroCachePrefix.$value;
		}
		$this->_redis_client->deleteKey($keys);
	}
}

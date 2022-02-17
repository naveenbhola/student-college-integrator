<?php
class CollegeReviewCache {
	private $instituteCacheKey = 'InstituteReviews';
	private $courseCacheKey = 'CourseReviews';
	private $aggregateReviewExpiry = 604800; //for 7 days

	function __construct() {
		$this->_redis_client = PredisLibrary::getInstance();
	}

	function storeAggregateReviewsForListingToCache($aggregateReviewData, $listingIds, $listingType) {
		if($listingType == 'institute' ||$listingType == 'university') {
			$cacheKeyPrefix = $this->instituteCacheKey;
		}
		else {
			$cacheKeyPrefix = $this->courseCacheKey;
		}
		
		foreach ($listingIds as $listingId) {
			$hashMember[$listingId] = json_encode($aggregateReviewData[$listingId]);
		}

		$this->_redis_client->addMembersToHash($cacheKeyPrefix,$hashMember,FALSE);
		$this->_redis_client->expireKey($cacheKeyPrefix, $this->aggregateReviewExpiry);
	}

	function getAggregateReviewsForListingFromCache($listingIds, $listingType) {
		if($listingType == 'institute' || $listingType == 'university') {
			$cacheKeyPrefix = $this->instituteCacheKey;
		}
		else {
			$cacheKeyPrefix = $this->courseCacheKey;
		}
    	
    	$data = array();

        $data = $this->_redis_client->getMembersOfHashByFieldNameWithValue($cacheKeyPrefix,$listingIds);

		foreach ($data as $key=>$reviewRows) {
			$data[$key] = json_decode($reviewRows,true);
		}
		return $data;
	}

}
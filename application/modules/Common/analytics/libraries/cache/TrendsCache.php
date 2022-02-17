<?php

/**
 * Class TrendsCache
 * Cache Library for shiksha analytics/trends home
 * @date    2017-09-25
 * @author  Romil Goel
 * @todo    none
 *
 */
class TrendsCache extends Cache
{
	 private $standardCacheTime = 604800; //1 week
	 private $monthCacheTime = 2592000; //1 month

	function __construct()
	{
		parent::__construct();
	}

	function getCachedStatesForUnivInstWidget($cache_key, $entity_type) {
		return $this->get($cache_key, $entity_type);
	}
	
	function putCachedStatesForUnivInstWidget($cache_key, $entity_type, $data) {
		$this->store($cache_key, $entity_type, json_encode($data), $this->standardCacheTime, NULL, 1);
	}
	
	function getCachedCourseLevels() {
		return $this->get("analytics_cons_course_levels", "crs_lvl");
	}
	
	function putCachedCourseLevels($data) {
		$this->store("analytics_cons_course_levels", "crs_lvl", json_encode($data), $this->monthCacheTime, NULL, 1);
	}

	function getCachedStates($listingType) {
		$raw_data = $this->get("analytics_cons_states_".$listingType, "states_list"); 
		return json_decode($raw_data, true);
	}
	
	function putCachedStates($listingType, $data) {
		$this->store("analytics_cons_states_".$listingType, "states_list", json_encode($data), $this->monthCacheTime, NULL, 1);
	}

	function getCachedQnA($cache_key) {

		$raw_data = $this->get($cache_key, "qna"); 
		return json_decode($raw_data, true);
	}
	
	function putCachedQnA($cache_key, $data) {
		$this->store($cache_key, "qna", json_encode($data), $this->standardCacheTime, NULL, 1);
	}

	function getCachedArticles($cache_key) {
		$raw_data = $this->get($cache_key, "art"); 
		return json_decode($raw_data, true);
	}
	
	function putCachedArticles($cache_key, $data) {
		$this->store($cache_key, "art", json_encode($data), $this->standardCacheTime, NULL, 1);
	}
}
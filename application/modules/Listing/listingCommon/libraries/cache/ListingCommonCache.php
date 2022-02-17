<?php 

class ListingCommonCache {
	function __construct() {
		$this->CI =& get_instance();
		$this->_redis_client = PredisLibrary::getInstance();
	}
	const TRENDING_SEARCH_CACHE_KEY = 'trendingSearches';
	var $trendingSearchSections = array('career', 'exam', 'collegeAndCourse');
	function storeTrendingSearches($sectionName, $sectionData) {
		$data       = serialize($sectionData);
		$hashKey    = self::TRENDING_SEARCH_CACHE_KEY;
		$hashMember = array($sectionName => $data);

		$this->_redis_client->addMembersToHash($hashKey,$hashMember,FALSE);
	}

	function getTrendingSearches($section) {
		$hashKey    = self::TRENDING_SEARCH_CACHE_KEY;
    	$dataFromCache = $this->_redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$this->trendingSearchSections);

    	foreach ($dataFromCache as &$value) {
			$value = unserialize($value);
		}
		if(in_array($section, $this->trendingSearchSections)) {
			return $dataFromCache[$section];
		}
		return $dataFromCache;
	}
}
<?php

class RankingPageCache extends Cache
{
    private $filterCacheKey = "RankingPageFilters";
    private $interlinkingCacheKey = "RankingPageInterlinking";
    private $cacheExpirationTime = 604800; // 1 Week Cache expiration for First Level Cache.
    private $filterCacheTTL =  6200; // 1 Week Cache expiration for First Level Cache.
    function __construct()
	{
		parent::__construct();
	}


	function getCategoryRelatedLinks(RankingPageRequest $request)
    {
		$key  = $request->getPageKey();
		$data = unserialize($this->get('CAT_LINKS_RANKINGPAGE_', $key));
		return $data;
    }
    
    function storeCategoryRelatedLinks(RankingPageRequest $request,$data)
    {
		$cacheKey = $request->getPageKey();
		$data = serialize($data);
		$this->store('CAT_LINKS_RANKINGPAGE_',$cacheKey,$data,86400,NULL,1); // 1 day cache
    }

    function deleteCategoryRelatedLinks(RankingPageRequest $request){
        $cacheKey = $request->getPageKey();
        $this->delete('CAT_LINKS_RANKINGPAGE_',$cacheKey);
    }
    
    function getAverageReviewRatingsForRankingPage($rankingPageId){
            $data = $this->get('RANKING_PAGE_AVERAGE_REVIEW_RATING',$rankingPageId);
            if(empty($data)){
                return array();
            }
            return unserialize($data);
    }
    function storeAverageReviewRatingsForRankingPage($overAllAverageRatingPerCourse,$rankingPageId,$ttl){
        $data = serialize($overAllAverageRatingPerCourse);
        $this->store('RANKING_PAGE_AVERAGE_REVIEW_RATING',$rankingPageId,$data,$ttl,NULL,1);
    }

    function deleteAverageReviewRatingsForRankingPage($rankingPageId){
        $this->delete('RANKING_PAGE_AVERAGE_REVIEW_RATING',$rankingPageId);
    }
    
    function getMultipleInstitutesNaukriData($instituteIds){
    	$naukriData =  $this->multiGet('NAUKRI_DATA_RANKING_',$instituteIds);
    	$naukriData = array_map('unserialize', array_map('gzuncompress',$naukriData));
    	if(empty($naukriData)){
    		return array();
    	}
    	return $naukriData;
    }

    function storeInstituteNaukriData($instituteId,$data){
    	$naukriData = gzcompress(serialize($data), 9);
    	$this->store('NAUKRI_DATA_RANKING_', $instituteId, $naukriData,-1, NULL, 1);
    }

    function deleteInstituteNaukriData($instituteId){
        $this->delete('NAUKRI_DATA_RANKING_',$instituteId);
    }

    function getRankingPages($id,$status){
        $data = unserialize($this->get('RANKING_PAGE_BY_ID_',$id.$status));
        if(empty($data)){
            return array();
        }
        return $data;
    }

    function storeRankingPages($id,$status,$data){
        $this->store('RANKING_PAGE_BY_ID_',$id.$status,serialize($data),86400,NULL,1);
    }

    function deleteRankingPages($id,$status=''){
        if(empty($status)){
            $this->delete('RANKING_PAGE_BY_ID_',$id.'live');
            $this->delete('RANKING_PAGE_BY_ID_',$id.'draft');
        }
        else{
            $this->delete('RANKING_PAGE_BY_ID_',$id.$status);
        }
    }

    function getRankingPageUrl($id){
        $data = $this->get('RANKING_URL_BY_ID_',$id);
        if(empty($data)){
            return '';
        }
        return $data;
    }

    function storeRankingPageUrl($url,$id){
        $this->store('RANKING_URL_BY_ID_',$id,$url,86400,NULL,1);
    }

    function deleteRankingPageUrl($id){
        $this->delete('RANKING_URL_BY_ID_',$id);
    }

    function getRankingPageObject($id, $status) {
        $rankingData = unserialize(gzuncompress($this->get('RANKING_PAGE_OBJECT_', $id . $status)));
        if (empty($rankingData)) {
            return array();
        }
        else {
            return $rankingData;
        }
    }

    function storeRankingPageObject($id,$status,$data){
        $this->store('RANKING_PAGE_OBJECT_',$id.$status,gzcompress(serialize($data)),86400,NULL,1);
    }

    function deleteRankingPageObject($id,$status=''){
        if(empty($status)){
            $this->delete('RANKING_PAGE_OBJECT_',$id.'live');
            $this->delete('RANKING_PAGE_OBJECT_',$id.'draft');
        }
        else{
            $this->delete('RANKING_PAGE_OBJECT_',$id.$status);
        }
    }

    function getRankingPageFilters($rankingPageId, $identifier){
        $hashKey = $this->filterCacheKey.':'.$rankingPageId;
        $hashMember = array($identifier);
        $dataFromCache = $this->getMembersOfHashByFieldNameWithValue($hashKey,'', $hashMember);
        foreach ($dataFromCache as &$value) {
            $value = unserialize($value);
        }
        return $dataFromCache[$identifier];
    }
    function storeRankingPageFilters($rankingPageId,$identifier, $filters){
        $hashKey = $this->filterCacheKey.':'.$rankingPageId;
        $hashMember = array($identifier => serialize($filters));
        $this->addMembersToHash($hashKey, '', $hashMember, -1);
    }

    function deleteRankingPageFilters($rankingPageId){
        $hashKey = $this->filterCacheKey.':'.$rankingPageId;
        $res = $this->delete($hashKey);
    }

    function getStreamWiseRankingPageUrl() {
    	$data = unserialize($this->get('Hamburger', 'RankingPageLinks'));
		return $data;
    }

    function storeStreamWiseRankingPageUrl($data) {
    	$data = serialize($data);
		$this->store('Hamburger', 'RankingPageLinks', $data, -1, NULL, 1); //infinite cache
    }

    function deleteStreamWiseRankingPageUrl() {
    	$this->delete('Hamburger', 'RankingPageLinks');
    }

    function getRankingPageInterlinkingData($rankingPageId, $identifier, $type){
        $hashKey = $this->interlinkingCacheKey.':'.$rankingPageId;
        $hashMember = array($identifier.$type);
        $dataFromCache = $this->getMembersOfHashByFieldNameWithValue($hashKey,'', $hashMember);
        foreach ($dataFromCache as &$value) {
            $value = unserialize($value);
        }
        return $dataFromCache[$identifier.$type];
    }
    function storeRankingPageInterlinkingData($rankingPageId,$identifier, $type, $filters){
        $hashKey = $this->interlinkingCacheKey.':'.$rankingPageId;
        $hashMember = array($identifier.$type => serialize($filters));
        $this->addMembersToHash($hashKey, '', $hashMember, -1);
    }

    function deleteRankingPageInterlinkingData($rankingPageId){
        $hashKey = $this->interlinkingCacheKey.':'.$rankingPageId;
        $res = $this->delete($hashKey);
    }

} ?>
    

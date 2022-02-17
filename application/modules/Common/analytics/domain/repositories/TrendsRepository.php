<?php

class TrendsRepository extends EntityRepository{

	function __construct($dao = NULL,$cache = NULL, $model = NULL, $locationRepository)
    {
        parent::__construct($dao,$cache);
        
        $this->dao                = $dao;
        $this->cache              = $cache;
        $this->model              = $model;
        $this->locationRepository = $locationRepository;
    }

    function getTesterData() {

        return $this->dao->getTestDataElastic();
    }
    function getTrendsOverallMetrics(){

    	// if cache present, then return data from cache
    	

    	// get directly from dao
    	$data = $this->dao->getTrendsOverallMetrics();

    	return $data;
    }

    function getPopularUniversities($location, $ownership, $pageNumber=1){

    	// if cache present, then return data from cache
    	
    	
    	// get directly from dao
    	$data = $this->dao->getPopularUniversities($location, $ownership, $pageNumber);

        $validStateIdsForFilters = $data['statesForFilter'];

        $data['locations']      = $this->getStateList('university', $validStateIdsForFilters);

        $data['ownership_list'] = array("private" => "Private", "public" => "Public/Government", "partnership" => "Public-Private");
    

    	return $data;
    }

    function getPopularInstitutesData($location, $stream, $pageNumber=1, $entityName = null, $entityId = null){

    	// if cache present, then return data from cache
    	
    	
    	// get directly from dao
    	$data = $this->dao->getPopularInstitutesData($location, $stream, $pageNumber, $entityName, $entityId);

        $validStateIdsForFilters = $data['statesForFilter'];

        $data['locations'] = $this->getStateList('institute', $validStateIdsForFilters);

    	return $data;
    }

    function getPopularCoursesData($levels, $credentials, $pageNumber=1, $entityType=null, $entityId=null){

        // if cache present, then return data from cache
        
        
        // get directly from dao
        $data = $this->dao->getPopularCoursesData($levels, $credentials, $pageNumber, $entityType, $entityId);

        return $data;
    }

    function getPopularExamsData($stream, $pageNumber=1, $entityType=null, $entityId=null){

        // if cache present, then return data from cache
        
        // get directly from dao
        $data = $this->dao->getPopularExamsData($stream, $pageNumber);
        $data['streams'] = $this->getStreamList($data['filterStreamIds']);

        return $data;
    }

    function getPopularSpecializationsData($stream, $pageNumber=1, $entityType=null, $entityId=null){

        // if cache present, then return data from cache
        
        
        // get directly from dao
        $data = $this->dao->getPopularSpecializationsData($stream, $pageNumber, $entityType, $entityId);

        $data['streams'] = $this->getStreamList($data['filterStreamIds']);

        return $data;
    }

    function getPopularQuestionsData($entityType, $entityId){

        // if cache present, then return data from cache
        
        
        // get directly from dao
        $data = $this->dao->getPopularQuestionsData($entityType, $entityId);

        return $data;
    }

    function getPopularArticlesData($entityType, $entityId){

        // if cache present, then return data from cache
        
        
        // get directly from dao
        $data = $this->dao->getPopularArticlesData($entityType, $entityId);

        return $data;
    }

    function getInterestByTimeData($entityType, $entityId){

        // get directly from dao
        $data = $this->dao->getInterestByTimeData($entityType, $entityId);

        return $data;
    }

    function getInterestByRegion($entityType, $entityId, $pageNumber=1){

        // get directly from dao
        $data = $this->dao->getInterestByRegion($entityType, $entityId, $pageNumber);

        return $data;
    }

    function getPopularityIndex($entityType, $entityId) {
        $data = $this->dao->getPopularityIndex($entityType, $entityId);
        return $data;
    }

    private function getStateList($listingtype="university", $validStateIdsForFilters){
        
        $statesList = array();
        $finalStatesList = array();
        $statesList = $this->model->getAllStatesForListing($listingtype);
        foreach($statesList as $state_id => $state_name) {
            if(!in_array($state_id, $validStateIdsForFilters)) {
                unset($statesList[$state_id]);
            }
        }
        return $statesList;
    }

    private function getStreamList($streamIdsForFilter = null){
        $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
        $listingBaseBuilder   = new ListingBaseBuilder();
        $streamRepoObj = $listingBaseBuilder->getStreamRepository();
        $allStreams = $streamRepoObj->getAllStreams();
        $streamList = array();
        foreach ($allStreams as $key => $value) {
            if(!$streamIdsForFilter || ($streamIdsForFilter && in_array($value['id'], $streamIdsForFilter))) {
                $streamList[$value['id']] = $value['name'];
            }
        }

        return $streamList;
    }

}

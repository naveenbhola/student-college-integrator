<?php

class ConsultantRepository extends EntityRepository {
    protected $dao;                           // data access object
    private $consultantLocationRepo; 	    // consultant location repository
    private $consultantStudentProfileRepo;  // consultant student profile repository
    private $consultantCache;
    protected $cache;
    
    public function __construct($dao, $consultantLocationRepo, $consultantStudentProfileRepo,$cache)
    {
        parent::__construct();
        $this->CI = & get_instance();
        if(!empty($consultantLocationRepo) &&
           !empty($consultantStudentProfileRepo) &&
           !empty($cache)
           )
        {
            $this->dao                          = $dao;
            $this->consultantLocationRepo       = $consultantLocationRepo;
            $this->consultantStudentProfileRepo = $consultantStudentProfileRepo;
            $this->consultantCache              = $cache;
            $this->cache                        = true;
        }
    }

    /*
     * function to get data for a consultant & return its object
     * params: consultant id 
     * return val : object of consultant entity
     */
    public function findMultiple($consultantIds = NULL, $dataTobeSkipped)
    {
        if(is_null($consultantIds))
        {
            return false;
        }
        else if(!is_array($consultantIds) && is_numeric($consultantIds))
        {
            $consultantIds = array($consultantIds);
        }
        // save a copy the requested list of ids
        $totalConsultantIds = $consultantIds;
        // we search objects for this in cache & get them
        if($this->cache) { 
            $cachedConsultants = $this->consultantCache->getMultipleConsultants($consultantIds);
            $foundInCache = array_keys($cachedConsultants);
			$consultantIds = array_diff($consultantIds,$foundInCache);
        }
        // if any of the requested objects were not vailable in cache, get them from db
        if(count($consultantIds)>0){
            // get consultant data from db 
            $consultantData = $this->dao->getConsultantData($consultantIds);
            // create objects of consultants with this data ...
            $dbConsultants = $this->_populateConsultantObject($consultantData);
            foreach($dbConsultants as $id=>$consultantSingleData){
                $this->consultantCache->storeConsultant($id,$consultantSingleData);
            }
        }
        // combine results from both cache & db
        $consultants = array();
        foreach($totalConsultantIds as $consultantId) {
			if(isset($cachedConsultants[$consultantId])) {
				$consultants[$consultantId] = $cachedConsultants[$consultantId];
			}
			else if(isset($dbConsultants[$consultantId])) {
				$consultants[$consultantId] = $dbConsultants[$consultantId];
			}
		}
        // ... along with objects of ConsultantLocation & ConsultantStudentProfile
        if($dataTobeSkipped['skipLocation'] !== TRUE){
            $consultantLocations = $this->consultantLocationRepo->find($totalConsultantIds);
            foreach($consultantLocations as $key=>$consultantLocationArr)
            {
                $consultants[$key]->__set('consultantLocations',$consultantLocationArr);
            }
        }
        if($dataTobeSkipped['skipProfile'] !== TRUE){
            $consultantStudentProfiles = $this->consultantStudentProfileRepo->find($totalConsultantIds, TRUE);
            foreach($consultantStudentProfiles as $key=>$consultantStudentProfileArr)
            {
                $consultants[$key]->__set('consultantStudentProfiles',$consultantStudentProfileArr);
            }
        }
        return $consultants;
    }
    /*
     * function to create an object of Consultant entity based on values fetched from db
     * params : consultant details fetched from db
     * return val : array of objects of Consultant entity
     */
    private function _populateConsultantObject($data)
    {
        $consultantObjects =array();
        foreach($data as $key => $consultantDetails)
        {
            if(empty($consultantDetails))
            {
                continue;
            }
            // initialize a consultant object
            $consultantObject = new Consultant();
            // fill it with values
            $this->fillObjectWithData($consultantObject, $consultantDetails);
            // push it into array of objects
            $consultantObjects[$consultantDetails['consultantId']]= $consultantObject;
        }
        //_p($consultantObjects);
        return $consultantObjects;
    }
    /*
     * works same as findMultiple but with a single consultant Id
     */
    public function find($consultantId = NULL)
    {
        if(is_null($consultantId))
        {
            return false;
        }
        else if(!is_array($consultantId) && is_numeric($consultantId))
        {
            $consultantId = array($consultantId);
        }
        return $this->findMultiple($consultantId);
    }
    
    public function getConsultantSubscriptionDetails($consultantId) {
        
        if($consultantId == "" || !is_numeric($consultantId)) {
            return false;
        }
        
        $subscriptionDetails = $this->dao->getConsultantSubscriptionDetails($consultantId);
        
        return $subscriptionDetails;        
    }
    
    public function disableCaching(){
		$this->cache = false;
	}

}

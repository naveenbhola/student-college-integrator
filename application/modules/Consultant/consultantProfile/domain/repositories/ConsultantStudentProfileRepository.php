<?php

class ConsultantStudentProfileRepository extends EntityRepository {
	
	protected $dao; 	// university repository
	private $consultantStudentProfileCache;
	protected $cache;

	public function __construct($dao,$consultantStudentProfileCache) {
		parent::__construct();
		$this->CI = & get_instance();
		if(!empty($dao) && !empty($consultantStudentProfileCache))
		{
			$this->dao = $dao;
			$this->consultantStudentProfileCache = $consultantStudentProfileCache;
			$this->cache                         = true;
		}
	}
	
	/*
	 * function to search & get all student profiles of a consultant, or among a list of student profiles
	 * params: consultant id (single numeric id / array of ids) or consultant student profile ids
	 * return val : array of objects of Class ConsultantStudentProfile indexed at respective consultant Ids
	 */
	public function findMultiple($entityId = NULL, $findByConsultant = FALSE)
	{
		if(is_null($entityId))
		{
			return false;
		}
		else if(!is_array($entityId) && is_numeric($entityId))
		{
			$entityId = array($entityId);
		}
		
		if($this->cache && $cachedStudentData = $this->consultantStudentProfileCache->getMultipleConsultantsStudentProfile($entityId))
		{
		       $consultantStudentProfiles = $cachedStudentData;
		}else{
			// get Student profiles from db...
			$consultantStudentProfileData = $this->dao->getConsultantStudentProfileData($entityId,$findByConsultant);
			// ... as ConsultantStudentProfile objects
			$consultantStudentProfiles= $this->_populateConsultantStudentProfileObject($consultantStudentProfileData);
			foreach($consultantStudentProfiles as $consultantId=>$consultantStudentTupleData){
					$this->consultantStudentProfileCache->storeConsultantStudentProfile($consultantId,$consultantStudentTupleData);
				}
			}
		return $consultantStudentProfiles;
	}
	/*
	 * function to create an object of ConsultantStudentProfile entity based on values fetched from db
	 * params : ConsultantStudentProfile details fetched from db
	 * return val : array of objects of ConsultantStudentProfile Entity
	 */
	private function _populateConsultantStudentProfileObject($data = array())
	{
		$consultantStudentProfileObjects =array();
		foreach($data as $key => $consultantStudentProfileDetails)
		{
		    foreach($consultantStudentProfileDetails as $k => $consultantStudentProfileDetail){
			if(empty($consultantStudentProfileDetail))
			{
				continue;
			}
			// initialize a consultant Student profile object
			$consultantStudentProfileObject = new ConsultantStudentProfile();
			// fill it with values
			$this->fillObjectWithData($consultantStudentProfileObject, $consultantStudentProfileDetail);
			// push it into array of objects
			if(!is_array($consultantStudentProfileObjects[$consultantStudentProfileDetail['consultantId']])){
				$consultantStudentProfileObjects[$consultantStudentProfileDetail['consultantId']] = array($consultantStudentProfileDetail['profileId']=>$consultantStudentProfileObject);
			}
			else{
				$consultantStudentProfileObjects[$consultantStudentProfileDetail['consultantId']][$consultantStudentProfileDetail['profileId']] = $consultantStudentProfileObject;
			}
		    }
		}
		//_p($consultantStudentProfileObjects);
		return $consultantStudentProfileObjects;
	}
	/*
	 * works same as findMultiple but with single id
	 */
	public function find($entityId = NULL, $findByConsultant = FALSE)
	{
		if(is_null($entityId))
		{
			return false;
		}
		else if(!is_array($entityId) && is_numeric($entityId))
		{
			$entityId = array($entityId);
		}
		return $this->findMultiple($entityId, $findByConsultant);
	}
	
	public function disableCaching(){
		$this->cache = false;
	}
}

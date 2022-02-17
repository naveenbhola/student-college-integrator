<?php

class ConsultantLocationRepository extends EntityRepository {
	
	protected $dao; 	// data access object
	private   $consultantLocationCache;
	protected $cache;
	
	public function __construct($dao,$cache) {
		parent::__construct();
		$this->CI = & get_instance();
		if(!empty($dao) && !empty($cache))
		{
			$this->dao = $dao;
			$this->consultantLocationCache    = $cache;
			$this->cache                        = true;
		}
	}
	
	/*
	 * function to get a consultant location or get all locations of a consultant
	 * params: consultant location id/ consultant id (single numeric id / array of ids), boolean to identify if we are looking for all locations of a consultant
	 * return val : array of objects of Class ConsultantLocation indexed at respective consultant Ids/ consulatnt location ids
	 */
	public function findMultiple($consultantId = NULL){
		if(is_null($consultantId))
		{
			return false;
		}
		else if(!is_array($consultantId) && is_numeric($consultantId))
		{
			$consultantId = array($consultantId);
		}
		
		if($this->cache && $cachedLocationData = $this->consultantLocationCache->getMultipleConsultantsLocation($consultantId))
		{
		       $consultantLocationObjects = $cachedLocationData;
		}else{
		    // get  locations from db ...
			$consultantLocationData = $this->dao->getConsultantLocationData($consultantId, TRUE);
			$consultantLocationObjects = $this->_populateConsultantLocationObject($consultantLocationData);
			foreach($consultantLocationObjects as $consultantId=>$consultantLocationTupleData){
				$this->consultantLocationCache->storeConsultantLocation($consultantId,$consultantLocationTupleData);
			}
		} 

		return $consultantLocationObjects;
	}
	/*
	 * function to find particular set of locations for a given consultant Id
	 */
	public function findMultipleLocations($consultantLocationIds = NULL)
	{
		if(count($consultantLocationIds)>0)
		{
				$consultantLocations = $this->findMultiple(array_keys($consultantLocationIds));
		}
		if($consultantLocations === false)
		{
				return false;
		}
		else{
				$consultantLocationObjects = array();
				// get required consultant locations from all locations
				foreach($consultantLocationIds as $consultantId => $consultantLocIds)
				{
						if(is_null($consultantLocationObjects[$consultantId])){
								$consultantLocationObjects[$consultantId] =array();
						}
						foreach($consultantLocIds as $consultantLocationId){
								
								$consultantLocationObjects[$consultantId][$consultantLocationId] = $consultantLocations[$consultantId][$consultantLocationId];
						}
				}
		}
//		_p($consultantLocationObjects);die;
		return $consultantLocationObjects;
	}
	
	/*
	 * function to create an object of ConsultantLocation entity based on values fetched from db
	 * params : ConsultantLocation details fetched from db
	 * return val : array of objects of ConsultantLocation Entity
	 */
	private function _populateConsultantLocationObject($data = array()){
		$consultantLocationObjects =array();
		foreach($data as $key => $consultantLocationDetails)
		{
		    foreach($consultantLocationDetails as $k => $consultantLocationDetail){
			if(empty($consultantLocationDetail))
			{
				continue;
			}
			// initialize a consultant object
			$consultantLocationObject = new ConsultantLocation();
			// fill it with values
			$this->fillObjectWithData($consultantLocationObject, $consultantLocationDetail);
			// push it into array of objects
			if(!is_array($consultantLocationObjects[$consultantLocationDetail['consultantId']])){
				$consultantLocationObjects[$consultantLocationDetail['consultantId']] = array($consultantLocationDetail['consultantLocationId']=>$consultantLocationObject);
			}
			else{
				$consultantLocationObjects[$consultantLocationDetail['consultantId']][$consultantLocationDetail['consultantLocationId']] = $consultantLocationObject;
			}
		    }
		}
		//_p($consultantLocationObjects);
		return $consultantLocationObjects;
	}
	/*
	 * works same as find multiple but for a single id
	 */
	public function find($entityId = NULL){
		if(is_null($entityId))
		{
			return false;
		}
		else if(!is_array($entityId) && is_numeric($entityId))
		{
			$entityId = array($entityId);
		}
		return $this->findMultiple($entityId);
	}
	
	public function disableCaching(){
		$this->cache = false;
	}
}

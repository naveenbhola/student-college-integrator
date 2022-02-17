<?php

class AbroadInstituteRepository extends EntityRepository
{
	
	private $nationalInstRepo;
	
	function __construct($dao,$cache)
	{
		parent::__construct($dao,$cache);
		
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('AbroadInstitute','InstituteLocation','ContactDetail'),'listing');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
		
		// $this->CI->load->entities(array('Institute','InstituteLocation','HeaderImage','Ranking','ListingMedia','AlumaniFeedback','InstituteDescriptionAttribute','InstituteJoinReason','ContactDetail','ListingViewCount'),'listing');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
	}
		
	/*
	 * Find an institute using institute id
	 */
	public function find($instituteId){
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		if($this->caching && $cachedInstitute = $this->cache->getInstitute($instituteId)) {
			if($cachedInstitute instanceof AbroadInstitute){
				return $cachedInstitute;
			}
			return false;
		}
        $resultantInstitute = $this->dao->checkIfInstituteIdBelongsToAbroad($instituteId);
		if($resultantInstitute['institute_id'] >0) {
			if(in_array($resultantInstitute['institute_type'],array('Department','Department_Virtual')) === false)
			{
				return false;
				// $this->_loadNationalInstituteRepo();
				// return $this->nationalInstRepo->find($instituteId);
			}
			else{  
				$instituteDataResults = $this->dao->getData($instituteId);
				$institute = $this->_loadOne($instituteDataResults);
				$this->cache->storeInstitute($institute);
				return $institute;
			}
		}
		else{
			show_404_abroad();
			return false;
		}
	}

	/*
	 * Find multiple institutes using institute ids
	 */
	public function findMultiple($instituteIds){
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		$orderOfInstituteIds = $instituteIds;
		$institutesFromCache = array();
		if($this->caching) {
			$institutesFromCache = $this->cache->getMultipleInstitutes($instituteIds);
			$institutesFromCache = array_filter($institutesFromCache,function($ele){ if($ele instanceof AbroadInstitute) return true; return false;});
			$foundInCache = array_keys($institutesFromCache);
			$instituteIds = array_diff($instituteIds,$foundInCache);
		}

		$abroadInstituteIds = $this->dao->fetchDiffOfValidAbroadInstituteIds($instituteIds);
		$nationalInstituteIds = array_diff($instituteIds,$abroadInstituteIds);
		
		// $instituteFromNationalRepo = array();
		// if(count($nationalInstituteIds) > 0) {
		// 	$this->_loadNationalInstituteRepo();
		// 	$instituteFromNationalRepo = $this->nationalInstRepo->findMultiple($nationalInstituteIds);
		// }
		
		$instituteIds = $abroadInstituteIds;
		
		if(count($instituteIds) > 0) {
			$instituteDataResults = $this->dao->getDataForMultipleInstitutes($instituteIds);
			$institutesFromDB = $this->_load($instituteDataResults);
			foreach($institutesFromDB as $institute) {
				$this->cache->storeInstitute($institute);
			}
		}

		$institutes = array();
		foreach($orderOfInstituteIds as $instituteId) {
			if(isset($institutesFromCache[$instituteId])) {
				$institutes[$instituteId] = $institutesFromCache[$instituteId];
			}
			else if(isset($institutesFromDB[$instituteId])) {
				$institutes[$instituteId] = $institutesFromDB[$instituteId];
			}
			// else if(isset($instituteFromNationalRepo[$instituteId])) {
			// 	$institutes[$instituteId] = $instituteFromNationalRepo[$instituteId];
			// }
		}
		return $institutes;
	}

	
	/***** LOAD ABROAD INST REPO ***/
	
	private function _loadNationalInstituteRepo(){
		if(empty($this->nationalInstRepo)) {
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder 			= new ListingBuilder;
			$this->nationalInstRepo = $listingBuilder->getInstituteRepository();
		}
	}
	
	/*
	 * ORM Functions
	 * Create domain objects from Db resultsets
	 */
	private function _loadOne($results)
	{
		$institutes = $this->_load(array($results));
		return current($institutes);
	}
	
	// handles multiple institute objects
	private function _load($results)
	{
		$institutes = array();

		if(is_array($results) && count($results)) {
			foreach($results as $instituteId => $result) {
				$institute = $this->_createInstitute($result);
				$this->_loadChildren($institute,$result);
				$institutes[$instituteId] = $institute;
			}
		}

		return $institutes;
	}
	
	// create Institute object
	private function _createInstitute($result)
	{
		$institute = new AbroadInstitute;
		$instituteData = (array) $result['general'] + (array) $result['view_count'];
		$this->fillObjectWithData($institute,$instituteData);
		return $institute;
	}
	
	// loads all the children of institute
	private function _loadChildren($institute,$result)
	{
		//$children = array('locations','header_images','ranking','media');
		$children = array('locations');
		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
				foreach($result[$child] as $childResult) {
					$this->_loadChild($child,$institute,$childResult);
				}
			}
			else {
				/*
				 * Load empty child
				 */
				$this->_loadChild($child,$institute);
			}
		}
	}
	
	// load indivisual child of an institute
	private function _loadChild($child,$institute,$childResult = NULL)
	{
		switch($child) {
			case 'locations':
				$this->_loadLocation($institute,$childResult);
				break;
		}
	}
	
	// creates location object
	private function _loadLocation($institute,$result = NULL)
	{
		$location = $this->_createLocation($result);
		$institute->addLocation($location);
	}
	
	// populates institute location and it's children
	private function _createLocation($result)
	{
		$result['entities'] = array();

        $region = new Region;
		$this->fillObjectWithData($region,$result);
		$result['entities']['region'] = $region;

        $country = new Country;
		$this->fillObjectWithData($country,$result);
		$result['entities']['country'] = $country;

		$state = new State;
		$this->fillObjectWithData($state,$result);
		$result['entities']['state'] = $state;

		$city = new City;
		$this->fillObjectWithData($city,$result);
		$result['entities']['city'] = $city;

		$zone = new Zone;
		$this->fillObjectWithData($zone,$result);
		$result['entities']['zone'] = $zone;

		$locality = new Locality;
		$this->fillObjectWithData($locality,$result);
		$result['entities']['locality'] = $locality;

		$contact_detail = new ContactDetail();
		$this->fillObjectWithData($contact_detail,$result);
		$result['entities']['contact_detail'] = $contact_detail;

		$location = new InstituteLocation;
		$this->fillObjectWithData($location,$result);

		return $location;
	}
	
	/*
	 * Find institutes with given courses
	* Accepts array <Institute Id> => <Array of course ids>
	* e.g.
	* [12 => array(45,876)]
	* [56 => array(89,65)]
	*/
	public function findWithCourses($institutesWithCourses = array())
	{
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository(); 
		
		/*
		 * Extract institute ids and courses ids
		* So that these can be used in findMultiple()
		*/
		$instituteIds = array();
		$courseIds = array();
		
		foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
					$instituteIds[] = $instituteId;
			$instituteCourseIds = (array) $instituteCourseIds;
			foreach($instituteCourseIds as $instituteCourseId) {
				$courseIds[] = $instituteCourseId;
			}
		}
			
		if(count($instituteIds) > 0 && count($courseIds) > 0) {
			$institutes = $this->findMultiple($instituteIds);
			$courses = $abroadCourseRepository->findMultiple($courseIds);
	       	foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
				$instituteCourseIds = (array) $instituteCourseIds;
				foreach($instituteCourseIds as $instituteCourseId) {
					if(isset($institutes[$instituteId])) {
						$institutes[$instituteId]->addCourse($courses[$instituteCourseId]);
					}
				}
			}
	
			return $institutes;
		}
	}
	
	public function getLiveAbroadInstitutes()
	{
		return $this->dao->getLiveAbroadInstitutes();
	}
	
}

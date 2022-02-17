<?php

class NationalInstituteRepositoryAbstract extends EntityRepository{

	function __construct($dao = NULL,$cache = NULL)
    {
        parent::__construct($dao,$cache);
        
        $this->dao         = $dao;
        $this->cache       = $cache;
    }

    protected function getMultipleInstituteFromCache($instituteIds, $sections){

		$instituteObjects = array();
		$dataFromCache = $this->cache->getMultipleInstituteSections($instituteIds, $sections);
		
		// get locations from cache
		$instituteLocations = array();
		if(in_array('location', $sections)){
			// foreach ($instituteIds as $instId) {
			// 	$instituteLocations[$instId] = $this->cache->getInstituteLocations($instId);
			// }
			$instituteLocations = $this->cache->getMultipleInstituteLocationsCache($instituteIds);
		}
		foreach ($dataFromCache as $instituteId=>$instituteData) {
			$basicSection = reset($instituteData['basic']);
			if(empty($instituteData['basic']) || !$basicSection['listing_id']) 
                continue;
	 	                            
			// fill location data
			$instituteData['location'] = $instituteLocations[$instituteId];
			// $instituteObjects[$instituteId] = $this->_populateInstituteObjFromData($instituteId, $instituteData, $sections);
			if($instituteObj)
				$institute = $instituteObj;
			else
				$institute = $this->_createInstitute();
			$this->_loadChildren($instituteId, $institute,$instituteData, false, $sections);
			$instituteObjects[$instituteId] = $institute;
		}
		// unset($instituteLocations);
		return $instituteObjects;
	}

	protected function getInstituteFromCache($instituteId, $sections){

		$dataFromCache = $this->cache->getInstituteSections($instituteId, $sections);

		$instituteLocations = array();
		if(in_array('location', $sections))
			$instituteLocations = $this->cache->getInstituteLocations($instituteId);

		$dataFromCache['location'] = $instituteLocations;

		if(empty($dataFromCache['basic']))
			return false;

		$instituteObj = $this->_populateInstituteObjFromData($instituteId, $dataFromCache, $sections);
		return $instituteObj;
	}

	protected function populateAndStoreInstituteObject($results, $instituteObj, $storeInCache = true, $sectionKey = array())
	{
		$institutes = $this->fillAndStoreInstitute($results, $instituteObj, $storeInCache, $sectionKey);
		return current($institutes);
	}

	protected function populateAndStoreMultipleInstituteObjects($results, $instituteObj, $storeInCache = true, $sectionKey = array())
	{
		$institutes = array();
		foreach ($results as $instituteId=>$instituteData) {
			$institutes += $this->fillAndStoreInstitute(array($instituteId=>$instituteData), $instituteObj, $storeInCache, $sectionKey);	
		}
		
		return $institutes;
	}

	private function fillAndStoreInstitute($results, $instituteObj, $storeInCache, $sectionKey)
	{
		$institutes = array();
		if(is_array($results) && count($results)) {
			foreach($results as $instituteId => $result) {
				if($instituteObj)
					$institute = $instituteObj;
				else
					$institute = $this->_createInstitute();
				$this->_loadChildren($instituteId, $institute,$result, $storeInCache, $sectionKey);
				$institutes[$instituteId] = $institute;
			}
		}

		return $institutes;
	}
	
	// create Institute object
	public function _createInstitute()
	{
		$institute = new Institute;
		return $institute;
	}
	
	// loads all the children of institute
	private function _loadChildren($instituteId, $institute,$result, $storeInCache = true, $sectionKey)
	{
		if($sectionKey)
			$children = $sectionKey;
		else {
			global $instituteSections;
			$children = $instituteSections;	
		}
		foreach($children as $key=>$child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
					$this->_loadChild($instituteId, $child,$institute,$result[$child], $storeInCache);
			}
			else {
				$this->_loadChild($instituteId, $child,$institute, NULL, $storeInCache);
			}
			// unset($children[$key]);
		}
	}

	// load indivisual child of an institute
	private function _loadChild($instituteId,$child,$institute,$childResult = NULL, $storeInCache)
	{
		switch($child) {
			case 'basic':
				foreach ($childResult as &$basicSection) {
					if($basicSection['main_location']){
						$basicSection['main_location'] = $this->_loadSectionDetails(array($basicSection['main_location']), 'location');
						$basicSection['main_location'] = current($basicSection['main_location']);
					}	
				}
				
				$this->_loadBasic($institute,$childResult);
				$basicData = $this->object_to_array($childResult);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $basicData);
				break;
			case 'location':
				$locations = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $locations, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'academic':
				$academicDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $academicDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'research_project':
				$researchDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $researchDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'usp':
				$uspDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $uspDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'event':
				$eventDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $eventDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'scholarship':
				$scholarshipDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $scholarshipDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'company':
				$companyDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $companyDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'media':
				$mediaDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $mediaDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
			case 'facility':
				$childResultFromDb = $childResult;
				foreach ($childResult as $facilityKey => $faciltyRow) {
					$facilityData = $faciltyRow;
					if(count($faciltyRow['child_facilities']) > 0){
						$childResult[$facilityKey]['child_facilities'] = $this->_loadSectionDetails($faciltyRow['child_facilities'], $child);
					}
				}
				$facilityDetails = $this->_loadSectionDetails($childResult, $child);
				$this->_addDataToObject($institute, $facilityDetails, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResultFromDb);
				break;
			case 'childPageExists':
				$this->_loadChildPageDetails($institute,$childResult); //to read from cache and populate in inst obj
				$childPageExistsDetails = $this->object_to_array($childResult);//to store in cache
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childPageExistsDetails);
				break;
			case 'brochureSectionData':
				$this->_loadChildPageDetails($institute,$childResult); //to read from cache and populate in inst obj
				$brochureSectionDetails = $this->object_to_array($childResult);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $brochureSectionDetails);
				break;
			case 'wikiSection':
				$this->_loadChildPageDetails($institute,$childResult);
				$wikiSectionDetails = $this->object_to_array($childResult);//to store in cache
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $wikiSectionDetails);
				break;
			case 'seoData':
				$data = $this->_loadSectionDetails($childResult, $child);
				$seoData = $this->makeSeoData($instituteId, $data);
				$this->_addDataToObject($institute, $seoData, $child);
				if($storeInCache) $this->_storeSectionInCache($instituteId, $child, $childResult);
				break;
		}
	}

	function _addDataToObject(&$instituteObj, $sectionData, $section){

		switch($section) {
			case 'location':
				$instituteObj->addLocations($sectionData);
				break;
			case 'academic':
				$instituteObj->addAcademics($sectionData);
				break;
			case 'research_project':
				$instituteObj->addResearchProjects($sectionData);
				break;
			case 'usp':
				$instituteObj->addUSPList($sectionData);
				break;
			case 'event':
				$instituteObj->addEventList($sectionData);
				break;
			case 'scholarship':
				$instituteObj->addScholarshipList($sectionData);
				break;
			case 'company':
				$instituteObj->addCompanyList($sectionData);
				break;
			case 'media':
				$instituteObj->addMediaList($sectionData);
				break;
			case 'facility':
				$instituteObj->addFacilities($sectionData);
				break;
			case 'seoData':
				$instituteObj->addSeoData($sectionData);
				break;
		}
	}

	private function _loadBasic($institute,$result = NULL){
		if(!empty($result)){
			$result = current($result);
			if(!empty($result['admissionDetails'])) 
			{
				$result['admissionDetails'] = $this->convertAdmissionImagesIframeToLazy($result['admissionDetails']);
			}
			$result = $this->setAdmissionAvailabilityFlag($result['listing_id'], $result);
			$this->fillObjectWithData($institute,$result);
		}
	}

	private function _loadChildPageDetails($institute,$result = NULL){
		$this->fillObjectWithData($institute,$result[0]);
	}

	protected function _loadSectionDetails($result, $sectionName){
		$sectionData = array();
		
		foreach ($result as $value) {
			if($sectionName == 'location'){
				$value['contact_details'] = $this->_loadSingleSectionDetail($value['contact_details'], 'contact');
				$sectionData[$value['listing_location_id']] = $this->_createSectionEntity($value, $sectionName);
			}
			else
				$sectionData[] = $this->_createSectionEntity($value, $sectionName);
		}

		return $sectionData;
	}

	private function _loadSingleSectionDetail($result, $sectionName){
		$sectionData = array();
		
		$sectionData = $this->_createSectionEntity($result, $sectionName);
		
		return $sectionData;
	}

	private function _createSectionEntity($result, $sectionName){

		switch ($sectionName) {
			case 'location':
				$sectionEntity = new NationalLocation;
				break;
			case 'academic':
				$sectionEntity = new NationalAcademic;
				break;
			case 'research_project':
				$sectionEntity = new NationalResearchProject;
				break;
			case 'usp':
				$sectionEntity = new NationalUSP;
				break;
			case 'event':
				$sectionEntity = new NationalEvent;
				break;
			case 'scholarship':
				$sectionEntity = new NationalScholarship;
				break;
			case 'company' : 
				$sectionEntity = new NationalCompany;
				break;
			case 'media' : 
				$sectionEntity = new NationalMedia;
				break;
			case 'facility' : 
				$sectionEntity = new NationalFacility;
				break;
			case 'contact' : 
				$sectionEntity = new NationalContact;
				break;
			case 'childPageExists':
				$sectionEntity = new ChildPageExists;
				break;
			case 'seoData':
				$sectionEntity = new InstitutePageSeoInfo;
				break;
		}

		$this->fillObjectWithData($sectionEntity,$result);

		return $sectionEntity;	
	}

	private function _storeSectionInCache($instituteId, $sectionName, $sectionData){

		global $instituteSectionNotCached;

		if($sectionName == 'location'){
			foreach ($sectionData as &$value) {
				$value = json_encode($value);
			}
			$this->cache->storeInstituteLocations($instituteId, $sectionData);
		}
		else if(!in_array($sectionName, $instituteSectionNotCached)){
			$this->cache->storeInstituteSection($instituteId, $sectionName, $sectionData);
		}
	}

	private function makeSeoData($instituteId, $sectionData){
		
		$seoData = array();
		foreach ($sectionData as $key => $data) {
			$pageType = $data -> getDescriptionType();
			$seoData[$pageType] = $data;
		}
		return $seoData;
	}

	function _populateInstituteObjFromData($instituteId,$dataFromCache,$sectionsNeeded){

		if($dataFromCache['basic']){
			$dataFromCache['basic'] = $this->setAdmissionAvailabilityFlag($instituteId, $dataFromCache['basic']);
			$instituteObj = $dataFromCache['basic'];
			unset($dataFromCache['basic']);
		}

		if(!isset($instituteObj) || get_class($instituteObj) != 'Institute'){
			$instituteObj = new Institute;
		}

		global $instituteSectionNotCached;
		global $instituteSections;

		foreach ($instituteSections as $section) {
			if(in_array($section, $instituteSectionNotCached) && in_array($section, $sectionsNeeded)){
				$dataFromDB = $this->dao->getData($instituteId, $section);
				$instituteObj = $this->populateAndStoreInstituteObject($dataFromDB, $instituteObj, false, array($section));
			}
			else if(in_array($section, $sectionsNeeded)){
				$this->_addDataToObject($instituteObj, $dataFromCache[$section], $section);
			}

		}

		return $instituteObj;
	}

	protected function _filterLocationsByStateCityLocality(&$locations, $stateCityLocalityFilter){

		// filter by state
		if(array_key_exists("states", $stateCityLocalityFilter)){
			foreach ($locations as $instituteId => $instituteLocations) {
				foreach ($instituteLocations as $instLocationId => $locationObject) {
					if(!in_array($locationObject->getStateId(),$stateCityLocalityFilter['states'])){
						unset($locations[$instituteId][$instLocationId]);
					}
				}
			}
		}

		// filter by city
		if(array_key_exists("cities", $stateCityLocalityFilter)){
			foreach ($locations as $instituteId => $instituteLocations) {
				foreach ($instituteLocations as $instLocationId => $locationObject) {
					if(!in_array($locationObject->getCityId(),$stateCityLocalityFilter['cities'])){
						unset($locations[$instituteId][$instLocationId]);
					}
				}
			}
		}

		// filter by localities
		if(array_key_exists("localities", $stateCityLocalityFilter)){
			foreach ($locations as $instituteId => $instituteLocations) {
				foreach ($instituteLocations as $instLocationId => $locationObject) {
					if(!in_array($locationObject->getLocalityId(),$stateCityLocalityFilter['localities'])){
						unset($locations[$instituteId][$instLocationId]);
					}
				}
			}
		}

		return $locations;
	}

	protected function _validateSections(&$sections){

		global $instituteSections;
		if($sections == 'full'){
			$sections = $instituteSections;
		}
		else if($sections == ''){
			$sections = array('basic');	
		}

		if(!in_array('basic', $sections)){
			$sections[] = 'basic';
		}
	}

	function setAdmissionAvailabilityFlag($listingId, $results){

		if(empty($results) || empty($listingId))
			return $results;

		$flag = $this->cache->checkIfAdmissionDetailsExists($listingId);
		if($flag)
			$showAdmissionFlag = 1;
		else
			$showAdmissionFlag = 0;

		if(is_array($results))
			$results['showAdmissionFlag'] = $showAdmissionFlag;
		else
			$results->setAdmissionDetailsFlag($showAdmissionFlag);

		return $results;
	}

	function object_to_array($obj) {
		if(is_object($obj)) $obj = (array) $this->dismount($obj);
		if(is_array($obj)) {
			$new = array();
			foreach($obj as $key => $val) {
				$new[$key] = $this->object_to_array($val);	
			}
		}
		else $new = $obj;
		return $new;
	}

	function convertAdmissionImagesIframeToLazy($htmlString)
	{
		$htmlString = html_entity_decode($htmlString, ENT_HTML5 | ENT_QUOTES);
        $htmlString = convertIframesToLazyLoad($htmlString);
        return $htmlString;
	}

	function dismount($object) {
		$reflectionClass = new ReflectionClass(get_class($object));
		$array = array();
		foreach ($reflectionClass->getProperties() as $property) {
			$property->setAccessible(true);
			$array[$property->getName()] = $property->getValue($object);
			$property->setAccessible(false);
		}
		return $array;
	}

}
?>

<?php

class UniversityRepositoryAbstract extends EntityRepository{

	function __construct($dao = NULL,$cache = NULL)
    {
        parent::__construct($dao,$cache);
        
        $this->dao         = $dao;
        $this->cache       = $cache;
    }

    protected function getMultipleUniversityFromCache($universityIds, $fields= array()){
    	$universityObjects = array();
		$dataFromCache = $this->cache->getUniversities($universityIds, $fields);		
		 // _p($dataFromCache);die;	
		foreach ($dataFromCache as $universityId=>$universityData) {
			if(!empty($universityData)){
				$university = $this->_createUniversityNew();
				$this->_loadChild($universityId, $university,$universityData, false);
				$universityObjects[$universityId] = $university;				
			}
		}
		return $universityObjects;
    }


    protected function populateAndStoreMultipleUniversityObjects($results, $universityObj, $storeInCache = true){
    	$universities = array();    	
		foreach ($results as $universityId=>$universityData) {
			$universities += $this->fillAndStoreUniversity(array($universityId=>$universityData), $universityObj, $storeInCache);	
		}
		return $universities;

    }

    private function fillAndStoreUniversity($results, $universityObj, $storeInCache)
	{
		$universities = array();
		if(is_array($results) && count($results)) {
			foreach($results as $universityId => $result) {
				if($universityObj)
					$university = $universityObj;
				else
					$university = $this->_createUniversityNew();

				$this->_loadChild($universityId, $university,$result, $storeInCache);
				$universities[$universityId] = $university;
			}
		}

		return $universities;
	}

    // create University object
	public function _createUniversityNew()
	{
		$university = new University;
		return $university;
	}

	// loads all the children of institute
	private function _loadChild($universityId, $university,$result, $storeInCache = true)
	{
	    // _p($result);die('aaaaaaa');
		//special case check if contact needs to fill or not
		$globalSectionList = array("intlStudentsURL", "packType", "accreditation", "totalIntlStudents", "logoURL", "establishmentYear", "campusCount", "percentIntlStudents", "courseCount","highlights","website","maleFemaleRatio","ugPgRatio","affiliation","media","id","facultyStudentRatio","indianConsultantsURL","indianStudentsURL","admissionCityId","accomodationWebsite","contactPersonName","courses","name","announcement","campuses","admissionContactPerson","seoDetails","conditionalOffer","wiki","universityEndowments","contactWebsite","accomodationDetails","type","acronym","contactEmail","livingExpenses","fundingType","universityLocation","cumulativeViewCount","contactNumber","scoreReporting","applicationProfiles","universityDefaultImgUrl","campusSize","brochureURL","facebookURL","seoURL","contactDetails","admissionContact","listing_seo_title","listing_seo_description","listing_seo_keywords","customAttribute","expertId","lastModifyDate");
		 
		if(!empty($result['seoDetails'])){
			$result['listing_seo_title']       = $result['seoDetails']['title'];
			$result['listing_seo_description'] = $result['seoDetails']['description'];
			$result['listing_seo_keywords']    = $result['seoDetails']['keyword'];
		}



		if(!empty($result['admissionCityId']) || !empty($result['admissionContactPerson'])){
			$result['admissionContact'] = true;
		}

		foreach ($globalSectionList as $section) {
			$val = $result[$section];
			switch ($section) {
				case 'media':
					if(empty($val)){
						$val[]="";
					}
					$mediaDetails = $this->_loadSectionDetails($val, $section);					
					$this->_addDataToObject($university, $mediaDetails, $section);
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);	
					break;	
				case 'universityLocation':
					$modifiedVal = $this->_loadUniversityLocationSubObject($val);
					$locationDetails = reset($this->_loadSectionDetails(array($modifiedVal), $section));
					$this->_addDataToObject($university, $locationDetails, $section);					
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);	
					break;
				case 'campuses':
					if(empty($val)){
						$val[]="";
					}
					$campusesDetails = $this->_loadSectionDetails($val, $section);
					$this->_addDataToObject($university, $campusesDetails, $section);
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);	
					break;
				case 'livingExpenses':
					$modifiedCampusAccomData =  $val;
					$modifiedCampusAccomData['accommodation_details'] = $result['accomodationDetails'];
					$modifiedCampusAccomData['accommodation_website_url'] = $result['accomodationWebsite'];
					$modifiedCampusAccomData = $this->_loadUniversityCampusAccSubObject($modifiedCampusAccomData);
					$campusAccommodationDetails = reset($this->_loadSectionDetails(array($modifiedCampusAccomData), $section));											
					$this->_addDataToObject($university, $campusAccommodationDetails, $section);
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);	
					break;
				case 'announcement':
					$announcementDetails = reset($this->_loadSectionDetails(array($val), $section));
					$this->_addDataToObject($university, $announcementDetails, $section);
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);	
					break;

				case 'contactDetails':
					$contactDetailsObjectData = array();
					$contactDetailsObjectData['contact_person'] = $result['contactPersonName'];
					$contactDetailsObjectData['contact_email'] = $result['contactEmail'];
					$contactDetailsObjectData['website'] = $result['contactWebsite'];
					$contactDetailsObjectData['contact_main_phone'] = $result['contactNumber'];
					$contactDetails = reset($this->_loadSectionDetails(array($contactDetailsObjectData), $section));
					$this->_addDataToObject($university, $contactDetails, $section);
					break;
				case 'seoDetails':					
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);
					break;
				case 'admissionContact':
					$admissionContactDetailsObjectData = array();
					$admissionContactDetailsObjectData['admission_contact_person'] = $result['admissionContactPerson'];
					$admissionContactDetailsObjectData['city'] = $result['admissionCityId'];
					$admissionContactDetailsObjectData['admission_website_url'] = $result['website'];
					$modifiedVal = $this->_loadAdmissionSubObject($admissionContactDetailsObjectData);					
					$admissionContactDetails = reset($this->_loadSectionDetails(array($modifiedVal), $section));
					$this->_addDataToObject($university, $admissionContactDetails, $section);
					break;
                case 'customAttribute':
                    if($storeInCache){
                        $this->_storeSectionInCache($universityId,$section,$val);
                    }
				default:					
					$this->fillObjectWithData($university,array($section=>$val));
					if($storeInCache) $this->_storeSectionInCache($universityId, $section, $val);
					break;
			}			
		}	
		return $university;	
		_p($university);die;
		//_p($result);die;
		// if($sectionKey)
		// 	$children = $sectionKey;
		// else {
		// 	global $instituteSections;
		// 	$children = $instituteSections;	
		// }
		
		// foreach($children as $key=>$child) {
		// 	if(is_array($result[$child]) && count($result[$child]) > 0) {
		// 			$this->_loadChild($instituteId, $child,$institute,$result[$child], $storeInCache);
		// 	}
		// 	else {
		// 		$this->_loadChild($instituteId, $child,$institute, NULL, $storeInCache);
		// 	}
		// 	// unset($children[$key]);
		// }
	}

	private function _loadUniversityLocationSubObject($data){
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder    = new LocationBuilder;
		$locationRepo = $locationBuilder->getLocationRepository();
		// _p($data);die;
		$data['entities'] = array();
		if(isset($data['cityId']) && !empty($data['cityId'])){
			$city               = $locationRepo->findCity($data['cityId']);												
		}else{
			$city = new City;			
		}		
		$data['entities']['city'] = $city;		


		$stateIdFromCityObj = $city->getStateId();
		if(isset($stateIdFromCityObj) && !empty($stateIdFromCityObj) && $stateIdFromCityObj != -1){
			$state               = $locationRepo->findState($stateIdFromCityObj);												
		}else{
			$state = new State;			
		}		
		$data['entities']['state'] = $state;

		if(isset($data['countryId']) && !empty($data['countryId'])){
			$country               = $locationRepo->findCountry($data['countryId']);												
		}else{
			$country = new Country;			
		}		
		$data['entities']['country'] = $country;

		$regionIdFromCountry = $country->getRegionId();		

		if(isset($regionIdFromCountry) && !empty($regionIdFromCountry)){
			$region               = $locationRepo->findRegion($regionIdFromCountry);												
		}else{
			$region = new Region;			
		}		
		$data['entities']['region'] = $region;
		
		return $data;
	}

	private function _loadUniversityCampusAccSubObject($data){
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$currencyRepository = $listingBuilder->getCurrencyRepository();	
		
		if(isset($data['livingExpensesCurrency']) && !empty($data['livingExpensesCurrency'])){
			$currencyObj 			= $currencyRepository->findCurrency($data['livingExpensesCurrency']);
		}else{
			$currencyObj = new Currency;			
		}		
		$data['currency_entity'] = $currencyObj;
		return $data;

	}

	private function _loadAdmissionSubObject($data){		
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder    = new LocationBuilder;
		$locationRepo = $locationBuilder->getLocationRepository();
		
		if(isset($data['city']) && !empty($data['city'])){
			$city               = $locationRepo->findCity($data['city']);												
		}else{
			$city = new City;			
		}		
		$data['city'] = $city;	
		return $data;
	}

	private function _storeSectionInCache($universityId, $sectionName, $sectionData){
		$this->cache->storeUniversitySection($universityId, $sectionName, $sectionData);
	}

	protected function _loadSectionDetails($result, $sectionName){		
		$sectionData = array();		
		foreach ($result as $value) {			
				$sectionData[] = $this->_createSectionEntity($value, $sectionName);
		}		
		return $sectionData;
	}

	private function _createSectionEntity($result, $sectionName){
		switch ($sectionName) {			
			case 'media' : 
				$sectionEntity = new ListingMedia;
				$result['media_type'] = $result['mediaType'];
				$result['thumburl']   = $result['thumbUrl'];				
				break;
			case 'universityLocation' :				
				$sectionEntity = new UniversityLocation;
				break;
			case 'admissionContact':
				$sectionEntity = new AdmissionContact;
				break;
			case 'campuses':
				$sectionEntity = new Campus;
				$result['campus_website_url']= $result['campus_link'];
				break;
			case 'livingExpenses':
				$sectionEntity = new CampusAccommodationV2;
				break;
			case 'announcement':
				$sectionEntity = new UniversityAnnouncement;			
				break;
			case 'contactDetails':
				$sectionEntity = new ContactDetail;
				break;
		}		
		$this->fillObjectWithData($sectionEntity,$result);		
		return $sectionEntity;	
	}

	function _addDataToObject(&$universityObj, $sectionData, $section){
		// _p($sectionData);
		// _p($section);
		switch($section) {
			case 'media':
				$universityObj->addMedia($sectionData);
				break;	
			case 'universityLocation':
				$universityObj->addLocation($sectionData);
				break;
			case 'campuses':
				$universityObj->addCampus($sectionData);
				break;
			case 'livingExpenses':
				$universityObj->addCampusAccommodation($sectionData);
				break;
			case 'announcement':
				$universityObj->addAnnouncement($sectionData);
				break;
			case 'contactDetails':
				$universityObj->addContactDetails($sectionData);
				break;
			case 'admissionContact':
				$universityObj->addAdmissionContact($sectionData);
				break;
		}
	}
}
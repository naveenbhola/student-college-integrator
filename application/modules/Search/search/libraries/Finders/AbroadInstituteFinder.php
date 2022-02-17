<?php

class AbroadInstituteFinder {

        private $_ci;
        private $listingBuilder;
    
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility", "", true);
		$this->_ci->load->model("listing/abroadinstitutemodel", "", true);
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
		
		$this->_ci->load->library("listing/AbroadListingCommonLib");
		$this->abroadListingCommonLib = new AbroadListingCommonLib();
	}
        
        public function getData($id = null) {
		if($id == null){
			return false;
		}
		$listingRepos = $this->listingBuilder->getAbroadInstituteRepository();
		$listingRepos->disableCaching();
		$abroadInstituteDataObject = $listingRepos->find($id);
		$abroadInstituteDataForIndex = $this->preprocessRawData($abroadInstituteDataObject);
        $this->abroadInstituteDataObject = $abroadInstituteDataObject;
//		$dataSufficientFlag = $this->isDataSufficient($abroadInstituteDataForIndex);
//		if(!$dataSufficientFlag){
//			$abroadInstituteDataForIndex = false;
//		}
		return $abroadInstituteDataForIndex;
	}
        
        public function preprocessRawData($abroadInstituteDataObject = null){
                $abroadInstitute['sa_institute_abbreviation'] = $this->getAbbreviation($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_id'] = $this->getId($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_name'] = $this->getName($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_type'] = $this->getInstituteType($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_pack_type'] = $this->getPackType($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_description'] = $this->getDescription($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_accreditation'] = $this->getAccreditation($abroadInstituteDataObject);
                $abroadInstitute['sa_institute_view_count'] = $this->getViewCount($abroadInstituteDataObject);
                $abroadInstitute['facetype'] = 'abroadinstitute';
                
                $locationInfo = $this->getLocationInfo($abroadInstituteDataObject);
                $abroadInstitute['unique_id'] = 'abroadinstitute_' . $abroadInstitute['sa_institute_id'] . '_' . $locationInfo['sa_institute_city_id'];

                $abroadInstitute = array_merge($abroadInstitute, $locationInfo);
                return $abroadInstitute;
        }
        
        private function getId($abroadInstituteDataObject = null){
                $id = -1;
                $id = $abroadInstituteDataObject->getId();
                return trim($id);
            
        }
        
        private function getName($abroadInstituteDataObject = null){
                $name = '';
                $name = $abroadInstituteDataObject->getName();
                return trim($name);
        }
        
        private function getAbbreviation($abroadInstituteDataObject = null){
                $abbreviation = '';
                $abbreviation = $abroadInstituteDataObject->getAbbreviation();
                return $abbreviation;
        }
        
        private function getInstituteType($abroadInstituteDataObject = null){
                $type = '';
                $type = $abroadInstituteDataObject->getInstituteType();
                return $type;
        }
        
        private function getPackType($abroadInstituteDataObject = null){
                $pack_type = '';
                $pack_type = $abroadInstituteDataObject->getPackType();
                return $pack_type;
        }
        
        private function getDescription($abroadInstituteDataObject = null){
                $description = '';
                $description = $abroadInstituteDataObject->getDescription();
                return $description;
        }
        
        private function getAccreditation($abroadInstituteDataObject = null){
                $accrediation = '';
                $accrediation = $abroadInstituteDataObject->getAccreditation();
                return $accrediation;
        }
        
        private function getLocationInfo($abroadInstituteDataObject = null){
                $locationInfo = array();
                $mainLocation = $abroadInstituteDataObject->getMainLocation();
                $locationInfo = $this->getCourseLocationInformation($mainLocation);
                return $locationInfo;
        }
        
        public function getUniversityName(){
                $universityName = '';
                $universityName = $this->abroadInstituteDataObject->getUniversityName();
        }
        
        public function getUniversityId(){
                $universityId = -1;
                $universityId = $this->abroadInstituteDataObject->getUniversityId();
                return $universityId;   
        }
        
        public function getCourses($abroadInstituteDataObject = null){
                $courses = $abroadInstituteDataObject->getCourses();
                return $courses;
        }
        
        private function getViewCount($abroadInstituteDataObject = null){
                $viewCount = -1;
                $viewCount = $abroadInstituteDataObject->getViewCount();
                return $viewCount;
        }
        
        private function getCourseLocationInformation($locationObject){
		$locationInfo['sa_institute_locality_id'] = $locationObject->getLocality()->getId() != null ? $locationObject->getLocality()->getId() : "";
		$locationInfo['sa_institute_locality_name'] = $locationObject->getLocality()->getName() != null ? $locationObject->getLocality()->getName() : "";
		
		$locationInfo['sa_institute_zone_id'] = $locationObject->getZone()->getId() != null ? $locationObject->getZone()->getId() : "";
		$locationInfo['sa_institute_zone_name'] = $locationObject->getZone()->getName() != null ? $locationObject->getZone()->getName() : "";
		
		$locationInfo['sa_institute_city_id'] = $locationObject->getCity()->getId() != null ? $locationObject->getCity()->getId() : "";
		$locationInfo['sa_institute_city_name'] = $locationObject->getCity()->getName() != null ? $locationObject->getCity()->getName() : "";
		
		$locationInfo['sa_institute_state_id'] = $locationObject->getState()->getId() != null ? $locationObject->getState()->getId() : "";
		$locationInfo['sa_institute_state_name'] = $locationObject->getState()->getName() != null ? $locationObject->getState()->getName() : "";
		
		$locationInfo['sa_institute_country_id'] = $locationObject->getCountry()->getId() != null ? $locationObject->getCountry()->getId() : "";
		$locationInfo['sa_institute_country_name'] = $locationObject->getCountry()->getName() != null ? $locationObject->getCountry()->getName() : "";
		
		$locationInfo['sa_institute_location_id'] = $locationObject->getLocationId() != null ? $locationObject->getLocationId() : "";
		
		
		$locationAttributes = array();
		$instituteLocationAttributes = $locationObject->getAttributes();
		foreach($instituteLocationAttributes as $attribute){
			$locationAttributes[$attribute->getName()] = $attribute->getValue() != null ? $attribute->getValue() : "";
		}
		$locationInfo['sa_institute_location_attributes'] = json_encode($locationAttributes);
		
		$searchModel = new SearchModel();
		$continentInfo = $searchModel->getContinentForCountry($locationInfo['institute_country_id']);
		$locationInfo = array_merge($locationInfo, $continentInfo);
		return $locationInfo;
	}
	
	public function getCourseIds() {
		$deptIds = array();
		$courseIds = array();
		$deptCourses = $this->abroadListingCommonLib->getUniversityCoursesGroupByDepartments($this->abroadInstituteDataObject->getUniversityId());
		$coursesByDepartment = $deptCourses['department']['courses'][$this->abroadInstituteDataObject->getId()];
		$keys = array_keys($coursesByDepartment);
		foreach($keys as $key) {
			if(!empty($coursesByDepartment[$key])) {
				$courseIds = array_merge($courseIds, array_keys($coursesByDepartment[$key]));
			}
		}
		
		return $courseIds;
	}
}


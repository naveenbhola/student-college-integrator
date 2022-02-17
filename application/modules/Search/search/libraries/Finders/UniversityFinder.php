<?php

class UniversityFinder{
        private $_ci;
        private $listingBuilder;
    
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility", "", true);
		$this->_ci->load->model("listing/universitymodel", "", true);
		
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
		
		$this->_ci->load->library("listing/AbroadListingCommonLib");
		$this->abroadListingCommonLib = new AbroadListingCommonLib();
	}
        
        public function getData($id = null) {
		if($id == null){
			return false;
		}
		$listingRepos = $this->listingBuilder->getUniversityRepository();
		$listingRepos->disableCaching();
		$universityDataObject = $listingRepos->find($id);
		$universityDataForIndex = $this->preprocessRawData($universityDataObject);
                $this->universityDataObject = $universityDataObject;
                if(empty($universityDataForIndex['university_id'])){
                        return null;
                }
//		$dataSufficientFlag = $this->isDataSufficient($universityDataForIndex);
//		if(!$dataSufficientFlag){
//			$universityDataForIndex = false;
//		}
		return $universityDataForIndex;
	}
        
        public function preprocessRawData($universityDataObject = null) {
                $university['university_id'] = $this->getId($universityDataObject);
                $university['university_name'] = $this->getName($universityDataObject);
                $university['university_acronym'] = $this->getAcronym($universityDataObject);
                $university['university_establishyear'] = $this->getEstablishYear($universityDataObject);
                $university['university_logolink'] = $this->getLogoLink($universityDataObject);
                $university['university_photo'] = $this->getPhoto($universityDataObject);
                $university['university_type'] = $this->getTypeOfInstitute($universityDataObject);
                $university['university_type2'] = $this->getTypeOfInstitute2($universityDataObject);
                $university['university_campus'] = $this->getCampuses($universityDataObject);
                $university['university_affiliation'] = $this->getAffiliation($universityDataObject);
                $university['university_accreditation'] = $this->getAccreditation($universityDataObject);
                $university['university_brochurelink'] = $this->getBrochureLink($universityDataObject);
                $university['university_facebookpage'] = $this->getFacebookPage($universityDataObject);
                $university['university_websitelink'] = $this->getWebsiteLink($universityDataObject);
                $university['university_whyjoin'] = $this->getWhyJoin($universityDataObject);
                $university['university_funding'] = $this->getFundingType($universityDataObject);
                $university['university_view_count'] = $this->getViewCount($universityDataObject);
                $university['university_international_student_website'] = $this->getInternationalStudentsPageLink($universityDataObject);
                $university['university_accommodation'] = $this->hasCampusAccommodation($universityDataObject);
                $university['university_accommodation_description'] = $this->getAccommodationDescription($universityDataObject);
                $university['university_seo_url'] = $this->getURL($universityDataObject);
                $university['facetype'] = 'university';

                $universityContactDetails = $this->getContactDetails($universityDataObject);
                $universityLocation = $this->getLocation($universityDataObject);
                $university['unique_id'] = 'university_' . $university['university_id'];

                $university = array_merge($university, $universityContactDetails, $universityLocation);
                
                return $university; 
        }
        
        private function getId($universityDataObject = null){
                $id = -1;
                $id = $universityDataObject->getId();
                return $id;
        }
        
        private function getName($universityDataObject = null){
                $name = '';
                $name = $universityDataObject->getName();
                return $name;
        }
        
        private function getAcronym($universityDataObject = null){
                $acronym = '';
                $acronym = $universityDataObject->getAcronym();
                return $acronym;
        }
        
        private function getEstablishYear($universityDataObject = null){
                $establishYear = '';
                $establishYear = $universityDataObject->getEstablishedYear();
                return $establishYear;
        }
        
        private function getLogoLink($universityDataObject = null){
                $logoLink = '';
                $logoLink = $universityDataObject->getLogoLink();
                return trim($logoLink);
        }
        
        private function hasCampusAccommodation($universityDataObject = null){
                $hasAccomodation = '';
                $hasAccomodation = $universityDataObject->hasCampusAccommodation();
                if(empty($hasAccomodation)) {
                        $hasAccomodation = 0;
                }
                return $hasAccomodation;
        }
        
        private function getAccommodationDescription($universityDataObject = null){
                $accomodationDesc = '';
                $accomodationDesc = $universityDataObject->getCampusAccommodation()->getAccommodationDetails();
                if(empty($accomodationDesc)){
                       $accomodationDesc =''; 
                }
                return $accomodationDesc;
        }
        
        private function getCampuses($universityDataObject = null){
                $campusNames = array();
                $campuses = $universityDataObject->getCampuses();
                if(!empty($campuses)){
                        foreach($campuses as $campus){
                                $campusNames[] = $campus->getName();
                        }
                }
                return $campusNames;
            
        }
        
        private function getInternationalStudentsPageLink($universityDataObject = null){
                $pageLink = '';
                $pageLink = $universityDataObject->getInternationalStudentsPageLink();
                return $pageLink;
        }
        
        private function getTypeOfInstitute($universityDataObject = null){
                $type = '';
                $type = $universityDataObject->getTypeOfInstitute();
                return $type;
        }
        
        private function getTypeOfInstitute2($universityDataObject = null){
                $type2 = '';
                $type2 =  $universityDataObject->getTypeOfInstitute2();
                return $type2;
        }
        
        public function getPhoto($universityDataObject = null){
                $univPhotos = array();
                $univPhotos = $universityDataObject->getPhotos();
		if(count($univPhotos)) {
                        $imgUrl = $univPhotos['0']->getThumbURL('172x115');
		} else {
                        $imgUrl = "";
		}
                return $imgUrl;
        }
        
        private function getAffiliation($universityDataObject = null){
                $affiliation = '';
                $affiliation = $universityDataObject->getAffiliation();
                if(empty($affiliation)){
                        $affiliation = ' ';
                }
                return $affiliation;
        }
        
        private function getAccreditation($universityDataObject = null){
                $accreditation = '';
                $accreditation = $universityDataObject->getAccreditation();
                if(empty($accreditation)){
                        $accreditation = ' ';
                }
                return $accreditation;
        }
        
        private function getBrochureLink($universityDataObject = null){
                $brochurelink = '';
                $brochurelink = $universityDataObject->getBrochureLink();
                return $brochurelink;
        }
        
        public function getContactDetails($universityDataObject = null){
                $contactDetailsInfo['university_email'] = $universityDataObject->getContactDetails()->getContactEmail() != null ? $universityDataObject->getContactDetails()->getContactEmail():'';
                $contactDetailsInfo['university_phone'] = $universityDataObject->getContactDetails()->getContactMainPhone() != null ? $universityDataObject->getContactDetails()->getContactMainPhone():'';
                $contactDetailsInfo['university_website'] = $universityDataObject->getContactDetails()->getContactWebsite() != null ? $universityDataObject->getContactDetails()->getContactWebsite():'';
                $contactDetailsInfo['university_person'] = $universityDataObject->getContactDetails()->getContactPerson() != null ? $universityDataObject->getContactDetails()->getContactPerson():'';

                return $contactDetailsInfo;
        }
        
        private function getFacebookPage($universityDataObject = null){
                $facebookpage = '';
                $facebookpage = $universityDataObject->getFacebookPage();
                return $facebookpage;
        }
        
        private function getWebsiteLink($universityDataObject = null){
                $websitelink = '';
                $websitelink = $universityDataObject->getWebsiteLink();
                return $websitelink;
        }
        
        private function getURL($universityDataObject = null){
                $listingSeoUrl = '';
                $listingSeoUrl = $universityDataObject->getURL();
                return $listingSeoUrl;
        }
        
        private function getLocation($universityDataObject = null){
                $locationObject =  $universityDataObject->getLocation();
                $locationInfo = $this->getCourseLocationInformation($locationObject);
                return $locationInfo;
        }
        
        private function getWhyJoin($universityDataObject = null) {
                $whyjoin = '';
                $whyjoin = $universityDataObject->getWhyJoin();
                return $whyjoin;
        }
        
        private function getFundingType($universityDataObject = null){
                $fundtype = '';
                $fundtype = $universityDataObject->isPublicalyFunded();
                return $fundtype;
        }
        
        private function getViewCount($universityDataObject = null){
                $viewCount = -1;
                $viewCount = $universityDataObject->getCumulativeViewCount();
                return $viewCount;
        }
        
        public function getDepartments(){
                $departments = $this->universityDataObject->getDepartments();
                return $departments;
        }
        
        private function getCourseLocationInformation($locationObject){
//		$locationInfo['univesity_location_id'] = $locationObject->getLocationId() != null ? $locationObject->getLocationId() : "";
		$locationInfo['university_address'] = $locationObject->getAddress() != null ? $locationObject->getAddress() : "";
		$locationInfo['university_city'] = $locationObject->getCity()->getName() != null ? $locationObject->getCity()->getName() : "";
                $locationInfo['university_country'] = $locationObject->getCountry()->getName() != null ? $locationObject->getCountry()->getName() : "";
                $locationInfo['university_state'] = $locationObject->getState()->getName() != null ? $locationObject->getState()->getName() : "";
                $locationInfo['university_region'] = $locationObject->getRegion()->getName() != null ? $locationObject->getRegion()->getName() : "";
                
                return $locationInfo;
	}
	
	public function getDepartmentIdsAndCourseIds() {
		$deptIds = array();
		$includeVirtualDept = true;
		$deptCourses = $this->abroadListingCommonLib->getUniversityCoursesGroupByDepartments($this->universityDataObject->getId(),$includeVirtualDept);
		
        if($includeVirtualDept == true) {
            $deptIds = array();
            $virtualdeptIds = array();
            foreach ($deptCourses['department']['courses'] as $deptId => $courseInfo) {
                $courseInfoWithType = $deptCourses['department']['courses'][$deptId];
                foreach ($courseInfoWithType as $key => $courseDetails) {
                    foreach ($courseDetails as $courseId => $courseInfoDetail) {
                        if($courseInfoDetail['instituteType'] == 'Department') {
                            $deptIds[] = $deptId;
                        } else if($courseInfoDetail['instituteType'] == 'Department_Virtual') {
                            $virtualdeptIds[] = $deptId;
                        } 
                        break;  
                    }
                    break;
                }
            }
          $data['departmentIds'] = $deptIds;
          $data['virtualdepartmentIds'] = $virtualdeptIds;
        } else {
          $deptIds = array_keys($deptCourses['department']['courses']);
          $data['departmentIds'] = $deptIds;
        }
    	$data['courseIds'] = $deptCourses['department']['course_ids'];
		return $data;
	}
}


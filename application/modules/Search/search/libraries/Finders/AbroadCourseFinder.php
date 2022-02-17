<?php

class AbroadCourseFinder {

        private $_ci;
        private $listingBuilder;
    
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility", "", true);
		$this->_ci->load->model("listing/abroadcoursemodel", "", true);
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
	}
        
        public function getData($id = null) {
		if($id == null){
			return false;
		}
		$listingRepos = $this->listingBuilder->getAbroadCourseRepository();
		$listingRepos->disableCaching();
		$abroadCourseDataObject = $listingRepos->find($id);
		$this->abroadCourseDataObject = $abroadCourseDataObject;
		$abroadCourseFlag = ($abroadCourseDataObject instanceof AbroadCourse) ? 1:0;
		if(empty($abroadCourseFlag)){
				return NULL;
		}
		$universityData = array();
		$universityFinder = SearchBuilder::getFinder('university');
		$universityData = $universityFinder->getData($this->getUniversityId());
		$abroadCourseDataForIndex = $this->preprocessRawData($abroadCourseDataObject, $universityData);
		
//		$dataSufficientFlag = $this->isDataSufficient($abroadCourseDataForIndex);
//		if(!$dataSufficientFlag){
//			$abroadCourseDataForIndex = false;
//		}
		return $abroadCourseDataForIndex;
	}
        
        public function preprocessRawData($abroadCourseDataObject = null, $universityData = null){
                $abroadCourse['sa_course_id'] = $this->getId($abroadCourseDataObject);
                $abroadCourse['sa_course_name'] = $this->getName($abroadCourseDataObject);
                $abroadCourse['sa_course_uni_name'] = $this->getUniversityName($abroadCourseDataObject);
                $abroadCourse['sa_course_uni_id'] = $this->getUniversityId();
                $abroadCourse['sa_course_insti_id'] = $this->getInstituteId();
                $abroadCourse['sa_course_insti_name'] = $this->getInstituteName($abroadCourseDataObject);
                $abroadCourse['sa_course_type']  = $this->getCourseType($abroadCourseDataObject);
                $abroadCourse['sa_course_level'] 	= $this->getCourseLevelValue($abroadCourseDataObject);
                $abroadCourse['sa_course_level_1'] 	= $this->getCourseLevel1Value($abroadCourseDataObject);
                $abroadCourse['sa_course_level_2'] 	= $this->getCourseLevel2Value($abroadCourseDataObject);
                $abroadCourse['sa_course_order']     = $this->getCourseOrder($abroadCourseDataObject);
                $abroadCourse['sa_course_description'] = $this->getCourseDescription($abroadCourseDataObject);
                $abroadCourse['sa_course_ldb_id'] = $this->getLDBCourseId($abroadCourseDataObject);
                $abroadCourse['sa_course_pack_type'] = $this->getCoursePackType($abroadCourseDataObject);
                $abroadCourse['sa_course_attributes'] = $this->getCourseAttributeList($abroadCourseDataObject);
                $abroadCourse['sa_course_scholarship'] = $this->getScholarshipInfo($abroadCourseDataObject);
                $abroadCourse['sa_course_seo_url'] = $this->getURL($abroadCourseDataObject);
                $abroadCourse['sa_course_desired'] = $this->getDesiredCourse($abroadCourseDataObject);
                $category = $this->getCourseCategory($abroadCourseDataObject);
                $abroadCourse['sa_course_parent_category'] = $category['parent'];
                $abroadCourse['sa_course_subcategory'] = $category['subParent'];
                $abroadCourse['sa_course_eligibility_exams']    = $this->getCourseEligibility($abroadCourseDataObject, 'required');
                $abroadCourse['sa_course_room_board']     = $this->getRoomBoard($abroadCourseDataObject);
                $abroadCourse['sa_course_insurance']      = $this->getInsurance($abroadCourseDataObject);
                $abroadCourse['sa_course_transportation'] = $this->getTransportation($abroadCourseDataObject);
                $abroadCourse['sa_course_custom_fees']   = $this->getCustomFees($abroadCourseDataObject);
				$abroadCourse['sa_course_univ_seo_url']  = $universityData['university_seo_url'];


                $abroadCourse['facetype'] = 'abroadcourse';
                $abroadCourseDuration = $this->getDuration($abroadCourseDataObject);
                $abroadCourseLdbDetails = $this->getLdbCourseDetailList($abroadCourse['sa_course_ldb_id']);
                $abroadCourseLocation = $this->getMainLocation($abroadCourseDataObject);
                $abroadCourseJobProfile = $this->getJobProfile($abroadCourseDataObject);
                $abroadCourseFees = $this->getFees($abroadCourseDataObject);
                
                $abroadCourse['unique_id'] = 'abroadcourse' . $abroadCourse['sa_course_id'] . '_' . $abroadCourseLocation['sa_course_city_id'];
                $abroadCourse = array_merge($abroadCourse, $abroadCourseDuration, $abroadCourseLocation, $abroadCourseJobProfile, $abroadCourseFees);
                
                return $abroadCourse;
        }
        
        
        private function getId($abroadCourseDataObject = null) {
                $id = -1;
                $id = $abroadCourseDataObject->getId();
                return $id;
        }
        private function getName($abroadCourseDataObject = null){
                $name = '';
                $name = $abroadCourseDataObject->getName();
                return $name;          
        }
        
        private function getUniversityName($abroadCourseDataObject = null){
                $university = '';
                $university = $abroadCourseDataObject->getUniversityName();
                return $university;
        }
        
        public function getCourseCategory($abroadCourseDataObject = null){
                $categories = Modules::run('listing/abroadListings/getCategoryOfAbroadCourse',$this->getId($abroadCourseDataObject) );
                $parentCategoryId = $categories['categoryId'];
                $subCategoryId = $categories['subcategoryId'];
                $this->_ci->load->builder('CategoryBuilder','categoryList');
                $builderObj	= new CategoryBuilder;
                $repoObj 	= $builderObj->getCategoryRepository();
                $parentCategory = $repoObj->find($parentCategoryId)->getName();
                $subParentCategory = $repoObj->find($subCategoryId)->getName();
                return array('parent' => $parentCategory , 'subParent'=>$subParentCategory);
        }
        
        public function getUniversityId(){
                $universityId = '';
                $universityId = $this->abroadCourseDataObject->getUniversityId();
                return $universityId;
        }
        
        public function getInstituteId(){
                $instituteId = '';
                $instituteId = $this->abroadCourseDataObject->getInstId();
                return $instituteId;
        }
        
        private function getScholarshipInfo($abroadCourseDataObject = null){
                $scholarshipInfo = 0;
                $scholarshipInfo = $abroadCourseDataObject->isOfferingScholarship();
                if(empty($scholarshipInfo)){
                        $scholarshipInfo = 0;
                }
                return $scholarshipInfo;
        }
        

        
        private function getInstituteName($abroadCourseDataObject = null){
                $institute = '';
                $institute = $abroadCourseDataObject->getInstituteName();
                return $institute;
        }
        
        private function getDuration($abroadCourseDataObject = null) {
                $durationInfo = array();
                $durationInfo['sa_course_duration_unit'] = $abroadCourseDataObject->getDuration()->getDurationUnit() != null ? $abroadCourseDataObject->getDuration()->getDurationUnit() :'';
                $durationInfo['sa_course_duration_value'] = $abroadCourseDataObject->getDuration()->getDurationValue() != null ? $abroadCourseDataObject->getDuration()->getDurationValue() :'';
                return $durationInfo;
        }
        
        private function getInstitute($abroadCourseDataObject = null){
                $institute = '';
                $institute = $abroadCourseDataObject->getInstitute();
                return $institute;
        }
        
        private function getRequestBrochure($abroadCourseDataObject = null){
                $link = '';
                $link = $abroadCourseDataObject->getRequestBrochure();
                return $link;
        }
        
        private function getLastUpdatedDate($abroadCourseDataObject = null){
                $updatedate = '';
                $updatedate = $abroadCourseDataObject->getLastUpdatedDate();
                return $updatedate;
        }
        
//        private function getExpiryDate($abroadCourseDataObject = null){
//            $expiryDate = '';
//            $expiryDate = $abroadCourseDataObject->getExpiryDate();
//            return $expiryDate;
//        }
        
        private function getClientId($abroadCourseDataObject = null){
                $clientid = '';
                $clientid = $abroadCourseDataObject->getClientId();
                return $clientid;
        }
        
        private function getURL($abroadCourseDataObject = null){
                $url = '';
                $url = $abroadCourseDataObject->getURL();
                return $url;
        }
        
        private function getCourseOrder($abroadCourseDataObject = null){
		$course_order = "";
		$course_order = $abroadCourseDataObject->getOrder();
		return trim($course_order);
	}
        
        private function getDesiredCourse($abroadCourseDataObject = null){
            $abroadCommonLib 	= $this->_ci->load->library('listingPosting/AbroadCommonLib');
            $desiredCourseData = $abroadCommonLib->getAbroadMainLDBCourses();
            $desiredCourseIds = array_column($desiredCourseData,"SpecializationId");
            $desiredCourseName = array_column($desiredCourseIds,"CourseName");
            $popularCourses = array_combine($desiredCourseIds,$desiredCourseName);
            $desiredCourseId = $abroadCourseDataObject->getDesiredCourseId();
            $desiredCourse = $popularCourses[$desiredCourseId];
            return $desiredCourse;
        }
        
        private function getMainLocation($abroadCourseDataObject = null){
                $mainlocationObject =  $abroadCourseDataObject->getMainLocation();
                $locationInfo = $this->getCourseLocationInformation($mainlocationObject);
                return $locationInfo;
        }
        
        private function getCourseLocationInformation($locationObject){
		$locationInfo['sa_course_locality_id'] = $locationObject->getLocality()->getId() != null ? $locationObject->getLocality()->getId() : "";
		$locationInfo['sa_course_locality_name'] = $locationObject->getLocality()->getName() != null ? $locationObject->getLocality()->getName() : "";
		
		$locationInfo['sa_course_zone_id'] = $locationObject->getZone()->getId() != null ? $locationObject->getZone()->getId() : "";
		$locationInfo['sa_course_zone_name'] = $locationObject->getZone()->getName() != null ? $locationObject->getZone()->getName() : "";
		
		$locationInfo['sa_course_city_id'] = $locationObject->getCity()->getId() != null ? $locationObject->getCity()->getId() : "";
		$locationInfo['sa_course_city_name'] = $locationObject->getCity()->getName() != null ? $locationObject->getCity()->getName() : "";
		
		$locationInfo['sa_course_state_id'] = $locationObject->getState()->getId() != null ? $locationObject->getState()->getId() : "";
		$locationInfo['sa_course_state_name'] = $locationObject->getState()->getName() != null ? $locationObject->getState()->getName() : "";
		
		$locationInfo['sa_course_country_id'] = $locationObject->getCountry()->getId() != null ? $locationObject->getCountry()->getId() : "";
		$locationInfo['sa_course_country_name'] = $locationObject->getCountry()->getName() != null ? $locationObject->getCountry()->getName() : "";
		
		$locationInfo['sa_course_institute_location_id'] = $locationObject->getLocationId() != null ? $locationObject->getLocationId() : "";
		
		
		$locationAttributes = array();
		$courseLocationAttributes = $locationObject->getAttributes();
		foreach($courseLocationAttributes as $attribute){
			$locationAttributes[$attribute->getName()] = $attribute->getValue() != null ? $attribute->getValue() : "";
		}
		$locationInfo['sa_course_location_attributes'] = json_encode($locationAttributes);
		
		$searchModel = new SearchModel();
		$continentInfo = $searchModel->getContinentForCountry($locationInfo['course_country_id']);
		$locationInfo = array_merge($locationInfo, $continentInfo);
		return $locationInfo;
	}
        
        private function getEligibilityExams($abroadCourseDataObject = null){
		$courseEligibility = array();
		foreach($abroadCourseDataObject->getEligibilityExams() as $exam){
			if($exam->getTypeOfMap() == $examMapType){
				array_push($courseEligibility, $exam->getName() . ',' . $exam->getCutOff());
			}
		}
		return $courseEligibility;
        }
        
        private function getRecruitingCompanies($abroadCourseDataObject = null){
                $companies = '';
                $companies = $abroadCourseDataObject->getRecruitingCompanies();
                return $companies;
        }
        
        private function getContactDetails($abroadCourseDataObject = null){
                $contactDetails = '';
                $contactDetails = $abroadCourseDataObject->getContactDetails();
                return $contactDetails;
        }
        
        private function getFees($abroadCourseDataObject = null){
            $fees = array();
//            $fees = $abroadCourseDataObject->getFees();
            $fees['sa_course_fees_value'] = $abroadCourseDataObject->getFees()->getValue() != null ? $abroadCourseDataObject->getFees()->getValue():'';
            $fees['sa_course_fees_currency'] = $abroadCourseDataObject->getFees()->getCurrency() != null ? $abroadCourseDataObject->getFees()->getCurrency():'';
            
            return $fees;
        }
        
        private function getCourseAttributeList($courseDataObject){
		$attributes = array();
		$attributesList = $courseDataObject->getAttributes();
		foreach($attributesList as $attribute){
			array_push($attributes, trim($attribute->getValue()));
		}
		return $attributes;
	}
       
        private function getCourseType($abroadCourseDataObject = null){
                $coursetype = '';
                $getCourseType = $abroadCourseDataObject->getCourseType();
                return $getCourseType;
        }
        
        private function getCourseLevelValue($abroadCourseDataObject = null) {
                $courselevel = '';
                $courselevel = $abroadCourseDataObject->getCourseLevelValue();
                return $courselevel;
        }
        
        private function getCourseLevel1Value($abroadCourseDataObject = null) {
                $courselevel1 = '';
                $courselevel1 = $abroadCourseDataObject->getCourseLevel1Value();
                return $courselevel1;
        }
        
        private function getCourseLevel2Value($abroadCourseDataObject = null) {
                $courselevel2 = '';
                $courselevel2 = $abroadCourseDataObject->getCourseLevel2Value();
                return $courselevel2;
        }
                   
        private function getCourseDescription($abroadCourseDataObject = null){
                $description = '';
                $decription = $abroadCourseDataObject->getCourseDescription();
                return $decription;
        }
        
        private function getJobProfile($abroadCourseDataObject = null){
                $jobprofile = '';
                $jobprofileObject = $abroadCourseDataObject->getJobProfile();
                $jobprofileInfo['sa_course_average_salary'] = $jobprofileObject->getAverageSalary() != null ? $jobprofileObject->getAverageSalary():'' ;
                $jobprofileInfo['sa_course_percentage_employed'] = $jobprofileObject->getPercentageEmployed() != null ? $jobprofileObject->getPercentageEmployed():'' ;
//                $jobprofileInfo['sa_course_currency'] = $jobprofileObject->getCurrencyEntity()->getName() != null ? $jobprofile->getCurrencyEntity()->getName():'' ;

                return $jobprofileInfo;
        }
        
        
        private function getLDBCourseId($abroadCourseDataObject = null) {
                $ldbcourseid = '';
                $ldbcourseid = $abroadCourseDataObject->getLDBCourseId();
                return $ldbcourseid;
        }
        
        private function getLdbCourseDetailList($ldb_id){
                $ListingClientObj = new Listing_client();
                $ldbDetails = $ListingClientObj->getLdbCourseDetailsForLdbId($ldb_id);
                return $ldbDetails;
	}
        
        private function getCourseEligibility($abroadCourseDataObject, $examMapType = "required"){
		$courseEligibility = array();
		foreach($abroadCourseDataObject->getEligibilityExams() as $exam){
                        array_push($courseEligibility, $exam->getName() . ',' . $exam->getCutOff());
		}
		return $courseEligibility;
	}
        
        private function getCoursePackType($abroadCourseDataObject = null){
                $pack_type = '';
                $pack_type = $abroadCourseDataObject->getCoursePackType();
                return $pack_type;
        }

        private function getRoomBoard($abroadCourseDataObject = null){
            $roomBoard = $abroadCourseDataObject->getRoomBoard();
            if(empty($roomBoard))
                $roomBoard = 0;

            return $roomBoard;
        }

        private function getInsurance($abroadCourseDataObject = null){
            $insurance = $abroadCourseDataObject->getInsurance();
            if(empty($insurance))
                $insurance = 0;

            return $insurance;
        }

        private function getTransportation($abroadCourseDataObject = null){
            $transportation =  $abroadCourseDataObject->getTransportation();
            if(empty($transportation))
                $transportation = 0;

            return $transportation;

        }

        private function getCustomFees($abroadCourseDataObject = null){
            $abroadCourseModel = new AbroadCourseModel();
            $customFees = $abroadCourseModel->getCourseCustomFees($abroadCourseDataObject->getId());
            if(empty($customFees))
                $customFees = 0;

            return $customFees;
        }

}


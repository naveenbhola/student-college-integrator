<?php

class Course
{
	private $course_id;
	private $courseTitle;
	private $course_type;
	private $course_level;
	private $course_level_1;
	private $course_level_2;
	private $course_order;

	private $institute_id;
	private $institute_name;
	private $city_id;
	private $city_name;
	private $country_id;
	private $country_name;

	private $listing_seo_url;
	private $listing_seo_title;
	private $listing_seo_description;
	private $listing_seo_keywords;
	
	private $easeOfImmigration;

	private $fees;
	private $duration;
	private $attributes;
	private $salient_features;
	private $exams;
	private $recruiting_companies;
	private $events;
	private $ranking;

	private $institute;
	private $course_description_attributes;
	private $locations;
	private $seats_general;
	private $seats_reserved;
	private $seats_management;
	private $cumulativeViewCount;
	private $pack_type;
	private $current_locations;
	private $seats_total;
	private $date_form_submission;
	private $date_result_declaration;
	private $date_course_comencement;
	private $last_modify_date;
	private $last_modifed_by;
	private $expiry_date;
	private $username;
	private $course_request_brochure_link; 
	private $naukriSalaryData; 
	private $useStoredSeoDataFlag;
	private $dominantSubcategory;
	private $ldbCourses;
	
	function __construct()
	{
	}
	
	
	public function getRequestBrochure() {
		return $this->course_request_brochure_link;
	}
	
	public function getLastUpdatedDate(){
		return $this->last_modify_date;
	}

	public function getLastModifiedBy(){
		return $this->last_modifed_by;
	}

        public function getExpiryDate(){
		return $this->expiry_date;
	}
	
        public function getClientId(){
		return $this->username;
	}
	
    public function getCurrentLocations() {
    	
    	return $this->current_locations;
    }
	
    public function getCurrentMainLocation()
	{
		foreach($this->current_locations as $location) {
			if($location->isHeadOffice()) {
				return $location;
			}
		}
		return reset($this->current_locations);
	}
	
    public function setCurrentLocations(CategoryPageRequest $request,$strict = false) {

    	$locations = $this->locations;
		$returnLocations = array();
		if(!$request){
			$this->current_locations = $locations;
			return;
		}
		foreach($locations as $location){
			if($request->getLocalityId() && $request->getLocalityId() > 0 && $strict){
					$tempId =  $location->getLocality()?$location->getLocality()->getId():0;
					if($request->getLocalityId() == $tempId){
						$returnLocations[] = $location;
					}
			}elseif($request->getCityId() && $request->getCityId() > 1){
				
					$tempId =  $location->getCity()?$location->getCity()->getId():0;
					$virtualId =  $location->getCity()?$location->getCity()->getVirtualCityId():0;
					if(($request->getCityId()  == $tempId) || ($request->getCityId()  == $virtualId)){
						$returnLocations[] = $location;
					}
					
			}elseif($request->getStateId() && $request->getStateId() > 1){
				
					$tempId =  $location->getState()?$location->getState()->getId():0;
					if($request->getStateId()  == $tempId){
						$returnLocations[] = $location;
					}
				
			}elseif($request->getCountryId() && $request->getCountryId() > 1){
				
					$tempId =  $location->getCountry()?$location->getCountry()->getId():0;
					if($request->getCountryId()  == $tempId){
						$returnLocations[] = $location;
					}
				
			}else{
				$returnLocations[] = $location;
			}
		}
		$this->current_locations = $returnLocations;
    }
    
	public function addViewCount(ListingViewCount $count) {

		$this->cumulativeViewCount = $count;
	}
	
	public function getTotalSeats() {
		
		return $this->seats_total;
	}
	
	public function getManagementSeats() {
		
		return $this->seats_management;
	}
	
	public function getGeneralSeats() {
	
		return $this->seats_general;
	}
	
	public function getReservedSeats() {
	
	    return $this->seats_reserved;
	}

	public function addLocation(InstituteLocation $location)
	{
		$this->locations[$location->getLocationId()] = $location;
	}

	public function setDescriptionAttribute(CourseDescriptionAttribute $attribute) {
		$this->course_description_attributes[] = $attribute;
	}
	
	public function setAdditionalURLParams($additionalURLParams)
	{
		$this->additionalURLParams = $additionalURLParams;
	}
	
	public function getURL()
	{
		if($this->listing_seo_url) {
			return $this->listing_seo_url.$this->additionalURLParams;
		}
		else {
			$locationArray = array();
			$locationArray[0] = $this->getMainLocation()->getCity()->getName()."-".$this->getMainLocation()->getCountry()->getName();

			$optionalArgs = array();
			$optionalArgs['location'] = $locationArray;
			$optionalArgs['institute'] = $this->institute_name;
			$dominantSubcatObj = $this->getDominantSubcategory();
			if(!empty($dominantSubcatObj) && is_object($dominantSubcatObj)) {
				$optionalArgs['dominantSubcat'] = $dominantSubcatObj->getSeoUrlDirectoryName();
			}
			return getSeoUrl($this->course_id,'course',$this->courseTitle,$optionalArgs,'old').$this->additionalURLParams;
		}
	}
	
	public function getMainLocation()
	{
		foreach($this->locations as $location) {
			if($location->isHeadOffice()) {
				return $location;
			}
		}
		return reset($this->locations);
		
	}

	public function getMetaData(){
		return array(
					 'seoTitle' => $this->listing_seo_title,
					 'seoKeywords' => $this->listing_seo_keywords,
					 'seoDescription' => $this->listing_seo_description
					 );
	}
	
	
	public function getCourseLevel()
	{
		$mainCourseLevel = trim($this->course_level);
		if($mainCourseLevel == 'Dual Degree') {
			return $mainCourseLevel;
		}

		$courseLevel_1 = trim($this->course_level_1);
			
		if($courseLevel_1) {
			if($courseLevel_1 == 'Post Graduate Diploma') {
				$mainCourseLevel = 'Post Graduate Diploma';
			}
			else if($courseLevel_1 != $mainCourseLevel) {
				$mainCourseLevel = $courseLevel_1.' '.$mainCourseLevel;
			}
		}

		return $mainCourseLevel;
	}

	public function getAffiliations()
	{
		$affiliations = array();

		if($this->_getAttributeValue('AffiliatedToIndianUni') == 'yes') {
			$affiliations[] = array('indian',$this->_getAttributeValue('AffiliatedToIndianUniName'));
		}
		if($this->_getAttributeValue('AffiliatedToForeignUni') == 'yes') {
			$affiliations[] = array('foreign',$this->_getAttributeValue('AffiliatedToForeignUniName'));
		}
		if($this->_getAttributeValue('AffiliatedToDeemedUni') == 'yes') {
			$affiliations[] = array('deemed');
		}
		if($this->_getAttributeValue('AffiliatedToAutonomous') == 'yes') {
			$affiliations[] = array('autonomous');
		}

		return $affiliations;
	}

	public function getApprovals()
	{
		$approvals = array();

		if($this->_getAttributeValue('AICTEStatus') == 'yes') {
			$approvals[] = 'aicte';
		}
		if($this->_getAttributeValue('UGCStatus') == 'yes') {
			$approvals[] = 'ugc';
		}
		if($this->_getAttributeValue('DECStatus') == 'yes') {
			$approvals[] = 'dec';
		}

		return $approvals;
	}
	
	public function getAccredited()
	{
		return $this->_getAttributeValue('AccreditedBy');
	}
	
	public function getOtherEligibilityCriteria()
	{
		return $this->_getAttributeValue('otherEligibilityCriteria');
	}
	public function isAICTEApproved()
	{
		return $this->_getAttributeValue('AICTEStatus');
	}

	public function isUGCRecognised()
	{
		return $this->_getAttributeValue('UGCStatus');
	}

	public function isDECApproved()
	{
		return $this->_getAttributeValue('DECStatus');
	}

	private function _getAttributeValue($attributeName)
	{
		return isset($this->attributes[$attributeName])?$this->attributes[$attributeName]->getValue():FALSE;
	}

	public function getEligibilityExams()
	{
		$exams = array();
		if(is_array($this->exams) && count($this->exams)) {
			foreach($this->exams as $exam) {
				if($exam->getId()) {
					$exams[] = $exam;
				}
			}
		}
		return $exams;
	}
	
	public function removeEligibilityExams(Exam $exam)
	{
		
		if(($key=array_search($exam,$this->exams))!== FALSE)
		{
			unset($this->exams[$key]);
		}
		
	}

	public function getClassTimings()
	{
		$classTimings = array();
		$classTimingAttributes = array('morningClasses','eveningClasses','weekendClasses');
		foreach($classTimingAttributes as $attr) {
			if($this->_getAttributeValue($attr) == 'yes') {
				$classTimings[] = $attr;
			}
		}
		return $classTimings;
	}

	public function getAverageSalary()
	{
		return (int) $this->_getAttributeValue('SalaryAvg');
	}
	
	public function getSalary()
	{
		return array("avg" => (int) $this->_getAttributeValue('SalaryAvg'),
					 "min" => (int) $this->_getAttributeValue('SalaryMin'),
					 "max" => (int) $this->_getAttributeValue('SalaryMax'),
					 "currency" => (string)$this->_getAttributeValue('SalaryCurrency'));
	}

	 
	public function isSalaryTypeExist($salaryType)
	{
		$isSalaryExist = false;
		if (! empty ( $salaryType )) {
			$salaryVal = $this->_getAttributeValue ( $salaryType );
			$isSalaryExist = empty ($salaryVal);
		} else {
			$SalaryAvg = $this->_getAttributeValue ( 'SalaryAvg' );
			$SalaryMin = $this->_getAttributeValue ( 'SalaryMin' );
			$SalaryMax = $this->_getAttributeValue ( 'SalaryMax' );
			$isSalaryExist = ! empty ($SalaryAvg) || ! empty ($SalaryMin) || ! empty ($SalaryMax);
		}
		return $isSalaryExist;
	}
	
	public function hasRecruitingCompanies(){
		$recruiters = $this->getRecruitingCompanies();
		if(count($recruiters)>0 && $recruiters[0]->getName()!=""){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getDateOfCommencement()
	{
		foreach($this->events as $event) {
			if($event->isCourseCommencement()) {
				return $event->getStartDate();
			}
		}
	}

	public function getRecruitingCompanies()
	{
		return $this->recruiting_companies;
	}

	/*
	 * Course Features
	 */

	public function getSalientFeatures($limit=0)
	{
		$features = array();

		if(is_array($this->salient_features) && count($this->salient_features) >0) {
			foreach($this->salient_features as $feature) {
				if($feature->getName()) {
					$features[] = $feature;
				}
			}
		}

		if(intval($limit) > 0){
			$features = array_slice($features,0,$limit);
		}

		return $features;
	}

	public function providesFreeLaptop()
	{
		return $this->_getSalientFeature('freeLaptop');
	}

	public function offersForeignTravel()
	{
		return $this->_getSalientFeature('foreignStudy','yes');
	}

	public function offersForeignExchange()
	{
		return $this->_getSalientFeature('foreignStudy','no');
	}

	public function getJobAssurance()
	{
		return $this->_getSalientFeature('jobAssurance');
	}

	public function offersDualDegree()
	{
		return $this->_getSalientFeature('dualDegree');
	}

	public function providesHostelAccomodation()
	{
		return $this->_getSalientFeature('hostel');
	}

	public function providesTransportFacility()
	{
		return $this->_getSalientFeature('transport');
	}

	public function getFreeTrainingProgram()
	{
		return $this->_getSalientFeature('freeTraining');
	}

	public function hasWifiCampus()
	{
		return $this->_getSalientFeature('wifi');
	}

	public function hasACCampus()
	{
		return $this->_getSalientFeature('acCampus');
	}

	private function _getSalientFeature($featureName,$featureValue = NULL)
	{
		foreach($this->salient_features as $feature) {
			if($feature->getName() == $featureName) {
				if($featureValue) {
					if($feature->getValue() == $featureValue) {
						return $feature;
					}
				}
				else {
					return $feature;
				}
			}
		}
	}

	public function easesImmigration()
	{
		return $this->easeOfImmigration?TRUE:FALSE;
	}

	public function getRanking()
	{
		return $this->ranking;
	}

	/*
	 * Setters
	 */

	public function addAttribute(CourseAttribute $attribute)
	{
		$this->attributes[$attribute->getName()] = $attribute;
	}

	public function addSalientFeature(SalientFeature $salient_feature)
	{
		$this->salient_features[] = $salient_feature;
	}

	public function addExam(Exam $exam)
	{
		$this->exams[] = $exam;
	}

	public function addRecruitingCompany(RecruitingCompany $company)
	{
		$this->recruiting_companies[] = $company;
	}

	public function addEvent(Event $event)
	{
		$this->events[] = $event;
	}

	public function addLdbCourse($ldbCourseId) {
		$this->ldbCourses[] = $ldbCourseId;
	}

	public function getLdbCourses() {
		return $this->ldbCourses;
	}

	public function getDominantSubcategory() {
		return $this->dominantSubcategory;
	}

	public function setDominantSubcategory(Category $subcategory) {
		$this->dominantSubcategory = $subcategory;
	}

	public function setInstitute(Institute $institute)
	{
		$this->institute = $institute;
	}

	public function setFees(CourseFees $fees)
	{
		$this->fees = $fees;
	}

	public function setDuration(CourseDuration $duration)
	{
		$this->duration = $duration;
	}

	public function setRanking(Ranking $ranking)
	{
		$this->ranking = $ranking;
	}

	/*
	 * Getters
	 */
	public function getInstId() {
		return $this->institute_id;
	}

	public function getId()
	{
		return $this->course_id;
	}

	public function getName()
	{
		return $this->courseTitle;
	}

	public function getInstitute()
	{
		return $this->institute;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	public function getFees($location_id = 0)
	{
		if($location_id > 0 && array_key_exists($location_id, $this->locations)) {
			$attributes = $this->locations[$location_id]->getAttributes();
			if($attributes['Course Fee Value']){
				$fee_value = trim($attributes['Course Fee Value']->getValue());
				if(!is_null($fee_value) && !empty($fee_value)) {
					$new_fees_object = new CourseFees();
					$new_fees_object->fees_value = $fee_value;
					$new_fees_object->fees_unit =  $attributes['Course Fee Unit']->getValue();
					// LF-4327 start
					if($attributes['Show Fee Disclaimer']){
						$fee_disclaimer_text = $attributes['Show Fee Disclaimer']->getValue();
						if(!is_null($fee_disclaimer_text) && !empty($fee_disclaimer_text)) {
							$new_fees_object->setFeeDisclaimer($fee_disclaimer_text);
						}
					}
					// LF-4327 end
					return $new_fees_object;
				}
			}
		}
		return $this->fees;

	}

	public function getCourseType()
	{
		return ucwords(trim($this->course_type));
	}

	public function getOrder()
	{
		return $this->course_order;
	}

	public function getDescriptionAttributes() {
		return $this->course_description_attributes;
	}

	public function getLocations()
	{
		return $this->locations;
	}

	public function getCumulativeViewCount() {

		return $this->cumulativeViewCount;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}
	
	public function getCourseLevelValue()
	{
		return $this->course_level;	
	}
	
	public function getCourseLevel1Value()
	{
		return $this->course_level_1;	
	}
	
	public function getCourseLevel2Value()
	{
		return $this->course_level_2;	
	}
	
	public function getCoursePackType(){
		return $this->pack_type;
	}
	
	public function isPaid() {

		return ($this->pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
	}

        public function getDateOfFormSubmission($location_id = 0) {
		if($location_id > 0) {
			$attributes = $this->_getAttributesForAsingleLocation($location_id);
                        $required_attribute = $attributes['date_form_submission'];
                        if(!empty($required_attribute)) {
				return $required_attribute->getValue();
                        } else {
				return $this->date_form_submission;
                        }
                } 
                return $this->date_form_submission;
        }

        public function getDateOfResultDeclaration($location_id = 0) {
		if($location_id > 0) {
                        $attributes = $this->_getAttributesForAsingleLocation($location_id);
                        $required_attribute = $attributes['date_result_declaration'];
                        if(!empty($required_attribute)) {
				return $required_attribute->getValue();
                        } else {
				return $this->date_result_declaration;
                        }
                } 
                return $this->date_result_declaration;
        }

        public function getDateOfCourseComencement($location_id = 0) {
		if($location_id > 0) {
			$attributes = $this->_getAttributesForAsingleLocation($location_id);
                        $required_attribute = $attributes['date_course_comencement'];
                        if(!empty($required_attribute)) {
				return $required_attribute->getValue();
                        } else {
				return $this->date_course_comencement;
                        }
                }
                return $this->date_course_comencement;
        }

        private function _getAttributesForAsingleLocation($location_id) {
		$locations = $this->locations;
                $required_location = $locations[$location_id];
                $attributes = $required_location->getAttributes();
                return $attributes;
        }
	
	public function getInstituteName(){
		
		return $this->institute_name;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	public function getViewCount()
	{
		return $this->cumulativeViewCount;
	}
	
	function isCourseMultilocation($isJsMultilocationInclusion = 1) {
                if(count($this->getLocations()) > 1)
                {
                        return 'true';
                }

                if(!$isJsMultilocationInclusion) {
                        return 'false';
                }

		global $listings_with_localities;
		if(in_array($this->getId(), $listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
			return 'true';
		} else if(in_array($this->getInstId(), $listings_with_localities['SMU'])) {
			return 'true';
		} else if(in_array($this->getInstId(), $listings_with_localities['UTS'])) {
			return 'true';
		} else if(in_array($this->getInstId(), $listings_with_localities['MAAC'])) {
			return 'true';
		} else if(in_array($this->getInstId(), $listings_with_localities['ICA'])) {
			return 'true';
		}else{
			return 'false';	
		}
		return 'false';
	}
        
        public function getNaukriSalaryData(){
		return $this->naukriSalaryData;
	}
	
	public function setNaukriSalaryData($data){
		$this->naukriSalaryData = $data;
	}
        
    public function getSeoDataFlag(){
		return ($this->useStoredSeoDataFlag != '') ? $this->useStoredSeoDataFlag : 0;
	}

	public function getCityName() {
		return $this->city_name;
	}
}

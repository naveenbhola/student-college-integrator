<?php

class CourseDocument {
	
	/*  Course general attributes
	  * Class variable names are kept same as schema field names
	  *
	*/
	private $course_id;
	
	private $course_title;
	
	private $course_type;
	
	private $course_level;
	
	private $course_level_1;

	private $course_level_2;
	
	private $course_head_office;
	
	private $course_seo_url;
	
	private $course_pack_type;
	
	private $facetype;
	
	private $unique_id;

	private $course_duration_normalized;
	
	private $course_type_cluster;

	private $course_level_cluster;
	
	private $course_location_cluster;
	
	private $course_institute_location_id;
	
	private $course_document_score;
	
	private $course_order;
	
	private $additionalURLParams;
	
	/**
	 * Other entity objects that are part of one course document
	 */
	private $course_parent_category_entity;
	
	private $course_child_category_entity;
	
	private $course_duration_entity;
	
	private $course_fees_entity;
	
	private $course_attribute_entity;
	
	private $course_salient_feature_entity;
	
	private $course_required_exam_entity;
	
	private $course_testprep_exam_entity;
	
	private $course_view_count_entity;
	
	private $course_ldb_entity;
	
	private $course_location_entity;
	
	private $course_other_location_entity;
	
	private $course_institute_entity;
	
	private $course_aof_exams_accepted;
	
	private $course_aof_fee;
	
	private $course_aof_last_date;
	
	private $course_aof_min_qualification;
	
	private $course_aof_externalurl;
	
	private $course_featured_bmskey;
	
	private $course_banner_bmskey;
	
	public function __construct(){
		
	}
	
	/**
	 * Setters for other entities that are part of CourseDocument
	 *
	*/
	
	public function setCourseAOFFees($fees){
		$this->course_aof_fee = $fees;
	}
	
	public function setCourseAOFLastDate($last_date){
		$this->course_aof_last_date = $last_date;
	}
	
	public function setCourseAOFExams($exams){
		$this->course_aof_exams_accepted = $exams;
	}
	
	public function setCourseAOFMinQualification($minQualificationStr){
		$this->course_aof_min_qualification = $minQualificationStr;
	}
	
	
	public function setCourseDurationEntity(CourseDuration $courseDurationEntity){
		if(get_class($courseDurationEntity) == "CourseDuration"){
			$this->course_duration_entity = $courseDurationEntity;	
		}
	}
	
	
	public function setAdditionalURLParams($additionalURLParams) {
		$this->additionalURLParams = $additionalURLParams;
	}
	
	
	public function setCourseFeesEntity(CourseFees $courseFeesEntity){
		if(get_class($courseFeesEntity) == "CourseFees"){
			$this->course_fees_entity = $courseFeesEntity;	
		}
	}
	
	public function setCourseAttributeEntity(CourseAttribute $courseAttributeEntity){
		if(get_class($courseAttributeEntity) == "CourseAttribute"){
			$this->course_attribute_entity[] = $courseAttributeEntity;
		}
	}
	
	public function setCourseSalientFeatureEntity(SalientFeature $courseSalientFeatureEntity){
		if(get_class($courseSalientFeatureEntity) == "SalientFeature"){
			$this->course_salient_feature_entity[] = $courseSalientFeatureEntity;
		}
	}
	
	public function setCourseRequiredExamEntity(Exam $examEntity){
		if(get_class($examEntity) == "Exam"){
			$this->course_required_exam_entity[] = $examEntity;	
		}
	}
	
	public function setCourseTestPrepExamEntity(Exam $examEntity){
		if(get_class($examEntity) == "Exam"){
			$this->course_testprep_exam_entity[] = $examEntity;
		}
	}
	
	public function setCourseViewCountEntity(ListingViewCount $viewCountEntity){
		if(get_class($viewCountEntity) == "ListingViewCount"){
			$this->course_view_count_entity = $viewCountEntity;	
		}
	}
	
	public function setCourseLocationEntity($instituteLocationId, InstituteLocation $location){
		if(get_class($location) == "InstituteLocation"){
			$this->course_location_entity[$instituteLocationId] = $location;
		}
	}
	
	public function setCourseOtherLocationEntity($instituteLocationId, InstituteLocation $location){
		if(get_class($location) == "InstituteLocation"){
			$this->course_other_location_entity[$instituteLocationId] = $location;
		}
	}
	
	public function setCourseParentCategoryEntity($categoryId, Category $category){
		if(get_class($category) == "Category"){
			$this->course_parent_category_entity[$categoryId] = $category;
		}
	}
	
	public function setCourseChildCategoryEntity($categoryId, Category $category){
		if(get_class($category) == "Category"){
			$this->course_child_category_entity[$categoryId] = $category;
		}
	}
	
	public function setCourseInstituteEntity(Institute $institute){
		if(get_class($institute) == "Institute"){
			$this->course_institute_entity = $institute;
		}
	}
	
	public function getId(){
		return $this->course_id;
	}
	
	public function getInstitute(){
		return $this->course_institute_entity;
	}
	
	public function isStudyAbroadCourse(){
		$countryId = $this->getLocation()->getCountry()->getId();
		$studyAbroad = false;
		if($countryId != 2){
			$studyAbroad = true;
		}
		return $studyAbroad;
	}
	
	public function getLocation(){
		return $this->course_location_entity[$this->course_institute_location_id];	
	}
	
	public function getOtherLocations(){
		return $this->course_other_location_entity;
	}

        public function getAllLocations() {
		$location = $this->course_location_entity ? $this->course_location_entity : array();
		$other_locations = $this->course_other_location_entity ? $this->course_other_location_entity : array();	
		return array_merge($location,$other_locations);
	} 
	
	public function getLocationEntity(){
		return $this->course_location_entity;	
	}
	
	public function getViewCount(){
		return $this->course_view_count_entity->getCount();
	}
	
	public function getURL(){
		if($this->course_seo_url) {
			return $this->course_seo_url.$this->additionalURLParams;
		}
		else {
			$courseLocationEntity = $this->course_location_entity[$this->course_institute_location_id];
			$locationArray = array();
			$locationArray[0] = $courseLocationEntity->getCity()->getName()."-".$courseLocationEntity->getCountry()->getName();
			$optionalArgs = array();
			$optionalArgs['location'] = $locationArray;
			$optionalArgs['institute'] = $this->course_institute_entity->getName();
			return getSeoUrl($this->course_id,'course',$this->course_title,$optionalArgs,'old').$this->additionalURLParams;
		}
	}
	
	private function _getAttributeValue($paramAttributeName) {
		$attributeParamValue = FALSE;
		foreach($this->course_attribute_entity as $attribute){
			$attributeName = $attribute->getName();
			$attributeValue = $attribute->getValue();
			if($paramAttributeName == $attributeName){
				$attributeParamValue = $attributeValue;
				break;
			}
		}
		return $attributeParamValue;
	}
	
	public function getAffiliations() {
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

	public function getApprovals() {
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

	public function isAICTEApproved() {
		return $this->_getAttributeValue('AICTEStatus');
	}

	public function isUGCRecognised() {
		return $this->_getAttributeValue('UGCStatus');
	}

	public function isDECApproved() {
		return $this->_getAttributeValue('DECStatus');
	}

	public function getEligibilityExams() {
		$exams = array();
		if(is_array($this->course_required_exam_entity) && count($this->course_required_exam_entity)) {
			foreach($this->course_required_exam_entity as $exam) {
				$exams[] = $exam;
			}
		}
		return $exams;
	}

	public function getAverageSalary() {
		return (int) $this->_getAttributeValue('SalaryAvg');
	}
	
	public function getSalary() {
		return array("avg" => (int) $this->_getAttributeValue('SalaryAvg'),
					 "min" => (int) $this->_getAttributeValue('SalaryMin'),
					 "max" => (int) $this->_getAttributeValue('SalaryMax'));
	}

	/*
	 * Course Features
	 */

	/*
	 * CourseDocument getters
	 * Name of getters are camelCase of class variable names
	 */
	public function getCourseId(){
		return $this->course_id;
	}
	
	public function getCourseTitle(){
		return $this->course_title;
	}
	
	public function getName(){
		return $this->course_title;
	}
	
	public function getCourseLevel(){
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
				$mainCourseLevel = $mainCourseLevel;
				//$mainCourseLevel = $courseLevel_1.' '.$mainCourseLevel;
			}
		}
		return ucwords($mainCourseLevel);
	}
	
	public function getCourseLevel1(){
		return $this->course_level_1;
	}
	
	public function getCourseLevel2(){
		return $this->course_level_2;
	}
	
	public function getCourseHeadOffice(){
		return $this->course_head_office;
	}

	public function getCourseSeoUrl(){
		return $this->course_seo_url;
	}
	
	public function getFacetype(){
		return $this->facetype;
	}
	
	public function getUniqueId(){
		return $this->unique_id;
	}
	
	public function getCourseNormalizedDuration(){
		return $this->course_duration_normalized;
	}
	
	public function getCourseTypeCluster(){
		return $this->course_type_cluster;
	}
	
	public function getCourseLevelCluster(){
		return $this->course_level_cluster;
	}
	
	public function getCourseLocationCluster(){
		return $this->course_location_cluster;
	}
	
	public function getDocumentScore(){
		return $this->course_document_score;
	}
	
	public function getSalientFeatures($limit=0) {
		$features = array();
		if(is_array($this->course_salient_feature_entity) && count($this->course_salient_feature_entity) >0) {
			foreach($this->course_salient_feature_entity as $feature) {
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

	public function getDuration(){
		return $this->course_duration_entity;
	}

	public function getHeadOfficeLocation(){
		$returnLocationObject = false;
		$isHeadOfficeLocation = $this->getLocation()->isHeadOffice();
		if($isHeadOfficeLocation != true){
			foreach($this->course_other_location_entity as $instituteLocationId => $otherLocationObject){
				if($otherLocationObject->isHeadOffice()){
					$returnLocationObject = $otherLocationObject;
					break;
				}
			}
		} else {
			$returnLocationObject = $this->getLocation();
		}
		return $returnLocationObject;
	}
	
	public function getFees($location_id = 0) {
		if($location_id == 0){
			return $this->course_fees_entity;
		} else {
			if($location_id > 0 && array_key_exists($location_id, $this->course_other_location_entity)) {
				$attributes = $this->course_other_location_entity[$location_id]->getAttributes();
				if($attributes['Course Fee Value']){
					$fee_value = $attributes['Course Fee Value']->getValue();
					if(!is_null($fee_value) || !empty($fee_value)) {
						$new_fees_object = new CourseFees();
						$new_fees_object->fees_value = $fee_value;
						$new_fees_object->fees_unit =  $attributes['Course Fee Unit']->getValue();
						return $new_fees_object;
					}
				}
			}	
		}
		return $this->course_fees_entity;
	}
	
	public function getOAFFees(){
		$fees = "";
		if(!empty($this->course_aof_fee)){
			$fees = "INR " . $this->course_aof_fee;	
		}
		return $fees;
	}

	public function getOAFLastDate(){
		$date = "";
		if(!empty($this->course_aof_last_date)){
			$currentTimeStamp = time();
			$lastDate = strtotime($this->course_aof_last_date);
			if($lastDate >= $currentTimeStamp){
				$date = date("j M, Y", strtotime($this->course_aof_last_date));	
			}
		}
		return $date;
	}
	
	public function getOAFExams(){
		$examsAccepted = "";
		if(!empty($this->course_aof_exams_accepted)){
			$examsAccepted = $this->course_aof_exams_accepted;
		}
		return $examsAccepted;
	}
	
	
	public function getOAFMinQualification(){
		$qualification = "";
		if(!empty($this->course_aof_min_qualification)){
			$qualification = $this->course_aof_min_qualification;
		}
		return $qualification;
	}
	
	public function getOAFExternalURL(){
		$externalURL = "";
		if(!empty($this->course_aof_externalurl)){
			$externalURL = $this->course_aof_externalurl;
		}
		return $externalURL;
	}
	
	public function getCourseType() {
		return ucwords(trim($this->course_type));
	}

	public function getOrder() {
		return $this->course_order;
	}

	public function getCourseLevelValue() {
		return $this->course_level;	
	}
	
	public function getCourseLevel1Value(){
		return $this->course_level_1;	
	}
	
	public function getCourseLevel2Value(){
		return $this->course_level_2;	
	}
	
	public function isPaid(){
		return ($this->course_pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->course_pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID || $this->course_pack_type == GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
	}
	
	public function getParentCategory(){
		return $this->course_parent_category_entity;
	}
	
	public function getFeaturedBMSKey(){
		return $this->course_featured_bmskey;
	}
	
	public function getBannerBMSKey(){
		return $this->course_banner_bmskey;
	}

	//public function 
	public function __set($paramName, $paramValue){
		$this->$paramName = $paramValue;
	}

        function isCourseMultilocation($isJsMultilocationInclusion = 1) {
                if(count($this->getAllLocations()) > 1)
                {
                        return 'true';
                }

                if(!$isJsMultilocationInclusion) {
                        return 'false';
                }

                global $listings_with_localities;
                if(in_array($this->getId(), $listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
                        return 'true';
                } else if(in_array($this->getInstitute()->getId(), $listings_with_localities['SMU'])) {
                        return 'true';
                } else if(in_array($this->getInstitute()->getId(), $listings_with_localities['UTS'])) {
                        return 'true';
                } else if(in_array($this->getInstitute()->getId(), $listings_with_localities['MAAC'])) {
                        return 'true';
                } else if(in_array($this->getInstitute()->getId(), $listings_with_localities['ICA'])) {
                        return 'true';
                }else{
                        return 'false';
                }
                return 'false';
        }
	
}

?>

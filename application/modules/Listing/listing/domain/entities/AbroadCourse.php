<?php

class AbroadCourseOld
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
	
	private $university_id;
	private $university_name;
	private $university_type;

	private $listing_seo_url;
	private $listing_seo_title;
	private $listing_seo_description;
	private $listing_seo_keywords;
	
	private $fees;
	private $duration;
	private $attributes;
	
	private $exams;
	private $recruiting_companies;
	
	private $institute;
	
	private $pack_type;
	private $cumulativeViewCount;
	private $last_modify_date;
	private $expiry_date;
	private $username;
	private $course_request_brochure_link; 
    
	private $course_description;
	
	private $courseWebsite;
	private $courseDurationLink;
	private $applicationDeadlineLink;
	private $admissionWebsiteLink;
	private $feesPageLink;
	private $scholarshipLinkCourseLevel;
	private $scholarshipLinkDeptLevel;
	private $scholarshipLinkUniversityLevel;
	private $facultyInfoLink;
	private $alumniInfoLink;
	private $faqLink;
	
	private $startDates = array();
	
	private $classProfile;
	private $jobProfile;
	private $desiredCourseId;
	private $ldbCourseId;
	
	private $courseCategory;  // Purpose : to make category filter on category pages, note :this property have to set manually before use
	private $courseSubCategory;
	
	private $customFees;
	private $transportation;
	private $insurance;
	private $roomBoard;

	private $applicationDetailId;
	private $isRmcEnabled;

	
	function __construct()
	{
	}	
	
	public function getRequestBrochure()
	{
                if(!empty($this->course_request_brochure_link)){
                    return MEDIAHOSTURL.$this->course_request_brochure_link;
                }
                return;
	}
	
	public function getLastUpdatedDate()
	{
		return $this->last_modify_date;
	}

    public function getExpiryDate()
	{
		return $this->expiry_date;
	}
	
    public function getClientId()
	{
		return $this->username;
	}
	
    public function setAdditionalURLParams($additionalURLParams)
	{
		$this->additionalURLParams = $additionalURLParams;
	}
	
	public function getURL()
	{
		if(!empty($this->listing_seo_url)){
                    return SHIKSHA_STUDYABROAD_HOME.$this->listing_seo_url;
                }
		return;
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

	public function getMetaData()
	{
		$courseName = htmlentities($this->getName());
		$universityName = htmlentities($this->getUniversityName());
		$countryName = htmlentities($this->getMainLocation()->getCountry()->getName());
	
		$this->listing_seo_title 	= ($this->listing_seo_title 	  == ""? $courseName." from ".$universityName.", ".$countryName." | Shiksha.com" :$this->listing_seo_title 	);
		$this->listing_seo_description 	= ($this->listing_seo_description == ""? "Get all information about ".$courseName." from ".$universityName.", ".$countryName." like fees structure, eligibility criteria, syllabus, qualification, course duration etc. on Shiksha.com" :$this->listing_seo_description);
		
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

	public function hasRecruitingCompanies()
	{
		$recruiters = $this->getRecruitingCompanies();
		if(count($recruiters)>0 && $recruiters[0]->getName()!=""){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getRecruitingCompanies()
	{
		return $this->recruiting_companies;
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
	
	public function setClassProfile(ClassProfile $classProfile)
	{
		$this->classProfile = $classProfile;
	}
	
	public function setJobProfile(JobProfile $jobProfile)
	{
		$this->jobProfile = $jobProfile;
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

	public function getFees()
	{
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
	
	public function getCoursePackType()
	{
		return $this->pack_type;
	}
	
	public function isPaid()
	{
		return ($this->pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
	}

	public function getViewCount()
	{
		return $this->cumulativeViewCount;
	}
	public function getInstituteName()
	{
		return $this->institute_name;
	}
	
	public function isDirectlyAssociatedWithUniversity()
	{
		return $this->university_type == 'institute' ? TRUE : FALSE;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	public function addLocation(InstituteLocation $location)
	{
		$this->locations[$location->getLocationId()] = $location;
	}
	
	public function getCourseDescription()
	{
		return $this->course_description;
	}

	public function getUniversityType(){
		return $this->university_type;
	}
	public function getUniversityId(){
		return $this->university_id;
	}
	public function getUniversityName(){
		return $this->university_name;
	}

	public function getJobProfile()
	{
		return $this->jobProfile;
	}
	
	public function setScholarshipDescription($description)
	{
		$this->scholarshipDescription = $description;
	}
	
	public function getScholarshipDescription()
	{
		return $this->scholarshipDescription;
	}
	
	public function getScholarshipEligibility()
	{
		return $this->scholarshipEligibility;
	}
	
	public function setScholarshipEligibility($eligibility)
	{
		$this->scholarshipEligibility = $eligibility;
	}
	
	public function getScholarshipDeadLine()
	{
		return $this->scholarshipDeadLine;
	}
	
	public function setScholarshipDeadLine($deadLine)
	{
		$this->scholarshipDeadLine = $deadLine;
	}
	
	public function getScholarshipAmount()
	{
		return $this->scholarshipAmount;
	}
	
	public function setScholarshipAmount($amount)
	{
		$this->scholarshipAmount = $amount;
	}
	
	public function getScholarshipCurrency()
	{
		return $this->scholarshipCurrency;
	}
	
	public function setScholarshipCurrency($currency)
	{
		$this->scholarshipCurrency = $currency;
	}
	
	public function setScholarshipCurrencyCode($currencyCode)
	{
		$this->scholarshipCurrencyCode = $currencyCode;
	}
	
	public function getScholarshipCurrencyCode()
	{
		return $this->scholarshipCurrencyCode;
	}
	
	public function getScholarshipLink()
	{
		return $this->scholarshipLink;
	}
	
	public function setScholarshipLink($link)
	{
		$this->scholarshipLink = $link;
	}
	
	public function getCustomScholarship()
	{
		return $this->customScholarship;
	}
	
	public function setCustomScholarship($details)
	{
		$this->customScholarship = $details;
	}
	
	public function getScholarshipLinkCourseLevel()
	{
		return $this->scholarshipLinkCourseLevel;
	}
	
	public function getScholarshipLinkDeptLevel()
	{
		return $this->scholarshipLinkDeptLevel;
	}

	public function getScholarshipLinkUniversityLevel()
	{
		return $this->scholarshipLinkUniversityLevel;
	}
	
	public function getFacultyInfoLink()
	{
		return $this->facultyInfoLink;
	}
	
	public function getCourseFaqLink()
	{
		return $this->faqLink;
	}
	
	public function getAlumniInfoLink()
	{
		return $this->alumniInfoLink;
	}
	
	public function isOfferingScholarship(){
		return !empty($this->scholarshipLinkCourseLevel) || !empty($this->scholarshipLinkDeptLevel) || !empty($this->scholarshipLinkUniversityLevel);
	}
	
	public function setCourseSubCategoryObj(Category $category){
		$this->courseCategory = $category;
	}
	
	public function getCourseSubCategoryObj(){
	
		return $this->courseCategory;
	}
	
	public function getDesiredCourseId()
	{
		return $this->desiredCourseId;
	}
	
	public function getLDBCourseId()
	{
		return $this->ldbCourseId;
	}

	public function getClassProfile()
	{
		return $this->classProfile;
	}
	
	public function getCountryId(){
		return $this->country_id;
	}
        
        public function getCountryName(){
		return $this->country_name;
	}
        
        public function getCityName(){
		return $this->city_name;
	}
	
	public function getCustomFees(){
		return $this->customFees;
	}
	
	public function getRoomBoard(){
		return $this->roomBoard;
	}
	
	public function getInsurance(){
		return $this->insurance;
	}
	
	public function getTransportation(){
		return $this->transportation;
	}
	
	public function setCourseSubCategory($subCategoryId){
		$this->courseSubCategory = $subCategoryId;
	}
	
	public function getCourseSubCategory(){
		return $this->courseSubCategory;
	}
	
	public function getTotalFees(){
		$nFees = new CourseFees();
		$nFees->__set('fees_unit',$this->fees->getCurrency());
		$nFees->__set('currency',$this->fees->getCurrencyEntity());
		
		$totalFees = $this->fees->getValue() + $this->roomBoard + $this->insurance + $this->transportation;
		foreach($this->customFees as $fees){
			$totalFees += $fees['value'];
		}
		$nFees->__set('fees_value',$totalFees);
		return $nFees;
	}
	
	public function cleanForCategorypage(){
		unset($this->course_description);
		unset($this->duration);
		unset($this->attributes);
		unset($this->recruiting_companies);
		unset($this->locations);
		unset($this->classProfile);
		unset($this->jobProfile);
		unset($this->course_type);
		unset($this->course_level_2);
		unset($this->institute_id);
		unset($this->institute_name);
		unset($this->city_id);
		unset($this->city_name);
		unset($this->country_id);
		unset($this->country_name);
		unset($this->institute);
		unset($this->last_modify_date);
		unset($this->expiry_date);
		unset($this->username);
		unset($this->courseDurationLink);
		unset($this->applicationDeadlineLink);
		unset($this->admissionWebsiteLink);
		unset($this->feesPageLink);
		unset($this->facultyInfoLink);
		unset($this->alumniInfoLink);
		unset($this->faqLink);
		/*unset($this->customFees);
		unset($this->transportation);
		unset($this->insurance);
		unset($this->roomBoard);*/
		unset($this->university_name);
		unset($this->university_type);
		unset($this->listing_seo_title);
		unset($this->listing_seo_description);
		unset($this->listing_seo_keywords);
		unset($this->courseWebsite);
		unset($this->startDates);
		unset($this->scholarshipDescription);
		unset($this->scholarshipEligibility);
		unset($this->scholarshipDeadLine);
		unset($this->scholarshipAmount);
		unset($this->scholarshipCurrency);
		unset($this->scholarshipCurrencyCode);
		unset($this->scholarshipLink);
		unset($this->customScholarship);
		$this->fees->cleanForCategorypage();
		$cleanExams = array();
		foreach($this->exams as $key=>$exam){
			$exam->cleanForCategoryPage();
			$this->exams[$key] = $exam;
		}
    }
	public function getCourseApplicationDetail()
	{
		return $this->applicationDetailId;
	}
	public function setCourseApplicationDetail($applicationDetailId)
	{
		$this->applicationDetailId = $applicationDetailId;
	}
	public function setRmcEnabledDetail($isRmcEnabled)
	{
		$this->isRmcEnabled = $isRmcEnabled;
	}
	public function getRmcEnabledDetail()
	{
		return $this->isRmcEnabled;
	}
	public function showRmcButton()
	{
		return $this->isRmcEnabled && ($this->applicationDetailId > 0);
	}
	public function getCourseWebsite()
	{
		return $this->courseWebsite;
	}
}

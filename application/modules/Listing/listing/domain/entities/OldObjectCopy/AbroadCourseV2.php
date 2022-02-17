<?php

class AbroadCourse/*V2*/
{
    private $courseId;
    private $name;
//    private $course_type;
//    private $course_level;
    private $level;
//    private $course_level_2;
//    private $course_order;

    private $instituteId;
    private $institute_name;
    private $city_id;
    private $city_name;
    private $country_id;
    private $country_name;

    private $universityId;
    private $university_name;
    private $university_type;

    private $seoURL;
    private $title;
    private $description;
    private $keyword;

    private $fees;
    private $duration;
    private $attributes;

    private $exams;
    private $recruitingCompanies;

    private $institute;

    private $packtype;
    private $cumulativeViewCount;
    private $lastModifiedDate;
    private $expiryDate;
    private $clientId;
    private $brochureURL;

    private $courseDescription;

    private $courseWebsiteURL;
    private $durationURL;
    private $applicationDeadlineURL;
    private $admissionWebsiteURL;
    private $feesPageURL;
    private $scholarshipURLCourseLevel;
    private $scholarshipURLDeptLevel;
    private $scholarshipURLUniversityLevel;
    private $facultyInfoURL;
    private $alumniInfoURL;
    private $faqURL;

    private $startDates = array();

    private $classProfile;
    private $jobProfile;
    private $desiredCourseId;
    private $specializationIds;

    private $courseCategory;  // Purpose : to make category filter on category pages, note :this property have to set manually before use
    private $subCategoryId;

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
        if(!empty($this->brochureURL)){
            return MEDIAHOSTURL.$this->brochureURL;
        }
        return;
    }

    public function getLastUpdatedDate()
    {
        return $this->lastModifiedDate;
    }

    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setAdditionalURLParams($additionalURLParams)
    {
        $this->additionalURLParams = $additionalURLParams;
    }

    public function getURL()
    {
        if(!empty($this->seoURL)){
            return SHIKSHA_STUDYABROAD_HOME.$this->seoURL;
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
//        $courseName = htmlentities($this->getName());
        $universityName = htmlentities($this->getUniversityName());
        $countryName = htmlentities($this->getMainLocation()->getCountry()->getName());

        //$this->title 	= ($this->title 	  == ""? $courseName." from ".$universityName.", ".$countryName." | Shiksha.com" :$this->title 	);
        //$this->description 	= ($this->description == ""? "Get all information about ".$courseName." from ".$universityName.", ".$countryName." like fees structure, eligibility criteria, syllabus, qualification, course duration etc. on Shiksha.com" :$this->description);
        $this->title = str_replace("{univName}",$this->getUniversityName(),$this->title);
        $this->title = str_replace("{countryName}",$countryName,$this->title);
        $this->description = str_replace("{univName}",$universityName,$this->description);

        return array(
            'seoTitle' => $this->title,
            'seoKeywords' => $this->keyword,
            'seoDescription' => $this->description
        );
    }

    //we need to get rid of this function
    public function getCourseLevel()
    {
        return $this->level." Degree";
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
        return $this->recruitingCompanies;
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
        $this->recruitingCompanies[] = $company;
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

    public function setDuration(CourseDurationCopy $duration)
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
        return $this->instituteId;
    }

    public function getId()
    {
        return $this->courseId;
    }

    public function getName()
    {
        return $this->name;
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
        return $this->level;
    }

    public function getCourseLevel2Value()
    {
        return $this->course_level_2;
    }

    public function getCoursePackType()
    {
        return $this->packtype;
    }

    public function isPaid()
    {
        return ($this->packtype == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->packtype == SILVER_LISTINGS_BASE_PRODUCT_ID || $this->packtype == GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
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
        return $this->courseDescription;
    }

    public function getUniversityType(){
        return $this->university_type;
    }
    public function getUniversityId(){
        return $this->universityId;
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
        return $this->scholarshipURLCourseLevel;
    }

    public function getScholarshipLinkDeptLevel()
    {
        return $this->scholarshipURLDeptLevel;
    }

    public function getScholarshipLinkUniversityLevel()
    {
        return $this->scholarshipURLUniversityLevel;
    }

    public function getFacultyInfoLink()
    {
        return $this->facultyInfoURL;
    }

    public function getCourseFaqLink()
    {
        return $this->faqURL;
    }

    public function getAlumniInfoLink()
    {
        return $this->alumniInfoURL;
    }

    public function isOfferingScholarship(){
        return !empty($this->scholarshipURLCourseLevel) || !empty($this->scholarshipURLDeptLevel) || !empty($this->scholarshipURLUniversityLevel);
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

    public function getLDBCourseId() //will be different from old courseObject used to derive courseLevel and categoryId which is same
    {
        return reset($this->specializationIds);
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
        $this->subCategoryId = $subCategoryId;
    }

    public function getCourseSubCategory(){
        return $this->subCategoryId;
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
        unset($this->courseDescription);
        unset($this->duration);
        unset($this->attributes);
        unset($this->recruitingCompanies);
        unset($this->locations);
        unset($this->classProfile);
        unset($this->jobProfile);
//        unset($this->course_type);
//        unset($this->course_level_2);
        unset($this->instituteId);
        unset($this->institute_name);
        unset($this->city_id);
        unset($this->city_name);
        unset($this->country_id);
        unset($this->country_name);
        unset($this->institute);
        unset($this->lastModifiedDate);
        unset($this->expiryDate);
        unset($this->clientId);
        unset($this->durationURL);
        unset($this->applicationDeadlineURL);
        unset($this->admissionWebsiteURL);
        unset($this->feesPageURL);
        unset($this->facultyInfoURL);
        unset($this->alumniInfoURL);
        unset($this->faqURL);
        /*unset($this->customFees);
        unset($this->transportation);
        unset($this->insurance);
        unset($this->roomBoard);*/
        unset($this->university_name);
        unset($this->university_type);
        unset($this->title);
        unset($this->description);
        unset($this->keyword);
        unset($this->courseWebsiteURL);
//        unset($this->startDates);
//        unset($this->scholarshipDescription);
//        unset($this->scholarshipEligibility);
//        unset($this->scholarshipDeadLine);
//        unset($this->scholarshipAmount);
//        unset($this->scholarshipCurrency);
//        unset($this->scholarshipCurrencyCode);
//        unset($this->scholarshipLink);
//        unset($this->customScholarship);
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
        // return $this->isRmcEnabled && ($this->applicationDetailId > 0); // SA-4897
        return ($this->isRmcEnabled>0?true:false);
    }
    public function getCourseWebsite()
    {
        return $this->courseWebsiteURL;
    }
}

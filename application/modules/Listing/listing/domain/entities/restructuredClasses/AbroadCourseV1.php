<?php

class AbroadCourseV1{
    private $courseId;
    private $name;
    private $universityId;
    private $instituteId;
    private $level;
    private $categoryId;
    private $subCategoryId;
    private $desiredCourseId;
    private $specializationIds;
    private $brochureURL;
    private $seoURL;
    private $seoDetails;
    private $clientId;
    private $packtype;
    private $lastModifiedDate;
    private $expiryDate;
    private $duration;
    private $durationURL;
    private $feeCurrency;
    private $tuition;
    private $roomBoard;
    private $mealFlag;
    private $insurance;
    private $transportation;
    private $customFees;//will handle later
    private $courseExams;
    private $courseCustomExams;
    private $courseDescription;
    private $examRequiredDetails;
    private $accreditation;
    private $affiliation;
    private $rankingDetails;
    private $curriculum;
    private $nzqfCategorization;
    private $recruitingCompanies;
    private $cumulativeViewCount;
    private $courseWebsiteURL;
    private $admissionWebsiteURL;
    private $feesPageURL;
    private $alumniInfoURL;
    private $scholarshipURLUniversityLevel;
    private $applicationDeadlineURL;
    private $faqURL;
    private $scholarshipURLCourseLevel;
    private $scholarshipURLDeptLevel;
    private $facultyInfoURL;
    private $englishProficiencyURL;
    private $anyOtherEligibilityURL;
    private $classProfile;
    private $jobProfile;

    private $applicationDetailId;
    private $isRmcEnabled;
    private $isWorkExperienceRequired;
    private $workExperienceValue;
    private $xiiCutoff;
    private $bachelorScoreUnit;
    private $bachelorCutoff;
    private $pgCutoff;
    private $threeYearDegreeAcceptedFlag;
    private $threeYearDegreeDescription;
    private $xiiCutoffComments;
    private $bachelorCutoffComments;
    private $pgCutoffComments;
    private $workExperienceDescription;
    private $additionalRequirement;

    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->courseId;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUniversityId()
    {
        return $this->universityId;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return mixed
     */
    public function getInstituteId()
    {
        return $this->instituteId;
    }

    /**
     * @param mixed $instituteId
     */
    public function setInstituteId($instituteId)
    {
        $this->instituteId = $instituteId;
    }


    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return mixed
     */
    public function getSubCategoryId()
    {
        return $this->subCategoryId;
    }

    /**
     * @return mixed
     */
    public function getDesiredCourseId()
    {
        return $this->desiredCourseId;
    }

    /**
     * @return mixed
     */
    public function getSpecializationIds()
    {
        return $this->specializationIds;
    }

    /**
     * @return mixed
     */
    public function getBrochureURL()
    {
        return $this->brochureURL;
    }

    /**
     * @return mixed
     */
    public function getSeoURL()
    {
        return $this->seoURL;
    }

    /**
     * @return mixed
     */
    public function getSeoDetails()
    {
        return $this->seoDetails;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getPacktype()
    {
        return $this->packtype;
    }

    /**
     * @return mixed
     */
    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    /**
     * @return mixed
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return mixed
     */
    public function getDurationURL()
    {
        return $this->durationURL;
    }

    /**
     * @return mixed
     */
    public function getFeeCurrency()
    {
        return $this->feeCurrency;
    }

    /**
     * @return mixed
     */
    public function getTuition()
    {
        return $this->tuition;
    }

    /**
     * @return mixed
     */
    public function getRoomBoard()
    {
        return $this->roomBoard;
    }

    /**
     * @return mixed
     */
    public function getMealFlag()
    {
        return $this->mealFlag;
    }

    /**
     * @return mixed
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @return mixed
     */
    public function getTransportation()
    {
        return $this->transportation;
    }

    /**
     * @return mixed
     */
    public function getCustomFees()
    {
        return $this->customFees;
    }

    /**
     * @return mixed
     */
    public function getCourseExams()
    {
        return $this->courseExams;
    }

    /**
     * @return mixed
     */
    public function getCourseCustomExams()
    {
        return $this->courseCustomExams;
    }

    /**
     * @return mixed
     */
    public function getCourseDescription()
    {
        return $this->courseDescription;
    }

    /**
     * @return mixed
     */
    public function getExamRequiredDetails()
    {
        return $this->examRequiredDetails;
    }

    /**
     * @return mixed
     */
    public function getAccreditation()
    {
        return $this->accreditation;
    }

    /**
     * @return mixed
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }

    /**
     * @return mixed
     */
    public function getRankingDetails()
    {
        return $this->rankingDetails;
    }

    /**
     * @return mixed
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * @return mixed
     */
    public function getNzqfCategorization()
    {
        return $this->nzqfCategorization;
    }

    /**
     * @return mixed
     */
    public function getRecruitingCompanies()
    {
        return $this->recruitingCompanies;
    }

    /**
     * @return mixed
     */
    public function getCumulativeViewCount()
    {
        return $this->cumulativeViewCount;
    }

    /**
     * @return mixed
     */
    public function getCourseWebsiteURL()
    {
        return $this->courseWebsiteURL;
    }

    /**
     * @return mixed
     */
    public function getAdmissionWebsiteURL()
    {
        return $this->admissionWebsiteURL;
    }

    /**
     * @return mixed
     */
    public function getFeesPageURL()
    {
        return $this->feesPageURL;
    }

    /**
     * @return mixed
     */
    public function getAlumniInfoURL()
    {
        return $this->alumniInfoURL;
    }

    /**
     * @return mixed
     */
    public function getScholarshipURLUniversityLevel()
    {
        return $this->scholarshipURLUniversityLevel;
    }

    /**
     * @return mixed
     */
    public function getApplicationDeadlineURL()
    {
        return $this->applicationDeadlineURL;
    }

    /**
     * @return mixed
     */
    public function getFaqURL()
    {
        return $this->faqURL;
    }

    /**
     * @return mixed
     */
    public function getScholarshipURLCourseLevel()
    {
        return $this->scholarshipURLCourseLevel;
    }

    /**
     * @return mixed
     */
    public function getScholarshipURLDeptLevel()
    {
        return $this->scholarshipURLDeptLevel;
    }

    /**
     * @return mixed
     */
    public function getFacultyInfoURL()
    {
        return $this->facultyInfoURL;
    }

    /**
     * @return mixed
     */
    public function getEnglishProficiencyURL()
    {
        return $this->englishProficiencyURL;
    }

    /**
     * @return mixed
     */
    public function getAnyOtherEligibilityURL()
    {
        return $this->anyOtherEligibilityURL;
    }

    /**
     * @return mixed
     */
    public function getClassProfile()
    {
        return $this->classProfile;
    }

    /**
     * @return mixed
     */
    public function getJobProfile()
    {
        return $this->jobProfile;
    }

    /**
     * @param mixed $courseId
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $universityId
     */
    public function setUniversityId($universityId)
    {
        $this->universityId = $universityId;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param mixed $subCategoryId
     */
    public function setSubCategoryId($subCategoryId)
    {
        $this->subCategoryId = $subCategoryId;
    }

    /**
     * @param mixed $desiredCourseId
     */
    public function setDesiredCourseId($desiredCourseId)
    {
        $this->desiredCourseId = $desiredCourseId;
    }

    /**
     * @param mixed $specializationIds
     */
    public function setSpecializationIds($specializationIds)
    {
        $this->specializationIds = $specializationIds;
    }

    /**
     * @param mixed $brochureURL
     */
    public function setBrochureURL($brochureURL)
    {
        $this->brochureURL = $brochureURL;
    }

    /**
     * @param mixed $seoURL
     */
    public function setSeoURL($seoURL)
    {
        $this->seoURL = $seoURL;
    }

    /**
     * @param mixed $seoDetails
     */
    public function setSeoDetails(CourseSEODetailsV1 $seoDetails)
    {
        $this->seoDetails = $seoDetails;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @param mixed $packtype
     */
    public function setPacktype($packtype)
    {
        $this->packtype = $packtype;
    }

    /**
     * @param mixed $lastModifiedDate
     */
    public function setLastModifiedDate($lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }

    /**
     * @param mixed $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration(CourseDurationV1 $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @param mixed $durationURL
     */
    public function setDurationURL($durationURL)
    {
        $this->durationURL = $durationURL;
    }

    /**
     * @param mixed $feeCurrency
     */
    public function setFeeCurrency($feeCurrency)
    {
        $this->feeCurrency = $feeCurrency;
    }

    /**
     * @param mixed $tuition
     */
    public function setTuition($tuition)
    {
        $this->tuition = $tuition;
    }

    /**
     * @param mixed $roomBoard
     */
    public function setRoomBoard($roomBoard)
    {
        $this->roomBoard = $roomBoard;
    }

    /**
     * @param mixed $mealFlag
     */
    public function setMealFlag($mealFlag)
    {
        $this->mealFlag = $mealFlag;
    }

    /**
     * @param mixed $insurance
     */
    public function setInsurance($insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * @param mixed $transportation
     */
    public function setTransportation($transportation)
    {
        $this->transportation = $transportation;
    }


    public function addCustomFees(CourseCustomFeesV1 $customFees){
        $this->customFees[] = $customFees;
    }
    /**
     * @param mixed $courseExams
     */
    public function addCourseExams(CourseExamV1 $courseExams)
    {
        $this->courseExams[] = $courseExams;
    }

    /**
     * @param mixed $courseCustomExams
     */
    public function addCourseCustomExams(CourseExamCustomV1 $courseCustomExams)
    {
        $this->courseCustomExams[] = $courseCustomExams;
    }

    /**
     * @param mixed $courseDescription
     */
    public function setCourseDescription($courseDescription)
    {
        $this->courseDescription = $courseDescription;
    }

    /**
     * @param mixed $examRequiredDetails
     */
    public function setExamRequiredDetails($examRequiredDetails)
    {
        $this->examRequiredDetails = $examRequiredDetails;
    }

    /**
     * @param mixed $accreditation
     */
    public function setAccreditation($accreditation)
    {
        $this->accreditation = $accreditation;
    }

    /**
     * @param mixed $affiliation
     */
    public function setAffiliation($affiliation)
    {
        $this->affiliation = $affiliation;
    }

    /**
     * @param mixed $rankingDetails
     */
    public function setRankingDetails($rankingDetails)
    {
        $this->rankingDetails = $rankingDetails;
    }

    /**
     * @param mixed $curriculum
     */
    public function setCurriculum($curriculum)
    {
        $this->curriculum = $curriculum;
    }

    /**
     * @param mixed $nzqfCategorization
     */
    public function setNzqfCategorization($nzqfCategorization)
    {
        $this->nzqfCategorization = $nzqfCategorization;
    }


    public function addRecruitingCompany(RecruitingCompany $company)
    {
        $this->recruitingCompanies[] = $company;
    }

    /**
     * @param mixed $cumulativeViewCount
     */
    public function setCumulativeViewCount($cumulativeViewCount)
    {
        $this->cumulativeViewCount = $cumulativeViewCount;
    }

    /**
     * @param mixed $courseWebsiteURL
     */
    public function setCourseWebsiteURL($courseWebsiteURL)
    {
        $this->courseWebsiteURL = $courseWebsiteURL;
    }

    /**
     * @param mixed $admissionWebsiteURL
     */
    public function setAdmissionWebsiteURL($admissionWebsiteURL)
    {
        $this->admissionWebsiteURL = $admissionWebsiteURL;
    }

    /**
     * @param mixed $feesPageURL
     */
    public function setFeesPageURL($feesPageURL)
    {
        $this->feesPageURL = $feesPageURL;
    }

    /**
     * @param mixed $alumniInfoURL
     */
    public function setAlumniInfoURL($alumniInfoURL)
    {
        $this->alumniInfoURL = $alumniInfoURL;
    }

    /**
     * @param mixed $scholarshipURLUniversityLevel
     */
    public function setScholarshipURLUniversityLevel($scholarshipURLUniversityLevel)
    {
        $this->scholarshipURLUniversityLevel = $scholarshipURLUniversityLevel;
    }

    /**
     * @param mixed $applicationDeadlineURL
     */
    public function setApplicationDeadlineURL($applicationDeadlineURL)
    {
        $this->applicationDeadlineURL = $applicationDeadlineURL;
    }

    /**
     * @param mixed $faqURL
     */
    public function setFaqURL($faqURL)
    {
        $this->faqURL = $faqURL;
    }

    /**
     * @param mixed $scholarshipURLCourseLevel
     */
    public function setScholarshipURLCourseLevel($scholarshipURLCourseLevel)
    {
        $this->scholarshipURLCourseLevel = $scholarshipURLCourseLevel;
    }

    /**
     * @param mixed $scholarshipURLDeptLevel
     */
    public function setScholarshipURLDeptLevel($scholarshipURLDeptLevel)
    {
        $this->scholarshipURLDeptLevel = $scholarshipURLDeptLevel;
    }

    /**
     * @param mixed $facultyInfoURL
     */
    public function setFacultyInfoURL($facultyInfoURL)
    {
        $this->facultyInfoURL = $facultyInfoURL;
    }

    /**
     * @param mixed $englishProficiencyURL
     */
    public function setEnglishProficiencyURL($englishProficiencyURL)
    {
        $this->englishProficiencyURL = $englishProficiencyURL;
    }

    /**
     * @param mixed $anyOtherEligibilityURL
     */
    public function setAnyOtherEligibilityURL($anyOtherEligibilityURL)
    {
        $this->anyOtherEligibilityURL = $anyOtherEligibilityURL;
    }

    /**
     * @param mixed $classProfile
     */
    public function setClassProfile(CourseClassProfile $classProfile)
    {
        $this->classProfile = $classProfile;
    }

    /**
     * @param mixed $jobProfile
     */
    public function setJobProfile(CourseJobProfile $jobProfile)
    {
        $this->jobProfile = $jobProfile;
    }

    public function setRmcEnabledDetail($isRmcEnabled)
    {
        $this->isRmcEnabled = $isRmcEnabled;
    }

    public function getRmcEnabledDetail()
    {
        return $this->isRmcEnabled;
    }

    public function getCourseApplicationDetail()
    {
        return $this->applicationDetailId;
    }

    public function setCourseApplicationDetail($applicationDetailId)
    {
        $this->applicationDetailId = $applicationDetailId;
    }
    public function setAdditionalRequirement($additionalRequirement)
    {
        $this->additionalRequirement = $additionalRequirement;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }
    function getWorkExperienceValue(){
        return $this->workExperienceValue;
    }
    function getXiiCutoff(){
        return $this->xiiCutoff;
    }
    function getBachelorScoreUnit(){
        return $this->bachelorScoreUnit;
    }
    function getBachelorCutoff(){
        return $this->bachelorCutoff;
    }
    function getPgCutoff(){
        return $this->pgCutoff;
    }

}

?>
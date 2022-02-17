<?php
class Consultant {
    private $consultantId;
    private $name;
    private $logo;
    private $description;
    private $establishmentYear;
    private $facebookLink;
    private $linkedInLink;
    private $website;
    private $offersPaidServices;
    private $paidServicesDetails;
    private $offersTestPrepServices;
    private $testPrepServicesDetails;
    private $ceoName;
    private $ceoQualification;
    private $employeeCount;
    private $media;
    private $excludedCourseComments;
    private $excludedCourses;
    private $universityMappings;
    private $consultantLocations;
    private $consultantStudentProfiles;
    private $defaultBranches;
    
    public function __construct(){
    }
    
    public function getId(){
        return $this->consultantId;
    }
    
    public function getLogo(){
        return $this->logo;
    }

    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }
    
    public function getEstablishmentYear(){
        return $this->establishmentYear;
    }
    
    public function getFacebookLink(){
        return $this->facebookLink;
    }
    
    public function getLinkedInLink(){
        return $this->linkedInLink;
    }
    
    public function getWebsite(){
        return $this->website;
    }
    
    public function hasPaidServices(){
        return $this->offersPaidServices;
    }
    
    public function hasTestPrepServices(){
        return $this->offersTestPrepServices;
    }
    
    public function getPaidServicesDetails(){
        return $this->paidServicesDetails;
    }
    
    public function getTestPrepServicesDetails(){
        return $this->testPrepServicesDetails;
    }
    
    public function getCEOName(){
        return $this->ceoName;
    }
    
    public function getCEOQualification(){
        return $this->ceoQualification;
    }
    
    public function getEmployeeCount(){
        return $this->employeeCount;
    }
    
    public function getMedia(){
        return $this->media;
    }
    
    public function getUniversitiesMapped(){
        return $this->universityMappings;
    }
    
    public function getConsultantLocations(){
        return $this->consultantLocations;
    }
    
    public function getConsultantStudentProfiles(){
        return $this->consultantStudentProfiles;
    }
    
    public function getExcludedCourses(){
        return $this->excludedCourses;
    }
    
    public function getExcludedCourseComments(){
        return $this->excludedCourseComments;
    }
    
    public function __set($property,$value) {
        $this->$property = $value;
    }
    
    public function getSeoInfo()
    {
        $consultantName = htmlentities($this->name);
        $seoData = array();
        $seoData['seoTitle'] = $consultantName.' Overseas Education Consultant';
        $seoData['seoDescription'] = 'Shiksha verified information on '.$consultantName.'. See Universities, countries & students sent abroad by '.$consultantName.' Overseas Education Consultant';
        $seoData['seoDescription'] = substr($seoData['seoDescription'],0,160);
        return $seoData;
    }
    
    public function getCanonicalUrl()
    {
        $url = str_replace(' ','-',htmlentities($this->name)).'-overseas-education-consultant-'.$this->consultantId;
        return SHIKSHA_STUDYABROAD_HOME.'/'.seo_url_lowercase($url);
    }
    public function getUrl()
    {
        $url = str_replace(' ','-',htmlentities($this->name)).'-overseas-education-consultant-'.$this->consultantId;
        return SHIKSHA_STUDYABROAD_HOME.'/'.seo_url_lowercase($url);
    }
    public function getDefaultBranches()
    {
        return $this->defaultBranches;
    }
}	
	
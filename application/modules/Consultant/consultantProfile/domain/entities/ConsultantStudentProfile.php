<?php
class ConsultantStudentProfile {
    private $consultantId;
    private $profileId;
    private $admissionDate;
    private $studentName;
    private $residenceCityId;
    private $classXPercentage;
    private $classXYear;
    private $classXIIPercentage;
    private $classXIIYear;
    private $totalWorkExperienceMonths;
    private $extraCurricularActivities;
    private $linkedInLink;
    private $facebookLink;
    private $studentEmail;
    private $studentPhone;
    private $profileUniversityMapping;
    private $profileExamMapping;
    private $profileGraduationMapping;
    private $profileCompanyMapping;
    private $profileDocumentMapping;
    
    public function __construct(){
    }
    
    public function getId(){
        return $this->profileId;
    }
    
    public function getConsultantId(){
        return $this->consultantId;
    }

    public function getStudentName(){
        return $this->studentName;
    }
    
    public function getAdmissionDate(){
        return $this->admissionDate;
    }
    
    public function getResidenceCityId(){
        return $this->residenceCityId;
    }
    
    public function getClassXPercentage(){
        return $this->classXPercentage;
    }
    
    public function getClassXYear(){
        return $this->classXYear;
    }
    
    public function getClassXIIPercentage(){
        return $this->classXIIPercentage;
    }
    
    public function getClassXIIYear(){
        return $this->classXIIYear;
    }
    
    public function getTotalWorkExperienceInMonths(){
        return $this->totalWorkExperienceMonths;
    }
    
    public function getExtraCurricularActivities(){
        return $this->extraCurricularActivities;
    }
    
    public function getStudentLinkedInLink(){
        return $this->linkedInLink;
    }
    
    public function getStudentFacebookLink(){
        return $this->facebookLink;
    }
    
    public function getStudentEmail(){
        return $this->studentEmail;
    }
    
    public function getStudentPhone(){
        return $this->studentPhone;
    }
    
    public function getProfileUniversityMapping(){
        return $this->profileUniversityMapping;
    }
    
    public function getProfileDocumentMapping(){
        return $this->profileDocumentMapping;
    }
    
    public function getProfileCompanyMapping(){
        return $this->profileCompanyMapping;
    }
    
    public function getProfileGraduationMapping()
    {
        return $this->profileGraduationMapping;
    }
    
    public function getProfileExamMapping()
    {
        return $this->profileExamMapping;
    }
    
    public function __set($property,$value) {
        $this->$property = $value;
    }
}	
	
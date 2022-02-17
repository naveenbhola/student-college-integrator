<?php

class scholarshipApplicationData {
    private $docsDescription;
    private $applyNowLink;
    private $faqLink;
    private $contactEmail;
    private $contactPhone;
    private $applicableNationalities;
    private $intakeYear;
    private $scholarshipBrochureUrl;
    private $applicableCountries;
    private $SOP;
    private $LOR;
    private $CV;
    private $financialDocuments;
    private $extraCurricularActivities;
    private $workExperience;
    private $officialTranscripts;
    private $Essays;
    
    public function getDocsDescription(){
        if(!isset($this->docsDescription)){
            throw new Exception("Please load the Scholarship Object with 'docsDescription' field in application section", 1);
        }
        return $this->docsDescription;
    }
    
    public function getApplyNowLink(){
        if(!isset($this->applyNowLink)){
            throw new Exception("Please load the Scholarship Object with 'applyNowLink' field in application section", 1);
        }
        return $this->applyNowLink;
    }
    public function getFaqLink(){
        if(!isset($this->faqLink)){
            throw new Exception("Please load the Scholarship Object with 'faqLink' field in application section", 1);
        }
        return $this->faqLink;
    }
    public function getContactEmail(){
        if(!isset($this->contactEmail)){
            throw new Exception("Please load the Scholarship Object with 'contactEmail' field in application section", 1);
        }
        return $this->contactEmail;
    }
    public function getContactPhone(){
        if(!isset($this->contactPhone)){
            throw new Exception("Please load the Scholarship Object with 'contactPhone' field in application section", 1);
        }
        return $this->contactPhone;
    }
    public function getApplicableNationalities(){
        if(!isset($this->applicableNationalities)){
            throw new Exception("Please load the Scholarship Object with 'applicableNationalities' field in application section", 1);
        }
        return $this->applicableNationalities;
    }
    
    public function getIntakeYears(){
        if(!isset($this->intakeYear)){
            throw new Exception("Please load the Scholarship Object with 'intakeYear' field in application section", 1);
        }
        return $this->intakeYear;
    }
    public function getBrochureUrl(){
        if(!isset($this->scholarshipBrochureUrl)){
            throw new Exception("Please load the Scholarship Object with 'scholarshipBrochureUrl' field in application section", 1);
        }
        if($this->scholarshipBrochureUrl==''){
            return $this->scholarshipBrochureUrl;
        }else{
            return MEDIAHOSTURL.$this->scholarshipBrochureUrl;
        }
        
    }
    public function getLOR(){
        if(!isset($this->LOR)){
            throw new Exception("Please load the Scholarship Object with 'LOR' field in application section", 1);
        }
        return $this->LOR;
    }
    public function getApplicableCountries(){
        if(!isset($this->applicableCountries)){
            throw new Exception("Please load the Scholarship Object with 'applicableCountries' field in application section", 1);
        }
        return $this->applicableCountries;
    }
    public function getSOP(){
        if(!isset($this->SOP)){
            throw new Exception("Please load the Scholarship Object with 'SOP' field in application section", 1);
        }
        return $this->SOP;
    }

    public function getEssays(){
        if(!isset($this->Essays)){
            throw new Exception("Please load the Scholarship Object with 'Essays' field in application section", 1);
        }
        return $this->Essays;
    }

    public function getCV(){
        if(!isset($this->CV)){
            throw new Exception("Please load the Scholarship Object with 'CV' field in application section", 1);
        }
        return $this->CV;
    }
    public function getExtraCurricularActivities(){
        if(!isset($this->extraCurricularActivities)){
            throw new Exception("Please load the Scholarship Object with 'extraCurricularActivities' field in application section", 1);
        }
        return $this->extraCurricularActivities;
    }
    public function getFinancialDocuments(){
        if(!isset($this->financialDocuments)){
            throw new Exception("Please load the Scholarship Object with 'financialDocuments' field in application section", 1);
        }
        return $this->financialDocuments;
    }
    public function getWorkExperience(){
        if(!isset($this->workExperience)){
            throw new Exception("Please load the Scholarship Object with 'workExperience' field in application section", 1);
        }
        return $this->workExperience;
    }
    
    public function getOfficialTranscripts(){
        if(!isset($this->officialTranscripts)){
            throw new Exception("Please load the Scholarship Object with 'officialTranscripts' field in application section", 1);
        }
        return $this->officialTranscripts;
    }
    function __set($property,$value){
        $this->$property = $value;
    }
}

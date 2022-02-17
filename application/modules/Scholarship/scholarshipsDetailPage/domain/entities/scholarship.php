<?php

class scholarship
{
    private $scholarshipId;
    private $name;
    private $category;
    private $organisationName;
    private $organisationLogo;
    private $link;
    private $description;
    private $type;
    private $scholarshipType2;
    private $extApplicationRequired;
    
    private $specialRestrictions;
    private $hierarchy;
    private $amount;
    
    private $eligibility;
    
    private $deadline;
    
    private $application;
    
    private $seoUrl;
    private $seoTitle;
    private $seoDescription;
    private $seoKeywords;
    private $modifiedAt;
    private $subscriptionType; //scholarship is only free as of now
    public function getId(){
        if(!isset($this->scholarshipId)){
            throw new Exception("Please load the Scholarship Object with 'scholarshipId' field in basic section", 1);
        }
        return $this->scholarshipId;
    }

    public function getName(){
        if(!isset($this->name)){
            throw new Exception("Please load the Scholarship Object with 'name' field in basic section", 1);
        }
        return $this->name;
    }
    
    public function getUrl(){
        if(!isset($this->seoUrl)){
            throw new Exception("Please load the Scholarship Object with 'seoUrl' field in basic section", 1);
        }
        if(!empty($this->seoUrl)){
            return SHIKSHA_STUDYABROAD_HOME.$this->seoUrl;
        }
        
    }

    public function getCategory(){
        if(!isset($this->category)){
            throw new Exception("Please load the Scholarship Object with 'category' field in basic section", 1);
        }
        return $this->category;
    }

    public function getSeoTitle(){
        if(!isset($this->seoTitle)){
            throw new Exception("Please load the Scholarship Object with 'seoTitle' field in basic section", 1);
        }
        return $this->seoTitle;
    }
    
    public function getSeoDescription(){
        if(!isset($this->seoDescription)){
            throw new Exception("Please load the Scholarship Object with 'seoDescription' field in basic section", 1);
        }
        return $this->seoDescription;
    }
    public function getScholarshipLink(){
        if(!isset($this->link)){
            throw new Exception("Please load the Scholarship Object with 'link' field in basic section", 1);
        }
        return $this->link;
    }

    public function getOrganisationLogo(){
        if(!isset($this->organisationLogo)){
            throw new Exception("Please load the Scholarship Object with 'organisationLogo' field in basic section", 1);
        }
        if(!empty($this->organisationLogo)){
            return MEDIAHOSTURL.$this->organisationLogo;
        }
    }
    public function getOrganisationName(){
        if(!isset($this->organisationName)){
            throw new Exception("Please load the Scholarship Object with 'organisationName' field in basic section", 1);
        }
        return $this->organisationName;
    }
    public function getScholarshipDescription(){
        if(!isset($this->description)){
            throw new Exception("Please load the Scholarship Object with 'description' field in basic section", 1);
        }
        return $this->description;
    }

    public function getSeoKeywords(){
        if(!isset($this->seoKeywords)){
            throw new Exception("Please load the Scholarship Object with 'seoKeywords' field in basic section", 1);
        }
        return $this->seoKeywords;
    }
    public function getScholarshipType(){
        if(!isset($this->type)){
            throw new Exception("Please load the Scholarship Object with 'type' field in basic section", 1);
        }
        return $this->type;
    }

    public function getScholarshipType2(){
        if(!isset($this->scholarshipType2)){
            throw new Exception("Please load the Scholarship Object with 'scholarshipType2' field in basic section", 1);
        }
        return $this->scholarshipType2;
    }

    public function externalApplicationRequired(){
        if(!isset($this->extApplicationRequired)){
            throw new Exception("Please load the Scholarship Object with 'extApplicationRequired' field in basic section", 1);
        }
        return $this->extApplicationRequired;
    }

    public function getApplicationData(){
        if(!isset($this->application)){
            throw new Exception("Please load the Scholarship Object with application section", 1);
        }
        else{
            return $this->application;
        }
    }

    public function getAmount(){
        if(!isset($this->amount)){
            throw new Exception("Please load the Scholarship Object with amount section", 1);
        }
        else{
            return $this->amount;
        }
    }
    
    public function getDeadLine(){
        if(!isset($this->deadline)){
            throw new Exception("Please load the Scholarship Object with deadline section", 1);
        }
        else{
            return $this->deadline;
        }
    }
    public function getEligibility(){
        if(!isset($this->eligibility)){
            throw new Exception("Please load the Scholarship Object with eligibility section", 1);
        }
        else{
            return $this->eligibility;
        }
    }
    public function getHierarchy(){
        if(!isset($this->hierarchy)){
            throw new Exception("Please load the Scholarship Object with hierarchy section", 1);
        }
        else{
            return $this->hierarchy;
        }
    }
    
    public function getSpecialRestrictions(){
        if(!isset($this->specialRestrictions)){
            throw new Exception("Please load the Scholarship Object with special restrictions section", 1);
        }
        else{
            return $this->specialRestrictions;
        }
    }

    public function getLastModifiedDate(){
        if(!isset($this->modifiedAt)){
            throw new Exception("Please load the Scholarship Object with 'modifiedAt' field in basic section", 1);
        }
        else{
            return $this->modifiedAt;
        }
    }
	
    public function getSubscriptionType(){
        if($this->subscriptionType==''){
            $this->subscriptionType = 'free';
        }
        return $this->subscriptionType;
    }
    
    public function isPaid(){
        if($this->getSubscriptionType()=='free'){
            return false;
        }else{
            return true;
        }
    }
    function __set($property,$value){
        $this->$property = $value;
    }
}

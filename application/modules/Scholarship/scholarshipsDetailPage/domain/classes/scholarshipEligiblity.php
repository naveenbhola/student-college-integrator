<?php

class scholarshipEligiblity {
    
    private $scholarshipExamsData;
    private $scholarshipEducationData;
    private $workXPRequired;
    private $workXP;
    private $interviewRequired;
    private $eligibilityDescription;
    private $eligibilityLink;
    private $selectionPreference;
    
    public function getExams(){
        if(!isset($this->scholarshipExamsData)){
            throw new Exception("Please load the Scholarship Object with 'scholarshipExamsData' field in eligibility section", 1);
        }
        return $this->scholarshipExamsData;
    }
    public function getEducation(){
        if(!isset($this->scholarshipEducationData)){
            throw new Exception("Please load the Scholarship Object with 'scholarshipEducationData' field in eligibility section", 1);
        }
        return $this->scholarshipEducationData;
    }
    public function workXPRequired(){
        if(!isset($this->workXPRequired)){
            throw new Exception("Please load the Scholarship Object with 'workXPRequired' field in eligibility section", 1);
        }
        return $this->workXPRequired;
    }
    public function getWorkXP(){
        if(!isset($this->workXP)){
            throw new Exception("Please load the Scholarship Object with 'workXP' field in eligibility section", 1);
        }
        return $this->workXP;
    }
    public function interviewRequired(){
        if(!isset($this->interviewRequired)){
            throw new Exception("Please load the Scholarship Object with 'interviewRequired' field in eligibility section", 1);
        }
        return $this->interviewRequired;
    }
    public function getDescription(){
        if(!isset($this->eligibilityDescription)){
            throw new Exception("Please load the Scholarship Object with 'eligibilityDescription' field in eligibility section", 1);
        }
        return $this->eligibilityDescription;
    }
    public function getLink(){
        if(!isset($this->eligibilityLink)){
            throw new Exception("Please load the Scholarship Object with 'eligibilityLink' field in eligibility section", 1);
        }
        return $this->eligibilityLink;
    }
    public function getPreference(){
        if(!isset($this->selectionPreference)){
            throw new Exception("Please load the Scholarship Object with 'selectionPreference' field in eligibility section", 1);
        }
        return $this->selectionPreference;
    }
    function __set($property,$value){
        $this->$property = $value;
    }
}

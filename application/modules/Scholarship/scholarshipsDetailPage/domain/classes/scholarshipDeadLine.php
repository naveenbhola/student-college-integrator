<?php

class scholarshipDeadLine {
    private $deadLineType;
    private $applicationEndDate;
    private $applicationEndDateDescription;
    private $applicationStartDate;
    private $applicationStartDateDescription;
    private $importantDates;
    private $additionalInfo;
    private $numAwards;
    private $numAwardsDescription;
    
    public function getDeadLineType(){
        if(!isset($this->deadLineType)){
            throw new Exception("Please load the Scholarship Object with 'deadLineType' field in deadline section", 1);
        }
        return $this->deadLineType;
    }
    public function getApplicationEndDate(){
        if(!isset($this->applicationEndDate)){
            throw new Exception("Please load the Scholarship Object with 'applicationEndDate' field in deadline section", 1);
        }
        return $this->applicationEndDate;
    }
    public function getApplicationEndDateDescription(){
        if(!isset($this->applicationEndDateDescription)){
            throw new Exception("Please load the Scholarship Object with 'applicationEndDateDescription' field in deadline section", 1);
        }
        return $this->applicationEndDateDescription;
    }
    public function getApplicationStartDate(){
        if(!isset($this->applicationStartDate)){
            throw new Exception("Please load the Scholarship Object with 'applicationStartDate' field in deadline section", 1);
        }
        return $this->applicationStartDate;
    }
    public function getApplicationStartDateDescription(){
        if(!isset($this->applicationStartDateDescription)){
            throw new Exception("Please load the Scholarship Object with 'applicationStartDateDescription' field in deadline section", 1);
        }
        return $this->applicationStartDateDescription;
    }
    public function getImportantDates(){
        if(!isset($this->importantDates)){
            throw new Exception("Please load the Scholarship Object with 'importantDates' field in deadline section", 1);
        }
        return $this->importantDates;
    }
    public function getAdditionalInfo(){
        if(!isset($this->additionalInfo)){
            throw new Exception("Please load the Scholarship Object with 'additionalInfo' field in deadline section", 1);
        }
        return $this->additionalInfo;
    }
    public function getNumAwards(){
        if(!isset($this->numAwards)){
            throw new Exception("Please load the Scholarship Object with 'numAwards' field in deadline section", 1);
        }
        return $this->numAwards;
    }
    public function getNumAwardsDescription(){
        if(!isset($this->numAwardsDescription)){
            throw new Exception("Please load the Scholarship Object with 'numAwardsDescription' field in deadline section", 1);
        }
        return $this->numAwardsDescription;
    }
        function __set($property,$value){
        $this->$property = $value;
    }
   
}

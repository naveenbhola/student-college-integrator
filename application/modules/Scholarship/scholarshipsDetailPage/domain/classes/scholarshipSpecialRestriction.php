<?php

class scholarshipSpecialRestriction {
    private $specialRestriction;
    private $specialRestrictionDescription;
    private $specialRestrictionLink;
    
    
    public function getRestrictions(){
        if(!isset($this->specialRestriction)){
            throw new Exception("Please load the Scholarship Object with 'specialRestriction' field in specialRestrictions section", 1);
        }
        return $this->specialRestriction;
    }
    public function getDescription(){
        if(!isset($this->specialRestrictionDescription)){
            throw new Exception("Please load the Scholarship Object with 'specialRestrictionDescription' field in specialRestrictions section", 1);
        }
        return $this->specialRestrictionDescription;
    }
    public function getLink(){
        if(!isset($this->specialRestrictionLink)){
            throw new Exception("Please load the Scholarship Object with 'specialRestrictionLink' field in specialRestrictions section", 1);
        }
        return $this->specialRestrictionLink;
    }
    function __set($property,$value){
        $this->$property = $value;
    }
}

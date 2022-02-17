<?php

class City
{
    private $city_id;
    private $city_name;
    private $enabled;
    private $tier;
    private $is_popular;
    private $state_id;
    private $countryId;
    private $virtualCityId;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->city_id;
    }
    
    public function getName()
    {
        if($this->city_id == 1){
            $this->city_name = "All Cities";
        }
        return $this->city_name;
    }

    public function getStateId()
    {
        if($this->state_id < 1){
            $this->state_id = 1;
        }
        return $this->state_id;
    }
    
    public function getCountryId()
    {
        return $this->countryId;   
    }
    
    public function getTier()
    {
        return $this->tier;
    }

    public function isPopular() {
        return $this->is_popular;
    }
    
    public function getVirtualCityId()
    {
        return $this->virtualCityId;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
    
    public function isVirtualCity() {
        if($this->enabled == 1) {
            return true;
        }
        else {
            return false;
        }
    }
}
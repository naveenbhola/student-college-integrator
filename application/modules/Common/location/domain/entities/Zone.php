<?php

class Zone
{
    private $zoneId;
    private $zoneName;
    private $cityId;
    private $state_id;
    private $countryId;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->zoneId;
    }
    
    public function getName()
    {
        return $this->zoneName;
    }
    
    public function getCityId()
    {
        return $this->cityId;
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
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
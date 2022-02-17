<?php

class Locality
{
    private $localityId;
    private $localityName;
    private $zoneId;
    private $cityId;
    private $stateId;
    private $countryId;
    
    function __construct()
    {
        
    }

    public function getId()
    {
        return $this->localityId;
    }

    public function getName()
    {
        return $this->localityName;
    }
    
    public function getZoneId()
    {
        return $this->zoneId;
    }
    
    public function getCityId()
    {
        return $this->cityId;
    }
    
    public function getStateId()
    {
        return $this->stateId;
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